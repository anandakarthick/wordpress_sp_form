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
        
        // AJAX handlers
        add_action('wp_ajax_spfm_share_form', array($this, 'share_form'));
        add_action('wp_ajax_spfm_send_whatsapp', array($this, 'send_whatsapp'));
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
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE token = %s AND status = 'active'",
            $token
        ));
    }
    
    // Share form via email
    public function share_form() {
        if (!wp_verify_nonce($_POST['nonce'], 'spfm_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed'));
        }
        
        $form_id = intval($_POST['form_id']);
        $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : null;
        $email = sanitize_email($_POST['email']);
        $method = sanitize_text_field($_POST['method'] ?? 'email');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        
        if (!$form_id) {
            wp_send_json_error(array('message' => 'Invalid form'));
        }
        
        // Generate token
        $token = self::generate_token($form_id, $customer_id);
        $url = self::get_share_url($token);
        
        // Get form details
        $forms = SPFM_Forms::get_instance();
        $form = $forms->get_by_id($form_id);
        
        if ($method === 'email' && !empty($email)) {
            // Send email
            $subject = 'You have been invited to fill a form: ' . $form->name;
            $message = $this->get_email_template($form, $url);
            
            $result = SPFM_Settings::send_email($email, $subject, $message);
            
            if ($result) {
                wp_send_json_success(array('message' => 'Form shared successfully via email', 'url' => $url));
            } else {
                wp_send_json_error(array('message' => 'Failed to send email'));
            }
        } elseif ($method === 'whatsapp' && !empty($phone)) {
            // Send via WhatsApp/SMS
            $message = "You have been invited to fill a form: {$form->name}\n\nClick here to fill the form: {$url}";
            
            $result = SPFM_Settings::send_whatsapp($phone, $message);
            
            if ($result['success']) {
                wp_send_json_success(array('message' => 'Form shared successfully via WhatsApp', 'url' => $url));
            } else {
                wp_send_json_error(array('message' => $result['message']));
            }
        } else {
            // Just return the URL
            wp_send_json_success(array('message' => 'Share link generated', 'url' => $url));
        }
    }
    
    private function get_email_template($form, $url) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .btn { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff !important; padding: 15px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1><?php echo esc_html($form->name); ?></h1>
                </div>
                <div class="content">
                    <p>Hello,</p>
                    <p>You have been invited to fill out a form. Please click the button below to access and complete the form.</p>
                    
                    <?php if (!empty($form->description)): ?>
                        <p><strong>About this form:</strong> <?php echo esc_html($form->description); ?></p>
                    <?php endif; ?>
                    
                    <p style="text-align: center;">
                        <a href="<?php echo esc_url($url); ?>" class="btn">Fill Form Now</a>
                    </p>
                    
                    <p>Or copy this link: <br><a href="<?php echo esc_url($url); ?>"><?php echo esc_url($url); ?></a></p>
                </div>
                <div class="footer">
                    <p>This email was sent from <?php echo get_bloginfo('name'); ?></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
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
        $current_theme = $form->theme_id ? $themes->get_by_id($form->theme_id) : null;
        
        // Get customer customizations if any
        $customizations = $this->get_customizations($token);
        
        include SPFM_PLUGIN_DIR . 'templates/customer-form.php';
    }
    
    // Save customer customizations
    public static function save_customizations($token, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_form_shares';
        
        return $wpdb->update(
            $table,
            array('customizations' => json_encode($data)),
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
