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
        // Configure SMTP if enabled
        add_action('phpmailer_init', array($this, 'configure_smtp'));
    }
    
    /**
     * Configure PHPMailer for SMTP
     */
    public function configure_smtp($phpmailer) {
        if (!get_option('spfm_smtp_enabled', 0)) {
            return;
        }
        
        $host = get_option('spfm_smtp_host', '');
        $port = get_option('spfm_smtp_port', 587);
        $encryption = get_option('spfm_smtp_encryption', 'tls');
        $username = get_option('spfm_smtp_username', '');
        $password = get_option('spfm_smtp_password', '');
        
        if (empty($host) || empty($username) || empty($password)) {
            return;
        }
        
        $phpmailer->isSMTP();
        $phpmailer->Host = $host;
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $port;
        $phpmailer->Username = $username;
        $phpmailer->Password = $password;
        
        if ($encryption === 'tls') {
            $phpmailer->SMTPSecure = 'tls';
        } elseif ($encryption === 'ssl') {
            $phpmailer->SMTPSecure = 'ssl';
        }
        
        // Set From headers
        $from_name = get_option('spfm_email_from_name', get_bloginfo('name'));
        $from_email = get_option('spfm_email_from_address', get_option('admin_email'));
        
        $phpmailer->setFrom($from_email, $from_name);
    }
    
    /**
     * Send email with HTML template
     */
    public static function send_email($to, $subject, $message, $attachments = array()) {
        $from_name = get_option('spfm_email_from_name', get_bloginfo('name'));
        $from_email = get_option('spfm_email_from_address', get_option('admin_email'));
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>'
        );
        
        $html_message = self::get_email_template($subject, $message);
        
        return wp_mail($to, $subject, $html_message, $headers, $attachments);
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
