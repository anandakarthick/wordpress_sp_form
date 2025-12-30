<?php
/**
 * Customer Form Template - Hospital Website Template Selector
 */

if (!defined('ABSPATH')) {
    exit;
}

$themes_handler = SPFM_Themes::get_instance();

// Get available themes for this form
$theme_ids = json_decode($form->available_themes, true) ?: array();

// If no themes specified, get all active templates
if (empty($theme_ids)) {
    $all_templates = $themes_handler->get_templates();
    $theme_ids = array_map(function($t) { return $t->id; }, $all_templates);
}

$available_themes = $themes_handler->get_by_ids($theme_ids);

// Get complete theme data
$themes_complete = array();
foreach ($available_themes as $theme) {
    $themes_complete[$theme->id] = $themes_handler->get_theme_complete($theme->id);
}

$allow_theme_selection = $form->allow_theme_selection;
$allow_color_customization = $form->allow_color_customization;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($form->name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&family=Nunito:wght@400;600;700&family=Open+Sans:wght@400;600&family=Montserrat:wght@400;600;700&family=Quicksand:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Lato:wght@400;700&family=Playfair+Display:wght@400;600;700&family=Oswald:wght@400;500;600&family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0891b2;
            --secondary: #0e7490;
            --accent: #06b6d4;
            --bg: #f0fdfa;
            --text: #1e293b;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0fdfa 0%, #ecfeff 100%);
            min-height: 100vh;
        }
        
        /* Header */
        .site-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: #fff;
            padding: 40px 20px;
            text-align: center;
        }
        .site-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .site-header p {
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
            font-size: 16px;
        }
        
        /* Progress Steps */
        .progress-steps {
            background: #fff;
            padding: 20px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .steps-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            max-width: 900px;
            margin: 0 auto;
            flex-wrap: wrap;
            padding: 0 20px;
        }
        .step {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 500;
        }
        .step.active { color: var(--primary); }
        .step.completed { color: #10b981; }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e2e8f0;
            font-weight: 700;
            font-size: 16px;
        }
        .step.active .step-number {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
        }
        .step.completed .step-number {
            background: #10b981;
            color: #fff;
        }
        
        /* Main Container */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        /* Step Sections */
        .step-section { display: none; }
        .step-section.active { display: block; animation: fadeIn 0.4s ease; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Section Title */
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        .section-title h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }
        .section-title p {
            color: #64748b;
            font-size: 16px;
        }
        
        /* Theme Selection */
        .themes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 30px;
        }
        .theme-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.4s ease;
            border: 3px solid transparent;
            position: relative;
        }
        .theme-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        .theme-card.selected {
            border-color: var(--primary);
            box-shadow: 0 10px 40px rgba(8,145,178,0.3);
        }
        .theme-preview {
            height: 220px;
            position: relative;
            overflow: hidden;
        }
        .theme-mockup {
            width: 92%;
            height: 200px;
            margin: 10px auto;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }
        .mockup-header {
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }
        .mockup-logo {
            font-weight: 700;
            color: inherit;
        }
        .mockup-nav {
            display: flex;
            gap: 12px;
            font-size: 9px;
            color: #64748b;
        }
        .mockup-hero {
            height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
            padding: 10px;
        }
        .mockup-hero h3 {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .mockup-hero p {
            font-size: 8px;
            opacity: 0.9;
        }
        .mockup-content {
            padding: 12px;
            display: flex;
            gap: 8px;
        }
        .mockup-card {
            flex: 1;
            height: 45px;
            background: #f1f5f9;
            border-radius: 6px;
        }
        .mockup-footer {
            height: 25px;
            margin-top: auto;
        }
        .select-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 35px;
            height: 35px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            font-size: 18px;
            color: #cbd5e1;
        }
        .theme-card.selected .select-badge {
            background: var(--primary);
            color: #fff;
        }
        .theme-info {
            padding: 25px;
        }
        .theme-category {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .theme-info h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1e293b;
        }
        .theme-info p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .theme-features {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .theme-feature {
            font-size: 11px;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 15px;
            color: #475569;
        }
        
        /* Content Editor */
        .content-editor {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }
        .pages-sidebar {
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        .pages-list {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .pages-list h4 {
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        .page-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: #f8fafc;
            border: 2px solid transparent;
            border-radius: 12px;
            cursor: pointer;
            margin-bottom: 10px;
            transition: all 0.3s;
        }
        .page-nav-item:hover { background: #f1f5f9; }
        .page-nav-item.active {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 4px 15px rgba(8,145,178,0.15);
        }
        .page-nav-item.completed {
            background: #ecfdf5;
            border-color: #10b981;
        }
        .page-nav-item i { font-size: 18px; color: var(--primary); }
        .page-nav-item span { flex: 1; font-weight: 500; }
        .page-nav-item .check { color: #10b981; display: none; }
        .page-nav-item.completed .check { display: block; }
        
        /* Page Content Editor */
        .page-content-editor {
            background: #fff;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            display: none;
        }
        .page-content-editor.active { display: block; }
        .page-header {
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f1f5f9;
        }
        .page-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .page-header h2 i { color: var(--primary); }
        .page-header p { color: #64748b; }
        
        /* Section Card */
        .section-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 25px;
        }
        .section-card h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Form Fields */
        .form-group {
            margin-bottom: 22px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #334155;
        }
        .form-group label .required { color: #ef4444; }
        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background: #fff;
        }
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 4px rgba(8,145,178,0.1);
        }
        .form-control::placeholder { color: #94a3b8; }
        .form-hint {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 6px;
        }
        
        /* Image Upload */
        .image-upload {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #fff;
        }
        .image-upload:hover {
            border-color: var(--primary);
            background: #f0fdfa;
        }
        .image-upload i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 15px;
        }
        .image-upload p { color: #64748b; }
        .image-upload input { display: none; }
        .image-preview {
            max-width: 200px;
            max-height: 150px;
            margin-top: 15px;
            border-radius: 8px;
        }
        
        /* Repeater */
        .repeater-container {
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            background: #fff;
        }
        .repeater-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            position: relative;
        }
        .repeater-item .remove-item {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 28px;
            height: 28px;
            background: #fee2e2;
            color: #ef4444;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
        }
        .add-repeater-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px;
            background: #f1f5f9;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            color: #475569;
            transition: all 0.3s;
        }
        .add-repeater-item:hover {
            background: #e2e8f0;
            color: var(--primary);
        }
        
        /* Color Customization */
        .color-customization {
            background: #fff;
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .color-customization h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .color-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 25px;
        }
        .color-picker-group { text-align: center; }
        .color-picker-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }
        .color-picker-group input[type="color"] {
            width: 70px;
            height: 50px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .color-picker-group .hint {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 8px;
        }
        
        /* Preview Container */
        .preview-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }
        .preview-sidebar {
            position: sticky;
            top: 100px;
            height: fit-content;
        }
        .preview-controls {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .preview-controls h4 {
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .preview-page-btn {
            display: block;
            width: 100%;
            padding: 12px 16px;
            background: #f8fafc;
            border: none;
            border-radius: 10px;
            text-align: left;
            cursor: pointer;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .preview-page-btn.active {
            background: var(--primary);
            color: #fff;
        }
        .preview-main {
            background: #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
        }
        .preview-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            background: #1e293b;
            color: #fff;
        }
        .device-buttons { display: flex; gap: 8px; }
        .device-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: #fff;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .device-btn.active { background: var(--primary); }
        .preview-frame-wrapper {
            padding: 25px;
            display: flex;
            justify-content: center;
            min-height: 600px;
            background: #f1f5f9;
        }
        .preview-frame {
            width: 100%;
            max-width: 1000px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.4s;
        }
        .preview-frame.tablet { max-width: 768px; }
        .preview-frame.mobile { max-width: 375px; }
        
        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #f1f5f9;
        }
        .btn-nav {
            padding: 16px 35px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            border: none;
        }
        .btn-prev {
            background: #f1f5f9;
            color: #475569;
        }
        .btn-prev:hover { background: #e2e8f0; }
        .btn-next {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
        }
        .btn-next:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(8,145,178,0.3); }
        .btn-next:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .btn-submit {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            padding: 18px 45px;
            font-size: 18px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(16,185,129,0.3); }
        
        /* Success */
        .success-container {
            text-align: center;
            padding: 100px 30px;
            background: #fff;
            border-radius: 24px;
            max-width: 600px;
            margin: 60px auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        .success-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }
        .success-icon i { font-size: 60px; color: #fff; }
        .success-container h2 {
            color: #10b981;
            font-size: 32px;
            margin-bottom: 15px;
        }
        .success-container p {
            color: #64748b;
            font-size: 18px;
            line-height: 1.7;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .content-editor, .preview-container { grid-template-columns: 1fr; }
            .pages-sidebar, .preview-sidebar { position: static; }
            .steps-container { gap: 20px; }
        }
        @media (max-width: 600px) {
            .themes-grid { grid-template-columns: 1fr; }
            .site-header h1 { font-size: 24px; }
            .step span { display: none; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <h1><?php echo esc_html($form->header_text ?: $form->name); ?></h1>
        <p>Select your preferred hospital website template, customize it, and fill in your content.</p>
    </header>
    
    <!-- Progress Steps -->
    <div class="progress-steps">
        <div class="steps-container">
            <div class="step active" data-step="1">
                <span class="step-number">1</span>
                <span>Choose Template</span>
            </div>
            <div class="step" data-step="2">
                <span class="step-number">2</span>
                <span>Fill Content</span>
            </div>
            <?php if ($allow_color_customization): ?>
            <div class="step" data-step="3">
                <span class="step-number">3</span>
                <span>Customize Colors</span>
            </div>
            <div class="step" data-step="4">
                <span class="step-number">4</span>
                <span>Preview & Submit</span>
            </div>
            <?php else: ?>
            <div class="step" data-step="3">
                <span class="step-number">3</span>
                <span>Preview & Submit</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="main-container">
        <!-- Step 1: Choose Template -->
        <div class="step-section active" id="step-1">
            <div class="section-title">
                <h2>Choose Your Hospital Website Template</h2>
                <p>Select a template that best fits your healthcare facility</p>
            </div>
            
            <div class="themes-grid">
                <?php foreach ($themes_complete as $theme): ?>
                    <div class="theme-card" data-theme-id="<?php echo $theme->id; ?>">
                        <div class="theme-preview" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?> 0%, <?php echo esc_attr($theme->secondary_color); ?> 100%);">
                            <div class="select-badge">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div class="theme-mockup">
                                <div class="mockup-header" style="border-bottom-color: <?php echo esc_attr($theme->primary_color); ?>20;">
                                    <span class="mockup-logo" style="color: <?php echo esc_attr($theme->primary_color); ?>;">
                                        <?php echo esc_html(substr($theme->name, 0, 15)); ?>
                                    </span>
                                    <div class="mockup-nav">
                                        <?php 
                                        $nav_items = array('Home', 'About', 'Services', 'Contact');
                                        foreach ($nav_items as $item): 
                                        ?>
                                            <span><?php echo $item; ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="mockup-hero" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?>, <?php echo esc_attr($theme->secondary_color); ?>);">
                                    <h3>Your Health, Our Priority</h3>
                                    <p>Quality healthcare for you and your family</p>
                                </div>
                                <div class="mockup-content">
                                    <div class="mockup-card"></div>
                                    <div class="mockup-card"></div>
                                    <div class="mockup-card"></div>
                                </div>
                                <div class="mockup-footer" style="background: <?php echo esc_attr($theme->footer_bg_color); ?>;"></div>
                            </div>
                        </div>
                        <div class="theme-info">
                            <span class="theme-category" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?>, <?php echo esc_attr($theme->accent_color); ?>);">
                                <?php echo esc_html(str_replace('_', ' ', ucfirst($theme->category))); ?>
                            </span>
                            <h3><?php echo esc_html($theme->name); ?></h3>
                            <p><?php echo esc_html($theme->description); ?></p>
                            <div class="theme-features">
                                <?php 
                                $features = json_decode($theme->features, true) ?: array();
                                foreach (array_slice($features, 0, 4) as $feature): 
                                ?>
                                    <span class="theme-feature"><?php echo esc_html($feature); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="nav-buttons">
                <div></div>
                <button class="btn-nav btn-next" onclick="goToStep(2)" id="step1-next" disabled>
                    Continue <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Step 2: Fill Content -->
        <div class="step-section" id="step-2">
            <div class="section-title">
                <h2>Fill Your Website Content</h2>
                <p>Enter the information for each page of your website</p>
            </div>
            
            <div class="content-editor">
                <div class="pages-sidebar">
                    <div class="pages-list">
                        <h4>Website Pages</h4>
                        <div id="pages-nav"></div>
                    </div>
                </div>
                
                <div class="content-main" id="content-forms"></div>
            </div>
            
            <div class="nav-buttons">
                <button class="btn-nav btn-prev" onclick="goToStep(1)">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <button class="btn-nav btn-next" onclick="goToStep(3)">
                    Continue <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <?php if ($allow_color_customization): ?>
        <!-- Step 3: Customize Colors -->
        <div class="step-section" id="step-3">
            <div class="section-title">
                <h2>Customize Your Colors</h2>
                <p>Personalize the color scheme to match your brand</p>
            </div>
            
            <div class="color-customization">
                <h3><i class="bi bi-palette"></i> Color Palette</h3>
                
                <div class="color-grid">
                    <div class="color-picker-group">
                        <label>Primary Color</label>
                        <input type="color" id="custom-primary" value="#0891b2">
                        <p class="hint">Main buttons & links</p>
                    </div>
                    <div class="color-picker-group">
                        <label>Secondary Color</label>
                        <input type="color" id="custom-secondary" value="#0e7490">
                        <p class="hint">Gradients & accents</p>
                    </div>
                    <div class="color-picker-group">
                        <label>Accent Color</label>
                        <input type="color" id="custom-accent" value="#06b6d4">
                        <p class="hint">Highlights & CTAs</p>
                    </div>
                    <div class="color-picker-group">
                        <label>Background</label>
                        <input type="color" id="custom-background" value="#f0fdfa">
                        <p class="hint">Page background</p>
                    </div>
                    <div class="color-picker-group">
                        <label>Text Color</label>
                        <input type="color" id="custom-text" value="#1e293b">
                        <p class="hint">Body text</p>
                    </div>
                    <div class="color-picker-group">
                        <label>Header Background</label>
                        <input type="color" id="custom-header-bg" value="#ffffff">
                        <p class="hint">Navigation area</p>
                    </div>
                </div>
            </div>
            
            <div class="nav-buttons">
                <button class="btn-nav btn-prev" onclick="goToStep(2)">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <button class="btn-nav btn-next" onclick="goToStep(4)">
                    Preview Website <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Step 4: Preview & Submit -->
        <div class="step-section" id="step-4">
        <?php else: ?>
        <!-- Step 3: Preview & Submit -->
        <div class="step-section" id="step-3">
        <?php endif; ?>
            <div class="section-title">
                <h2>Preview & Submit</h2>
                <p>Review your website and submit your order</p>
            </div>
            
            <div class="preview-container">
                <div class="preview-sidebar">
                    <div class="preview-controls">
                        <h4>Preview Pages</h4>
                        <div class="preview-pages" id="preview-pages-nav"></div>
                        
                        <hr style="margin: 20px 0; border-color: #e2e8f0;">
                        
                        <h4>Your Information</h4>
                        <div class="form-group">
                            <label>Your Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="customer-name" required placeholder="John Doe">
                        </div>
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" class="form-control" id="customer-email" required placeholder="john@example.com">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" class="form-control" id="customer-phone" placeholder="+1 234 567 8900">
                        </div>
                    </div>
                </div>
                
                <div class="preview-main">
                    <div class="preview-toolbar">
                        <div class="device-buttons">
                            <button class="device-btn active" data-device="desktop"><i class="bi bi-display"></i></button>
                            <button class="device-btn" data-device="tablet"><i class="bi bi-tablet"></i></button>
                            <button class="device-btn" data-device="mobile"><i class="bi bi-phone"></i></button>
                        </div>
                        <span>Live Preview</span>
                    </div>
                    
                    <div class="preview-frame-wrapper">
                        <div class="preview-frame" id="live-preview"></div>
                    </div>
                </div>
            </div>
            
            <div class="nav-buttons">
                <button class="btn-nav btn-prev" onclick="goToStep(<?php echo $allow_color_customization ? 3 : 2; ?>)">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <button class="btn-nav btn-submit" onclick="submitOrder()">
                    <i class="bi bi-check-lg"></i> Submit Website Order
                </button>
            </div>
        </div>
        
        <!-- Success -->
        <div class="step-section" id="step-success">
            <div class="success-container">
                <div class="success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h2>Thank You!</h2>
                <p><?php echo esc_html($form->success_message ?: 'Your hospital website order has been submitted successfully. Our team will review your submission and contact you shortly.'); ?></p>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        const formId = <?php echo $form->id; ?>;
        const token = '<?php echo esc_js($token); ?>';
        const nonce = '<?php echo wp_create_nonce('spfm_nonce'); ?>';
        const allowColorCustomization = <?php echo $allow_color_customization ? 'true' : 'false'; ?>;
        const totalSteps = <?php echo $allow_color_customization ? 4 : 3; ?>;
        
        const themesData = <?php echo json_encode($themes_complete); ?>;
        
        let selectedThemeId = null;
        let currentStep = 1;
        let pageContents = {};
        let colorCustomizations = {};
        let currentPageIndex = 0;
        
        $(document).ready(function() {
            // Theme selection
            $('.theme-card').on('click', function() {
                $('.theme-card').removeClass('selected');
                $(this).addClass('selected');
                selectedThemeId = $(this).data('theme-id');
                $('#step1-next').prop('disabled', false);
                
                const theme = themesData[selectedThemeId];
                if (theme) {
                    $('#custom-primary').val(theme.primary_color);
                    $('#custom-secondary').val(theme.secondary_color);
                    $('#custom-accent').val(theme.accent_color || theme.primary_color);
                    $('#custom-background').val(theme.background_color);
                    $('#custom-text').val(theme.text_color);
                    $('#custom-header-bg').val(theme.header_bg_color || '#ffffff');
                    
                    colorCustomizations = {
                        primary_color: theme.primary_color,
                        secondary_color: theme.secondary_color,
                        accent_color: theme.accent_color || theme.primary_color,
                        background_color: theme.background_color,
                        text_color: theme.text_color,
                        header_bg_color: theme.header_bg_color || '#ffffff'
                    };
                    
                    document.documentElement.style.setProperty('--primary', theme.primary_color);
                    document.documentElement.style.setProperty('--secondary', theme.secondary_color);
                    document.documentElement.style.setProperty('--accent', theme.accent_color);
                }
            });
            
            // Color changes
            $('input[type="color"]').on('input', function() {
                const id = $(this).attr('id');
                const value = $(this).val();
                const key = id.replace('custom-', '').replace(/-/g, '_');
                colorCustomizations[key] = value;
                
                if (key === 'primary_color' || key === 'primary') {
                    document.documentElement.style.setProperty('--primary', value);
                }
            });
            
            // Device switcher
            $('.device-btn').on('click', function() {
                $('.device-btn').removeClass('active');
                $(this).addClass('active');
                const device = $(this).data('device');
                $('.preview-frame').removeClass('desktop tablet mobile').addClass(device);
            });
        });
        
        function goToStep(step) {
            if (step === 2 && !selectedThemeId) {
                alert('Please select a template first.');
                return;
            }
            
            $('.step').removeClass('active completed');
            for (let i = 1; i < step; i++) {
                $(`.step[data-step="${i}"]`).addClass('completed');
            }
            $(`.step[data-step="${step}"]`).addClass('active');
            
            $('.step-section').removeClass('active');
            $(`#step-${step}`).addClass('active');
            
            currentStep = step;
            
            if (step === 2) loadContentEditor();
            if ((allowColorCustomization && step === 4) || (!allowColorCustomization && step === 3)) loadPreview();
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function loadContentEditor() {
            const theme = themesData[selectedThemeId];
            if (!theme) return;
            
            let pagesNav = '';
            theme.pages.forEach((page, index) => {
                const icon = getPageIcon(page.page_icon);
                pagesNav += `
                    <div class="page-nav-item ${index === 0 ? 'active' : ''}" data-page-index="${index}" onclick="showPageEditor(${index})">
                        <i class="bi bi-${icon}"></i>
                        <span>${page.page_name}</span>
                        <i class="bi bi-check-circle-fill check"></i>
                    </div>
                `;
            });
            $('#pages-nav').html(pagesNav);
            
            let contentForms = '';
            theme.pages.forEach((page, index) => {
                const icon = getPageIcon(page.page_icon);
                contentForms += `
                    <div class="page-content-editor ${index === 0 ? 'active' : ''}" id="page-editor-${index}">
                        <div class="page-header">
                            <h2><i class="bi bi-${icon}"></i> ${page.page_name}</h2>
                            <p>${page.page_description || 'Fill in the content for this page.'}</p>
                        </div>
                        ${buildSectionForms(page, index)}
                    </div>
                `;
            });
            $('#content-forms').html(contentForms);
        }
        
        function buildSectionForms(page, pageIndex) {
            if (!page.sections || page.sections.length === 0) {
                return '<p style="color: #64748b;">No content sections for this page.</p>';
            }
            
            let html = '';
            page.sections.forEach((section, secIndex) => {
                html += `
                    <div class="section-card">
                        <h4><i class="bi bi-layers"></i> ${section.section_name}</h4>
                        ${buildFieldsHtml(section.fields, section.default_values || {}, pageIndex, secIndex)}
                    </div>
                `;
            });
            return html;
        }
        
        function buildFieldsHtml(fields, defaults, pageIndex, secIndex, prefix = '') {
            if (!fields || fields.length === 0) return '';
            
            let html = '';
            fields.forEach((field, fieldIndex) => {
                if (field.type === 'repeater') return;
                
                const fieldName = `${prefix}page_${pageIndex}_sec_${secIndex}_${field.name}`;
                const required = field.required ? '<span class="required">*</span>' : '';
                const defaultVal = defaults[field.name] || field.placeholder || '';
                
                html += `<div class="form-group">`;
                html += `<label>${field.label} ${required}</label>`;
                
                switch (field.type) {
                    case 'text':
                    case 'email':
                    case 'url':
                    case 'number':
                        html += `<input type="${field.type}" class="form-control content-field" name="${fieldName}" 
                                 value="${escapeHtml(defaultVal)}" placeholder="${field.placeholder || ''}" ${field.required ? 'required' : ''}>`;
                        break;
                    case 'textarea':
                        html += `<textarea class="form-control content-field" name="${fieldName}" rows="4" 
                                 placeholder="${field.placeholder || ''}" ${field.required ? 'required' : ''}>${escapeHtml(defaultVal)}</textarea>`;
                        break;
                    case 'editor':
                        html += `<textarea class="form-control content-field" name="${fieldName}" rows="6" 
                                 placeholder="${field.placeholder || ''}" ${field.required ? 'required' : ''}>${escapeHtml(defaultVal)}</textarea>`;
                        break;
                    case 'image':
                        html += `
                            <div class="image-upload" onclick="triggerFileInput('${fieldName}')">
                                <i class="bi bi-cloud-upload"></i>
                                <p>Click to upload image</p>
                                <input type="file" id="${fieldName}" class="content-field image-field" accept="image/*" 
                                       onchange="previewImage(this, '${fieldName}')">
                                <img id="preview-${fieldName}" class="image-preview" style="display:none;">
                            </div>
                        `;
                        break;
                    case 'icon':
                        html += `<input type="text" class="form-control content-field" name="${fieldName}" 
                                 value="${escapeHtml(defaultVal)}" placeholder="e.g., heart, clock, shield">`;
                        break;
                    default:
                        html += `<input type="text" class="form-control content-field" name="${fieldName}" 
                                 value="${escapeHtml(defaultVal)}" placeholder="${field.placeholder || ''}">`;
                }
                
                if (field.hint) {
                    html += `<p class="form-hint">${field.hint}</p>`;
                }
                
                html += `</div>`;
            });
            
            return html;
        }
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function getPageIcon(iconClass) {
            const mapping = {
                'dashicons-admin-home': 'house',
                'dashicons-info': 'info-circle',
                'dashicons-phone': 'telephone',
                'dashicons-email': 'envelope',
                'dashicons-heart': 'heart-pulse',
                'dashicons-groups': 'people',
                'dashicons-building': 'building',
                'dashicons-welcome-write-blog': 'journal-text',
                'dashicons-format-gallery': 'images',
                'dashicons-clipboard': 'clipboard-check',
                'dashicons-calendar': 'calendar-check',
                'dashicons-admin-page': 'file-text'
            };
            return mapping[iconClass] || 'file-text';
        }
        
        function showPageEditor(index) {
            $('.page-nav-item').removeClass('active');
            $(`.page-nav-item[data-page-index="${index}"]`).addClass('active');
            $('.page-content-editor').removeClass('active');
            $(`#page-editor-${index}`).addClass('active');
            currentPageIndex = index;
        }
        
        function triggerFileInput(id) {
            document.getElementById(id).click();
        }
        
        function previewImage(input, fieldName) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(`#preview-${fieldName}`).attr('src', e.target.result).show();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function collectContentData() {
            pageContents = {};
            $('.content-field').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    if (!$(this).is(':file')) {
                        pageContents[name] = $(this).val();
                    }
                }
            });
            return pageContents;
        }
        
        function loadPreview() {
            collectContentData();
            
            const theme = themesData[selectedThemeId];
            if (!theme) return;
            
            let pagesNav = '';
            theme.pages.forEach((page, index) => {
                pagesNav += `
                    <button class="preview-page-btn ${index === 0 ? 'active' : ''}" onclick="showPreviewPage(${index}, this)">
                        ${page.page_name}
                    </button>
                `;
            });
            $('#preview-pages-nav').html(pagesNav);
            
            renderPagePreview(0);
        }
        
        function showPreviewPage(index, btn) {
            $('.preview-page-btn').removeClass('active');
            $(btn).addClass('active');
            renderPagePreview(index);
        }
        
        function renderPagePreview(pageIndex) {
            const theme = themesData[selectedThemeId];
            const page = theme.pages[pageIndex];
            
            const primary = colorCustomizations.primary_color || theme.primary_color;
            const secondary = colorCustomizations.secondary_color || theme.secondary_color;
            const accent = colorCustomizations.accent_color || theme.accent_color || primary;
            const background = colorCustomizations.background_color || theme.background_color;
            const textColor = colorCustomizations.text_color || theme.text_color;
            const headerBg = colorCustomizations.header_bg_color || theme.header_bg_color || '#ffffff';
            const footerBg = theme.footer_bg_color || '#0f172a';
            
            const headerContent = getContentForSection(0, 0);
            const logoText = headerContent.logo_text || theme.name;
            const phone = headerContent.phone || '+1 (555) 123-4567';
            const email = headerContent.email || 'info@hospital.com';
            
            let previewHtml = `
                <div style="font-family: ${theme.font_family}, sans-serif; background: ${background}; min-height: 100%;">
                    <!-- Top Bar -->
                    <div style="background: ${primary}; color: #fff; padding: 8px 20px; font-size: 12px; display: flex; justify-content: space-between; flex-wrap: wrap;">
                        <span>📞 ${phone} | ✉️ ${email}</span>
                        <span>🚨 24/7 Emergency Services</span>
                    </div>
                    
                    <!-- Header -->
                    <header style="background: ${headerBg}; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                        <div style="font-size: 20px; font-weight: 700; color: ${primary};">${logoText}</div>
                        <nav style="display: flex; gap: 25px; font-size: 14px;">
                            ${theme.pages.map(p => `<a href="#" style="color: ${textColor}; text-decoration: none;">${p.page_name}</a>`).join('')}
                        </nav>
                        <a href="#" style="background: ${primary}; color: #fff; padding: 10px 20px; border-radius: 25px; text-decoration: none; font-size: 13px; font-weight: 600;">Book Appointment</a>
                    </header>
                    
                    <!-- Page Content -->
                    ${renderPageSections(page, pageIndex, primary, secondary, accent, textColor, background)}
                    
                    <!-- Footer -->
                    <footer style="background: ${footerBg}; color: #fff; padding: 50px 20px 20px;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto 30px;">
                            <div>
                                <h4 style="margin-bottom: 15px; font-size: 18px;">About Us</h4>
                                <p style="opacity: 0.8; font-size: 14px; line-height: 1.7;">Providing quality healthcare services with compassion and excellence.</p>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 15px; font-size: 18px;">Quick Links</h4>
                                <p style="opacity: 0.8; font-size: 14px; line-height: 2;">Home<br>Services<br>Doctors<br>Contact</p>
                            </div>
                            <div>
                                <h4 style="margin-bottom: 15px; font-size: 18px;">Contact</h4>
                                <p style="opacity: 0.8; font-size: 14px; line-height: 1.7;">📍 123 Medical Center Dr<br>📞 ${phone}<br>✉️ ${email}</p>
                            </div>
                        </div>
                        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; text-align: center; opacity: 0.7; font-size: 13px;">
                            © 2024 ${logoText}. All rights reserved.
                        </div>
                    </footer>
                </div>
            `;
            
            $('#live-preview').html(previewHtml);
        }
        
        function getContentForSection(pageIndex, secIndex) {
            const prefix = `page_${pageIndex}_sec_${secIndex}_`;
            const content = {};
            Object.keys(pageContents).forEach(key => {
                if (key.startsWith(prefix)) {
                    const fieldName = key.replace(prefix, '');
                    content[fieldName] = pageContents[key];
                }
            });
            return content;
        }
        
        function renderPageSections(page, pageIndex, primary, secondary, accent, textColor, background) {
            let html = '';
            
            if (!page.sections) return html;
            
            page.sections.forEach((section, secIndex) => {
                const content = getContentForSection(pageIndex, secIndex);
                
                switch (section.section_type) {
                    case 'hero':
                        const headline = content.headline || 'Your Health, Our Priority';
                        const subheadline = content.subheadline || 'Providing compassionate, world-class healthcare services.';
                        const ctaText = content.cta_text || 'Book Appointment';
                        const cta2Text = content.cta2_text || 'Our Services';
                        
                        html += `
                            <div style="background: linear-gradient(135deg, ${primary} 0%, ${secondary} 100%); padding: 80px 20px; text-align: center; color: #fff;">
                                <h1 style="font-size: 36px; font-weight: 700; margin-bottom: 15px; max-width: 700px; margin-left: auto; margin-right: auto;">${headline}</h1>
                                <p style="font-size: 18px; opacity: 0.9; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.7;">${subheadline}</p>
                                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                                    <a href="#" style="background: #fff; color: ${primary}; padding: 14px 30px; border-radius: 30px; text-decoration: none; font-weight: 600;">${ctaText}</a>
                                    <a href="#" style="background: transparent; color: #fff; padding: 14px 30px; border-radius: 30px; text-decoration: none; font-weight: 600; border: 2px solid #fff;">${cta2Text}</a>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'info_cards':
                        html += `
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; max-width: 1200px; margin: -40px auto 40px; padding: 0 20px; position: relative; z-index: 10;">
                                <div style="background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); display: flex; gap: 15px;">
                                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, ${primary}, ${accent}); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px;">🕐</div>
                                    <div>
                                        <h3 style="font-size: 16px; margin-bottom: 5px;">${content.card1_title || '24/7 Emergency'}</h3>
                                        <p style="font-size: 13px; color: #666;">${content.card1_text || 'Round-the-clock emergency care.'}</p>
                                    </div>
                                </div>
                                <div style="background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); display: flex; gap: 15px;">
                                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, ${primary}, ${accent}); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px;">📅</div>
                                    <div>
                                        <h3 style="font-size: 16px; margin-bottom: 5px;">${content.card2_title || 'Easy Appointments'}</h3>
                                        <p style="font-size: 13px; color: #666;">${content.card2_text || 'Book appointments online or by phone.'}</p>
                                    </div>
                                </div>
                                <div style="background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); display: flex; gap: 15px;">
                                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, ${primary}, ${accent}); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px;">🛡️</div>
                                    <div>
                                        <h3 style="font-size: 16px; margin-bottom: 5px;">${content.card3_title || 'Quality Care'}</h3>
                                        <p style="font-size: 13px; color: #666;">${content.card3_text || 'Accredited facility with certified professionals.'}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'services':
                        const servicesTitle = content.section_title || 'Our Medical Services';
                        html += `
                            <div style="padding: 60px 20px; background: ${background};">
                                <h2 style="text-align: center; font-size: 32px; color: ${primary}; margin-bottom: 40px;">${servicesTitle}</h2>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; max-width: 1200px; margin: 0 auto;">
                                    ${['Emergency Care', 'Surgery', 'Internal Medicine', 'Pediatrics', 'Cardiology', 'Orthopedics'].map(service => `
                                        <div style="background: #fff; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, ${primary}, ${secondary}); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #fff; font-size: 28px;">❤️</div>
                                            <h3 style="font-size: 18px; margin-bottom: 10px;">${service}</h3>
                                            <p style="color: #666; font-size: 14px;">Quality care for your health needs.</p>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'stats':
                        html += `
                            <div style="background: linear-gradient(135deg, ${primary}, ${secondary}); padding: 60px 20px; color: #fff;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 30px; max-width: 1000px; margin: 0 auto; text-align: center;">
                                    <div>
                                        <div style="font-size: 42px; font-weight: 700;">${content.stat1_number || '50+'}</div>
                                        <div style="opacity: 0.9;">${content.stat1_label || 'Years Experience'}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 42px; font-weight: 700;">${content.stat2_number || '200+'}</div>
                                        <div style="opacity: 0.9;">${content.stat2_label || 'Expert Doctors'}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 42px; font-weight: 700;">${content.stat3_number || '100K+'}</div>
                                        <div style="opacity: 0.9;">${content.stat3_label || 'Patients Served'}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 42px; font-weight: 700;">${content.stat4_number || '50+'}</div>
                                        <div style="opacity: 0.9;">${content.stat4_label || 'Specialties'}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'page_header':
                        html += `
                            <div style="background: linear-gradient(135deg, ${primary}, ${secondary}); padding: 60px 20px; text-align: center; color: #fff;">
                                <h1 style="font-size: 36px; font-weight: 700; margin-bottom: 10px;">${content.title || section.section_name}</h1>
                                <p style="opacity: 0.8;">${content.breadcrumb || 'Home / ' + (content.title || section.section_name)}</p>
                            </div>
                        `;
                        break;
                        
                    case 'content':
                        html += `
                            <div style="padding: 60px 20px; max-width: 900px; margin: 0 auto;">
                                <h2 style="font-size: 28px; color: ${primary}; margin-bottom: 20px;">${content.title || 'About Us'}</h2>
                                <p style="color: ${textColor}; line-height: 1.8; font-size: 16px;">${content.content || 'We are committed to providing excellent healthcare services to our community.'}</p>
                            </div>
                        `;
                        break;
                        
                    case 'contact_info':
                        html += `
                            <div style="padding: 60px 20px; background: ${background};">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; max-width: 1000px; margin: 0 auto;">
                                    <div style="background: #fff; padding: 30px; border-radius: 15px; display: flex; gap: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                        <div style="width: 50px; height: 50px; background: ${primary}; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px;">📍</div>
                                        <div>
                                            <h4 style="margin-bottom: 5px;">Address</h4>
                                            <p style="color: #666; font-size: 14px;">${(content.address || '123 Medical Center Drive').replace(/\n/g, '<br>')}</p>
                                        </div>
                                    </div>
                                    <div style="background: #fff; padding: 30px; border-radius: 15px; display: flex; gap: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                        <div style="width: 50px; height: 50px; background: ${primary}; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px;">📞</div>
                                        <div>
                                            <h4 style="margin-bottom: 5px;">Phone</h4>
                                            <p style="color: #666; font-size: 14px;">${content.phone || '+1 (555) 123-4567'}<br>Emergency: ${content.emergency || '+1 (555) 999-0000'}</p>
                                        </div>
                                    </div>
                                    <div style="background: #fff; padding: 30px; border-radius: 15px; display: flex; gap: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                        <div style="width: 50px; height: 50px; background: ${primary}; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px;">🕐</div>
                                        <div>
                                            <h4 style="margin-bottom: 5px;">Hours</h4>
                                            <p style="color: #666; font-size: 14px;">${(content.hours || 'Mon-Fri: 8AM - 8PM').replace(/\n/g, '<br>')}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'cta':
                        html += `
                            <div style="background: linear-gradient(135deg, ${accent}, ${primary}); padding: 60px 20px; text-align: center; color: #fff;">
                                <h2 style="font-size: 32px; margin-bottom: 15px;">${content.cta_headline || 'Need Emergency Care?'}</h2>
                                <p style="font-size: 18px; opacity: 0.9; margin-bottom: 25px;">${content.cta_text || 'Our emergency department is open 24/7.'}</p>
                                <a href="#" style="background: #fff; color: ${primary}; padding: 15px 35px; border-radius: 30px; text-decoration: none; font-weight: 600; font-size: 16px;">${content.cta_button || 'Call Now'}</a>
                            </div>
                        `;
                        break;
                        
                    case 'blog':
                        html += `
                            <div style="padding: 60px 20px; background: ${background};">
                                <h2 style="text-align: center; font-size: 32px; color: ${primary}; margin-bottom: 40px;">Health Blog</h2>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; max-width: 1200px; margin: 0 auto;">
                                    ${['Health Tips', 'Preventive Care', 'Wellness Guide'].map((title, i) => `
                                        <div style="background: #fff; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                            <div style="height: 160px; background: linear-gradient(135deg, ${primary}, ${accent}); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 40px;">📰</div>
                                            <div style="padding: 25px;">
                                                <div style="font-size: 12px; color: #999; margin-bottom: 10px;">Dec ${15 + i}, 2024 • Health</div>
                                                <h3 style="font-size: 18px; margin-bottom: 10px;">${title}</h3>
                                                <p style="color: #666; font-size: 14px; line-height: 1.6;">Expert advice for better health...</p>
                                                <a href="#" style="color: ${primary}; font-weight: 600; text-decoration: none; display: inline-block; margin-top: 15px;">Read More →</a>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                        break;
                        
                    default:
                        if (section.section_type !== 'header' && section.section_type !== 'footer') {
                            html += `
                                <div style="padding: 40px 20px;">
                                    <h3 style="color: ${primary}; margin-bottom: 15px;">${section.section_name}</h3>
                                    <p style="color: #666;">Content section</p>
                                </div>
                            `;
                        }
                }
            });
            
            return html;
        }
        
        function submitOrder() {
            const customerName = $('#customer-name').val();
            const customerEmail = $('#customer-email').val();
            
            if (!customerName || !customerEmail) {
                alert('Please enter your name and email.');
                return;
            }
            
            collectContentData();
            
            const data = {
                action: 'spfm_customer_submit',
                nonce: nonce,
                token: token,
                form_id: formId,
                selected_theme_id: selectedThemeId,
                page_contents: JSON.stringify(pageContents),
                color_customizations: JSON.stringify(colorCustomizations),
                customer_name: customerName,
                customer_email: customerEmail,
                customer_phone: $('#customer-phone').val()
            };
            
            $('.btn-submit').text('Submitting...').prop('disabled', true);
            
            $.post(ajaxUrl, data, function(response) {
                if (response.success) {
                    $('.step-section').removeClass('active');
                    $('#step-success').addClass('active');
                    $('.progress-steps').hide();
                } else {
                    alert(response.data.message || 'Failed to submit. Please try again.');
                    $('.btn-submit').html('<i class="bi bi-check-lg"></i> Submit Website Order').prop('disabled', false);
                }
            }).fail(function() {
                alert('An error occurred. Please try again.');
                $('.btn-submit').html('<i class="bi bi-check-lg"></i> Submit Website Order').prop('disabled', false);
            });
        }
    </script>
</body>
</html>
