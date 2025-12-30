<?php
/**
 * Template Preview Page
 * Full-page preview of hospital website template
 */

if (!defined('ABSPATH')) {
    exit;
}

// $preview_theme is passed from themes.php
$theme = $preview_theme;

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
function get_preview_value($defaults, $key, $fallback = '') {
    return isset($defaults[$key]) ? $defaults[$key] : $fallback;
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

// Get content values
$logoText = get_preview_value($defaults, 'logo_text', $theme->name);
$phone = get_preview_value($defaults, 'phone', '+1 (555) 123-4567');
$email = get_preview_value($defaults, 'email', 'info@' . strtolower(str_replace(' ', '', $theme->name)) . '.com');
$emergency = get_preview_value($defaults, 'emergency_number', '+1 (555) 999-0000');
$headline = get_preview_value($defaults, 'headline', 'Your Health, Our Priority');
$subheadline = get_preview_value($defaults, 'subheadline', 'Providing compassionate, world-class healthcare services with state-of-the-art facilities and experienced medical professionals.');
$ctaText = get_preview_value($defaults, 'cta_text', 'Book Appointment');
$cta2Text = get_preview_value($defaults, 'cta2_text', 'Our Services');

// Info cards
$card1Title = get_preview_value($defaults, 'card1_title', '24/7 Emergency');
$card1Text = get_preview_value($defaults, 'card1_text', 'Round-the-clock emergency care with rapid response team ready to help.');
$card2Title = get_preview_value($defaults, 'card2_title', 'Easy Appointments');
$card2Text = get_preview_value($defaults, 'card2_text', 'Book appointments online or call us. We make healthcare accessible.');
$card3Title = get_preview_value($defaults, 'card3_title', 'Quality Care');
$card3Text = get_preview_value($defaults, 'card3_text', 'Accredited facility with board-certified medical professionals.');

// Services
$servicesTitle = get_preview_value($defaults, 'section_title', 'Our Medical Services');
$servicesSubtitle = get_preview_value($defaults, 'section_subtitle', 'Comprehensive healthcare solutions for you and your family');

// Stats
$stat1Num = get_preview_value($defaults, 'stat1_number', '50+');
$stat1Label = get_preview_value($defaults, 'stat1_label', 'Years Experience');
$stat2Num = get_preview_value($defaults, 'stat2_number', '200+');
$stat2Label = get_preview_value($defaults, 'stat2_label', 'Expert Doctors');
$stat3Num = get_preview_value($defaults, 'stat3_number', '100K+');
$stat3Label = get_preview_value($defaults, 'stat3_label', 'Patients Served');
$stat4Num = get_preview_value($defaults, 'stat4_number', '50+');
$stat4Label = get_preview_value($defaults, 'stat4_label', 'Specialties');

// Footer
$footerAbout = get_preview_value($defaults, 'footer_about', $logoText . ' has been serving our community for decades, providing exceptional healthcare with compassion and excellence.');
$footerAddress = get_preview_value($defaults, 'footer_address', "123 Medical Center Drive\nHealthcare District\nYour City, State 12345");
$copyright = get_preview_value($defaults, 'copyright', '© ' . date('Y') . ' ' . $logoText . '. All rights reserved.');

// Features for template
$features = json_decode($theme->features, true) ?: array();

// Category icons
$categoryIcons = array(
    'hospital' => '🏥',
    'dental' => '🦷',
    'eye_care' => '👁️',
    'pediatric' => '👶',
    'cardiology' => '❤️',
    'mental_health' => '🧠',
    'orthopedic' => '🦴',
    'diagnostic' => '🔬'
);
$categoryIcon = isset($categoryIcons[$theme->category]) ? $categoryIcons[$theme->category] : '🏥';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($theme->name); ?> - Template Preview</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&family=Open+Sans:wght@400;600;700&family=Lato:wght@400;700&family=Montserrat:wght@400;500;600;700&family=Nunito:wght@400;600;700&family=Quicksand:wght@400;500;600;700&family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: <?php echo esc_attr($primary); ?>;
            --secondary: <?php echo esc_attr($secondary); ?>;
            --accent: <?php echo esc_attr($accent); ?>;
            --background: <?php echo esc_attr($background); ?>;
            --text: <?php echo esc_attr($textColor); ?>;
            --header-bg: <?php echo esc_attr($headerBg); ?>;
            --footer-bg: <?php echo esc_attr($footerBg); ?>;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: '<?php echo esc_attr($fontFamily); ?>', sans-serif;
            color: var(--text);
            background: var(--background);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: '<?php echo esc_attr($headingFont); ?>', sans-serif;
        }
        
        /* Admin Preview Bar */
        .preview-admin-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            padding: 12px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .preview-admin-bar .bar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .preview-admin-bar .template-name {
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .preview-admin-bar .template-category {
            font-size: 12px;
            background: rgba(255,255,255,0.15);
            padding: 4px 12px;
            border-radius: 15px;
        }
        .preview-admin-bar .bar-right {
            display: flex;
            gap: 12px;
        }
        .preview-admin-bar .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: none;
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
        
        .preview-content {
            margin-top: 60px;
        }
        
        /* Top Bar */
        .top-bar {
            background: var(--primary);
            color: #fff;
            padding: 10px 20px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .top-bar a {
            color: #fff;
            text-decoration: none;
        }
        .top-bar span {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        /* Header */
        header {
            background: var(--header-bg);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            flex-wrap: wrap;
            gap: 20px;
        }
        .logo {
            font-size: 26px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-icon {
            font-size: 32px;
        }
        nav {
            display: flex;
            gap: 30px;
        }
        nav a {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: color 0.3s;
            position: relative;
        }
        nav a:hover {
            color: var(--primary);
        }
        nav a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s;
        }
        nav a:hover::after {
            width: 100%;
        }
        .header-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            padding: 12px 28px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .header-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.2);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 100px 40px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="80" cy="20" r="30" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="80" r="40" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: cover;
        }
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }
        .hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        .hero p {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 40px;
            line-height: 1.7;
        }
        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .hero-buttons .btn-primary {
            background: #fff;
            color: var(--primary);
            padding: 16px 40px;
            border-radius: 35px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .hero-buttons .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }
        .hero-buttons .btn-secondary {
            background: transparent;
            color: #fff;
            padding: 16px 40px;
            border-radius: 35px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            border: 2px solid rgba(255,255,255,0.5);
            transition: all 0.3s;
        }
        .hero-buttons .btn-secondary:hover {
            background: rgba(255,255,255,0.1);
            border-color: #fff;
        }
        
        /* Info Cards */
        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: -60px auto 60px;
            padding: 0 40px;
            position: relative;
            z-index: 10;
        }
        .info-card {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.1);
            display: flex;
            gap: 20px;
            transition: all 0.3s;
        }
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .info-card-icon {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }
        .info-card h3 {
            font-size: 20px;
            margin-bottom: 8px;
            color: var(--text);
        }
        .info-card p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
        }
        
        /* Section */
        section {
            padding: 80px 40px;
        }
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }
        .section-header h2 {
            font-size: 40px;
            color: var(--primary);
            margin-bottom: 15px;
        }
        .section-header p {
            font-size: 18px;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .service-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.06);
            transition: all 0.3s;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }
        .service-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 40px;
            color: #fff;
        }
        .service-card h3 {
            font-size: 22px;
            margin-bottom: 12px;
            color: var(--text);
        }
        .service-card p {
            font-size: 15px;
            color: #64748b;
            line-height: 1.6;
        }
        
        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 80px 40px;
            color: #fff;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }
        .stat-item {
            padding: 20px;
        }
        .stat-number {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 8px;
            line-height: 1;
        }
        .stat-label {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 500;
        }
        
        /* Team Section */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 35px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .team-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            text-align: center;
            transition: all 0.3s;
        }
        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        .team-photo {
            height: 250px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .team-photo-placeholder {
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: #fff;
            font-weight: 700;
        }
        .team-info {
            padding: 30px;
        }
        .team-info h3 {
            font-size: 22px;
            margin-bottom: 5px;
            color: var(--text);
        }
        .team-info .specialty {
            color: var(--primary);
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 10px;
        }
        .team-info p {
            color: #64748b;
            font-size: 14px;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--accent) 0%, var(--primary) 100%);
            padding: 80px 40px;
            text-align: center;
            color: #fff;
        }
        .cta-section h2 {
            font-size: 40px;
            margin-bottom: 15px;
        }
        .cta-section p {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 35px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .cta-section .btn {
            background: #fff;
            color: var(--primary);
            padding: 18px 50px;
            border-radius: 35px;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
            display: inline-block;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .cta-section .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }
        
        /* Blog Section */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 35px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .blog-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .blog-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        .blog-image {
            height: 200px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
        }
        .blog-content {
            padding: 30px;
        }
        .blog-meta {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 12px;
        }
        .blog-content h3 {
            font-size: 20px;
            margin-bottom: 12px;
            color: var(--text);
            line-height: 1.4;
        }
        .blog-content p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .blog-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .blog-link:hover {
            text-decoration: underline;
        }
        
        /* Footer */
        footer {
            background: var(--footer-bg);
            color: #fff;
            padding: 70px 40px 30px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto 40px;
        }
        .footer-col h4 {
            font-size: 20px;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 12px;
        }
        .footer-col h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: var(--primary);
        }
        .footer-col p {
            opacity: 0.8;
            font-size: 15px;
            line-height: 1.8;
        }
        .footer-links {
            list-style: none;
        }
        .footer-links li {
            margin-bottom: 12px;
        }
        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 15px;
            transition: all 0.3s;
        }
        .footer-links a:hover {
            color: var(--primary);
            padding-left: 5px;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 30px;
            text-align: center;
            opacity: 0.7;
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .preview-admin-bar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .preview-admin-bar .bar-left {
                flex-direction: column;
                gap: 10px;
            }
            header {
                padding: 15px 20px;
            }
            nav {
                display: none;
            }
            .hero {
                padding: 60px 20px;
            }
            .hero h1 {
                font-size: 32px;
            }
            .hero p {
                font-size: 16px;
            }
            .info-cards {
                padding: 0 20px;
                margin-top: -40px;
            }
            section {
                padding: 50px 20px;
            }
            .section-header h2 {
                font-size: 28px;
            }
            .stat-number {
                font-size: 40px;
            }
        }
    </style>
