<?php
/**
 * Form Publisher Class
 * 
 * Handles all form publishing operations including:
 * - Publishing forms (making them live)
 * - Unpublishing forms (taking them offline)
 * - Managing form status transitions
 * - Tracking publishing history
 * 
 * @package FormPublishingSystem
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Form_Publisher {
    
    /**
     * Form status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_UNPUBLISHED = 'unpublished';
    const STATUS_SCHEDULED = 'scheduled';
    
    /**
     * Database instance
     *
     * @var wpdb
     */
    private $db;
    
    /**
     * Table name for forms
     *
     * @var string
     */
    private $forms_table;
    
    /**
     * Table name for publishing history
     *
     * @var string
     */
    private $history_table;
    
    /**
     * Constructor
     */
    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->forms_table = $wpdb->prefix . 'training_forms';
        $this->history_table = $wpdb->prefix . 'training_forms_publish_history';
    }
    
    /**
     * Publish a form
     * 
     * @param int $form_id The form ID to publish
     * @param int $user_id The user ID performing the action
     * @param string $publish_date Optional publish date (defaults to now)
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    public function publish_form($form_id, $user_id, $publish_date = null) {
        // Validate form exists
        $form = $this->get_form($form_id);
        if (!$form) {
            return new WP_Error('invalid_form', 'Form not found');
        }
        
        // Check if form can be published
        $validation = $this->validate_form_for_publishing($form_id);
        if (is_wp_error($validation)) {
            return $validation;
        }
        
        // Set publish date
        if ($publish_date === null) {
            $publish_date = current_time('mysql');
        }
        
        // Update form status
        $result = $this->db->update(
            $this->forms_table,
            array(
                'status' => self::STATUS_PUBLISHED,
                'published_date' => $publish_date,
                'published_by' => $user_id,
                'updated_at' => current_time('mysql')
            ),
            array('id' => $form_id),
            array('%s', '%s', '%d', '%s'),
            array('%d')
        );
        
        if ($result === false) {
            return new WP_Error('publish_failed', 'Failed to publish form');
        }
        
        // Log publishing action
        $this->log_publishing_action($form_id, $user_id, 'published', $form['status']);
        
        // Trigger action hook
        do_action('training_form_published', $form_id, $user_id);
        
        return true;
    }
    
    /**
     * Unpublish a form
     * 
     * @param int $form_id The form ID to unpublish
     * @param int $user_id The user ID performing the action
     * @param string $reason Optional reason for unpublishing
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    public function unpublish_form($form_id, $user_id, $reason = '') {
        // Validate form exists
        $form = $this->get_form($form_id);
        if (!$form) {
            return new WP_Error('invalid_form', 'Form not found');
        }
        
        // Check if form is currently published
        if ($form['status'] !== self::STATUS_PUBLISHED) {
            return new WP_Error('not_published', 'Form is not currently published');
        }
        
        // Update form status
        $result = $this->db->update(
            $this->forms_table,
            array(
                'status' => self::STATUS_UNPUBLISHED,
                'unpublished_date' => current_time('mysql'),
                'unpublished_by' => $user_id,
                'unpublish_reason' => $reason,
                'updated_at' => current_time('mysql')
            ),
            array('id' => $form_id),
            array('%s', '%s', '%d', '%s', '%s'),
            array('%d')
        );
        
        if ($result === false) {
            return new WP_Error('unpublish_failed', 'Failed to unpublish form');
        }
        
        // Log unpublishing action
        $this->log_publishing_action($form_id, $user_id, 'unpublished', $form['status'], $reason);
        
        // Trigger action hook
        do_action('training_form_unpublished', $form_id, $user_id, $reason);
        
        return true;
    }
    
    /**
     * Schedule a form for future publishing
     * 
     * @param int $form_id The form ID to schedule
     * @param int $user_id The user ID performing the action
     * @param string $scheduled_date The date to publish the form
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    public function schedule_form($form_id, $user_id, $scheduled_date) {
        // Validate form exists
        $form = $this->get_form($form_id);
        if (!$form) {
            return new WP_Error('invalid_form', 'Form not found');
        }
        
        // Validate scheduled date is in the future
        $scheduled_timestamp = strtotime($scheduled_date);
        if ($scheduled_timestamp <= current_time('timestamp')) {
            return new WP_Error('invalid_date', 'Scheduled date must be in the future');
        }
        
        // Update form status
        $result = $this->db->update(
            $this->forms_table,
            array(
                'status' => self::STATUS_SCHEDULED,
                'scheduled_publish_date' => $scheduled_date,
                'scheduled_by' => $user_id,
                'updated_at' => current_time('mysql')
            ),
            array('id' => $form_id),
            array('%s', '%s', '%d', '%s'),
            array('%d')
        );
        
        if ($result === false) {
            return new WP_Error('schedule_failed', 'Failed to schedule form');
        }
        
        // Log scheduling action
        $this->log_publishing_action($form_id, $user_id, 'scheduled', $form['status'], "Scheduled for: $scheduled_date");
        
        // Trigger action hook
        do_action('training_form_scheduled', $form_id, $user_id, $scheduled_date);
        
        return true;
    }
    
    /**
     * Process scheduled forms (should be called by cron)
     * 
     * @return int Number of forms published
     */
    public function process_scheduled_forms() {
        $current_time = current_time('mysql');
        
        // Get all scheduled forms that are due
        $scheduled_forms = $this->db->get_results($this->db->prepare(
            "SELECT id, scheduled_by FROM {$this->forms_table}
            WHERE status = %s AND scheduled_publish_date <= %s",
            self::STATUS_SCHEDULED,
            $current_time
        ));
        
        $published_count = 0;
        
        foreach ($scheduled_forms as $form) {
            $result = $this->publish_form($form->id, $form->scheduled_by);
            if (!is_wp_error($result)) {
                $published_count++;
            }
        }
        
        return $published_count;
    }
    
    /**
     * Validate form for publishing
     * 
     * @param int $form_id The form ID to validate
     * @return bool|WP_Error True if valid, WP_Error if not
     */
    private function validate_form_for_publishing($form_id) {
        $form = $this->get_form($form_id);
        
        // Check if form has required fields
        if (empty($form['title'])) {
            return new WP_Error('missing_title', 'Form must have a title');
        }
        
        if (empty($form['form_fields'])) {
            return new WP_Error('missing_fields', 'Form must have at least one field');
        }
        
        // Check if form is already published
        if ($form['status'] === self::STATUS_PUBLISHED) {
            return new WP_Error('already_published', 'Form is already published');
        }
        
        // Apply filters for custom validation
        $custom_validation = apply_filters('training_form_validate_for_publishing', true, $form_id, $form);
        
        return $custom_validation;
    }
    
    /**
     * Get form by ID
     * 
     * @param int $form_id The form ID
     * @return array|null Form data or null if not found
     */
    public function get_form($form_id) {
        return $this->db->get_row($this->db->prepare(
            "SELECT * FROM {$this->forms_table} WHERE id = %d",
            $form_id
        ), ARRAY_A);
    }
    
    /**
     * Get all published forms
     * 
     * @return array Array of published forms
     */
    public function get_published_forms() {
        return $this->db->get_results($this->db->prepare(
            "SELECT * FROM {$this->forms_table} WHERE status = %s ORDER BY published_date DESC",
            self::STATUS_PUBLISHED
        ), ARRAY_A);
    }
    
    /**
     * Get form publishing history
     * 
     * @param int $form_id The form ID
     * @return array Publishing history
     */
    public function get_publishing_history($form_id) {
        return $this->db->get_results($this->db->prepare(
            "SELECT * FROM {$this->history_table} WHERE form_id = %d ORDER BY action_date DESC",
            $form_id
        ), ARRAY_A);
    }
    
    /**
     * Log publishing action
     * 
     * @param int $form_id The form ID
     * @param int $user_id The user ID performing the action
     * @param string $action The action performed
     * @param string $previous_status Previous form status
     * @param string $notes Optional notes
     * @return bool Success status
     */
    private function log_publishing_action($form_id, $user_id, $action, $previous_status, $notes = '') {
        return $this->db->insert(
            $this->history_table,
            array(
                'form_id' => $form_id,
                'user_id' => $user_id,
                'action' => $action,
                'previous_status' => $previous_status,
                'notes' => $notes,
                'action_date' => current_time('mysql')
            ),
            array('%d', '%d', '%s', '%s', '%s', '%s')
        );
    }
    
    /**
     * Get form status label
     * 
     * @param string $status Status code
     * @return string Human-readable status label
     */
    public static function get_status_label($status) {
        $labels = array(
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_UNPUBLISHED => 'Unpublished',
            self::STATUS_SCHEDULED => 'Scheduled'
        );
        
        return isset($labels[$status]) ? $labels[$status] : 'Unknown';
    }
    
    /**
     * Check if user can publish forms
     * 
     * @param int $user_id User ID to check
     * @return bool True if user can publish
     */
    public function user_can_publish($user_id = null) {
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }
        
        return user_can($user_id, 'publish_posts') || user_can($user_id, 'manage_options');
    }
}
