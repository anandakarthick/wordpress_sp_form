<?php
/**
 * Database Handler Class
 * Hospital/Medical Website Template System
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Database {
    
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Users table for separate login
        $table_users = $wpdb->prefix . 'spfm_users';
        $sql_users = "CREATE TABLE $table_users (
            id INT(11) NOT NULL AUTO_INCREMENT,
            username VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(200) NOT NULL,
            role VARCHAR(50) DEFAULT 'user',
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY username (username),
            UNIQUE KEY email (email)
        ) $charset_collate;";
        
        // Customers table
        $table_customers = $wpdb->prefix . 'spfm_customers';
        $sql_customers = "CREATE TABLE $table_customers (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(200) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(50) DEFAULT '',
            company VARCHAR(200) DEFAULT '',
            address TEXT,
            city VARCHAR(100) DEFAULT '',
            state VARCHAR(100) DEFAULT '',
            country VARCHAR(100) DEFAULT '',
            zip_code VARCHAR(20) DEFAULT '',
            notes TEXT,
            status TINYINT(1) DEFAULT 1,
            created_by INT(11) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Website Themes/Templates table
        $table_themes = $wpdb->prefix . 'spfm_themes';
        $sql_themes = "CREATE TABLE $table_themes (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(200) NOT NULL,
            description TEXT,
            category VARCHAR(100) DEFAULT 'hospital',
            preview_image VARCHAR(500) DEFAULT '',
            thumbnail VARCHAR(500) DEFAULT '',
            primary_color VARCHAR(20) DEFAULT '#0891b2',
            secondary_color VARCHAR(20) DEFAULT '#0e7490',
            accent_color VARCHAR(20) DEFAULT '#06b6d4',
            background_color VARCHAR(20) DEFAULT '#ffffff',
            text_color VARCHAR(20) DEFAULT '#333333',
            header_bg_color VARCHAR(20) DEFAULT '#ffffff',
            footer_bg_color VARCHAR(20) DEFAULT '#1e293b',
            font_family VARCHAR(100) DEFAULT 'Poppins',
            heading_font VARCHAR(100) DEFAULT 'Poppins',
            demo_url VARCHAR(500) DEFAULT '',
            features TEXT,
            default_content LONGTEXT,
            custom_css LONGTEXT,
            is_template TINYINT(1) DEFAULT 1,
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Theme Pages table
        $table_theme_pages = $wpdb->prefix . 'spfm_theme_pages';
        $sql_theme_pages = "CREATE TABLE $table_theme_pages (
            id INT(11) NOT NULL AUTO_INCREMENT,
            theme_id INT(11) NOT NULL,
            page_name VARCHAR(200) NOT NULL,
            page_slug VARCHAR(100) NOT NULL,
            page_icon VARCHAR(50) DEFAULT 'dashicons-admin-page',
            page_order INT(11) DEFAULT 0,
            is_required TINYINT(1) DEFAULT 1,
            page_description TEXT,
            default_content LONGTEXT,
            content_sections LONGTEXT,
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY theme_id (theme_id)
        ) $charset_collate;";
        
        // Theme Page Sections
        $table_page_sections = $wpdb->prefix . 'spfm_page_sections';
        $sql_page_sections = "CREATE TABLE $table_page_sections (
            id INT(11) NOT NULL AUTO_INCREMENT,
            page_id INT(11) NOT NULL,
            section_name VARCHAR(200) NOT NULL,
            section_type VARCHAR(100) DEFAULT 'content',
            section_order INT(11) DEFAULT 0,
            fields LONGTEXT,
            default_values LONGTEXT,
            is_required TINYINT(1) DEFAULT 0,
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY page_id (page_id)
        ) $charset_collate;";
        
        // Forms table
        $table_forms = $wpdb->prefix . 'spfm_forms';
        $sql_forms = "CREATE TABLE $table_forms (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(200) NOT NULL,
            description TEXT,
            available_themes LONGTEXT,
            allow_color_customization TINYINT(1) DEFAULT 1,
            allow_theme_selection TINYINT(1) DEFAULT 1,
            header_text VARCHAR(500) DEFAULT '',
            footer_text VARCHAR(500) DEFAULT '',
            success_message TEXT,
            notify_admin TINYINT(1) DEFAULT 1,
            status TINYINT(1) DEFAULT 1,
            created_by INT(11) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Form shares table
        $table_shares = $wpdb->prefix . 'spfm_form_shares';
        $sql_shares = "CREATE TABLE $table_shares (
            id INT(11) NOT NULL AUTO_INCREMENT,
            form_id INT(11) NOT NULL,
            customer_id INT(11) DEFAULT NULL,
            token VARCHAR(64) NOT NULL,
            shared_via VARCHAR(50) DEFAULT 'link',
            shared_to VARCHAR(200) DEFAULT '',
            views INT(11) DEFAULT 0,
            status VARCHAR(50) DEFAULT 'active',
            expires_at DATETIME DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY token (token),
            KEY form_id (form_id)
        ) $charset_collate;";
        
        // Form submissions table
        $table_submissions = $wpdb->prefix . 'spfm_form_submissions';
        $sql_submissions = "CREATE TABLE $table_submissions (
            id INT(11) NOT NULL AUTO_INCREMENT,
            form_id INT(11) NOT NULL,
            share_id INT(11) DEFAULT NULL,
            customer_id INT(11) DEFAULT NULL,
            selected_theme_id INT(11) NOT NULL,
            page_contents LONGTEXT NOT NULL,
            color_customizations LONGTEXT,
            uploaded_files LONGTEXT,
            customer_info LONGTEXT,
            ip_address VARCHAR(50) DEFAULT '',
            user_agent TEXT,
            status VARCHAR(50) DEFAULT 'new',
            admin_notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY form_id (form_id),
            KEY selected_theme_id (selected_theme_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($sql_users);
        dbDelta($sql_customers);
        dbDelta($sql_themes);
        dbDelta($sql_theme_pages);
        dbDelta($sql_page_sections);
        dbDelta($sql_forms);
        dbDelta($sql_shares);
        dbDelta($sql_submissions);
        
        // Create default admin user
        self::create_default_admin();
        
        // Create hospital templates
        self::create_hospital_templates();
        
        // Set flush rules flag
        update_option('spfm_flush_rules', true);
        
        // Update version
        update_option('spfm_db_version', '2.0.0');
    }
    
    private static function create_default_admin() {
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_users';
        
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE username = 'admin'");
        
        if (!$exists) {
            $wpdb->insert($table, array(
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name' => 'Administrator',
                'role' => 'admin',
                'status' => 1
            ));
        }
    }
    
    private static function create_hospital_templates() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        $sections_table = $wpdb->prefix . 'spfm_page_sections';
        
        // Check if templates already exist
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM $themes_table WHERE is_template = 1");
        if ($exists) {
            return;
        }
        
        // ============ TEMPLATE 1: General Hospital ============
        $template1 = self::get_general_hospital_template();
        $wpdb->insert($themes_table, $template1['theme']);
        $theme1_id = $wpdb->insert_id;
        self::insert_template_pages($theme1_id, $template1['pages']);
        
        // ============ TEMPLATE 2: Dental Clinic ============
        $template2 = self::get_dental_clinic_template();
        $wpdb->insert($themes_table, $template2['theme']);
        $theme2_id = $wpdb->insert_id;
        self::insert_template_pages($theme2_id, $template2['pages']);
        
        // ============ TEMPLATE 3: Eye Care Center ============
        $template3 = self::get_eye_care_template();
        $wpdb->insert($themes_table, $template3['theme']);
        $theme3_id = $wpdb->insert_id;
        self::insert_template_pages($theme3_id, $template3['pages']);
        
        // ============ TEMPLATE 4: Children's Hospital ============
        $template4 = self::get_children_hospital_template();
        $wpdb->insert($themes_table, $template4['theme']);
        $theme4_id = $wpdb->insert_id;
        self::insert_template_pages($theme4_id, $template4['pages']);
        
        // ============ TEMPLATE 5: Cardiology Center ============
        $template5 = self::get_cardiology_template();
        $wpdb->insert($themes_table, $template5['theme']);
        $theme5_id = $wpdb->insert_id;
        self::insert_template_pages($theme5_id, $template5['pages']);
        
        // ============ TEMPLATE 6: Mental Health Clinic ============
        $template6 = self::get_mental_health_template();
        $wpdb->insert($themes_table, $template6['theme']);
        $theme6_id = $wpdb->insert_id;
        self::insert_template_pages($theme6_id, $template6['pages']);
        
        // ============ TEMPLATE 7: Orthopedic Center ============
        $template7 = self::get_orthopedic_template();
        $wpdb->insert($themes_table, $template7['theme']);
        $theme7_id = $wpdb->insert_id;
        self::insert_template_pages($theme7_id, $template7['pages']);
        
        // ============ TEMPLATE 8: Diagnostic Lab ============
        $template8 = self::get_diagnostic_lab_template();
        $wpdb->insert($themes_table, $template8['theme']);
        $theme8_id = $wpdb->insert_id;
        self::insert_template_pages($theme8_id, $template8['pages']);
    }
    
    private static function insert_template_pages($theme_id, $pages) {
        global $wpdb;
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        $sections_table = $wpdb->prefix . 'spfm_page_sections';
        
        foreach ($pages as $order => $page) {
            $wpdb->insert($pages_table, array(
                'theme_id' => $theme_id,
                'page_name' => $page['name'],
                'page_slug' => $page['slug'],
                'page_icon' => $page['icon'],
                'page_order' => $order,
                'is_required' => $page['required'] ? 1 : 0,
                'page_description' => $page['description'],
                'default_content' => json_encode($page['default_content'] ?? array()),
                'status' => 1
            ));
            $page_id = $wpdb->insert_id;
            
            foreach ($page['sections'] as $sec_order => $section) {
                $wpdb->insert($sections_table, array(
                    'page_id' => $page_id,
                    'section_name' => $section['name'],
                    'section_type' => $section['type'],
                    'section_order' => $sec_order,
                    'fields' => json_encode($section['fields']),
                    'default_values' => json_encode($section['defaults'] ?? array()),
                    'is_required' => ($section['required'] ?? false) ? 1 : 0,
                    'status' => 1
                ));
            }
        }
    }
    
    // ==================== TEMPLATE 1: General Hospital ====================
    private static function get_general_hospital_template() {
        return array(
            'theme' => array(
                'name' => 'General Hospital',
                'description' => 'Complete hospital website with departments, doctors, appointments, and patient resources.',
                'category' => 'hospital',
                'primary_color' => '#0891b2',
                'secondary_color' => '#0e7490',
                'accent_color' => '#06b6d4',
                'background_color' => '#f0fdfa',
                'text_color' => '#1e293b',
                'header_bg_color' => '#ffffff',
                'footer_bg_color' => '#0f172a',
                'font_family' => 'Inter',
                'heading_font' => 'Poppins',
                'features' => json_encode(['24/7 Emergency', 'Online Appointments', 'Doctor Profiles', 'Department Pages', 'Health Blog', 'Patient Portal']),
                'custom_css' => self::get_hospital_css(),
                'is_template' => 1,
                'status' => 1
            ),
            'pages' => array(
                self::get_home_page('general'),
                self::get_about_page('general'),
                self::get_departments_page(),
                self::get_doctors_page(),
                self::get_services_page('general'),
                self::get_blog_page(),
                self::get_contact_page('general')
            )
        );
    }
    
    // ==================== TEMPLATE 2: Dental Clinic ====================
    private static function get_dental_clinic_template() {
        return array(
            'theme' => array(
                'name' => 'Dental Care Clinic',
                'description' => 'Modern dental clinic website with services, smile gallery, and appointment booking.',
                'category' => 'dental',
                'primary_color' => '#0ea5e9',
                'secondary_color' => '#0284c7',
                'accent_color' => '#38bdf8',
                'background_color' => '#f0f9ff',
                'text_color' => '#1e293b',
                'header_bg_color' => '#ffffff',
                'footer_bg_color' => '#0c4a6e',
                'font_family' => 'Nunito',
                'heading_font' => 'Poppins',
                'features' => json_encode(['Smile Gallery', 'Treatment Plans', 'Online Booking', 'Dental Tips Blog', 'Insurance Info', 'Virtual Consultation']),
                'custom_css' => self::get_hospital_css(),
                'is_template' => 1,
                'status' => 1
            ),
            'pages' => array(
                self::get_home_page('dental'),
                self::get_about_page('dental'),
                self::get_services_page('dental'),
                self::get_doctors_page('dental'),
                self::get_gallery_page('dental'),
                self::get_blog_page('dental'),
                self::get_contact_page('dental')
            )
        );
    }
    
    // ==================== TEMPLATE 3: Eye Care Center ====================
    private static function get_eye_care_template() {
        return array(
            'theme' => array(
                'name' => 'Eye Care Center',
                'description' => 'Professional eye care and vision center website with services and optical shop.',
                'category' => 'eye_care',
                'primary_color' => '#8b5cf6',
                'secondary_color' => '#7c3aed',
                'accent_color' => '#a78bfa',
                'background_color' => '#faf5ff',
                'text_color' => '#1e293b',
                'header_bg_color' => '#ffffff',
                'footer_bg_color' => '#2e1065',
                'font_family' => 'Open Sans',
                'heading_font' => 'Montserrat',
                'features' => json_encode(['Eye Exams', 'LASIK Info', 'Optical Shop', 'Doctor Profiles', 'Vision Blog', 'Insurance Partners']),
                'custom_css' => self::get_hospital_css(),
                'is_template' => 1,
                'status' => 1
            ),
            'pages' => array(
                self::get_home_page('eye'),
                self::get_about_page('eye'),
                self::get_services_page('eye'),
                self::get_doctors_page('eye'),
                self::get_blog_page('eye'),
                self::get_contact_page('eye')
            )
        );
    }
    
    // ==================== TEMPLATE 4: Children's Hospital ====================
    private static function get_children_hospital_template() {
        return array(
            'theme' => array(
                'name' => 'Children\'s Hospital',
                'description' => 'Friendly pediatric hospital website designed for parents and young patients.',
                'category' => 'pediatric',
                'primary_color' => '#f97316',
                'secondary_color' => '#ea580c',
                'accent_color' => '#fb923c',
                'background_color' => '#fff7ed',
                'text_color' => '#1e293b',
                'header_bg_color' => '#ffffff',
                'footer_bg_color' => '#7c2d12',
                'font_family' => 'Quicksand',
                'heading_font' => 'Fredoka One',
                'features' => json_encode(['Child-Friendly Design', 'Parent Resources', 'Pediatric Specialists', 'Play Areas', 'Health Tips', 'Emergency Care']),
                'custom_css' => self::get_hospital_css(),
                'is_template' => 1,
                'status' => 1
            ),
            'pages' => array(
                self::get_home_page('pediatric'),
                self::get_about_page('pediatric'),
                self::get_services_page('pediatric'),
                self::get_doctors_page('pediatric'),
                self::get_blog_page('pediatric'),
                self::get_contact_page('pediatric')
            )
        );
    }
    
    // ==================== TEMPLATE 5: Cardiology Center ====================
    private static function get_cardiology_template() {
        return array(
            'theme' => array(
                'name' => 'Heart & Cardiology Center',
                'description' => 'Specialized cardiology center website with heart health resources.',
                'category' => 'cardiology',
                'primary_color' => '#dc2626',
                'secondary_color' => '#b91c1c',
                'accent_color' => '#ef4444',
                'background_color' => '#fef2f2',
                'text_color' => '#1e293b',
                'header_bg_color' => '#ffffff',
                'footer_bg_color' => '#450a0a',
                'font_family' => 'Roboto',
                'heading_font' => 'Roboto Slab',
                'features' => json_encode(['Heart Screenings', 'Cardiac Surgery', 'Rehabilitation', 'Emergency Care', 'Health Monitoring', 'Research Updates']),
                'custom_css' => self::get_hospital_css(),
                'is_template' => 1,
                'status' => 1
            ),
            'pages' => array(
                self::get_home_page('cardiology'),
                self::get_about_page('cardiology'),
                self::get_services_page('cardiology'),
                self::get_doctors_page('cardiology'),
                self::get_blog_page('cardiology'),
                self::get_contact_page('cardiology')
            )
        );
    }
    
    // ==================== TEMPLATE 6: Mental Health Clinic ====================
    private static function get_mental_health_template() {
        return array(
            'theme' => array(
                'name' => 'Mental Wellness Center',
                'description' => 'Calm and supportive mental health clinic website with therapy services.',
                'category' => 'mental_health',
                'primary_color' => '#10b981',
                'secondary_color' => '#059669',
                'accent_color' => '#34d399',
                'background_color' => '#ecfdf5',
                'text_color' => '#1e293b',
                'header_bg_color' => '#ffffff',
                'footer_bg_color' => '#064e3b',
                'font_family' => 'Lato',
                'heading_font' => 'Playfair Display',
                'features' => json_encode(['Therapy Services', 'Support Groups', 'Online Counseling', 'Self-Help Resources', 'Crisis Support', 'Wellness Blog']),
                'custom_css' => self::get_hospital_css(),
                'is_template' => 1,
                'status' => 1
            ),
            'pages' => array(
                self::get_home_page('mental'),
                self::get_about_page('mental'),
                self::get_services_page('mental'),
                self::get_doctors_page('mental'),
                self::get_blog_page('mental'),
                self::get_contact_page('mental')
            )
        );
    }
    
    // ==================== TEMPLATE 7: Orthopedic Center ====================
    private static function get_orthopedic_template() {
        return array(
            'theme' => array(
                'name' => 'Orthopedic & Spine Center',
                'description' => 'Professional orthopedic center website for bone, joint, and spine care.',
                'category' => 'orthopedic',
                'primary_color' => '#2563eb',
                'secondary_color' => '#1d4ed8',
                'accent_color' => '#3b82f6',
                'background_color' => '#eff6ff',
                'text_color' => '#1e293b',
                'header_bg_color' => '#ffffff',
                'footer_bg_color' => '#1e3a8a',
                'font_family' => 'Source Sans Pro',
                'heading_font' => 'Oswald',
                'features' => json_encode(['Joint Replacement', 'Sports Medicine', 'Spine Surgery', 'Physical Therapy', 'Injury Prevention', 'Recovery Programs']),
                'custom_css' => self::get_hospital_css(),
                'is_template' => 1,
                'status' => 1
            ),
            'pages' => array(
                self::get_home_page('orthopedic'),
                self::get_about_page('orthopedic'),
                self::get_services_page('orthopedic'),
                self::get_doctors_page('orthopedic'),
                self::get_blog_page('orthopedic'),
                self::get_contact_page('orthopedic')
            )
        );
    }
    
    // ==================== TEMPLATE 8: Diagnostic Lab ====================
    private static function get_diagnostic_lab_template() {
        return array(
            'theme' => array(
                'name' => 'Diagnostic & Pathology Lab',
                'description' => 'Modern diagnostic laboratory website with test catalog and online reports.',
                'category' => 'diagnostic',
                'primary_color' => '#0d9488',
                'secondary_color' => '#0f766e',
                'accent_color' => '#14b8a6',
                'background_color' => '#f0fdfa',
                'text_color' => '#1e293b',
                'header_bg_color' => '#ffffff',
                'footer_bg_color' => '#134e4a',
                'font_family' => 'Rubik',
                'heading_font' => 'Poppins',
                'features' => json_encode(['Online Reports', 'Home Collection', 'Test Catalog', 'Health Packages', 'Corporate Plans', 'Quality Certified']),
                'custom_css' => self::get_hospital_css(),
                'is_template' => 1,
                'status' => 1
            ),
            'pages' => array(
                self::get_home_page('diagnostic'),
                self::get_about_page('diagnostic'),
                self::get_services_page('diagnostic'),
                self::get_tests_page(),
                self::get_blog_page('diagnostic'),
                self::get_contact_page('diagnostic')
            )
        );
    }
    
    // ==================== PAGE TEMPLATES ====================
    
    private static function get_home_page($type = 'general') {
        $content = self::get_home_content($type);
        return array(
            'name' => 'Home',
            'slug' => 'home',
            'icon' => 'dashicons-admin-home',
            'required' => true,
            'description' => 'Main landing page with hero section, services overview, and key information.',
            'default_content' => $content,
            'sections' => array(
                array(
                    'name' => 'Header & Navigation',
                    'type' => 'header',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'logo_text', 'label' => 'Hospital/Clinic Name', 'type' => 'text', 'required' => true),
                        array('name' => 'logo_image', 'label' => 'Logo Image', 'type' => 'image'),
                        array('name' => 'phone', 'label' => 'Phone Number', 'type' => 'text'),
                        array('name' => 'email', 'label' => 'Email Address', 'type' => 'email'),
                        array('name' => 'emergency_number', 'label' => 'Emergency Number', 'type' => 'text')
                    ),
                    'defaults' => $content['header'] ?? array()
                ),
                array(
                    'name' => 'Hero Section',
                    'type' => 'hero',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'headline', 'label' => 'Main Headline', 'type' => 'text', 'required' => true),
                        array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea'),
                        array('name' => 'cta_text', 'label' => 'Primary Button Text', 'type' => 'text'),
                        array('name' => 'cta_link', 'label' => 'Primary Button Link', 'type' => 'text'),
                        array('name' => 'cta2_text', 'label' => 'Secondary Button Text', 'type' => 'text'),
                        array('name' => 'cta2_link', 'label' => 'Secondary Button Link', 'type' => 'text'),
                        array('name' => 'hero_image', 'label' => 'Hero Image', 'type' => 'image')
                    ),
                    'defaults' => $content['hero'] ?? array()
                ),
                array(
                    'name' => 'Quick Info Cards',
                    'type' => 'info_cards',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'card1_icon', 'label' => 'Card 1 Icon', 'type' => 'icon'),
                        array('name' => 'card1_title', 'label' => 'Card 1 Title', 'type' => 'text'),
                        array('name' => 'card1_text', 'label' => 'Card 1 Text', 'type' => 'textarea'),
                        array('name' => 'card2_icon', 'label' => 'Card 2 Icon', 'type' => 'icon'),
                        array('name' => 'card2_title', 'label' => 'Card 2 Title', 'type' => 'text'),
                        array('name' => 'card2_text', 'label' => 'Card 2 Text', 'type' => 'textarea'),
                        array('name' => 'card3_icon', 'label' => 'Card 3 Icon', 'type' => 'icon'),
                        array('name' => 'card3_title', 'label' => 'Card 3 Title', 'type' => 'text'),
                        array('name' => 'card3_text', 'label' => 'Card 3 Text', 'type' => 'textarea')
                    ),
                    'defaults' => $content['info_cards'] ?? array()
                ),
                array(
                    'name' => 'Services Overview',
                    'type' => 'services',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
                        array('name' => 'section_subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'),
                        array('name' => 'services', 'label' => 'Services', 'type' => 'repeater', 'fields' => array(
                            array('name' => 'icon', 'label' => 'Icon', 'type' => 'icon'),
                            array('name' => 'title', 'label' => 'Service Name', 'type' => 'text'),
                            array('name' => 'description', 'label' => 'Description', 'type' => 'textarea')
                        ))
                    ),
                    'defaults' => $content['services'] ?? array()
                ),
                array(
                    'name' => 'Why Choose Us',
                    'type' => 'features',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
                        array('name' => 'section_text', 'label' => 'Section Text', 'type' => 'textarea'),
                        array('name' => 'feature1', 'label' => 'Feature 1', 'type' => 'text'),
                        array('name' => 'feature2', 'label' => 'Feature 2', 'type' => 'text'),
                        array('name' => 'feature3', 'label' => 'Feature 3', 'type' => 'text'),
                        array('name' => 'feature4', 'label' => 'Feature 4', 'type' => 'text'),
                        array('name' => 'image', 'label' => 'Section Image', 'type' => 'image')
                    ),
                    'defaults' => $content['features'] ?? array()
                ),
                array(
                    'name' => 'Statistics',
                    'type' => 'stats',
                    'required' => false,
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
                    'defaults' => $content['stats'] ?? array()
                ),
                array(
                    'name' => 'Call to Action',
                    'type' => 'cta',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'cta_headline', 'label' => 'CTA Headline', 'type' => 'text'),
                        array('name' => 'cta_text', 'label' => 'CTA Text', 'type' => 'textarea'),
                        array('name' => 'cta_button', 'label' => 'Button Text', 'type' => 'text'),
                        array('name' => 'cta_link', 'label' => 'Button Link', 'type' => 'text')
                    ),
                    'defaults' => $content['cta'] ?? array()
                ),
                array(
                    'name' => 'Footer',
                    'type' => 'footer',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'footer_about', 'label' => 'About Text', 'type' => 'textarea'),
                        array('name' => 'footer_address', 'label' => 'Address', 'type' => 'textarea'),
                        array('name' => 'footer_phone', 'label' => 'Phone', 'type' => 'text'),
                        array('name' => 'footer_email', 'label' => 'Email', 'type' => 'email'),
                        array('name' => 'facebook', 'label' => 'Facebook URL', 'type' => 'url'),
                        array('name' => 'twitter', 'label' => 'Twitter URL', 'type' => 'url'),
                        array('name' => 'instagram', 'label' => 'Instagram URL', 'type' => 'url'),
                        array('name' => 'linkedin', 'label' => 'LinkedIn URL', 'type' => 'url'),
                        array('name' => 'copyright', 'label' => 'Copyright Text', 'type' => 'text')
                    ),
                    'defaults' => $content['footer'] ?? array()
                )
            )
        );
    }
    
    private static function get_about_page($type = 'general') {
        $content = self::get_about_content($type);
        return array(
            'name' => 'About Us',
            'slug' => 'about',
            'icon' => 'dashicons-info',
            'required' => true,
            'description' => 'Information about your hospital/clinic, mission, vision, and history.',
            'default_content' => $content,
            'sections' => array(
                array(
                    'name' => 'Page Header',
                    'type' => 'page_header',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Page Title', 'type' => 'text', 'required' => true),
                        array('name' => 'breadcrumb', 'label' => 'Breadcrumb Text', 'type' => 'text')
                    ),
                    'defaults' => $content['header'] ?? array()
                ),
                array(
                    'name' => 'About Introduction',
                    'type' => 'content',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Section Title', 'type' => 'text'),
                        array('name' => 'content', 'label' => 'About Content', 'type' => 'editor'),
                        array('name' => 'image', 'label' => 'About Image', 'type' => 'image')
                    ),
                    'defaults' => $content['intro'] ?? array()
                ),
                array(
                    'name' => 'Mission & Vision',
                    'type' => 'mission',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'mission_title', 'label' => 'Mission Title', 'type' => 'text'),
                        array('name' => 'mission_text', 'label' => 'Mission Statement', 'type' => 'textarea'),
                        array('name' => 'vision_title', 'label' => 'Vision Title', 'type' => 'text'),
                        array('name' => 'vision_text', 'label' => 'Vision Statement', 'type' => 'textarea'),
                        array('name' => 'values_title', 'label' => 'Values Title', 'type' => 'text'),
                        array('name' => 'values_text', 'label' => 'Our Values', 'type' => 'textarea')
                    ),
                    'defaults' => $content['mission'] ?? array()
                ),
                array(
                    'name' => 'Our History',
                    'type' => 'history',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Section Title', 'type' => 'text'),
                        array('name' => 'content', 'label' => 'History Content', 'type' => 'editor')
                    ),
                    'defaults' => $content['history'] ?? array()
                ),
                array(
                    'name' => 'Certifications & Awards',
                    'type' => 'awards',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Section Title', 'type' => 'text'),
                        array('name' => 'awards', 'label' => 'Awards/Certifications', 'type' => 'repeater', 'fields' => array(
                            array('name' => 'name', 'label' => 'Award Name', 'type' => 'text'),
                            array('name' => 'year', 'label' => 'Year', 'type' => 'text'),
                            array('name' => 'image', 'label' => 'Badge/Logo', 'type' => 'image')
                        ))
                    ),
                    'defaults' => $content['awards'] ?? array()
                )
            )
        );
    }
    
    private static function get_services_page($type = 'general') {
        $content = self::get_services_content($type);
        return array(
            'name' => 'Services',
            'slug' => 'services',
            'icon' => 'dashicons-heart',
            'required' => true,
            'description' => 'List of medical services and treatments offered.',
            'default_content' => $content,
            'sections' => array(
                array(
                    'name' => 'Page Header',
                    'type' => 'page_header',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
                        array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
                    ),
                    'defaults' => $content['header'] ?? array()
                ),
                array(
                    'name' => 'Services List',
                    'type' => 'services_list',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'services', 'label' => 'Services', 'type' => 'repeater', 'fields' => array(
                            array('name' => 'icon', 'label' => 'Icon', 'type' => 'icon'),
                            array('name' => 'title', 'label' => 'Service Name', 'type' => 'text'),
                            array('name' => 'description', 'label' => 'Description', 'type' => 'editor'),
                            array('name' => 'image', 'label' => 'Image', 'type' => 'image')
                        ))
                    ),
                    'defaults' => $content['services'] ?? array()
                )
            )
        );
    }
    
    private static function get_doctors_page($type = 'general') {
        $content = self::get_doctors_content($type);
        return array(
            'name' => 'Our Doctors',
            'slug' => 'doctors',
            'icon' => 'dashicons-groups',
            'required' => true,
            'description' => 'Doctor profiles with specializations and contact information.',
            'default_content' => $content,
            'sections' => array(
                array(
                    'name' => 'Page Header',
                    'type' => 'page_header',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
                        array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
                    ),
                    'defaults' => $content['header'] ?? array()
                ),
                array(
                    'name' => 'Doctors List',
                    'type' => 'team',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'doctors', 'label' => 'Doctors', 'type' => 'repeater', 'fields' => array(
                            array('name' => 'photo', 'label' => 'Photo', 'type' => 'image'),
                            array('name' => 'name', 'label' => 'Full Name', 'type' => 'text'),
                            array('name' => 'title', 'label' => 'Title/Degree', 'type' => 'text'),
                            array('name' => 'specialty', 'label' => 'Specialty', 'type' => 'text'),
                            array('name' => 'experience', 'label' => 'Years of Experience', 'type' => 'text'),
                            array('name' => 'bio', 'label' => 'Short Bio', 'type' => 'textarea'),
                            array('name' => 'email', 'label' => 'Email', 'type' => 'email'),
                            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text')
                        ))
                    ),
                    'defaults' => $content['doctors'] ?? array()
                )
            )
        );
    }
    
    private static function get_departments_page() {
        return array(
            'name' => 'Departments',
            'slug' => 'departments',
            'icon' => 'dashicons-building',
            'required' => false,
            'description' => 'Hospital departments and specialties.',
            'default_content' => array(),
            'sections' => array(
                array(
                    'name' => 'Page Header',
                    'type' => 'page_header',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
                        array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
                    ),
                    'defaults' => array('title' => 'Our Departments', 'subtitle' => 'Specialized care across all medical disciplines')
                ),
                array(
                    'name' => 'Departments List',
                    'type' => 'departments',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'departments', 'label' => 'Departments', 'type' => 'repeater', 'fields' => array(
                            array('name' => 'icon', 'label' => 'Icon', 'type' => 'icon'),
                            array('name' => 'name', 'label' => 'Department Name', 'type' => 'text'),
                            array('name' => 'description', 'label' => 'Description', 'type' => 'textarea'),
                            array('name' => 'head_doctor', 'label' => 'Head Doctor', 'type' => 'text'),
                            array('name' => 'phone', 'label' => 'Department Phone', 'type' => 'text')
                        ))
                    ),
                    'defaults' => array()
                )
            )
        );
    }
    
    private static function get_blog_page($type = 'general') {
        $content = self::get_blog_content($type);
        return array(
            'name' => 'Health Blog',
            'slug' => 'blog',
            'icon' => 'dashicons-welcome-write-blog',
            'required' => false,
            'description' => 'Health tips, news, and medical articles.',
            'default_content' => $content,
            'sections' => array(
                array(
                    'name' => 'Page Header',
                    'type' => 'page_header',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
                        array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
                    ),
                    'defaults' => $content['header'] ?? array()
                ),
                array(
                    'name' => 'Blog Posts',
                    'type' => 'blog',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'posts', 'label' => 'Blog Posts', 'type' => 'repeater', 'fields' => array(
                            array('name' => 'image', 'label' => 'Featured Image', 'type' => 'image'),
                            array('name' => 'title', 'label' => 'Post Title', 'type' => 'text'),
                            array('name' => 'excerpt', 'label' => 'Excerpt', 'type' => 'textarea'),
                            array('name' => 'content', 'label' => 'Full Content', 'type' => 'editor'),
                            array('name' => 'author', 'label' => 'Author', 'type' => 'text'),
                            array('name' => 'date', 'label' => 'Date', 'type' => 'text'),
                            array('name' => 'category', 'label' => 'Category', 'type' => 'text')
                        ))
                    ),
                    'defaults' => $content['posts'] ?? array()
                )
            )
        );
    }
    
    private static function get_contact_page($type = 'general') {
        $content = self::get_contact_content($type);
        return array(
            'name' => 'Contact Us',
            'slug' => 'contact',
            'icon' => 'dashicons-phone',
            'required' => true,
            'description' => 'Contact information, location map, and inquiry form.',
            'default_content' => $content,
            'sections' => array(
                array(
                    'name' => 'Page Header',
                    'type' => 'page_header',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
                        array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
                    ),
                    'defaults' => $content['header'] ?? array()
                ),
                array(
                    'name' => 'Contact Information',
                    'type' => 'contact_info',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'address', 'label' => 'Full Address', 'type' => 'textarea'),
                        array('name' => 'phone', 'label' => 'Main Phone', 'type' => 'text'),
                        array('name' => 'emergency', 'label' => 'Emergency Phone', 'type' => 'text'),
                        array('name' => 'email', 'label' => 'Email Address', 'type' => 'email'),
                        array('name' => 'hours', 'label' => 'Working Hours', 'type' => 'textarea')
                    ),
                    'defaults' => $content['contact'] ?? array()
                ),
                array(
                    'name' => 'Map',
                    'type' => 'map',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'map_embed', 'label' => 'Google Map Embed Code', 'type' => 'textarea')
                    ),
                    'defaults' => array()
                )
            )
        );
    }
    
    private static function get_gallery_page($type = 'dental') {
        return array(
            'name' => 'Smile Gallery',
            'slug' => 'gallery',
            'icon' => 'dashicons-format-gallery',
            'required' => false,
            'description' => 'Before and after photos showcasing our work.',
            'default_content' => array(),
            'sections' => array(
                array(
                    'name' => 'Page Header',
                    'type' => 'page_header',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
                        array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
                    ),
                    'defaults' => array('title' => 'Smile Gallery', 'subtitle' => 'See the beautiful transformations we\'ve helped create')
                ),
                array(
                    'name' => 'Gallery Images',
                    'type' => 'gallery',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'images', 'label' => 'Gallery Images', 'type' => 'gallery')
                    ),
                    'defaults' => array()
                )
            )
        );
    }
    
    private static function get_tests_page() {
        return array(
            'name' => 'Test Catalog',
            'slug' => 'tests',
            'icon' => 'dashicons-clipboard',
            'required' => false,
            'description' => 'Complete list of diagnostic tests and health packages.',
            'default_content' => array(),
            'sections' => array(
                array(
                    'name' => 'Page Header',
                    'type' => 'page_header',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
                        array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
                    ),
                    'defaults' => array('title' => 'Test Catalog', 'subtitle' => 'Comprehensive diagnostic tests with accurate results')
                ),
                array(
                    'name' => 'Test Categories',
                    'type' => 'tests',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'categories', 'label' => 'Test Categories', 'type' => 'repeater', 'fields' => array(
                            array('name' => 'name', 'label' => 'Category Name', 'type' => 'text'),
                            array('name' => 'tests', 'label' => 'Tests', 'type' => 'repeater', 'fields' => array(
                                array('name' => 'name', 'label' => 'Test Name', 'type' => 'text'),
                                array('name' => 'price', 'label' => 'Price', 'type' => 'text'),
                                array('name' => 'turnaround', 'label' => 'Turnaround Time', 'type' => 'text')
                            ))
                        ))
                    ),
                    'defaults' => array()
                )
            )
        );
    }
    
    // ==================== DEFAULT CONTENT ====================
    
    private static function get_home_content($type) {
        $contents = array(
            'general' => array(
                'header' => array(
                    'logo_text' => 'City General Hospital',
                    'phone' => '+1 (555) 123-4567',
                    'email' => 'info@citygeneralhospital.com',
                    'emergency_number' => '911 / +1 (555) 999-0000'
                ),
                'hero' => array(
                    'headline' => 'Your Health, Our Priority',
                    'subheadline' => 'Providing compassionate, world-class healthcare services with state-of-the-art facilities and experienced medical professionals.',
                    'cta_text' => 'Book Appointment',
                    'cta_link' => '#contact',
                    'cta2_text' => 'Our Services',
                    'cta2_link' => '#services'
                ),
                'info_cards' => array(
                    'card1_icon' => 'clock',
                    'card1_title' => '24/7 Emergency',
                    'card1_text' => 'Round-the-clock emergency care with rapid response team ready to help.',
                    'card2_icon' => 'calendar',
                    'card2_title' => 'Easy Appointments',
                    'card2_text' => 'Book your appointments online or call us anytime.',
                    'card3_icon' => 'shield',
                    'card3_title' => 'Quality Care',
                    'card3_text' => 'Accredited facility with certified medical professionals.'
                ),
                'services' => array(
                    'section_title' => 'Our Medical Services',
                    'section_subtitle' => 'Comprehensive healthcare solutions for you and your family'
                ),
                'features' => array(
                    'section_title' => 'Why Choose Us?',
                    'section_text' => 'We combine advanced medical technology with compassionate care to deliver the best health outcomes.',
                    'feature1' => 'Board-Certified Physicians',
                    'feature2' => 'Latest Medical Technology',
                    'feature3' => 'Patient-Centered Care',
                    'feature4' => '50+ Years of Excellence'
                ),
                'stats' => array(
                    'stat1_number' => '50+',
                    'stat1_label' => 'Years Experience',
                    'stat2_number' => '200+',
                    'stat2_label' => 'Expert Doctors',
                    'stat3_number' => '100K+',
                    'stat3_label' => 'Patients Served',
                    'stat4_number' => '50+',
                    'stat4_label' => 'Specialties'
                ),
                'cta' => array(
                    'cta_headline' => 'Need Emergency Care?',
                    'cta_text' => 'Our emergency department is open 24/7. Don\'t wait - get the care you need now.',
                    'cta_button' => 'Call Emergency',
                    'cta_link' => 'tel:+15559990000'
                ),
                'footer' => array(
                    'footer_about' => 'City General Hospital has been serving our community for over 50 years, providing exceptional healthcare with compassion and excellence.',
                    'footer_address' => '123 Healthcare Avenue\nMedical District\nNew York, NY 10001',
                    'footer_phone' => '+1 (555) 123-4567',
                    'footer_email' => 'info@citygeneralhospital.com',
                    'copyright' => '© 2024 City General Hospital. All rights reserved.'
                )
            ),
            'dental' => array(
                'header' => array(
                    'logo_text' => 'Bright Smile Dental',
                    'phone' => '+1 (555) 234-5678',
                    'email' => 'smile@brightdental.com',
                    'emergency_number' => '+1 (555) 234-9999'
                ),
                'hero' => array(
                    'headline' => 'Your Perfect Smile Starts Here',
                    'subheadline' => 'Experience gentle, modern dentistry in a comfortable environment. We make dental visits something to smile about!',
                    'cta_text' => 'Schedule Visit',
                    'cta_link' => '#contact',
                    'cta2_text' => 'View Services',
                    'cta2_link' => '#services'
                ),
                'info_cards' => array(
                    'card1_icon' => 'smile',
                    'card1_title' => 'Gentle Care',
                    'card1_text' => 'Anxiety-free dentistry with sedation options available.',
                    'card2_icon' => 'sparkles',
                    'card2_title' => 'Modern Tech',
                    'card2_text' => 'Digital X-rays and laser dentistry for best results.',
                    'card3_icon' => 'family',
                    'card3_title' => 'Family Friendly',
                    'card3_text' => 'Dental care for patients of all ages.'
                ),
                'stats' => array(
                    'stat1_number' => '15K+',
                    'stat1_label' => 'Happy Smiles',
                    'stat2_number' => '25+',
                    'stat2_label' => 'Years Experience',
                    'stat3_number' => '5',
                    'stat3_label' => 'Star Rating',
                    'stat4_number' => '12',
                    'stat4_label' => 'Dental Experts'
                ),
                'footer' => array(
                    'footer_about' => 'Bright Smile Dental is committed to providing exceptional dental care in a warm, welcoming environment.',
                    'footer_address' => '456 Dental Plaza\nSuite 200\nLos Angeles, CA 90001',
                    'footer_phone' => '+1 (555) 234-5678',
                    'footer_email' => 'smile@brightdental.com',
                    'copyright' => '© 2024 Bright Smile Dental. All rights reserved.'
                )
            ),
            'eye' => array(
                'header' => array(
                    'logo_text' => 'ClearView Eye Center',
                    'phone' => '+1 (555) 345-6789',
                    'email' => 'care@clearvieweye.com',
                    'emergency_number' => '+1 (555) 345-9999'
                ),
                'hero' => array(
                    'headline' => 'See Life More Clearly',
                    'subheadline' => 'Advanced eye care and vision correction services. From comprehensive exams to LASIK surgery, we help you see your best.',
                    'cta_text' => 'Book Eye Exam',
                    'cta_link' => '#contact',
                    'cta2_text' => 'Learn About LASIK',
                    'cta2_link' => '#services'
                ),
                'stats' => array(
                    'stat1_number' => '30K+',
                    'stat1_label' => 'Eyes Treated',
                    'stat2_number' => '99%',
                    'stat2_label' => 'Success Rate',
                    'stat3_number' => '20+',
                    'stat3_label' => 'Years Experience',
                    'stat4_number' => '8',
                    'stat4_label' => 'Specialists'
                ),
                'footer' => array(
                    'footer_about' => 'ClearView Eye Center provides comprehensive eye care using the latest technology and techniques.',
                    'footer_address' => '789 Vision Way\nEye Care Center\nChicago, IL 60601',
                    'footer_phone' => '+1 (555) 345-6789',
                    'footer_email' => 'care@clearvieweye.com',
                    'copyright' => '© 2024 ClearView Eye Center. All rights reserved.'
                )
            ),
            'pediatric' => array(
                'header' => array(
                    'logo_text' => 'Happy Kids Children\'s Hospital',
                    'phone' => '+1 (555) 456-7890',
                    'email' => 'care@happykidshospital.com',
                    'emergency_number' => '+1 (555) 456-9999'
                ),
                'hero' => array(
                    'headline' => 'Where Little Heroes Get Big Care',
                    'subheadline' => 'Child-friendly healthcare with specialized pediatric experts. Making hospital visits less scary and more caring for your little ones.',
                    'cta_text' => 'Book Appointment',
                    'cta_link' => '#contact',
                    'cta2_text' => 'Our Services',
                    'cta2_link' => '#services'
                ),
                'stats' => array(
                    'stat1_number' => '50K+',
                    'stat1_label' => 'Kids Treated',
                    'stat2_number' => '100+',
                    'stat2_label' => 'Pediatric Experts',
                    'stat3_number' => '30+',
                    'stat3_label' => 'Years Caring',
                    'stat4_number' => '24/7',
                    'stat4_label' => 'Emergency Care'
                ),
                'footer' => array(
                    'footer_about' => 'Happy Kids Children\'s Hospital is dedicated to providing the highest quality pediatric care in a warm, child-friendly environment.',
                    'footer_address' => '321 Rainbow Lane\nChildren\'s Medical Center\nBoston, MA 02101',
                    'footer_phone' => '+1 (555) 456-7890',
                    'footer_email' => 'care@happykidshospital.com',
                    'copyright' => '© 2024 Happy Kids Children\'s Hospital. All rights reserved.'
                )
            ),
            'cardiology' => array(
                'header' => array(
                    'logo_text' => 'HeartCare Cardiology Center',
                    'phone' => '+1 (555) 567-8901',
                    'email' => 'heart@heartcarecenter.com',
                    'emergency_number' => '+1 (555) 567-9999'
                ),
                'hero' => array(
                    'headline' => 'Your Heart in Expert Hands',
                    'subheadline' => 'Leading-edge cardiac care with compassion. From prevention to intervention, we\'re dedicated to your heart health.',
                    'cta_text' => 'Heart Screening',
                    'cta_link' => '#contact',
                    'cta2_text' => 'Our Expertise',
                    'cta2_link' => '#services'
                ),
                'stats' => array(
                    'stat1_number' => '25K+',
                    'stat1_label' => 'Hearts Healed',
                    'stat2_number' => '98%',
                    'stat2_label' => 'Success Rate',
                    'stat3_number' => '15',
                    'stat3_label' => 'Cardiologists',
                    'stat4_number' => '40+',
                    'stat4_label' => 'Years Experience'
                ),
                'footer' => array(
                    'footer_about' => 'HeartCare Cardiology Center is committed to providing exceptional cardiovascular care using the most advanced treatments.',
                    'footer_address' => '555 Heart Health Blvd\nCardiac Care Building\nHouston, TX 77001',
                    'footer_phone' => '+1 (555) 567-8901',
                    'footer_email' => 'heart@heartcarecenter.com',
                    'copyright' => '© 2024 HeartCare Cardiology Center. All rights reserved.'
                )
            ),
            'mental' => array(
                'header' => array(
                    'logo_text' => 'Serenity Mental Wellness',
                    'phone' => '+1 (555) 678-9012',
                    'email' => 'support@serenitywellness.com',
                    'emergency_number' => '988 (Crisis Line)'
                ),
                'hero' => array(
                    'headline' => 'Your Journey to Wellness Begins Here',
                    'subheadline' => 'Compassionate mental health care in a safe, supportive environment. You don\'t have to face this alone.',
                    'cta_text' => 'Get Support',
                    'cta_link' => '#contact',
                    'cta2_text' => 'Our Services',
                    'cta2_link' => '#services'
                ),
                'stats' => array(
                    'stat1_number' => '10K+',
                    'stat1_label' => 'Lives Changed',
                    'stat2_number' => '50+',
                    'stat2_label' => 'Therapists',
                    'stat3_number' => '95%',
                    'stat3_label' => 'Patient Satisfaction',
                    'stat4_number' => '20+',
                    'stat4_label' => 'Years Healing'
                ),
                'footer' => array(
                    'footer_about' => 'Serenity Mental Wellness provides comprehensive mental health services with compassion, understanding, and respect.',
                    'footer_address' => '888 Peaceful Path\nWellness Center\nSeattle, WA 98101',
                    'footer_phone' => '+1 (555) 678-9012',
                    'footer_email' => 'support@serenitywellness.com',
                    'copyright' => '© 2024 Serenity Mental Wellness. All rights reserved.'
                )
            ),
            'orthopedic' => array(
                'header' => array(
                    'logo_text' => 'SpineFirst Orthopedic Center',
                    'phone' => '+1 (555) 789-0123',
                    'email' => 'care@spinefirst.com',
                    'emergency_number' => '+1 (555) 789-9999'
                ),
                'hero' => array(
                    'headline' => 'Get Moving Again',
                    'subheadline' => 'Expert orthopedic care for bones, joints, and spine. From sports injuries to joint replacement, we help you live pain-free.',
                    'cta_text' => 'Book Consultation',
                    'cta_link' => '#contact',
                    'cta2_text' => 'Our Treatments',
                    'cta2_link' => '#services'
                ),
                'stats' => array(
                    'stat1_number' => '20K+',
                    'stat1_label' => 'Surgeries',
                    'stat2_number' => '99%',
                    'stat2_label' => 'Success Rate',
                    'stat3_number' => '18',
                    'stat3_label' => 'Specialists',
                    'stat4_number' => '35+',
                    'stat4_label' => 'Years Experience'
                ),
                'footer' => array(
                    'footer_about' => 'SpineFirst Orthopedic Center specializes in comprehensive bone, joint, and spine care using minimally invasive techniques.',
                    'footer_address' => '999 Motion Avenue\nOrthopedic Building\nDenver, CO 80201',
                    'footer_phone' => '+1 (555) 789-0123',
                    'footer_email' => 'care@spinefirst.com',
                    'copyright' => '© 2024 SpineFirst Orthopedic Center. All rights reserved.'
                )
            ),
            'diagnostic' => array(
                'header' => array(
                    'logo_text' => 'PrecisionLab Diagnostics',
                    'phone' => '+1 (555) 890-1234',
                    'email' => 'tests@precisionlab.com',
                    'emergency_number' => '+1 (555) 890-9999'
                ),
                'hero' => array(
                    'headline' => 'Accurate Results, Better Health',
                    'subheadline' => 'State-of-the-art diagnostic testing with quick turnaround. Your health insights delivered with precision and care.',
                    'cta_text' => 'Book a Test',
                    'cta_link' => '#contact',
                    'cta2_text' => 'View Test Catalog',
                    'cta2_link' => '#tests'
                ),
                'stats' => array(
                    'stat1_number' => '500K+',
                    'stat1_label' => 'Tests Conducted',
                    'stat2_number' => '99.9%',
                    'stat2_label' => 'Accuracy',
                    'stat3_number' => '24h',
                    'stat3_label' => 'Report Time',
                    'stat4_number' => '50+',
                    'stat4_label' => 'Collection Centers'
                ),
                'footer' => array(
                    'footer_about' => 'PrecisionLab Diagnostics provides accurate, affordable, and accessible diagnostic testing services.',
                    'footer_address' => '111 Lab Lane\nDiagnostic Center\nPhiladelphia, PA 19101',
                    'footer_phone' => '+1 (555) 890-1234',
                    'footer_email' => 'tests@precisionlab.com',
                    'copyright' => '© 2024 PrecisionLab Diagnostics. All rights reserved.'
                )
            )
        );
        
        return $contents[$type] ?? $contents['general'];
    }
    
    private static function get_about_content($type) {
        return array(
            'header' => array('title' => 'About Us', 'breadcrumb' => 'Home / About Us'),
            'intro' => array(
                'title' => 'Our Story',
                'content' => 'For decades, we have been at the forefront of healthcare, combining medical excellence with compassionate care. Our dedicated team of healthcare professionals is committed to providing the highest quality services to our patients and community.'
            ),
            'mission' => array(
                'mission_title' => 'Our Mission',
                'mission_text' => 'To provide exceptional healthcare services that improve the health and well-being of our community through compassion, innovation, and excellence.',
                'vision_title' => 'Our Vision',
                'vision_text' => 'To be the most trusted healthcare provider, recognized for outstanding patient care, medical innovation, and community service.',
                'values_title' => 'Our Values',
                'values_text' => 'Compassion • Excellence • Integrity • Innovation • Teamwork • Respect'
            ),
            'history' => array(
                'title' => 'Our History',
                'content' => 'Established with a vision to serve our community, we have grown from a small clinic to a comprehensive healthcare facility, touching thousands of lives along the way.'
            )
        );
    }
    
    private static function get_services_content($type) {
        $services = array(
            'general' => array('Emergency Care', 'Surgery', 'Internal Medicine', 'Pediatrics', 'Cardiology', 'Orthopedics'),
            'dental' => array('General Dentistry', 'Cosmetic Dentistry', 'Orthodontics', 'Dental Implants', 'Root Canal', 'Teeth Whitening'),
            'eye' => array('Eye Exams', 'LASIK Surgery', 'Cataract Treatment', 'Glaucoma Care', 'Contact Lenses', 'Pediatric Eye Care'),
            'pediatric' => array('Well-Child Visits', 'Vaccinations', 'Pediatric Surgery', 'Developmental Care', 'Emergency Care', 'Specialist Referrals'),
            'cardiology' => array('Heart Screening', 'Cardiac Catheterization', 'Heart Surgery', 'Pacemaker Implants', 'Cardiac Rehabilitation', 'Preventive Care'),
            'mental' => array('Individual Therapy', 'Group Therapy', 'Psychiatric Services', 'Crisis Intervention', 'Addiction Treatment', 'Family Counseling'),
            'orthopedic' => array('Joint Replacement', 'Spine Surgery', 'Sports Medicine', 'Fracture Care', 'Physical Therapy', 'Arthroscopy'),
            'diagnostic' => array('Blood Tests', 'Imaging Services', 'Health Packages', 'Corporate Wellness', 'Home Collection', 'Online Reports')
        );
        
        return array(
            'header' => array('title' => 'Our Services', 'subtitle' => 'Comprehensive healthcare services tailored to your needs'),
            'services' => $services[$type] ?? $services['general']
        );
    }
    
    private static function get_doctors_content($type) {
        return array(
            'header' => array('title' => 'Our Medical Team', 'subtitle' => 'Meet our experienced and caring healthcare professionals')
        );
    }
    
    private static function get_blog_content($type) {
        $topics = array(
            'general' => array('Health Tips for a Stronger Immune System', 'Understanding Preventive Care', 'When to Visit the Emergency Room'),
            'dental' => array('Tips for Maintaining Healthy Teeth', 'The Benefits of Regular Dental Checkups', 'Foods That Are Good for Your Teeth'),
            'eye' => array('Protecting Your Eyes in the Digital Age', 'Signs You Need an Eye Exam', 'Understanding LASIK Surgery'),
            'pediatric' => array('Keeping Your Child Healthy', 'Vaccination Schedule Guide', 'Nutrition Tips for Growing Kids'),
            'cardiology' => array('Heart-Healthy Lifestyle Tips', 'Understanding Blood Pressure', 'Signs of Heart Problems'),
            'mental' => array('Managing Stress and Anxiety', 'The Importance of Self-Care', 'When to Seek Professional Help'),
            'orthopedic' => array('Preventing Sports Injuries', 'Understanding Back Pain', 'Recovery After Joint Surgery'),
            'diagnostic' => array('Understanding Your Lab Results', 'Importance of Regular Health Checkups', 'Preparing for Your Tests')
        );
        
        return array(
            'header' => array('title' => 'Health Blog', 'subtitle' => 'Expert health advice, tips, and news from our medical team'),
            'posts' => $topics[$type] ?? $topics['general']
        );
    }
    
    private static function get_contact_content($type) {
        return array(
            'header' => array('title' => 'Contact Us', 'subtitle' => 'We\'re here to help. Reach out to us anytime.'),
            'contact' => array(
                'address' => '123 Medical Center Drive\nHealthcare District\nYour City, State 12345',
                'phone' => '+1 (555) 123-4567',
                'emergency' => '+1 (555) 999-0000',
                'email' => 'info@hospital.com',
                'hours' => "Monday - Friday: 8:00 AM - 8:00 PM\nSaturday: 9:00 AM - 5:00 PM\nSunday: Emergency Only\n\n24/7 Emergency Services Available"
            )
        );
    }
    
    // ==================== CUSTOM CSS ====================
    
    private static function get_hospital_css() {
        return '
/* Hospital Website Custom CSS */

