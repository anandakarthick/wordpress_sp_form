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
    
    /**
     * Get all themes with optional filters
     */
    public function get_all($args = array()) {
        global $wpdb;
        
        $defaults = array(
            'status' => '',
            'category' => '',
            'is_template' => '',
            'search' => '',
            'per_page' => 20,
            'page' => 1,
            'orderby' => 'created_at',
            'order' => 'DESC'
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $where = array('1=1');
        $values = array();
        
        if ($args['status'] !== '') {
            $where[] = 'status = %d';
            $values[] = intval($args['status']);
        }
        
        if ($args['category']) {
            $where[] = 'category = %s';
            $values[] = $args['category'];
        }
        
        if ($args['is_template'] !== '') {
            $where[] = 'is_template = %d';
            $values[] = intval($args['is_template']);
        }
        
        if ($args['search']) {
            $where[] = '(name LIKE %s OR description LIKE %s)';
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $values[] = $search;
            $values[] = $search;
        }
        
        $where_clause = implode(' AND ', $where);
        $offset = ($args['page'] - 1) * $args['per_page'];
        
        $orderby = sanitize_sql_orderby($args['orderby'] . ' ' . $args['order']);
        
        $sql = "SELECT * FROM {$this->table} WHERE $where_clause ORDER BY $orderby LIMIT %d OFFSET %d";
        $values[] = $args['per_page'];
        $values[] = $offset;
        
        if (!empty($values)) {
            $sql = $wpdb->prepare($sql, $values);
        }
        
        return $wpdb->get_results($sql);
    }
    
    /**
     * Get only active templates
     */
    public function get_templates() {
        global $wpdb;
        return $wpdb->get_results(
            "SELECT * FROM {$this->table} WHERE is_template = 1 AND status = 1 ORDER BY name ASC"
        );
    }
    
    /**
     * Get theme by ID
     */
    public function get_by_id($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE id = %d",
            $id
        ));
    }
    
    /**
     * Get themes by IDs
     */
    public function get_by_ids($ids) {
        global $wpdb;
        
        if (empty($ids)) return array();
        
        $ids = array_map('intval', $ids);
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE id IN ($placeholders) AND status = 1",
            $ids
        ));
    }
    
    /**
     * Get theme pages
     */
    public function get_theme_pages($theme_id) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->pages_table} WHERE theme_id = %d ORDER BY page_order ASC",
            $theme_id
        ));
    }
    
    /**
     * Get page sections
     */
    public function get_page_sections($page_id) {
        global $wpdb;
        $sections = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->sections_table} WHERE page_id = %d ORDER BY section_order ASC",
            $page_id
        ));
        
        foreach ($sections as &$section) {
            $section->fields = json_decode($section->fields, true) ?: array();
        }
        
        return $sections;
    }
    
    /**
     * Get complete theme with pages and sections
     */
    public function get_theme_complete($theme_id) {
        $theme = $this->get_by_id($theme_id);
        
        if (!$theme) return null;
        
        $theme->pages = $this->get_theme_pages($theme_id);
        
        foreach ($theme->pages as &$page) {
            $page->sections = $this->get_page_sections($page->id);
        }
        
        return $theme;
    }
    
    /**
     * Create a new theme
     */
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
            'features' => isset($data['features']) ? json_encode($data['features']) : '[]',
            'is_template' => isset($data['is_template']) ? intval($data['is_template']) : 0,
            'status' => isset($data['status']) ? intval($data['status']) : 1
        );
        
        $result = $wpdb->insert($this->table, $insert_data);
        
        if ($result === false) {
            return false;
        }
        
        $theme_id = $wpdb->insert_id;
        
        // If duplicating from another template
        if (!empty($data['duplicate_from'])) {
            $this->duplicate_pages($data['duplicate_from'], $theme_id);
        }
        
        return $theme_id;
    }
    
    /**
     * Update a theme
     */
    public function update($id, $data) {
        global $wpdb;
        
        $update_data = array();
        
        if (isset($data['name'])) {
            $update_data['name'] = sanitize_text_field($data['name']);
        }
        if (isset($data['description'])) {
            $update_data['description'] = sanitize_textarea_field($data['description']);
        }
        if (isset($data['category'])) {
            $update_data['category'] = sanitize_text_field($data['category']);
        }
        if (isset($data['preview_image'])) {
            $update_data['preview_image'] = esc_url_raw($data['preview_image']);
        }
        if (isset($data['thumbnail'])) {
            $update_data['thumbnail'] = esc_url_raw($data['thumbnail']);
        }
        if (isset($data['primary_color'])) {
            $update_data['primary_color'] = sanitize_hex_color($data['primary_color']);
        }
        if (isset($data['secondary_color'])) {
            $update_data['secondary_color'] = sanitize_hex_color($data['secondary_color']);
        }
        if (isset($data['accent_color'])) {
            $update_data['accent_color'] = sanitize_hex_color($data['accent_color']);
        }
        if (isset($data['background_color'])) {
            $update_data['background_color'] = sanitize_hex_color($data['background_color']);
        }
        if (isset($data['text_color'])) {
            $update_data['text_color'] = sanitize_hex_color($data['text_color']);
        }
        if (isset($data['header_bg_color'])) {
            $update_data['header_bg_color'] = sanitize_hex_color($data['header_bg_color']);
        }
        if (isset($data['footer_bg_color'])) {
            $update_data['footer_bg_color'] = sanitize_hex_color($data['footer_bg_color']);
        }
        if (isset($data['font_family'])) {
            $update_data['font_family'] = sanitize_text_field($data['font_family']);
        }
        if (isset($data['heading_font'])) {
            $update_data['heading_font'] = sanitize_text_field($data['heading_font']);
        }
        if (isset($data['features'])) {
            $update_data['features'] = json_encode(array_filter($data['features']));
        }
        if (isset($data['status'])) {
            $update_data['status'] = intval($data['status']);
        }
        
        if (empty($update_data)) {
            return true;
        }
        
        $result = $wpdb->update(
            $this->table,
            $update_data,
            array('id' => $id)
        );
        
        return $result !== false ? $id : false;
    }
    
    /**
     * Delete a theme
     */
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
    
    /**
     * Toggle theme status
     */
    public function toggle_status($id) {
        global $wpdb;
        $theme = $this->get_by_id($id);
        if (!$theme) return false;
        
        return $wpdb->update(
            $this->table,
            array('status' => $theme->status ? 0 : 1),
            array('id' => $id)
        );
    }
    
    /**
     * Duplicate template to new theme
     */
    public function duplicate_template($id) {
        $source = $this->get_theme_complete($id);
        if (!$source) return false;
        
        // Create new theme
        $new_theme_id = $this->create(array(
            'name' => $source->name . ' (Copy)',
            'description' => $source->description,
            'category' => $source->category,
            'preview_image' => $source->preview_image,
            'primary_color' => $source->primary_color,
            'secondary_color' => $source->secondary_color,
            'accent_color' => $source->accent_color,
            'background_color' => $source->background_color,
            'text_color' => $source->text_color,
            'header_bg_color' => $source->header_bg_color,
            'footer_bg_color' => $source->footer_bg_color,
            'font_family' => $source->font_family,
            'heading_font' => $source->heading_font,
            'features' => json_decode($source->features, true),
            'is_template' => 0,
            'status' => 1
        ));
        
        if (!$new_theme_id) return false;
        
        // Duplicate pages
        $this->duplicate_pages($id, $new_theme_id);
        
        return $new_theme_id;
    }
    
    /**
     * Duplicate pages from one theme to another
     */
    private function duplicate_pages($source_id, $target_id) {
        global $wpdb;
        
        $pages = $this->get_theme_pages($source_id);
        
        foreach ($pages as $page) {
            // Insert new page
            $wpdb->insert($this->pages_table, array(
                'theme_id' => $target_id,
                'page_name' => $page->page_name,
                'page_slug' => $page->page_slug,
                'page_icon' => $page->page_icon,
                'page_order' => $page->page_order,
                'is_required' => $page->is_required,
                'page_description' => $page->page_description,
                'status' => 1
            ));
            $new_page_id = $wpdb->insert_id;
            
            // Duplicate sections
            $sections = $this->get_page_sections($page->id);
            foreach ($sections as $section) {
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
    }
    
    /**
     * Get available categories
     */
    public function get_categories() {
        return array(
            'business' => 'Business',
            'portfolio' => 'Portfolio',
            'ecommerce' => 'E-Commerce',
            'restaurant' => 'Restaurant',
            'medical' => 'Medical',
            'realestate' => 'Real Estate',
            'education' => 'Education',
            'fitness' => 'Fitness',
            'travel' => 'Travel',
            'technology' => 'Technology',
            'blog' => 'Blog',
            'nonprofit' => 'Non-Profit'
        );
    }
    
    /**
     * Get available fonts
     */
    public function get_fonts() {
        return array(
            'Poppins',
            'Inter',
            'Roboto',
            'Open Sans',
            'Lato',
            'Montserrat',
            'Nunito',
            'Source Sans Pro',
            'Playfair Display',
            'Merriweather',
            'Oswald',
            'Raleway'
        );
    }
}
