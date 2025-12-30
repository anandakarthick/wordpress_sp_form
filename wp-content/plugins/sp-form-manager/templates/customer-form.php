<?php
/**
 * Customer Form Template - Hospital Website Template Selector
 * Full Content Customization Support
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

// Get complete theme data with pages and sections
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0891b2;
            --secondary: #0e7490;
            --accent: #06b6d4;
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
        .section-title p { color: #64748b; }
        
        /* Theme Selection Grid */
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
        .mockup-logo { font-weight: 700; }
        .mockup-nav { display: flex; gap: 12px; font-size: 9px; color: #64748b; }
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
        .mockup-hero h3 { font-size: 12px; font-weight: 700; margin-bottom: 5px; }
        .mockup-hero p { font-size: 8px; opacity: 0.9; }
        .mockup-content { padding: 12px; display: flex; gap: 8px; }
        .mockup-card { flex: 1; height: 45px; background: #f1f5f9; border-radius: 6px; }
        .mockup-footer { height: 25px; }
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
        .theme-info { padding: 25px; }
        .theme-category {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .theme-info h3 { font-size: 20px; font-weight: 700; margin-bottom: 8px; color: #1e293b; }
        .theme-info p { color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 15px; }
        .theme-features { display: flex; flex-wrap: wrap; gap: 6px; }
        .theme-feature {
            font-size: 11px;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 15px;
            color: #475569;
        }
        
        /* Content Editor - Step 2 */
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
        .page-nav-item i { font-size: 18px; color: var(--primary); }
        .page-nav-item span { flex: 1; font-weight: 500; }
        
        /* Page Content Editor */
        .page-content-editor {
            background: #fff;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            display: none;
        }
        .page-content-editor.active { display: block; }
        .page-header-bar {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }
        .page-header-bar h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .page-header-bar h2 i { color: var(--primary); }
        .page-header-bar p { color: #64748b; margin: 0; }
        
        /* Section Cards */
        .section-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }
        .section-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .section-card-header h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }
        .section-toggle {
            width: 30px;
            height: 30px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s;
        }
        .section-card.collapsed .section-toggle { transform: rotate(-90deg); }
        .section-card.collapsed .section-card-body { display: none; }
        
        /* Form Fields */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-row.three-col {
            grid-template-columns: 1fr 1fr 1fr;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #334155;
        }
        .form-group label .required { color: #ef4444; }
        .form-group label .hint {
            font-weight: 400;
            color: #94a3b8;
            font-size: 12px;
        }
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
        textarea.form-control { resize: vertical; min-height: 100px; }
        
        /* Image Upload */
        .image-upload-box {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #fff;
            position: relative;
        }
        .image-upload-box:hover {
            border-color: var(--primary);
            background: #f0fdfa;
        }
        .image-upload-box.has-image {
            padding: 10px;
            border-style: solid;
            border-color: var(--primary);
        }
        .image-upload-box i {
            font-size: 40px;
            color: #cbd5e1;
            margin-bottom: 10px;
        }
        .image-upload-box p { color: #64748b; margin: 0; font-size: 14px; }
        .image-upload-box input[type="file"] { display: none; }
        .image-upload-box .preview-img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
        }
        .image-upload-box .remove-image {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
        }
        .image-upload-box.has-image .remove-image { display: flex; align-items: center; justify-content: center; }
        .image-upload-box.has-image i,
        .image-upload-box.has-image p { display: none; }
        
        /* Repeater Fields */
        .repeater-container {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .repeater-item {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
        }
        .repeater-item:last-child { border-bottom: none; }
        .repeater-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .repeater-item-title {
            font-weight: 600;
            color: #1e293b;
        }
        .remove-repeater-item {
            width: 32px;
            height: 32px;
            background: #fee2e2;
            color: #ef4444;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .add-repeater-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 15px;
            background: #f8fafc;
            border: none;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            color: var(--primary);
            transition: all 0.3s;
        }
        .add-repeater-item:hover { background: #f1f5f9; }
        
        /* Color Customization */
        .color-customization {
            background: #fff;
            border-radius: 20px;
            padding: 35px;
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
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
        .color-picker-wrapper {
            position: relative;
            display: inline-block;
        }
        .color-picker-wrapper input[type="color"] {
            width: 70px;
            height: 50px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .color-hex {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-top: 8px;
        }
        
        /* Preview */
        .preview-container {
            display: grid;
            grid-template-columns: 300px 1fr;
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
            margin-bottom: 20px;
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
        .preview-page-btn.active { background: var(--primary); color: #fff; }
        .customer-info-form .form-group { margin-bottom: 15px; }
        
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
        .btn-prev { background: #f1f5f9; color: #475569; }
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
        .success-container h2 { color: #10b981; font-size: 32px; margin-bottom: 15px; }
        .success-container p { color: #64748b; font-size: 18px; line-height: 1.7; }
        
        /* Responsive */
        @media (max-width: 992px) {
            .content-editor, .preview-container { grid-template-columns: 1fr; }
            .pages-sidebar, .preview-sidebar { position: static; }
            .form-row { grid-template-columns: 1fr; }
            .form-row.three-col { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .themes-grid { grid-template-columns: 1fr; }
            .site-header h1 { font-size: 24px; }
            .step span { display: none; }
            .color-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <h1><?php echo esc_html($form->header_text ?: $form->name); ?></h1>
        <p>Select your hospital website template, customize all content with your information, and submit your order.</p>
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
                            <div class="select-badge"><i class="bi bi-check-lg"></i></div>
                            <div class="theme-mockup">
                                <div class="mockup-header">
                                    <span class="mockup-logo" style="color: <?php echo esc_attr($theme->primary_color); ?>;">
                                        <?php echo esc_html(substr($theme->name, 0, 15)); ?>
                                    </span>
                                    <div class="mockup-nav">
                                        <span>Home</span><span>About</span><span>Services</span><span>Contact</span>
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
                <p>Enter your hospital/clinic information for each page. All fields can be customized with your own content.</p>
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
                <p>Personalize the color scheme to match your brand identity</p>
            </div>
            
            <div class="color-customization">
                <h3><i class="bi bi-palette"></i> Color Palette</h3>
                <div class="color-grid">
                    <div class="color-picker-group">
                        <label>Primary Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" id="custom-primary" value="#0891b2">
                            <span class="color-hex" id="hex-primary">#0891b2</span>
                        </div>
                    </div>
                    <div class="color-picker-group">
                        <label>Secondary Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" id="custom-secondary" value="#0e7490">
                            <span class="color-hex" id="hex-secondary">#0e7490</span>
                        </div>
                    </div>
                    <div class="color-picker-group">
                        <label>Accent Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" id="custom-accent" value="#06b6d4">
                            <span class="color-hex" id="hex-accent">#06b6d4</span>
                        </div>
                    </div>
                    <div class="color-picker-group">
                        <label>Background</label>
                        <div class="color-picker-wrapper">
                            <input type="color" id="custom-background" value="#f0fdfa">
                            <span class="color-hex" id="hex-background">#f0fdfa</span>
                        </div>
                    </div>
                    <div class="color-picker-group">
                        <label>Text Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" id="custom-text" value="#1e293b">
                            <span class="color-hex" id="hex-text">#1e293b</span>
                        </div>
                    </div>
                    <div class="color-picker-group">
                        <label>Header Background</label>
                        <div class="color-picker-wrapper">
                            <input type="color" id="custom-header-bg" value="#ffffff">
                            <span class="color-hex" id="hex-header-bg">#ffffff</span>
                        </div>
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
                <h2>Preview & Submit Your Order</h2>
                <p>Review your customized website and submit your order</p>
            </div>
            
            <div class="preview-container">
                <div class="preview-sidebar">
                    <div class="preview-controls">
                        <h4>Preview Pages</h4>
                        <div id="preview-pages-nav"></div>
                    </div>
                    
                    <div class="preview-controls customer-info-form">
                        <h4>Your Contact Information</h4>
                        <div class="form-group">
                            <label>Your Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="customer-name" required placeholder="John Doe">
                        </div>
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" class="form-control" id="customer-email" required placeholder="john@example.com">
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
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
                <div class="success-icon"><i class="bi bi-check-lg"></i></div>
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
        
        const themesData = <?php echo json_encode($themes_complete); ?>;
        
        let selectedThemeId = null;
        let currentStep = 1;
        let pageContents = {};
        let colorCustomizations = {};
        let uploadedImages = {};
        
        $(document).ready(function() {
            // Theme selection
            $('.theme-card').on('click', function() {
                $('.theme-card').removeClass('selected');
                $(this).addClass('selected');
                selectedThemeId = $(this).data('theme-id');
                $('#step1-next').prop('disabled', false);
                
                // Set default colors from theme
                const theme = themesData[selectedThemeId];
                if (theme) {
                    setColorValue('primary', theme.primary_color);
                    setColorValue('secondary', theme.secondary_color);
                    setColorValue('accent', theme.accent_color || theme.primary_color);
                    setColorValue('background', theme.background_color);
                    setColorValue('text', theme.text_color);
                    setColorValue('header-bg', theme.header_bg_color || '#ffffff');
                    
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
            
            // Color picker changes
            $('input[type="color"]').on('input', function() {
                const id = $(this).attr('id');
                const value = $(this).val();
                const key = id.replace('custom-', '').replace(/-/g, '_');
                
                colorCustomizations[key + '_color'] = value;
                $(`#hex-${id.replace('custom-', '')}`).text(value);
                
                if (key === 'primary') {
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
        
        function setColorValue(name, value) {
            $(`#custom-${name}`).val(value);
            $(`#hex-${name}`).text(value);
        }
        
        function goToStep(step) {
            if (step === 2 && !selectedThemeId) {
                alert('Please select a template first.');
                return;
            }
            
            // Update progress
            $('.step').removeClass('active completed');
            for (let i = 1; i < step; i++) {
                $(`.step[data-step="${i}"]`).addClass('completed');
            }
            $(`.step[data-step="${step}"]`).addClass('active');
            
            // Show step content
            $('.step-section').removeClass('active');
            $(`#step-${step}`).addClass('active');
            currentStep = step;
            
            // Load content for specific steps
            if (step === 2) loadContentEditor();
            if ((allowColorCustomization && step === 4) || (!allowColorCustomization && step === 3)) loadPreview();
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function loadContentEditor() {
            const theme = themesData[selectedThemeId];
            if (!theme || !theme.pages) return;
            
            // Build pages navigation
            let pagesNav = '';
            theme.pages.forEach((page, index) => {
                const icon = getPageIcon(page.page_icon);
                pagesNav += `
                    <div class="page-nav-item ${index === 0 ? 'active' : ''}" data-page-index="${index}" onclick="showPageEditor(${index})">
                        <i class="bi bi-${icon}"></i>
                        <span>${page.page_name}</span>
                    </div>
                `;
            });
            $('#pages-nav').html(pagesNav);
            
            // Build content forms for each page
            let contentForms = '';
            theme.pages.forEach((page, pageIndex) => {
                const icon = getPageIcon(page.page_icon);
                contentForms += `
                    <div class="page-content-editor ${pageIndex === 0 ? 'active' : ''}" id="page-editor-${pageIndex}">
                        <div class="page-header-bar">
                            <h2><i class="bi bi-${icon}"></i> ${page.page_name}</h2>
                            <p>${page.page_description || 'Customize the content for this page with your own information.'}</p>
                        </div>
                        ${buildPageSections(page, pageIndex)}
                    </div>
                `;
            });
            $('#content-forms').html(contentForms);
            
            // Initialize image upload handlers
            initImageUploads();
        }
        
        function buildPageSections(page, pageIndex) {
            if (!page.sections || page.sections.length === 0) {
                return '<div class="section-card"><p style="color: #64748b; text-align: center; padding: 30px;">No customizable sections for this page.</p></div>';
            }
            
            let html = '';
            page.sections.forEach((section, secIndex) => {
                const sectionIcon = getSectionIcon(section.section_type);
                html += `
                    <div class="section-card" id="section-${pageIndex}-${secIndex}">
                        <div class="section-card-header" onclick="toggleSection(this)">
                            <h4><i class="bi bi-${sectionIcon}"></i> ${section.section_name}</h4>
                            <div class="section-toggle"><i class="bi bi-chevron-down"></i></div>
                        </div>
                        <div class="section-card-body">
                            ${buildSectionFields(section, pageIndex, secIndex)}
                        </div>
                    </div>
                `;
            });
            return html;
        }
        
        function buildSectionFields(section, pageIndex, secIndex) {
            if (!section.fields || section.fields.length === 0) return '';
            
            const defaults = section.default_values || {};
            let html = '';
            
            // Group fields for better layout
            let currentRow = [];
            
            section.fields.forEach((field, fieldIndex) => {
                if (field.type === 'repeater') {
                    // Handle repeater fields separately
                    if (currentRow.length > 0) {
                        html += renderFieldRow(currentRow);
                        currentRow = [];
                    }
                    html += buildRepeaterField(field, pageIndex, secIndex, defaults);
                    return;
                }
                
                const fieldHtml = buildField(field, pageIndex, secIndex, defaults[field.name] || '');
                
                // Full width for textarea, editor, image
                if (['textarea', 'editor', 'image'].includes(field.type)) {
                    if (currentRow.length > 0) {
                        html += renderFieldRow(currentRow);
                        currentRow = [];
                    }
                    html += `<div class="form-group">${fieldHtml}</div>`;
                } else {
                    currentRow.push(fieldHtml);
                    if (currentRow.length === 2) {
                        html += renderFieldRow(currentRow);
                        currentRow = [];
                    }
                }
            });
            
            // Render remaining fields
            if (currentRow.length > 0) {
                html += renderFieldRow(currentRow);
            }
            
            return html;
        }
        
        function renderFieldRow(fields) {
            if (fields.length === 1) {
                return `<div class="form-group">${fields[0]}</div>`;
            }
            return `<div class="form-row">${fields.map(f => `<div class="form-group">${f}</div>`).join('')}</div>`;
        }
        
        function buildField(field, pageIndex, secIndex, defaultValue) {
            const fieldName = `field_${pageIndex}_${secIndex}_${field.name}`;
            const required = field.required ? '<span class="required">*</span>' : '';
            const escapedDefault = escapeHtml(defaultValue);
            
            let html = `<label>${field.label} ${required}</label>`;
            
            switch (field.type) {
                case 'text':
                case 'email':
                case 'url':
                case 'tel':
                    html += `<input type="${field.type}" class="form-control content-field" name="${fieldName}" 
                             value="${escapedDefault}" placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" 
                             ${field.required ? 'required' : ''}>`;
                    break;
                    
                case 'textarea':
                    html += `<textarea class="form-control content-field" name="${fieldName}" rows="4" 
                             placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" 
                             ${field.required ? 'required' : ''}>${escapedDefault}</textarea>`;
                    break;
                    
                case 'editor':
                    html += `<textarea class="form-control content-field" name="${fieldName}" rows="6" 
                             placeholder="${field.placeholder || 'Enter detailed content here...'}" 
                             ${field.required ? 'required' : ''}>${escapedDefault}</textarea>`;
                    break;
                    
                case 'image':
                    html += `
                        <div class="image-upload-box" onclick="triggerImageUpload('${fieldName}')" id="upload-box-${fieldName}">
                            <i class="bi bi-cloud-upload"></i>
                            <p>Click to upload ${field.label.toLowerCase()}</p>
                            <input type="file" id="file-${fieldName}" class="image-field" accept="image/*" 
                                   onchange="handleImageUpload(this, '${fieldName}')">
                            <img class="preview-img" id="preview-${fieldName}" src="" alt="">
                            <button type="button" class="remove-image" onclick="removeImage(event, '${fieldName}')">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    `;
                    break;
                    
                case 'icon':
                    html += `<input type="text" class="form-control content-field" name="${fieldName}" 
                             value="${escapedDefault}" placeholder="e.g., heart, clock, shield, phone">
                             <small class="text-muted">Enter icon name (heart, clock, shield, calendar, etc.)</small>`;
                    break;
                    
                default:
                    html += `<input type="text" class="form-control content-field" name="${fieldName}" 
                             value="${escapedDefault}" placeholder="${field.placeholder || ''}" 
                             ${field.required ? 'required' : ''}>`;
            }
            
            return html;
        }
        
        function buildRepeaterField(field, pageIndex, secIndex, defaults) {
            const fieldName = `field_${pageIndex}_${secIndex}_${field.name}`;
            const defaultItems = defaults[field.name] || [];
            
            let html = `
                <div class="form-group">
                    <label>${field.label}</label>
                    <div class="repeater-container" id="repeater-${fieldName}">
            `;
            
            // Add default items or one empty item
            const items = defaultItems.length > 0 ? defaultItems : [{}];
            items.forEach((item, itemIndex) => {
                html += buildRepeaterItem(field, fieldName, itemIndex, item);
            });
            
            html += `
                        <button type="button" class="add-repeater-item" onclick="addRepeaterItem('${fieldName}', ${JSON.stringify(field.fields).replace(/"/g, '&quot;')})">
                            <i class="bi bi-plus-circle"></i> Add ${field.label.replace(/s$/, '')}
                        </button>
                    </div>
                </div>
            `;
            
            return html;
        }
        
        function buildRepeaterItem(field, fieldName, itemIndex, itemData = {}) {
            let html = `
                <div class="repeater-item" data-index="${itemIndex}">
                    <div class="repeater-item-header">
                        <span class="repeater-item-title">${field.label.replace(/s$/, '')} #${itemIndex + 1}</span>
                        <button type="button" class="remove-repeater-item" onclick="removeRepeaterItem(this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="form-row">
            `;
            
            if (field.fields) {
                field.fields.forEach((subField, subIndex) => {
                    const subFieldName = `${fieldName}_${itemIndex}_${subField.name}`;
                    const subValue = itemData[subField.name] || '';
                    html += `
                        <div class="form-group">
                            <label>${subField.label}</label>
                            <input type="${subField.type === 'textarea' ? 'text' : subField.type || 'text'}" 
                                   class="form-control content-field" 
                                   name="${subFieldName}" 
                                   value="${escapeHtml(subValue)}" 
                                   placeholder="Enter ${subField.label.toLowerCase()}">
                        </div>
                    `;
                });
            }
            
            html += `</div></div>`;
            return html;
        }
        
        function addRepeaterItem(fieldName, fields) {
            const container = $(`#repeater-${fieldName}`);
            const currentItems = container.find('.repeater-item').length;
            
            const field = { label: 'Item', fields: fields };
            const newItem = buildRepeaterItem(field, fieldName, currentItems, {});
            
            container.find('.add-repeater-item').before(newItem);
        }
        
        function removeRepeaterItem(btn) {
            $(btn).closest('.repeater-item').remove();
        }
        
        function toggleSection(header) {
            $(header).closest('.section-card').toggleClass('collapsed');
        }
        
        function showPageEditor(index) {
            $('.page-nav-item').removeClass('active');
            $(`.page-nav-item[data-page-index="${index}"]`).addClass('active');
            $('.page-content-editor').removeClass('active');
            $(`#page-editor-${index}`).addClass('active');
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
        
        function getSectionIcon(sectionType) {
            const mapping = {
                'header': 'window-dock',
                'hero': 'star',
                'info_cards': 'grid-3x3-gap',
                'services': 'heart-pulse',
                'services_list': 'list-check',
                'features': 'check2-circle',
                'stats': 'graph-up',
                'cta': 'megaphone',
                'footer': 'window-sidebar',
                'page_header': 'type-h1',
                'content': 'text-paragraph',
                'mission': 'bullseye',
                'history': 'clock-history',
                'awards': 'award',
                'team': 'people',
                'contact_info': 'geo-alt',
                'map': 'map',
                'blog': 'newspaper',
                'gallery': 'images',
                'tests': 'clipboard2-pulse'
            };
            return mapping[sectionType] || 'layers';
        }
        
        function triggerImageUpload(fieldName) {
            document.getElementById(`file-${fieldName}`).click();
        }
        
        function handleImageUpload(input, fieldName) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(`#preview-${fieldName}`).attr('src', e.target.result);
                    $(`#upload-box-${fieldName}`).addClass('has-image');
                    uploadedImages[fieldName] = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function removeImage(event, fieldName) {
            event.stopPropagation();
            $(`#preview-${fieldName}`).attr('src', '');
            $(`#upload-box-${fieldName}`).removeClass('has-image');
            $(`#file-${fieldName}`).val('');
            delete uploadedImages[fieldName];
        }
        
        function initImageUploads() {
            // Already handled by inline handlers
        }
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function collectContentData() {
            pageContents = {};
            
            // Collect all form field values
            $('.content-field').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    pageContents[name] = $(this).val();
                }
            });
            
            // Add uploaded images
            Object.keys(uploadedImages).forEach(key => {
                pageContents[`image_${key}`] = uploadedImages[key];
            });
            
            return pageContents;
        }
        
        function loadPreview() {
            collectContentData();
            
            const theme = themesData[selectedThemeId];
            if (!theme) return;
            
            // Build preview pages navigation
            let pagesNav = '';
            theme.pages.forEach((page, index) => {
                pagesNav += `
                    <button class="preview-page-btn ${index === 0 ? 'active' : ''}" onclick="showPreviewPage(${index}, this)">
                        ${page.page_name}
                    </button>
                `;
            });
            $('#preview-pages-nav').html(pagesNav);
            
            // Render first page preview
            renderPagePreview(0);
        }
        
        function showPreviewPage(index, btn) {
            $('.preview-page-btn').removeClass('active');
            $(btn).addClass('active');
            renderPagePreview(index);
        }
        
        function getFieldValue(pageIndex, secIndex, fieldName, fallback = '') {
            const key = `field_${pageIndex}_${secIndex}_${fieldName}`;
            return pageContents[key] || fallback;
        }
        
        function renderPagePreview(pageIndex) {
            const theme = themesData[selectedThemeId];
            const page = theme.pages[pageIndex];
            
            // Get colors
            const primary = colorCustomizations.primary_color || theme.primary_color;
            const secondary = colorCustomizations.secondary_color || theme.secondary_color;
            const accent = colorCustomizations.accent_color || theme.accent_color || primary;
            const background = colorCustomizations.background_color || theme.background_color;
            const textColor = colorCustomizations.text_color || theme.text_color;
            const headerBg = colorCustomizations.header_bg_color || theme.header_bg_color || '#ffffff';
            const footerBg = theme.footer_bg_color || '#0f172a';
            
            // Get header values from first page, first section (usually header)
            const logoText = getFieldValue(0, 0, 'logo_text', theme.name);
            const phone = getFieldValue(0, 0, 'phone', '+1 (555) 123-4567');
            const email = getFieldValue(0, 0, 'email', 'info@hospital.com');
            const emergency = getFieldValue(0, 0, 'emergency_number', '+1 (555) 999-0000');
            
            let previewHtml = `
                <div style="font-family: ${theme.font_family || 'Inter'}, sans-serif; background: ${background}; min-height: 100%;">
                    <!-- Top Bar -->
                    <div style="background: ${primary}; color: #fff; padding: 8px 20px; font-size: 12px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <span>📞 ${phone} | ✉️ ${email}</span>
                        <span>🚨 Emergency: ${emergency}</span>
                    </div>
                    
                    <!-- Header -->
                    <header style="background: ${headerBg}; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); flex-wrap: wrap; gap: 15px;">
                        <div style="font-size: 20px; font-weight: 700; color: ${primary};">${logoText}</div>
                        <nav style="display: flex; gap: 20px; font-size: 14px; flex-wrap: wrap;">
                            ${theme.pages.map(p => `<a href="#" style="color: ${textColor}; text-decoration: none;">${p.page_name}</a>`).join('')}
                        </nav>
                        <a href="#" style="background: ${primary}; color: #fff; padding: 10px 20px; border-radius: 25px; text-decoration: none; font-size: 13px; font-weight: 600;">Book Appointment</a>
                    </header>
                    
                    <!-- Page Content -->
                    ${renderPreviewSections(page, pageIndex, primary, secondary, accent, textColor, background, footerBg)}
                    
                    <!-- Footer -->
                    ${renderFooter(primary, footerBg, logoText, phone, email)}
                </div>
            `;
            
            $('#live-preview').html(previewHtml);
        }
        
        function renderPreviewSections(page, pageIndex, primary, secondary, accent, textColor, background, footerBg) {
            let html = '';
            
            if (!page.sections) return html;
            
            page.sections.forEach((section, secIndex) => {
                switch (section.section_type) {
                    case 'hero':
                        const headline = getFieldValue(pageIndex, secIndex, 'headline', 'Your Health, Our Priority');
                        const subheadline = getFieldValue(pageIndex, secIndex, 'subheadline', 'Providing compassionate, world-class healthcare services with state-of-the-art facilities.');
                        const ctaText = getFieldValue(pageIndex, secIndex, 'cta_text', 'Book Appointment');
                        const cta2Text = getFieldValue(pageIndex, secIndex, 'cta2_text', 'Our Services');
                        
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
                        const card1Title = getFieldValue(pageIndex, secIndex, 'card1_title', '24/7 Emergency');
                        const card1Text = getFieldValue(pageIndex, secIndex, 'card1_text', 'Round-the-clock emergency care with rapid response team.');
                        const card2Title = getFieldValue(pageIndex, secIndex, 'card2_title', 'Easy Appointments');
                        const card2Text = getFieldValue(pageIndex, secIndex, 'card2_text', 'Book appointments online or call us anytime.');
                        const card3Title = getFieldValue(pageIndex, secIndex, 'card3_title', 'Quality Care');
                        const card3Text = getFieldValue(pageIndex, secIndex, 'card3_text', 'Accredited facility with certified medical professionals.');
                        
                        html += `
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; max-width: 1200px; margin: -40px auto 40px; padding: 0 20px; position: relative; z-index: 10;">
                                <div style="background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); display: flex; gap: 15px;">
                                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, ${primary}, ${accent}); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex-shrink: 0;">🕐</div>
                                    <div>
                                        <h3 style="font-size: 16px; margin: 0 0 5px 0; color: ${textColor};">${card1Title}</h3>
                                        <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.5;">${card1Text}</p>
                                    </div>
                                </div>
                                <div style="background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); display: flex; gap: 15px;">
                                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, ${primary}, ${accent}); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex-shrink: 0;">📅</div>
                                    <div>
                                        <h3 style="font-size: 16px; margin: 0 0 5px 0; color: ${textColor};">${card2Title}</h3>
                                        <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.5;">${card2Text}</p>
                                    </div>
                                </div>
                                <div style="background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); display: flex; gap: 15px;">
                                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, ${primary}, ${accent}); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex-shrink: 0;">🛡️</div>
                                    <div>
                                        <h3 style="font-size: 16px; margin: 0 0 5px 0; color: ${textColor};">${card3Title}</h3>
                                        <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.5;">${card3Text}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'services':
                    case 'services_list':
                        const servicesTitle = getFieldValue(pageIndex, secIndex, 'section_title', 'Our Medical Services');
                        const servicesSubtitle = getFieldValue(pageIndex, secIndex, 'section_subtitle', 'Comprehensive healthcare solutions for you and your family');
                        
                        html += `
                            <div style="padding: 60px 20px; background: ${background};">
                                <div style="text-align: center; margin-bottom: 40px;">
                                    <h2 style="font-size: 32px; color: ${primary}; margin: 0 0 10px 0;">${servicesTitle}</h2>
                                    <p style="color: #666; font-size: 16px; margin: 0;">${servicesSubtitle}</p>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; max-width: 1200px; margin: 0 auto;">
                                    ${['Emergency Care', 'Surgery', 'Internal Medicine', 'Pediatrics', 'Cardiology', 'Orthopedics'].map(service => `
                                        <div style="background: #fff; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, ${primary}, ${secondary}); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #fff; font-size: 28px;">❤️</div>
                                            <h3 style="font-size: 18px; margin: 0 0 10px 0; color: ${textColor};">${service}</h3>
                                            <p style="color: #666; font-size: 14px; margin: 0; line-height: 1.6;">Quality care for your health needs.</p>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'stats':
                        const stat1Num = getFieldValue(pageIndex, secIndex, 'stat1_number', '50+');
                        const stat1Label = getFieldValue(pageIndex, secIndex, 'stat1_label', 'Years Experience');
                        const stat2Num = getFieldValue(pageIndex, secIndex, 'stat2_number', '200+');
                        const stat2Label = getFieldValue(pageIndex, secIndex, 'stat2_label', 'Expert Doctors');
                        const stat3Num = getFieldValue(pageIndex, secIndex, 'stat3_number', '100K+');
                        const stat3Label = getFieldValue(pageIndex, secIndex, 'stat3_label', 'Patients Served');
                        const stat4Num = getFieldValue(pageIndex, secIndex, 'stat4_number', '50+');
                        const stat4Label = getFieldValue(pageIndex, secIndex, 'stat4_label', 'Specialties');
                        
                        html += `
                            <div style="background: linear-gradient(135deg, ${primary}, ${secondary}); padding: 60px 20px; color: #fff;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 30px; max-width: 1000px; margin: 0 auto; text-align: center;">
                                    <div>
                                        <div style="font-size: 42px; font-weight: 700;">${stat1Num}</div>
                                        <div style="opacity: 0.9;">${stat1Label}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 42px; font-weight: 700;">${stat2Num}</div>
                                        <div style="opacity: 0.9;">${stat2Label}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 42px; font-weight: 700;">${stat3Num}</div>
                                        <div style="opacity: 0.9;">${stat3Label}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 42px; font-weight: 700;">${stat4Num}</div>
                                        <div style="opacity: 0.9;">${stat4Label}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'page_header':
                        const pageTitle = getFieldValue(pageIndex, secIndex, 'title', page.page_name);
                        const breadcrumb = getFieldValue(pageIndex, secIndex, 'breadcrumb', 'Home / ' + page.page_name);
                        
                        html += `
                            <div style="background: linear-gradient(135deg, ${primary}, ${secondary}); padding: 60px 20px; text-align: center; color: #fff;">
                                <h1 style="font-size: 36px; font-weight: 700; margin: 0 0 10px 0;">${pageTitle}</h1>
                                <p style="opacity: 0.8; margin: 0;">${breadcrumb}</p>
                            </div>
                        `;
                        break;
                        
                    case 'content':
                        const contentTitle = getFieldValue(pageIndex, secIndex, 'title', 'About Us');
                        const contentText = getFieldValue(pageIndex, secIndex, 'content', 'We are committed to providing excellent healthcare services to our community with compassion and expertise.');
                        
                        html += `
                            <div style="padding: 60px 20px; max-width: 900px; margin: 0 auto;">
                                <h2 style="font-size: 28px; color: ${primary}; margin: 0 0 20px 0;">${contentTitle}</h2>
                                <p style="color: ${textColor}; line-height: 1.8; font-size: 16px;">${contentText.replace(/\n/g, '<br>')}</p>
                            </div>
                        `;
                        break;
                        
                    case 'mission':
                        const missionTitle = getFieldValue(pageIndex, secIndex, 'mission_title', 'Our Mission');
                        const missionText = getFieldValue(pageIndex, secIndex, 'mission_text', 'To provide exceptional healthcare services that improve the health and well-being of our community.');
                        const visionTitle = getFieldValue(pageIndex, secIndex, 'vision_title', 'Our Vision');
                        const visionText = getFieldValue(pageIndex, secIndex, 'vision_text', 'To be the most trusted healthcare provider, recognized for outstanding patient care.');
                        
                        html += `
                            <div style="padding: 60px 20px; background: ${background};">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; max-width: 1000px; margin: 0 auto;">
                                    <div style="background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                        <h3 style="color: ${primary}; margin: 0 0 15px 0;">${missionTitle}</h3>
                                        <p style="color: #666; line-height: 1.7; margin: 0;">${missionText}</p>
                                    </div>
                                    <div style="background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                        <h3 style="color: ${primary}; margin: 0 0 15px 0;">${visionTitle}</h3>
                                        <p style="color: #666; line-height: 1.7; margin: 0;">${visionText}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'contact_info':
                        const address = getFieldValue(pageIndex, secIndex, 'address', '123 Medical Center Drive\nHealthcare District\nYour City, State 12345');
                        const contactPhone = getFieldValue(pageIndex, secIndex, 'phone', '+1 (555) 123-4567');
                        const contactEmergency = getFieldValue(pageIndex, secIndex, 'emergency', '+1 (555) 999-0000');
                        const contactEmail = getFieldValue(pageIndex, secIndex, 'email', 'info@hospital.com');
                        const hours = getFieldValue(pageIndex, secIndex, 'hours', 'Monday - Friday: 8:00 AM - 8:00 PM\nSaturday: 9:00 AM - 5:00 PM\nSunday: Emergency Only');
                        
                        html += `
                            <div style="padding: 60px 20px; background: ${background};">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; max-width: 1000px; margin: 0 auto;">
                                    <div style="background: #fff; padding: 25px; border-radius: 15px; display: flex; gap: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                        <div style="width: 50px; height: 50px; background: ${primary}; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex-shrink: 0;">📍</div>
                                        <div>
                                            <h4 style="margin: 0 0 8px 0; color: ${textColor};">Address</h4>
                                            <p style="color: #666; font-size: 14px; margin: 0; line-height: 1.6;">${address.replace(/\n/g, '<br>')}</p>
                                        </div>
                                    </div>
                                    <div style="background: #fff; padding: 25px; border-radius: 15px; display: flex; gap: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                        <div style="width: 50px; height: 50px; background: ${primary}; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex-shrink: 0;">📞</div>
                                        <div>
                                            <h4 style="margin: 0 0 8px 0; color: ${textColor};">Phone</h4>
                                            <p style="color: #666; font-size: 14px; margin: 0; line-height: 1.6;">
                                                Main: ${contactPhone}<br>Emergency: ${contactEmergency}
                                            </p>
                                        </div>
                                    </div>
                                    <div style="background: #fff; padding: 25px; border-radius: 15px; display: flex; gap: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                        <div style="width: 50px; height: 50px; background: ${primary}; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex-shrink: 0;">🕐</div>
                                        <div>
                                            <h4 style="margin: 0 0 8px 0; color: ${textColor};">Working Hours</h4>
                                            <p style="color: #666; font-size: 14px; margin: 0; line-height: 1.6;">${hours.replace(/\n/g, '<br>')}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'cta':
                        const ctaHeadline = getFieldValue(pageIndex, secIndex, 'cta_headline', 'Need Emergency Care?');
                        const ctaTextContent = getFieldValue(pageIndex, secIndex, 'cta_text', 'Our emergency department is open 24/7. Don\'t wait - get the care you need now.');
                        const ctaButton = getFieldValue(pageIndex, secIndex, 'cta_button', 'Call Now');
                        
                        html += `
                            <div style="background: linear-gradient(135deg, ${accent}, ${primary}); padding: 60px 20px; text-align: center; color: #fff;">
                                <h2 style="font-size: 32px; margin: 0 0 15px 0;">${ctaHeadline}</h2>
                                <p style="font-size: 18px; opacity: 0.9; margin: 0 0 25px 0; max-width: 600px; margin-left: auto; margin-right: auto;">${ctaTextContent}</p>
                                <a href="#" style="display: inline-block; background: #fff; color: ${primary}; padding: 15px 35px; border-radius: 30px; text-decoration: none; font-weight: 600; font-size: 16px;">${ctaButton}</a>
                            </div>
                        `;
                        break;
                        
                    case 'blog':
                        html += `
                            <div style="padding: 60px 20px; background: ${background};">
                                <div style="text-align: center; margin-bottom: 40px;">
                                    <h2 style="font-size: 32px; color: ${primary}; margin: 0 0 10px 0;">Health Blog</h2>
                                    <p style="color: #666; margin: 0;">Expert health advice and tips from our medical team</p>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; max-width: 1200px; margin: 0 auto;">
                                    ${['Health Tips for Better Living', 'Understanding Preventive Care', 'Wellness Guide for Families'].map((title, i) => `
                                        <div style="background: #fff; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                            <div style="height: 160px; background: linear-gradient(135deg, ${primary}, ${accent}); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 40px;">📰</div>
                                            <div style="padding: 25px;">
                                                <div style="font-size: 12px; color: #999; margin-bottom: 10px;">Dec ${15 + i}, 2024 • Health</div>
                                                <h3 style="font-size: 18px; margin: 0 0 10px 0; color: ${textColor};">${title}</h3>
                                                <p style="color: #666; font-size: 14px; line-height: 1.6; margin: 0 0 15px 0;">Expert advice from our medical professionals...</p>
                                                <a href="#" style="color: ${primary}; font-weight: 600; text-decoration: none;">Read More →</a>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'team':
                        html += `
                            <div style="padding: 60px 20px; background: ${background};">
                                <div style="text-align: center; margin-bottom: 40px;">
                                    <h2 style="font-size: 32px; color: ${primary}; margin: 0 0 10px 0;">Our Medical Team</h2>
                                    <p style="color: #666; margin: 0;">Meet our experienced healthcare professionals</p>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; max-width: 1000px; margin: 0 auto;">
                                    ${['Dr. Sarah Johnson', 'Dr. Michael Chen', 'Dr. Emily Brown'].map((name, i) => `
                                        <div style="background: #fff; border-radius: 15px; padding: 30px; text-align: center; box-shadow: 0 5px 25px rgba(0,0,0,0.08);">
                                            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, ${primary}, ${secondary}); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #fff; font-size: 36px; font-weight: 700;">${name.charAt(4)}</div>
                                            <h3 style="font-size: 18px; margin: 0 0 5px 0; color: ${textColor};">${name}</h3>
                                            <p style="color: ${primary}; font-weight: 600; margin: 0 0 10px 0;">${['Cardiology', 'Neurology', 'Pediatrics'][i]}</p>
                                            <p style="color: #666; font-size: 14px; margin: 0;">15+ years experience</p>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                        break;
                }
            });
            
            return html;
        }
        
        function renderFooter(primary, footerBg, logoText, phone, email) {
            // Get footer values if available
            const footerAbout = getFieldValue(0, 7, 'footer_about', `${logoText} has been serving our community for decades, providing exceptional healthcare with compassion and excellence.`);
            const footerAddress = getFieldValue(0, 7, 'footer_address', '123 Medical Center Drive\nHealthcare District\nYour City, State 12345');
            const copyright = getFieldValue(0, 7, 'copyright', `© ${new Date().getFullYear()} ${logoText}. All rights reserved.`);
            
            return `
                <footer style="background: ${footerBg}; color: #fff; padding: 50px 20px 20px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto 30px;">
                        <div>
                            <h4 style="font-size: 18px; margin: 0 0 15px 0; position: relative; padding-bottom: 10px;">
                                About Us
                                <span style="position: absolute; left: 0; bottom: 0; width: 40px; height: 3px; background: ${primary};"></span>
                            </h4>
                            <p style="opacity: 0.8; font-size: 14px; line-height: 1.7; margin: 0;">${footerAbout}</p>
                        </div>
                        <div>
                            <h4 style="font-size: 18px; margin: 0 0 15px 0; position: relative; padding-bottom: 10px;">
                                Quick Links
                                <span style="position: absolute; left: 0; bottom: 0; width: 40px; height: 3px; background: ${primary};"></span>
                            </h4>
                            <p style="opacity: 0.8; font-size: 14px; line-height: 2.2; margin: 0;">
                                <a href="#" style="color: rgba(255,255,255,0.8); text-decoration: none;">Home</a><br>
                                <a href="#" style="color: rgba(255,255,255,0.8); text-decoration: none;">About Us</a><br>
                                <a href="#" style="color: rgba(255,255,255,0.8); text-decoration: none;">Services</a><br>
                                <a href="#" style="color: rgba(255,255,255,0.8); text-decoration: none;">Contact</a>
                            </p>
                        </div>
                        <div>
                            <h4 style="font-size: 18px; margin: 0 0 15px 0; position: relative; padding-bottom: 10px;">
                                Contact Info
                                <span style="position: absolute; left: 0; bottom: 0; width: 40px; height: 3px; background: ${primary};"></span>
                            </h4>
                            <p style="opacity: 0.8; font-size: 14px; line-height: 1.8; margin: 0;">
                                📍 ${footerAddress.replace(/\n/g, '<br>')}<br><br>
                                📞 ${phone}<br>
                                ✉️ ${email}
                            </p>
                        </div>
                    </div>
                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; text-align: center; opacity: 0.7; font-size: 13px;">
                        ${copyright}
                    </div>
                </footer>
            `;
        }
        
        function submitOrder() {
            const customerName = $('#customer-name').val().trim();
            const customerEmail = $('#customer-email').val().trim();
            
            if (!customerName || !customerEmail) {
                alert('Please enter your name and email address.');
                return;
            }
            
            // Validate email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(customerEmail)) {
                alert('Please enter a valid email address.');
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
            
            $('.btn-submit').html('<i class="bi bi-hourglass-split"></i> Submitting...').prop('disabled', true);
            
            $.post(ajaxUrl, data, function(response) {
                if (response.success) {
                    $('.step-section').removeClass('active');
                    $('#step-success').addClass('active');
                    $('.progress-steps').hide();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    alert(response.data.message || 'Failed to submit order. Please try again.');
                    $('.btn-submit').html('<i class="bi bi-check-lg"></i> Submit Website Order').prop('disabled', false);
                }
            }).fail(function() {
                alert('An error occurred. Please check your connection and try again.');
                $('.btn-submit').html('<i class="bi bi-check-lg"></i> Submit Website Order').prop('disabled', false);
            });
        }
    </script>
</body>
</html>
