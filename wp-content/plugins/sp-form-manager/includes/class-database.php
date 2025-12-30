<?php
/**
 * Database Handler Class
 * Website Template System
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
            category VARCHAR(100) DEFAULT 'business',
            preview_image VARCHAR(500) DEFAULT '',
            thumbnail VARCHAR(500) DEFAULT '',
            primary_color VARCHAR(20) DEFAULT '#667eea',
            secondary_color VARCHAR(20) DEFAULT '#764ba2',
            accent_color VARCHAR(20) DEFAULT '#28a745',
            background_color VARCHAR(20) DEFAULT '#ffffff',
            text_color VARCHAR(20) DEFAULT '#333333',
            header_bg_color VARCHAR(20) DEFAULT '#ffffff',
            footer_bg_color VARCHAR(20) DEFAULT '#1a1a2e',
            font_family VARCHAR(100) DEFAULT 'Poppins',
            heading_font VARCHAR(100) DEFAULT 'Poppins',
            demo_url VARCHAR(500) DEFAULT '',
            features TEXT,
            is_template TINYINT(1) DEFAULT 1,
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Theme Pages table - Each theme has multiple pages
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
            content_sections LONGTEXT,
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY theme_id (theme_id)
        ) $charset_collate;";
        
        // Theme Page Sections - Content fields for each page section
        $table_page_sections = $wpdb->prefix . 'spfm_page_sections';
        $sql_page_sections = "CREATE TABLE $table_page_sections (
            id INT(11) NOT NULL AUTO_INCREMENT,
            page_id INT(11) NOT NULL,
            section_name VARCHAR(200) NOT NULL,
            section_type VARCHAR(100) DEFAULT 'content',
            section_order INT(11) DEFAULT 0,
            fields LONGTEXT,
            is_required TINYINT(1) DEFAULT 0,
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY page_id (page_id)
        ) $charset_collate;";
        
        // Forms table - Admin creates forms with selected themes
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
        
        // Form submissions table - Customer selections and content
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
        
        // Create pre-built website templates
        self::create_website_templates();
        
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
    
    private static function create_website_templates() {
        global $wpdb;
        $themes_table = $wpdb->prefix . 'spfm_themes';
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        $sections_table = $wpdb->prefix . 'spfm_page_sections';
        
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM $themes_table WHERE is_template = 1");
        
        if ($exists) {
            return;
        }
        
        // ============ TEMPLATE 1: Corporate Business ============
        $wpdb->insert($themes_table, array(
            'name' => 'Corporate Business',
            'description' => 'Professional corporate website template perfect for businesses, agencies, and enterprises.',
            'category' => 'business',
            'primary_color' => '#2563eb',
            'secondary_color' => '#1e40af',
            'accent_color' => '#3b82f6',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'header_bg_color' => '#ffffff',
            'footer_bg_color' => '#1f2937',
            'font_family' => 'Inter',
            'heading_font' => 'Inter',
            'features' => json_encode(['Responsive Design', 'Contact Form', 'Service Showcase', 'Team Section', 'Testimonials']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme1_id = $wpdb->insert_id;
        
        // Template 1 Pages
        self::create_theme_pages($theme1_id, 'corporate');
        
        // ============ TEMPLATE 2: Creative Portfolio ============
        $wpdb->insert($themes_table, array(
            'name' => 'Creative Portfolio',
            'description' => 'Modern portfolio template for designers, photographers, and creative professionals.',
            'category' => 'portfolio',
            'primary_color' => '#8b5cf6',
            'secondary_color' => '#7c3aed',
            'accent_color' => '#a78bfa',
            'background_color' => '#faf5ff',
            'text_color' => '#1f2937',
            'header_bg_color' => '#ffffff',
            'footer_bg_color' => '#1e1b4b',
            'font_family' => 'Poppins',
            'heading_font' => 'Playfair Display',
            'features' => json_encode(['Portfolio Gallery', 'About Section', 'Skills Showcase', 'Contact Form', 'Social Links']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme2_id = $wpdb->insert_id;
        
        self::create_theme_pages($theme2_id, 'portfolio');
        
        // ============ TEMPLATE 3: Restaurant & Cafe ============
        $wpdb->insert($themes_table, array(
            'name' => 'Restaurant & Cafe',
            'description' => 'Elegant restaurant template with menu showcase, reservations, and gallery.',
            'category' => 'restaurant',
            'primary_color' => '#dc2626',
            'secondary_color' => '#991b1b',
            'accent_color' => '#f59e0b',
            'background_color' => '#fffbeb',
            'text_color' => '#1f2937',
            'header_bg_color' => '#1f2937',
            'footer_bg_color' => '#1f2937',
            'font_family' => 'Lato',
            'heading_font' => 'Playfair Display',
            'features' => json_encode(['Menu Display', 'Reservation Form', 'Photo Gallery', 'Location Map', 'Opening Hours']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme3_id = $wpdb->insert_id;
        
        self::create_theme_pages($theme3_id, 'restaurant');
        
        // ============ TEMPLATE 4: E-Commerce Shop ============
        $wpdb->insert($themes_table, array(
            'name' => 'E-Commerce Shop',
            'description' => 'Complete online store template with product showcase and shopping features.',
            'category' => 'ecommerce',
            'primary_color' => '#059669',
            'secondary_color' => '#047857',
            'accent_color' => '#10b981',
            'background_color' => '#f0fdf4',
            'text_color' => '#1f2937',
            'header_bg_color' => '#ffffff',
            'footer_bg_color' => '#064e3b',
            'font_family' => 'Nunito',
            'heading_font' => 'Nunito',
            'features' => json_encode(['Product Catalog', 'Category Pages', 'Shopping Cart', 'Checkout', 'Customer Reviews']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme4_id = $wpdb->insert_id;
        
        self::create_theme_pages($theme4_id, 'ecommerce');
        
        // ============ TEMPLATE 5: Medical & Healthcare ============
        $wpdb->insert($themes_table, array(
            'name' => 'Medical & Healthcare',
            'description' => 'Professional healthcare website for clinics, hospitals, and medical practices.',
            'category' => 'medical',
            'primary_color' => '#0891b2',
            'secondary_color' => '#0e7490',
            'accent_color' => '#06b6d4',
            'background_color' => '#ecfeff',
            'text_color' => '#1f2937',
            'header_bg_color' => '#ffffff',
            'footer_bg_color' => '#164e63',
            'font_family' => 'Open Sans',
            'heading_font' => 'Montserrat',
            'features' => json_encode(['Doctor Profiles', 'Appointment Booking', 'Services List', 'Patient Testimonials', 'Emergency Contact']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme5_id = $wpdb->insert_id;
        
        self::create_theme_pages($theme5_id, 'medical');
        
        // ============ TEMPLATE 6: Real Estate ============
        $wpdb->insert($themes_table, array(
            'name' => 'Real Estate',
            'description' => 'Property listing website for real estate agents and property companies.',
            'category' => 'realestate',
            'primary_color' => '#ea580c',
            'secondary_color' => '#c2410c',
            'accent_color' => '#f97316',
            'background_color' => '#fff7ed',
            'text_color' => '#1f2937',
            'header_bg_color' => '#ffffff',
            'footer_bg_color' => '#431407',
            'font_family' => 'Roboto',
            'heading_font' => 'Roboto',
            'features' => json_encode(['Property Listings', 'Search Filters', 'Agent Profiles', 'Virtual Tours', 'Contact Form']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme6_id = $wpdb->insert_id;
        
        self::create_theme_pages($theme6_id, 'realestate');
        
        // ============ TEMPLATE 7: Education & School ============
        $wpdb->insert($themes_table, array(
            'name' => 'Education & School',
            'description' => 'Educational institution website for schools, colleges, and online courses.',
            'category' => 'education',
            'primary_color' => '#4f46e5',
            'secondary_color' => '#4338ca',
            'accent_color' => '#6366f1',
            'background_color' => '#eef2ff',
            'text_color' => '#1f2937',
            'header_bg_color' => '#ffffff',
            'footer_bg_color' => '#1e1b4b',
            'font_family' => 'Source Sans Pro',
            'heading_font' => 'Merriweather',
            'features' => json_encode(['Course Catalog', 'Faculty Profiles', 'Admission Info', 'Events Calendar', 'Student Portal']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme7_id = $wpdb->insert_id;
        
        self::create_theme_pages($theme7_id, 'education');
        
        // ============ TEMPLATE 8: Fitness & Gym ============
        $wpdb->insert($themes_table, array(
            'name' => 'Fitness & Gym',
            'description' => 'Dynamic fitness website for gyms, personal trainers, and fitness centers.',
            'category' => 'fitness',
            'primary_color' => '#dc2626',
            'secondary_color' => '#b91c1c',
            'accent_color' => '#fbbf24',
            'background_color' => '#1f2937',
            'text_color' => '#f9fafb',
            'header_bg_color' => '#111827',
            'footer_bg_color' => '#111827',
            'font_family' => 'Oswald',
            'heading_font' => 'Oswald',
            'features' => json_encode(['Class Schedule', 'Trainer Profiles', 'Membership Plans', 'Workout Programs', 'Gallery']),
            'is_template' => 1,
            'status' => 1
        ));
        $theme8_id = $wpdb->insert_id;
        
        self::create_theme_pages($theme8_id, 'fitness');
    }
    
    private static function create_theme_pages($theme_id, $template_type) {
        global $wpdb;
        $pages_table = $wpdb->prefix . 'spfm_theme_pages';
        $sections_table = $wpdb->prefix . 'spfm_page_sections';
        
        // Common pages structure based on template type
        $pages = self::get_template_pages($template_type);
        
        foreach ($pages as $order => $page) {
            $wpdb->insert($pages_table, array(
                'theme_id' => $theme_id,
                'page_name' => $page['name'],
                'page_slug' => $page['slug'],
                'page_icon' => $page['icon'],
                'page_order' => $order,
                'is_required' => $page['required'] ? 1 : 0,
                'page_description' => $page['description'],
                'status' => 1
            ));
            $page_id = $wpdb->insert_id;
            
            // Create sections for each page
            foreach ($page['sections'] as $sec_order => $section) {
                $wpdb->insert($sections_table, array(
                    'page_id' => $page_id,
                    'section_name' => $section['name'],
                    'section_type' => $section['type'],
                    'section_order' => $sec_order,
                    'fields' => json_encode($section['fields']),
                    'is_required' => $section['required'] ? 1 : 0,
                    'status' => 1
                ));
            }
        }
    }
    
    private static function get_template_pages($type) {
        $common_home = array(
            'name' => 'Home',
            'slug' => 'home',
            'icon' => 'dashicons-admin-home',
            'required' => true,
            'description' => 'Main landing page of your website',
            'sections' => array(
                array(
                    'name' => 'Hero Section',
                    'type' => 'hero',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'headline', 'label' => 'Main Headline', 'type' => 'text', 'required' => true, 'placeholder' => 'Your powerful headline here'),
                        array('name' => 'subheadline', 'label' => 'Subheadline', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Supporting text that explains your value proposition'),
                        array('name' => 'cta_text', 'label' => 'Button Text', 'type' => 'text', 'required' => false, 'placeholder' => 'Get Started'),
                        array('name' => 'cta_link', 'label' => 'Button Link', 'type' => 'text', 'required' => false, 'placeholder' => '#contact'),
                        array('name' => 'hero_image', 'label' => 'Hero Image', 'type' => 'image', 'required' => false)
                    )
                ),
                array(
                    'name' => 'Features/Services Overview',
                    'type' => 'features',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'section_title', 'label' => 'Section Title', 'type' => 'text', 'placeholder' => 'Our Services'),
                        array('name' => 'section_description', 'label' => 'Section Description', 'type' => 'textarea'),
                        array('name' => 'features', 'label' => 'Features/Services', 'type' => 'repeater', 'fields' => array(
                            array('name' => 'icon', 'label' => 'Icon', 'type' => 'icon'),
                            array('name' => 'title', 'label' => 'Title', 'type' => 'text'),
                            array('name' => 'description', 'label' => 'Description', 'type' => 'textarea')
                        ))
                    )
                ),
                array(
                    'name' => 'Call to Action',
                    'type' => 'cta',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'cta_headline', 'label' => 'CTA Headline', 'type' => 'text', 'placeholder' => 'Ready to get started?'),
                        array('name' => 'cta_description', 'label' => 'CTA Description', 'type' => 'textarea'),
                        array('name' => 'cta_button_text', 'label' => 'Button Text', 'type' => 'text'),
                        array('name' => 'cta_button_link', 'label' => 'Button Link', 'type' => 'text')
                    )
                )
            )
        );
        
        $common_about = array(
            'name' => 'About',
            'slug' => 'about',
            'icon' => 'dashicons-info',
            'required' => true,
            'description' => 'Tell visitors about your company/brand',
            'sections' => array(
                array(
                    'name' => 'About Introduction',
                    'type' => 'content',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'about_title', 'label' => 'Page Title', 'type' => 'text', 'required' => true, 'placeholder' => 'About Us'),
                        array('name' => 'about_content', 'label' => 'About Content', 'type' => 'editor', 'required' => true),
                        array('name' => 'about_image', 'label' => 'About Image', 'type' => 'image')
                    )
                ),
                array(
                    'name' => 'Mission & Vision',
                    'type' => 'content',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'mission_title', 'label' => 'Mission Title', 'type' => 'text', 'placeholder' => 'Our Mission'),
                        array('name' => 'mission_content', 'label' => 'Mission Statement', 'type' => 'textarea'),
                        array('name' => 'vision_title', 'label' => 'Vision Title', 'type' => 'text', 'placeholder' => 'Our Vision'),
                        array('name' => 'vision_content', 'label' => 'Vision Statement', 'type' => 'textarea')
                    )
                ),
                array(
                    'name' => 'Team Members',
                    'type' => 'team',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'team_title', 'label' => 'Section Title', 'type' => 'text', 'placeholder' => 'Meet Our Team'),
                        array('name' => 'team_members', 'label' => 'Team Members', 'type' => 'repeater', 'fields' => array(
                            array('name' => 'photo', 'label' => 'Photo', 'type' => 'image'),
                            array('name' => 'name', 'label' => 'Name', 'type' => 'text'),
                            array('name' => 'position', 'label' => 'Position', 'type' => 'text'),
                            array('name' => 'bio', 'label' => 'Short Bio', 'type' => 'textarea')
                        ))
                    )
                )
            )
        );
        
        $common_contact = array(
            'name' => 'Contact',
            'slug' => 'contact',
            'icon' => 'dashicons-email',
            'required' => true,
            'description' => 'Contact information and form',
            'sections' => array(
                array(
                    'name' => 'Contact Information',
                    'type' => 'contact',
                    'required' => true,
                    'fields' => array(
                        array('name' => 'contact_title', 'label' => 'Page Title', 'type' => 'text', 'placeholder' => 'Contact Us'),
                        array('name' => 'contact_description', 'label' => 'Description', 'type' => 'textarea'),
                        array('name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'placeholder' => '123 Business Street, City, Country'),
                        array('name' => 'phone', 'label' => 'Phone Number', 'type' => 'text', 'placeholder' => '+1 234 567 8900'),
                        array('name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'placeholder' => 'contact@example.com'),
                        array('name' => 'working_hours', 'label' => 'Working Hours', 'type' => 'text', 'placeholder' => 'Mon-Fri: 9AM - 6PM')
                    )
                ),
                array(
                    'name' => 'Social Media',
                    'type' => 'social',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'facebook', 'label' => 'Facebook URL', 'type' => 'url'),
                        array('name' => 'twitter', 'label' => 'Twitter/X URL', 'type' => 'url'),
                        array('name' => 'instagram', 'label' => 'Instagram URL', 'type' => 'url'),
                        array('name' => 'linkedin', 'label' => 'LinkedIn URL', 'type' => 'url'),
                        array('name' => 'youtube', 'label' => 'YouTube URL', 'type' => 'url')
                    )
                ),
                array(
                    'name' => 'Map Location',
                    'type' => 'map',
                    'required' => false,
                    'fields' => array(
                        array('name' => 'google_map_embed', 'label' => 'Google Map Embed Code', 'type' => 'textarea', 'placeholder' => 'Paste your Google Maps embed code here')
                    )
                )
            )
        );
        
        // Type-specific pages
        switch ($type) {
            case 'corporate':
                return array(
                    $common_home,
                    $common_about,
                    array(
                        'name' => 'Services',
                        'slug' => 'services',
                        'icon' => 'dashicons-screenoptions',
                        'required' => true,
                        'description' => 'Showcase your services',
                        'sections' => array(
                            array(
                                'name' => 'Services List',
                                'type' => 'services',
                                'required' => true,
                                'fields' => array(
                                    array('name' => 'services_title', 'label' => 'Page Title', 'type' => 'text', 'placeholder' => 'Our Services'),
                                    array('name' => 'services_intro', 'label' => 'Introduction', 'type' => 'textarea'),
                                    array('name' => 'services', 'label' => 'Services', 'type' => 'repeater', 'fields' => array(
                                        array('name' => 'icon', 'label' => 'Icon', 'type' => 'icon'),
                                        array('name' => 'title', 'label' => 'Service Title', 'type' => 'text'),
                                        array('name' => 'description', 'label' => 'Description', 'type' => 'editor'),
                                        array('name' => 'image', 'label' => 'Image', 'type' => 'image')
                                    ))
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'Testimonials',
                        'slug' => 'testimonials',
                        'icon' => 'dashicons-format-quote',
                        'required' => false,
                        'description' => 'Customer testimonials',
                        'sections' => array(
                            array(
                                'name' => 'Client Testimonials',
                                'type' => 'testimonials',
                                'required' => false,
                                'fields' => array(
                                    array('name' => 'testimonials_title', 'label' => 'Section Title', 'type' => 'text', 'placeholder' => 'What Our Clients Say'),
                                    array('name' => 'testimonials', 'label' => 'Testimonials', 'type' => 'repeater', 'fields' => array(
                                        array('name' => 'photo', 'label' => 'Client Photo', 'type' => 'image'),
                                        array('name' => 'name', 'label' => 'Client Name', 'type' => 'text'),
                                        array('name' => 'company', 'label' => 'Company', 'type' => 'text'),
                                        array('name' => 'quote', 'label' => 'Testimonial', 'type' => 'textarea'),
                                        array('name' => 'rating', 'label' => 'Rating (1-5)', 'type' => 'number')
                                    ))
                                )
                            )
                        )
                    ),
                    $common_contact
                );
                
            case 'portfolio':
                return array(
                    $common_home,
                    $common_about,
                    array(
                        'name' => 'Portfolio',
                        'slug' => 'portfolio',
                        'icon' => 'dashicons-portfolio',
                        'required' => true,
                        'description' => 'Showcase your work',
                        'sections' => array(
                            array(
                                'name' => 'Portfolio Gallery',
                                'type' => 'gallery',
                                'required' => true,
                                'fields' => array(
                                    array('name' => 'portfolio_title', 'label' => 'Page Title', 'type' => 'text', 'placeholder' => 'My Work'),
                                    array('name' => 'portfolio_intro', 'label' => 'Introduction', 'type' => 'textarea'),
                                    array('name' => 'projects', 'label' => 'Projects', 'type' => 'repeater', 'fields' => array(
                                        array('name' => 'image', 'label' => 'Project Image', 'type' => 'image'),
                                        array('name' => 'title', 'label' => 'Project Title', 'type' => 'text'),
                                        array('name' => 'category', 'label' => 'Category', 'type' => 'text'),
                                        array('name' => 'description', 'label' => 'Description', 'type' => 'textarea'),
                                        array('name' => 'link', 'label' => 'Project Link', 'type' => 'url')
                                    ))
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'Skills',
                        'slug' => 'skills',
                        'icon' => 'dashicons-star-filled',
                        'required' => false,
                        'description' => 'Your skills and expertise',
                        'sections' => array(
                            array(
                                'name' => 'Skills List',
                                'type' => 'skills',
                                'required' => false,
                                'fields' => array(
                                    array('name' => 'skills_title', 'label' => 'Section Title', 'type' => 'text', 'placeholder' => 'My Skills'),
                                    array('name' => 'skills', 'label' => 'Skills', 'type' => 'repeater', 'fields' => array(
                                        array('name' => 'skill_name', 'label' => 'Skill Name', 'type' => 'text'),
                                        array('name' => 'skill_level', 'label' => 'Proficiency (%)', 'type' => 'number')
                                    ))
                                )
                            )
                        )
                    ),
                    $common_contact
                );
                
            case 'restaurant':
                return array(
                    $common_home,
                    $common_about,
                    array(
                        'name' => 'Menu',
                        'slug' => 'menu',
                        'icon' => 'dashicons-food',
                        'required' => true,
                        'description' => 'Your food menu',
                        'sections' => array(
                            array(
                                'name' => 'Menu Categories',
                                'type' => 'menu',
                                'required' => true,
                                'fields' => array(
                                    array('name' => 'menu_title', 'label' => 'Page Title', 'type' => 'text', 'placeholder' => 'Our Menu'),
                                    array('name' => 'menu_intro', 'label' => 'Introduction', 'type' => 'textarea'),
                                    array('name' => 'categories', 'label' => 'Menu Categories', 'type' => 'repeater', 'fields' => array(
                                        array('name' => 'category_name', 'label' => 'Category Name', 'type' => 'text'),
                                        array('name' => 'items', 'label' => 'Menu Items', 'type' => 'repeater', 'fields' => array(
                                            array('name' => 'name', 'label' => 'Item Name', 'type' => 'text'),
                                            array('name' => 'description', 'label' => 'Description', 'type' => 'textarea'),
                                            array('name' => 'price', 'label' => 'Price', 'type' => 'text'),
                                            array('name' => 'image', 'label' => 'Image', 'type' => 'image')
                                        ))
                                    ))
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'Gallery',
                        'slug' => 'gallery',
                        'icon' => 'dashicons-format-gallery',
                        'required' => false,
                        'description' => 'Photo gallery',
                        'sections' => array(
                            array(
                                'name' => 'Photo Gallery',
                                'type' => 'gallery',
                                'required' => false,
                                'fields' => array(
                                    array('name' => 'gallery_title', 'label' => 'Section Title', 'type' => 'text', 'placeholder' => 'Gallery'),
                                    array('name' => 'images', 'label' => 'Images', 'type' => 'gallery')
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'Reservations',
                        'slug' => 'reservations',
                        'icon' => 'dashicons-calendar-alt',
                        'required' => false,
                        'description' => 'Table reservations',
                        'sections' => array(
                            array(
                                'name' => 'Reservation Info',
                                'type' => 'reservations',
                                'required' => false,
                                'fields' => array(
                                    array('name' => 'reservations_title', 'label' => 'Page Title', 'type' => 'text', 'placeholder' => 'Make a Reservation'),
                                    array('name' => 'reservations_description', 'label' => 'Description', 'type' => 'textarea'),
                                    array('name' => 'reservation_phone', 'label' => 'Reservation Phone', 'type' => 'text'),
                                    array('name' => 'reservation_email', 'label' => 'Reservation Email', 'type' => 'email')
                                )
                            )
                        )
                    ),
                    $common_contact
                );
                
            case 'ecommerce':
                return array(
                    $common_home,
                    $common_about,
                    array(
                        'name' => 'Products',
                        'slug' => 'products',
                        'icon' => 'dashicons-products',
                        'required' => true,
                        'description' => 'Product catalog',
                        'sections' => array(
                            array(
                                'name' => 'Product Catalog',
                                'type' => 'products',
                                'required' => true,
                                'fields' => array(
                                    array('name' => 'shop_title', 'label' => 'Page Title', 'type' => 'text', 'placeholder' => 'Shop'),
                                    array('name' => 'shop_description', 'label' => 'Description', 'type' => 'textarea'),
                                    array('name' => 'categories', 'label' => 'Product Categories', 'type' => 'repeater', 'fields' => array(
                                        array('name' => 'category_name', 'label' => 'Category Name', 'type' => 'text'),
                                        array('name' => 'category_image', 'label' => 'Category Image', 'type' => 'image'),
                                        array('name' => 'products', 'label' => 'Products', 'type' => 'repeater', 'fields' => array(
                                            array('name' => 'name', 'label' => 'Product Name', 'type' => 'text'),
                                            array('name' => 'image', 'label' => 'Image', 'type' => 'image'),
                                            array('name' => 'price', 'label' => 'Price', 'type' => 'text'),
                                            array('name' => 'sale_price', 'label' => 'Sale Price', 'type' => 'text'),
                                            array('name' => 'description', 'label' => 'Description', 'type' => 'textarea')
                                        ))
                                    ))
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'Shipping & Returns',
                        'slug' => 'shipping',
                        'icon' => 'dashicons-car',
                        'required' => false,
                        'description' => 'Shipping and return policies',
                        'sections' => array(
                            array(
                                'name' => 'Policies',
                                'type' => 'content',
                                'required' => false,
                                'fields' => array(
                                    array('name' => 'shipping_title', 'label' => 'Shipping Policy Title', 'type' => 'text'),
                                    array('name' => 'shipping_content', 'label' => 'Shipping Policy', 'type' => 'editor'),
                                    array('name' => 'returns_title', 'label' => 'Returns Policy Title', 'type' => 'text'),
                                    array('name' => 'returns_content', 'label' => 'Returns Policy', 'type' => 'editor')
                                )
                            )
                        )
                    ),
                    $common_contact
                );
                
            case 'medical':
                return array(
                    $common_home,
                    $common_about,
                    array(
                        'name' => 'Services',
                        'slug' => 'services',
                        'icon' => 'dashicons-heart',
                        'required' => true,
                        'description' => 'Medical services',
                        'sections' => array(
                            array(
                                'name' => 'Medical Services',
                                'type' => 'services',
                                'required' => true,
                                'fields' => array(
                                    array('name' => 'services_title', 'label' => 'Page Title', 'type' => 'text', 'placeholder' => 'Our Services'),
                                    array('name' => 'services_intro', 'label' => 'Introduction', 'type' => 'textarea'),
                                    array('name' => 'services', 'label' => 'Services', 'type' => 'repeater', 'fields' => array(
                                        array('name' => 'icon', 'label' => 'Icon', 'type' => 'icon'),
                                        array('name' => 'title', 'label' => 'Service Name', 'type' => 'text'),
                                        array('name' => 'description', 'label' => 'Description', 'type' => 'editor')
                                    ))
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'Doctors',
                        'slug' => 'doctors',
                        'icon' => 'dashicons-groups',
                        'required' => true,
                        'description' => 'Doctor profiles',
                        'sections' => array(
                            array(
                                'name' => 'Our Doctors',
                                'type' => 'team',
                                'required' => true,
                                'fields' => array(
                                    array('name' => 'doctors_title', 'label' => 'Page Title', 'type' => 'text', 'placeholder' => 'Our Doctors'),
                                    array('name' => 'doctors', 'label' => 'Doctors', 'type' => 'repeater', 'fields' => array(
                                        array('name' => 'photo', 'label' => 'Photo', 'type' => 'image'),
                                        array('name' => 'name', 'label' => 'Name', 'type' => 'text'),
                                        array('name' => 'specialization', 'label' => 'Specialization', 'type' => 'text'),
                                        array('name' => 'qualifications', 'label' => 'Qualifications', 'type' => 'text'),
                                        array('name' => 'bio', 'label' => 'Biography', 'type' => 'textarea')
                                    ))
                                )
                            )
                        )
                    ),
                    array(
                        'name' => 'Appointments',
                        'slug' => 'appointments',
                        'icon' => 'dashicons-calendar',
                        'required' => false,
                        'description' => 'Appointment booking',
                        'sections' => array(
                            array(
                                'name' => 'Booking Info',
                                'type' => 'booking',
                                'required' => false,
                                'fields' => array(
                                    array('name' => 'booking_title', 'label' => 'Page Title', 'type' => 'text', 'placeholder' => 'Book an Appointment'),
                                    array('name' => 'booking_description', 'label' => 'Description', 'type' => 'textarea'),
                                    array('name' => 'booking_phone', 'label' => 'Appointment Phone', 'type' => 'text'),
                                    array('name' => 'emergency_number', 'label' => 'Emergency Number', 'type' => 'text')
                                )
                            )
                        )
                    ),
                    $common_contact
                );
                
            default:
                return array($common_home, $common_about, $common_contact);
        }
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
