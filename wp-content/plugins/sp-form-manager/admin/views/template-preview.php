<?php
/**
 * Template Preview Page
 * Full-page preview of hospital website template
 * Version 3.0 - Standalone Preview
 */

if (!defined('ABSPATH')) {
    exit;
}

// $preview_theme is passed from sp-form-manager.php
$theme = $preview_theme;
$category = $theme->category;

// Get default content from sections
$defaults = array();
if (!empty($theme->pages)) {
    foreach ($theme->pages as $page) {
        if (!empty($page->sections)) {
            foreach ($page->sections as $section) {
                if (!empty($section->default_values)) {
                    foreach ($section->default_values as $key => $value) {
                        $defaults[$key] = $value;
                    }
                }
            }
        }
    }
}

// Helper function to get value
if (!function_exists('get_preview_value')) {
    function get_preview_value($defaults, $key, $fallback = '') {
        return isset($defaults[$key]) ? $defaults[$key] : $fallback;
    }
}

$primary = $theme->primary_color;
$secondary = $theme->secondary_color;
$accent = $theme->accent_color ?: $primary;
$background = $theme->background_color;
$textColor = $theme->text_color;
$headerBg = $theme->header_bg_color ?: '#ffffff';
$footerBg = $theme->footer_bg_color ?: '#0f172a';
$fontFamily = $theme->font_family ?: 'Inter';
$headingFont = $theme->heading_font ?: 'Poppins';

// Category icons and data
$categoryData = array(
    'hospital' => array('icon' => '🏥', 'name' => 'General Hospital'),
    'dental' => array('icon' => '🦷', 'name' => 'Dental Clinic'),
    'eye_care' => array('icon' => '👁️', 'name' => 'Eye Care Center'),
    'pediatric' => array('icon' => '👶', 'name' => 'Pediatric Hospital'),
    'cardiology' => array('icon' => '❤️', 'name' => 'Cardiology Center'),
    'mental_health' => array('icon' => '🧠', 'name' => 'Mental Health'),
    'orthopedic' => array('icon' => '🦴', 'name' => 'Orthopedic Center'),
    'diagnostic' => array('icon' => '🔬', 'name' => 'Diagnostic Lab')
);
$catInfo = $categoryData[$category] ?? array('icon' => '🏥', 'name' => 'Hospital');

