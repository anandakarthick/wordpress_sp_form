<?php
/**
 * Settings Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Settings {
    
    private static $instance = null;
    private static $phpmailer_error = '';
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Configure SMTP if enabled
        add_action('phpmailer_init', array($this, 'configure_smtp'), 10, 1);
        
        // Capture mail errors
        add_action('wp_mail_failed', array($this, 'capture_mail_error'), 10, 1);
    }
    
    /**
     * Capture mail errors
     */
    public function capture_mail_error($wp_error) {
        if (is_wp_error($wp_error)) {
            self::$phpmailer_error = $wp_error->get_error_message();
            error_log('SPFM Mail Error: ' . self::$phpmailer_error);
        }
    }
    
    /**
     * Get last mail error
     */
    public static function get_last_error() {
        return self::$phpmailer_error;
    }
    
    /**
     * Configure PHPMailer for SMTP
     */
    public function configure_smtp($phpmailer) {
        $smtp_enabled = get_option('spfm_smtp_enabled', 0);
        
        if (!$smtp_enabled) {
            return;
        }
        
        $host = get_option('spfm_smtp_host', '');
        $port = get_option('spfm_smtp_port', 587);
        $encryption = get_option('spfm_smtp_encryption', 'tls');
        $username = get_option('spfm_smtp_username', '');
        $password = get_option('spfm_smtp_password', '');
        
        if (empty($host) || empty($username) || empty($password)) {
            error_log('SPFM: SMTP enabled but missing configuration - Host: ' . (!empty($host) ? 'Set' : 'Empty') . ', User: ' . (!empty($username) ? 'Set' : 'Empty') . ', Pass: ' . (!empty($password) ? 'Set' : 'Empty'));
            return;
        }
        
        try {
            $phpmailer->isSMTP();
            $phpmailer->Host = $host;
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = intval($port);
            $phpmailer->Username = $username;
            $phpmailer->Password = $password;
            
            if ($encryption === 'tls') {
                $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($encryption === 'ssl') {
                $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $phpmailer->SMTPSecure = '';
                $phpmailer->SMTPAutoTLS = false;
            }
            
            // Set From headers - IMPORTANT: Use SMTP username as From email to avoid "Data not accepted" error
            $from_name = get_option('spfm_email_from_name', get_bloginfo('name'));
            $from_email = get_option('spfm_email_from_address', '');
            
            // If From email is empty or different from SMTP username, use SMTP username
            // Most SMTP providers require From address to match authenticated user
            if (empty($from_email) || strpos($host, 'gmail') !== false || strpos($host, 'office365') !== false || strpos($host, 'outlook') !== false) {
                $from_email = $username;
            }
            
            $phpmailer->setFrom($from_email, $from_name);
            
            // Clear any existing Reply-To and set it to the configured from address if different
            $configured_from = get_option('spfm_email_from_address', '');
            if (!empty($configured_from) && $configured_from !== $from_email) {
                $phpmailer->clearReplyTos();
                $phpmailer->addReplyTo($configured_from, $from_name);
            }
            
        } catch (Exception $e) {
            error_log('SPFM SMTP Configuration Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Send email with HTML template
     */
    public static function send_email($to, $subject, $message, $attachments = array()) {
        // Reset error
        self::$phpmailer_error = '';
        
        $from_name = get_option('spfm_email_from_name', get_bloginfo('name'));
        $from_email = get_option('spfm_email_from_address', get_option('admin_email'));
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>'
        );
        
        $html_message = self::get_email_template($subject, $message);
        
        // Ensure Settings instance is initialized (registers SMTP hook)
        self::get_instance();
        
        $result = wp_mail($to, $subject, $html_message, $headers, $attachments);
        
        if (!$result && !empty(self::$phpmailer_error)) {
            error_log('SPFM: Email failed to ' . $to . ' - Error: ' . self::$phpmailer_error);
        }
        
        return $result;
    }
    
    /**
     * Send test email with detailed error reporting
     */
    public static function send_test_email($to) {
        // Reset error
        self::$phpmailer_error = '';
        
        // Ensure Settings instance is initialized
        self::get_instance();
        
        $smtp_enabled = get_option('spfm_smtp_enabled', 0);
        $from_name = get_option('spfm_email_from_name', get_bloginfo('name'));
        $from_email = get_option('spfm_email_from_address', get_option('admin_email'));
        
        $subject = 'SP Form Manager - Test Email';
        $message = '<h2>Test Email</h2>';
        $message .= '<p>This is a test email from SP Form Manager.</p>';
        $message .= '<p>If you received this, your email settings are working correctly!</p>';
        $message .= '<hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">';
        $message .= '<p style="font-size: 12px; color: #888;"><strong>Configuration Used:</strong></p>';
        $message .= '<ul style="font-size: 12px; color: #888;">';
        $message .= '<li>SMTP Enabled: ' . ($smtp_enabled ? 'Yes' : 'No (using PHP mail)') . '</li>';
        
        if ($smtp_enabled) {
            $message .= '<li>SMTP Host: ' . esc_html(get_option('spfm_smtp_host', 'Not set')) . '</li>';
            $message .= '<li>SMTP Port: ' . esc_html(get_option('spfm_smtp_port', '587')) . '</li>';
            $message .= '<li>Encryption: ' . esc_html(get_option('spfm_smtp_encryption', 'tls')) . '</li>';
        }
        
        $message .= '<li>From: ' . esc_html($from_name) . ' &lt;' . esc_html($from_email) . '&gt;</li>';
        $message .= '</ul>';
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>'
        );
        
        $html_message = self::get_email_template($subject, $message);
        
        $result = wp_mail($to, $subject, $html_message, $headers);
        
        if (!$result) {
            $error = self::$phpmailer_error;
            if (empty($error)) {
                $error = 'Unknown error. Check your server\'s mail configuration.';
            }
            return array(
                'success' => false,
                'message' => $error
            );
        }
        
        return array(
            'success' => true,
            'message' => 'Test email sent successfully to ' . $to
        );
    }
    
    /**
     * Get HTML email template
     */
    public static function get_email_template($subject, $content) {
        $site_name = get_bloginfo('name');
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, sans-serif;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 40px 20px;">
                <tr>
                    <td align="center">
                        <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <!-- Header -->
                            <tr>
                                <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center;">
                                    <h1 style="color: #ffffff; margin: 0; font-size: 24px;">' . esc_html($subject) . '</h1>
                                </td>
                            </tr>
                            <!-- Content -->
                            <tr>
                                <td style="padding: 40px 30px;">
                                    ' . $content . '
                                </td>
                            </tr>
                            <!-- Footer -->
                            <tr>
                                <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee;">
                                    <p style="margin: 0; color: #999; font-size: 13px;">
                                        © ' . date('Y') . ' ' . esc_html($site_name) . '. All rights reserved.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
    }
    
    /**
     * Send submission notification to admin
     */
    public static function send_submission_notification($form, $submission_data, $theme_name, $customer_info) {
        $admin_email = get_option('spfm_admin_email', get_option('admin_email'));
        
        $subject = 'New Website Order: ' . $form->name;
        
        $content = '<h2 style="color: #333; margin-bottom: 20px;">New Website Order Received!</h2>';
        $content .= '<p style="color: #666; font-size: 16px; line-height: 1.6;">A customer has submitted a website order through your form.</p>';
        
        $content .= '<table style="width: 100%; border-collapse: collapse; margin: 25px 0;">';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold; width: 150px;">Form Name</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . esc_html($form->name) . '</td></tr>';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold;">Selected Template</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . esc_html($theme_name) . '</td></tr>';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold;">Customer Name</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . esc_html($customer_info['name'] ?? '') . '</td></tr>';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold;">Customer Email</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . esc_html($customer_info['email'] ?? '') . '</td></tr>';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold;">Customer Phone</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . esc_html($customer_info['phone'] ?? '-') . '</td></tr>';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold;">Submitted</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . date('F j, Y g:i A') . '</td></tr>';
        $content .= '</table>';
        
        $content .= '<p style="text-align: center; margin-top: 30px;">';
        $content .= '<a href="' . admin_url('admin.php?page=spfm-submissions') . '" style="display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: 500;">View Submission</a>';
        $content .= '</p>';
        
        return self::send_email($admin_email, $subject, $content);
    }
    
    /**
     * Send share notification (email or SMS)
     */
    public static function send_share_notification($method, $recipient, $form_name, $share_url) {
        if ($method === 'email') {
            $subject = 'You\'ve Been Invited to Create Your Website';
            
            $content = '<h2 style="color: #333; margin-bottom: 20px;">Create Your Dream Website!</h2>';
            $content .= '<p style="color: #666; font-size: 16px; line-height: 1.6;">You have been invited to select and customize your website template.</p>';
            $content .= '<p style="color: #666; font-size: 16px; line-height: 1.6;">Click the button below to:</p>';
            $content .= '<ul style="color: #666; font-size: 15px; line-height: 1.8;">';
            $content .= '<li>Choose from beautiful website templates</li>';
            $content .= '<li>Customize colors to match your brand</li>';
            $content .= '<li>Fill in your website content</li>';
            $content .= '<li>Preview and submit your order</li>';
            $content .= '</ul>';
            $content .= '<p style="text-align: center; margin: 35px 0;">';
            $content .= '<a href="' . esc_url($share_url) . '" style="display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; padding: 15px 40px; text-decoration: none; border-radius: 30px; font-weight: 600; font-size: 16px;">Start Creating Your Website</a>';
            $content .= '</p>';
            $content .= '<p style="color: #999; font-size: 13px; text-align: center;">If the button doesn\'t work, copy this link: ' . esc_url($share_url) . '</p>';
            
            return self::send_email($recipient, $subject, $content);
        } else {
            // SMS via Nexmo/Vonage
            return self::send_whatsapp($recipient, "You've been invited to create your website! Click here to get started: " . $share_url);
        }
    }
    
    /**
     * Send SMS via Nexmo/Vonage
     */
    public static function send_whatsapp($to, $message) {
        $api_key = get_option('spfm_nexmo_api_key', '');
        $api_secret = get_option('spfm_nexmo_api_secret', '');
        $from = get_option('spfm_nexmo_from_number', '');
        
        if (empty($api_key) || empty($api_secret) || empty($from)) {
            return array(
                'success' => false,
                'message' => 'SMS settings not configured. Please set up Nexmo/Vonage API in Settings.'
            );
        }
        
        // Clean phone number
        $to = preg_replace('/[^0-9+]/', '', $to);
        
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
            return array(
                'success' => false,
                'message' => 'Failed to send SMS: ' . $response->get_error_message()
            );
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['messages'][0]['status']) && $body['messages'][0]['status'] == '0') {
            return array(
                'success' => true,
                'message' => 'SMS sent successfully!'
            );
        } else {
            $error = isset($body['messages'][0]['error-text']) ? $body['messages'][0]['error-text'] : 'Unknown error';
            return array(
                'success' => false,
                'message' => 'Failed to send SMS: ' . $error
            );
        }
    }
}
