<?php
/**
 * Website Themes/Templates Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Themes {
    
    private static $instance = null;
    private $table;
    private $pages_table;
    private $sections_table;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'spfm_themes';
        $this->pages_table = $wpdb->prefix . 'spfm_theme_pages';
        $this->sections_table = $wpdb->prefix . 'spfm_page_sections';
    }
    
    public function get_all($args = array()) {
        global $wpdb;
        
        $defaults = array(
            'per_page' => 50,
            'page' => 1,
            'orderby' => 'name',
            'order' => 'ASC',
            'search' => '',
            'status' => '',
            'category' => '',
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
        
        if (!empty($args['category'])) {
            $where .= $wpdb->prepare(" AND category = %s", $args['category']);
        }
        
        if ($args['is_template'] !== '') {
            $where .= $wpdb->prepare(" AND is_template = %d", $args['is_template']);
        }
        
        $orderby = sanitize_sql_orderby($args['orderby'] . ' ' . $args['order']);
        if (!$orderby) {
            $orderby = 'name ASC';
        }
        
        $sql = "SELECT * FROM {$this->table} $where ORDER BY $orderby LIMIT %d OFFSET %d";
        
        return $wpdb->get_results($wpdb->prepare($sql, $args['per_page'], $offset));
    }
    
    public function get_templates() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->table} WHERE is_template = 1 AND status = 1 ORDER BY name ASC");
    }
    
    public function get_all_active() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->table} WHERE status = 1 ORDER BY is_template DESC, name ASC");
    }
    
    public function get_by_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table} WHERE id = %d", $id));
    }
    
    public function get_by_ids($ids) {
        global $wpdb;
        if (empty($ids)) return array();
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE id IN ($placeholders) AND status = 1 ORDER BY name ASC",
            ...$ids
        ));
    }
    
    // Get all pages for a theme
    public function get_theme_pages($theme_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->pages_table} WHERE theme_id = %d AND status = 1 ORDER BY page_order ASC",
            $theme_id
        ));
    }
    
    // Get page by ID
    public function get_page_by_id($page_id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->pages_table} WHERE id = %d",
            $page_id
        ));
    }
    
    // Get sections for a page
    public function get_page_sections($page_id) {
        global $wpdb;
        $sections = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->sections_table} WHERE page_id = %d AND status = 1 ORDER BY section_order ASC",
            $page_id
        ));
        
        foreach ($sections as &$section) {
            $section->fields = json_decode($section->fields, true);
        }
        
        return $sections;
    }
    
    // Get complete theme with pages and sections
    public function get_theme_complete($theme_id) {
        $theme = $this->get_by_id($theme_id);
        if (!$theme) return null;
        
        $theme->features = json_decode($theme->features, true);
        $theme->pages = $this->get_theme_pages($theme_id);
        
        foreach ($theme->pages as &$page) {
            $page->sections = $this->get_page_sections($page->id);
        }
        
        return $theme;
    }
    
    public function create($data) {
        global $wpdb;
        
        $insert_data = array(
            'name' => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field($data['description'] ?? ''),
            'category' => sanitize_text_field($data['category'] ?? 'business'),
            'preview_image' => esc_url_raw($data['preview_image'] ?? ''),
            'thumbnail' => esc_url_raw($data['thumbnail'] ?? ''),
            'primary_color' => sanitize_hex_color($data['primary_color'] ?? '#667eea'),
            'secondary_color' => sanitize_hex_color($data['secondary_color'] ?? '#764ba2'),
            'accent_color' => sanitize_hex_color($data['accent_color'] ?? '#28a745'),
            'background_color' => sanitize_hex_color($data['background_color'] ?? '#ffffff'),
            'text_color' => sanitize_hex_color($data['text_color'] ?? '#333333'),
            'header_bg_color' => sanitize_hex_color($data['header_bg_color'] ?? '#ffffff'),
            'footer_bg_color' => sanitize_hex_color($data['footer_bg_color'] ?? '#1a1a2e'),
            'font_family' => sanitize_text_field($data['font_family'] ?? 'Poppins'),
            'heading_font' => sanitize_text_field($data['heading_font'] ?? 'Poppins'),
            'features' => json_encode($data['features'] ?? array()),
            'is_template' => intval($data['is_template'] ?? 0),
            'status' => intval($data['status'] ?? 1)
        );
        
        $result = $wpdb->insert($this->table, $insert_data);
        
        return $result === false ? false : $wpdb->insert_id;
    }
    
    public function update($id, $data) {
        global $wpdb;
        
        $update_data = array(
            'name' => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field($data['description'] ?? ''),
            'category' => sanitize_text_field($data['category'] ?? 'business'),
            'preview_image' => esc_url_raw($data['preview_image'] ?? ''),
            'thumbnail' => esc_url_raw($data['thumbnail'] ?? ''),
            'primary_color' => sanitize_hex_color($data['primary_color'] ?? '#667eea'),
            'secondary_color' => sanitize_hex_color($data['secondary_color'] ?? '#764ba2'),
            'accent_color' => sanitize_hex_color($data['accent_color'] ?? '#28a745'),
            'background_color' => sanitize_hex_color($data['background_color'] ?? '#ffffff'),
            'text_color' => sanitize_hex_color($data['text_color'] ?? '#333333'),
            'header_bg_color' => sanitize_hex_color($data['header_bg_color'] ?? '#ffffff'),
            'footer_bg_color' => sanitize_hex_color($data['footer_bg_color'] ?? '#1a1a2e'),
            'font_family' => sanitize_text_field($data['font_family'] ?? 'Poppins'),
            'heading_font' => sanitize_text_field($data['heading_font'] ?? 'Poppins'),
            'status' => intval($data['status'] ?? 1)
        );
        
        if (isset($data['features'])) {
            $update_data['features'] = json_encode($data['features']);
        }
        
        return $wpdb->update($this->table, $update_data, array('id' => $id)) !== false;
    }
    
    public function delete($id) {
        global $wpdb;
        
        // Delete sections first
        $pages = $this->get_theme_pages($id);
        foreach ($pages as $page) {
            $wpdb->delete($this->sections_table, array('page_id' => $page->id));
        }
        
        // Delete pages
        $wpdb->delete($this->pages_table, array('theme_id' => $id));
        
        // Delete theme
        return $wpdb->delete($this->table, array('id' => $id));
    }
    
    public function toggle_status($id) {
        global $wpdb;
        
        $current_status = $wpdb->get_var($wpdb->prepare("SELECT status FROM {$this->table} WHERE id = %d", $id));
        $new_status = $current_status ? 0 : 1;
        
        return $wpdb->update($this->table, array('status' => $new_status), array('id' => $id));
    }
    
    public function duplicate_template($id) {
        $theme = $this->get_theme_complete($id);
        
        if (!$theme) return false;
        
        global $wpdb;
        
        // Create new theme
        $new_theme_data = (array) $theme;
        unset($new_theme_data['id'], $new_theme_data['pages']);
        $new_theme_data['name'] = $theme->name . ' (Copy)';
        $new_theme_data['is_template'] = 0;
        $new_theme_data['features'] = $theme->features;
        
        $new_theme_id = $this->create($new_theme_data);
        
        if (!$new_theme_id) return false;
        
        // Copy pages
        foreach ($theme->pages as $page) {
            $wpdb->insert($this->pages_table, array(
                'theme_id' => $new_theme_id,
                'page_name' => $page->page_name,
                'page_slug' => $page->page_slug,
                'page_icon' => $page->page_icon,
                'page_order' => $page->page_order,
                'is_required' => $page->is_required,
                'page_description' => $page->page_description,
                'status' => 1
            ));
            $new_page_id = $wpdb->insert_id;
            
            // Copy sections
            foreach ($page->sections as $section) {
                $wpdb->insert($this->sections_table, array(
                    'page_id' => $new_page_id,
                    'section_name' => $section->section_name,
                    'section_type' => $section->section_type,
                    'section_order' => $section->section_order,
                    'fields' => json_encode($section->fields),
                    'is_required' => $section->is_required,
                    'status' => 1
                ));
            }
        }
        
        return $new_theme_id;
    }
    
    public function get_categories() {
        return array(
            'business' => 'Business & Corporate',
            'portfolio' => 'Portfolio & Creative',
            'ecommerce' => 'E-Commerce & Shop',
            'restaurant' => 'Restaurant & Food',
            'medical' => 'Medical & Healthcare',
            'realestate' => 'Real Estate',
            'education' => 'Education & School',
            'fitness' => 'Fitness & Sports',
            'travel' => 'Travel & Tourism',
            'technology' => 'Technology & IT',
            'blog' => 'Blog & Magazine',
            'nonprofit' => 'Non-Profit & Charity'
        );
    }
    
    public function get_fonts() {
        return array(
            'Poppins' => 'Poppins',
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Nunito' => 'Nunito',
            'Source Sans Pro' => 'Source Sans Pro',
            'Playfair Display' => 'Playfair Display',
            'Merriweather' => 'Merriweather',
            'Oswald' => 'Oswald',
            'Raleway' => 'Raleway'
        );
    }
}
