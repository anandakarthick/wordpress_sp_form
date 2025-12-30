<?php
/**
 * Database Handler Class
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
        
        // Themes table (now with template support)
        $table_themes = $wpdb->prefix . 'spfm_themes';
        $sql_themes = "CREATE TABLE $table_themes (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(200) NOT NULL,
            description TEXT,
            template_type VARCHAR(50) DEFAULT 'custom',
            preview_image VARCHAR(500) DEFAULT '',
            primary_color VARCHAR(20) DEFAULT '#007bff',
            secondary_color VARCHAR(20) DEFAULT '#6c757d',
            background_color VARCHAR(20) DEFAULT '#ffffff',
            text_color VARCHAR(20) DEFAULT '#333333',
            accent_color VARCHAR(20) DEFAULT '#28a745',
            header_bg_color VARCHAR(20) DEFAULT '#667eea',
            button_style VARCHAR(50) DEFAULT 'rounded',
            font_family VARCHAR(100) DEFAULT 'Arial, sans-serif',
            header_font VARCHAR(100) DEFAULT 'Arial, sans-serif',
            custom_css TEXT,
            layout_style VARCHAR(50) DEFAULT 'default',
            is_template TINYINT(1) DEFAULT 0,
            status TINYINT(1) DEFAULT 1,
            created_by INT(11) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Forms table
        $table_forms = $wpdb->prefix . 'spfm_forms';
        $sql_forms = "CREATE TABLE $table_forms (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(200) NOT NULL,
            description TEXT,
            theme_id INT(11) DEFAULT NULL,
            header_text VARCHAR(500) DEFAULT '',
            footer_text VARCHAR(500) DEFAULT '',
            submit_button_text VARCHAR(100) DEFAULT 'Submit',
            success_message TEXT,
            logo_url VARCHAR(500) DEFAULT '',
            banner_url VARCHAR(500) DEFAULT '',
            allow_customization TINYINT(1) DEFAULT 1,
            notify_admin TINYINT(1) DEFAULT 1,
            status TINYINT(1) DEFAULT 1,
            created_by INT(11) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Form fields table (custom fields)
        $table_form_fields = $wpdb->prefix . 'spfm_form_fields';
        $sql_form_fields = "CREATE TABLE $table_form_fields (
            id INT(11) NOT NULL AUTO_INCREMENT,
            form_id INT(11) NOT NULL,
            field_label VARCHAR(200) NOT NULL,
            field_name VARCHAR(100) NOT NULL,
            field_type VARCHAR(50) NOT NULL DEFAULT 'text',
            field_options TEXT,
            placeholder VARCHAR(200) DEFAULT '',
            default_value TEXT,
            is_required TINYINT(1) DEFAULT 0,
            field_order INT(11) DEFAULT 0,
            validation_rules TEXT,
            css_class VARCHAR(200) DEFAULT '',
            status TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY form_id (form_id)
        ) $charset_collate;";
        
        // Form shares table
        $table_shares = $wpdb->prefix . 'spfm_form_shares';
        $sql_shares = "CREATE TABLE $table_shares (
            id INT(11) NOT NULL AUTO_INCREMENT,
            form_id INT(11) NOT NULL,
            customer_id INT(11) DEFAULT NULL,
            token VARCHAR(64) NOT NULL,
            customizations LONGTEXT,
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
            submission_data LONGTEXT NOT NULL,
            uploaded_files LONGTEXT,
            customizations LONGTEXT,
            ip_address VARCHAR(50) DEFAULT '',
            user_agent TEXT,
            status VARCHAR(50) DEFAULT 'pending',
            admin_notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY form_id (form_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($sql_users);
        dbDelta($sql_customers);
        dbDelta($sql_themes);
        dbDelta($sql_forms);
        dbDelta($sql_form_fields);
        dbDelta($sql_shares);
        dbDelta($sql_submissions);
        
        // Create default admin user
        self::create_default_admin();
        
        // Create pre-built theme templates
        self::create_theme_templates();
        
        // Set flush rules flag
        update_option('spfm_flush_rules', true);
        
        // Update version
        update_option('spfm_db_version', '1.1.0');
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
    
    private static function create_theme_templates() {
        global $wpdb;
        $table = $wpdb->prefix . 'spfm_themes';
        
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE is_template = 1");
        
        if (!$exists) {
            // Template 1: Modern Blue
            $wpdb->insert($table, array(
                'name' => 'Modern Blue',
                'description' => 'A clean, modern template with blue accents. Perfect for professional forms.',
                'template_type' => 'modern',
                'primary_color' => '#007bff',
                'secondary_color' => '#6c757d',
                'background_color' => '#f8f9fa',
                'text_color' => '#212529',
                'accent_color' => '#17a2b8',
                'header_bg_color' => '#007bff',
                'button_style' => 'rounded',
                'font_family' => 'Segoe UI, sans-serif',
                'header_font' => 'Segoe UI, sans-serif',
                'layout_style' => 'card',
                'is_template' => 1,
                'status' => 1
            ));
            
            // Template 2: Elegant Purple
            $wpdb->insert($table, array(
                'name' => 'Elegant Purple',
                'description' => 'An elegant gradient template with purple tones. Great for creative forms.',
                'template_type' => 'gradient',
                'primary_color' => '#667eea',
                'secondary_color' => '#764ba2',
                'background_color' => '#ffffff',
                'text_color' => '#333333',
                'accent_color' => '#f093fb',
                'header_bg_color' => '#667eea',
                'button_style' => 'gradient',
                'font_family' => 'Poppins, sans-serif',
                'header_font' => 'Poppins, sans-serif',
                'layout_style' => 'gradient-header',
                'is_template' => 1,
                'status' => 1
            ));
            
            // Template 3: Minimal White
            $wpdb->insert($table, array(
                'name' => 'Minimal White',
                'description' => 'A minimalist white template. Clean and distraction-free.',
                'template_type' => 'minimal',
                'primary_color' => '#000000',
                'secondary_color' => '#666666',
                'background_color' => '#ffffff',
                'text_color' => '#000000',
                'accent_color' => '#333333',
                'header_bg_color' => '#ffffff',
                'button_style' => 'outline',
                'font_family' => 'Helvetica, sans-serif',
                'header_font' => 'Helvetica, sans-serif',
                'layout_style' => 'minimal',
                'is_template' => 1,
                'status' => 1
            ));
            
            // Template 4: Corporate Green
            $wpdb->insert($table, array(
                'name' => 'Corporate Green',
                'description' => 'Professional corporate template with green accents. Ideal for business.',
                'template_type' => 'corporate',
                'primary_color' => '#28a745',
                'secondary_color' => '#20c997',
                'background_color' => '#f5f5f5',
                'text_color' => '#2d3436',
                'accent_color' => '#00b894',
                'header_bg_color' => '#155724',
                'button_style' => 'rounded',
                'font_family' => 'Roboto, sans-serif',
                'header_font' => 'Roboto, sans-serif',
                'layout_style' => 'boxed',
                'is_template' => 1,
                'status' => 1
            ));
            
            // Template 5: Warm Sunset
            $wpdb->insert($table, array(
                'name' => 'Warm Sunset',
                'description' => 'Warm orange and red gradient template. Energetic and inviting.',
                'template_type' => 'gradient',
                'primary_color' => '#ff6b6b',
                'secondary_color' => '#feca57',
                'background_color' => '#fff9f0',
                'text_color' => '#2d3436',
                'accent_color' => '#ff9ff3',
                'header_bg_color' => '#ff6b6b',
                'button_style' => 'gradient',
                'font_family' => 'Nunito, sans-serif',
                'header_font' => 'Nunito, sans-serif',
                'layout_style' => 'gradient-header',
                'is_template' => 1,
                'status' => 1
            ));
            
            // Template 6: Ocean Blue
            $wpdb->insert($table, array(
                'name' => 'Ocean Blue',
                'description' => 'Deep ocean blue theme. Calm and trustworthy.',
                'template_type' => 'modern',
                'primary_color' => '#0984e3',
                'secondary_color' => '#74b9ff',
                'background_color' => '#dfe6e9',
                'text_color' => '#2d3436',
                'accent_color' => '#00cec9',
                'header_bg_color' => '#0984e3',
                'button_style' => 'rounded',
                'font_family' => 'Open Sans, sans-serif',
                'header_font' => 'Open Sans, sans-serif',
                'layout_style' => 'card',
                'is_template' => 1,
                'status' => 1
            ));
            
            // Template 7: Dark Mode
            $wpdb->insert($table, array(
                'name' => 'Dark Mode',
                'description' => 'Modern dark theme. Easy on the eyes.',
                'template_type' => 'dark',
                'primary_color' => '#6c5ce7',
                'secondary_color' => '#a29bfe',
                'background_color' => '#1a1a2e',
                'text_color' => '#eaeaea',
                'accent_color' => '#00d2d3',
                'header_bg_color' => '#16213e',
                'button_style' => 'rounded',
                'font_family' => 'Inter, sans-serif',
                'header_font' => 'Inter, sans-serif',
                'layout_style' => 'dark',
                'is_template' => 1,
                'status' => 1
            ));
            
            // Template 8: Fresh Mint
            $wpdb->insert($table, array(
                'name' => 'Fresh Mint',
                'description' => 'Fresh and clean mint green theme. Natural and refreshing.',
                'template_type' => 'modern',
                'primary_color' => '#00b894',
                'secondary_color' => '#55efc4',
                'background_color' => '#f0fff4',
                'text_color' => '#2d3436',
                'accent_color' => '#81ecec',
                'header_bg_color' => '#00b894',
                'button_style' => 'rounded',
                'font_family' => 'Lato, sans-serif',
                'header_font' => 'Lato, sans-serif',
                'layout_style' => 'card',
                'is_template' => 1,
                'status' => 1
            ));
        }
    }
    
    public static function drop_tables() {
        global $wpdb;
        
        $tables = array(
            $wpdb->prefix . 'spfm_form_submissions',
            $wpdb->prefix . 'spfm_form_shares',
            $wpdb->prefix . 'spfm_form_fields',
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
