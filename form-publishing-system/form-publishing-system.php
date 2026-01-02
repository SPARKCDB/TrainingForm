<?php
/**
 * Plugin Name: Form Publishing System
 * Plugin URI: https://github.com/SPARKCDB/TrainingForm
 * Description: A comprehensive form publishing system with status management, scheduling, and history tracking.
 * Version: 1.0.0
 * Author: SPARKCDB
 * Author URI: https://github.com/SPARKCDB
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: form-publishing-system
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * 
 * @package FormPublishingSystem
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('FORM_PUBLISHING_VERSION', '1.0.0');
define('FORM_PUBLISHING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FORM_PUBLISHING_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FORM_PUBLISHING_PLUGIN_FILE', __FILE__);

/**
 * Main Form Publishing System Class
 */
class Form_Publishing_System {
    
    /**
     * Singleton instance
     *
     * @var Form_Publishing_System
     */
    private static $instance = null;
    
    /**
     * Form publisher instance
     *
     * @var Form_Publisher
     */
    public $publisher;
    
    /**
     * REST API instance
     *
     * @var Form_Publishing_REST_API
     */
    public $rest_api;
    
    /**
     * Admin interface instance
     *
     * @var Form_Publishing_Admin_Interface
     */
    public $admin_interface;
    
    /**
     * Get singleton instance
     *
     * @return Form_Publishing_System
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
        $this->init_components();
    }
    
    /**
     * Load required dependencies
     */
    private function load_dependencies() {
        require_once FORM_PUBLISHING_PLUGIN_DIR . 'includes/class-database.php';
        require_once FORM_PUBLISHING_PLUGIN_DIR . 'includes/class-form-publisher.php';
        require_once FORM_PUBLISHING_PLUGIN_DIR . 'includes/class-rest-api.php';
        
        if (is_admin()) {
            require_once FORM_PUBLISHING_PLUGIN_DIR . 'includes/class-admin-interface.php';
        }
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        register_activation_hook(FORM_PUBLISHING_PLUGIN_FILE, array($this, 'activate'));
        register_deactivation_hook(FORM_PUBLISHING_PLUGIN_FILE, array($this, 'deactivate'));
        
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('form_publishing_cron', array($this, 'process_scheduled_forms'));
    }
    
    /**
     * Initialize components
     */
    private function init_components() {
        $this->publisher = new Form_Publisher();
        $this->rest_api = new Form_Publishing_REST_API();
        
        if (is_admin()) {
            $this->admin_interface = new Form_Publishing_Admin_Interface();
        }
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Create database tables
        Form_Publishing_Database::create_tables();
        
        // Schedule cron job for processing scheduled forms
        if (!wp_next_scheduled('form_publishing_cron')) {
            wp_schedule_event(time(), 'hourly', 'form_publishing_cron');
        }
        
        // Insert sample form
        Form_Publishing_Database::insert_sample_form();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clear scheduled cron job
        $timestamp = wp_next_scheduled('form_publishing_cron');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'form_publishing_cron');
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Load text domain for translations
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'form-publishing-system',
            false,
            dirname(plugin_basename(FORM_PUBLISHING_PLUGIN_FILE)) . '/languages/'
        );
    }
    
    /**
     * Process scheduled forms (cron callback)
     */
    public function process_scheduled_forms() {
        $publisher = new Form_Publisher();
        $count = $publisher->process_scheduled_forms();
        
        if ($count > 0) {
            error_log("Form Publishing System: Published {$count} scheduled form(s)");
        }
    }
    
    /**
     * Get plugin version
     *
     * @return string
     */
    public function get_version() {
        return FORM_PUBLISHING_VERSION;
    }
}

/**
 * Get main plugin instance
 *
 * @return Form_Publishing_System
 */
function form_publishing_system() {
    return Form_Publishing_System::get_instance();
}

// Initialize the plugin
form_publishing_system();
