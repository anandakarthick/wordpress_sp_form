<?php
/**
 * Customer Form Template - Website Template Selector
 * 
 * Variables available: $form, $share, $token, $available_themes
 */

if (!defined('ABSPATH')) {
    exit;
}

$themes_handler = SPFM_Themes::get_instance();

// Get available themes for this form
$theme_ids = json_decode($form->available_themes, true) ?: array();
$available_themes = $themes_handler->get_by_ids($theme_ids);

// Get complete theme data for each
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
            --primary: #667eea;
            --secondary: #764ba2;
            --accent: #28a745;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        /* Header */
        .site-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: #fff;
            padding: 30px 0;
            text-align: center;
        }
        
        .site-header h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .steps-container {
            display: flex;
            justify-content: center;
            gap: 50px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #999;
            font-size: 14px;
        }
        
        .step.active {
            color: var(--primary);
        }
        
        .step.completed {
            color: var(--accent);
        }
        
        .step-number {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e9ecef;
            font-weight: 600;
        }
        
        .step.active .step-number {
            background: var(--primary);
            color: #fff;
        }
        
        .step.completed .step-number {
            background: var(--accent);
            color: #fff;
        }
        
        /* Main Container */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        
        /* Step Sections */
        .step-section {
            display: none;
        }
        
        .step-section.active {
            display: block;
        }
        
        /* Theme Selection */
        .themes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .theme-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.3s;
            border: 3px solid transparent;
        }
        
        .theme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .theme-card.selected {
            border-color: var(--primary);
        }
        
        .theme-preview {
            height: 200px;
            position: relative;
            overflow: hidden;
        }
        
        .theme-mockup {
            width: 90%;
            height: 170px;
            margin: 15px auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .mockup-nav {
            height: 25px;
            padding: 5px 10px;
            display: flex;
            gap: 10px;
            align-items: center;
            border-bottom: 1px solid #eee;
            font-size: 8px;
        }
        
        .mockup-hero {
            height: 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
        
        .mockup-content {
            padding: 10px;
        }
        
        .mockup-cards {
            display: flex;
            gap: 5px;
        }
        
        .mockup-cards div {
            flex: 1;
            height: 30px;
            background: #e9ecef;
            border-radius: 4px;
        }
        
        .mockup-footer {
            height: 20px;
        }
        
        .theme-info {
            padding: 20px;
        }
        
        .theme-info h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        
        .theme-category {
            display: inline-block;
            font-size: 12px;
            background: #e9ecef;
            padding: 3px 10px;
            border-radius: 15px;
            margin-bottom: 10px;
        }
        
        .theme-pages {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
        }
        
        .theme-page-tag {
            font-size: 11px;
            background: #f0f0f0;
            padding: 3px 8px;
            border-radius: 10px;
        }
        
        .select-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 30px;
            height: 30px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .theme-card.selected .select-badge {
            background: var(--primary);
            color: #fff;
        }
        
        /* Content Fill Section */
        .content-editor {
            display: flex;
            gap: 25px;
        }
        
        .pages-sidebar {
            width: 280px;
            flex-shrink: 0;
        }
        
        .pages-list {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            position: sticky;
            top: 100px;
        }
        
        .pages-list h4 {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #666;
        }
        
        .page-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            background: #f8f9fa;
            border: 2px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 8px;
            transition: all 0.2s;
        }
        
        .page-nav-item:hover {
            background: #f0f0f0;
        }
        
        .page-nav-item.active {
            background: #fff;
            border-color: var(--primary);
        }
        
        .page-nav-item.completed {
            background: #d4edda;
            border-color: var(--accent);
        }
        
        .page-nav-item i {
            color: var(--primary);
        }
        
        .page-nav-item span {
            flex: 1;
        }
        
        .page-nav-item .check {
            color: var(--accent);
            display: none;
        }
        
        .page-nav-item.completed .check {
            display: block;
        }
        
        .content-main {
            flex: 1;
        }
        
        .page-content-editor {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            display: none;
        }
        
        .page-content-editor.active {
            display: block;
        }
        
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .page-header h2 {
            margin: 0 0 5px 0;
        }
        
        .page-header p {
            color: #666;
            margin: 0;
        }
        
        .section-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .section-card h4 {
            margin: 0 0 20px 0;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-group label .required {
            color: #dc3545;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-hint {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        
        /* Repeater Fields */
        .repeater-container {
            border: 2px dashed #e0e0e0;
            border-radius: 10px;
            padding: 20px;
        }
        
        .repeater-item {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            position: relative;
        }
        
        .repeater-item .remove-item {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #dc3545;
            color: #fff;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            cursor: pointer;
        }
        
        .add-repeater-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 12px;
            background: #f0f0f0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }
        
        .add-repeater-item:hover {
            background: #e0e0e0;
        }
        
        /* Image Upload */
        .image-upload {
            border: 2px dashed #e0e0e0;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .image-upload:hover {
            border-color: var(--primary);
            background: #f8f9ff;
        }
        
        .image-upload i {
            font-size: 40px;
            color: #ccc;
            margin-bottom: 10px;
        }
        
        .image-upload input {
            display: none;
        }
        
        .image-preview {
            max-width: 200px;
            max-height: 150px;
            margin-top: 15px;
            border-radius: 8px;
        }
        
        /* Color Customization */
        .color-customization {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        
        .color-customization h3 {
            margin: 0 0 20px 0;
        }
        
        .color-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }
        
        .color-picker-group {
            text-align: center;
        }
        
        .color-picker-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .color-picker-group input[type="color"] {
            width: 60px;
            height: 45px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Preview Section */
        .preview-container {
            display: flex;
            gap: 25px;
        }
        
        .preview-sidebar {
            width: 250px;
            flex-shrink: 0;
        }
        
        .preview-controls {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        
        .preview-controls h4 {
            margin: 0 0 15px 0;
        }
        
        .preview-pages {
            margin-bottom: 20px;
        }
        
        .preview-page-btn {
            display: block;
            width: 100%;
            padding: 10px 15px;
            background: #f8f9fa;
            border: none;
            border-radius: 8px;
            text-align: left;
            cursor: pointer;
            margin-bottom: 5px;
        }
        
        .preview-page-btn.active {
            background: var(--primary);
            color: #fff;
        }
        
        .preview-main {
            flex: 1;
            background: #e9ecef;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .preview-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background: #333;
            color: #fff;
        }
        
        .device-buttons {
            display: flex;
            gap: 5px;
        }
        
        .device-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: #fff;
            padding: 5px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .device-btn.active {
            background: var(--primary);
        }
        
        .preview-frame-wrapper {
            padding: 20px;
            display: flex;
            justify-content: center;
            min-height: 600px;
        }
        
        .preview-frame {
            width: 100%;
            max-width: 1000px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .preview-frame.tablet {
            max-width: 768px;
        }
        
        .preview-frame.mobile {
            max-width: 375px;
        }
        
        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .btn-nav {
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-prev {
            background: #f0f0f0;
            border: none;
            color: #333;
        }
        
        .btn-next {
            background: var(--primary);
            border: none;
            color: #fff;
        }
        
        .btn-submit {
            background: var(--accent);
            border: none;
            color: #fff;
            padding: 15px 40px;
            font-size: 18px;
        }
        
        .btn-nav:hover {
            transform: translateY(-2px);
        }
        
        /* Success Message */
        .success-container {
            text-align: center;
            padding: 80px 20px;
            background: #fff;
            border-radius: 15px;
            max-width: 600px;
            margin: 50px auto;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--accent), #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        
        .success-icon i {
            font-size: 50px;
            color: #fff;
        }
        
        .success-container h2 {
            color: var(--accent);
            margin-bottom: 15px;
        }
        
        @media (max-width: 992px) {
            .content-editor, .preview-container {
                flex-direction: column;
            }
            .pages-sidebar, .preview-sidebar {
                width: 100%;
            }
            .steps-container {
                flex-wrap: wrap;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <h1><?php echo esc_html($form->header_text ?: $form->name); ?></h1>
            <p>Select your preferred website template, customize it, and fill in your content.</p>
        </div>
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
            <h2 class="mb-4">Choose Your Website Template</h2>
            
            <div class="themes-grid">
                <?php foreach ($themes_complete as $theme): ?>
                    <div class="theme-card" data-theme-id="<?php echo $theme->id; ?>">
                        <div class="theme-preview" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?> 0%, <?php echo esc_attr($theme->secondary_color); ?> 100%);">
                            <div class="select-badge">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div class="theme-mockup">
                                <div class="mockup-nav">
                                    <?php foreach (array_slice($theme->pages, 0, 4) as $page): ?>
                                        <span><?php echo esc_html($page->page_name); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <div class="mockup-hero" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?> 0%, <?php echo esc_attr($theme->secondary_color); ?> 100%);">
                                    <div style="width: 60%; height: 10px; background: rgba(255,255,255,0.8); border-radius: 5px; margin-bottom: 5px;"></div>
                                    <div style="width: 40%; height: 6px; background: rgba(255,255,255,0.5); border-radius: 3px;"></div>
                                </div>
                                <div class="mockup-content">
                                    <div class="mockup-cards">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="mockup-footer" style="background: <?php echo esc_attr($theme->footer_bg_color); ?>;"></div>
                            </div>
                        </div>
                        <div class="theme-info">
                            <span class="theme-category"><?php echo esc_html(ucfirst($theme->category)); ?></span>
                            <h3><?php echo esc_html($theme->name); ?></h3>
                            <p class="text-muted small"><?php echo esc_html($theme->description); ?></p>
                            <div class="theme-pages">
                                <?php foreach ($theme->pages as $page): ?>
                                    <span class="theme-page-tag">
                                        <i class="<?php echo esc_attr($page->page_icon); ?>"></i>
                                        <?php echo esc_html($page->page_name); ?>
                                    </span>
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
            <h2 class="mb-4">Fill Your Website Content</h2>
            
            <div class="content-editor">
                <div class="pages-sidebar">
                    <div class="pages-list">
                        <h4>Website Pages</h4>
                        <div id="pages-nav">
                            <!-- Pages will be loaded here -->
                        </div>
                    </div>
                </div>
                
                <div class="content-main" id="content-forms">
                    <!-- Page content forms will be loaded here -->
                </div>
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
            <h2 class="mb-4">Customize Your Colors</h2>
            
            <div class="color-customization">
                <h3><i class="bi bi-palette"></i> Color Palette</h3>
                <p class="text-muted mb-4">Customize the colors to match your brand identity.</p>
                
                <div class="color-grid">
                    <div class="color-picker-group">
                        <label>Primary Color</label>
                        <input type="color" id="custom-primary" value="#667eea">
                        <p class="form-hint">Main buttons, links</p>
                    </div>
                    <div class="color-picker-group">
                        <label>Secondary Color</label>
                        <input type="color" id="custom-secondary" value="#764ba2">
                        <p class="form-hint">Gradients, accents</p>
                    </div>
                    <div class="color-picker-group">
                        <label>Accent Color</label>
                        <input type="color" id="custom-accent" value="#28a745">
                        <p class="form-hint">Highlights, CTAs</p>
                    </div>
                    <div class="color-picker-group">
                        <label>Background</label>
                        <input type="color" id="custom-background" value="#ffffff">
                        <p class="form-hint">Page background</p>
                    </div>
                    <div class="color-picker-group">
                        <label>Text Color</label>
                        <input type="color" id="custom-text" value="#333333">
                        <p class="form-hint">Body text</p>
                    </div>
                    <div class="color-picker-group">
                        <label>Header Background</label>
                        <input type="color" id="custom-header-bg" value="#667eea">
                        <p class="form-hint">Navigation area</p>
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
        <!-- Step 3: Preview & Submit (without color customization) -->
        <div class="step-section" id="step-3">
        <?php endif; ?>
            <h2 class="mb-4">Preview & Submit</h2>
            
            <div class="preview-container">
                <div class="preview-sidebar">
                    <div class="preview-controls">
                        <h4>Preview Pages</h4>
                        <div class="preview-pages" id="preview-pages-nav">
                            <!-- Pages buttons loaded here -->
                        </div>
                        
                        <hr>
                        
                        <h4>Your Info</h4>
                        <div class="form-group">
                            <label>Your Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="customer-name" required>
                        </div>
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" class="form-control" id="customer-email" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" class="form-control" id="customer-phone">
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
                        <div class="preview-frame" id="live-preview">
                            <!-- Live preview rendered here -->
                        </div>
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
        
        <!-- Success Message -->
        <div class="step-section" id="step-success">
            <div class="success-container">
                <div class="success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h2>Thank You!</h2>
                <p class="lead"><?php echo esc_html($form->success_message ?: 'Your website order has been submitted successfully. We will contact you shortly.'); ?></p>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuration
        const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        const formId = <?php echo $form->id; ?>;
        const token = '<?php echo esc_js($token); ?>';
        const nonce = '<?php echo wp_create_nonce('spfm_nonce'); ?>';
        const allowColorCustomization = <?php echo $allow_color_customization ? 'true' : 'false'; ?>;
        const totalSteps = <?php echo $allow_color_customization ? 4 : 3; ?>;
        
        // Theme data
        const themesData = <?php echo json_encode($themes_complete); ?>;
        
        // State
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
                
                // Set initial colors from selected theme
                const theme = themesData[selectedThemeId];
                if (theme) {
                    $('#custom-primary').val(theme.primary_color);
                    $('#custom-secondary').val(theme.secondary_color);
                    $('#custom-accent').val(theme.accent_color || theme.primary_color);
                    $('#custom-background').val(theme.background_color);
                    $('#custom-text').val(theme.text_color);
                    $('#custom-header-bg').val(theme.header_bg_color || theme.primary_color);
                    
                    colorCustomizations = {
                        primary_color: theme.primary_color,
                        secondary_color: theme.secondary_color,
                        accent_color: theme.accent_color || theme.primary_color,
                        background_color: theme.background_color,
                        text_color: theme.text_color,
                        header_bg_color: theme.header_bg_color || theme.primary_color
                    };
                }
            });
            
            // Color changes
            $('input[type="color"]').on('input', function() {
                const id = $(this).attr('id');
                const value = $(this).val();
                const key = id.replace('custom-', '').replace(/-/g, '_');
                colorCustomizations[key] = value;
                updatePreview();
            });
            
            // Device switcher
            $('.device-btn').on('click', function() {
                $('.device-btn').removeClass('active');
                $(this).addClass('active');
                const device = $(this).data('device');
                $('#live-preview, .preview-frame').removeClass('desktop tablet mobile').addClass(device);
            });
        });
        
        function goToStep(step) {
            // Validation
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
            
            // Show section
            $('.step-section').removeClass('active');
            $(`#step-${step}`).addClass('active');
            
            currentStep = step;
            
            // Load content for step 2
            if (step === 2) {
                loadContentEditor();
            }
            
            // Update preview for final step
            if ((allowColorCustomization && step === 4) || (!allowColorCustomization && step === 3)) {
                loadPreview();
            }
            
            window.scrollTo(0, 0);
        }
        
        function loadContentEditor() {
            const theme = themesData[selectedThemeId];
            if (!theme) return;
            
            // Build pages navigation
            let pagesNav = '';
            theme.pages.forEach((page, index) => {
                pagesNav += `
                    <div class="page-nav-item ${index === 0 ? 'active' : ''}" data-page-index="${index}" onclick="showPageEditor(${index})">
                        <i class="bi bi-${getPageIcon(page.page_icon)}"></i>
                        <span>${page.page_name}</span>
                        <i class="bi bi-check-circle-fill check"></i>
                    </div>
                `;
            });
            $('#pages-nav').html(pagesNav);
            
            // Build content forms for each page
            let contentForms = '';
            theme.pages.forEach((page, index) => {
                contentForms += `
                    <div class="page-content-editor ${index === 0 ? 'active' : ''}" id="page-editor-${index}">
                        <div class="page-header">
                            <h2><i class="bi bi-${getPageIcon(page.page_icon)}"></i> ${page.page_name}</h2>
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
                return '<p class="text-muted">No content sections for this page.</p>';
            }
            
            let html = '';
            page.sections.forEach((section, secIndex) => {
                html += `
                    <div class="section-card">
                        <h4><i class="bi bi-layers"></i> ${section.section_name}</h4>
                        ${buildFieldsHtml(section.fields, pageIndex, secIndex)}
                    </div>
                `;
            });
            return html;
        }
        
        function buildFieldsHtml(fields, pageIndex, secIndex, prefix = '') {
            if (!fields || fields.length === 0) return '';
            
            let html = '';
            fields.forEach((field, fieldIndex) => {
                const fieldName = `${prefix}page_${pageIndex}_sec_${secIndex}_${field.name}`;
                const required = field.required ? '<span class="required">*</span>' : '';
                
                html += `<div class="form-group">`;
                html += `<label>${field.label} ${required}</label>`;
                
                switch (field.type) {
                    case 'text':
                    case 'email':
                    case 'url':
                    case 'number':
                        html += `<input type="${field.type}" class="form-control content-field" name="${fieldName}" 
                                 placeholder="${field.placeholder || ''}" ${field.required ? 'required' : ''}>`;
                        break;
                    case 'textarea':
                        html += `<textarea class="form-control content-field" name="${fieldName}" rows="4" 
                                 placeholder="${field.placeholder || ''}" ${field.required ? 'required' : ''}></textarea>`;
                        break;
                    case 'editor':
                        html += `<textarea class="form-control content-field editor-field" name="${fieldName}" rows="6" 
                                 placeholder="${field.placeholder || ''}" ${field.required ? 'required' : ''}></textarea>`;
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
                                 placeholder="e.g., bi-star, bi-heart">`;
                        break;
                    case 'repeater':
                        html += `
                            <div class="repeater-container" id="repeater-${fieldName}">
                                <div class="repeater-items"></div>
                                <button type="button" class="add-repeater-item" onclick="addRepeaterItem('${fieldName}', ${JSON.stringify(field.fields).replace(/"/g, '&quot;')})">
                                    <i class="bi bi-plus-circle"></i> Add Item
                                </button>
                            </div>
                        `;
                        break;
                    case 'gallery':
                        html += `
                            <div class="image-upload" onclick="triggerFileInput('${fieldName}')">
                                <i class="bi bi-images"></i>
                                <p>Click to upload multiple images</p>
                                <input type="file" id="${fieldName}" class="content-field gallery-field" accept="image/*" multiple>
                                <div id="gallery-preview-${fieldName}" class="gallery-preview"></div>
                            </div>
                        `;
                        break;
                    default:
                        html += `<input type="text" class="form-control content-field" name="${fieldName}" 
                                 placeholder="${field.placeholder || ''}">`;
                }
                
                if (field.hint) {
                    html += `<p class="form-hint">${field.hint}</p>`;
                }
                
                html += `</div>`;
            });
            
            return html;
        }
        
        function getPageIcon(iconClass) {
            // Convert dashicons to bootstrap icons
            const mapping = {
                'dashicons-admin-home': 'house',
                'dashicons-info': 'info-circle',
                'dashicons-email': 'envelope',
                'dashicons-screenoptions': 'grid-3x3-gap',
                'dashicons-format-quote': 'chat-quote',
                'dashicons-portfolio': 'briefcase',
                'dashicons-star-filled': 'star',
                'dashicons-food': 'cup-hot',
                'dashicons-format-gallery': 'images',
                'dashicons-calendar-alt': 'calendar',
                'dashicons-products': 'bag',
                'dashicons-car': 'truck',
                'dashicons-heart': 'heart',
                'dashicons-groups': 'people',
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
        
        let repeaterCounters = {};
        
        function addRepeaterItem(fieldName, fields) {
            if (!repeaterCounters[fieldName]) repeaterCounters[fieldName] = 0;
            repeaterCounters[fieldName]++;
            
            const itemId = repeaterCounters[fieldName];
            let itemHtml = `
                <div class="repeater-item" id="repeater-item-${fieldName}-${itemId}">
                    <button type="button" class="remove-item" onclick="removeRepeaterItem('${fieldName}', ${itemId})">
                        <i class="bi bi-x"></i>
                    </button>
            `;
            
            fields.forEach(field => {
                const subFieldName = `${fieldName}_${itemId}_${field.name}`;
                itemHtml += `<div class="form-group">`;
                itemHtml += `<label>${field.label}</label>`;
                
                switch (field.type) {
                    case 'text':
                        itemHtml += `<input type="text" class="form-control content-field" name="${subFieldName}">`;
                        break;
                    case 'textarea':
                        itemHtml += `<textarea class="form-control content-field" name="${subFieldName}" rows="3"></textarea>`;
                        break;
                    case 'image':
                        itemHtml += `
                            <div class="image-upload" onclick="triggerFileInput('${subFieldName}')" style="padding: 15px;">
                                <i class="bi bi-image"></i>
                                <input type="file" id="${subFieldName}" class="content-field" accept="image/*" 
                                       onchange="previewImage(this, '${subFieldName}')">
                                <img id="preview-${subFieldName}" class="image-preview" style="display:none; max-height: 80px;">
                            </div>
                        `;
                        break;
                    case 'number':
                        itemHtml += `<input type="number" class="form-control content-field" name="${subFieldName}">`;
                        break;
                    default:
                        itemHtml += `<input type="text" class="form-control content-field" name="${subFieldName}">`;
                }
                
                itemHtml += `</div>`;
            });
            
            itemHtml += `</div>`;
            
            $(`#repeater-${fieldName} .repeater-items`).append(itemHtml);
        }
        
        function removeRepeaterItem(fieldName, itemId) {
            $(`#repeater-item-${fieldName}-${itemId}`).remove();
        }
        
        function collectContentData() {
            pageContents = {};
            
            $('.content-field').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    if ($(this).is(':file')) {
                        // Handle file inputs later during submission
                        pageContents[name] = $(this).val();
                    } else {
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
            
            // Build preview pages navigation
            let pagesNav = '';
            theme.pages.forEach((page, index) => {
                pagesNav += `
                    <button class="preview-page-btn ${index === 0 ? 'active' : ''}" 
                            onclick="showPreviewPage(${index}, this)">
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
        
        function renderPagePreview(pageIndex) {
            const theme = themesData[selectedThemeId];
            const page = theme.pages[pageIndex];
            
            const primary = colorCustomizations.primary_color || theme.primary_color;
            const secondary = colorCustomizations.secondary_color || theme.secondary_color;
            const accent = colorCustomizations.accent_color || theme.accent_color || primary;
            const background = colorCustomizations.background_color || theme.background_color;
            const textColor = colorCustomizations.text_color || theme.text_color;
            const headerBg = colorCustomizations.header_bg_color || theme.header_bg_color || primary;
            
            let previewHtml = `
                <div style="font-family: ${theme.font_family}, sans-serif; background: ${background}; min-height: 500px;">
                    <!-- Header/Nav -->
                    <nav style="background: ${headerBg}; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center;">
                        <div style="color: ${headerBg === '#ffffff' ? textColor : '#fff'}; font-weight: 700;">
                            ${pageContents['page_0_sec_0_headline'] || 'Your Brand'}
                        </div>
                        <div style="display: flex; gap: 20px;">
                            ${theme.pages.map(p => `<a href="#" style="color: ${headerBg === '#ffffff' ? textColor : 'rgba(255,255,255,0.8)'}; text-decoration: none; font-size: 14px;">${p.page_name}</a>`).join('')}
                        </div>
                    </nav>
                    
                    <!-- Page Content -->
                    ${renderPreviewSections(page, pageIndex, primary, secondary, accent, textColor)}
                    
                    <!-- Footer -->
                    <footer style="background: ${theme.footer_bg_color}; color: #fff; padding: 40px 30px; text-align: center;">
                        <p style="margin: 0; opacity: 0.8; font-size: 14px;">© 2024 Your Company. All rights reserved.</p>
                    </footer>
                </div>
            `;
            
            $('#live-preview').html(previewHtml);
        }
        
        function renderPreviewSections(page, pageIndex, primary, secondary, accent, textColor) {
            let html = '';
            
            if (!page.sections) return html;
            
            page.sections.forEach((section, secIndex) => {
                const sectionKey = `page_${pageIndex}_sec_${secIndex}`;
                
                switch (section.section_type) {
                    case 'hero':
                        const headline = pageContents[`${sectionKey}_headline`] || 'Welcome to Our Website';
                        const subheadline = pageContents[`${sectionKey}_subheadline`] || 'Your success story starts here';
                        const ctaText = pageContents[`${sectionKey}_cta_text`] || 'Get Started';
                        
                        html += `
                            <div style="background: linear-gradient(135deg, ${primary} 0%, ${secondary} 100%); padding: 80px 30px; text-align: center; color: #fff;">
                                <h1 style="font-size: 32px; margin-bottom: 15px;">${headline}</h1>
                                <p style="font-size: 18px; opacity: 0.9; margin-bottom: 25px; max-width: 600px; margin-left: auto; margin-right: auto;">${subheadline}</p>
                                <button style="background: ${accent}; color: #fff; border: none; padding: 15px 35px; border-radius: 50px; font-size: 16px; cursor: pointer;">
                                    ${ctaText}
                                </button>
                            </div>
                        `;
                        break;
                        
                    case 'content':
                        const title = pageContents[`${sectionKey}_about_title`] || pageContents[`${sectionKey}_section_title`] || section.section_name;
                        const content = pageContents[`${sectionKey}_about_content`] || pageContents[`${sectionKey}_content`] || 'Your content goes here...';
                        
                        html += `
                            <div style="padding: 60px 30px; color: ${textColor};">
                                <h2 style="text-align: center; margin-bottom: 30px; color: ${primary};">${title}</h2>
                                <div style="max-width: 800px; margin: 0 auto; line-height: 1.8;">
                                    ${content.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'features':
                    case 'services':
                        const featTitle = pageContents[`${sectionKey}_section_title`] || pageContents[`${sectionKey}_services_title`] || 'Our Services';
                        
                        html += `
                            <div style="padding: 60px 30px; background: #f8f9fa;">
                                <h2 style="text-align: center; margin-bottom: 40px; color: ${primary};">${featTitle}</h2>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; max-width: 1000px; margin: 0 auto;">
                                    <div style="background: #fff; padding: 30px; border-radius: 10px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, ${primary}, ${secondary}); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px;">★</div>
                                        <h4>Service 1</h4>
                                        <p style="color: #666;">Description of your service goes here.</p>
                                    </div>
                                    <div style="background: #fff; padding: 30px; border-radius: 10px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, ${primary}, ${secondary}); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px;">✦</div>
                                        <h4>Service 2</h4>
                                        <p style="color: #666;">Description of your service goes here.</p>
                                    </div>
                                    <div style="background: #fff; padding: 30px; border-radius: 10px; text-align: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
                                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, ${primary}, ${secondary}); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px;">◆</div>
                                        <h4>Service 3</h4>
                                        <p style="color: #666;">Description of your service goes here.</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    case 'contact':
                        const contactTitle = pageContents[`${sectionKey}_contact_title`] || 'Contact Us';
                        const address = pageContents[`${sectionKey}_address`] || 'Your address';
                        const phone = pageContents[`${sectionKey}_phone`] || 'Your phone';
                        const email = pageContents[`${sectionKey}_email`] || 'Your email';
                        
                        html += `
                            <div style="padding: 60px 30px; color: ${textColor};">
                                <h2 style="text-align: center; margin-bottom: 40px; color: ${primary};">${contactTitle}</h2>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; max-width: 900px; margin: 0 auto; text-align: center;">
                                    <div>
                                        <div style="font-size: 30px; color: ${primary}; margin-bottom: 10px;">📍</div>
                                        <h4>Address</h4>
                                        <p style="color: #666;">${address}</p>
                                    </div>
                                    <div>
                                        <div style="font-size: 30px; color: ${primary}; margin-bottom: 10px;">📞</div>
                                        <h4>Phone</h4>
                                        <p style="color: #666;">${phone}</p>
                                    </div>
                                    <div>
                                        <div style="font-size: 30px; color: ${primary}; margin-bottom: 10px;">✉️</div>
                                        <h4>Email</h4>
                                        <p style="color: #666;">${email}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        break;
                        
                    default:
                        html += `
                            <div style="padding: 40px 30px; color: ${textColor};">
                                <h3 style="color: ${primary};">${section.section_name}</h3>
                                <p>Content section preview</p>
                            </div>
                        `;
                }
            });
            
            return html;
        }
        
        function updatePreview() {
            if (currentStep === totalSteps) {
                renderPagePreview(0);
            }
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
