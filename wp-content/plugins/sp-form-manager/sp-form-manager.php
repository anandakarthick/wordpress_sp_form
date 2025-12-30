<?php
/**
 * Plugin Name: SP Form Manager
 * Plugin URI: https://example.com/sp-form-manager
 * Description: Website template order system - Let customers choose templates, fill content, customize colors, and submit orders.
 * Version: 2.0.0
 * Author: Developer
 * License: GPL v2 or later
 * Text Domain: sp-form-manager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SPFM_VERSION', '2.0.0');
define('SPFM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SPFM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SPFM_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SPFM_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
class SP_Form_Manager {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    private function includes() {
        require_once SPFM_PLUGIN_DIR . 'includes/class-database.php';
        require_once SPFM_PLUGIN_DIR . 'includes/class-settings.php';
        require_once SPFM_PLUGIN_DIR . 'includes/class-auth.php';
        require_once SPFM_PLUGIN_DIR . 'includes/class-customers.php';
        require_once SPFM_PLUGIN_DIR . 'includes/class-themes.php';
        require_once SPFM_PLUGIN_DIR . 'includes/class-forms.php';
        require_once SPFM_PLUGIN_DIR . 'includes/class-share.php';
        require_once SPFM_PLUGIN_DIR . 'includes/class-ajax-handler.php';
        require_once SPFM_PLUGIN_DIR . 'admin/class-admin.php';
    }
    
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_action('init', array($this, 'init'));
        add_action('init', array($this, 'register_rewrite_rules'));
        add_filter('query_vars', array($this, 'add_query_vars'));
        add_action('template_redirect', array($this, 'handle_form_request'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Check if tables need to be created
        add_action('admin_init', array($this, 'check_tables'));
        
        // Flush rewrite rules if needed
        add_action('init', array($this, 'maybe_flush_rules'), 20);
        
        // Fallback: Handle form via GET parameter
        add_action('wp_loaded', array($this, 'handle_form_fallback'));
    }
    
    public function activate() {
        SPFM_Database::create_tables();
        $this->register_rewrite_rules();
        flush_rewrite_rules();
        update_option('spfm_flush_rules', true);
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    public function check_tables() {
        $db_version = get_option('spfm_db_version', '0');
        if (version_compare($db_version, '2.0.0', '<')) {
            SPFM_Database::create_tables();
        }
    }
    
    public function register_rewrite_rules() {
        add_rewrite_rule(
            '^spfm-form/([a-zA-Z0-9]+)/?$',
            'index.php?spfm_form_token=$matches[1]',
            'top'
        );
    }
    
    public function add_query_vars($vars) {
        $vars[] = 'spfm_form_token';
        return $vars;
    }
    
    public function maybe_flush_rules() {
        if (get_option('spfm_flush_rules', false)) {
            flush_rewrite_rules();
            delete_option('spfm_flush_rules');
        }
    }
    
    public function handle_form_request() {
        $token = get_query_var('spfm_form_token');
        
        if (!empty($token)) {
            $share_handler = SPFM_Share::get_instance();
            echo $share_handler->render_customer_form($token);
            exit;
        }
    }
    
    /**
     * Fallback handler using GET parameter
     * URL format: /?spfm_token=XXXXXX
     */
    public function handle_form_fallback() {
        if (isset($_GET['spfm_token']) && !empty($_GET['spfm_token'])) {
            $token = sanitize_text_field($_GET['spfm_token']);
            $share_handler = SPFM_Share::get_instance();
            echo $share_handler->render_customer_form($token);
            exit;
        }
    }
    
    public function init() {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
        
        // Initialize components
        SPFM_Settings::get_instance();
        SPFM_Auth::get_instance();
        SPFM_Admin::get_instance();
        SPFM_Share::get_instance();
        SPFM_Ajax_Handler::get_instance();
    }
    
    public function enqueue_frontend_assets() {
        wp_enqueue_style('spfm-frontend', SPFM_PLUGIN_URL . 'assets/css/frontend.css', array(), SPFM_VERSION);
        wp_enqueue_script('spfm-frontend', SPFM_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), SPFM_VERSION, true);
        
        wp_localize_script('spfm-frontend', 'spfm_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('spfm_nonce')
        ));
    }
    
    public function enqueue_admin_assets($hook) {
        // Always enqueue on our plugin pages
        if (strpos($hook, 'spfm') !== false || strpos($hook, 'sp-form') !== false) {
            wp_enqueue_style('spfm-admin', SPFM_PLUGIN_URL . 'assets/css/admin.css', array(), SPFM_VERSION);
            wp_enqueue_script('spfm-admin', SPFM_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), SPFM_VERSION, true);
            
            wp_localize_script('spfm-admin', 'spfm_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('spfm_nonce')
            ));
        }
    }
}

// Initialize the plugin
function spfm_init() {
    return SP_Form_Manager::get_instance();
}
add_action('plugins_loaded', 'spfm_init');

// Manual table creation function (can be called from admin)
function spfm_create_tables_manually() {
    SPFM_Database::create_tables();
}
