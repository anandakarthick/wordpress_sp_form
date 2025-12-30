<?php
/**
 * Settings Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Settings {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    public function register_settings() {
        register_setting('spfm_settings', 'spfm_nexmo_api_key');
        register_setting('spfm_settings', 'spfm_nexmo_api_secret');
        register_setting('spfm_settings', 'spfm_nexmo_from_number');
        register_setting('spfm_settings', 'spfm_admin_email');
        register_setting('spfm_settings', 'spfm_email_from_name');
        register_setting('spfm_settings', 'spfm_email_from_address');
        register_setting('spfm_settings', 'spfm_smtp_host');
        register_setting('spfm_settings', 'spfm_smtp_port');
        register_setting('spfm_settings', 'spfm_smtp_username');
        register_setting('spfm_settings', 'spfm_smtp_password');
        register_setting('spfm_settings', 'spfm_smtp_encryption');
    }
    
    public static function get($key, $default = '') {
        return get_option('spfm_' . $key, $default);
    }
    
    public static function set($key, $value) {
        return update_option('spfm_' . $key, $value);
    }
    
    // Send Email
    public static function send_email($to, $subject, $message, $headers = array()) {
        $from_name = self::get('email_from_name', get_bloginfo('name'));
        $from_email = self::get('email_from_address', get_option('admin_email'));
        
        $default_headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>'
        );
        
        $headers = array_merge($default_headers, $headers);
        
        return wp_mail($to, $subject, $message, $headers);
    }
    
    // Send WhatsApp via Nexmo/Vonage
    public static function send_whatsapp($to, $message) {
        $api_key = self::get('nexmo_api_key');
        $api_secret = self::get('nexmo_api_secret');
        $from = self::get('nexmo_from_number');
        
        if (empty($api_key) || empty($api_secret) || empty($from)) {
            return array('success' => false, 'message' => 'Nexmo not configured');
        }
        
        // Clean phone number
        $to = preg_replace('/[^0-9]/', '', $to);
        
        // Nexmo SMS API (WhatsApp requires business verification, using SMS as fallback)
        $url = 'https://rest.nexmo.com/sms/json';
        
        $data = array(
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'to' => $to,
            'from' => $from,
            'text' => $message
        );
        
        $response = wp_remote_post($url, array(
            'body' => $data,
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            return array('success' => false, 'message' => $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['messages'][0]['status']) && $body['messages'][0]['status'] == '0') {
            return array('success' => true, 'message' => 'Message sent successfully');
        }
        
        return array('success' => false, 'message' => $body['messages'][0]['error-text'] ?? 'Failed to send');
    }
    
    // Send admin notification
    public static function notify_admin($subject, $message) {
        $admin_email = self::get('admin_email', get_option('admin_email'));
        return self::send_email($admin_email, $subject, $message);
    }
}
