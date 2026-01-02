<?php
/**
 * Admin Interface Class
 * 
 * Provides the admin dashboard interface for managing form publishing
 * 
 * @package FormPublishingSystem
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Form_Publishing_Admin_Interface {
    
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
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_init', array($this, 'handle_form_actions'));
    }
    
    /**
     * Add admin menu pages
     */
    public function add_admin_menu() {
        add_menu_page(
            'Form Publishing',
            'Form Publishing',
            'edit_posts',
            'form-publishing',
            array($this, 'render_forms_page'),
            'dashicons-forms',
            30
        );
        
        add_submenu_page(
            'form-publishing',
            'All Forms',
            'All Forms',
            'edit_posts',
            'form-publishing',
            array($this, 'render_forms_page')
        );
        
        add_submenu_page(
            'form-publishing',
            'Published Forms',
            'Published Forms',
            'edit_posts',
            'form-publishing-published',
            array($this, 'render_published_forms_page')
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'form-publishing') === false) {
            return;
        }
        
        wp_enqueue_style('form-publishing-admin', 
            plugins_url('assets/css/admin.css', dirname(__FILE__)),
            array(),
            '1.0.0'
        );
        
        wp_enqueue_script('form-publishing-admin',
            plugins_url('assets/js/admin.js', dirname(__FILE__)),
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_localize_script('form-publishing-admin', 'formPublishingData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('form_publishing_nonce'),
            'restUrl' => rest_url('form-publishing/v1'),
            'restNonce' => wp_create_nonce('wp_rest')
        ));
    }
    
    /**
     * Handle form actions
     */
    public function handle_form_actions() {
        if (!isset($_GET['action']) || !isset($_GET['form_id'])) {
            return;
        }
        
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'form_action')) {
            wp_die('Security check failed');
        }
        
        $form_id = intval($_GET['form_id']);
        $action = sanitize_text_field($_GET['action']);
        $user_id = get_current_user_id();
        
        $result = null;
        $redirect_url = admin_url('admin.php?page=form-publishing');
        
        switch ($action) {
            case 'publish':
                if ($this->publisher->user_can_publish()) {
                    $result = $this->publisher->publish_form($form_id, $user_id);
                    $message = is_wp_error($result) ? 'error' : 'published';
                }
                break;
                
            case 'unpublish':
                if ($this->publisher->user_can_publish()) {
                    $reason = isset($_GET['reason']) ? sanitize_text_field($_GET['reason']) : '';
                    $result = $this->publisher->unpublish_form($form_id, $user_id, $reason);
                    $message = is_wp_error($result) ? 'error' : 'unpublished';
                }
                break;
        }
        
        if ($result !== null) {
            $redirect_url = add_query_arg('message', $message, $redirect_url);
            if (is_wp_error($result)) {
                $redirect_url = add_query_arg('error_message', $result->get_error_message(), $redirect_url);
            }
            wp_redirect($redirect_url);
            exit;
        }
    }
    
    /**
     * Render forms page
     */
    public function render_forms_page() {
        global $wpdb;
        
        $forms_table = $wpdb->prefix . 'training_forms';
        $forms = $wpdb->get_results("SELECT * FROM {$forms_table} ORDER BY created_at DESC", ARRAY_A);
        
        $this->render_admin_notices();
        ?>
        <div class="wrap form-publishing-admin">
            <h1 class="wp-heading-inline">All Forms</h1>
            <a href="#" class="page-title-action" id="add-new-form">Add New Form</a>
            <hr class="wp-header-end">
            
            <div class="form-publishing-stats">
                <?php $this->render_statistics(); ?>
            </div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Published Date</th>
                        <th>Views</th>
                        <th>Submissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($forms)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px;">
                                <p>No forms found. Create your first form to get started!</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($forms as $form): ?>
                            <tr>
                                <td><?php echo esc_html($form['id']); ?></td>
                                <td><strong><?php echo esc_html($form['title']); ?></strong></td>
                                <td><?php echo $this->render_status_badge($form['status']); ?></td>
                                <td><?php echo esc_html(date('Y-m-d H:i', strtotime($form['created_at']))); ?></td>
                                <td><?php echo $form['published_date'] ? esc_html(date('Y-m-d H:i', strtotime($form['published_date']))) : '—'; ?></td>
                                <td><?php echo esc_html($form['view_count']); ?></td>
                                <td><?php echo esc_html($form['submission_count']); ?></td>
                                <td><?php echo $this->render_action_links($form); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    /**
     * Render published forms page
     */
    public function render_published_forms_page() {
        $forms = $this->publisher->get_published_forms();
        
        ?>
        <div class="wrap form-publishing-admin">
            <h1>Published Forms</h1>
            <hr class="wp-header-end">
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Published Date</th>
                        <th>Published By</th>
                        <th>Views</th>
                        <th>Submissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($forms)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <p>No published forms yet.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($forms as $form): ?>
                            <tr>
                                <td><?php echo esc_html($form['id']); ?></td>
                                <td><strong><?php echo esc_html($form['title']); ?></strong></td>
                                <td><?php echo esc_html(date('Y-m-d H:i', strtotime($form['published_date']))); ?></td>
                                <td><?php echo esc_html(get_userdata($form['published_by'])->display_name); ?></td>
                                <td><?php echo esc_html($form['view_count']); ?></td>
                                <td><?php echo esc_html($form['submission_count']); ?></td>
                                <td><?php echo $this->render_action_links($form); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    
    /**
     * Render statistics
     */
    private function render_statistics() {
        global $wpdb;
        
        $forms_table = $wpdb->prefix . 'training_forms';
        
        $total = $wpdb->get_var("SELECT COUNT(*) FROM {$forms_table}");
        $published = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$forms_table} WHERE status = %s",
            Form_Publisher::STATUS_PUBLISHED
        ));
        $draft = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$forms_table} WHERE status = %s",
            Form_Publisher::STATUS_DRAFT
        ));
        $scheduled = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$forms_table} WHERE status = %s",
            Form_Publisher::STATUS_SCHEDULED
        ));
        
        ?>
        <div class="form-stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo esc_html($total); ?></div>
                <div class="stat-label">Total Forms</div>
            </div>
            <div class="stat-card stat-published">
                <div class="stat-number"><?php echo esc_html($published); ?></div>
                <div class="stat-label">Published</div>
            </div>
            <div class="stat-card stat-draft">
                <div class="stat-number"><?php echo esc_html($draft); ?></div>
                <div class="stat-label">Drafts</div>
            </div>
            <div class="stat-card stat-scheduled">
                <div class="stat-number"><?php echo esc_html($scheduled); ?></div>
                <div class="stat-label">Scheduled</div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render status badge
     */
    private function render_status_badge($status) {
        $label = Form_Publisher::get_status_label($status);
        $class = 'status-badge status-' . esc_attr($status);
        return '<span class="' . $class . '">' . esc_html($label) . '</span>';
    }
    
    /**
     * Render action links
     */
    private function render_action_links($form) {
        $actions = array();
        $nonce = wp_create_nonce('form_action');
        
        if ($form['status'] === Form_Publisher::STATUS_DRAFT || 
            $form['status'] === Form_Publisher::STATUS_UNPUBLISHED) {
            if ($this->publisher->user_can_publish()) {
                $publish_url = wp_nonce_url(
                    admin_url('admin.php?page=form-publishing&action=publish&form_id=' . $form['id']),
                    'form_action'
                );
                $actions[] = '<a href="' . esc_url($publish_url) . '" class="button button-primary button-small">Publish</a>';
            }
        }
        
        if ($form['status'] === Form_Publisher::STATUS_PUBLISHED) {
            if ($this->publisher->user_can_publish()) {
                $unpublish_url = wp_nonce_url(
                    admin_url('admin.php?page=form-publishing&action=unpublish&form_id=' . $form['id']),
                    'form_action'
                );
                $actions[] = '<a href="' . esc_url($unpublish_url) . '" class="button button-small">Unpublish</a>';
            }
        }
        
        $actions[] = '<a href="#" class="view-history" data-form-id="' . esc_attr($form['id']) . '">History</a>';
        
        return implode(' | ', $actions);
    }
    
    /**
     * Render admin notices
     */
    private function render_admin_notices() {
        if (!isset($_GET['message'])) {
            return;
        }
        
        $message = sanitize_text_field($_GET['message']);
        $notices = array(
            'published' => array('success', 'Form published successfully!'),
            'unpublished' => array('success', 'Form unpublished successfully!'),
            'scheduled' => array('success', 'Form scheduled successfully!'),
            'error' => array('error', isset($_GET['error_message']) ? sanitize_text_field($_GET['error_message']) : 'An error occurred.')
        );
        
        if (isset($notices[$message])) {
            list($type, $text) = $notices[$message];
            echo '<div class="notice notice-' . esc_attr($type) . ' is-dismissible"><p>' . esc_html($text) . '</p></div>';
        }
    }
}