/* General Styles */
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-family), sans-serif; color: var(--text-color); background: var(--background-color); line-height: 1.6; }

/* Variables */
:root {
    --primary: #0891b2;
    --secondary: #0e7490;
    --accent: #06b6d4;
    --bg: #f0fdfa;
    --text: #1e293b;
    --white: #ffffff;
    --dark: #0f172a;
}

/* Header */
.site-header {
    background: var(--header-bg-color, #fff);
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
    position: sticky;
    top: 0;
    z-index: 1000;
}
.header-top {
    background: var(--primary-color);
    color: #fff;
    padding: 8px 0;
    font-size: 14px;
}
.header-top a { color: #fff; text-decoration: none; }
.header-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 5%;
    max-width: 1400px;
    margin: 0 auto;
}
.logo { font-size: 24px; font-weight: 700; color: var(--primary-color); text-decoration: none; }
.main-nav { display: flex; gap: 30px; }
.main-nav a {
    text-decoration: none;
    color: var(--text-color);
    font-weight: 500;
    padding: 10px 0;
    border-bottom: 2px solid transparent;
    transition: all 0.3s;
}
.main-nav a:hover { color: var(--primary-color); border-bottom-color: var(--primary-color); }
.header-cta {
    background: var(--primary-color);
    color: #fff;
    padding: 12px 25px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}
.header-cta:hover { background: var(--secondary-color); transform: translateY(-2px); }

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: #fff;
    padding: 100px 5%;
    position: relative;
    overflow: hidden;
}
.hero-content { max-width: 600px; position: relative; z-index: 2; }
.hero-content h1 {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 20px;
    line-height: 1.2;
}
.hero-content p {
    font-size: 18px;
    opacity: 0.9;
    margin-bottom: 30px;
    line-height: 1.7;
}
.hero-buttons { display: flex; gap: 15px; flex-wrap: wrap; }
.btn-primary {
    background: #fff;
    color: var(--primary-color);
    padding: 15px 35px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}
.btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
.btn-secondary {
    background: transparent;
    color: #fff;
    padding: 15px 35px;
    border: 2px solid #fff;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}
.btn-secondary:hover { background: #fff; color: var(--primary-color); }

/* Info Cards */
.info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    max-width: 1200px;
    margin: -50px auto 50px;
    padding: 0 5%;
    position: relative;
    z-index: 10;
}
.info-card {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    display: flex;
    gap: 20px;
    align-items: flex-start;
    transition: all 0.3s;
}
.info-card:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(0,0,0,0.15); }
.info-card-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    flex-shrink: 0;
}
.info-card h3 { font-size: 18px; margin-bottom: 8px; color: var(--text-color); }
.info-card p { font-size: 14px; color: #666; line-height: 1.6; }

/* Sections */
.section { padding: 80px 5%; max-width: 1400px; margin: 0 auto; }
.section-header { text-align: center; margin-bottom: 50px; }
.section-header h2 {
    font-size: 36px;
    color: var(--primary-color);
    margin-bottom: 15px;
}
.section-header p { font-size: 18px; color: #666; max-width: 600px; margin: 0 auto; }

/* Services Grid */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}
.service-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.service-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.12); }
.service-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 32px;
    margin: 30px auto 20px;
}
.service-card h3 { text-align: center; font-size: 20px; margin-bottom: 10px; }
.service-card p { text-align: center; padding: 0 25px 30px; color: #666; }

/* Stats Section */
.stats-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    padding: 60px 5%;
    color: #fff;
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
}
.stat-item h3 { font-size: 48px; font-weight: 700; margin-bottom: 10px; }
.stat-item p { font-size: 16px; opacity: 0.9; }

