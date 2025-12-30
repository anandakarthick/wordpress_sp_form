<?php
/**
 * Themes Handler Class
 * Hospital/Medical Website Templates
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
     * Get all themes
     */
    public function get_all($status = null) {
        global $wpdb;
        
        $sql = "SELECT * FROM {$this->table}";
        if ($status !== null) {
            $sql .= $wpdb->prepare(" WHERE status = %d", $status);
        }
        $sql .= " ORDER BY is_template DESC, name ASC";
        
        return $wpdb->get_results($sql);
    }
    
    /**
     * Get only pre-built templates
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
        
        if (empty($ids)) {
            return array();
        }
        
        $ids = array_map('intval', $ids);
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE id IN ($placeholders) AND status = 1 ORDER BY name ASC",
            $ids
        ));
    }
    
    /**
     * Get complete theme with pages and sections
     */
    public function get_theme_complete($id) {
        global $wpdb;
        
        $theme = $this->get_by_id($id);
        if (!$theme) return null;
        
        // Get pages
        $pages = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->pages_table} WHERE theme_id = %d AND status = 1 ORDER BY page_order ASC",
            $id
        ));
        
        // Get sections for each page
        foreach ($pages as &$page) {
            $sections = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$this->sections_table} WHERE page_id = %d AND status = 1 ORDER BY section_order ASC",
                $page->id
            ));
            
            foreach ($sections as &$section) {
                $section->fields = json_decode($section->fields, true) ?: array();
                $section->default_values = json_decode($section->default_values, true) ?: array();
            }
            
            $page->sections = $sections;
            $page->default_content = json_decode($page->default_content, true) ?: array();
        }
        
        $theme->pages = $pages;
        
        return $theme;
    }
    
    /**
     * Create theme
     */
    public function create($data) {
        global $wpdb;
        
        $insert_data = array(
            'name' => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field($data['description'] ?? ''),
            'category' => sanitize_text_field($data['category'] ?? 'hospital'),
            'preview_image' => esc_url_raw($data['preview_image'] ?? ''),
            'primary_color' => sanitize_hex_color($data['primary_color'] ?? '#0891b2'),
            'secondary_color' => sanitize_hex_color($data['secondary_color'] ?? '#0e7490'),
            'accent_color' => sanitize_hex_color($data['accent_color'] ?? '#06b6d4'),
            'background_color' => sanitize_hex_color($data['background_color'] ?? '#f0fdfa'),
            'text_color' => sanitize_hex_color($data['text_color'] ?? '#1e293b'),
            'header_bg_color' => sanitize_hex_color($data['header_bg_color'] ?? '#ffffff'),
            'footer_bg_color' => sanitize_hex_color($data['footer_bg_color'] ?? '#0f172a'),
            'font_family' => sanitize_text_field($data['font_family'] ?? 'Inter'),
            'heading_font' => sanitize_text_field($data['heading_font'] ?? 'Poppins'),
            'features' => is_array($data['features'] ?? null) ? json_encode($data['features']) : ($data['features'] ?? '[]'),
            'custom_css' => $data['custom_css'] ?? '',
            'is_template' => intval($data['is_template'] ?? 0),
            'status' => intval($data['status'] ?? 1)
        );
        
        $result = $wpdb->insert($this->table, $insert_data);
        
        if ($result === false) {
            return false;
        }
        
        $theme_id = $wpdb->insert_id;
        
        // Duplicate pages from source template if specified
        if (!empty($data['duplicate_from'])) {
            $this->duplicate_pages($data['duplicate_from'], $theme_id);
        }
        
        return $theme_id;
    }
    
    /**
     * Update theme
     */
    public function update($id, $data) {
        global $wpdb;
        
        $update_data = array();
        
        if (isset($data['name'])) $update_data['name'] = sanitize_text_field($data['name']);
        if (isset($data['description'])) $update_data['description'] = sanitize_textarea_field($data['description']);
        if (isset($data['category'])) $update_data['category'] = sanitize_text_field($data['category']);
        if (isset($data['preview_image'])) $update_data['preview_image'] = esc_url_raw($data['preview_image']);
        if (isset($data['primary_color'])) $update_data['primary_color'] = sanitize_hex_color($data['primary_color']);
        if (isset($data['secondary_color'])) $update_data['secondary_color'] = sanitize_hex_color($data['secondary_color']);
        if (isset($data['accent_color'])) $update_data['accent_color'] = sanitize_hex_color($data['accent_color']);
        if (isset($data['background_color'])) $update_data['background_color'] = sanitize_hex_color($data['background_color']);
        if (isset($data['text_color'])) $update_data['text_color'] = sanitize_hex_color($data['text_color']);
        if (isset($data['header_bg_color'])) $update_data['header_bg_color'] = sanitize_hex_color($data['header_bg_color']);
        if (isset($data['footer_bg_color'])) $update_data['footer_bg_color'] = sanitize_hex_color($data['footer_bg_color']);
        if (isset($data['font_family'])) $update_data['font_family'] = sanitize_text_field($data['font_family']);
        if (isset($data['heading_font'])) $update_data['heading_font'] = sanitize_text_field($data['heading_font']);
        if (isset($data['features'])) {
            $update_data['features'] = is_array($data['features']) ? json_encode($data['features']) : $data['features'];
        }
        if (isset($data['custom_css'])) $update_data['custom_css'] = $data['custom_css'];
        if (isset($data['status'])) $update_data['status'] = intval($data['status']);
        
        if (empty($update_data)) {
            return false;
        }
        
        return $wpdb->update($this->table, $update_data, array('id' => $id));
    }
    
    /**
     * Delete theme
     */
    public function delete($id) {
        global $wpdb;
        
        // Get pages first
        $pages = $wpdb->get_results($wpdb->prepare(
            "SELECT id FROM {$this->pages_table} WHERE theme_id = %d",
            $id
        ));
        
        // Delete sections
        foreach ($pages as $page) {
            $wpdb->delete($this->sections_table, array('page_id' => $page->id));
        }
        
        // Delete pages
        $wpdb->delete($this->pages_table, array('theme_id' => $id));
        
        // Delete theme
        return $wpdb->delete($this->table, array('id' => $id));
    }
    
    /**
     * Toggle status
     */
    public function toggle_status($id) {
        global $wpdb;
        
        $theme = $this->get_by_id($id);
        if (!$theme) return false;
        
        $new_status = $theme->status ? 0 : 1;
        
        return $wpdb->update(
            $this->table,
            array('status' => $new_status),
            array('id' => $id)
        );
    }
    
    /**
     * Duplicate template
     */
    public function duplicate_template($id) {
        $theme = $this->get_by_id($id);
        if (!$theme) return false;
        
        $new_data = array(
            'name' => $theme->name . ' (Copy)',
            'description' => $theme->description,
            'category' => $theme->category,
            'preview_image' => $theme->preview_image,
            'primary_color' => $theme->primary_color,
            'secondary_color' => $theme->secondary_color,
            'accent_color' => $theme->accent_color,
            'background_color' => $theme->background_color,
            'text_color' => $theme->text_color,
            'header_bg_color' => $theme->header_bg_color,
            'footer_bg_color' => $theme->footer_bg_color,
            'font_family' => $theme->font_family,
            'heading_font' => $theme->heading_font,
            'features' => $theme->features,
            'custom_css' => $theme->custom_css,
            'is_template' => 0,
            'status' => 1,
            'duplicate_from' => $id
        );
        
        return $this->create($new_data);
    }
    
    /**
     * Duplicate pages from source to target theme
     */
    public function duplicate_pages($source_theme_id, $target_theme_id) {
        global $wpdb;
        
        $source_pages = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->pages_table} WHERE theme_id = %d ORDER BY page_order ASC",
            $source_theme_id
        ));
        
        foreach ($source_pages as $page) {
            $wpdb->insert($this->pages_table, array(
                'theme_id' => $target_theme_id,
                'page_name' => $page->page_name,
                'page_slug' => $page->page_slug,
                'page_icon' => $page->page_icon,
                'page_order' => $page->page_order,
                'is_required' => $page->is_required,
                'page_description' => $page->page_description,
                'default_content' => $page->default_content,
                'status' => 1
            ));
            
            $new_page_id = $wpdb->insert_id;
            
            // Duplicate sections
            $sections = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$this->sections_table} WHERE page_id = %d ORDER BY section_order ASC",
                $page->id
            ));
            
            foreach ($sections as $section) {
                $wpdb->insert($this->sections_table, array(
                    'page_id' => $new_page_id,
                    'section_name' => $section->section_name,
                    'section_type' => $section->section_type,
                    'section_order' => $section->section_order,
                    'fields' => $section->fields,
                    'default_values' => $section->default_values,
                    'is_required' => $section->is_required,
                    'status' => 1
                ));
            }
        }
    }
    
    /**
     * Get categories
     */
    public function get_categories() {
        return array(
            'hospital' => 'General Hospital',
            'dental' => 'Dental Clinic',
            'eye_care' => 'Eye Care Center',
            'pediatric' => 'Children\'s Hospital',
            'cardiology' => 'Cardiology Center',
            'mental_health' => 'Mental Health Clinic',
            'orthopedic' => 'Orthopedic Center',
            'diagnostic' => 'Diagnostic Lab',
            'pharmacy' => 'Pharmacy',
            'rehabilitation' => 'Rehabilitation Center',
            'cosmetic' => 'Cosmetic Surgery',
            'fertility' => 'Fertility Clinic'
        );
    }
    
    /**
     * Get fonts
     */
    public function get_fonts() {
        return array(
            'Inter' => 'Inter',
            'Poppins' => 'Poppins',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Nunito' => 'Nunito',
            'Quicksand' => 'Quicksand',
            'Rubik' => 'Rubik',
            'Source Sans Pro' => 'Source Sans Pro',
            'Playfair Display' => 'Playfair Display',
            'Oswald' => 'Oswald'
        );
    }
    
    /**
     * Count themes
     */
    public function count($status = null) {
        global $wpdb;
        
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if ($status !== null) {
            $sql .= $wpdb->prepare(" WHERE status = %d", $status);
        }
        
        return $wpdb->get_var($sql);
    }
    
    /**
     * Get theme count by category
     */
    public function count_by_category() {
        global $wpdb;
        return $wpdb->get_results(
            "SELECT category, COUNT(*) as count FROM {$this->table} WHERE status = 1 GROUP BY category",
            OBJECT_K
        );
    }
}
