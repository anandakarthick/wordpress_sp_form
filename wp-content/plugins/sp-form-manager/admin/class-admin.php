<?php
/**
 * Admin Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
    }
    
    public function admin_init() {
        // Any admin initialization
    }
    
    public function add_admin_menu() {
        // Main menu
        add_menu_page(
            'SP Form Manager',
            'SP Form Manager',
            'manage_options',
            'spfm-dashboard',
            array($this, 'render_dashboard'),
            'dashicons-layout',
            30
        );
        
        // Dashboard submenu
        add_submenu_page(
            'spfm-dashboard',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'spfm-dashboard',
            array($this, 'render_dashboard')
        );
        
        // Website Templates
        add_submenu_page(
            'spfm-dashboard',
            'Website Templates',
            'Templates',
            'manage_options',
            'spfm-themes',
            array($this, 'render_themes')
        );
        
        // Forms
        add_submenu_page(
            'spfm-dashboard',
            'Forms',
            'Forms',
            'manage_options',
            'spfm-forms',
            array($this, 'render_forms')
        );
        
        // Submissions
        add_submenu_page(
            'spfm-dashboard',
            'Submissions',
            'Submissions',
            'manage_options',
            'spfm-submissions',
            array($this, 'render_submissions')
        );
        
        // Customers
        add_submenu_page(
            'spfm-dashboard',
            'Customers',
            'Customers',
            'manage_options',
            'spfm-customers',
            array($this, 'render_customers')
        );
        
        // Users
        add_submenu_page(
            'spfm-dashboard',
            'Users',
            'Users',
            'manage_options',
            'spfm-users',
            array($this, 'render_users')
        );
        
        // Settings
        add_submenu_page(
            'spfm-dashboard',
            'Settings',
            'Settings',
            'manage_options',
            'spfm-settings',
            array($this, 'render_settings')
        );
    }
    
    public function render_dashboard() {
        include SPFM_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
    
    public function render_themes() {
        include SPFM_PLUGIN_DIR . 'admin/views/themes.php';
    }
    
    public function render_forms() {
        include SPFM_PLUGIN_DIR . 'admin/views/forms.php';
    }
    
    public function render_submissions() {
        include SPFM_PLUGIN_DIR . 'admin/views/submissions.php';
    }
    
    public function render_customers() {
        include SPFM_PLUGIN_DIR . 'admin/views/customers.php';
    }
    
    public function render_users() {
        include SPFM_PLUGIN_DIR . 'admin/views/users.php';
    }
    
    public function render_settings() {
        include SPFM_PLUGIN_DIR . 'admin/views/settings.php';
    }
}
