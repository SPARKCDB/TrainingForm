<?php
/**
 * REST API Class
 * 
 * Provides REST API endpoints for form publishing operations
 * 
 * @package FormPublishingSystem
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Form_Publishing_REST_API {
    
    /**
     * API namespace
     */
    const NAMESPACE = 'form-publishing/v1';
    
    /**
     * Form publisher instance
     *
     * @var Form_Publisher
     */
    private $publisher;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->publisher = new Form_Publisher();
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    
    /**
     * Register REST API routes
     */
    public function register_routes() {
        // Get all forms
        register_rest_route(self::NAMESPACE, '/forms', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_forms'),
            'permission_callback' => array($this, 'check_read_permission')
        ));
        
        // Get single form
        register_rest_route(self::NAMESPACE, '/forms/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_form'),
            'permission_callback' => array($this, 'check_read_permission')
        ));
        
        // Publish form
        register_rest_route(self::NAMESPACE, '/forms/(?P<id>\d+)/publish', array(
            'methods' => 'POST',
            'callback' => array($this, 'publish_form'),
            'permission_callback' => array($this, 'check_publish_permission'),
            'args' => array(
                'publish_date' => array(
                    'type' => 'string',
                    'format' => 'date-time',
                    'required' => false
                )
            )
        ));
        
        // Unpublish form
        register_rest_route(self::NAMESPACE, '/forms/(?P<id>\d+)/unpublish', array(
            'methods' => 'POST',
            'callback' => array($this, 'unpublish_form'),
            'permission_callback' => array($this, 'check_publish_permission'),
            'args' => array(
                'reason' => array(
                    'type' => 'string',
                    'required' => false
                )
            )
        ));
        
        // Schedule form
        register_rest_route(self::NAMESPACE, '/forms/(?P<id>\d+)/schedule', array(
            'methods' => 'POST',
            'callback' => array($this, 'schedule_form'),
            'permission_callback' => array($this, 'check_publish_permission'),
            'args' => array(
                'scheduled_date' => array(
                    'type' => 'string',
                    'format' => 'date-time',
                    'required' => true
                )
            )
        ));
        
        // Get published forms
        register_rest_route(self::NAMESPACE, '/forms/published', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_published_forms'),
            'permission_callback' => '__return_true' // Public endpoint
        ));
        
        // Get publishing history
        register_rest_route(self::NAMESPACE, '/forms/(?P<id>\d+)/history', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_history'),
            'permission_callback' => array($this, 'check_read_permission')
        ));
    }
    
    /**
     * Get all forms
     * 
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Response object
     */
    public function get_forms($request) {
        global $wpdb;
        
        $status = $request->get_param('status');
        $page = $request->get_param('page') ?: 1;
        $per_page = $request->get_param('per_page') ?: 20;
        $offset = ($page - 1) * $per_page;
        
        $forms_table = $wpdb->prefix . 'training_forms';
        
        $where = '';
        if ($status) {
            $where = $wpdb->prepare("WHERE status = %s", $status);
        }
        
        $forms = $wpdb->get_results(
            "SELECT * FROM {$forms_table} {$where} 
            ORDER BY created_at DESC 
            LIMIT {$per_page} OFFSET {$offset}",
            ARRAY_A
        );
        
        $total = $wpdb->get_var("SELECT COUNT(*) FROM {$forms_table} {$where}");
        
        return new WP_REST_Response(array(
            'forms' => $forms,
            'total' => (int) $total,
            'page' => (int) $page,
            'per_page' => (int) $per_page,
            'total_pages' => ceil($total / $per_page)
        ), 200);
    }
    
    /**
     * Get single form
     * 
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Response object
     */
    public function get_form($request) {
        $form_id = $request->get_param('id');
        $form = $this->publisher->get_form($form_id);
        
        if (!$form) {
            return new WP_Error('form_not_found', 'Form not found', array('status' => 404));
        }
        
        return new WP_REST_Response($form, 200);
    }
    
    /**
     * Publish form
     * 
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Response object
     */
    public function publish_form($request) {
        $form_id = $request->get_param('id');
        $publish_date = $request->get_param('publish_date');
        $user_id = get_current_user_id();
        
        $result = $this->publisher->publish_form($form_id, $user_id, $publish_date);
        
        if (is_wp_error($result)) {
            return new WP_REST_Response(array(
                'error' => $result->get_error_code(),
                'message' => $result->get_error_message()
            ), 400);
        }
        
        return new WP_REST_Response(array(
            'success' => true,
            'message' => 'Form published successfully',
            'form' => $this->publisher->get_form($form_id)
        ), 200);
    }
    
    /**
     * Unpublish form
     * 
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Response object
     */
    public function unpublish_form($request) {
        $form_id = $request->get_param('id');
        $reason = $request->get_param('reason') ?: '';
        $user_id = get_current_user_id();
        
        $result = $this->publisher->unpublish_form($form_id, $user_id, $reason);
        
        if (is_wp_error($result)) {
            return new WP_REST_Response(array(
                'error' => $result->get_error_code(),
                'message' => $result->get_error_message()
            ), 400);
        }
        
        return new WP_REST_Response(array(
            'success' => true,
            'message' => 'Form unpublished successfully',
            'form' => $this->publisher->get_form($form_id)
        ), 200);
    }
    
    /**
     * Schedule form
     * 
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Response object
     */
    public function schedule_form($request) {
        $form_id = $request->get_param('id');
        $scheduled_date = $request->get_param('scheduled_date');
        $user_id = get_current_user_id();
        
        $result = $this->publisher->schedule_form($form_id, $user_id, $scheduled_date);
        
        if (is_wp_error($result)) {
            return new WP_REST_Response(array(
                'error' => $result->get_error_code(),
                'message' => $result->get_error_message()
            ), 400);
        }
        
        return new WP_REST_Response(array(
            'success' => true,
            'message' => 'Form scheduled successfully',
            'form' => $this->publisher->get_form($form_id)
        ), 200);
    }
    
    /**
     * Get published forms
     * 
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Response object
     */
    public function get_published_forms($request) {
        $forms = $this->publisher->get_published_forms();
        
        return new WP_REST_Response(array(
            'forms' => $forms,
            'count' => count($forms)
        ), 200);
    }
    
    /**
     * Get publishing history
     * 
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Response object
     */
    public function get_history($request) {
        $form_id = $request->get_param('id');
        $history = $this->publisher->get_publishing_history($form_id);
        
        return new WP_REST_Response(array(
            'history' => $history,
            'count' => count($history)
        ), 200);
    }
    
    /**
     * Check read permission
     * 
     * @return bool Permission status
     */
    public function check_read_permission() {
        return current_user_can('edit_posts');
    }
    
    /**
     * Check publish permission
     * 
     * @return bool Permission status
     */
    public function check_publish_permission() {
        return $this->publisher->user_can_publish();
    }
}
