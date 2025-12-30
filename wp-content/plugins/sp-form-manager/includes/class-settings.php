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
        add_action('phpmailer_init', array($this, 'configure_smtp'));
    }
    
    /**
     * Configure SMTP if enabled
     */
    public function configure_smtp($phpmailer) {
        if (!get_option('spfm_smtp_enabled', 0)) {
            return;
        }
        
        $phpmailer->isSMTP();
        $phpmailer->Host = get_option('spfm_smtp_host', '');
        $phpmailer->Port = get_option('spfm_smtp_port', 587);
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = get_option('spfm_smtp_username', '');
        $phpmailer->Password = get_option('spfm_smtp_password', '');
        
        $encryption = get_option('spfm_smtp_encryption', 'tls');
        if ($encryption !== 'none') {
            $phpmailer->SMTPSecure = $encryption;
        }
        
        // Set from headers
        $phpmailer->From = get_option('spfm_email_from_address', get_option('admin_email'));
        $phpmailer->FromName = get_option('spfm_email_from_name', get_bloginfo('name'));
    }
    
    /**
     * Send email notification
     */
    public static function send_email($to, $subject, $message, $attachments = array()) {
        $from_name = get_option('spfm_email_from_name', get_bloginfo('name'));
        $from_email = get_option('spfm_email_from_address', get_option('admin_email'));
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>'
        );
        
        // Wrap message in HTML template
        $html_message = self::get_email_template($subject, $message);
        
        return wp_mail($to, $subject, $html_message, $headers, $attachments);
    }
    
    /**
     * Get HTML email template
     */
    private static function get_email_template($subject, $content) {
        $primary_color = '#667eea';
        $site_name = get_bloginfo('name');
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 30px 0;">
                <tr>
                    <td align="center">
                        <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            <!-- Header -->
                            <tr>
                                <td style="background: linear-gradient(135deg, ' . $primary_color . ' 0%, #764ba2 100%); padding: 30px; text-align: center;">
                                    <h1 style="color: #ffffff; margin: 0; font-size: 24px;">' . esc_html($subject) . '</h1>
                                </td>
                            </tr>
                            <!-- Content -->
                            <tr>
                                <td style="padding: 30px;">
                                    ' . $content . '
                                </td>
                            </tr>
                            <!-- Footer -->
                            <tr>
                                <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee;">
                                    <p style="margin: 0; color: #999; font-size: 12px;">
                                        Sent from ' . esc_html($site_name) . ' via SP Form Manager
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
     * Send form submission notification
     */
    public static function send_submission_notification($form, $submission_data, $files = array()) {
        $admin_email = get_option('spfm_admin_email', get_option('admin_email'));
        
        if (!$admin_email) {
            return false;
        }
        
        $subject = 'New Form Submission: ' . $form->name;
        
        // Build content
        $content = '<h2 style="color: #333; margin-bottom: 20px;">New Submission Received</h2>';
        $content .= '<p style="color: #666;">A new submission was received for <strong>' . esc_html($form->name) . '</strong>.</p>';
        
        // Submission data table
        $content .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
        foreach ($submission_data as $field) {
            $value = is_array($field['value']) ? implode(', ', $field['value']) : $field['value'];
            $content .= '<tr>';
            $content .= '<td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold; width: 30%;">' . esc_html($field['label']) . '</td>';
            $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . nl2br(esc_html($value)) . '</td>';
            $content .= '</tr>';
        }
        $content .= '</table>';
        
        // Uploaded files
        if (!empty($files)) {
            $content .= '<h3 style="color: #333; margin-top: 25px;">Uploaded Files</h3>';
            $content .= '<ul style="margin: 0; padding-left: 20px;">';
            foreach ($files as $file) {
                $content .= '<li><a href="' . esc_url($file['url']) . '" style="color: #667eea;">' . esc_html($file['label']) . '</a></li>';
            }
            $content .= '</ul>';
        }
        
        // Timestamp
        $content .= '<p style="color: #999; font-size: 12px; margin-top: 25px;">Submitted on: ' . current_time('F j, Y g:i A') . '</p>';
        
        return self::send_email($admin_email, $subject, $content);
    }
    
    /**
     * Send SMS/WhatsApp via Nexmo
     */
    public static function send_whatsapp($to, $message) {
        $api_key = get_option('spfm_nexmo_api_key', '');
        $api_secret = get_option('spfm_nexmo_api_secret', '');
        $from = get_option('spfm_nexmo_from_number', '');
        
        if (empty($api_key) || empty($api_secret) || empty($from)) {
            return array(
                'success' => false,
                'message' => 'Nexmo API credentials not configured. Please set up in Settings.'
            );
        }
        
        // Clean phone number
        $to = preg_replace('/[^0-9]/', '', $to);
        
        // Nexmo SMS API
        $url = 'https://rest.nexmo.com/sms/json';
        $data = array(
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'from' => $from,
            'to' => $to,
            'text' => $message
        );
        
        $response = wp_remote_post($url, array(
            'body' => $data,
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => 'Failed to connect to Nexmo API: ' . $response->get_error_message()
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
    
    /**
     * Send form share notification
     */
    public static function send_share_notification($method, $recipient, $form_name, $share_url) {
        if ($method === 'email') {
            $subject = 'You have been invited to fill a form';
            $content = '
                <h2 style="color: #333;">Form Invitation</h2>
                <p style="color: #666; font-size: 16px;">You have been invited to fill out the following form:</p>
                <h3 style="color: #667eea;">' . esc_html($form_name) . '</h3>
                <p style="margin: 25px 0;">
                    <a href="' . esc_url($share_url) . '" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 15px 30px; text-decoration: none; border-radius: 50px; font-weight: bold;">
                        Fill Form Now
                    </a>
                </p>
                <p style="color: #999; font-size: 14px;">Or copy this link: <a href="' . esc_url($share_url) . '" style="color: #667eea;">' . esc_html($share_url) . '</a></p>
            ';
            
            return self::send_email($recipient, $subject, $content);
        } else {
            // SMS/WhatsApp
            $message = "You've been invited to fill out: {$form_name}\n\nClick here: {$share_url}";
            return self::send_whatsapp($recipient, $message);
        }
    }
}
