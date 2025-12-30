<?php
/**
 * Admin Handler Class
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
        add_action('init', array($this, 'create_pages'));
    }
    
    public function create_pages() {
        // Create Login Page
        if (!get_page_by_path('spfm-login')) {
            wp_insert_post(array(
                'post_title' => 'SP Form Login',
                'post_name' => 'spfm-login',
                'post_content' => '[spfm_login]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ));
        }
        
        // Create Dashboard Page
        if (!get_page_by_path('spfm-dashboard')) {
            wp_insert_post(array(
                'post_title' => 'SP Form Dashboard',
                'post_name' => 'spfm-dashboard',
                'post_content' => '[spfm_dashboard]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ));
        }
    }
    
    public function add_admin_menu() {
        // Main Menu
        add_menu_page(
            'SP Form Manager',
            'SP Form Manager',
            'manage_options',
            'spfm-dashboard',
            array($this, 'dashboard_page'),
            'dashicons-forms',
            30
        );
        
        // Dashboard
        add_submenu_page(
            'spfm-dashboard',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'spfm-dashboard',
            array($this, 'dashboard_page')
        );
        
        // Customers
        add_submenu_page(
            'spfm-dashboard',
            'Customers',
            'Customers',
            'manage_options',
            'spfm-customers',
            array($this, 'customers_page')
        );
        
        // Themes
        add_submenu_page(
            'spfm-dashboard',
            'Themes',
            'Themes',
            'manage_options',
            'spfm-themes',
            array($this, 'themes_page')
        );
        
        // Forms
        add_submenu_page(
            'spfm-dashboard',
            'Forms',
            'Forms',
            'manage_options',
            'spfm-forms',
            array($this, 'forms_page')
        );
        
        // Form Fields (hidden)
        add_submenu_page(
            null,
            'Form Fields',
            'Form Fields',
            'manage_options',
            'spfm-form-fields',
            array($this, 'form_fields_page')
        );
        
        // Submissions
        add_submenu_page(
            'spfm-dashboard',
            'Submissions',
            'Submissions',
            'manage_options',
            'spfm-submissions',
            array($this, 'submissions_page')
        );
        
        // Settings
        add_submenu_page(
            'spfm-dashboard',
            'Settings',
            'Settings',
            'manage_options',
            'spfm-settings',
            array($this, 'settings_page')
        );
        
        // Users
        add_submenu_page(
            'spfm-dashboard',
            'Users',
            'Users',
            'manage_options',
            'spfm-users',
            array($this, 'users_page')
        );
    }
    
    public function dashboard_page() {
        include SPFM_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
    
    public function customers_page() {
        include SPFM_PLUGIN_DIR . 'admin/views/customers.php';
    }
    
    public function themes_page() {
        include SPFM_PLUGIN_DIR . 'admin/views/themes.php';
    }
    
    public function forms_page() {
        include SPFM_PLUGIN_DIR . 'admin/views/forms.php';
    }
    
    public function form_fields_page() {
        include SPFM_PLUGIN_DIR . 'admin/views/form-fields.php';
    }
    
    public function submissions_page() {
        include SPFM_PLUGIN_DIR . 'admin/views/submissions.php';
    }
    
    public function settings_page() {
        include SPFM_PLUGIN_DIR . 'admin/views/settings.php';
    }
    
    public function users_page() {
        include SPFM_PLUGIN_DIR . 'admin/views/users.php';
    }
}
