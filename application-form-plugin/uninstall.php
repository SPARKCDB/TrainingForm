<?php
/**
 * Uninstall Script
 * Fires when the plugin is uninstalled
 */

// Exit if uninstall not called from WordPress
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

// Define table name
$table_name = $wpdb->prefix . 'afp_applications';

// Delete the database table
$wpdb->query("DROP TABLE IF EXISTS $table_name");

// Delete plugin options
delete_option('afp_admin_email');
delete_option('afp_enable_notifications');

// Delete uploaded files
$upload_dir = wp_upload_dir()['basedir'] . '/application-documents/';

if (is_dir($upload_dir)) {
    // Get all files in directory
    $files = glob($upload_dir . '*');
    
    // Delete all files
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    
    // Remove directory
    rmdir($upload_dir);
}

// Clear any cached data
wp_cache_flush();
