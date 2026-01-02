<?php
/**
 * Uninstall Script
 * 
 * Fired when the plugin is uninstalled
 * 
 * @package FormPublishingSystem
 * @since 1.0.0
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Load plugin file
require_once plugin_dir_path(__FILE__) . 'includes/class-database.php';

// Option to keep data on uninstall
$keep_data = get_option('form_publishing_keep_data', false);

if (!$keep_data) {
    // Drop database tables
    Form_Publishing_Database::drop_tables();
    
    // Delete plugin options
    delete_option('form_publishing_db_version');
    delete_option('form_publishing_keep_data');
    
    // Clear any scheduled cron jobs
    $timestamp = wp_next_scheduled('form_publishing_cron');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'form_publishing_cron');
    }
    
    // Clear all cron schedules
    wp_clear_scheduled_hook('form_publishing_cron');
}
