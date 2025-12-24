<?php
/**
 * Database Handler Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class AFP_Database {
    
    private static $table_name = 'afp_applications';
    
    /**
     * Create database table
     */
    public static function create_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::$table_name;
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            full_name varchar(255) NOT NULL,
            nric_no varchar(50) NOT NULL,
            contact_no varchar(50) NOT NULL,
            email varchar(255) NOT NULL,
            home_address text NOT NULL,
            course_interested varchar(255) NOT NULL,
            nric_front_file varchar(255) DEFAULT NULL,
            nric_back_file varchar(255) DEFAULT NULL,
            birth_certificate_file varchar(255) DEFAULT NULL,
            bank_statement_file varchar(255) DEFAULT NULL,
            status varchar(50) DEFAULT 'pending',
            notes text DEFAULT NULL,
            ip_address varchar(100) DEFAULT NULL,
            user_agent text DEFAULT NULL,
            submitted_date datetime DEFAULT CURRENT_TIMESTAMP,
            updated_date datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY email (email),
            KEY nric_no (nric_no),
            KEY status (status),
            KEY submitted_date (submitted_date)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Insert new application
     */
    public static function insert_application($data) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::$table_name;
        
        $insert_data = array(
            'full_name' => sanitize_text_field($data['full_name']),
            'nric_no' => sanitize_text_field($data['nric_no']),
            'contact_no' => sanitize_text_field($data['contact_no']),
            'email' => sanitize_email($data['email']),
            'home_address' => sanitize_textarea_field($data['home_address']),
            'course_interested' => sanitize_text_field($data['course_interested']),
            'nric_front_file' => isset($data['nric_front_file']) ? sanitize_text_field($data['nric_front_file']) : null,
            'nric_back_file' => isset($data['nric_back_file']) ? sanitize_text_field($data['nric_back_file']) : null,
            'birth_certificate_file' => isset($data['birth_certificate_file']) ? sanitize_text_field($data['birth_certificate_file']) : null,
            'bank_statement_file' => isset($data['bank_statement_file']) ? sanitize_text_field($data['bank_statement_file']) : null,
            'ip_address' => self::get_client_ip(),
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '',
            'status' => 'pending'
        );
        
        $result = $wpdb->insert($table_name, $insert_data);
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        return false;
    }
    
    /**
     * Get all applications
     */
    public static function get_applications($args = array()) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::$table_name;
        
        $defaults = array(
            'status' => '',
            'orderby' => 'submitted_date',
            'order' => 'DESC',
            'limit' => 20,
            'offset' => 0,
            'search' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where = '1=1';
        
        if (!empty($args['status'])) {
            $where .= $wpdb->prepare(' AND status = %s', $args['status']);
        }
        
        if (!empty($args['search'])) {
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $where .= $wpdb->prepare(' AND (full_name LIKE %s OR email LIKE %s OR nric_no LIKE %s OR contact_no LIKE %s)', $search, $search, $search, $search);
        }
        
        $orderby = sanitize_sql_orderby($args['orderby'] . ' ' . $args['order']);
        $limit = absint($args['limit']);
        $offset = absint($args['offset']);
        
        $sql = "SELECT * FROM $table_name WHERE $where ORDER BY $orderby LIMIT $limit OFFSET $offset";
        
        return $wpdb->get_results($sql);
    }
    
    /**
     * Get application by ID
     */
    public static function get_application($id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::$table_name;
        
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
    }
    
    /**
     * Update application status
     */
    public static function update_status($id, $status, $notes = '') {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::$table_name;
        
        return $wpdb->update(
            $table_name,
            array(
                'status' => sanitize_text_field($status),
                'notes' => sanitize_textarea_field($notes)
            ),
            array('id' => absint($id))
        );
    }
    
    /**
     * Delete application
     */
    public static function delete_application($id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::$table_name;
        
        // Get application to delete files
        $application = self::get_application($id);
        
        if ($application) {
            // Delete associated files
            $files = array(
                $application->nric_front_file,
                $application->nric_back_file,
                $application->birth_certificate_file,
                $application->bank_statement_file
            );
            
            foreach ($files as $file) {
                if (!empty($file) && file_exists(AFP_UPLOAD_DIR . $file)) {
                    unlink(AFP_UPLOAD_DIR . $file);
                }
            }
        }
        
        return $wpdb->delete($table_name, array('id' => absint($id)));
    }
    
    /**
     * Get total count
     */
    public static function get_total_count($status = '') {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::$table_name;
        
        if (empty($status)) {
            return $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        } else {
            return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE status = %s", $status));
        }
    }
    
    /**
     * Get client IP address
     */
    private static function get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }
}
