<?php
/**
 * Form Sharing Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Share {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_rewrite_rules'));
        add_filter('query_vars', array($this, 'add_query_vars'));
        add_action('template_redirect', array($this, 'handle_form_view'));
    }
    
    public function register_rewrite_rules() {
        add_rewrite_rule(
            '^spfm-form/([a-zA-Z0-9]+)/?$',
            'index.php?spfm_form_token=$matches[1]',
            'top'
        );
        
        // Flush rules if needed
        if (get_option('spfm_flush_rules', false)) {
            flush_rewrite_rules();
            delete_option('spfm_flush_rules');
        }
    }
    
    public function add_query_vars($vars) {
        $vars[] = 'spfm_form_token';
        return $vars;
    }
    
    public function handle_form_view() {
        $token = get_query_var('spfm_form_token');
        
        if (!empty($token)) {
            $this->render_customer_form($token);
            exit;
        }
    }
    
    // Generate unique share token
    public static function generate_token($form_id, $customer_id = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_form_shares';
        
        $token = wp_generate_password(32, false);
        
        $wpdb->insert($table, array(
            'form_id' => $form_id,
            'customer_id' => $customer_id,
            'token' => $token,
            'status' => 'active',
            'created_at' => current_time('mysql')
        ));
        
        return $token;
    }
    
    // Get share URL
    public static function get_share_url($token) {
        return home_url('/spfm-form/' . $token . '/');
    }
    
    // Get share by token
    public static function get_share_by_token($token) {
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_form_shares';
        
        $share = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE token = %s AND status = 'active'",
            $token
        ));
        
        // Increment view count
        if ($share) {
            $wpdb->update(
                $table,
                array('views' => $share->views + 1),
                array('id' => $share->id)
            );
        }
        
        return $share;
    }
    
    // Render customer form page
    private function render_customer_form($token) {
        $share = self::get_share_by_token($token);
        
        if (!$share) {
            wp_die('This form link is invalid or has expired.', 'Form Not Found', array('response' => 404));
        }
        
        $forms = SPFM_Forms::get_instance();
        $form = $forms->get_by_id($share->form_id);
        
        if (!$form || !$form->status) {
            wp_die('This form is no longer available.', 'Form Not Available', array('response' => 404));
        }
        
        $fields = $forms->get_fields($share->form_id);
        $themes = SPFM_Themes::get_instance();
        $all_themes = $themes->get_all_active();
        
        // Get customizations
        $customizations = self::get_customizations($token);
        
        // Determine current theme (from customizations or form default)
        $theme_id = isset($customizations['theme_id']) ? $customizations['theme_id'] : $form->theme_id;
        $current_theme = $theme_id ? $themes->get_by_id($theme_id) : null;
        
        // Include the template
        include SPFM_PLUGIN_DIR . 'templates/customer-form.php';
    }
    
    // Save customer customizations
    public static function save_customizations($token, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_form_shares';
        
        // Get existing customizations and merge
        $existing = self::get_customizations($token);
        $merged = array_merge($existing, array_filter($data));
        
        return $wpdb->update(
            $table,
            array('customizations' => json_encode($merged)),
            array('token' => $token)
        );
    }
    
    // Get customer customizations
    public static function get_customizations($token) {
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_form_shares';
        
        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT customizations FROM $table WHERE token = %s",
            $token
        ));
        
        return $result ? json_decode($result, true) : array();
    }
}