/* Doctors Grid */
.doctors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
}
.doctor-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    text-align: center;
    transition: all 0.3s;
}
.doctor-card:hover { transform: translateY(-5px); }
.doctor-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    margin: 30px auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 40px;
    font-weight: 700;
}
.doctor-card h3 { font-size: 20px; margin-bottom: 5px; }
.doctor-card .specialty { color: var(--primary-color); font-weight: 600; margin-bottom: 10px; }
.doctor-card .bio { padding: 0 20px 25px; color: #666; font-size: 14px; }

/* Blog Section */
.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
}
.blog-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.blog-card:hover { transform: translateY(-5px); }
.blog-image {
    height: 200px;
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 48px;
}
.blog-content { padding: 25px; }
.blog-meta { font-size: 13px; color: #999; margin-bottom: 10px; }
.blog-card h3 { font-size: 18px; margin-bottom: 10px; line-height: 1.4; }
.blog-card p { color: #666; font-size: 14px; line-height: 1.6; }
.read-more {
    display: inline-block;
    margin-top: 15px;
    color: var(--primary-color);
    font-weight: 600;
    text-decoration: none;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--primary-color) 100%);
    padding: 80px 5%;
    text-align: center;
    color: #fff;
}
.cta-section h2 { font-size: 36px; margin-bottom: 15px; }
.cta-section p { font-size: 18px; margin-bottom: 30px; opacity: 0.9; }
.cta-section .btn-primary {
    background: #fff;
    color: var(--primary-color);
    font-size: 18px;
    padding: 18px 45px;
}

