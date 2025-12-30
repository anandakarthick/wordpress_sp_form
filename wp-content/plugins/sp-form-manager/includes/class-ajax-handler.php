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
        add_action('wp_ajax_spfm_get_theme_pages', array($this, 'get_theme_pages'));
        add_action('wp_ajax_spfm_toggle_theme_status', array($this, 'toggle_theme_status'));
        add_action('wp_ajax_spfm_duplicate_theme', array($this, 'duplicate_theme'));
        add_action('wp_ajax_spfm_theme_preview', array($this, 'theme_preview'));
        
        // Form AJAX actions
        add_action('wp_ajax_spfm_save_form', array($this, 'save_form'));
        add_action('wp_ajax_spfm_delete_form', array($this, 'delete_form'));
        add_action('wp_ajax_spfm_get_form', array($this, 'get_form'));
        
        // Share actions
        add_action('wp_ajax_spfm_share_form', array($this, 'share_form'));
        add_action('wp_ajax_spfm_get_share_link', array($this, 'get_share_link'));
        
        // Customer form submission (public)
        add_action('wp_ajax_spfm_customer_submit', array($this, 'customer_submit'));
        add_action('wp_ajax_nopriv_spfm_customer_submit', array($this, 'customer_submit'));
        
        // Submission actions
        add_action('wp_ajax_spfm_get_submission', array($this, 'get_submission'));
        add_action('wp_ajax_spfm_delete_submission', array($this, 'delete_submission'));
        add_action('wp_ajax_spfm_update_submission_status', array($this, 'update_submission_status'));
        add_action('wp_ajax_spfm_render_submission_preview', array($this, 'render_submission_preview'));
        
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
    
    private function verify_nonce_get() {
        if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'spfm_nonce')) {
            wp_die('Security check failed.');
        }
    }
    
    // ==================== CUSTOMERS ====================
    public function save_customer() {
        $this->verify_nonce();
        
        $customers = SPFM_Customers::get_instance();
        
        $data = array(
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'email' => sanitize_email($_POST['email'] ?? ''),
            'phone' => sanitize_text_field($_POST['phone'] ?? ''),
            'company' => sanitize_text_field($_POST['company'] ?? ''),
            'address' => sanitize_textarea_field($_POST['address'] ?? ''),
            'city' => sanitize_text_field($_POST['city'] ?? ''),
            'state' => sanitize_text_field($_POST['state'] ?? ''),
            'country' => sanitize_text_field($_POST['country'] ?? ''),
            'zip_code' => sanitize_text_field($_POST['zip_code'] ?? ''),
            'notes' => sanitize_textarea_field($_POST['notes'] ?? ''),
            'status' => isset($_POST['status']) ? intval($_POST['status']) : 1
        );
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        
        if ($id) {
            $result = $customers->update($id, $data);
            $message = 'Customer updated successfully.';
        } else {
            $result = $customers->create($data);
            $message = 'Customer created successfully.';
        }
        
        if ($result) {
            wp_send_json_success(array('message' => $message, 'id' => $result));
        } else {
            wp_send_json_error(array('message' => 'Failed to save customer.'));
        }
    }
    
    public function delete_customer() {
        $this->verify_nonce();
        
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid customer ID.'));
            return;
        }
        
        $customers = SPFM_Customers::get_instance();
        if ($customers->delete($id)) {
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
            return;
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
        
        $themes = SPFM_Themes::get_instance();
        
        // Handle features array
        $features = array();
        if (isset($_POST['features']) && is_array($_POST['features'])) {
            foreach ($_POST['features'] as $feature) {
                $feature = sanitize_text_field($feature);
                if (!empty($feature)) {
                    $features[] = $feature;
                }
            }
        }
        
        $data = array(
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'description' => sanitize_textarea_field($_POST['description'] ?? ''),
            'category' => sanitize_text_field($_POST['category'] ?? 'business'),
            'preview_image' => esc_url_raw($_POST['preview_image'] ?? ''),
            'primary_color' => sanitize_hex_color($_POST['primary_color'] ?? '#667eea'),
            'secondary_color' => sanitize_hex_color($_POST['secondary_color'] ?? '#764ba2'),
            'accent_color' => sanitize_hex_color($_POST['accent_color'] ?? '#28a745'),
            'background_color' => sanitize_hex_color($_POST['background_color'] ?? '#ffffff'),
            'text_color' => sanitize_hex_color($_POST['text_color'] ?? '#333333'),
            'header_bg_color' => sanitize_hex_color($_POST['header_bg_color'] ?? '#ffffff'),
            'footer_bg_color' => sanitize_hex_color($_POST['footer_bg_color'] ?? '#1a1a2e'),
            'font_family' => sanitize_text_field($_POST['font_family'] ?? 'Poppins'),
            'heading_font' => sanitize_text_field($_POST['heading_font'] ?? 'Poppins'),
            'features' => $features,
            'status' => isset($_POST['status']) ? 1 : 0
        );
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        
        if ($id) {
            $result = $themes->update($id, $data);
            $message = 'Theme updated successfully.';
        } else {
            // Handle duplicate from template
            if (!empty($_POST['duplicate_from'])) {
                $data['duplicate_from'] = intval($_POST['duplicate_from']);
            }
            $result = $themes->create($data);
            $message = 'Theme created successfully.';
        }
        
        if ($result) {
            wp_send_json_success(array('message' => $message, 'id' => $result));
        } else {
            wp_send_json_error(array('message' => 'Failed to save theme.'));
        }
    }
    
    public function delete_theme() {
        $this->verify_nonce();
        
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid theme ID.'));
            return;
        }
        
        $themes = SPFM_Themes::get_instance();
        $theme = $themes->get_by_id($id);
        
        if ($theme && $theme->is_template) {
            wp_send_json_error(array('message' => 'Cannot delete pre-built templates.'));
            return;
        }
        
        if ($themes->delete($id)) {
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
            return;
        }
        
        $themes = SPFM_Themes::get_instance();
        $theme = $themes->get_theme_complete($id);
        
        if ($theme) {
            wp_send_json_success(array('theme' => $theme));
        } else {
            wp_send_json_error(array('message' => 'Theme not found.'));
        }
    }
    
    public function get_theme_pages() {
        $this->verify_nonce();
        
        $theme_id = intval($_POST['theme_id'] ?? 0);
        if (!$theme_id) {
            wp_send_json_error(array('message' => 'Invalid theme ID.'));
            return;
        }
        
        $themes = SPFM_Themes::get_instance();
        $theme = $themes->get_theme_complete($theme_id);
        
        if (!$theme) {
            wp_send_json_error(array('message' => 'Theme not found.'));
            return;
        }
        
        // Build HTML for pages
        ob_start();
        ?>
        <div class="theme-pages-detail">
            <?php foreach ($theme->pages as $page): ?>
                <div class="page-detail-card">
                    <div class="page-detail-header">
                        <span class="dashicons <?php echo esc_attr($page->page_icon); ?>"></span>
                        <h4><?php echo esc_html($page->page_name); ?></h4>
                        <?php if ($page->is_required): ?>
                            <span class="required-badge">Required</span>
                        <?php endif; ?>
                    </div>
                    <p class="page-description"><?php echo esc_html($page->page_description); ?></p>
                    
                    <?php if (!empty($page->sections)): ?>
                        <div class="page-sections">
                            <h5>Sections:</h5>
                            <?php foreach ($page->sections as $section): ?>
                                <div class="section-item">
                                    <strong><?php echo esc_html($section->section_name); ?></strong>
                                    <span class="section-type"><?php echo esc_html($section->section_type); ?></span>
                                    <?php if (!empty($section->fields)): ?>
                                        <div class="section-fields">
                                            <?php foreach ($section->fields as $field): ?>
                                                <span class="field-tag">
                                                    <?php echo esc_html($field['label']); ?>
                                                    <small>(<?php echo esc_html($field['type']); ?>)</small>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <style>
            .theme-pages-detail { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
            .page-detail-card { background: #f8f9fa; border-radius: 10px; padding: 20px; }
            .page-detail-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
            .page-detail-header h4 { margin: 0; flex: 1; }
            .page-detail-header .dashicons { color: #667eea; }
            .required-badge { font-size: 10px; background: #dc3545; color: #fff; padding: 2px 8px; border-radius: 10px; }
            .page-description { color: #666; font-size: 13px; margin-bottom: 15px; }
            .page-sections h5 { font-size: 12px; color: #999; margin: 0 0 10px 0; text-transform: uppercase; }
            .section-item { background: #fff; padding: 12px; border-radius: 6px; margin-bottom: 10px; }
            .section-item strong { display: block; margin-bottom: 5px; }
            .section-type { font-size: 11px; background: #e9ecef; padding: 2px 8px; border-radius: 10px; }
            .section-fields { margin-top: 10px; display: flex; flex-wrap: wrap; gap: 5px; }
            .field-tag { font-size: 11px; background: #fff3cd; padding: 3px 8px; border-radius: 10px; }
            .field-tag small { color: #999; }
        </style>
        <?php
        $html = ob_get_clean();
        
        wp_send_json_success(array(
            'theme_name' => $theme->name,
            'html' => $html
        ));
    }
    
    public function toggle_theme_status() {
        $this->verify_nonce();
        
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid theme ID.'));
            return;
        }
        
        $themes = SPFM_Themes::get_instance();
        if ($themes->toggle_status($id)) {
            wp_send_json_success(array('message' => 'Status updated.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update status.'));
        }
    }
    
    public function duplicate_theme() {
        $this->verify_nonce();
        
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid theme ID.'));
            return;
        }
        
        $themes = SPFM_Themes::get_instance();
        $new_id = $themes->duplicate_template($id);
        
        if ($new_id) {
            wp_send_json_success(array('message' => 'Theme duplicated.', 'id' => $new_id));
        } else {
            wp_send_json_error(array('message' => 'Failed to duplicate theme.'));
        }
    }
    
    public function theme_preview() {
        $this->verify_nonce_get();
        
        $id = intval($_GET['id'] ?? 0);
        if (!$id) {
            wp_die('Invalid theme ID.');
        }
        
        $themes = SPFM_Themes::get_instance();
        $theme = $themes->get_theme_complete($id);
        
        if (!$theme) {
            wp_die('Theme not found.');
        }
        
        // Render preview
        include SPFM_PLUGIN_PATH . 'templates/theme-preview.php';
        exit;
    }
    
    // ==================== FORMS ====================
    public function save_form() {
        $this->verify_nonce();
        
        $forms = SPFM_Forms::get_instance();
        
        $data = array(
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'description' => sanitize_textarea_field($_POST['description'] ?? ''),
            'available_themes' => $_POST['available_themes'] ?? '[]',
            'allow_theme_selection' => isset($_POST['allow_theme_selection']) ? 1 : 0,
            'allow_color_customization' => isset($_POST['allow_color_customization']) ? 1 : 0,
            'header_text' => sanitize_text_field($_POST['header_text'] ?? ''),
            'footer_text' => sanitize_text_field($_POST['footer_text'] ?? ''),
            'success_message' => sanitize_textarea_field($_POST['success_message'] ?? ''),
            'notify_admin' => isset($_POST['notify_admin']) ? 1 : 0,
            'status' => isset($_POST['status']) ? intval($_POST['status']) : 1
        );
        
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        
        if ($id) {
            $result = $forms->update($id, $data);
            $message = 'Form updated successfully.';
        } else {
            $result = $forms->create($data);
            $message = 'Form created successfully.';
        }
        
        if ($result) {
            wp_send_json_success(array('message' => $message, 'id' => $result));
        } else {
            wp_send_json_error(array('message' => 'Failed to save form.'));
        }
    }
    
    public function delete_form() {
        $this->verify_nonce();
        
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid form ID.'));
            return;
        }
        
        $forms = SPFM_Forms::get_instance();
        if ($forms->delete($id)) {
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
            return;
        }
        
        $forms = SPFM_Forms::get_instance();
        $form = $forms->get_by_id($id);
        
        if ($form) {
            wp_send_json_success(array('form' => $form));
        } else {
            wp_send_json_error(array('message' => 'Form not found.'));
        }
    }
    
    // ==================== SHARE ====================
    public function share_form() {
        $this->verify_nonce();
        
        $form_id = intval($_POST['form_id'] ?? 0);
        $method = sanitize_text_field($_POST['method'] ?? 'link');
        $customer_id = intval($_POST['customer_id'] ?? 0);
        
        if (!$form_id) {
            wp_send_json_error(array('message' => 'Invalid form ID.'));
            return;
        }
        
        $forms = SPFM_Forms::get_instance();
        $form = $forms->get_by_id($form_id);
        
        if (!$form) {
            wp_send_json_error(array('message' => 'Form not found.'));
            return;
        }
        
        $share_handler = SPFM_Share::get_instance();
        
        $shared_to = '';
        if ($method === 'email') {
            $shared_to = sanitize_email($_POST['email'] ?? '');
        } elseif ($method === 'sms' || $method === 'whatsapp') {
            $shared_to = sanitize_text_field($_POST['phone'] ?? '');
        }
        
        $share_data = array(
            'form_id' => $form_id,
            'customer_id' => $customer_id,
            'shared_via' => $method,
            'shared_to' => $shared_to
        );
        
        $share = $share_handler->create_share($share_data);
        
        if (!$share) {
            wp_send_json_error(array('message' => 'Failed to create share link.'));
            return;
        }
        
        // Send notification
        if ($method === 'email' && !empty($shared_to)) {
            $result = SPFM_Settings::send_share_notification('email', $shared_to, $form->name, $share['url']);
            if (!$result) {
                wp_send_json_error(array('message' => 'Share created but email failed to send.'));
                return;
            }
        } elseif (($method === 'sms' || $method === 'whatsapp') && !empty($shared_to)) {
            $result = SPFM_Settings::send_share_notification('sms', $shared_to, $form->name, $share['url']);
            if (!$result['success']) {
                wp_send_json_error(array('message' => $result['message']));
                return;
            }
        }
        
        wp_send_json_success(array(
            'message' => 'Form shared successfully!',
            'url' => $share['url']
        ));
    }
    
    public function get_share_link() {
        $this->verify_nonce();
        
        $form_id = intval($_POST['form_id'] ?? 0);
        if (!$form_id) {
            wp_send_json_error(array('message' => 'Invalid form ID.'));
            return;
        }
        
        $share_handler = SPFM_Share::get_instance();
        $share = $share_handler->create_share(array(
            'form_id' => $form_id,
            'shared_via' => 'link'
        ));
        
        if ($share) {
            wp_send_json_success(array('url' => $share['url']));
        } else {
            wp_send_json_error(array('message' => 'Failed to generate link.'));
        }
    }
    
    // ==================== CUSTOMER SUBMISSION ====================
    public function customer_submit() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'spfm_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed.'));
            return;
        }
        
        $token = sanitize_text_field($_POST['token'] ?? '');
        $form_id = intval($_POST['form_id'] ?? 0);
        $selected_theme_id = intval($_POST['selected_theme_id'] ?? 0);
        
        if (!$token || !$form_id || !$selected_theme_id) {
            wp_send_json_error(array('message' => 'Missing required data.'));
            return;
        }
        
        // Verify share token
        $share_handler = SPFM_Share::get_instance();
        $share = $share_handler->get_by_token($token);
        
        if (!$share || $share->form_id != $form_id) {
            wp_send_json_error(array('message' => 'Invalid submission.'));
            return;
        }
        
        // Get form
        $forms_handler = SPFM_Forms::get_instance();
        $form = $forms_handler->get_by_id($form_id);
        
        if (!$form) {
            wp_send_json_error(array('message' => 'Form not found.'));
            return;
        }
        
        // Prepare submission data
        $submission_data = array(
            'form_id' => $form_id,
            'share_id' => $share->id,
            'customer_id' => $share->customer_id,
            'selected_theme_id' => $selected_theme_id,
            'page_contents' => $_POST['page_contents'] ?? '{}',
            'color_customizations' => $_POST['color_customizations'] ?? '{}',
            'customer_info' => json_encode(array(
                'name' => sanitize_text_field($_POST['customer_name'] ?? ''),
                'email' => sanitize_email($_POST['customer_email'] ?? ''),
                'phone' => sanitize_text_field($_POST['customer_phone'] ?? '')
            ))
        );
        
        // Save submission
        $submission_id = $forms_handler->save_submission($submission_data);
        
        if (!$submission_id) {
            wp_send_json_error(array('message' => 'Failed to save submission.'));
            return;
        }
        
        // Update share status
        $share_handler->update_status($share->id, 'submitted');
        
        // Send notification
        if ($form->notify_admin) {
            $this->send_submission_notification($form, $submission_id, $submission_data);
        }
        
        wp_send_json_success(array(
            'message' => 'Submission saved successfully!',
            'submission_id' => $submission_id
        ));
    }
    
    private function send_submission_notification($form, $submission_id, $data) {
        $admin_email = get_option('spfm_admin_email', get_option('admin_email'));
        
        $themes_handler = SPFM_Themes::get_instance();
        $theme = $themes_handler->get_by_id($data['selected_theme_id']);
        
        $customer_info = json_decode($data['customer_info'], true);
        
        $subject = 'New Website Order: ' . $form->name;
        
        $content = '<h2>New Website Order Received</h2>';
        $content .= '<p>A new website order has been submitted.</p>';
        
        $content .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold;">Form</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . esc_html($form->name) . '</td></tr>';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold;">Selected Template</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . esc_html($theme ? $theme->name : 'Unknown') . '</td></tr>';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold;">Customer Name</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . esc_html($customer_info['name'] ?? '') . '</td></tr>';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold;">Customer Email</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . esc_html($customer_info['email'] ?? '') . '</td></tr>';
        $content .= '<tr><td style="padding: 12px; border: 1px solid #eee; background: #f8f9fa; font-weight: bold;">Customer Phone</td>';
        $content .= '<td style="padding: 12px; border: 1px solid #eee;">' . esc_html($customer_info['phone'] ?? '') . '</td></tr>';
        $content .= '</table>';
        
        $content .= '<p><a href="' . admin_url('admin.php?page=spfm-submissions&action=view&id=' . $submission_id) . '" style="display: inline-block; background: #667eea; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 5px;">View Full Submission</a></p>';
        
        SPFM_Settings::send_email($admin_email, $subject, $content);
    }
    
    // ==================== SUBMISSIONS ====================
    public function get_submission() {
        $this->verify_nonce();
        
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid submission ID.'));
            return;
        }
        
        $forms = SPFM_Forms::get_instance();
        $submission = $forms->get_submission_by_id($id);
        
        if ($submission) {
            $submission->page_contents = json_decode($submission->page_contents, true);
            $submission->color_customizations = json_decode($submission->color_customizations, true);
            $submission->customer_info = json_decode($submission->customer_info, true);
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
        
        $forms = SPFM_Forms::get_instance();
        if ($forms->delete_submission($id)) {
            wp_send_json_success(array('message' => 'Submission deleted successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete submission.'));
        }
    }
    
    public function update_submission_status() {
        $this->verify_nonce();
        
        $id = intval($_POST['id'] ?? 0);
        $status = sanitize_text_field($_POST['status'] ?? 'new');
        
        if (!$id) {
            wp_send_json_error(array('message' => 'Invalid submission ID.'));
            return;
        }
        
        $forms = SPFM_Forms::get_instance();
        if ($forms->update_submission_status($id, $status) !== false) {
            wp_send_json_success(array('message' => 'Status updated successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update status.'));
        }
    }
    
    public function render_submission_preview() {
        $this->verify_nonce_get();
        
        $id = intval($_GET['id'] ?? 0);
        if (!$id) {
            wp_die('Invalid submission ID.');
        }
        
        $forms = SPFM_Forms::get_instance();
        $submission = $forms->get_submission_by_id($id);
        
        if (!$submission) {
            wp_die('Submission not found.');
        }
        
        $themes = SPFM_Themes::get_instance();
        $theme = $themes->get_theme_complete($submission->selected_theme_id);
        
        if (!$theme) {
            wp_die('Theme not found.');
        }
        
        $page_contents = json_decode($submission->page_contents, true) ?: array();
        $color_customizations = json_decode($submission->color_customizations, true) ?: array();
        
        // Include preview template
        include SPFM_PLUGIN_PATH . 'templates/submission-preview.php';
        exit;
    }
    
    // ==================== SETTINGS ====================
    public function save_settings() {
        $this->verify_nonce();
        
        $settings = array(
            'admin_email' => sanitize_email($_POST['admin_email'] ?? ''),
            'email_from_name' => sanitize_text_field($_POST['email_from_name'] ?? ''),
            'email_from_address' => sanitize_email($_POST['email_from_address'] ?? ''),
            'smtp_enabled' => isset($_POST['smtp_enabled']) ? 1 : 0,
            'smtp_host' => sanitize_text_field($_POST['smtp_host'] ?? ''),
            'smtp_port' => intval($_POST['smtp_port'] ?? 587),
            'smtp_encryption' => sanitize_text_field($_POST['smtp_encryption'] ?? 'tls'),
            'smtp_username' => sanitize_text_field($_POST['smtp_username'] ?? ''),
            'smtp_password' => sanitize_text_field($_POST['smtp_password'] ?? ''),
            'nexmo_api_key' => sanitize_text_field($_POST['nexmo_api_key'] ?? ''),
            'nexmo_api_secret' => sanitize_text_field($_POST['nexmo_api_secret'] ?? ''),
            'nexmo_from_number' => sanitize_text_field($_POST['nexmo_from_number'] ?? '')
        );
        
        foreach ($settings as $key => $value) {
            update_option('spfm_' . $key, $value);
        }
        
        wp_send_json_success(array('message' => 'Settings saved successfully.'));
    }
    
    public function test_email() {
        $this->verify_nonce();
        
        $email = sanitize_email($_POST['email'] ?? '');
        if (!$email) {
            wp_send_json_error(array('message' => 'Please enter an email address.'));
            return;
        }
        
        $subject = 'SP Form Manager - Test Email';
        $message = '<h2>Test Email</h2><p>This is a test email from SP Form Manager.</p><p>If you received this, your email settings are working correctly!</p>';
        
        $result = SPFM_Settings::send_email($email, $subject, $message);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Test email sent successfully!'));
        } else {
            wp_send_json_error(array('message' => 'Failed to send test email. Check your settings.'));
        }
    }
    
    public function test_sms() {
        $this->verify_nonce();
        
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        if (!$phone) {
            wp_send_json_error(array('message' => 'Please enter a phone number.'));
            return;
        }
        
        $result = SPFM_Settings::send_whatsapp($phone, 'SP Form Manager - Test SMS. Your settings are working!');
        
        if ($result['success']) {
            wp_send_json_success(array('message' => 'Test SMS sent successfully!'));
        } else {
            wp_send_json_error(array('message' => $result['message']));
        }
    }
    
    public function flush_rules() {
        $this->verify_nonce();
        flush_rewrite_rules();
        wp_send_json_success(array('message' => 'Rewrite rules flushed.'));
    }
    
    public function recreate_tables() {
        $this->verify_nonce();
        
        // Force recreation of hospital templates by deleting old ones first
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        $sections_table = $wpdb->prefix . 'spfm_page_sections';
        
        // Delete old template data
        $wpdb->query("DELETE FROM $sections_table WHERE page_id IN (SELECT id FROM $pages_table WHERE theme_id IN (SELECT id FROM $themes_table WHERE is_template = 1))");
        $wpdb->query("DELETE FROM $pages_table WHERE theme_id IN (SELECT id FROM $themes_table WHERE is_template = 1)");
        $wpdb->query("DELETE FROM $themes_table WHERE is_template = 1");
        
        // Update DB version to force table recreation
        delete_option('spfm_db_version');
        
        // Recreate tables and templates
        SPFM_Database::create_tables();
        
        wp_send_json_success(array('message' => 'Database tables and hospital templates recreated successfully!'));
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
        
        if ($wpdb->delete($table, array('id' => $id))) {
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
        
        $user = $wpdb->get_row($wpdb->prepare(
            "SELECT id, username, email, full_name, role, status FROM $table WHERE id = %d",
            $id
        ));
        
        if ($user) {
            wp_send_json_success(array('user' => $user));
        } else {
            wp_send_json_error(array('message' => 'User not found.'));
        }
    }
}
