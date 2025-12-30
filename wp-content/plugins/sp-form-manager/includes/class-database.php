<?php
/**
 * Database Handler Class
 * Professional Hospital/Medical Website Template System
 * Version 3.0 - Unique Designs for Each Template
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPFM_Database {
    
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Users table
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
        
        // Themes/Templates table
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
            layout_style VARCHAR(50) DEFAULT 'modern',
            header_style VARCHAR(50) DEFAULT 'standard',
            hero_style VARCHAR(50) DEFAULT 'centered',
            buttons_config LONGTEXT,
            links_config LONGTEXT,
            site_content LONGTEXT,
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
            page_layout VARCHAR(50) DEFAULT 'standard',
            show_in_menu TINYINT(1) DEFAULT 1,
            menu_label VARCHAR(100) DEFAULT '',
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY theme_id (theme_id)
        ) $charset_collate;";
        
        // Page Sections table
        $table_page_sections = $wpdb->prefix . 'spfm_page_sections';
        $sql_page_sections = "CREATE TABLE $table_page_sections (
            id INT(11) NOT NULL AUTO_INCREMENT,
            page_id INT(11) NOT NULL,
            section_name VARCHAR(200) NOT NULL,
            section_type VARCHAR(100) DEFAULT 'content',
            section_layout VARCHAR(50) DEFAULT 'full',
            section_order INT(11) DEFAULT 0,
            fields LONGTEXT,
            default_values LONGTEXT,
            buttons LONGTEXT,
            links LONGTEXT,
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
            buttons_config LONGTEXT,
            links_config LONGTEXT,
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
        
        self::create_default_admin();
        self::create_all_templates();
        
        update_option('spfm_flush_rules', true);
        update_option('spfm_db_version', '3.0.0');
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
    
    private static function create_all_templates() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM $themes_table WHERE is_template = 1");
        if ($exists) return;
        
        // Create all 8 unique templates
        self::create_template_general_hospital();
        self::create_template_dental_clinic();
        self::create_template_eye_care();
        self::create_template_pediatric();
        self::create_template_cardiology();
        self::create_template_mental_health();
        self::create_template_orthopedic();
        self::create_template_diagnostic();
    }
    
    // =====================================================
    // TEMPLATE 1: GENERAL HOSPITAL - Multi-Department Layout
    // =====================================================
    private static function create_template_general_hospital() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        $sections_table = $wpdb->prefix . 'spfm_page_sections';
        
        // Insert theme
        $wpdb->insert($themes_table, array(
            'name' => 'General Hospital',
            'description' => 'Complete multi-department hospital website with emergency services, departments, doctors, and patient portal.',
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
            'layout_style' => 'professional',
            'header_style' => 'mega-menu',
            'hero_style' => 'split-image',
            'features' => json_encode(['24/7 Emergency', 'Multi-Specialty', 'Patient Portal', 'Online Appointments', 'Lab Results', 'Pharmacy']),
            'buttons_config' => json_encode(array(
                'primary' => array('text' => 'Book Appointment', 'link' => '/contact', 'style' => 'filled'),
                'secondary' => array('text' => 'Emergency: 911', 'link' => 'tel:911', 'style' => 'outline'),
                'header_cta' => array('text' => 'Patient Portal', 'link' => '/portal', 'style' => 'filled')
            )),
            'is_template' => 1,
            'status' => 1
        ));
        $theme_id = $wpdb->insert_id;
        
        // PAGE 1: HOME - Hospital Landing
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id,
            'page_name' => 'Home',
            'page_slug' => 'home',
            'page_icon' => 'dashicons-admin-home',
            'page_order' => 0,
            'is_required' => 1,
            'page_description' => 'Hospital homepage with emergency banner, departments overview, and patient resources',
            'page_layout' => 'full-width'
        ));
        $page_id = $wpdb->insert_id;
        
        // Home sections
        self::insert_section($page_id, 'Top Bar', 'topbar', 0, array(
            array('name' => 'emergency_text', 'label' => 'Emergency Text', 'type' => 'text'),
            array('name' => 'emergency_number', 'label' => 'Emergency Number', 'type' => 'text'),
            array('name' => 'working_hours', 'label' => 'Working Hours', 'type' => 'text'),
            array('name' => 'location_text', 'label' => 'Location Text', 'type' => 'text')
        ), array(
            'emergency_text' => '🚨 24/7 Emergency Services Available',
            'emergency_number' => '911',
            'working_hours' => 'Mon-Sat: 8AM-8PM | Sun: Emergency Only',
            'location_text' => '📍 123 Medical Center Dr, New York'
        ));
        
        self::insert_section($page_id, 'Header', 'header', 1, array(
            array('name' => 'logo_text', 'label' => 'Hospital Name', 'type' => 'text'),
            array('name' => 'logo_image', 'label' => 'Logo Image', 'type' => 'image'),
            array('name' => 'tagline', 'label' => 'Tagline', 'type' => 'text'),
            array('name' => 'phone', 'label' => 'Phone Number', 'type' => 'text'),
            array('name' => 'cta_button_text', 'label' => 'CTA Button Text', 'type' => 'text'),
            array('name' => 'cta_button_link', 'label' => 'CTA Button Link', 'type' => 'url')
        ), array(
            'logo_text' => 'City General Hospital',
            'tagline' => 'Excellence in Healthcare Since 1970',
            'phone' => '+1 (555) 123-4567',
            'cta_button_text' => 'Patient Portal',
            'cta_button_link' => '/patient-portal'
        ));
        
        self::insert_section($page_id, 'Hero Section', 'hero_split', 2, array(
            array('name' => 'headline', 'label' => 'Main Headline', 'type' => 'text'),
            array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea'),
            array('name' => 'hero_image', 'label' => 'Hero Image', 'type' => 'image'),
            array('name' => 'primary_btn_text', 'label' => 'Primary Button Text', 'type' => 'text'),
            array('name' => 'primary_btn_link', 'label' => 'Primary Button Link', 'type' => 'url'),
            array('name' => 'secondary_btn_text', 'label' => 'Secondary Button Text', 'type' => 'text'),
            array('name' => 'secondary_btn_link', 'label' => 'Secondary Button Link', 'type' => 'url'),
            array('name' => 'badge_text', 'label' => 'Award Badge Text', 'type' => 'text')
        ), array(
            'headline' => 'World-Class Healthcare for Your Family',
            'subheadline' => 'Comprehensive medical care with 50+ departments, 200+ specialists, and state-of-the-art facilities serving our community for over 50 years.',
            'primary_btn_text' => 'Find a Doctor',
            'primary_btn_link' => '/doctors',
            'secondary_btn_text' => 'View Departments',
            'secondary_btn_link' => '/departments',
            'badge_text' => '🏆 #1 Rated Hospital in the Region'
        ));
        
        self::insert_section($page_id, 'Quick Services', 'service_cards', 3, array(
            array('name' => 'card1_icon', 'label' => 'Card 1 Icon', 'type' => 'icon'),
            array('name' => 'card1_title', 'label' => 'Card 1 Title', 'type' => 'text'),
            array('name' => 'card1_desc', 'label' => 'Card 1 Description', 'type' => 'textarea'),
            array('name' => 'card1_link', 'label' => 'Card 1 Link', 'type' => 'url'),
            array('name' => 'card2_icon', 'label' => 'Card 2 Icon', 'type' => 'icon'),
            array('name' => 'card2_title', 'label' => 'Card 2 Title', 'type' => 'text'),
            array('name' => 'card2_desc', 'label' => 'Card 2 Description', 'type' => 'textarea'),
            array('name' => 'card2_link', 'label' => 'Card 2 Link', 'type' => 'url'),
            array('name' => 'card3_icon', 'label' => 'Card 3 Icon', 'type' => 'icon'),
            array('name' => 'card3_title', 'label' => 'Card 3 Title', 'type' => 'text'),
            array('name' => 'card3_desc', 'label' => 'Card 3 Description', 'type' => 'textarea'),
            array('name' => 'card3_link', 'label' => 'Card 3 Link', 'type' => 'url'),
            array('name' => 'card4_icon', 'label' => 'Card 4 Icon', 'type' => 'icon'),
            array('name' => 'card4_title', 'label' => 'Card 4 Title', 'type' => 'text'),
            array('name' => 'card4_desc', 'label' => 'Card 4 Description', 'type' => 'textarea'),
            array('name' => 'card4_link', 'label' => 'Card 4 Link', 'type' => 'url')
        ), array(
            'card1_icon' => '🚑', 'card1_title' => 'Emergency Care', 'card1_desc' => '24/7 emergency department with trauma center', 'card1_link' => '/emergency',
            'card2_icon' => '📅', 'card2_title' => 'Book Appointment', 'card2_desc' => 'Schedule online or call our helpline', 'card2_link' => '/appointments',
            'card3_icon' => '🔬', 'card3_title' => 'Lab & Diagnostics', 'card3_desc' => 'Complete diagnostic services under one roof', 'card3_link' => '/diagnostics',
            'card4_icon' => '💊', 'card4_title' => '24h Pharmacy', 'card4_desc' => 'Round-the-clock pharmacy services', 'card4_link' => '/pharmacy'
        ));
        
        self::insert_section($page_id, 'Departments Grid', 'departments_grid', 4, array(
            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
            array('name' => 'section_subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'),
            array('name' => 'departments', 'label' => 'Departments', 'type' => 'repeater')
        ), array(
            'section_title' => 'Our Medical Departments',
            'section_subtitle' => 'Comprehensive healthcare across 50+ specialties',
            'departments' => array(
                array('icon' => '❤️', 'name' => 'Cardiology', 'link' => '/departments/cardiology'),
                array('icon' => '🧠', 'name' => 'Neurology', 'link' => '/departments/neurology'),
                array('icon' => '🦴', 'name' => 'Orthopedics', 'link' => '/departments/orthopedics'),
                array('icon' => '👶', 'name' => 'Pediatrics', 'link' => '/departments/pediatrics'),
                array('icon' => '🔬', 'name' => 'Oncology', 'link' => '/departments/oncology'),
                array('icon' => '🫁', 'name' => 'Pulmonology', 'link' => '/departments/pulmonology'),
                array('icon' => '🩺', 'name' => 'General Medicine', 'link' => '/departments/medicine'),
                array('icon' => '🏥', 'name' => 'Surgery', 'link' => '/departments/surgery')
            )
        ));
        
        self::insert_section($page_id, 'Statistics', 'stats_bar', 5, array(
            array('name' => 'stat1_number', 'label' => 'Stat 1 Number', 'type' => 'text'),
            array('name' => 'stat1_label', 'label' => 'Stat 1 Label', 'type' => 'text'),
            array('name' => 'stat2_number', 'label' => 'Stat 2 Number', 'type' => 'text'),
            array('name' => 'stat2_label', 'label' => 'Stat 2 Label', 'type' => 'text'),
            array('name' => 'stat3_number', 'label' => 'Stat 3 Number', 'type' => 'text'),
            array('name' => 'stat3_label', 'label' => 'Stat 3 Label', 'type' => 'text'),
            array('name' => 'stat4_number', 'label' => 'Stat 4 Number', 'type' => 'text'),
            array('name' => 'stat4_label', 'label' => 'Stat 4 Label', 'type' => 'text')
        ), array(
            'stat1_number' => '500+', 'stat1_label' => 'Hospital Beds',
            'stat2_number' => '200+', 'stat2_label' => 'Expert Doctors',
            'stat3_number' => '50+', 'stat3_label' => 'Departments',
            'stat4_number' => '1M+', 'stat4_label' => 'Patients Served'
        ));
        
        self::insert_section($page_id, 'Featured Doctors', 'doctors_carousel', 6, array(
            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
            array('name' => 'view_all_text', 'label' => 'View All Button Text', 'type' => 'text'),
            array('name' => 'view_all_link', 'label' => 'View All Button Link', 'type' => 'url')
        ), array(
            'section_title' => 'Meet Our Specialists',
            'view_all_text' => 'View All Doctors →',
            'view_all_link' => '/doctors'
        ));
        
        self::insert_section($page_id, 'CTA Banner', 'cta_emergency', 7, array(
            array('name' => 'headline', 'label' => 'CTA Headline', 'type' => 'text'),
            array('name' => 'description', 'label' => 'Description', 'type' => 'textarea'),
            array('name' => 'button_text', 'label' => 'Button Text', 'type' => 'text'),
            array('name' => 'button_link', 'label' => 'Button Link', 'type' => 'url'),
            array('name' => 'phone_text', 'label' => 'Phone Text', 'type' => 'text'),
            array('name' => 'phone_number', 'label' => 'Phone Number', 'type' => 'text')
        ), array(
            'headline' => 'Need Emergency Medical Care?',
            'description' => 'Our Level 1 Trauma Center is open 24/7 with rapid response teams ready to help.',
            'button_text' => 'Emergency Services',
            'button_link' => '/emergency',
            'phone_text' => 'Call Emergency:',
            'phone_number' => '911'
        ));
        
        self::insert_section($page_id, 'Footer', 'footer_hospital', 8, array(
            array('name' => 'about_text', 'label' => 'About Text', 'type' => 'textarea'),
            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'email', 'label' => 'Email', 'type' => 'email'),
            array('name' => 'emergency', 'label' => 'Emergency Number', 'type' => 'text'),
            array('name' => 'facebook', 'label' => 'Facebook URL', 'type' => 'url'),
            array('name' => 'twitter', 'label' => 'Twitter URL', 'type' => 'url'),
            array('name' => 'linkedin', 'label' => 'LinkedIn URL', 'type' => 'url'),
            array('name' => 'copyright', 'label' => 'Copyright Text', 'type' => 'text')
        ), array(
            'about_text' => 'City General Hospital has been serving our community with excellence in healthcare for over 50 years. We are committed to providing compassionate, patient-centered care.',
            'address' => "123 Medical Center Drive\nHealthcare District\nNew York, NY 10001",
            'phone' => '+1 (555) 123-4567',
            'email' => 'info@citygeneralhospital.com',
            'emergency' => '911',
            'copyright' => '© 2024 City General Hospital. All Rights Reserved. | HIPAA Compliant | Joint Commission Accredited'
        ));
        
        // PAGE 2: DEPARTMENTS
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Departments', 'page_slug' => 'departments',
            'page_icon' => 'dashicons-building', 'page_order' => 1, 'is_required' => 1,
            'page_description' => 'All hospital departments with descriptions and contact info'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
            array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
            array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'),
            array('name' => 'breadcrumb', 'label' => 'Breadcrumb', 'type' => 'text')
        ), array('title' => 'Medical Departments', 'subtitle' => 'Specialized care across 50+ medical disciplines', 'breadcrumb' => 'Home / Departments'));
        
        self::insert_section($page_id, 'Departments List', 'departments_full', 1, array(
            array('name' => 'departments', 'label' => 'Departments', 'type' => 'repeater')
        ), array());
        
        // PAGE 3: DOCTORS
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Find a Doctor', 'page_slug' => 'doctors',
            'page_icon' => 'dashicons-groups', 'page_order' => 2, 'is_required' => 1,
            'page_description' => 'Doctor directory with search and filter'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
            array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
            array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
        ), array('title' => 'Find a Doctor', 'subtitle' => 'Search our directory of 200+ medical specialists'));
        
        self::insert_section($page_id, 'Doctor Search', 'doctor_search', 1, array(
            array('name' => 'search_placeholder', 'label' => 'Search Placeholder', 'type' => 'text'),
            array('name' => 'filter_by_dept', 'label' => 'Show Department Filter', 'type' => 'checkbox')
        ), array('search_placeholder' => 'Search by name, specialty, or condition...', 'filter_by_dept' => true));
        
        self::insert_section($page_id, 'Doctors Grid', 'doctors_grid', 2, array(
            array('name' => 'doctors', 'label' => 'Doctors', 'type' => 'repeater')
        ), array());
        
        // PAGE 4: SERVICES
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Services', 'page_slug' => 'services',
            'page_icon' => 'dashicons-heart', 'page_order' => 3, 'is_required' => 1,
            'page_description' => 'All medical services offered'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
            array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
            array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
        ), array('title' => 'Our Services', 'subtitle' => 'Comprehensive healthcare services for you and your family'));
        
        self::insert_section($page_id, 'Services List', 'services_detailed', 1, array(
            array('name' => 'services', 'label' => 'Services', 'type' => 'repeater')
        ), array(
            'services' => array(
                array('icon' => '🚑', 'title' => 'Emergency & Trauma', 'desc' => 'Level 1 Trauma Center with 24/7 emergency care', 'link' => '/services/emergency'),
                array('icon' => '🏥', 'title' => 'Inpatient Care', 'desc' => 'Comprehensive hospital admission services', 'link' => '/services/inpatient'),
                array('icon' => '🩺', 'title' => 'Outpatient Services', 'desc' => 'Day procedures and consultations', 'link' => '/services/outpatient'),
                array('icon' => '🔬', 'title' => 'Laboratory', 'desc' => 'Full diagnostic laboratory services', 'link' => '/services/lab'),
                array('icon' => '📷', 'title' => 'Imaging Center', 'desc' => 'MRI, CT, X-Ray, and Ultrasound', 'link' => '/services/imaging'),
                array('icon' => '💊', 'title' => 'Pharmacy', 'desc' => '24-hour pharmacy services', 'link' => '/services/pharmacy')
            )
        ));
        
        // PAGE 5: PATIENT PORTAL
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Patient Portal', 'page_slug' => 'portal',
            'page_icon' => 'dashicons-id', 'page_order' => 4, 'is_required' => 0,
            'page_description' => 'Patient resources and portal access'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
            array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
            array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
        ), array('title' => 'Patient Portal', 'subtitle' => 'Access your health records, appointments, and more'));
        
        self::insert_section($page_id, 'Portal Features', 'portal_features', 1, array(
            array('name' => 'features', 'label' => 'Features', 'type' => 'repeater')
        ), array(
            'features' => array(
                array('icon' => '📋', 'title' => 'Medical Records', 'desc' => 'View your complete medical history'),
                array('icon' => '📅', 'title' => 'Appointments', 'desc' => 'Schedule and manage appointments'),
                array('icon' => '💬', 'title' => 'Message Your Doctor', 'desc' => 'Secure communication with providers'),
                array('icon' => '🧪', 'title' => 'Test Results', 'desc' => 'View lab and imaging results'),
                array('icon' => '💳', 'title' => 'Billing', 'desc' => 'Pay bills and view statements'),
                array('icon' => '💊', 'title' => 'Prescriptions', 'desc' => 'Request prescription refills')
            )
        ));
        
        // PAGE 6: CONTACT
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Contact Us', 'page_slug' => 'contact',
            'page_icon' => 'dashicons-phone', 'page_order' => 5, 'is_required' => 1,
            'page_description' => 'Contact information and locations'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
            array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
            array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
        ), array('title' => 'Contact Us', 'subtitle' => 'We\'re here to help 24/7'));
        
        self::insert_section($page_id, 'Contact Cards', 'contact_cards', 1, array(
            array('name' => 'main_phone', 'label' => 'Main Phone', 'type' => 'text'),
            array('name' => 'emergency', 'label' => 'Emergency', 'type' => 'text'),
            array('name' => 'email', 'label' => 'Email', 'type' => 'email'),
            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
            array('name' => 'hours', 'label' => 'Working Hours', 'type' => 'textarea')
        ), array(
            'main_phone' => '+1 (555) 123-4567',
            'emergency' => '911',
            'email' => 'info@citygeneralhospital.com',
            'address' => "123 Medical Center Drive\nNew York, NY 10001",
            'hours' => "Mon-Fri: 8AM - 8PM\nSat: 9AM - 5PM\nSun: Emergency Only\nEmergency: 24/7"
        ));
        
        self::insert_section($page_id, 'Map', 'google_map', 2, array(
            array('name' => 'map_embed', 'label' => 'Google Maps Embed Code', 'type' => 'textarea')
        ), array());
    }
    
    // =====================================================
    // TEMPLATE 2: DENTAL CLINIC - Smile-Focused Design
    // =====================================================
    private static function create_template_dental_clinic() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        
        $wpdb->insert($themes_table, array(
            'name' => 'Dental Care Clinic',
            'description' => 'Modern dental clinic with smile gallery, treatment plans, and online booking.',
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
            'layout_style' => 'modern-clean',
            'header_style' => 'centered-logo',
            'hero_style' => 'smile-focused',
            'features' => json_encode(['Smile Gallery', 'Virtual Consultation', 'Treatment Plans', 'Sedation Dentistry', 'Same-Day Crowns', 'Family Dentistry']),
            'buttons_config' => json_encode(array(
                'primary' => array('text' => 'Book Your Smile', 'link' => '/book', 'style' => 'rounded'),
                'secondary' => array('text' => 'Free Consultation', 'link' => '/consultation', 'style' => 'outline')
            )),
            'is_template' => 1,
            'status' => 1
        ));
        $theme_id = $wpdb->insert_id;
        
        // PAGE 1: HOME
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Home', 'page_slug' => 'home',
            'page_icon' => 'dashicons-admin-home', 'page_order' => 0, 'is_required' => 1,
            'page_description' => 'Welcoming dental homepage with smile focus'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Header', 'header_centered', 0, array(
            array('name' => 'logo_text', 'label' => 'Clinic Name', 'type' => 'text'),
            array('name' => 'tagline', 'label' => 'Tagline', 'type' => 'text'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'book_btn_text', 'label' => 'Book Button Text', 'type' => 'text'),
            array('name' => 'book_btn_link', 'label' => 'Book Button Link', 'type' => 'url')
        ), array(
            'logo_text' => 'Bright Smile Dental',
            'tagline' => 'Creating Beautiful Smiles',
            'phone' => '+1 (555) 234-5678',
            'book_btn_text' => 'Book Your Visit',
            'book_btn_link' => '/contact'
        ));
        
        self::insert_section($page_id, 'Hero Section', 'hero_smile', 1, array(
            array('name' => 'headline', 'label' => 'Main Headline', 'type' => 'text'),
            array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea'),
            array('name' => 'cta_text', 'label' => 'CTA Button Text', 'type' => 'text'),
            array('name' => 'cta_link', 'label' => 'CTA Button Link', 'type' => 'url'),
            array('name' => 'consult_text', 'label' => 'Consultation Button Text', 'type' => 'text'),
            array('name' => 'consult_link', 'label' => 'Consultation Button Link', 'type' => 'url'),
            array('name' => 'promo_text', 'label' => 'Promo Banner Text', 'type' => 'text')
        ), array(
            'headline' => 'Your Perfect Smile Starts Here',
            'subheadline' => 'Experience gentle, modern dentistry in a comfortable environment. We make dental visits something to smile about!',
            'cta_text' => 'Schedule Appointment',
            'cta_link' => '/contact',
            'consult_text' => 'Free Consultation',
            'consult_link' => '/consultation',
            'promo_text' => '✨ New Patients: Free Whitening with Checkup!'
        ));
        
        self::insert_section($page_id, 'Services Pills', 'services_pills', 2, array(
            array('name' => 'services', 'label' => 'Services', 'type' => 'repeater')
        ), array(
            'services' => array(
                array('icon' => '✨', 'name' => 'Teeth Whitening', 'link' => '/services/whitening'),
                array('icon' => '📐', 'name' => 'Invisalign', 'link' => '/services/invisalign'),
                array('icon' => '🔧', 'name' => 'Dental Implants', 'link' => '/services/implants'),
                array('icon' => '👑', 'name' => 'Crowns & Veneers', 'link' => '/services/crowns'),
                array('icon' => '🦷', 'name' => 'General Dentistry', 'link' => '/services/general'),
                array('icon' => '😴', 'name' => 'Sedation Dentistry', 'link' => '/services/sedation')
            )
        ));
        
        self::insert_section($page_id, 'Why Choose Us', 'features_grid', 3, array(
            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
            array('name' => 'features', 'label' => 'Features', 'type' => 'repeater')
        ), array(
            'section_title' => 'Why Patients Love Us',
            'features' => array(
                array('icon' => '😊', 'title' => 'Gentle Care', 'desc' => 'Anxiety-free experience with sedation options'),
                array('icon' => '🏆', 'title' => '25+ Years', 'desc' => 'Trusted by thousands of families'),
                array('icon' => '🔬', 'title' => 'Latest Technology', 'desc' => 'Digital X-rays & laser dentistry'),
                array('icon' => '⏰', 'title' => 'Same-Day Service', 'desc' => 'Emergency and same-day appointments')
            )
        ));
        
        self::insert_section($page_id, 'Smile Gallery Preview', 'gallery_preview', 4, array(
            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
            array('name' => 'section_subtitle', 'label' => 'Subtitle', 'type' => 'textarea'),
            array('name' => 'gallery_link_text', 'label' => 'View Gallery Button', 'type' => 'text'),
            array('name' => 'gallery_link', 'label' => 'Gallery Link', 'type' => 'url')
        ), array(
            'section_title' => 'Smile Transformations',
            'section_subtitle' => 'See the beautiful smiles we\'ve helped create',
            'gallery_link_text' => 'View Full Gallery →',
            'gallery_link' => '/gallery'
        ));
        
        self::insert_section($page_id, 'Testimonials', 'testimonials_slider', 5, array(
            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
            array('name' => 'testimonials', 'label' => 'Testimonials', 'type' => 'repeater')
        ), array(
            'section_title' => 'What Our Patients Say',
            'testimonials' => array(
                array('text' => 'Best dental experience ever! The team made me feel so comfortable.', 'name' => 'Sarah M.', 'rating' => '5'),
                array('text' => 'Finally found a dentist I\'m not afraid of. Highly recommend!', 'name' => 'John D.', 'rating' => '5')
            )
        ));
        
        self::insert_section($page_id, 'Insurance Section', 'insurance_info', 6, array(
            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
            array('name' => 'description', 'label' => 'Description', 'type' => 'textarea'),
            array('name' => 'insurance_text', 'label' => 'Insurance Text', 'type' => 'text')
        ), array(
            'section_title' => 'Insurance & Payment',
            'description' => 'We accept most major dental insurance plans and offer flexible payment options.',
            'insurance_text' => 'We accept: Delta Dental, Cigna, Aetna, MetLife, and more'
        ));
        
        self::insert_section($page_id, 'Footer', 'footer_dental', 7, array(
            array('name' => 'about_text', 'label' => 'About Text', 'type' => 'textarea'),
            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'email', 'label' => 'Email', 'type' => 'email'),
            array('name' => 'hours', 'label' => 'Office Hours', 'type' => 'textarea'),
            array('name' => 'copyright', 'label' => 'Copyright', 'type' => 'text')
        ), array(
            'about_text' => 'Bright Smile Dental is committed to providing exceptional dental care in a warm, welcoming environment.',
            'address' => "456 Dental Plaza, Suite 200\nLos Angeles, CA 90001",
            'phone' => '+1 (555) 234-5678',
            'email' => 'smile@brightdental.com',
            'hours' => "Mon-Thu: 8AM - 6PM\nFri: 8AM - 4PM\nSat: 9AM - 2PM",
            'copyright' => '© 2024 Bright Smile Dental. All Rights Reserved.'
        ));
        
        // PAGE 2: SERVICES
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Services', 'page_slug' => 'services',
            'page_icon' => 'dashicons-heart', 'page_order' => 1, 'is_required' => 1,
            'page_description' => 'Complete dental services'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
            array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
            array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
        ), array('title' => 'Our Dental Services', 'subtitle' => 'Comprehensive dental care for your entire family'));
        
        self::insert_section($page_id, 'Services Categories', 'services_tabs', 1, array(
            array('name' => 'categories', 'label' => 'Service Categories', 'type' => 'repeater')
        ), array(
            'categories' => array(
                array('name' => 'General Dentistry', 'services' => array('Cleanings', 'Fillings', 'Extractions', 'Root Canals')),
                array('name' => 'Cosmetic Dentistry', 'services' => array('Whitening', 'Veneers', 'Bonding', 'Smile Makeovers')),
                array('name' => 'Restorative', 'services' => array('Crowns', 'Bridges', 'Implants', 'Dentures')),
                array('name' => 'Orthodontics', 'services' => array('Invisalign', 'Braces', 'Retainers'))
            )
        ));
        
        // PAGE 3: SMILE GALLERY
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Smile Gallery', 'page_slug' => 'gallery',
            'page_icon' => 'dashicons-format-gallery', 'page_order' => 2, 'is_required' => 0,
            'page_description' => 'Before & after transformations'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
            array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
            array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
        ), array('title' => 'Smile Gallery', 'subtitle' => 'Real results from real patients'));
        
        self::insert_section($page_id, 'Before After Gallery', 'before_after', 1, array(
            array('name' => 'gallery', 'label' => 'Gallery Items', 'type' => 'repeater')
        ), array());
        
        self::insert_section($page_id, 'Consultation CTA', 'cta_consultation', 2, array(
            array('name' => 'headline', 'label' => 'Headline', 'type' => 'text'),
            array('name' => 'btn_text', 'label' => 'Button Text', 'type' => 'text'),
            array('name' => 'btn_link', 'label' => 'Button Link', 'type' => 'url')
        ), array(
            'headline' => 'Ready for Your Smile Transformation?',
            'btn_text' => 'Book Free Consultation',
            'btn_link' => '/contact'
        ));
        
        // PAGE 4: OUR TEAM
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Our Team', 'page_slug' => 'team',
            'page_icon' => 'dashicons-groups', 'page_order' => 3, 'is_required' => 1,
            'page_description' => 'Meet our dental professionals'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
            array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
            array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
        ), array('title' => 'Meet Our Team', 'subtitle' => 'Experienced, caring dental professionals'));
        
        self::insert_section($page_id, 'Team Members', 'team_grid', 1, array(
            array('name' => 'team', 'label' => 'Team Members', 'type' => 'repeater')
        ), array());
        
        // PAGE 5: NEW PATIENTS
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'New Patients', 'page_slug' => 'new-patients',
            'page_icon' => 'dashicons-welcome-learn-more', 'page_order' => 4, 'is_required' => 0,
            'page_description' => 'Information for new patients'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
            array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
            array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
        ), array('title' => 'New Patient Information', 'subtitle' => 'Everything you need for your first visit'));
        
        self::insert_section($page_id, 'First Visit Info', 'first_visit', 1, array(
            array('name' => 'what_to_expect', 'label' => 'What to Expect', 'type' => 'editor'),
            array('name' => 'what_to_bring', 'label' => 'What to Bring', 'type' => 'editor'),
            array('name' => 'forms_text', 'label' => 'Forms Info', 'type' => 'textarea'),
            array('name' => 'forms_link', 'label' => 'Download Forms Link', 'type' => 'url')
        ), array());
        
        // PAGE 6: CONTACT
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Contact', 'page_slug' => 'contact',
            'page_icon' => 'dashicons-phone', 'page_order' => 5, 'is_required' => 1,
            'page_description' => 'Contact and appointment booking'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
            array('name' => 'title', 'label' => 'Page Title', 'type' => 'text'),
            array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
        ), array('title' => 'Book Your Appointment', 'subtitle' => 'Schedule your visit today'));
        
        self::insert_section($page_id, 'Contact Info', 'contact_dental', 1, array(
            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'email', 'label' => 'Email', 'type' => 'email'),
            array('name' => 'hours', 'label' => 'Office Hours', 'type' => 'textarea'),
            array('name' => 'emergency_text', 'label' => 'Emergency Text', 'type' => 'textarea')
        ), array(
            'address' => "456 Dental Plaza, Suite 200\nLos Angeles, CA 90001",
            'phone' => '+1 (555) 234-5678',
            'email' => 'smile@brightdental.com',
            'hours' => "Mon-Thu: 8AM - 6PM\nFri: 8AM - 4PM\nSat: 9AM - 2PM",
            'emergency_text' => 'Dental Emergency? Call us immediately!'
        ));
    }
    
    // =====================================================
    // TEMPLATE 3: EYE CARE - Vision-Centric Design
    // =====================================================
    private static function create_template_eye_care() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        
        $wpdb->insert($themes_table, array(
            'name' => 'Vision Eye Care Center',
            'description' => 'Professional eye care center with LASIK, optical shop, and comprehensive vision services.',
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
            'layout_style' => 'elegant',
            'header_style' => 'transparent',
            'hero_style' => 'eye-animation',
            'features' => json_encode(['LASIK Surgery', 'Comprehensive Eye Exams', 'Optical Boutique', 'Contact Lenses', 'Pediatric Eye Care', 'Glaucoma Treatment']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme_id = $wpdb->insert_id;
        
        // HOME PAGE
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Home', 'page_slug' => 'home',
            'page_icon' => 'dashicons-admin-home', 'page_order' => 0, 'is_required' => 1,
            'page_description' => 'Vision-focused homepage'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Header', 'header', 0, array(
            array('name' => 'logo_text', 'label' => 'Center Name', 'type' => 'text'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'cta_text', 'label' => 'CTA Button Text', 'type' => 'text'),
            array('name' => 'cta_link', 'label' => 'CTA Button Link', 'type' => 'url')
        ), array(
            'logo_text' => 'ClearView Eye Center',
            'phone' => '+1 (555) 345-6789',
            'cta_text' => 'Book Eye Exam',
            'cta_link' => '/contact'
        ));
        
        self::insert_section($page_id, 'Hero', 'hero_eye', 1, array(
            array('name' => 'headline', 'label' => 'Headline', 'type' => 'text'),
            array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea'),
            array('name' => 'cta1_text', 'label' => 'Primary CTA Text', 'type' => 'text'),
            array('name' => 'cta1_link', 'label' => 'Primary CTA Link', 'type' => 'url'),
            array('name' => 'cta2_text', 'label' => 'Secondary CTA Text', 'type' => 'text'),
            array('name' => 'cta2_link', 'label' => 'Secondary CTA Link', 'type' => 'url')
        ), array(
            'headline' => 'See Life More Clearly',
            'subheadline' => 'Advanced eye care and vision correction. From comprehensive exams to LASIK surgery, we help you see your best.',
            'cta1_text' => 'Free LASIK Consultation',
            'cta1_link' => '/lasik',
            'cta2_text' => 'Book Eye Exam',
            'cta2_link' => '/contact'
        ));
        
        self::insert_section($page_id, 'Services', 'services_eye', 2, array(
            array('name' => 'services', 'label' => 'Services', 'type' => 'repeater')
        ), array(
            'services' => array(
                array('icon' => '👁️', 'title' => 'Comprehensive Eye Exams', 'desc' => 'Full vision and eye health assessment', 'link' => '/services/exams'),
                array('icon' => '✨', 'title' => 'LASIK Surgery', 'desc' => 'Freedom from glasses and contacts', 'link' => '/services/lasik'),
                array('icon' => '🔬', 'title' => 'Cataract Treatment', 'desc' => 'Advanced lens replacement surgery', 'link' => '/services/cataract'),
                array('icon' => '👓', 'title' => 'Optical Boutique', 'desc' => 'Designer frames and lenses', 'link' => '/optical'),
                array('icon' => '📋', 'title' => 'Contact Lens Fitting', 'desc' => 'Expert fitting for all lens types', 'link' => '/services/contacts'),
                array('icon' => '💧', 'title' => 'Dry Eye Treatment', 'desc' => 'Relief for chronic dry eyes', 'link' => '/services/dry-eye')
            )
        ));
        
        self::insert_section($page_id, 'LASIK Promo', 'lasik_banner', 3, array(
            array('name' => 'headline', 'label' => 'Headline', 'type' => 'text'),
            array('name' => 'benefits', 'label' => 'Benefits', 'type' => 'textarea'),
            array('name' => 'cta_text', 'label' => 'CTA Text', 'type' => 'text'),
            array('name' => 'cta_link', 'label' => 'CTA Link', 'type' => 'url'),
            array('name' => 'promo_text', 'label' => 'Promo Text', 'type' => 'text')
        ), array(
            'headline' => 'Life-Changing LASIK',
            'benefits' => "✓ 15-Minute Procedure\n✓ 99% Success Rate\n✓ Most Patients See 20/20\n✓ Financing Available",
            'cta_text' => 'Get Free Assessment',
            'cta_link' => '/lasik',
            'promo_text' => '💰 Starting at $1,999 per eye'
        ));
        
        self::insert_section($page_id, 'Stats', 'stats', 4, array(
            array('name' => 'stat1_number', 'label' => 'Stat 1 Number', 'type' => 'text'),
            array('name' => 'stat1_label', 'label' => 'Stat 1 Label', 'type' => 'text'),
            array('name' => 'stat2_number', 'label' => 'Stat 2 Number', 'type' => 'text'),
            array('name' => 'stat2_label', 'label' => 'Stat 2 Label', 'type' => 'text'),
            array('name' => 'stat3_number', 'label' => 'Stat 3 Number', 'type' => 'text'),
            array('name' => 'stat3_label', 'label' => 'Stat 3 Label', 'type' => 'text')
        ), array(
            'stat1_number' => '50,000+', 'stat1_label' => 'LASIK Procedures',
            'stat2_number' => '99%', 'stat2_label' => 'Patient Satisfaction',
            'stat3_number' => '20+', 'stat3_label' => 'Years Experience'
        ));
        
        self::insert_section($page_id, 'Footer', 'footer', 5, array(
            array('name' => 'about_text', 'label' => 'About', 'type' => 'textarea'),
            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'email', 'label' => 'Email', 'type' => 'email'),
            array('name' => 'copyright', 'label' => 'Copyright', 'type' => 'text')
        ), array(
            'about_text' => 'ClearView Eye Center provides comprehensive eye care using the latest technology.',
            'address' => "789 Vision Way\nChicago, IL 60601",
            'phone' => '+1 (555) 345-6789',
            'email' => 'care@clearvieweye.com',
            'copyright' => '© 2024 ClearView Eye Center. All Rights Reserved.'
        ));
        
        // Additional pages: Services, LASIK, Optical, Doctors, Contact
        $pages = array(
            array('Services', 'services', 'dashicons-heart', 'All vision services'),
            array('LASIK', 'lasik', 'dashicons-visibility', 'LASIK information'),
            array('Optical Shop', 'optical', 'dashicons-cart', 'Frames and lenses'),
            array('Our Doctors', 'doctors', 'dashicons-groups', 'Eye care specialists'),
            array('Contact', 'contact', 'dashicons-phone', 'Appointments')
        );
        
        foreach ($pages as $i => $p) {
            $wpdb->insert($pages_table, array(
                'theme_id' => $theme_id, 'page_name' => $p[0], 'page_slug' => $p[1],
                'page_icon' => $p[2], 'page_order' => $i + 1, 'is_required' => ($p[1] == 'contact' ? 1 : 0),
                'page_description' => $p[3]
            ));
            $page_id = $wpdb->insert_id;
            
            self::insert_section($page_id, 'Page Header', 'page_header', 0, array(
                array('name' => 'title', 'label' => 'Title', 'type' => 'text'),
                array('name' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea')
            ), array('title' => $p[0], 'subtitle' => $p[3]));
        }
    }
    
    // =====================================================
    // TEMPLATE 4: PEDIATRIC - Child-Friendly Design
    // =====================================================
    private static function create_template_pediatric() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        
        $wpdb->insert($themes_table, array(
            'name' => 'Happy Kids Children\'s Hospital',
            'description' => 'Colorful, child-friendly pediatric hospital with play areas and family resources.',
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
            'layout_style' => 'playful',
            'header_style' => 'colorful',
            'hero_style' => 'animated-kids',
            'features' => json_encode(['Child-Friendly Rooms', 'Play Areas', 'Family Suites', 'Pediatric ER', 'Child Life Specialists', 'NICU']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme_id = $wpdb->insert_id;
        
        // HOME
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Home', 'page_slug' => 'home',
            'page_icon' => 'dashicons-admin-home', 'page_order' => 0, 'is_required' => 1,
            'page_description' => 'Fun, welcoming homepage for families'
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Header', 'header_playful', 0, array(
            array('name' => 'logo_text', 'label' => 'Hospital Name', 'type' => 'text'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'emergency', 'label' => 'Emergency Number', 'type' => 'text')
        ), array(
            'logo_text' => 'Happy Kids Hospital',
            'phone' => '+1 (555) 456-7890',
            'emergency' => '+1 (555) 456-9999'
        ));
        
        self::insert_section($page_id, 'Hero', 'hero_kids', 1, array(
            array('name' => 'headline', 'label' => 'Headline', 'type' => 'text'),
            array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea'),
            array('name' => 'cta1_text', 'label' => 'Button 1 Text', 'type' => 'text'),
            array('name' => 'cta1_link', 'label' => 'Button 1 Link', 'type' => 'url'),
            array('name' => 'cta2_text', 'label' => 'Button 2 Text', 'type' => 'text'),
            array('name' => 'cta2_link', 'label' => 'Button 2 Link', 'type' => 'url')
        ), array(
            'headline' => 'Where Little Heroes Get Big Care! 🦸',
            'subheadline' => 'Child-friendly healthcare with specialized pediatric experts. Making hospital visits less scary and more caring for your little ones.',
            'cta1_text' => '🎮 Virtual Tour',
            'cta1_link' => '/tour',
            'cta2_text' => '📅 Book Visit',
            'cta2_link' => '/contact'
        ));
        
        self::insert_section($page_id, 'Fun Features', 'features_playful', 2, array(
            array('name' => 'features', 'label' => 'Features', 'type' => 'repeater')
        ), array(
            'features' => array(
                array('icon' => '🎨', 'title' => 'Colorful Rooms', 'desc' => 'Bright, cheerful patient rooms'),
                array('icon' => '🎮', 'title' => 'Play Areas', 'desc' => 'Fun spaces for kids to play'),
                array('icon' => '👨‍👩‍👧', 'title' => 'Family Suites', 'desc' => 'Parents can stay overnight'),
                array('icon' => '🤡', 'title' => 'Clown Doctors', 'desc' => 'Laughter is the best medicine')
            )
        ));
        
        self::insert_section($page_id, 'Services', 'services_kids', 3, array(
            array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text'),
            array('name' => 'services', 'label' => 'Services', 'type' => 'repeater')
        ), array(
            'section_title' => 'How We Help Little Ones',
            'services' => array(
                array('icon' => '👶', 'title' => 'Newborn Care', 'link' => '/services/newborn'),
                array('icon' => '💉', 'title' => 'Vaccinations', 'link' => '/services/vaccines'),
                array('icon' => '🏥', 'title' => 'Pediatric Surgery', 'link' => '/services/surgery'),
                array('icon' => '🧠', 'title' => 'Development', 'link' => '/services/development')
            )
        ));
        
        self::insert_section($page_id, 'Stats', 'stats_kids', 4, array(
            array('name' => 'stat1', 'label' => 'Stat 1', 'type' => 'text'),
            array('name' => 'stat2', 'label' => 'Stat 2', 'type' => 'text'),
            array('name' => 'stat3', 'label' => 'Stat 3', 'type' => 'text')
        ), array(
            'stat1' => '🌟 100K+ Kids Treated',
            'stat2' => '👨‍⚕️ 150 Specialists',
            'stat3' => '🏥 24/7 Peds ER'
        ));
        
        self::insert_section($page_id, 'Footer', 'footer_kids', 5, array(
            array('name' => 'about', 'label' => 'About', 'type' => 'textarea'),
            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'copyright', 'label' => 'Copyright', 'type' => 'text')
        ), array(
            'about' => 'Happy Kids Hospital is dedicated to providing the highest quality pediatric care.',
            'address' => "321 Rainbow Lane\nBoston, MA 02101",
            'phone' => '+1 (555) 456-7890',
            'copyright' => '© 2024 Happy Kids Hospital 🎈'
        ));
        
        // Other pages
        $pages = array('Services', 'Our Doctors', 'For Families', 'Contact');
        foreach ($pages as $i => $name) {
            $wpdb->insert($pages_table, array(
                'theme_id' => $theme_id, 'page_name' => $name, 'page_slug' => sanitize_title($name),
                'page_icon' => 'dashicons-admin-page', 'page_order' => $i + 1, 'is_required' => ($name == 'Contact' ? 1 : 0)
            ));
        }
    }
    
    // =====================================================
    // TEMPLATE 5: CARDIOLOGY - Heart-Focused Design
    // =====================================================
    private static function create_template_cardiology() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        
        $wpdb->insert($themes_table, array(
            'name' => 'HeartCare Cardiology Center',
            'description' => 'Specialized cardiac center with emergency alerts and heart health resources.',
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
            'layout_style' => 'professional-urgent',
            'header_style' => 'emergency-ready',
            'hero_style' => 'heartbeat',
            'features' => json_encode(['24/7 Cardiac Emergency', 'Cath Lab', 'Heart Surgery', 'Cardiac Rehab', 'Pacemakers', 'Heart Failure Program']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme_id = $wpdb->insert_id;
        
        // HOME
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Home', 'page_slug' => 'home',
            'page_icon' => 'dashicons-admin-home', 'page_order' => 0, 'is_required' => 1
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Emergency Alert', 'emergency_alert', 0, array(
            array('name' => 'alert_text', 'label' => 'Alert Text', 'type' => 'text'),
            array('name' => 'phone', 'label' => 'Emergency Phone', 'type' => 'text')
        ), array(
            'alert_text' => '⚠️ Heart Attack Signs: Chest pain, shortness of breath, arm pain → Call 911 immediately',
            'phone' => '911'
        ));
        
        self::insert_section($page_id, 'Header', 'header', 1, array(
            array('name' => 'logo_text', 'label' => 'Center Name', 'type' => 'text'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'emergency_btn_text', 'label' => 'Emergency Button', 'type' => 'text'),
            array('name' => 'emergency_btn_link', 'label' => 'Emergency Link', 'type' => 'url')
        ), array(
            'logo_text' => 'HeartCare Center',
            'phone' => '+1 (555) 567-8901',
            'emergency_btn_text' => '🚨 Cardiac Emergency',
            'emergency_btn_link' => 'tel:911'
        ));
        
        self::insert_section($page_id, 'Hero', 'hero_heart', 2, array(
            array('name' => 'headline', 'label' => 'Headline', 'type' => 'text'),
            array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea'),
            array('name' => 'cta_text', 'label' => 'CTA Text', 'type' => 'text'),
            array('name' => 'cta_link', 'label' => 'CTA Link', 'type' => 'url')
        ), array(
            'headline' => 'Your Heart in Expert Hands',
            'subheadline' => 'Leading-edge cardiac care from prevention to intervention. Our team of specialists is dedicated to your heart health.',
            'cta_text' => 'Book Heart Screening',
            'cta_link' => '/contact'
        ));
        
        self::insert_section($page_id, 'Services', 'services_cardio', 3, array(
            array('name' => 'services', 'label' => 'Services', 'type' => 'repeater')
        ), array(
            'services' => array(
                array('icon' => '🩺', 'title' => 'Diagnostic Testing', 'link' => '/services/diagnostics'),
                array('icon' => '💓', 'title' => 'Cardiac Catheterization', 'link' => '/services/cath'),
                array('icon' => '🫀', 'title' => 'Heart Surgery', 'link' => '/services/surgery'),
                array('icon' => '⚡', 'title' => 'Electrophysiology', 'link' => '/services/ep'),
                array('icon' => '💪', 'title' => 'Cardiac Rehab', 'link' => '/services/rehab'),
                array('icon' => '❤️', 'title' => 'Heart Failure Program', 'link' => '/services/heart-failure')
            )
        ));
        
        self::insert_section($page_id, 'Stats', 'stats', 4, array(
            array('name' => 'stat1_number', 'label' => 'Stat 1 Number', 'type' => 'text'),
            array('name' => 'stat1_label', 'label' => 'Stat 1 Label', 'type' => 'text'),
            array('name' => 'stat2_number', 'label' => 'Stat 2 Number', 'type' => 'text'),
            array('name' => 'stat2_label', 'label' => 'Stat 2 Label', 'type' => 'text'),
            array('name' => 'stat3_number', 'label' => 'Stat 3 Number', 'type' => 'text'),
            array('name' => 'stat3_label', 'label' => 'Stat 3 Label', 'type' => 'text')
        ), array(
            'stat1_number' => '50,000+', 'stat1_label' => 'Procedures',
            'stat2_number' => '98%', 'stat2_label' => 'Success Rate',
            'stat3_number' => '25', 'stat3_label' => 'Cardiologists'
        ));
        
        self::insert_section($page_id, 'Footer', 'footer', 5, array(
            array('name' => 'about', 'label' => 'About', 'type' => 'textarea'),
            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'emergency', 'label' => 'Emergency', 'type' => 'text'),
            array('name' => 'copyright', 'label' => 'Copyright', 'type' => 'text')
        ), array(
            'about' => 'HeartCare Center is committed to exceptional cardiovascular care.',
            'address' => "555 Heart Health Blvd\nHouston, TX 77001",
            'phone' => '+1 (555) 567-8901',
            'emergency' => '911',
            'copyright' => '© 2024 HeartCare Center'
        ));
        
        // Other pages
        foreach (array('Services', 'Conditions', 'Our Doctors', 'Heart Health', 'Contact') as $i => $name) {
            $wpdb->insert($pages_table, array(
                'theme_id' => $theme_id, 'page_name' => $name, 'page_slug' => sanitize_title($name),
                'page_icon' => 'dashicons-admin-page', 'page_order' => $i + 1, 'is_required' => ($name == 'Contact' ? 1 : 0)
            ));
        }
    }
    
    // =====================================================
    // TEMPLATE 6: MENTAL HEALTH - Calming Design
    // =====================================================
    private static function create_template_mental_health() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        
        $wpdb->insert($themes_table, array(
            'name' => 'Serenity Mental Wellness',
            'description' => 'Calming, supportive mental health clinic with crisis resources and therapy services.',
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
            'layout_style' => 'calming',
            'header_style' => 'minimal',
            'hero_style' => 'peaceful',
            'features' => json_encode(['Individual Therapy', 'Group Sessions', 'Online Counseling', 'Crisis Support', 'Wellness Programs', 'Family Therapy']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme_id = $wpdb->insert_id;
        
        // HOME
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Home', 'page_slug' => 'home',
            'page_icon' => 'dashicons-admin-home', 'page_order' => 0, 'is_required' => 1
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Crisis Banner', 'crisis_banner', 0, array(
            array('name' => 'message', 'label' => 'Crisis Message', 'type' => 'text'),
            array('name' => 'hotline', 'label' => 'Crisis Hotline', 'type' => 'text'),
            array('name' => 'hotline_link', 'label' => 'Hotline Link', 'type' => 'url')
        ), array(
            'message' => '💚 In crisis? You matter. Help is available 24/7.',
            'hotline' => 'Call 988',
            'hotline_link' => 'tel:988'
        ));
        
        self::insert_section($page_id, 'Header', 'header', 1, array(
            array('name' => 'logo_text', 'label' => 'Center Name', 'type' => 'text'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'cta_text', 'label' => 'CTA Text', 'type' => 'text'),
            array('name' => 'cta_link', 'label' => 'CTA Link', 'type' => 'url')
        ), array(
            'logo_text' => 'Serenity Wellness',
            'phone' => '+1 (555) 678-9012',
            'cta_text' => 'Get Support',
            'cta_link' => '/contact'
        ));
        
        self::insert_section($page_id, 'Hero', 'hero_peaceful', 2, array(
            array('name' => 'headline', 'label' => 'Headline', 'type' => 'text'),
            array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea'),
            array('name' => 'cta_text', 'label' => 'CTA Text', 'type' => 'text'),
            array('name' => 'cta_link', 'label' => 'CTA Link', 'type' => 'url')
        ), array(
            'headline' => 'Your Journey to Wellness Begins Here',
            'subheadline' => 'Compassionate mental health care in a safe, supportive environment. You don\'t have to face this alone.',
            'cta_text' => 'Start Your Journey',
            'cta_link' => '/contact'
        ));
        
        self::insert_section($page_id, 'Services', 'services_mental', 3, array(
            array('name' => 'services', 'label' => 'Services', 'type' => 'repeater')
        ), array(
            'services' => array(
                array('icon' => '💬', 'title' => 'Individual Therapy', 'desc' => 'One-on-one sessions'),
                array('icon' => '👥', 'title' => 'Group Therapy', 'desc' => 'Supportive group sessions'),
                array('icon' => '👨‍👩‍👧', 'title' => 'Family Therapy', 'desc' => 'Heal together'),
                array('icon' => '💊', 'title' => 'Psychiatric Services', 'desc' => 'Medication management'),
                array('icon' => '🧘', 'title' => 'Wellness Programs', 'desc' => 'Mindfulness & self-care'),
                array('icon' => '🆘', 'title' => 'Crisis Intervention', 'desc' => '24/7 support')
            )
        ));
        
        self::insert_section($page_id, 'Approach', 'approach_section', 4, array(
            array('name' => 'title', 'label' => 'Section Title', 'type' => 'text'),
            array('name' => 'points', 'label' => 'Approach Points', 'type' => 'textarea')
        ), array(
            'title' => 'Our Approach',
            'points' => "✓ Judgment-free environment\n✓ Evidence-based treatments\n✓ Personalized care plans\n✓ Holistic wellness focus"
        ));
        
        self::insert_section($page_id, 'Footer', 'footer', 5, array(
            array('name' => 'about', 'label' => 'About', 'type' => 'textarea'),
            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'crisis_line', 'label' => 'Crisis Line', 'type' => 'text'),
            array('name' => 'copyright', 'label' => 'Copyright', 'type' => 'text')
        ), array(
            'about' => 'Serenity Wellness provides comprehensive mental health services with compassion.',
            'address' => "888 Peaceful Path\nSeattle, WA 98101",
            'phone' => '+1 (555) 678-9012',
            'crisis_line' => '988 (24/7 Crisis Line)',
            'copyright' => '© 2024 Serenity Wellness • You\'re Not Alone'
        ));
        
        // Other pages
        foreach (array('Services', 'What We Treat', 'Our Team', 'Resources', 'Contact') as $i => $name) {
            $wpdb->insert($pages_table, array(
                'theme_id' => $theme_id, 'page_name' => $name, 'page_slug' => sanitize_title($name),
                'page_icon' => 'dashicons-admin-page', 'page_order' => $i + 1, 'is_required' => ($name == 'Contact' ? 1 : 0)
            ));
        }
    }
    
    // =====================================================
    // TEMPLATE 7: ORTHOPEDIC - Active/Motion Design
    // =====================================================
    private static function create_template_orthopedic() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        
        $wpdb->insert($themes_table, array(
            'name' => 'SpineFirst Orthopedic Center',
            'description' => 'Professional orthopedic center for bone, joint, and spine care with rehab programs.',
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
            'layout_style' => 'dynamic',
            'header_style' => 'bold',
            'hero_style' => 'motion',
            'features' => json_encode(['Joint Replacement', 'Sports Medicine', 'Spine Surgery', 'Physical Therapy', 'Fracture Care', 'Robotic Surgery']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme_id = $wpdb->insert_id;
        
        // HOME
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Home', 'page_slug' => 'home',
            'page_icon' => 'dashicons-admin-home', 'page_order' => 0, 'is_required' => 1
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Header', 'header', 0, array(
            array('name' => 'logo_text', 'label' => 'Center Name', 'type' => 'text'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'cta_text', 'label' => 'CTA Text', 'type' => 'text'),
            array('name' => 'cta_link', 'label' => 'CTA Link', 'type' => 'url')
        ), array(
            'logo_text' => 'SpineFirst Orthopedics',
            'phone' => '+1 (555) 789-0123',
            'cta_text' => 'Book Consultation',
            'cta_link' => '/contact'
        ));
        
        self::insert_section($page_id, 'Hero', 'hero_motion', 1, array(
            array('name' => 'headline', 'label' => 'Headline', 'type' => 'text'),
            array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea'),
            array('name' => 'cta_text', 'label' => 'CTA Text', 'type' => 'text'),
            array('name' => 'cta_link', 'label' => 'CTA Link', 'type' => 'url')
        ), array(
            'headline' => 'Get Moving Again',
            'subheadline' => 'Expert orthopedic care for bones, joints, and spine. From sports injuries to joint replacement, we help you live pain-free.',
            'cta_text' => 'Schedule Consultation',
            'cta_link' => '/contact'
        ));
        
        self::insert_section($page_id, 'Specialties', 'specialties_grid', 2, array(
            array('name' => 'specialties', 'label' => 'Specialties', 'type' => 'repeater')
        ), array(
            'specialties' => array(
                array('icon' => '🦴', 'title' => 'Joint Replacement', 'link' => '/services/joint'),
                array('icon' => '🏃', 'title' => 'Sports Medicine', 'link' => '/services/sports'),
                array('icon' => '🧠', 'title' => 'Spine Surgery', 'link' => '/services/spine'),
                array('icon' => '✋', 'title' => 'Hand & Wrist', 'link' => '/services/hand'),
                array('icon' => '🦶', 'title' => 'Foot & Ankle', 'link' => '/services/foot'),
                array('icon' => '💪', 'title' => 'Physical Therapy', 'link' => '/services/pt')
            )
        ));
        
        self::insert_section($page_id, 'Stats', 'stats', 3, array(
            array('name' => 'stat1_number', 'label' => 'Stat 1 Number', 'type' => 'text'),
            array('name' => 'stat1_label', 'label' => 'Stat 1 Label', 'type' => 'text'),
            array('name' => 'stat2_number', 'label' => 'Stat 2 Number', 'type' => 'text'),
            array('name' => 'stat2_label', 'label' => 'Stat 2 Label', 'type' => 'text'),
            array('name' => 'stat3_number', 'label' => 'Stat 3 Number', 'type' => 'text'),
            array('name' => 'stat3_label', 'label' => 'Stat 3 Label', 'type' => 'text')
        ), array(
            'stat1_number' => '25,000+', 'stat1_label' => 'Surgeries',
            'stat2_number' => '99%', 'stat2_label' => 'Success Rate',
            'stat3_number' => '20', 'stat3_label' => 'Surgeons'
        ));
        
        self::insert_section($page_id, 'Rehab CTA', 'cta_rehab', 4, array(
            array('name' => 'text', 'label' => 'Text', 'type' => 'text'),
            array('name' => 'link_text', 'label' => 'Link Text', 'type' => 'text'),
            array('name' => 'link', 'label' => 'Link', 'type' => 'url')
        ), array(
            'text' => '💪 Full Rehabilitation Programs Available',
            'link_text' => 'Learn About Rehab →',
            'link' => '/services/rehab'
        ));
        
        self::insert_section($page_id, 'Footer', 'footer', 5, array(
            array('name' => 'about', 'label' => 'About', 'type' => 'textarea'),
            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'copyright', 'label' => 'Copyright', 'type' => 'text')
        ), array(
            'about' => 'SpineFirst specializes in comprehensive bone, joint, and spine care.',
            'address' => "999 Motion Avenue\nDenver, CO 80201",
            'phone' => '+1 (555) 789-0123',
            'copyright' => '© 2024 SpineFirst Orthopedics'
        ));
        
        // Other pages
        foreach (array('Services', 'Joint Replacement', 'Our Surgeons', 'Patient Info', 'Contact') as $i => $name) {
            $wpdb->insert($pages_table, array(
                'theme_id' => $theme_id, 'page_name' => $name, 'page_slug' => sanitize_title($name),
                'page_icon' => 'dashicons-admin-page', 'page_order' => $i + 1, 'is_required' => ($name == 'Contact' ? 1 : 0)
            ));
        }
    }
    
    // =====================================================
    // TEMPLATE 8: DIAGNOSTIC LAB - Tech/Data Design
    // =====================================================
    private static function create_template_diagnostic() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        
        $wpdb->insert($themes_table, array(
            'name' => 'PrecisionLab Diagnostics',
            'description' => 'Modern diagnostic lab with online reports, home collection, and health packages.',
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
            'layout_style' => 'tech-modern',
            'header_style' => 'utility',
            'hero_style' => 'data-centric',
            'features' => json_encode(['Online Reports', 'Home Collection', '24h Turnaround', 'Health Packages', 'Corporate Plans', 'NABL Certified']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme_id = $wpdb->insert_id;
        
        // HOME
        $wpdb->insert($pages_table, array(
            'theme_id' => $theme_id, 'page_name' => 'Home', 'page_slug' => 'home',
            'page_icon' => 'dashicons-admin-home', 'page_order' => 0, 'is_required' => 1
        ));
        $page_id = $wpdb->insert_id;
        
        self::insert_section($page_id, 'Header', 'header_utility', 0, array(
            array('name' => 'logo_text', 'label' => 'Lab Name', 'type' => 'text'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'report_btn_text', 'label' => 'Report Button', 'type' => 'text'),
            array('name' => 'report_btn_link', 'label' => 'Report Link', 'type' => 'url'),
            array('name' => 'book_btn_text', 'label' => 'Book Button', 'type' => 'text'),
            array('name' => 'book_btn_link', 'label' => 'Book Link', 'type' => 'url')
        ), array(
            'logo_text' => 'PrecisionLab',
            'phone' => '+1 (555) 890-1234',
            'report_btn_text' => '📱 Get Reports',
            'report_btn_link' => '/reports',
            'book_btn_text' => 'Book Test',
            'book_btn_link' => '/contact'
        ));
        
        self::insert_section($page_id, 'Hero', 'hero_lab', 1, array(
            array('name' => 'headline', 'label' => 'Headline', 'type' => 'text'),
            array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea'),
            array('name' => 'cta1_text', 'label' => 'CTA 1 Text', 'type' => 'text'),
            array('name' => 'cta1_link', 'label' => 'CTA 1 Link', 'type' => 'url'),
            array('name' => 'cta2_text', 'label' => 'CTA 2 Text', 'type' => 'text'),
            array('name' => 'cta2_link', 'label' => 'CTA 2 Link', 'type' => 'url')
        ), array(
            'headline' => 'Accurate Results, Better Health',
            'subheadline' => 'State-of-the-art diagnostic testing with quick turnaround. 500+ tests • Online reports • Home collection',
            'cta1_text' => 'Book a Test',
            'cta1_link' => '/contact',
            'cta2_text' => 'View Test Catalog',
            'cta2_link' => '/tests'
        ));
        
        self::insert_section($page_id, 'Features', 'features_lab', 2, array(
            array('name' => 'features', 'label' => 'Features', 'type' => 'repeater')
        ), array(
            'features' => array(
                array('icon' => '📱', 'title' => 'Online Reports', 'desc' => 'Access reports anytime'),
                array('icon' => '🏠', 'title' => 'Home Collection', 'desc' => 'We come to you'),
                array('icon' => '⏱️', 'title' => '24h Results', 'desc' => 'Fast turnaround'),
                array('icon' => '✅', 'title' => 'NABL Certified', 'desc' => 'Quality assured')
            )
        ));
        
        self::insert_section($page_id, 'Test Categories', 'test_categories', 3, array(
            array('name' => 'categories', 'label' => 'Categories', 'type' => 'repeater')
        ), array(
            'categories' => array(
                array('icon' => '🩸', 'name' => 'Blood Tests', 'link' => '/tests/blood'),
                array('icon' => '💉', 'name' => 'Diabetes', 'link' => '/tests/diabetes'),
                array('icon' => '🦋', 'name' => 'Thyroid', 'link' => '/tests/thyroid'),
                array('icon' => '❤️', 'name' => 'Cardiac', 'link' => '/tests/cardiac'),
                array('icon' => '🫁', 'name' => 'Liver/Kidney', 'link' => '/tests/organ'),
                array('icon' => '🧬', 'name' => 'Allergy', 'link' => '/tests/allergy')
            )
        ));
        
        self::insert_section($page_id, 'Health Packages', 'packages', 4, array(
            array('name' => 'packages', 'label' => 'Packages', 'type' => 'repeater')
        ), array(
            'packages' => array(
                array('name' => 'Basic Health', 'price' => '$99', 'tests' => '40+ Tests', 'popular' => false),
                array('name' => 'Comprehensive', 'price' => '$199', 'tests' => '70+ Tests', 'popular' => true),
                array('name' => 'Executive', 'price' => '$349', 'tests' => '100+ Tests', 'popular' => false)
            )
        ));
        
        self::insert_section($page_id, 'Stats', 'stats', 5, array(
            array('name' => 'stat1', 'label' => 'Stat 1', 'type' => 'text'),
            array('name' => 'stat2', 'label' => 'Stat 2', 'type' => 'text'),
            array('name' => 'stat3', 'label' => 'Stat 3', 'type' => 'text')
        ), array(
            'stat1' => '1M+ Tests',
            'stat2' => '99.9% Accuracy',
            'stat3' => '50+ Centers'
        ));
        
        self::insert_section($page_id, 'Footer', 'footer', 6, array(
            array('name' => 'about', 'label' => 'About', 'type' => 'textarea'),
            array('name' => 'address', 'label' => 'Address', 'type' => 'textarea'),
            array('name' => 'phone', 'label' => 'Phone', 'type' => 'text'),
            array('name' => 'email', 'label' => 'Email', 'type' => 'email'),
            array('name' => 'copyright', 'label' => 'Copyright', 'type' => 'text')
        ), array(
            'about' => 'PrecisionLab provides accurate, affordable diagnostic testing.',
            'address' => "111 Lab Lane\nPhiladelphia, PA 19101",
            'phone' => '+1 (555) 890-1234',
            'email' => 'tests@precisionlab.com',
            'copyright' => '© 2024 PrecisionLab • NABL Accredited'
        ));
        
        // Other pages
        foreach (array('Test Catalog', 'Health Packages', 'Home Collection', 'Corporate', 'Locations', 'Contact') as $i => $name) {
            $wpdb->insert($pages_table, array(
                'theme_id' => $theme_id, 'page_name' => $name, 'page_slug' => sanitize_title($name),
                'page_icon' => 'dashicons-admin-page', 'page_order' => $i + 1, 'is_required' => ($name == 'Contact' ? 1 : 0)
            ));
        }
    }
    
    // =====================================================
    // HELPER FUNCTION: Insert Section
    // =====================================================
    private static function insert_section($page_id, $name, $type, $order, $fields, $defaults, $buttons = array(), $links = array()) {
        global $wpdb;
        $sections_table = $wpdb->prefix . 'spfm_page_sections';
        
        $wpdb->insert($sections_table, array(
            'page_id' => $page_id,
            'section_name' => $name,
            'section_type' => $type,
            'section_order' => $order,
            'fields' => json_encode($fields),
            'default_values' => json_encode($defaults),
            'buttons' => json_encode($buttons),
            'links' => json_encode($links),
            'is_required' => 0,
            'status' => 1
        ));
    }
    
    // =====================================================
    // DROP TABLES
    // =====================================================
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
