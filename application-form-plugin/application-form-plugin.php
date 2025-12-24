<?php
/**
 * Plugin Name: Application Form Plugin
 * Plugin URI: https://yoursite.com/
 * Description: A comprehensive application form plugin with document uploads for course registration
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yoursite.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: application-form-plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('AFP_VERSION', '1.0.0');
define('AFP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AFP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AFP_UPLOAD_DIR', wp_upload_dir()['basedir'] . '/application-documents/');
define('AFP_UPLOAD_URL', wp_upload_dir()['baseurl'] . '/application-documents/');

/**
 * Main Plugin Class
 */
class Application_Form_Plugin {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Initialize plugin
        add_action('plugins_loaded', array($this, 'init'));
    }
    
    /**
     * Initialize the plugin
     */
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain('application-form-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Include required files
        $this->include_files();
        
        // Register hooks
        $this->register_hooks();
    }
    
    /**
     * Include required files
     */
    private function include_files() {
        require_once AFP_PLUGIN_DIR . 'includes/class-database.php';
        require_once AFP_PLUGIN_DIR . 'includes/class-form-handler.php';
        require_once AFP_PLUGIN_DIR . 'includes/class-admin-page.php';
        require_once AFP_PLUGIN_DIR . 'includes/class-email-handler.php';
    }
    
    /**
     * Register WordPress hooks
     */
    private function register_hooks() {
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Register shortcode
        add_shortcode('application_form', array($this, 'render_form_shortcode'));
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Create database table
        AFP_Database::create_table();
        
        // Create upload directory
        if (!file_exists(AFP_UPLOAD_DIR)) {
            wp_mkdir_p(AFP_UPLOAD_DIR);
            
            // Add .htaccess for security
            $htaccess_content = "Options -Indexes\n<Files *.php>\ndeny from all\n</Files>";
            file_put_contents(AFP_UPLOAD_DIR . '.htaccess', $htaccess_content);
        }
        
        // Set default options
        add_option('afp_admin_email', get_option('admin_email'));
        add_option('afp_enable_notifications', '1');
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        if (has_shortcode(get_post()->post_content ?? '', 'application_form')) {
            wp_enqueue_style('afp-frontend', AFP_PLUGIN_URL . 'assets/css/frontend.css', array(), AFP_VERSION);
            wp_enqueue_script('afp-frontend', AFP_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), AFP_VERSION, true);
            
            // Localize script
            wp_localize_script('afp-frontend', 'afpData', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('afp_form_nonce')
            ));
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'application-forms') !== false) {
            wp_enqueue_style('afp-admin', AFP_PLUGIN_URL . 'assets/css/admin.css', array(), AFP_VERSION);
            wp_enqueue_script('afp-admin', AFP_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), AFP_VERSION, true);
        }
    }
    
    /**
     * Render form shortcode
     */
    public function render_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => 'Course Application Form',
            'redirect' => ''
        ), $atts);
        
        ob_start();
        AFP_Form_Handler::render_form($atts);
        return ob_get_clean();
    }
}

// Initialize the plugin
new Application_Form_Plugin();