// Admin URLs
$back_url = admin_url('admin.php?page=spfm-themes');
$edit_url = admin_url('admin.php?page=spfm-themes&action=edit&id=' . $theme->id);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($theme->name); ?> - Template Preview</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            width: 100%;
            min-height: 100%;
            background: <?php echo esc_attr($background); ?>;
        }
        
        body {
            font-family: '<?php echo esc_attr($fontFamily); ?>', sans-serif;
            color: <?php echo esc_attr($textColor); ?>;
            line-height: 1.6;
            font-size: 16px;
        }
        
        :root {
            --primary: <?php echo esc_attr($primary); ?>;
            --secondary: <?php echo esc_attr($secondary); ?>;
            --accent: <?php echo esc_attr($accent); ?>;
            --background: <?php echo esc_attr($background); ?>;
            --text: <?php echo esc_attr($textColor); ?>;
            --header-bg: <?php echo esc_attr($headerBg); ?>;
            --footer-bg: <?php echo esc_attr($footerBg); ?>;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: '<?php echo esc_attr($headingFont); ?>', sans-serif;
            margin: 0;
        }
        
        a { text-decoration: none; }
        ul { list-style: none; }
        
        /* Admin Bar */
        .preview-admin-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: #fff;
            padding: 0 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 999999;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .preview-admin-bar .left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .preview-admin-bar .template-name {
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #fff;
        }
        .preview-admin-bar .category-badge {
            font-size: 11px;
            background: rgba(255,255,255,0.15);
            padding: 4px 12px;
            border-radius: 15px;
            color: #fff;
        }
        .preview-admin-bar .right {
            display: flex;
            gap: 10px;
        }
        .preview-admin-bar .btn {
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            cursor: pointer;
        }
        .preview-admin-bar .btn-back {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .preview-admin-bar .btn-back:hover { 
            background: rgba(255,255,255,0.2); 
        }
        .preview-admin-bar .btn-edit {
            background: var(--primary);
            color: #fff;
        }
        .preview-admin-bar .btn-edit:hover { 
            opacity: 0.9; 
        }
        
        /* Main Content Wrapper */
        .preview-main {
            margin-top: 60px;
            width: 100%;
        }
        
        /* Common Styles */
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 0 20px; 
        }
        
        /* Top Bar */
        .top-bar {
            background: var(--primary);
            color: #fff;
            padding: 10px 30px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        /* Header */
        .header {
            background: var(--header-bg);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            position: sticky;
            top: 60px;
            z-index: 1000;
            flex-wrap: wrap;
            gap: 15px;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav { 
            display: flex; 
            gap: 25px; 
            flex-wrap: wrap;
        }
        .nav a {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: color 0.3s;
        }
        .nav a:hover { color: var(--primary); }
        .header-cta {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
        }
        
        /* Hero */
        .hero {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 80px 30px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .hero h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
            color: #fff;
        }
        .hero p {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 35px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            color: #fff;
        }
        .hero-btns {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-primary {
            background: #fff;
            color: var(--primary);
            padding: 15px 35px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            transition: transform 0.3s;
            display: inline-block;
        }
        .btn-primary:hover { transform: translateY(-3px); }
        .btn-outline {
            background: transparent;
            color: #fff;
            padding: 15px 35px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            border: 2px solid rgba(255,255,255,0.5);
            display: inline-block;
        }
        .btn-outline:hover { background: rgba(255,255,255,0.1); }
        
        /* Section */
        section { 
            padding: 80px 30px; 
            position: relative;
        }
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        .section-title h2 {
            font-size: 36px;
            color: var(--primary);
            margin-bottom: 10px;
        }
        .section-title p {
            color: #64748b;
            font-size: 18px;
        }
        
        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            background: #fff;
            padding: 35px 25px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: transform 0.3s;
            position: relative;
        }
        .card:hover { transform: translateY(-8px); }
        .card-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 35px;
        }
        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: var(--text);
        }
        .card p {
            color: #64748b;
            font-size: 14px;
        }
        
        /* Stats */
        .stats-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 60px 30px;
            color: #fff;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 30px;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }
        .stat-num {
            font-size: 48px;
            font-weight: 800;
            color: #fff;
        }
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
            color: #fff;
        }
        
        /* Footer */
        .footer {
            background: var(--footer-bg);
            color: #fff;
            padding: 60px 30px 30px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto 40px;
        }
        .footer h4 {
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
            color: #fff;
        }
        .footer p, .footer li {
            opacity: 0.8;
            font-size: 14px;
            margin-bottom: 8px;
            line-height: 1.7;
            color: #fff;
        }
        .footer ul { list-style: none; }
        .footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }
        .footer a:hover { color: var(--primary); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            text-align: center;
            opacity: 0.6;
            font-size: 13px;
            color: #fff;
        }
        
        /* Alert Banner */
        .alert-banner {
            background: #dc2626;
            color: #fff;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .alert-banner.crisis {
            background: var(--primary);
        }
        
        /* Promo Banner */
        .promo-banner {
            background: linear-gradient(90deg, var(--accent), var(--primary));
            color: #fff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .promo-banner h3 {
            font-size: 20px;
            color: #fff;
        }
        .promo-banner .price {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
        }
        .promo-banner .btn-white {
            background: #fff;
            color: var(--primary);
            padding: 10px 25px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
        }
        
        /* Gallery Grid */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .gallery-item {
            border-radius: 12px;
            overflow: hidden;
            display: flex;
        }
        .gallery-item .before, .gallery-item .after {
            flex: 1;
            padding: 60px 20px;
            text-align: center;
            font-weight: 600;
        }
        .gallery-item .before {
            background: #e2e8f0;
            color: #64748b;
        }
        .gallery-item .after {
            background: var(--accent);
            color: #fff;
        }
        
        /* Packages Grid */
        .packages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .package-card {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            position: relative;
        }
        .package-card.featured {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            transform: scale(1.05);
        }
        .package-card .badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--accent);
            color: #fff;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        .package-card h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .package-card .price {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .package-card .tests {
            opacity: 0.8;
            margin-bottom: 20px;
        }
        .package-card .btn-package {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
        }
        .package-card:not(.featured) .btn-package {
            background: var(--primary);
            color: #fff;
        }
        .package-card.featured .btn-package {
            background: #fff;
            color: var(--primary);
        }
        
        /* Team Grid */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1100px;
            margin: 0 auto;
        }
        .team-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            text-align: center;
        }
        .team-photo {
            height: 180px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .team-avatar {
            width: 90px;
            height: 90px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #fff;
            font-weight: 700;
        }
        .team-info {
            padding: 25px;
        }
        .team-info h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .team-info .role {
            color: var(--primary);
            font-weight: 600;
            font-size: 14px;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--accent), var(--primary));
            padding: 80px 30px;
            text-align: center;
            color: #fff;
        }
        .cta-section h2 {
            font-size: 36px;
            margin-bottom: 15px;
            color: #fff;
        }
        .cta-section p {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: #fff;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .preview-admin-bar {
                flex-direction: column;
                gap: 10px;
                padding: 10px 15px;
                height: auto;
            }
            .preview-admin-bar .left, .preview-admin-bar .right {
                flex-wrap: wrap;
                justify-content: center;
            }
            .preview-main {
                margin-top: 100px;
            }
            .header {
                position: relative;
                top: 0;
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .nav { 
                display: none; 
            }
            .hero { 
                padding: 50px 20px; 
            }
            .hero h1 { 
                font-size: 32px; 
            }
            .hero p { 
                font-size: 16px; 
            }
            section { 
                padding: 50px 20px; 
            }
            .section-title h2 { 
                font-size: 28px; 
            }
            .stat-num { 
                font-size: 36px; 
            }
            .promo-banner {
                flex-direction: column;
                text-align: center;
            }
            .cards-grid, .team-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Admin Preview Bar -->
    <div class="preview-admin-bar">
        <div class="left">
            <div class="template-name">
                <span><?php echo $catInfo['icon']; ?></span>
                <span><?php echo esc_html($theme->name); ?></span>
            </div>
            <span class="category-badge"><?php echo esc_html(ucwords(str_replace('_', ' ', $category))); ?></span>
        </div>
        <div class="right">
            <a href="<?php echo esc_url($back_url); ?>" class="btn btn-back">← Back to Templates</a>
            <a href="<?php echo esc_url($edit_url); ?>" class="btn btn-edit">✏️ Edit Template</a>
        </div>
    </div>

    <!-- Main Preview Content -->
    <div class="preview-main">
        <?php 
        // Include category-specific preview
        $preview_file = __DIR__ . '/previews/preview-' . str_replace('_', '-', $category) . '.php';
        if (file_exists($preview_file)) {
            include $preview_file;
        } else {
            include __DIR__ . '/previews/preview-hospital.php';
        }
        ?>
    </div>
</body>
</html>
