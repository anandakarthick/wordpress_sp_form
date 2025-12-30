<?php
/**
 * AJAX Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Ajax_Handler {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Customer AJAX actions
        add_action('wp_ajax_spfm_save_customer', array($this, 'save_customer'));
        add_action('wp_ajax_spfm_delete_customer', array($this, 'delete_customer'));
        add_action('wp_ajax_spfm_get_customer', array($this, 'get_customer'));
        
        // Theme AJAX actions
        add_action('wp_ajax_spfm_save_theme', array($this, 'save_theme'));
        add_action('wp_ajax_spfm_delete_theme', array($this, 'delete_theme'));
        add_action('wp_ajax_spfm_get_theme', array($this, 'get_theme'));
        add_action('wp_ajax_spfm_toggle_theme_status', array($this, 'toggle_theme_status'));
        add_action('wp_ajax_spfm_duplicate_theme', array($this, 'duplicate_theme'));
        add_action('wp_ajax_spfm_theme_preview', array($this, 'theme_preview'));
        
        // Form AJAX actions
        add_action('wp_ajax_spfm_save_form', array($this, 'save_form'));
        add_action('wp_ajax_spfm_delete_form', array($this, 'delete_form'));
        add_action('wp_ajax_spfm_get_form', array($this, 'get_form'));
        add_action('wp_ajax_spfm_toggle_form_status', array($this, 'toggle_form_status'));
        
        // Form Field AJAX actions
        add_action('wp_ajax_spfm_save_field', array($this, 'save_field'));
        add_action('wp_ajax_spfm_delete_field', array($this, 'delete_field'));
        add_action('wp_ajax_spfm_get_field', array($this, 'get_field'));
        add_action('wp_ajax_spfm_reorder_fields', array($this, 'reorder_fields'));
        
        // Share actions
        add_action('wp_ajax_spfm_share_form', array($this, 'share_form'));
        add_action('wp_ajax_spfm_get_share_link', array($this, 'get_share_link'));
        
        // Customer form actions (public)
        add_action('wp_ajax_spfm_customer_submit', array($this, 'customer_submit'));
        add_action('wp_ajax_nopriv_spfm_customer_submit', array($this, 'customer_submit'));
        add_action('wp_ajax_spfm_save_customizations', array($this, 'save_customizations'));
        add_action('wp_ajax_nopriv_spfm_save_customizations', array($this, 'save_customizations'));
        add_action('wp_ajax_spfm_apply_theme', array($this, 'apply_theme'));
        add_action('wp_ajax_nopriv_spfm_apply_theme', array($this, 'apply_theme'));
        
        // Submission actions
        add_action('wp_ajax_spfm_get_submission', array($this, 'get_submission'));
        add_action('wp_ajax_spfm_delete_submission', array($this, 'delete_submission'));
        add_action('wp_ajax_spfm_update_submission_status', array($this, 'update_submission_status'));
        
        // Settings
        add_action('wp_ajax_spfm_save_settings', array($this, 'save_settings'));
        add_action('wp_ajax_spfm_test_email', array($this, 'test_email'));
        add_action('wp_ajax_spfm_test_sms', array($this, 'test_sms'));
        add_action('wp_ajax_spfm_flush_rules', array($this, 'flush_rules'));
        add_action('wp_ajax_spfm_recreate_tables', array($this, 'recreate_tables'));
        
        // User AJAX actions
        add_action('wp_ajax_spfm_save_user', array($this, 'save_user'));
        add_action('wp_ajax_spfm_delete_user', array($this, 'delete_user'));
        add_action('wp_ajax_spfm_get_user', array($this, 'get_user'));
    }
    
    private function verify_nonce() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'spfm_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed.'));
            exit;
        }
    }
    
    private function check_table_exists($table_name) {
        global $wpdb;
        $table = $wpdb->prefix . $table_name;
        $result = $wpdb->get_var("SHOW TABLES LIKE '$table'");
        return $result === $table;
    }
    
    // ==================== CUSTOMERS ====================
    public function save_customer() {
        $this->verify_nonce();
        
        if (!$this->check_table_exists('spfm_customers')) {
            SPFM_Database::create_tables();
        }
        
        $customers = SPFM_Customers::get_instance();
        
        $data = array(
            'name' => isset($_POST['name']) ? $_POST['name'] : '',
            'email' => isset($_POST['email']) ? $_POST['email'] : '',
            'phone' => isset($_POST['phone']) ? $_POST['phone'] : '',
            'company' => isset($_POST['company']) ? $_POST['company'] : '',
            'address' => isset($_POST['address']) ? $_POST['address'] : '',
            'city' => isset($_POST['city']) ? $_POST['city'] : '',
            'state' => isset($_POST['state']) ? $_POST['state'] : '',
            'country' => isset($_POST['country']) ? $_POST['country'] : '',
            'zip_code' => isset($_POST['zip_code']) ? $_POST['zip_code'] : '',
            'notes' => isset($_POST['notes']) ? $_POST['notes'] : '',
            'status' => isset($_POST['status']) ? $_POST['status'] : 1
        );
        
        if (empty($data['name'])) {
            wp_send_json_error(array('message' => 'Name is required.'));
            return;
        }
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        
        if ($id) {
            $result = $customers->update($id, $data);
            $message = 'Customer updated successfully.';
        } else {
            $result = $customers->create($data);
            $message = 'Customer created successfully.';
        }
        
        if ($result !== false) {
            wp_send_json_success(array('message' => $message, 'id' => $id ? $id : $result));
        } else {
            wp_send_json_error(array('message' => 'Failed to save customer.'));
        }
    }
    
    public function delete_customer() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid customer ID.'));
        }
        $customers = SPFM_Customers::get_instance();
        $result = $customers->delete($id);
        if ($result) {
            wp_send_json_success(array('message' => 'Customer deleted successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete customer.'));
        }
    }
    
    public function get_customer() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid customer ID.'));
        }
        $customers = SPFM_Customers::get_instance();
        $customer = $customers->get_by_id($id);
        if ($customer) {
            wp_send_json_success(array('customer' => $customer));
        } else {
            wp_send_json_error(array('message' => 'Customer not found.'));
        }
    }
    
    // ==================== THEMES ====================
    public function save_theme() {
        $this->verify_nonce();
        
        if (!$this->check_table_exists('spfm_themes')) {
            SPFM_Database::create_tables();
        }
        
        $themes = SPFM_Themes::get_instance();
        
        $data = array(
            'name' => isset($_POST['name']) ? $_POST['name'] : '',
            'description' => isset($_POST['description']) ? $_POST['description'] : '',
            'template_type' => isset($_POST['template_type']) ? $_POST['template_type'] : 'custom',
            'primary_color' => isset($_POST['primary_color']) ? $_POST['primary_color'] : '#007bff',
            'secondary_color' => isset($_POST['secondary_color']) ? $_POST['secondary_color'] : '#6c757d',
            'background_color' => isset($_POST['background_color']) ? $_POST['background_color'] : '#ffffff',
            'text_color' => isset($_POST['text_color']) ? $_POST['text_color'] : '#333333',
            'accent_color' => isset($_POST['accent_color']) ? $_POST['accent_color'] : '#28a745',
            'header_bg_color' => isset($_POST['header_bg_color']) ? $_POST['header_bg_color'] : '#667eea',
            'button_style' => isset($_POST['button_style']) ? $_POST['button_style'] : 'rounded',
            'font_family' => isset($_POST['font_family']) ? $_POST['font_family'] : 'Arial, sans-serif',
            'header_font' => isset($_POST['header_font']) ? $_POST['header_font'] : 'Arial, sans-serif',
            'layout_style' => isset($_POST['layout_style']) ? $_POST['layout_style'] : 'default',
            'custom_css' => isset($_POST['custom_css']) ? $_POST['custom_css'] : '',
            'is_template' => isset($_POST['is_template']) ? $_POST['is_template'] : 0,
            'status' => isset($_POST['status']) ? $_POST['status'] : 1
        );
        
        if (empty($data['name'])) {
            wp_send_json_error(array('message' => 'Theme name is required.'));
            return;
        }
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        
        if ($id) {
            $result = $themes->update($id, $data);
            $message = 'Theme updated successfully.';
        } else {
            $result = $themes->create($data);
            $message = 'Theme created successfully.';
        }
        
        if ($result !== false) {
            wp_send_json_success(array('message' => $message, 'id' => $id ? $id : $result));
        } else {
            wp_send_json_error(array('message' => 'Failed to save theme.'));
        }
    }
    
    public function delete_theme() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid theme ID.'));
        }
        $themes = SPFM_Themes::get_instance();
        $result = $themes->delete($id);
        if ($result) {
            wp_send_json_success(array('message' => 'Theme deleted successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete theme.'));
        }
    }
    
    public function get_theme() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid theme ID.'));
        }
        $themes = SPFM_Themes::get_instance();
        $theme = $themes->get_by_id($id);
        if ($theme) {
            wp_send_json_success(array('theme' => $theme));
        } else {
            wp_send_json_error(array('message' => 'Theme not found.'));
        }
    }
    
    public function toggle_theme_status() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid theme ID.'));
        }
        $themes = SPFM_Themes::get_instance();
        $result = $themes->toggle_status($id);
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Status updated successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update status.'));
        }
    }
    
    public function duplicate_theme() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid theme ID.'));
        }
        $themes = SPFM_Themes::get_instance();
        $result = $themes->duplicate_template($id);
        if ($result) {
            wp_send_json_success(array('message' => 'Theme duplicated successfully.', 'id' => $result));
        } else {
            wp_send_json_error(array('message' => 'Failed to duplicate theme.'));
        }
    }
    
    // ==================== FORMS ====================
    public function save_form() {
        $this->verify_nonce();
        
        if (!$this->check_table_exists('spfm_forms')) {
            SPFM_Database::create_tables();
        }
        
        $forms = SPFM_Forms::get_instance();
        
        $data = array(
            'name' => isset($_POST['name']) ? $_POST['name'] : '',
            'description' => isset($_POST['description']) ? $_POST['description'] : '',
            'theme_id' => isset($_POST['theme_id']) ? $_POST['theme_id'] : null,
            'header_text' => isset($_POST['header_text']) ? $_POST['header_text'] : '',
            'footer_text' => isset($_POST['footer_text']) ? $_POST['footer_text'] : '',
            'submit_button_text' => isset($_POST['submit_button_text']) ? $_POST['submit_button_text'] : 'Submit',
            'success_message' => isset($_POST['success_message']) ? $_POST['success_message'] : 'Thank you for your submission!',
            'allow_customization' => isset($_POST['allow_customization']) ? $_POST['allow_customization'] : 1,
            'notify_admin' => isset($_POST['notify_admin']) ? $_POST['notify_admin'] : 1,
            'status' => isset($_POST['status']) ? $_POST['status'] : 1
        );
        
        if (empty($data['name'])) {
            wp_send_json_error(array('message' => 'Form name is required.'));
            return;
        }
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        
        if ($id) {
            $result = $forms->update($id, $data);
            $message = 'Form updated successfully.';
        } else {
            $result = $forms->create($data);
            $message = 'Form created successfully.';
        }
        
        if ($result !== false) {
            wp_send_json_success(array('message' => $message, 'id' => $id ? $id : $result));
        } else {
            wp_send_json_error(array('message' => 'Failed to save form.'));
        }
    }
    
    public function delete_form() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid form ID.'));
        }
        $forms = SPFM_Forms::get_instance();
        $result = $forms->delete($id);
        if ($result) {
            wp_send_json_success(array('message' => 'Form deleted successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete form.'));
        }
    }
    
    public function get_form() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid form ID.'));
        }
        $forms = SPFM_Forms::get_instance();
        $form = $forms->get_by_id($id);
        $fields = $forms->get_fields($id);
        if ($form) {
            wp_send_json_success(array('form' => $form, 'fields' => $fields));
        } else {
            wp_send_json_error(array('message' => 'Form not found.'));
        }
    }
    
    public function toggle_form_status() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid form ID.'));
        }
        $forms = SPFM_Forms::get_instance();
        $result = $forms->toggle_status($id);
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Status updated successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update status.'));
        }
    }
    
    // ==================== FORM FIELDS ====================
    public function save_field() {
        $this->verify_nonce();
        $forms = SPFM_Forms::get_instance();
        $form_id = intval($_POST['form_id'] ?? 0);
        if (!$form_id) {
            wp_send_json_error(array('message' => 'Invalid form ID.'));
        }
        
        $data = array(
            'field_label' => isset($_POST['field_label']) ? $_POST['field_label'] : '',
            'field_name' => isset($_POST['field_name']) ? $_POST['field_name'] : '',
            'field_type' => isset($_POST['field_type']) ? $_POST['field_type'] : 'text',
            'field_options' => isset($_POST['field_options']) ? $_POST['field_options'] : '',
            'placeholder' => isset($_POST['placeholder']) ? $_POST['placeholder'] : '',
            'default_value' => isset($_POST['default_value']) ? $_POST['default_value'] : '',
            'is_required' => isset($_POST['is_required']) ? intval($_POST['is_required']) : 0,
            'css_class' => isset($_POST['css_class']) ? $_POST['css_class'] : '',
            'status' => isset($_POST['status']) ? $_POST['status'] : 1
        );
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        
        if ($id) {
            $result = $forms->update_field($id, $data);
            $message = 'Field updated successfully.';
        } else {
            $result = $forms->add_field($form_id, $data);
            $message = 'Field added successfully.';
        }
        
        if ($result !== false) {
            wp_send_json_success(array('message' => $message, 'id' => $id ? $id : $result));
        } else {
            wp_send_json_error(array('message' => 'Failed to save field.'));
        }
    }
    
    public function delete_field() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid field ID.'));
        }
        $forms = SPFM_Forms::get_instance();
        $result = $forms->delete_field($id);
        if ($result) {
            wp_send_json_success(array('message' => 'Field deleted successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete field.'));
        }
    }
    
    public function get_field() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid field ID.'));
        }
        $forms = SPFM_Forms::get_instance();
        $field = $forms->get_field_by_id($id);
        if ($field) {
            wp_send_json_success(array('field' => $field));
        } else {
            wp_send_json_error(array('message' => 'Field not found.'));
        }
    }
    
    public function reorder_fields() {
        $this->verify_nonce();
        $form_id = intval($_POST['form_id'] ?? 0);
        $orders = isset($_POST['orders']) ? $_POST['orders'] : array();
        if (!$form_id || empty($orders)) {
            wp_send_json_error(array('message' => 'Invalid data.'));
        }
        $forms = SPFM_Forms::get_instance();
        $result = $forms->reorder_fields($form_id, $orders);
        if ($result) {
            wp_send_json_success(array('message' => 'Fields reordered successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to reorder fields.'));
        }
    }
    
    // ==================== SHARING ====================
    public function share_form() {
        $this->verify_nonce();
        
        $form_id = intval($_POST['form_id'] ?? 0);
        $method = sanitize_text_field($_POST['method'] ?? 'link');
        $email = sanitize_email($_POST['email'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $customer_id = intval($_POST['customer_id'] ?? 0);
        
        if (!$form_id) {
            wp_send_json_error(array('message' => 'Invalid form ID.'));
            return;
        }
        
        // Generate share token
        $token = SPFM_Share::generate_token($form_id, $customer_id);
        $url = SPFM_Share::get_share_url($token);
        
        $forms = SPFM_Forms::get_instance();
        $form = $forms->get_by_id($form_id);
        
        if ($method === 'email' && !empty($email)) {
            $subject = 'You have been invited to fill a form: ' . $form->name;
            $message = $this->get_share_email_template($form, $url);
            $result = SPFM_Settings::send_email($email, $subject, $message);
            
            if ($result) {
                wp_send_json_success(array('message' => 'Form shared via email successfully!', 'url' => $url));
            } else {
                wp_send_json_error(array('message' => 'Failed to send email. Share link: ' . $url));
            }
        } elseif ($method === 'whatsapp' && !empty($phone)) {
            $sms_message = "You have been invited to fill a form: {$form->name}. Click here: {$url}";
            $result = SPFM_Settings::send_whatsapp($phone, $sms_message);
            
            if ($result['success']) {
                wp_send_json_success(array('message' => 'Form shared via SMS/WhatsApp successfully!', 'url' => $url));
            } else {
                wp_send_json_error(array('message' => $result['message'] . '. Share link: ' . $url));
            }
        } else {
            wp_send_json_success(array('message' => 'Share link generated successfully!', 'url' => $url));
        }
    }
    
    private function get_share_email_template($form, $url) {
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
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1><?php echo esc_html($form->name); ?></h1>
                </div>
                <div class="content">
                    <p>Hello,</p>
                    <p>You have been invited to fill out a form. Click the button below to get started.</p>
                    <p style="text-align: center;">
                        <a href="<?php echo esc_url($url); ?>" class="btn">Fill Form Now</a>
                    </p>
                    <p>Or copy this link: <a href="<?php echo esc_url($url); ?>"><?php echo esc_url($url); ?></a></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    public function get_share_link() {
        $this->verify_nonce();
        $form_id = intval($_POST['form_id'] ?? 0);
        if (!$form_id) {
            wp_send_json_error(array('message' => 'Invalid form ID.'));
            return;
        }
        $token = SPFM_Share::generate_token($form_id);
        $url = SPFM_Share::get_share_url($token);
        wp_send_json_success(array('url' => $url));
    }
    
    // ==================== CUSTOMER FORM SUBMISSION ====================
    public function customer_submit() {
        $this->verify_nonce();
        
        $token = sanitize_text_field($_POST['spfm_token'] ?? '');
        $form_id = intval($_POST['spfm_form_id'] ?? 0);
        
        if (!$token || !$form_id) {
            wp_send_json_error(array('message' => 'Invalid submission.'));
            return;
        }
        
        $share = SPFM_Share::get_share_by_token($token);
        if (!$share) {
            wp_send_json_error(array('message' => 'Invalid or expired form link.'));
            return;
        }
        
        $forms = SPFM_Forms::get_instance();
        $form = $forms->get_by_id($form_id);
        $fields = $forms->get_fields($form_id);
        
        // Collect submission data
        $submission_data = array();
        foreach ($fields as $field) {
            if (isset($_POST[$field->field_name])) {
                $submission_data[$field->field_name] = array(
                    'label' => $field->field_label,
                    'type' => $field->field_type,
                    'value' => $_POST[$field->field_name]
                );
            }
        }
        
        // Handle file uploads
        $uploaded_files = array();
        foreach ($fields as $field) {
            if ($field->field_type === 'file' && isset($_FILES[$field->field_name])) {
                $file = $_FILES[$field->field_name];
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $upload = wp_handle_upload($file, array('test_form' => false));
                    if (!isset($upload['error'])) {
                        $uploaded_files[$field->field_name] = array(
                            'label' => $field->field_label,
                            'url' => $upload['url'],
                            'file' => $upload['file']
                        );
                    }
                }
            }
        }
        
        // Get customizations
        $customizations = SPFM_Share::get_customizations($token);
        
        // Save submission
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_form_submissions';
        
        $result = $wpdb->insert($table, array(
            'form_id' => $form_id,
            'share_id' => $share->id,
            'customer_id' => $share->customer_id,
            'submission_data' => json_encode($submission_data),
            'uploaded_files' => json_encode($uploaded_files),
            'customizations' => json_encode($customizations),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'status' => 'new'
        ));
        
        if ($result) {
            // Notify admin
            if ($form->notify_admin) {
                $this->send_admin_notification($form, $submission_data, $uploaded_files);
            }
            
            $success_message = !empty($form->success_message) ? $form->success_message : 'Thank you for your submission!';
            wp_send_json_success(array('message' => $success_message));
        } else {
            wp_send_json_error(array('message' => 'Failed to save submission.'));
        }
    }
    
    private function send_admin_notification($form, $submission_data, $uploaded_files) {
        $admin_email = SPFM_Settings::get('admin_email', get_option('admin_email'));
        $subject = 'New Form Submission: ' . $form->name;
        
        $message = '<h2>New Form Submission</h2>';
        $message .= '<p><strong>Form:</strong> ' . esc_html($form->name) . '</p>';
        $message .= '<p><strong>Submitted:</strong> ' . current_time('mysql') . '</p>';
        $message .= '<hr>';
        $message .= '<h3>Submission Data:</h3>';
        $message .= '<table style="width:100%; border-collapse: collapse;">';
        
        foreach ($submission_data as $key => $data) {
            $value = is_array($data['value']) ? implode(', ', $data['value']) : $data['value'];
            $message .= '<tr style="border-bottom: 1px solid #eee;">';
            $message .= '<td style="padding: 10px; font-weight: bold;">' . esc_html($data['label']) . '</td>';
            $message .= '<td style="padding: 10px;">' . esc_html($value) . '</td>';
            $message .= '</tr>';
        }
        
        $message .= '</table>';
        
        if (!empty($uploaded_files)) {
            $message .= '<h3>Uploaded Files:</h3>';
            foreach ($uploaded_files as $file) {
                $message .= '<p><a href="' . esc_url($file['url']) . '">' . esc_html($file['label']) . '</a></p>';
            }
        }
        
        SPFM_Settings::send_email($admin_email, $subject, $message);
    }
    
    // ==================== CUSTOMIZATIONS ====================
    public function save_customizations() {
        $this->verify_nonce();
        
        $token = sanitize_text_field($_POST['token'] ?? '');
        if (!$token) {
            wp_send_json_error(array('message' => 'Invalid token.'));
            return;
        }
        
        $customizations = array(
            'primary_color' => sanitize_hex_color($_POST['primary_color'] ?? ''),
            'secondary_color' => sanitize_hex_color($_POST['secondary_color'] ?? ''),
            'background_color' => sanitize_hex_color($_POST['background_color'] ?? ''),
            'header_bg_color' => sanitize_hex_color($_POST['header_bg_color'] ?? ''),
            'header_text' => sanitize_text_field($_POST['header_text'] ?? ''),
            'footer_text' => sanitize_textarea_field($_POST['footer_text'] ?? ''),
            'submit_button_text' => sanitize_text_field($_POST['submit_button_text'] ?? '')
        );
        
        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $upload = wp_handle_upload($_FILES['logo'], array('test_form' => false));
            if (!isset($upload['error'])) {
                $customizations['logo_url'] = $upload['url'];
            }
        }
        
        // Handle banner upload
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $upload = wp_handle_upload($_FILES['banner'], array('test_form' => false));
            if (!isset($upload['error'])) {
                $customizations['banner_url'] = $upload['url'];
            }
        }
        
        $result = SPFM_Share::save_customizations($token, $customizations);
        
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Customizations saved successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to save customizations.'));
        }
    }
    
    public function apply_theme() {
        $this->verify_nonce();
        
        $token = sanitize_text_field($_POST['token'] ?? '');
        $theme_id = intval($_POST['theme_id'] ?? 0);
        
        if (!$token || !$theme_id) {
            wp_send_json_error(array('message' => 'Invalid data.'));
            return;
        }
        
        // Get current customizations and update theme
        $customizations = SPFM_Share::get_customizations($token);
        
        // Get theme colors
        $themes = SPFM_Themes::get_instance();
        $theme = $themes->get_by_id($theme_id);
        
        if ($theme) {
            $customizations['theme_id'] = $theme_id;
            $customizations['primary_color'] = $theme->primary_color;
            $customizations['secondary_color'] = $theme->secondary_color;
            $customizations['background_color'] = $theme->background_color;
            $customizations['header_bg_color'] = $theme->header_bg_color;
            
            // Update the share with new theme
            global $wpdb;
            $wpdb->update(
                $wpdb->prefix . 'spfm_form_shares',
                array('customizations' => json_encode($customizations)),
                array('token' => $token)
            );
            
            // Also update the form's theme
            $share = SPFM_Share::get_share_by_token($token);
            if ($share) {
                $wpdb->update(
                    $wpdb->prefix . 'spfm_forms',
                    array('theme_id' => $theme_id),
                    array('id' => $share->form_id)
                );
            }
            
            wp_send_json_success(array('message' => 'Theme applied successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Theme not found.'));
        }
    }
    
    // ==================== SUBMISSIONS ====================
    public function get_submission() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid submission ID.'));
            return;
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_form_submissions';
        $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
        
        if ($submission) {
            $submission->submission_data = json_decode($submission->submission_data, true);
            $submission->uploaded_files = json_decode($submission->uploaded_files, true);
            wp_send_json_success(array('submission' => $submission));
        } else {
            wp_send_json_error(array('message' => 'Submission not found.'));
        }
    }
    
    public function delete_submission() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid submission ID.'));
            return;
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_form_submissions';
        $result = $wpdb->delete($table, array('id' => $id));
        
        if ($result) {
            wp_send_json_success(array('message' => 'Submission deleted successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete submission.'));
        }
    }
    
    public function update_submission_status() {
        $this->verify_nonce();
        $id = intval($_POST['id'] ?? 0);
        $status = sanitize_text_field($_POST['status'] ?? 'pending');
        
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid submission ID.'));
            return;
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_form_submissions';
        $result = $wpdb->update($table, array('status' => $status), array('id' => $id));
        
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Status updated successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update status.'));
        }
    }
    
    // ==================== SETTINGS ====================
    public function save_settings() {
        $this->verify_nonce();
        
        $settings = array(
            'admin_email' => sanitize_email($_POST['admin_email'] ?? ''),
            'email_from_name' => sanitize_text_field($_POST['email_from_name'] ?? ''),
            'email_from_address' => sanitize_email($_POST['email_from_address'] ?? ''),
            // SMTP Settings
            'smtp_enabled' => isset($_POST['smtp_enabled']) ? 1 : 0,
            'smtp_host' => sanitize_text_field($_POST['smtp_host'] ?? ''),
            'smtp_port' => intval($_POST['smtp_port'] ?? 587),
            'smtp_encryption' => sanitize_text_field($_POST['smtp_encryption'] ?? 'tls'),
            'smtp_username' => sanitize_text_field($_POST['smtp_username'] ?? ''),
            'smtp_password' => sanitize_text_field($_POST['smtp_password'] ?? ''),
            // Nexmo Settings
            'nexmo_api_key' => sanitize_text_field($_POST['nexmo_api_key'] ?? ''),
            'nexmo_api_secret' => sanitize_text_field($_POST['nexmo_api_secret'] ?? ''),
            'nexmo_from_number' => sanitize_text_field($_POST['nexmo_from_number'] ?? '')
        );
        
        foreach ($settings as $key => $value) {
            update_option('spfm_' . $key, $value);
        }
        
        wp_send_json_success(array('message' => 'Settings saved successfully.'));
    }
    
    public function theme_preview() {
        // Check nonce from GET for iframe loading
        if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'spfm_nonce')) {
            wp_die('Security check failed.');
        }
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$id) {
            wp_die('Invalid theme ID.');
        }
        
        $themes = SPFM_Themes::get_instance();
        $theme = $themes->get_by_id($id);
        
        if (!$theme) {
            wp_die('Theme not found.');
        }
        
        // Allow live customization from URL params
        $custom = array();
        $color_params = array('primary_color', 'secondary_color', 'background_color', 'text_color', 'accent_color', 'header_bg_color');
        foreach ($color_params as $param) {
            if (isset($_GET[$param])) {
                $custom[$param] = sanitize_hex_color($_GET[$param]);
            }
        }
        if (isset($_GET['layout_style'])) {
            $custom['layout_style'] = sanitize_text_field($_GET['layout_style']);
        }
        if (isset($_GET['button_style'])) {
            $custom['button_style'] = sanitize_text_field($_GET['button_style']);
        }
        if (isset($_GET['body_font'])) {
            $custom['body_font'] = sanitize_text_field($_GET['body_font']);
        }
        if (isset($_GET['header_font'])) {
            $custom['header_font'] = sanitize_text_field($_GET['header_font']);
        }
        
        // Merge custom values with theme defaults
        $preview_theme = clone $theme;
        foreach ($custom as $key => $value) {
            if (!empty($value)) {
                $preview_theme->$key = $value;
            }
        }
        
        // Output preview HTML
        $this->render_theme_preview($preview_theme);
        exit;
    }
    
    private function render_theme_preview($theme) {
        $primary = $theme->primary_color;
        $secondary = $theme->secondary_color;
        $background = $theme->background_color;
        $text = $theme->text_color;
        $accent = $theme->accent_color ?: $primary;
        $header_bg = $theme->header_bg_color ?: $primary;
        $body_font = $theme->body_font ?: 'Poppins';
        $header_font = $theme->header_font ?: 'Poppins';
        $button_style = $theme->button_style ?: 'rounded';
        $layout_style = $theme->layout_style ?: 'default';
        
        $button_radius = '8px';
        if ($button_style === 'pill') $button_radius = '50px';
        elseif ($button_style === 'square') $button_radius = '0';
        
        $header_gradient = "linear-gradient(135deg, {$header_bg} 0%, {$secondary} 100%)";
        if ($layout_style === 'minimal') $header_gradient = $header_bg;
        
        $button_bg = $button_style === 'gradient' ? $header_gradient : $accent;
        $button_border = $button_style === 'outline' ? "2px solid {$accent}" : 'none';
        $button_text_color = $button_style === 'outline' ? $accent : '#fff';
        $button_bg_outline = $button_style === 'outline' ? 'transparent' : $button_bg;
        $button_shadow = $button_style === 'shadow' ? 'box-shadow: 0 5px 20px rgba(0,0,0,0.2);' : '';
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($body_font); ?>:wght@400;500;600;700&family=<?php echo urlencode($header_font); ?>:wght@400;500;600;700&display=swap" rel="stylesheet">
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    font-family: '<?php echo $body_font; ?>', sans-serif;
                    background: #f5f5f5;
                    padding: 30px;
                    min-height: 100vh;
                }
                .form-wrapper {
                    max-width: 600px;
                    margin: 0 auto;
                    background: <?php echo $background; ?>;
                    border-radius: 15px;
                    overflow: hidden;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                }
                .form-header {
                    background: <?php echo $header_gradient; ?>;
                    padding: 40px 30px;
                    text-align: center;
                }
                .form-header h1 {
                    font-family: '<?php echo $header_font; ?>', sans-serif;
                    color: #fff;
                    font-size: 24px;
                    margin-bottom: 8px;
                }
                .form-header p {
                    color: rgba(255,255,255,0.9);
                    font-size: 14px;
                }
                .form-body {
                    padding: 30px;
                    color: <?php echo $text; ?>;
                }
                .form-group {
                    margin-bottom: 20px;
                }
                .form-group label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: 600;
                    font-size: 14px;
                }
                .form-group label .required {
                    color: #dc3545;
                }
                .form-control {
                    width: 100%;
                    padding: 12px 15px;
                    border: 2px solid #e0e0e0;
                    border-radius: 8px;
                    font-size: 14px;
                    font-family: inherit;
                    transition: all 0.3s;
                }
                .form-control:focus {
                    border-color: <?php echo $primary; ?>;
                    outline: none;
                    box-shadow: 0 0 0 3px <?php echo $primary; ?>20;
                }
                textarea.form-control {
                    resize: vertical;
                    min-height: 100px;
                }
                .form-check {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin-bottom: 8px;
                }
                .form-check input {
                    width: 18px;
                    height: 18px;
                    accent-color: <?php echo $primary; ?>;
                }
                .btn-submit {
                    display: block;
                    width: 100%;
                    padding: 15px 30px;
                    background: <?php echo $button_bg_outline; ?>;
                    color: <?php echo $button_text_color; ?>;
                    border: <?php echo $button_border; ?>;
                    border-radius: <?php echo $button_radius; ?>;
                    font-size: 16px;
                    font-weight: 600;
                    cursor: pointer;
                    font-family: inherit;
                    transition: all 0.3s;
                    <?php echo $button_shadow; ?>
                }
                .btn-submit:hover {
                    transform: translateY(-2px);
                    opacity: 0.9;
                }
                .form-footer {
                    background: #f8f9fa;
                    padding: 20px 30px;
                    text-align: center;
                    font-size: 13px;
                    color: #666;
                    border-top: 1px solid #eee;
                }
                .page-nav {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                    padding-top: 20px;
                    border-top: 1px solid #eee;
                }
                .btn-nav {
                    padding: 10px 25px;
                    background: #e9ecef;
                    border: none;
                    border-radius: <?php echo $button_radius; ?>;
                    cursor: pointer;
                    font-family: inherit;
                    transition: all 0.3s;
                }
                .btn-nav:hover {
                    background: #dee2e6;
                }
                .btn-nav.primary {
                    background: <?php echo $accent; ?>;
                    color: #fff;
                }
                .progress-bar {
                    height: 4px;
                    background: #e9ecef;
                    margin-bottom: 0;
                }
                .progress-fill {
                    height: 100%;
                    background: <?php echo $header_gradient; ?>;
                    width: 33%;
                    transition: width 0.3s;
                }
                .section-title {
                    font-family: '<?php echo $header_font; ?>', sans-serif;
                    font-size: 18px;
                    color: <?php echo $primary; ?>;
                    margin: 20px 0 15px;
                    padding-bottom: 10px;
                    border-bottom: 2px solid <?php echo $primary; ?>30;
                }
            </style>
        </head>
        <body>
            <div class="form-wrapper">
                <div class="progress-bar"><div class="progress-fill"></div></div>
                <div class="form-header">
                    <h1>Sample Form</h1>
                    <p>Preview of <?php echo esc_html($theme->name); ?> theme</p>
                </div>
                <div class="form-body">
                    <h3 class="section-title">Personal Information</h3>
                    
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" class="form-control" placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <input type="email" class="form-control" placeholder="Enter your email">
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" class="form-control" placeholder="+1 (234) 567-8900">
                    </div>
                    
                    <h3 class="section-title">Additional Details</h3>
                    
                    <div class="form-group">
                        <label>Select Option</label>
                        <select class="form-control">
                            <option value="">Choose an option...</option>
                            <option>Option 1</option>
                            <option>Option 2</option>
                            <option>Option 3</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Preferences</label>
                        <div class="form-check">
                            <input type="checkbox" id="opt1" checked>
                            <label for="opt1">Email notifications</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="opt2">
                            <label for="opt2">SMS notifications</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" placeholder="Enter your message here..."></textarea>
                    </div>
                    
                    <button type="button" class="btn-submit">Submit Form</button>
                    
                    <div class="page-nav">
                        <button class="btn-nav">← Previous</button>
                        <button class="btn-nav primary">Next →</button>
                    </div>
                </div>
                <div class="form-footer">
                    Thank you for using our service. Your privacy is important to us.
                </div>
            </div>
        </body>
        </html>
        <?php
    }
    
    public function test_email() {
        $this->verify_nonce();
        
        $email = sanitize_email($_POST['email'] ?? '');
        if (!$email) {
            wp_send_json_error(array('message' => 'Please enter an email address.'));
            return;
        }
        
        $subject = 'SP Form Manager - Test Email';
        $message = '
            <h2>Test Email</h2>
            <p>This is a test email from SP Form Manager.</p>
            <p>If you received this email, your email settings are configured correctly!</p>
            <p><strong>Sent:</strong> ' . current_time('mysql') . '</p>
        ';
        
        $result = SPFM_Settings::send_email($email, $subject, $message);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Test email sent successfully to ' . $email));
        } else {
            wp_send_json_error(array('message' => 'Failed to send test email. Please check your email settings.'));
        }
    }
    
    public function test_sms() {
        $this->verify_nonce();
        
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        if (!$phone) {
            wp_send_json_error(array('message' => 'Please enter a phone number.'));
            return;
        }
        
        $message = 'SP Form Manager - Test SMS. If you received this, your SMS settings are working!';
        
        $result = SPFM_Settings::send_whatsapp($phone, $message);
        
        if ($result['success']) {
            wp_send_json_success(array('message' => 'Test SMS sent successfully!'));
        } else {
            wp_send_json_error(array('message' => 'Failed to send SMS: ' . $result['message']));
        }
    }
    
    public function flush_rules() {
        $this->verify_nonce();
        flush_rewrite_rules();
        wp_send_json_success(array('message' => 'Rewrite rules flushed.'));
    }
    
    public function recreate_tables() {
        $this->verify_nonce();
        SPFM_Database::create_tables();
        wp_send_json_success(array('message' => 'Database tables recreated.'));
    }
    
    // ==================== USERS ====================
    public function save_user() {
        $this->verify_nonce();
        
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_users';
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        
        $data = array(
            'email' => sanitize_email($_POST['email'] ?? ''),
            'full_name' => sanitize_text_field($_POST['full_name'] ?? ''),
            'role' => sanitize_text_field($_POST['role'] ?? 'user'),
            'status' => isset($_POST['status']) ? intval($_POST['status']) : 1
        );
        
        if ($id) {
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            $result = $wpdb->update($table, $data, array('id' => $id));
            $message = 'User updated successfully.';
        } else {
            $data['username'] = sanitize_text_field($_POST['username'] ?? '');
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $result = $wpdb->insert($table, $data);
            $message = 'User created successfully.';
        }
        
        if ($result !== false) {
            wp_send_json_success(array('message' => $message));
        } else {
            wp_send_json_error(array('message' => 'Failed to save user.'));
        }
    }
    
    public function delete_user() {
        $this->verify_nonce();
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_users';
        $id = intval($_POST['id'] ?? 0);
        if (!$id || $id === 1) {
            wp_send_json_error(array('message' => 'Cannot delete this user.'));
            return;
        }
        $result = $wpdb->delete($table, array('id' => $id));
        if ($result) {
            wp_send_json_success(array('message' => 'User deleted successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete user.'));
        }
    }
    
    public function get_user() {
        $this->verify_nonce();
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_users';
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid user ID.'));
            return;
        }
        $user = $wpdb->get_row($wpdb->prepare("SELECT id, username, email, full_name, role, status FROM $table WHERE id = %d", $id));
        if ($user) {
            wp_send_json_success(array('user' => $user));
        } else {
            wp_send_json_error(array('message' => 'User not found.'));
        }
    }
}
