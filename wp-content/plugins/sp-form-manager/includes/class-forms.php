<?php
/**
 * Forms Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Forms {
    
    private static $instance = null;
    private $table;
    private $fields_table;
    private $submissions_table;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'spfm_forms';
        $this->fields_table = $wpdb->prefix . 'spfm_form_fields';
        $this->submissions_table = $wpdb->prefix . 'spfm_form_submissions';
    }
    
    // Form CRUD
    public function get_all($args = array()) {
        global $wpdb;
        
        $defaults = array(
            'per_page' => 20,
            'page' => 1,
            'orderby' => 'created_at',
            'order' => 'DESC',
            'search' => '',
            'status' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $offset = ($args['page'] - 1) * $args['per_page'];
        
        $where = "WHERE 1=1";
        
        if (!empty($args['search'])) {
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $where .= $wpdb->prepare(" AND (f.name LIKE %s OR f.description LIKE %s)", $search, $search);
        }
        
        if ($args['status'] !== '') {
            $where .= $wpdb->prepare(" AND f.status = %d", $args['status']);
        }
        
        $sql = "SELECT f.*, t.name as theme_name 
                FROM {$this->table} f 
                LEFT JOIN {$wpdb->prefix}spfm_themes t ON f.theme_id = t.id 
                $where 
                ORDER BY f.created_at DESC 
                LIMIT %d OFFSET %d";
        
        return $wpdb->get_results($wpdb->prepare($sql, $args['per_page'], $offset));
    }
    
    public function get_total($args = array()) {
        global $wpdb;
        
        $where = "WHERE 1=1";
        
        if (!empty($args['search'])) {
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $where .= $wpdb->prepare(" AND (name LIKE %s OR description LIKE %s)", $search, $search);
        }
        
        if (isset($args['status']) && $args['status'] !== '') {
            $where .= $wpdb->prepare(" AND status = %d", $args['status']);
        }
        
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$this->table} $where");
    }
    
    public function get_by_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id));
    }
    
    public function create($data) {
        global $wpdb;
        
        $theme_id = !empty($data['theme_id']) ? intval($data['theme_id']) : null;
        
        $insert_data = array(
            'name' => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field(isset($data['description']) ? $data['description'] : ''),
            'theme_id' => $theme_id,
            'header_text' => sanitize_text_field(isset($data['header_text']) ? $data['header_text'] : ''),
            'footer_text' => sanitize_textarea_field(isset($data['footer_text']) ? $data['footer_text'] : ''),
            'submit_button_text' => sanitize_text_field(isset($data['submit_button_text']) ? $data['submit_button_text'] : 'Submit'),
            'success_message' => sanitize_textarea_field(isset($data['success_message']) ? $data['success_message'] : 'Thank you for your submission!'),
            'allow_customization' => isset($data['allow_customization']) ? intval($data['allow_customization']) : 1,
            'notify_admin' => isset($data['notify_admin']) ? intval($data['notify_admin']) : 1,
            'status' => isset($data['status']) ? intval($data['status']) : 1,
            'created_by' => 0
        );
        
        $result = $wpdb->insert($this->table, $insert_data);
        
        if ($result === false) {
            error_log('SPFM Form Insert Error: ' . $wpdb->last_error);
            return false;
        }
        
        return $wpdb->insert_id;
    }
    
    public function update($id, $data) {
        global $wpdb;
        
        $theme_id = !empty($data['theme_id']) ? intval($data['theme_id']) : null;
        
        $update_data = array(
            'name' => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field(isset($data['description']) ? $data['description'] : ''),
            'theme_id' => $theme_id,
            'header_text' => sanitize_text_field(isset($data['header_text']) ? $data['header_text'] : ''),
            'footer_text' => sanitize_textarea_field(isset($data['footer_text']) ? $data['footer_text'] : ''),
            'submit_button_text' => sanitize_text_field(isset($data['submit_button_text']) ? $data['submit_button_text'] : 'Submit'),
            'success_message' => sanitize_textarea_field(isset($data['success_message']) ? $data['success_message'] : ''),
            'allow_customization' => isset($data['allow_customization']) ? intval($data['allow_customization']) : 1,
            'notify_admin' => isset($data['notify_admin']) ? intval($data['notify_admin']) : 1,
            'status' => isset($data['status']) ? intval($data['status']) : 1
        );
        
        $result = $wpdb->update($this->table, $update_data, array('id' => $id));
        
        if ($result === false) {
            error_log('SPFM Form Update Error: ' . $wpdb->last_error);
            return false;
        }
        
        return true;
    }
    
    public function delete($id) {
        global $wpdb;
        $wpdb->delete($this->fields_table, array('form_id' => $id), array('%d'));
        $wpdb->delete($this->submissions_table, array('form_id' => $id), array('%d'));
        return $wpdb->delete($this->table, array('id' => $id), array('%d'));
    }
    
    public function toggle_status($id) {
        global $wpdb;
        
        $current_status = $wpdb->get_var($wpdb->prepare("SELECT status FROM {$this->table} WHERE id = %d", $id));
        $new_status = $current_status ? 0 : 1;
        
        return $wpdb->update($this->table, array('status' => $new_status), array('id' => $id), array('%d'), array('%d'));
    }
    
    // Form Fields CRUD
    public function get_fields($form_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->fields_table} WHERE form_id = %d ORDER BY field_order ASC",
            $form_id
        ));
    }
    
    public function get_field_by_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->fields_table} WHERE id = %d", $id));
    }
    
    public function add_field($form_id, $data) {
        global $wpdb;
        
        $max_order = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(field_order) FROM {$this->fields_table} WHERE form_id = %d",
            $form_id
        ));
        
        $insert_data = array(
            'form_id' => intval($form_id),
            'field_label' => sanitize_text_field($data['field_label']),
            'field_name' => sanitize_title($data['field_name']),
            'field_type' => sanitize_text_field($data['field_type']),
            'field_options' => isset($data['field_options']) ? sanitize_textarea_field($data['field_options']) : '',
            'placeholder' => sanitize_text_field(isset($data['placeholder']) ? $data['placeholder'] : ''),
            'default_value' => sanitize_text_field(isset($data['default_value']) ? $data['default_value'] : ''),
            'is_required' => isset($data['is_required']) ? intval($data['is_required']) : 0,
            'field_order' => ($max_order !== null) ? intval($max_order) + 1 : 0,
            'validation_rules' => isset($data['validation_rules']) ? sanitize_textarea_field($data['validation_rules']) : '',
            'css_class' => sanitize_text_field(isset($data['css_class']) ? $data['css_class'] : ''),
            'status' => isset($data['status']) ? intval($data['status']) : 1
        );
        
        $result = $wpdb->insert($this->fields_table, $insert_data);
        
        return $result === false ? false : $wpdb->insert_id;
    }
    
    public function update_field($field_id, $data) {
        global $wpdb;
        
        $update_data = array(
            'field_label' => sanitize_text_field($data['field_label']),
            'field_name' => sanitize_title($data['field_name']),
            'field_type' => sanitize_text_field($data['field_type']),
            'field_options' => isset($data['field_options']) ? sanitize_textarea_field($data['field_options']) : '',
            'placeholder' => sanitize_text_field(isset($data['placeholder']) ? $data['placeholder'] : ''),
            'default_value' => sanitize_text_field(isset($data['default_value']) ? $data['default_value'] : ''),
            'is_required' => isset($data['is_required']) ? intval($data['is_required']) : 0,
            'validation_rules' => isset($data['validation_rules']) ? sanitize_textarea_field($data['validation_rules']) : '',
            'css_class' => sanitize_text_field(isset($data['css_class']) ? $data['css_class'] : ''),
            'status' => isset($data['status']) ? intval($data['status']) : 1
        );
        
        return $wpdb->update($this->fields_table, $update_data, array('id' => $field_id)) !== false;
    }
    
    public function delete_field($field_id) {
        global $wpdb;
        return $wpdb->delete($this->fields_table, array('id' => $field_id), array('%d'));
    }
    
    public function reorder_fields($form_id, $field_orders) {
        global $wpdb;
        
        foreach ($field_orders as $field_id => $order) {
            $wpdb->update(
                $this->fields_table,
                array('field_order' => intval($order)),
                array('id' => intval($field_id), 'form_id' => intval($form_id))
            );
        }
        
        return true;
    }
    
    // Field Types
    public function get_field_types() {
        return array(
            'text' => 'Text Input',
            'email' => 'Email',
            'number' => 'Number',
            'phone' => 'Phone',
            'textarea' => 'Textarea',
            'select' => 'Dropdown Select',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkboxes',
            'date' => 'Date Picker',
            'time' => 'Time Picker',
            'datetime' => 'Date & Time',
            'file' => 'File Upload',
            'url' => 'URL',
            'password' => 'Password',
            'hidden' => 'Hidden Field',
            'heading' => 'Section Heading',
            'paragraph' => 'Paragraph Text',
            'divider' => 'Divider'
        );
    }
    
    // Submissions
    public function get_submission_count($form_id) {
        global $wpdb;
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->submissions_table} WHERE form_id = %d",
            $form_id
        ));
    }
    
    public function save_submission($form_id, $data, $customer_id = null) {
        global $wpdb;
        
        $insert_data = array(
            'form_id' => intval($form_id),
            'customer_id' => $customer_id ? intval($customer_id) : null,
            'submission_data' => json_encode($data),
            'ip_address' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            'status' => 'new'
        );
        
        $result = $wpdb->insert($this->submissions_table, $insert_data);
        
        return $result === false ? false : $wpdb->insert_id;
    }
    
    // Render Form HTML
    public function render_form($form_id) {
        $form = $this->get_by_id($form_id);
        
        if (!$form || !$form->status) {
            return '<p>Form not found or inactive.</p>';
        }
        
        $fields = $this->get_fields($form_id);
        
        if (empty($fields)) {
            return '<p>This form has no fields.</p>';
        }
        
        $theme_css = '';
        if ($form->theme_id) {
            $themes = SPFM_Themes::get_instance();
            $theme_css = $themes->get_theme_css($form->theme_id);
        }
        
        ob_start();
        ?>
        <style><?php echo $theme_css; ?></style>
        <div class="spfm-form-wrapper">
            <form id="spfm-form-<?php echo $form_id; ?>" class="spfm-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="spfm_form_id" value="<?php echo $form_id; ?>">
                <?php wp_nonce_field('spfm_submit_form_' . $form_id, 'spfm_form_nonce'); ?>
                
                <?php foreach ($fields as $field): ?>
                    <?php if ($field->status): ?>
                        <?php $this->render_field($field); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <div class="spfm-form-group">
                    <button type="submit" class="btn btn-primary spfm-submit-btn">
                        <?php echo esc_html($form->submit_button_text ?: 'Submit'); ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    private function render_field($field) {
        $required = $field->is_required ? 'required' : '';
        $required_mark = $field->is_required ? '<span class="required">*</span>' : '';
        
        echo '<div class="spfm-form-group ' . esc_attr($field->css_class) . '">';
        
        switch ($field->field_type) {
            case 'text':
            case 'email':
            case 'number':
            case 'phone':
            case 'url':
            case 'password':
            case 'date':
            case 'time':
            case 'datetime':
                echo '<label for="' . esc_attr($field->field_name) . '">' . esc_html($field->field_label) . $required_mark . '</label>';
                $type = $field->field_type === 'phone' ? 'tel' : ($field->field_type === 'datetime' ? 'datetime-local' : $field->field_type);
                echo '<input type="' . esc_attr($type) . '" 
                        name="' . esc_attr($field->field_name) . '" 
                        id="' . esc_attr($field->field_name) . '" 
                        class="form-control" 
                        placeholder="' . esc_attr($field->placeholder) . '" 
                        value="' . esc_attr($field->default_value) . '" 
                        ' . $required . '>';
                break;
                
            case 'textarea':
                echo '<label for="' . esc_attr($field->field_name) . '">' . esc_html($field->field_label) . $required_mark . '</label>';
                echo '<textarea name="' . esc_attr($field->field_name) . '" 
                        id="' . esc_attr($field->field_name) . '" 
                        class="form-control" 
                        placeholder="' . esc_attr($field->placeholder) . '" 
                        rows="4" 
                        ' . $required . '>' . esc_textarea($field->default_value) . '</textarea>';
                break;
                
            case 'select':
                echo '<label for="' . esc_attr($field->field_name) . '">' . esc_html($field->field_label) . $required_mark . '</label>';
                echo '<select name="' . esc_attr($field->field_name) . '" id="' . esc_attr($field->field_name) . '" class="form-control" ' . $required . '>';
                echo '<option value="">Select...</option>';
                $options = explode("\n", $field->field_options);
                foreach ($options as $option) {
                    $option = trim($option);
                    if (!empty($option)) {
                        echo '<option value="' . esc_attr($option) . '">' . esc_html($option) . '</option>';
                    }
                }
                echo '</select>';
                break;
                
            case 'radio':
                echo '<label>' . esc_html($field->field_label) . $required_mark . '</label>';
                echo '<div class="spfm-radio-group">';
                $options = explode("\n", $field->field_options);
                foreach ($options as $index => $option) {
                    $option = trim($option);
                    if (!empty($option)) {
                        echo '<div class="form-check">';
                        echo '<input type="radio" name="' . esc_attr($field->field_name) . '" id="' . esc_attr($field->field_name . '_' . $index) . '" value="' . esc_attr($option) . '" class="form-check-input" ' . ($index === 0 && $field->is_required ? 'required' : '') . '>';
                        echo '<label class="form-check-label" for="' . esc_attr($field->field_name . '_' . $index) . '">' . esc_html($option) . '</label>';
                        echo '</div>';
                    }
                }
                echo '</div>';
                break;
                
            case 'checkbox':
                echo '<label>' . esc_html($field->field_label) . $required_mark . '</label>';
                echo '<div class="spfm-checkbox-group">';
                $options = explode("\n", $field->field_options);
                foreach ($options as $index => $option) {
                    $option = trim($option);
                    if (!empty($option)) {
                        echo '<div class="form-check">';
                        echo '<input type="checkbox" name="' . esc_attr($field->field_name) . '[]" id="' . esc_attr($field->field_name . '_' . $index) . '" value="' . esc_attr($option) . '" class="form-check-input">';
                        echo '<label class="form-check-label" for="' . esc_attr($field->field_name . '_' . $index) . '">' . esc_html($option) . '</label>';
                        echo '</div>';
                    }
                }
                echo '</div>';
                break;
                
            case 'file':
                echo '<label for="' . esc_attr($field->field_name) . '">' . esc_html($field->field_label) . $required_mark . '</label>';
                echo '<input type="file" name="' . esc_attr($field->field_name) . '" id="' . esc_attr($field->field_name) . '" class="form-control" ' . $required . '>';
                break;
                
            case 'hidden':
                echo '<input type="hidden" name="' . esc_attr($field->field_name) . '" value="' . esc_attr($field->default_value) . '">';
                break;
                
            case 'heading':
                echo '<h3 class="spfm-section-heading">' . esc_html($field->field_label) . '</h3>';
                break;
                
            case 'paragraph':
                echo '<p class="spfm-paragraph">' . esc_html($field->field_options) . '</p>';
                break;
                
            case 'divider':
                echo '<hr class="spfm-divider">';
                break;
        }
        
        echo '</div>';
    }
}
