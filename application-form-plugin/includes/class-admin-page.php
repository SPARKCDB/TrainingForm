<?php
/**
 * Admin Page Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class AFP_Admin_Page {
    
    /**
     * Initialize admin page
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_menu_pages'));
        add_action('admin_post_afp_update_status', array(__CLASS__, 'handle_status_update'));
        add_action('admin_post_afp_delete_application', array(__CLASS__, 'handle_delete'));
        add_action('admin_post_afp_export_csv', array(__CLASS__, 'export_to_csv'));
    }
    
    /**
     * Add admin menu pages
     */
    public static function add_menu_pages() {
        add_menu_page(
            'Application Forms',
            'Application Forms',
            'manage_options',
            'application-forms',
            array(__CLASS__, 'render_list_page'),
            'dashicons-feedback',
            30
        );
        
        add_submenu_page(
            'application-forms',
            'View Application',
            'All Applications',
            'manage_options',
            'application-forms',
            array(__CLASS__, 'render_list_page')
        );
        
        add_submenu_page(
            'application-forms',
            'Settings',
            'Settings',
            'manage_options',
            'application-forms-settings',
            array(__CLASS__, 'render_settings_page')
        );
    }
    
    /**
     * Render applications list page
     */
    public static function render_list_page() {
        // Get filter parameters
        $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
        $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
        $per_page = 20;
        
        // Check if viewing single application
        if (isset($_GET['action']) && $_GET['action'] === 'view' && isset($_GET['id'])) {
            self::render_single_application(absint($_GET['id']));
            return;
        }
        
        // Get applications
        $args = array(
            'status' => $status,
            'search' => $search,
            'limit' => $per_page,
            'offset' => ($paged - 1) * $per_page
        );
        
        $applications = AFP_Database::get_applications($args);
        $total_items = AFP_Database::get_total_count($status);
        $total_pages = ceil($total_items / $per_page);
        
        // Get counts for status filters
        $pending_count = AFP_Database::get_total_count('pending');
        $approved_count = AFP_Database::get_total_count('approved');
        $rejected_count = AFP_Database::get_total_count('rejected');
        
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Application Forms</h1>
            <a href="<?php echo admin_url('admin-post.php?action=afp_export_csv' . ($status ? '&status=' . $status : '')); ?>" class="page-title-action">Export to CSV</a>
            <hr class="wp-header-end">
            
            <?php if (isset($_GET['message'])): ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo esc_html(self::get_message($_GET['message'])); ?></p>
                </div>
            <?php endif; ?>
            
            <ul class="subsubsub">
                <li>
                    <a href="<?php echo admin_url('admin.php?page=application-forms'); ?>" <?php echo empty($status) ? 'class="current"' : ''; ?>>
                        All <span class="count">(<?php echo $total_items; ?>)</span>
                    </a> |
                </li>
                <li>
                    <a href="<?php echo admin_url('admin.php?page=application-forms&status=pending'); ?>" <?php echo $status === 'pending' ? 'class="current"' : ''; ?>>
                        Pending <span class="count">(<?php echo $pending_count; ?>)</span>
                    </a> |
                </li>
                <li>
                    <a href="<?php echo admin_url('admin.php?page=application-forms&status=approved'); ?>" <?php echo $status === 'approved' ? 'class="current"' : ''; ?>>
                        Approved <span class="count">(<?php echo $approved_count; ?>)</span>
                    </a> |
                </li>
                <li>
                    <a href="<?php echo admin_url('admin.php?page=application-forms&status=rejected'); ?>" <?php echo $status === 'rejected' ? 'class="current"' : ''; ?>>
                        Rejected <span class="count">(<?php echo $rejected_count; ?>)</span>
                    </a>
                </li>
            </ul>
            
            <form method="get" action="">
                <input type="hidden" name="page" value="application-forms">
                <?php if ($status): ?>
                    <input type="hidden" name="status" value="<?php echo esc_attr($status); ?>">
                <?php endif; ?>
                <p class="search-box">
                    <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search applications...">
                    <input type="submit" class="button" value="Search">
                </p>
            </form>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Full Name</th>
                        <th>NRIC No</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($applications)): ?>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td><?php echo esc_html($app->id); ?></td>
                                <td><strong><?php echo esc_html($app->full_name); ?></strong></td>
                                <td><?php echo esc_html($app->nric_no); ?></td>
                                <td><?php echo esc_html($app->email); ?></td>
                                <td><?php echo esc_html($app->contact_no); ?></td>
                                <td><?php echo esc_html($app->course_interested); ?></td>
                                <td>
                                    <span class="afp-status-badge afp-status-<?php echo esc_attr($app->status); ?>">
                                        <?php echo esc_html(ucfirst($app->status)); ?>
                                    </span>
                                </td>
                                <td><?php echo date('Y-m-d H:i', strtotime($app->submitted_date)); ?></td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=application-forms&action=view&id=' . $app->id); ?>" class="button button-small">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center;">No applications found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <?php if ($total_pages > 1): ?>
                <div class="tablenav">
                    <div class="tablenav-pages">
                        <?php
                        $page_links = paginate_links(array(
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'total' => $total_pages,
                            'current' => $paged
                        ));
                        echo $page_links;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Render single application view
     */
    private static function render_single_application($id) {
        $application = AFP_Database::get_application($id);
        
        if (!$application) {
            echo '<div class="wrap"><h1>Application Not Found</h1><p>The requested application could not be found.</p></div>';
            return;
        }
        
        ?>
        <div class="wrap">
            <h1>View Application #<?php echo esc_html($application->id); ?></h1>
            <a href="<?php echo admin_url('admin.php?page=application-forms'); ?>" class="page-title-action">&larr; Back to List</a>
            
            <div class="afp-application-view">
                <div class="afp-view-section">
                    <h2>Personal Information</h2>
                    <table class="form-table">
                        <tr>
                            <th>Full Name:</th>
                            <td><?php echo esc_html($application->full_name); ?></td>
                        </tr>
                        <tr>
                            <th>NRIC No:</th>
                            <td><?php echo esc_html($application->nric_no); ?></td>
                        </tr>
                        <tr>
                            <th>Contact No:</th>
                            <td><?php echo esc_html($application->contact_no); ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><a href="mailto:<?php echo esc_attr($application->email); ?>"><?php echo esc_html($application->email); ?></a></td>
                        </tr>
                        <tr>
                            <th>Home Address:</th>
                            <td><?php echo nl2br(esc_html($application->home_address)); ?></td>
                        </tr>
                        <tr>
                            <th>Course Interested:</th>
                            <td><?php echo esc_html($application->course_interested); ?></td>
                        </tr>
                    </table>
                </div>
                
                <div class="afp-view-section">
                    <h2>Application Documents</h2>
                    <table class="form-table">
                        <tr>
                            <th>NRIC Front:</th>
                            <td>
                                <?php if ($application->nric_front_file): ?>
                                    <a href="<?php echo AFP_UPLOAD_URL . $application->nric_front_file; ?>" target="_blank" class="button">View File</a>
                                <?php else: ?>
                                    <em>No file uploaded</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>NRIC Back:</th>
                            <td>
                                <?php if ($application->nric_back_file): ?>
                                    <a href="<?php echo AFP_UPLOAD_URL . $application->nric_back_file; ?>" target="_blank" class="button">View File</a>
                                <?php else: ?>
                                    <em>No file uploaded</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Birth Certificate:</th>
                            <td>
                                <?php if ($application->birth_certificate_file): ?>
                                    <a href="<?php echo AFP_UPLOAD_URL . $application->birth_certificate_file; ?>" target="_blank" class="button">View File</a>
                                <?php else: ?>
                                    <em>No file uploaded</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Bank Statement:</th>
                            <td>
                                <?php if ($application->bank_statement_file): ?>
                                    <a href="<?php echo AFP_UPLOAD_URL . $application->bank_statement_file; ?>" target="_blank" class="button">View File</a>
                                <?php else: ?>
                                    <em>No file uploaded</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="afp-view-section">
                    <h2>Application Status</h2>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <?php wp_nonce_field('afp_update_status'); ?>
                        <input type="hidden" name="action" value="afp_update_status">
                        <input type="hidden" name="application_id" value="<?php echo esc_attr($application->id); ?>">
                        
                        <table class="form-table">
                            <tr>
                                <th>Current Status:</th>
                                <td>
                                    <select name="status" class="regular-text">
                                        <option value="pending" <?php selected($application->status, 'pending'); ?>>Pending</option>
                                        <option value="approved" <?php selected($application->status, 'approved'); ?>>Approved</option>
                                        <option value="rejected" <?php selected($application->status, 'rejected'); ?>>Rejected</option>
                                        <option value="under_review" <?php selected($application->status, 'under_review'); ?>>Under Review</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Notes:</th>
                                <td>
                                    <textarea name="notes" rows="4" class="large-text"><?php echo esc_textarea($application->notes); ?></textarea>
                                </td>
                            </tr>
                        </table>
                        
                        <p class="submit">
                            <button type="submit" class="button button-primary">Update Status</button>
                        </p>
                    </form>
                </div>
                
                <div class="afp-view-section">
                    <h2>Additional Information</h2>
                    <table class="form-table">
                        <tr>
                            <th>Submitted Date:</th>
                            <td><?php echo date('F j, Y \a\t g:i A', strtotime($application->submitted_date)); ?></td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td><?php echo date('F j, Y \a\t g:i A', strtotime($application->updated_date)); ?></td>
                        </tr>
                        <tr>
                            <th>IP Address:</th>
                            <td><?php echo esc_html($application->ip_address); ?></td>
                        </tr>
                    </table>
                    
                    <p>
                        <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=afp_delete_application&id=' . $application->id), 'afp_delete_application'); ?>" 
                           class="button button-link-delete" 
                           onclick="return confirm('Are you sure you want to delete this application? This action cannot be undone.');">
                            Delete Application
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render settings page
     */
    public static function render_settings_page() {
        if (isset($_POST['afp_save_settings'])) {
            check_admin_referer('afp_settings');
            
            update_option('afp_admin_email', sanitize_email($_POST['admin_email']));
            update_option('afp_enable_notifications', isset($_POST['enable_notifications']) ? '1' : '0');
            
            echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
        }
        
        $admin_email = get_option('afp_admin_email', get_option('admin_email'));
        $enable_notifications = get_option('afp_enable_notifications', '1');
        
        ?>
        <div class="wrap">
            <h1>Application Form Settings</h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('afp_settings'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">Admin Email</th>
                        <td>
                            <input type="email" name="admin_email" value="<?php echo esc_attr($admin_email); ?>" class="regular-text">
                            <p class="description">Email address to receive new application notifications.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Email Notifications</th>
                        <td>
                            <label>
                                <input type="checkbox" name="enable_notifications" value="1" <?php checked($enable_notifications, '1'); ?>>
                                Enable email notifications for new applications
                            </label>
                        </td>
                    </tr>
                </table>
                
                <h2>Shortcode Usage</h2>
                <p>Use the following shortcode to display the application form on any page or post:</p>
                <code>[application_form]</code>
                
                <p>You can also customize the title:</p>
                <code>[application_form title="Custom Title Here"]</code>
                
                <p class="submit">
                    <input type="submit" name="afp_save_settings" class="button button-primary" value="Save Settings">
                </p>
            </form>
        </div>
        <?php
    }
    
    /**
     * Handle status update
     */
    public static function handle_status_update() {
        check_admin_referer('afp_update_status');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $application_id = isset($_POST['application_id']) ? absint($_POST['application_id']) : 0;
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        $notes = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';
        
        if ($application_id && $status) {
            AFP_Database::update_status($application_id, $status, $notes);
            
            wp_redirect(admin_url('admin.php?page=application-forms&action=view&id=' . $application_id . '&message=updated'));
            exit;
        }
        
        wp_redirect(admin_url('admin.php?page=application-forms'));
        exit;
    }
    
    /**
     * Handle application deletion
     */
    public static function handle_delete() {
        check_admin_referer('afp_delete_application');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $application_id = isset($_GET['id']) ? absint($_GET['id']) : 0;
        
        if ($application_id) {
            AFP_Database::delete_application($application_id);
        }
        
        wp_redirect(admin_url('admin.php?page=application-forms&message=deleted'));
        exit;
    }
    
    /**
     * Export applications to CSV
     */
    public static function export_to_csv() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
        
        $applications = AFP_Database::get_applications(array(
            'status' => $status,
            'limit' => 999999
        ));
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="applications-' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, array(
            'ID', 'Full Name', 'NRIC No', 'Contact No', 'Email', 'Home Address', 
            'Course Interested', 'Status', 'Submitted Date', 'IP Address'
        ));
        
        // Add data rows
        foreach ($applications as $app) {
            fputcsv($output, array(
                $app->id,
                $app->full_name,
                $app->nric_no,
                $app->contact_no,
                $app->email,
                $app->home_address,
                $app->course_interested,
                $app->status,
                $app->submitted_date,
                $app->ip_address
            ));
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Get message text
     */
    private static function get_message($message) {
        $messages = array(
            'updated' => 'Application status updated successfully.',
            'deleted' => 'Application deleted successfully.'
        );
        
        return isset($messages[$message]) ? $messages[$message] : '';
    }
}

// Initialize admin page
AFP_Admin_Page::init();
