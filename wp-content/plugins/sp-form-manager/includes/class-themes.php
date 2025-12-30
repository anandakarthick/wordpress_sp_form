<?php
/**
 * Themes Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Themes {
    
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
        $this->table = $wpdb->prefix . 'spfm_themes';
    }
    
    public function get_all($args = array()) {
        global $wpdb;
        
        $defaults = array(
            'per_page' => 50,
            'page' => 1,
            'orderby' => 'created_at',
            'order' => 'DESC',
            'search' => '',
            'status' => '',
            'is_template' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $offset = ($args['page'] - 1) * $args['per_page'];
        
        $where = "WHERE 1=1";
        
        if (!empty($args['search'])) {
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $where .= $wpdb->prepare(" AND (name LIKE %s OR description LIKE %s)", $search, $search);
        }
        
        if ($args['status'] !== '') {
            $where .= $wpdb->prepare(" AND status = %d", $args['status']);
        }
        
        if ($args['is_template'] !== '') {
            $where .= $wpdb->prepare(" AND is_template = %d", $args['is_template']);
        }
        
        $orderby = sanitize_sql_orderby($args['orderby'] . ' ' . $args['order']);
        if (!$orderby) {
            $orderby = 'created_at DESC';
        }
        
        $sql = "SELECT * FROM {$this->table} $where ORDER BY $orderby LIMIT %d OFFSET %d";
        
        return $wpdb->get_results($wpdb->prepare($sql, $args['per_page'], $offset));
    }
    
    public function get_templates() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->table} WHERE is_template = 1 ORDER BY name ASC");
    }
    
    public function get_all_active() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->table} WHERE status = 1 ORDER BY is_template DESC, name ASC");
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
        
        if (isset($args['is_template']) && $args['is_template'] !== '') {
            $where .= $wpdb->prepare(" AND is_template = %d", $args['is_template']);
        }
        
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$this->table} $where");
    }
    
    public function get_by_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id));
    }
    
    public function create($data) {
        global $wpdb;
        
        $insert_data = array(
            'name' => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field(isset($data['description']) ? $data['description'] : ''),
            'template_type' => sanitize_text_field(isset($data['template_type']) ? $data['template_type'] : 'custom'),
            'preview_image' => esc_url_raw(isset($data['preview_image']) ? $data['preview_image'] : ''),
            'primary_color' => $this->sanitize_color($data['primary_color'] ?? '#007bff'),
            'secondary_color' => $this->sanitize_color($data['secondary_color'] ?? '#6c757d'),
            'background_color' => $this->sanitize_color($data['background_color'] ?? '#ffffff'),
            'text_color' => $this->sanitize_color($data['text_color'] ?? '#333333'),
            'accent_color' => $this->sanitize_color($data['accent_color'] ?? '#28a745'),
            'header_bg_color' => $this->sanitize_color($data['header_bg_color'] ?? '#667eea'),
            'button_style' => sanitize_text_field(isset($data['button_style']) ? $data['button_style'] : 'rounded'),
            'font_family' => sanitize_text_field($data['font_family'] ?? 'Arial, sans-serif'),
            'header_font' => sanitize_text_field($data['header_font'] ?? 'Arial, sans-serif'),
            'custom_css' => isset($data['custom_css']) ? $data['custom_css'] : '',
            'layout_style' => sanitize_text_field(isset($data['layout_style']) ? $data['layout_style'] : 'default'),
            'is_template' => isset($data['is_template']) ? intval($data['is_template']) : 0,
            'status' => isset($data['status']) ? intval($data['status']) : 1,
            'created_by' => 0
        );
        
        $result = $wpdb->insert($this->table, $insert_data);
        
        if ($result === false) {
            error_log('SPFM Theme Insert Error: ' . $wpdb->last_error);
            return false;
        }
        
        return $wpdb->insert_id;
    }
    
    public function update($id, $data) {
        global $wpdb;
        
        $update_data = array(
            'name' => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field(isset($data['description']) ? $data['description'] : ''),
            'template_type' => sanitize_text_field(isset($data['template_type']) ? $data['template_type'] : 'custom'),
            'preview_image' => esc_url_raw(isset($data['preview_image']) ? $data['preview_image'] : ''),
            'primary_color' => $this->sanitize_color($data['primary_color'] ?? '#007bff'),
            'secondary_color' => $this->sanitize_color($data['secondary_color'] ?? '#6c757d'),
            'background_color' => $this->sanitize_color($data['background_color'] ?? '#ffffff'),
            'text_color' => $this->sanitize_color($data['text_color'] ?? '#333333'),
            'accent_color' => $this->sanitize_color($data['accent_color'] ?? '#28a745'),
            'header_bg_color' => $this->sanitize_color($data['header_bg_color'] ?? '#667eea'),
            'button_style' => sanitize_text_field(isset($data['button_style']) ? $data['button_style'] : 'rounded'),
            'font_family' => sanitize_text_field($data['font_family'] ?? 'Arial, sans-serif'),
            'header_font' => sanitize_text_field($data['header_font'] ?? 'Arial, sans-serif'),
            'custom_css' => isset($data['custom_css']) ? $data['custom_css'] : '',
            'layout_style' => sanitize_text_field(isset($data['layout_style']) ? $data['layout_style'] : 'default'),
            'is_template' => isset($data['is_template']) ? intval($data['is_template']) : 0,
            'status' => isset($data['status']) ? intval($data['status']) : 1
        );
        
        $result = $wpdb->update($this->table, $update_data, array('id' => $id));
        
        if ($result === false) {
            error_log('SPFM Theme Update Error: ' . $wpdb->last_error);
            return false;
        }
        
        return true;
    }
    
    private function sanitize_color($color) {
        $color = sanitize_hex_color($color);
        return $color ? $color : '#007bff';
    }
    
    public function delete($id) {
        global $wpdb;
        return $wpdb->delete($this->table, array('id' => $id), array('%d'));
    }
    
    public function toggle_status($id) {
        global $wpdb;
        
        $current_status = $wpdb->get_var($wpdb->prepare("SELECT status FROM {$this->table} WHERE id = %d", $id));
        $new_status = $current_status ? 0 : 1;
        
        return $wpdb->update($this->table, array('status' => $new_status), array('id' => $id), array('%d'), array('%d'));
    }
    
    public function duplicate_template($id) {
        $theme = $this->get_by_id($id);
        
        if (!$theme) {
            return false;
        }
        
        $new_data = (array) $theme;
        unset($new_data['id']);
        $new_data['name'] = $theme->name . ' (Copy)';
        $new_data['is_template'] = 0;
        
        return $this->create($new_data);
    }
    
    public function get_theme_css($id, $customizations = array()) {
        $theme = $this->get_by_id($id);
        
        if (!$theme) {
            return '';
        }
        
        // Apply customizations if any
        $primary = isset($customizations['primary_color']) ? $customizations['primary_color'] : $theme->primary_color;
        $secondary = isset($customizations['secondary_color']) ? $customizations['secondary_color'] : $theme->secondary_color;
        $background = isset($customizations['background_color']) ? $customizations['background_color'] : $theme->background_color;
        $text = isset($customizations['text_color']) ? $customizations['text_color'] : $theme->text_color;
        $accent = isset($customizations['accent_color']) ? $customizations['accent_color'] : $theme->accent_color;
        $header_bg = isset($customizations['header_bg_color']) ? $customizations['header_bg_color'] : $theme->header_bg_color;
        $font = isset($customizations['font_family']) ? $customizations['font_family'] : $theme->font_family;
        $header_font = isset($customizations['header_font']) ? $customizations['header_font'] : $theme->header_font;
        
        $button_style = $theme->button_style;
        $button_css = $this->get_button_css($button_style, $primary, $secondary);
        
        $css = "
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;600&family=Roboto:wght@400;500;700&family=Lato:wght@400;700&family=Nunito:wght@400;600;700&family=Inter:wght@400;500;600&display=swap');
            
            .spfm-form-wrapper {
                background-color: {$background};
                color: {$text};
                font-family: {$font};
            }
            
            .spfm-form-header {
                background: {$header_bg};
                font-family: {$header_font};
            }
            
            .spfm-form-wrapper h1,
            .spfm-form-wrapper h2,
            .spfm-form-wrapper h3 {
                font-family: {$header_font};
                color: {$text};
            }
            
            .spfm-form-wrapper a {
                color: {$primary};
            }
            
            .spfm-form-wrapper .form-control:focus {
                border-color: {$primary};
                box-shadow: 0 0 0 0.2rem rgba(" . $this->hex_to_rgb($primary) . ", 0.25);
            }
            
            .spfm-form-wrapper .accent-text {
                color: {$accent};
            }
            
            {$button_css}
        ";
        
        // Layout specific styles
        switch ($theme->layout_style) {
            case 'gradient-header':
                $css .= "
                    .spfm-form-header {
                        background: linear-gradient(135deg, {$primary} 0%, {$secondary} 100%);
                    }
                ";
                break;
            case 'dark':
                $css .= "
                    .spfm-form-wrapper input,
                    .spfm-form-wrapper select,
                    .spfm-form-wrapper textarea {
                        background-color: rgba(255,255,255,0.1);
                        border-color: rgba(255,255,255,0.2);
                        color: {$text};
                    }
                ";
                break;
        }
        
        if (!empty($theme->custom_css)) {
            $css .= $theme->custom_css;
        }
        
        return $css;
    }
    
    private function get_button_css($style, $primary, $secondary) {
        switch ($style) {
            case 'gradient':
                return "
                    .spfm-form-wrapper .btn-primary {
                        background: linear-gradient(135deg, {$primary} 0%, {$secondary} 100%);
                        border: none;
                        color: #fff;
                    }
                    .spfm-form-wrapper .btn-primary:hover {
                        background: linear-gradient(135deg, {$secondary} 0%, {$primary} 100%);
                        transform: translateY(-2px);
                    }
                ";
            case 'outline':
                return "
                    .spfm-form-wrapper .btn-primary {
                        background: transparent;
                        border: 2px solid {$primary};
                        color: {$primary};
                    }
                    .spfm-form-wrapper .btn-primary:hover {
                        background: {$primary};
                        color: #fff;
                    }
                ";
            case 'rounded':
            default:
                return "
                    .spfm-form-wrapper .btn-primary {
                        background-color: {$primary};
                        border-color: {$primary};
                        color: #fff;
                        border-radius: 50px;
                    }
                    .spfm-form-wrapper .btn-primary:hover {
                        background-color: {$secondary};
                        border-color: {$secondary};
                    }
                ";
        }
    }
    
    private function hex_to_rgb($hex) {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return "$r, $g, $b";
    }
    
    public function get_layout_styles() {
        return array(
            'default' => 'Default',
            'card' => 'Card Style',
            'minimal' => 'Minimal',
            'boxed' => 'Boxed',
            'gradient-header' => 'Gradient Header',
            'dark' => 'Dark Mode'
        );
    }
    
    public function get_button_styles() {
        return array(
            'rounded' => 'Rounded',
            'gradient' => 'Gradient',
            'outline' => 'Outline',
            'square' => 'Square'
        );
    }
    
    public function get_font_families() {
        return array(
            'Arial, sans-serif' => 'Arial',
            'Helvetica, sans-serif' => 'Helvetica',
            'Segoe UI, sans-serif' => 'Segoe UI',
            'Poppins, sans-serif' => 'Poppins',
            'Open Sans, sans-serif' => 'Open Sans',
            'Roboto, sans-serif' => 'Roboto',
            'Lato, sans-serif' => 'Lato',
            'Nunito, sans-serif' => 'Nunito',
            'Inter, sans-serif' => 'Inter',
            'Georgia, serif' => 'Georgia',
            'Times New Roman, serif' => 'Times New Roman'
        );
    }
}
