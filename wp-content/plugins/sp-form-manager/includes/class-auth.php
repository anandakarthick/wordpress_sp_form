<?php
/**
 * Authentication Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Auth {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('spfm_login', array($this, 'login_shortcode'));
        add_shortcode('spfm_dashboard', array($this, 'dashboard_shortcode'));
        add_action('init', array($this, 'handle_login'));
        add_action('init', array($this, 'handle_logout'));
        add_action('init', array($this, 'handle_register'));
    }
    
    public function login_shortcode($atts) {
        if ($this->is_logged_in()) {
            wp_redirect(home_url('/spfm-dashboard/'));
            exit;
        }
        
        ob_start();
        include SPFM_PLUGIN_DIR . 'templates/login.php';
        return ob_get_clean();
    }
    
    public function dashboard_shortcode($atts) {
        if (!$this->is_logged_in()) {
            wp_redirect(home_url('/spfm-login/'));
            exit;
        }
        
        ob_start();
        include SPFM_PLUGIN_DIR . 'templates/dashboard.php';
        return ob_get_clean();
    }
    
    public function handle_login() {
        if (isset($_POST['spfm_login_submit']) && wp_verify_nonce($_POST['spfm_login_nonce'], 'spfm_login')) {
            global $wpdb;
            
            $username = sanitize_text_field($_POST['username']);
            $password = $_POST['password'];
            
            $table = $wpdb->prefix . 'spfm_users';
            $user = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table WHERE (username = %s OR email = %s) AND status = 1",
                $username, $username
            ));
            
            if ($user && password_verify($password, $user->password)) {
                $_SESSION['spfm_user_id'] = $user->id;
                $_SESSION['spfm_user_name'] = $user->full_name;
                $_SESSION['spfm_user_role'] = $user->role;
                $_SESSION['spfm_logged_in'] = true;
                
                wp_redirect(home_url('/spfm-dashboard/'));
                exit;
            } else {
                $_SESSION['spfm_login_error'] = 'Invalid username or password.';
                wp_redirect(home_url('/spfm-login/'));
                exit;
            }
        }
    }
    
    public function handle_logout() {
        if (isset($_GET['spfm_logout']) && $_GET['spfm_logout'] == '1') {
            unset($_SESSION['spfm_user_id']);
            unset($_SESSION['spfm_user_name']);
            unset($_SESSION['spfm_user_role']);
            unset($_SESSION['spfm_logged_in']);
            
            wp_redirect(home_url('/spfm-login/'));
            exit;
        }
    }
    
    public function handle_register() {
        if (isset($_POST['spfm_register_submit']) && wp_verify_nonce($_POST['spfm_register_nonce'], 'spfm_register')) {
            global $wpdb;
            
            $username = sanitize_text_field($_POST['username']);
            $email = sanitize_email($_POST['email']);
            $password = $_POST['password'];
            $full_name = sanitize_text_field($_POST['full_name']);
            
            $table = $wpdb->prefix . 'spfm_users';
            
            // Check if user exists
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE username = %s OR email = %s",
                $username, $email
            ));
            
            if ($exists) {
                $_SESSION['spfm_register_error'] = 'Username or email already exists.';
                wp_redirect(home_url('/spfm-login/'));
                exit;
            }
            
            $result = $wpdb->insert($table, array(
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'full_name' => $full_name,
                'role' => 'user',
                'status' => 1
            ));
            
            if ($result) {
                $_SESSION['spfm_register_success'] = 'Registration successful. Please login.';
            } else {
                $_SESSION['spfm_register_error'] = 'Registration failed. Please try again.';
            }
            
            wp_redirect(home_url('/spfm-login/'));
            exit;
        }
    }
    
    public static function is_logged_in() {
        return isset($_SESSION['spfm_logged_in']) && $_SESSION['spfm_logged_in'] === true;
    }
    
    public static function get_current_user_id() {
        return isset($_SESSION['spfm_user_id']) ? $_SESSION['spfm_user_id'] : 0;
    }
    
    public static function get_current_user_name() {
        return isset($_SESSION['spfm_user_name']) ? $_SESSION['spfm_user_name'] : '';
    }
    
    public static function get_current_user_role() {
        return isset($_SESSION['spfm_user_role']) ? $_SESSION['spfm_user_role'] : '';
    }
    
    public static function is_admin() {
        return self::get_current_user_role() === 'admin';
    }
}
