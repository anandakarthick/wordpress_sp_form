<?php
/**
 * Template Preview Page
 * Full-page preview of hospital website template
 * Version 3.0 - Unique Designs for Each Category
 */

if (!defined('ABSPATH')) {
    exit;
}

// $preview_theme is passed from themes.php
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
$email = get_preview_value($defaults, 'email', 'info@example.com');
$headline = get_preview_value($defaults, 'headline', 'Your Health, Our Priority');
$subheadline = get_preview_value($defaults, 'subheadline', 'Providing compassionate, world-class healthcare services.');

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($theme->name); ?> - Template Preview</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: '<?php echo esc_attr($fontFamily); ?>', sans-serif;
            color: var(--text);
            background: var(--background);
            line-height: 1.6;
            padding-top: 70px;
        }
        
        h1, h2, h3, h4 {
            font-family: '<?php echo esc_attr($headingFont); ?>', sans-serif;
        }
        
        /* Admin Bar */
        .admin-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: #fff;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 99999;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .admin-bar .left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .admin-bar .template-name {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-bar .category-badge {
            font-size: 12px;
            background: rgba(255,255,255,0.15);
            padding: 5px 14px;
            border-radius: 20px;
        }
        .admin-bar .right {
            display: flex;
            gap: 12px;
        }
        .admin-bar .btn {
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .admin-bar .btn-back {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .admin-bar .btn-back:hover { background: rgba(255,255,255,0.2); }
        .admin-bar .btn-edit {
            background: var(--primary);
            color: #fff;
        }
        .admin-bar .btn-edit:hover { opacity: 0.9; }
        
        /* Common Styles */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Top Bar */
        .top-bar {
            background: var(--primary);
            color: #fff;
            padding: 10px 30px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
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
            top: 70px;
            z-index: 1000;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav { display: flex; gap: 25px; }
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
        }
        .hero p {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 35px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
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
        }
        .btn-outline:hover { background: rgba(255,255,255,0.1); }
        
        /* Section */
        section { padding: 80px 30px; }
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
        }
        .card {
            background: #fff;
            padding: 35px 25px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: transform 0.3s;
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
        }
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
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
        }
        .footer p, .footer li {
            opacity: 0.8;
            font-size: 14px;
            margin-bottom: 8px;
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
        }
        
        /* Category Specific Styles */
        <?php if ($category === 'dental'): ?>
        .hero::before {
            content: '😊';
            position: absolute;
            font-size: 200px;
            opacity: 0.1;
            right: 10%;
            top: 50%;
            transform: translateY(-50%);
        }
        <?php elseif ($category === 'eye_care'): ?>
        .hero::before {
            content: '👁️';
            position: absolute;
            font-size: 200px;
            opacity: 0.1;
            right: 10%;
            top: 50%;
            transform: translateY(-50%);
        }
        <?php elseif ($category === 'pediatric'): ?>
        .hero::before {
            content: '🎈🌈⭐';
            position: absolute;
            font-size: 100px;
            opacity: 0.15;
            right: 5%;
            top: 20%;
        }
        <?php elseif ($category === 'cardiology'): ?>
        .hero::before {
            content: '❤️';
            position: absolute;
            font-size: 200px;
            opacity: 0.1;
            right: 10%;
            top: 50%;
            transform: translateY(-50%);
            animation: heartbeat 1s ease-in-out infinite;
        }
        @keyframes heartbeat {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.1); }
        }
        <?php elseif ($category === 'mental_health'): ?>
        .hero::before {
            content: '🌿🦋🌸';
            position: absolute;
            font-size: 80px;
            opacity: 0.15;
            right: 5%;
            top: 20%;
        }
        <?php elseif ($category === 'orthopedic'): ?>
        .hero::before {
            content: '🏃';
            position: absolute;
            font-size: 200px;
            opacity: 0.1;
            right: 10%;
            top: 50%;
            transform: translateY(-50%);
        }
        <?php elseif ($category === 'diagnostic'): ?>
        .hero::before {
            content: '🔬🧪';
            position: absolute;
            font-size: 120px;
            opacity: 0.1;
            right: 5%;
            top: 30%;
        }
        <?php endif; ?>
        
        /* Alert Banner */
        .alert-banner {
            background: #dc2626;
            color: #fff;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }
        .alert-banner.crisis {
            background: var(--primary);
        }
        
        /* LASIK Banner */
        .promo-banner {
            background: linear-gradient(90deg, var(--accent), var(--primary));
            color: #fff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .promo-banner h3 {
            font-size: 20px;
        }
        .promo-banner .price {
            font-size: 24px;
            font-weight: 800;
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
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        .team-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            text-align: center;
        }
        .team-photo {
            height: 200px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .team-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #fff;
        }
        .team-info {
            padding: 25px;
        }
        .team-info h3 {
            font-size: 20px;
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
        }
        .cta-section p {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body { padding-top: 0; }
            .admin-bar {
                position: relative;
                flex-direction: column;
                gap: 15px;
                padding: 15px;
                height: auto;
            }
            .admin-bar .left, .admin-bar .right {
                flex-wrap: wrap;
                justify-content: center;
            }
            .header {
                position: relative;
                top: 0;
                flex-direction: column;
                gap: 15px;
            }
            .nav { display: none; }
            .hero { padding: 50px 20px; }
            .hero h1 { font-size: 32px; }
            .hero p { font-size: 16px; }
            section { padding: 50px 20px; }
            .section-title h2 { font-size: 28px; }
            .stat-num { font-size: 36px; }
            .promo-banner {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Admin Bar -->
    <div class="admin-bar">
        <div class="left">
            <div class="template-name">
                <span><?php echo $catInfo['icon']; ?></span>
                <span><?php echo esc_html($theme->name); ?></span>
            </div>
            <span class="category-badge"><?php echo esc_html(ucwords(str_replace('_', ' ', $category))); ?></span>
        </div>
        <div class="right">
            <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="btn btn-back">← Back to Templates</a>
            <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id=' . $theme->id); ?>" class="btn btn-edit">✏️ Edit Template</a>
        </div>
    </div>

    <?php 
    // Include category-specific preview
    switch ($category) {
        case 'hospital':
            include __DIR__ . '/previews/preview-hospital.php';
            break;
        case 'dental':
            include __DIR__ . '/previews/preview-dental.php';
            break;
        case 'eye_care':
            include __DIR__ . '/previews/preview-eye-care.php';
            break;
        case 'pediatric':
            include __DIR__ . '/previews/preview-pediatric.php';
            break;
        case 'cardiology':
            include __DIR__ . '/previews/preview-cardiology.php';
            break;
        case 'mental_health':
            include __DIR__ . '/previews/preview-mental-health.php';
            break;
        case 'orthopedic':
            include __DIR__ . '/previews/preview-orthopedic.php';
            break;
        case 'diagnostic':
            include __DIR__ . '/previews/preview-diagnostic.php';
            break;
        default:
            include __DIR__ . '/previews/preview-hospital.php';
    }
    ?>

</body>
</html>
<?php exit; ?>