</head>
<body>
    <!-- Admin Preview Bar -->
    <div class="preview-admin-bar">
        <div class="bar-left">
            <div class="template-name">
                <span><?php echo $categoryIcon; ?></span>
                <?php echo esc_html($theme->name); ?>
            </div>
            <span class="template-category"><?php echo esc_html(ucwords(str_replace('_', ' ', $theme->category))); ?></span>
        </div>
        <div class="bar-right">
            <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="btn btn-back">
                ← Back to Templates
            </a>
            <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id=' . $theme->id); ?>" class="btn btn-edit">
                ✏️ Edit Template
            </a>
        </div>
    </div>
    
    <div class="preview-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <span>
                📞 <?php echo esc_html($phone); ?> &nbsp;|&nbsp; ✉️ <?php echo esc_html($email); ?>
            </span>
            <span>
                🚨 Emergency: <?php echo esc_html($emergency); ?>
            </span>
        </div>
        
        <!-- Header -->
        <header>
            <div class="logo">
                <span class="logo-icon"><?php echo $categoryIcon; ?></span>
                <?php echo esc_html($logoText); ?>
            </div>
            <nav>
                <?php if (!empty($theme->pages)): ?>
                    <?php foreach (array_slice($theme->pages, 0, 6) as $page): ?>
                        <a href="#"><?php echo esc_html($page->page_name); ?></a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="#">Home</a>
                    <a href="#">About</a>
                    <a href="#">Services</a>
                    <a href="#">Doctors</a>
                    <a href="#">Contact</a>
                <?php endif; ?>
            </nav>
            <a href="#" class="header-btn"><?php echo esc_html($ctaText); ?></a>
        </header>
        
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1><?php echo esc_html($headline); ?></h1>
                <p><?php echo esc_html($subheadline); ?></p>
                <div class="hero-buttons">
                    <a href="#" class="btn-primary"><?php echo esc_html($ctaText); ?></a>
                    <a href="#" class="btn-secondary"><?php echo esc_html($cta2Text); ?></a>
                </div>
            </div>
        </section>
        
        <!-- Info Cards -->
        <div class="info-cards">
            <div class="info-card">
                <div class="info-card-icon">🕐</div>
                <div>
                    <h3><?php echo esc_html($card1Title); ?></h3>
                    <p><?php echo esc_html($card1Text); ?></p>
                </div>
            </div>
            <div class="info-card">
                <div class="info-card-icon">📅</div>
                <div>
                    <h3><?php echo esc_html($card2Title); ?></h3>
                    <p><?php echo esc_html($card2Text); ?></p>
                </div>
            </div>
            <div class="info-card">
                <div class="info-card-icon">🛡️</div>
                <div>
                    <h3><?php echo esc_html($card3Title); ?></h3>
                    <p><?php echo esc_html($card3Text); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Services Section -->
        <section style="background: <?php echo esc_attr($background); ?>;">
            <div class="section-header">
                <h2><?php echo esc_html($servicesTitle); ?></h2>
                <p><?php echo esc_html($servicesSubtitle); ?></p>
            </div>
            <div class="services-grid">
                <?php 
                $services = array(
                    array('icon' => '🚑', 'name' => 'Emergency Care', 'desc' => 'Round-the-clock emergency medical services with rapid response.'),
                    array('icon' => '🏥', 'name' => 'Surgery', 'desc' => 'Advanced surgical procedures with experienced surgeons.'),
                    array('icon' => '💊', 'name' => 'Internal Medicine', 'desc' => 'Comprehensive diagnosis and treatment of adult diseases.'),
                    array('icon' => '👶', 'name' => 'Pediatrics', 'desc' => 'Specialized healthcare for infants, children and adolescents.'),
                    array('icon' => '❤️', 'name' => 'Cardiology', 'desc' => 'Expert care for heart and cardiovascular conditions.'),
                    array('icon' => '🦴', 'name' => 'Orthopedics', 'desc' => 'Treatment for bones, joints, muscles and spine.'),
                );
                foreach ($services as $service): 
                ?>
                    <div class="service-card">
                        <div class="service-icon"><?php echo $service['icon']; ?></div>
                        <h3><?php echo esc_html($service['name']); ?></h3>
                        <p><?php echo esc_html($service['desc']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!-- Stats Section -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo esc_html($stat1Num); ?></div>
                    <div class="stat-label"><?php echo esc_html($stat1Label); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo esc_html($stat2Num); ?></div>
                    <div class="stat-label"><?php echo esc_html($stat2Label); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo esc_html($stat3Num); ?></div>
                    <div class="stat-label"><?php echo esc_html($stat3Label); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo esc_html($stat4Num); ?></div>
                    <div class="stat-label"><?php echo esc_html($stat4Label); ?></div>
                </div>
            </div>
        </section>
        
        <!-- Team Section -->
        <section style="background: <?php echo esc_attr($background); ?>;">
            <div class="section-header">
                <h2>Our Medical Team</h2>
                <p>Meet our experienced and dedicated healthcare professionals</p>
            </div>
            <div class="team-grid">
                <?php 
                $doctors = array(
                    array('name' => 'Dr. Sarah Johnson', 'specialty' => 'Chief Medical Officer', 'exp' => '25+ years experience'),
                    array('name' => 'Dr. Michael Chen', 'specialty' => 'Cardiology', 'exp' => '20+ years experience'),
                    array('name' => 'Dr. Emily Brown', 'specialty' => 'Pediatrics', 'exp' => '15+ years experience'),
                );
                foreach ($doctors as $doctor): 
                ?>
                    <div class="team-card">
                        <div class="team-photo">
                            <div class="team-photo-placeholder"><?php echo strtoupper(substr($doctor['name'], 4, 1)); ?></div>
                        </div>
                        <div class="team-info">
                            <h3><?php echo esc_html($doctor['name']); ?></h3>
                            <div class="specialty"><?php echo esc_html($doctor['specialty']); ?></div>
                            <p><?php echo esc_html($doctor['exp']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!-- CTA Section -->
        <section class="cta-section">
            <h2>Need Emergency Care?</h2>
            <p>Our emergency department is open 24/7. Don't wait – get the care you need now.</p>
            <a href="#" class="btn">📞 Call Emergency</a>
        </section>
        
        <!-- Blog Section -->
        <section style="background: <?php echo esc_attr($background); ?>;">
            <div class="section-header">
                <h2>Health Blog</h2>
                <p>Expert health advice and tips from our medical professionals</p>
            </div>
            <div class="blog-grid">
                <?php 
                $posts = array(
                    array('icon' => '🏃', 'title' => '10 Tips for a Healthier Lifestyle', 'excerpt' => 'Simple changes that can make a big difference in your overall health and wellbeing.'),
                    array('icon' => '🥗', 'title' => 'Nutrition Guide for Heart Health', 'excerpt' => 'Learn which foods can help keep your heart healthy and prevent cardiovascular disease.'),
                    array('icon' => '😴', 'title' => 'The Importance of Quality Sleep', 'excerpt' => 'Why good sleep is essential and how to improve your sleep habits.'),
                );
                foreach ($posts as $post): 
                ?>
                    <div class="blog-card">
                        <div class="blog-image"><?php echo $post['icon']; ?></div>
                        <div class="blog-content">
                            <div class="blog-meta">Dec 15, 2024 • Health Tips</div>
                            <h3><?php echo esc_html($post['title']); ?></h3>
                            <p><?php echo esc_html($post['excerpt']); ?></p>
                            <a href="#" class="blog-link">Read More →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <!-- Footer -->
        <footer>
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>About Us</h4>
                    <p><?php echo esc_html($footerAbout); ?></p>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <?php if (!empty($theme->pages)): ?>
                            <?php foreach (array_slice($theme->pages, 0, 5) as $page): ?>
                                <li><a href="#"><?php echo esc_html($page->page_name); ?></a></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><a href="#">Home</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Services</a></li>
                            <li><a href="#">Doctors</a></li>
                            <li><a href="#">Contact</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Our Services</h4>
                    <ul class="footer-links">
                        <li><a href="#">Emergency Care</a></li>
                        <li><a href="#">Surgery</a></li>
                        <li><a href="#">Diagnostics</a></li>
                        <li><a href="#">Pharmacy</a></li>
                        <li><a href="#">Rehabilitation</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact Info</h4>
                    <p>
                        📍 <?php echo nl2br(esc_html($footerAddress)); ?><br><br>
                        📞 <?php echo esc_html($phone); ?><br>
                        ✉️ <?php echo esc_html($email); ?>
                    </p>
                </div>
            </div>
            <div class="footer-bottom">
                <?php echo esc_html($copyright); ?>
            </div>
        </footer>
    </div>
</body>
</html>
<?php exit; ?>
