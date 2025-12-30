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
        } else {
            // Create default pages structure when starting from scratch
            $this->create_default_pages($theme_id);
        }
        
        return $theme_id;
    }
    
    /**
     * Create default pages for a new template
     */
    public function create_default_pages($theme_id) {
        global $wpdb;
        
        $default_pages = array(
            array(
                'page_name' => 'Home',
                'page_slug' => 'home',
                'page_icon' => 'dashicons-admin-home',
                'page_order' => 1,
                'is_required' => 1,
                'page_description' => 'Main landing page with hero section, services, stats, and more.',
                'sections' => array(
                    array(
                        'section_name' => 'Hero Section',
                        'section_type' => 'hero',
                        'section_order' => 1,
                        'is_required' => 1,
                        'fields' => array(
                            array('name' => 'headline', 'label' => 'Headline', 'type' => 'text'),
                            array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea'),
                            array('name' => 'cta_text', 'label' => 'Primary CTA Text', 'type' => 'text'),
                            array('name' => 'cta2_text', 'label' => 'Secondary CTA Text', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'headline' => 'Your Health, Our Priority',
                            'subheadline' => 'Providing compassionate, world-class healthcare services with state-of-the-art facilities and experienced medical professionals.',
                            'cta_text' => 'Book Appointment',
                            'cta2_text' => 'Our Services'
                        )
                    ),
                    array(
                        'section_name' => 'Info Cards',
                        'section_type' => 'info_cards',
                        'section_order' => 2,
                        'is_required' => 0,
                        'fields' => array(
                            array('name' => 'card1_title', 'label' => 'Card 1 Title', 'type' => 'text'),
                            array('name' => 'card1_text', 'label' => 'Card 1 Text', 'type' => 'textarea'),
                            array('name' => 'card2_title', 'label' => 'Card 2 Title', 'type' => 'text'),
                            array('name' => 'card2_text', 'label' => 'Card 2 Text', 'type' => 'textarea'),
                            array('name' => 'card3_title', 'label' => 'Card 3 Title', 'type' => 'text'),
                            array('name' => 'card3_text', 'label' => 'Card 3 Text', 'type' => 'textarea')
                        ),
                        'default_values' => array(
                            'card1_title' => '24/7 Emergency',
                            'card1_text' => 'Round-the-clock emergency care with rapid response team ready to help.',
                            'card2_title' => 'Easy Appointments',
                            'card2_text' => 'Book appointments online or call us. We make healthcare accessible.',
                            'card3_title' => 'Quality Care',
                            'card3_text' => 'Accredited facility with board-certified medical professionals.'
                        )
                    ),
                    array(
                        'section_name' => 'Services Overview',
                        'section_type' => 'services',
                        'section_order' => 3,
                        'is_required' => 0,
                        'fields' => array(
                            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
                            array('name' => 'section_subtitle', 'label' => 'Section Subtitle', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'section_title' => 'Our Medical Services',
                            'section_subtitle' => 'Comprehensive healthcare solutions for you and your family'
                        )
                    ),
                    array(
                        'section_name' => 'Statistics',
                        'section_type' => 'stats',
                        'section_order' => 4,
                        'is_required' => 0,
                        'fields' => array(
                            array('name' => 'stat1_number', 'label' => 'Stat 1 Number', 'type' => 'text'),
                            array('name' => 'stat1_label', 'label' => 'Stat 1 Label', 'type' => 'text'),
                            array('name' => 'stat2_number', 'label' => 'Stat 2 Number', 'type' => 'text'),
                            array('name' => 'stat2_label', 'label' => 'Stat 2 Label', 'type' => 'text'),
                            array('name' => 'stat3_number', 'label' => 'Stat 3 Number', 'type' => 'text'),
                            array('name' => 'stat3_label', 'label' => 'Stat 3 Label', 'type' => 'text'),
                            array('name' => 'stat4_number', 'label' => 'Stat 4 Number', 'type' => 'text'),
                            array('name' => 'stat4_label', 'label' => 'Stat 4 Label', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'stat1_number' => '50+',
                            'stat1_label' => 'Years Experience',
                            'stat2_number' => '200+',
                            'stat2_label' => 'Expert Doctors',
                            'stat3_number' => '100K+',
                            'stat3_label' => 'Patients Served',
                            'stat4_number' => '50+',
                            'stat4_label' => 'Specialties'
                        )
                    ),
                    array(
                        'section_name' => 'Team Preview',
                        'section_type' => 'team',
                        'section_order' => 5,
                        'is_required' => 0,
                        'fields' => array(
                            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
                            array('name' => 'section_subtitle', 'label' => 'Section Subtitle', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'section_title' => 'Our Medical Team',
                            'section_subtitle' => 'Meet our experienced and dedicated healthcare professionals'
                        )
                    ),
                    array(
                        'section_name' => 'Call to Action',
                        'section_type' => 'cta',
                        'section_order' => 6,
                        'is_required' => 0,
                        'fields' => array(
                            array('name' => 'cta_headline', 'label' => 'CTA Headline', 'type' => 'text'),
                            array('name' => 'cta_description', 'label' => 'CTA Description', 'type' => 'textarea'),
                            array('name' => 'cta_button', 'label' => 'CTA Button Text', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'cta_headline' => 'Need Emergency Care?',
                            'cta_description' => 'Our emergency department is open 24/7. Don\'t wait – get the care you need now.',
                            'cta_button' => 'Call Emergency'
                        )
                    ),
                    array(
                        'section_name' => 'Blog Preview',
                        'section_type' => 'blog',
                        'section_order' => 7,
                        'is_required' => 0,
                        'fields' => array(
                            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
                            array('name' => 'section_subtitle', 'label' => 'Section Subtitle', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'section_title' => 'Health Blog',
                            'section_subtitle' => 'Expert health advice from our medical professionals'
                        )
                    )
                )
            ),
            array(
                'page_name' => 'About',
                'page_slug' => 'about',
                'page_icon' => 'dashicons-info',
                'page_order' => 2,
                'is_required' => 0,
                'page_description' => 'About us page with hospital history, mission, vision, and values.',
                'sections' => array(
                    array(
                        'section_name' => 'Page Header',
                        'section_type' => 'page_header',
                        'section_order' => 1,
                        'is_required' => 1,
                        'fields' => array(
                            array('name' => 'page_title', 'label' => 'Page Title', 'type' => 'text'),
                            array('name' => 'breadcrumb', 'label' => 'Breadcrumb', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'page_title' => 'About Us',
                            'breadcrumb' => 'Home / About Us'
                        )
                    ),
                    array(
                        'section_name' => 'About Content',
                        'section_type' => 'content',
                        'section_order' => 2,
                        'is_required' => 1,
                        'fields' => array(
                            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
                            array('name' => 'content', 'label' => 'Content', 'type' => 'textarea')
                        ),
                        'default_values' => array(
                            'section_title' => 'Who We Are',
                            'content' => 'We are a leading healthcare provider committed to delivering exceptional medical services to our community.'
                        )
                    ),
                    array(
                        'section_name' => 'Mission & Vision',
                        'section_type' => 'mission',
                        'section_order' => 3,
                        'is_required' => 0,
                        'fields' => array(
                            array('name' => 'mission', 'label' => 'Mission Statement', 'type' => 'textarea'),
                            array('name' => 'vision', 'label' => 'Vision Statement', 'type' => 'textarea')
                        ),
                        'default_values' => array(
                            'mission' => 'To provide compassionate, high-quality healthcare accessible to all members of our community.',
                            'vision' => 'To be the leading healthcare provider known for excellence, innovation, and patient-centered care.'
                        )
                    )
                )
            ),
            array(
                'page_name' => 'Services',
                'page_slug' => 'services',
                'page_icon' => 'dashicons-heart',
                'page_order' => 3,
                'is_required' => 0,
                'page_description' => 'Detailed services page listing all medical services offered.',
                'sections' => array(
                    array(
                        'section_name' => 'Page Header',
                        'section_type' => 'page_header',
                        'section_order' => 1,
                        'is_required' => 1,
                        'fields' => array(
                            array('name' => 'page_title', 'label' => 'Page Title', 'type' => 'text'),
                            array('name' => 'breadcrumb', 'label' => 'Breadcrumb', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'page_title' => 'Our Services',
                            'breadcrumb' => 'Home / Services'
                        )
                    ),
                    array(
                        'section_name' => 'Services List',
                        'section_type' => 'services',
                        'section_order' => 2,
                        'is_required' => 1,
                        'fields' => array(
                            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
                            array('name' => 'section_subtitle', 'label' => 'Section Subtitle', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'section_title' => 'Medical Services',
                            'section_subtitle' => 'Comprehensive healthcare solutions'
                        )
                    )
                )
            ),
            array(
                'page_name' => 'Doctors',
                'page_slug' => 'doctors',
                'page_icon' => 'dashicons-groups',
                'page_order' => 4,
                'is_required' => 0,
                'page_description' => 'Medical team page showcasing doctors and staff.',
                'sections' => array(
                    array(
                        'section_name' => 'Page Header',
                        'section_type' => 'page_header',
                        'section_order' => 1,
                        'is_required' => 1,
                        'fields' => array(
                            array('name' => 'page_title', 'label' => 'Page Title', 'type' => 'text'),
                            array('name' => 'breadcrumb', 'label' => 'Breadcrumb', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'page_title' => 'Our Doctors',
                            'breadcrumb' => 'Home / Doctors'
                        )
                    ),
                    array(
                        'section_name' => 'Team Grid',
                        'section_type' => 'team',
                        'section_order' => 2,
                        'is_required' => 1,
                        'fields' => array(
                            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
                            array('name' => 'section_subtitle', 'label' => 'Section Subtitle', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'section_title' => 'Meet Our Medical Team',
                            'section_subtitle' => 'Experienced professionals dedicated to your health'
                        )
                    )
                )
            ),
            array(
                'page_name' => 'Contact',
                'page_slug' => 'contact',
                'page_icon' => 'dashicons-phone',
                'page_order' => 5,
                'is_required' => 1,
                'page_description' => 'Contact page with location, hours, and contact form.',
                'sections' => array(
                    array(
                        'section_name' => 'Page Header',
                        'section_type' => 'page_header',
                        'section_order' => 1,
                        'is_required' => 1,
                        'fields' => array(
                            array('name' => 'page_title', 'label' => 'Page Title', 'type' => 'text'),
                            array('name' => 'breadcrumb', 'label' => 'Breadcrumb', 'type' => 'text')
                        ),
                        'default_values' => array(
                            'page_title' => 'Contact Us',
                            'breadcrumb' => 'Home / Contact'
                        )
                    ),
                    array(
                        'section_name' => 'Contact Information',
                        'section_type' => 'contact_info',
                        'section_order' => 2,
                        'is_required' => 1,
                        'fields' => array(
                            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
                            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
                            array('name' => 'email', 'label' => 'Email', 'type' => 'text'),
                            array('name' => 'working_hours', 'label' => 'Working Hours', 'type' => 'textarea')
                        ),
                        'default_values' => array(
                            'address' => "123 Medical Center Drive\nYour City, State 12345",
                            'phone' => '+1 (555) 123-4567',
                            'email' => 'info@hospital.com',
                            'working_hours' => "Monday - Friday: 8:00 AM - 8:00 PM\nSaturday: 9:00 AM - 5:00 PM\nSunday: Emergency Only"
                        )
                    )
                )
            )
        );
        
        foreach ($default_pages as $page_data) {
            $sections = $page_data['sections'];
            unset($page_data['sections']);
            
            $page_data['theme_id'] = $theme_id;
            $page_data['default_content'] = '{}';
            $page_data['status'] = 1;
            
            $wpdb->insert($this->pages_table, $page_data);
            $page_id = $wpdb->insert_id;
            
            // Insert sections
            foreach ($sections as $section_data) {
                $wpdb->insert($this->sections_table, array(
                    'page_id' => $page_id,
                    'section_name' => $section_data['section_name'],
                    'section_type' => $section_data['section_type'],
                    'section_order' => $section_data['section_order'],
                    'is_required' => $section_data['is_required'],
                    'fields' => json_encode($section_data['fields']),
                    'default_values' => json_encode($section_data['default_values']),
                    'status' => 1
                ));
            }
        }
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
     * Add a page to theme
     */
    public function add_page($theme_id, $page_data) {
        global $wpdb;
        
        // Get max page order
        $max_order = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(page_order) FROM {$this->pages_table} WHERE theme_id = %d",
            $theme_id
        ));
        
        $page_order = ($max_order !== null) ? $max_order + 1 : 1;
        
        $insert_data = array(
            'theme_id' => $theme_id,
            'page_name' => sanitize_text_field($page_data['page_name']),
            'page_slug' => sanitize_title($page_data['page_name']),
            'page_icon' => sanitize_text_field($page_data['page_icon'] ?? 'dashicons-admin-page'),
            'page_order' => $page_order,
            'is_required' => intval($page_data['is_required'] ?? 0),
            'page_description' => sanitize_textarea_field($page_data['page_description'] ?? ''),
            'default_content' => '{}',
            'status' => 1
        );
        
        $result = $wpdb->insert($this->pages_table, $insert_data);
        
        if ($result === false) {
            return false;
        }
        
        return $wpdb->insert_id;
    }
    
    /**
     * Add a section to page
     */
    public function add_section($page_id, $section_data) {
        global $wpdb;
        
        // Get max section order
        $max_order = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(section_order) FROM {$this->sections_table} WHERE page_id = %d",
            $page_id
        ));
        
        $section_order = ($max_order !== null) ? $max_order + 1 : 1;
        
        $insert_data = array(
            'page_id' => $page_id,
            'section_name' => sanitize_text_field($section_data['section_name']),
            'section_type' => sanitize_text_field($section_data['section_type']),
            'section_order' => $section_order,
            'is_required' => intval($section_data['is_required'] ?? 0),
            'fields' => json_encode($section_data['fields'] ?? array()),
            'default_values' => json_encode($section_data['default_values'] ?? array()),
            'status' => 1
        );
        
        $result = $wpdb->insert($this->sections_table, $insert_data);
        
        if ($result === false) {
            return false;
        }
        
        return $wpdb->insert_id;
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