/* Contact Section */
.contact-section { background: #fff; }
.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
}
.contact-info-card {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    padding: 25px;
    background: var(--background-color);
    border-radius: 12px;
}
.contact-icon {
    width: 50px;
    height: 50px;
    background: var(--primary-color);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
    flex-shrink: 0;
}
.contact-info-card h4 { font-size: 16px; margin-bottom: 5px; }
.contact-info-card p { color: #666; font-size: 14px; line-height: 1.6; }

/* Footer */
.site-footer {
    background: var(--footer-bg-color, #0f172a);
    color: #fff;
    padding: 60px 5% 30px;
}
.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    max-width: 1400px;
    margin: 0 auto;
}
.footer-col h4 {
    font-size: 18px;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
}
.footer-col h4::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 40px;
    height: 3px;
    background: var(--primary-color);
}
.footer-col p { opacity: 0.8; line-height: 1.7; margin-bottom: 10px; }
.footer-col a { color: rgba(255,255,255,0.8); text-decoration: none; display: block; margin-bottom: 10px; transition: color 0.3s; }
.footer-col a:hover { color: var(--accent-color); }
.social-links { display: flex; gap: 10px; margin-top: 15px; }
.social-links a {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}
.social-links a:hover { background: var(--primary-color); }
.footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.1);
    margin-top: 40px;
    padding-top: 25px;
    text-align: center;
    opacity: 0.7;
    font-size: 14px;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    padding: 80px 5%;
    text-align: center;
    color: #fff;
}
.page-header h1 { font-size: 42px; margin-bottom: 10px; }
.page-header .breadcrumb { opacity: 0.8; font-size: 14px; }

/* Responsive */
@media (max-width: 768px) {
    .hero-content h1 { font-size: 32px; }
    .section-header h2 { font-size: 28px; }
    .header-main { flex-wrap: wrap; gap: 15px; }
    .main-nav { display: none; }
    .info-cards { margin-top: -30px; }
}
';
    }
    
    public static function drop_tables() {
        global $wpdb;
        
        $tables = array(
            $wpdb->prefix . 'spfm_form_submissions',
            $wpdb->prefix . 'spfm_form_shares',
            $wpdb->prefix . 'spfm_page_sections',
            $wpdb->prefix . 'spfm_theme_pages',
            $wpdb->prefix . 'spfm_forms',
            $wpdb->prefix . 'spfm_themes',
            $wpdb->prefix . 'spfm_customers',
            $wpdb->prefix . 'spfm_users'
        );
        
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
        
        delete_option('spfm_db_version');
    }
}
