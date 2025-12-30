<?php
/**
 * Share Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Share {
    
    private static $instance = null;
    private $table;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'spfm_form_shares';
    }
    
    /**
     * Generate unique token
     */
    public function generate_token() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Create a new share
     */
    public function create_share($data) {
        global $wpdb;
        
        $token = $this->generate_token();
        
        $insert_data = array(
            'form_id' => intval($data['form_id']),
            'customer_id' => intval($data['customer_id'] ?? 0),
            'token' => $token,
            'shared_via' => sanitize_text_field($data['shared_via'] ?? 'link'),
            'shared_to' => sanitize_text_field($data['shared_to'] ?? ''),
            'status' => 'active'
        );
        
        $result = $wpdb->insert($this->table, $insert_data);
        
        if ($result === false) {
            return false;
        }
        
        return array(
            'id' => $wpdb->insert_id,
            'token' => $token,
            'url' => home_url('/spfm-form/' . $token . '/')
        );
    }
    
    /**
     * Get share by token
     */
    public function get_by_token($token) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE token = %s",
            $token
        ));
    }
    
    /**
     * Get share by ID
     */
    public function get_by_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE id = %d",
            $id
        ));
    }
    
    /**
     * Increment view count
     */
    public function increment_views($token) {
        global $wpdb;
        return $wpdb->query($wpdb->prepare(
            "UPDATE {$this->table} SET views = views + 1 WHERE token = %s",
            $token
        ));
    }
    
    /**
     * Update share status
     */
    public function update_status($id, $status) {
        global $wpdb;
        return $wpdb->update(
            $this->table,
            array('status' => sanitize_text_field($status)),
            array('id' => $id)
        );
    }
    
    /**
     * Render customer form
     */
    public function render_customer_form($token) {
        $share = $this->get_by_token($token);
        
        if (!$share) {
            return $this->render_error('Invalid or expired link.');
        }
        
        if ($share->status !== 'active') {
            return $this->render_error('This form link is no longer active.');
        }
        
        // Get form
        $forms_handler = SPFM_Forms::get_instance();
        $form = $forms_handler->get_by_id($share->form_id);
        
        if (!$form || !$form->status) {
            return $this->render_error('This form is not available.');
        }
        
        // Increment views
        $this->increment_views($token);
        
        // Load template
        ob_start();
        include SPFM_PLUGIN_PATH . 'templates/customer-form.php';
        return ob_get_clean();
    }
    
    /**
     * Render error page
     */
    private function render_error($message) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
                    background: #f5f5f5;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    margin: 0;
                }
                .error-container {
                    background: #fff;
                    padding: 60px;
                    border-radius: 15px;
                    text-align: center;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                    max-width: 500px;
                }
                .error-icon {
                    width: 80px;
                    height: 80px;
                    background: linear-gradient(135deg, #ff6b6b, #ee5a5a);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 25px;
                    font-size: 40px;
                    color: #fff;
                }
                h1 {
                    color: #333;
                    margin: 0 0 15px;
                }
                p {
                    color: #666;
                    font-size: 18px;
                    margin: 0;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <div class="error-icon">!</div>
                <h1>Oops!</h1>
                <p><?php echo esc_html($message); ?></p>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get shares for a form
     */
    public function get_form_shares($form_id, $limit = 50) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT s.*, c.name as customer_name, c.email as customer_email 
             FROM {$this->table} s 
             LEFT JOIN {$wpdb->prefix}spfm_customers c ON s.customer_id = c.id 
             WHERE s.form_id = %d 
             ORDER BY s.created_at DESC 
             LIMIT %d",
            $form_id,
            $limit
        ));
    }
    
    /**
     * Delete share
     */
    public function delete($id) {
        global $wpdb;
        return $wpdb->delete($this->table, array('id' => $id));
    }
}
