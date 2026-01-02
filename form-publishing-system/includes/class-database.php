<?php
/**
 * Database Management Class
 * 
 * Handles database table creation and schema management
 * for the form publishing system
 * 
 * @package FormPublishingSystem
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Form_Publishing_Database {
    
    /**
     * Database version
     */
    const DB_VERSION = '1.0.0';
    
    /**
     * Create database tables
     * 
     * @return bool Success status
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        $forms_table = $wpdb->prefix . 'training_forms';
        $history_table = $wpdb->prefix . 'training_forms_publish_history';
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // Forms table
        $forms_sql = "CREATE TABLE $forms_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text,
            form_fields longtext,
            status varchar(20) NOT NULL DEFAULT 'draft',
            created_by bigint(20) UNSIGNED NOT NULL,
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            published_by bigint(20) UNSIGNED,
            published_date datetime,
            unpublished_by bigint(20) UNSIGNED,
            unpublished_date datetime,
            unpublish_reason text,
            scheduled_by bigint(20) UNSIGNED,
            scheduled_publish_date datetime,
            view_count bigint(20) DEFAULT 0,
            submission_count bigint(20) DEFAULT 0,
            PRIMARY KEY (id),
            KEY status (status),
            KEY created_by (created_by),
            KEY published_date (published_date),
            KEY scheduled_publish_date (scheduled_publish_date)
        ) $charset_collate;";
        
        // Publishing history table
        $history_sql = "CREATE TABLE $history_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            form_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED NOT NULL,
            action varchar(50) NOT NULL,
            previous_status varchar(20),
            notes text,
            action_date datetime NOT NULL,
            PRIMARY KEY (id),
            KEY form_id (form_id),
            KEY user_id (user_id),
            KEY action_date (action_date)
        ) $charset_collate;";
        
        dbDelta($forms_sql);
        dbDelta($history_sql);
        
        // Save database version
        update_option('form_publishing_db_version', self::DB_VERSION);
        
        return true;
    }
    
    /**
     * Drop database tables (for uninstall)
     * 
     * @return bool Success status
     */
    public static function drop_tables() {
        global $wpdb;
        
        $forms_table = $wpdb->prefix . 'training_forms';
        $history_table = $wpdb->prefix . 'training_forms_publish_history';
        
        $wpdb->query("DROP TABLE IF EXISTS $history_table");
        $wpdb->query("DROP TABLE IF EXISTS $forms_table");
        
        delete_option('form_publishing_db_version');
        
        return true;
    }
    
    /**
     * Check if tables exist
     * 
     * @return bool True if tables exist
     */
    public static function tables_exist() {
        global $wpdb;
        
        $forms_table = $wpdb->prefix . 'training_forms';
        $history_table = $wpdb->prefix . 'training_forms_publish_history';
        
        $forms_exists = $wpdb->get_var("SHOW TABLES LIKE '$forms_table'") === $forms_table;
        $history_exists = $wpdb->get_var("SHOW TABLES LIKE '$history_table'") === $history_table;
        
        return $forms_exists && $history_exists;
    }
    
    /**
     * Insert sample form for testing
     * 
     * @return int|false Form ID on success, false on failure
     */
    public static function insert_sample_form() {
        global $wpdb;
        
        $forms_table = $wpdb->prefix . 'training_forms';
        $current_user_id = get_current_user_id();
        
        if (!$current_user_id) {
            $current_user_id = 1; // Default to admin
        }
        
        $sample_fields = json_encode(array(
            array(
                'type' => 'text',
                'label' => 'Full Name',
                'name' => 'full_name',
                'required' => true
            ),
            array(
                'type' => 'email',
                'label' => 'Email Address',
                'name' => 'email',
                'required' => true
            ),
            array(
                'type' => 'tel',
                'label' => 'Phone Number',
                'name' => 'phone',
                'required' => true
            ),
            array(
                'type' => 'textarea',
                'label' => 'Why do you want to join this training?',
                'name' => 'motivation',
                'required' => true
            ),
            array(
                'type' => 'file',
                'label' => 'Upload Resume/CV',
                'name' => 'resume',
                'required' => false,
                'accept' => '.pdf,.doc,.docx'
            )
        ));
        
        $result = $wpdb->insert(
            $forms_table,
            array(
                'title' => 'Training Application Form',
                'description' => 'Application form for our comprehensive training program. Please fill out all required fields.',
                'form_fields' => $sample_fields,
                'status' => 'draft',
                'created_by' => $current_user_id,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%d', '%s', '%s')
        );
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        return false;
    }
}
