<?php
/**
 * Customer Form Template
 * This template is shown when a customer clicks on a shared form link
 */

if (!defined('ABSPATH')) {
    exit;
}

// Variables available: $form, $fields, $share, $token, $all_themes, $current_theme, $customizations
$allow_customization = isset($form->allow_customization) && $form->allow_customization;
$theme_css = '';
if ($current_theme) {
    $theme_css = $themes->get_theme_css($current_theme->id, $customizations);
}

// Get form settings
$logo_url = !empty($customizations['logo_url']) ? $customizations['logo_url'] : ($form->logo_url ?? '');
$banner_url = !empty($customizations['banner_url']) ? $customizations['banner_url'] : ($form->banner_url ?? '');
$header_text = !empty($customizations['header_text']) ? $customizations['header_text'] : ($form->header_text ?? $form->name);
$footer_text = !empty($customizations['footer_text']) ? $customizations['footer_text'] : ($form->footer_text ?? '');
$submit_text = !empty($customizations['submit_button_text']) ? $customizations['submit_button_text'] : ($form->submit_button_text ?? 'Submit');

$layout_class = $current_theme ? 'layout-' . $current_theme->layout_style : 'layout-default';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($form->name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f5f7fa;
        }
        
        .spfm-customer-page {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }
        
        .spfm-form-wrapper {
            max-width: 700px;
            margin: 30px auto;
            padding: 0;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            background: #fff;
        }
        
        .spfm-form-header {
            padding: 40px 30px;
            text-align: center;
            color: #fff;
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .spfm-form-header h1 {
            margin: 0;
            font-size: 28px;
            color: #fff !important;
        }
        
        .spfm-form-header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        
        .spfm-form-logo {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 15px;
        }
        
        .spfm-form-banner {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
        }
        
        .spfm-form-body {
            padding: 30px;
        }
        
        .spfm-form-group {
            margin-bottom: 20px;
        }
        
        .spfm-form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .spfm-form-group .required {
            color: #dc3545;
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            width: 100%;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-check {
            margin-bottom: 8px;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }
        
        .btn-primary {
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-radius: 50px;
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .spfm-form-footer {
            text-align: center;
            padding: 20px 30px;
            background: #f8f9fa;
            color: #666;
            font-size: 14px;
        }
        
        .spfm-section-heading {
            font-size: 20px;
            color: #333;
            margin: 20px 0 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        
        .spfm-divider {
            border: 0;
            border-top: 1px solid #eee;
            margin: 25px 0;
        }
        
        .spfm-paragraph {
            color: #666;
            line-height: 1.6;
        }
        
        /* Customization Panel */
        .spfm-customize-toggle {
            position: fixed;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 15px 10px;
            border-radius: 10px 0 0 10px;
            cursor: pointer;
            z-index: 1000;
            box-shadow: -5px 0 15px rgba(0,0,0,0.2);
            writing-mode: vertical-rl;
            text-orientation: mixed;
            border: none;
        }
        
        .spfm-customize-panel {
            position: fixed;
            right: -400px;
            top: 0;
            width: 400px;
            height: 100vh;
            background: #fff;
            box-shadow: -5px 0 30px rgba(0,0,0,0.2);
            z-index: 999;
            transition: right 0.3s ease;
            overflow-y: auto;
        }
        
        .spfm-customize-panel.open {
            right: 0;
        }
        
        .spfm-customize-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .spfm-customize-body {
            padding: 20px;
        }
        
        .spfm-customize-section {
            margin-bottom: 25px;
        }
        
        .spfm-customize-section h4 {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            font-size: 16px;
        }
        
        .color-picker-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .color-picker-group label {
            flex: 1;
            font-size: 14px;
        }
        
        .color-picker-group input[type="color"] {
            width: 50px;
            height: 35px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        
        .theme-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .theme-card {
            border: 2px solid #eee;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        
        .theme-card:hover,
        .theme-card.active {
            border-color: #667eea;
        }
        
        .theme-card .theme-preview {
            height: 40px;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        
        .theme-card .theme-name {
            font-size: 12px;
            color: #666;
        }
        
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-area:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        
        .upload-preview {
            max-width: 100%;
            max-height: 100px;
            margin-top: 10px;
            border-radius: 5px;
        }
        
        /* Success Message */
        .spfm-success-message {
            text-align: center;
            padding: 60px 30px;
        }
        
        .spfm-success-message .icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        
        .spfm-success-message h2 {
            color: #28a745;
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .spfm-customer-page {
                padding: 10px;
            }
            
            .spfm-form-wrapper {
                margin: 15px auto;
                border-radius: 10px;
            }
            
            .spfm-form-header {
                padding: 30px 20px;
            }
            
            .spfm-form-body {
                padding: 20px;
            }
            
            .spfm-customize-panel {
                width: 100%;
                right: -100%;
            }
            
            .spfm-customize-toggle {
                top: auto;
                bottom: 20px;
                right: 20px;
                transform: none;
                writing-mode: horizontal-tb;
                border-radius: 50px;
                padding: 12px 20px;
            }
        }
        
        <?php echo $theme_css; ?>
    </style>
</head>
<body>
    <div class="spfm-customer-page <?php echo esc_attr($layout_class); ?>">
        <?php if (!empty($banner_url)): ?>
            <img src="<?php echo esc_url($banner_url); ?>" alt="Banner" class="spfm-form-banner">
        <?php endif; ?>
        
        <div class="spfm-form-wrapper">
            <div class="spfm-form-header">
                <?php if (!empty($logo_url)): ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="Logo" class="spfm-form-logo">
                <?php endif; ?>
                <h1><?php echo esc_html($header_text); ?></h1>
                <?php if (!empty($form->description)): ?>
                    <p><?php echo esc_html($form->description); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="spfm-form-body">
                <form id="spfm-customer-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="spfm_form_id" value="<?php echo $form->id; ?>">
                    <input type="hidden" name="spfm_token" value="<?php echo esc_attr($token); ?>">
                    <?php wp_nonce_field('spfm_customer_submit_' . $token, 'spfm_customer_nonce'); ?>
                    
                    <?php foreach ($fields as $field): ?>
                        <?php if ($field->status): ?>
                            <?php spfm_render_customer_field($field); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    
                    <div class="spfm-form-group text-center">
                        <button type="submit" class="btn btn-primary spfm-submit-btn">
                            <?php echo esc_html($submit_text); ?>
                        </button>
                    </div>
                </form>
            </div>
            
            <?php if (!empty($footer_text)): ?>
                <div class="spfm-form-footer">
                    <?php echo wp_kses_post($footer_text); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($allow_customization): ?>
            <!-- Customization Toggle -->
            <button class="spfm-customize-toggle" id="customize-toggle">
                <i class="bi bi-palette"></i> Customize
            </button>
            
            <!-- Customization Panel -->
            <div class="spfm-customize-panel" id="customize-panel">
                <div class="spfm-customize-header">
                    <h4 class="mb-0"><i class="bi bi-palette"></i> Customize Form</h4>
                    <button type="button" class="btn-close btn-close-white" id="close-customize"></button>
                </div>
                
                <div class="spfm-customize-body">
                    <form id="customization-form">
                        <!-- Theme Selection -->
                        <div class="spfm-customize-section">
                            <h4><i class="bi bi-brush"></i> Select Theme</h4>
                            <div class="theme-cards">
                                <?php foreach ($all_themes as $t): ?>
                                    <div class="theme-card <?php echo ($current_theme && $current_theme->id == $t->id) ? 'active' : ''; ?>" 
                                         data-theme-id="<?php echo $t->id; ?>">
                                        <div class="theme-preview" style="background: linear-gradient(135deg, <?php echo esc_attr($t->primary_color); ?> 0%, <?php echo esc_attr($t->secondary_color); ?> 100%);"></div>
                                        <div class="theme-name"><?php echo esc_html($t->name); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Colors -->
                        <div class="spfm-customize-section">
                            <h4><i class="bi bi-droplet"></i> Colors</h4>
                            <div class="color-picker-group">
                                <label>Primary Color</label>
                                <input type="color" name="primary_color" value="<?php echo esc_attr($current_theme->primary_color ?? '#667eea'); ?>">
                            </div>
                            <div class="color-picker-group">
                                <label>Secondary Color</label>
                                <input type="color" name="secondary_color" value="<?php echo esc_attr($current_theme->secondary_color ?? '#764ba2'); ?>">
                            </div>
                            <div class="color-picker-group">
                                <label>Background Color</label>
                                <input type="color" name="background_color" value="<?php echo esc_attr($current_theme->background_color ?? '#ffffff'); ?>">
                            </div>
                            <div class="color-picker-group">
                                <label>Header Color</label>
                                <input type="color" name="header_bg_color" value="<?php echo esc_attr($current_theme->header_bg_color ?? '#667eea'); ?>">
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="spfm-customize-section">
                            <h4><i class="bi bi-fonts"></i> Content</h4>
                            <div class="mb-3">
                                <label class="form-label">Header Text</label>
                                <input type="text" class="form-control" name="header_text" value="<?php echo esc_attr($header_text); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Submit Button Text</label>
                                <input type="text" class="form-control" name="submit_button_text" value="<?php echo esc_attr($submit_text); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Footer Text</label>
                                <textarea class="form-control" name="footer_text" rows="2"><?php echo esc_textarea($footer_text); ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Images -->
                        <div class="spfm-customize-section">
                            <h4><i class="bi bi-image"></i> Images</h4>
                            <div class="mb-3">
                                <label class="form-label">Logo</label>
                                <div class="upload-area" id="logo-upload">
                                    <i class="bi bi-cloud-upload"></i>
                                    <p class="mb-0">Click to upload logo</p>
                                    <input type="file" name="logo" accept="image/*" hidden>
                                    <?php if (!empty($logo_url)): ?>
                                        <img src="<?php echo esc_url($logo_url); ?>" class="upload-preview">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Banner</label>
                                <div class="upload-area" id="banner-upload">
                                    <i class="bi bi-cloud-upload"></i>
                                    <p class="mb-0">Click to upload banner</p>
                                    <input type="file" name="banner" accept="image/*" hidden>
                                    <?php if (!empty($banner_url)): ?>
                                        <img src="<?php echo esc_url($banner_url); ?>" class="upload-preview">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg"></i> Apply Changes
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        const formToken = '<?php echo esc_js($token); ?>';
        const nonce = '<?php echo wp_create_nonce('spfm_nonce'); ?>';
        
        jQuery(document).ready(function($) {
            // Toggle customization panel
            $('#customize-toggle').on('click', function() {
                $('#customize-panel').addClass('open');
            });
            
            $('#close-customize').on('click', function() {
                $('#customize-panel').removeClass('open');
            });
            
            // Theme selection
            $('.theme-card').on('click', function() {
                $('.theme-card').removeClass('active');
                $(this).addClass('active');
                
                var themeId = $(this).data('theme-id');
                
                // Apply theme via AJAX
                $.post(ajaxUrl, {
                    action: 'spfm_apply_theme',
                    nonce: nonce,
                    token: formToken,
                    theme_id: themeId
                }, function(response) {
                    if (response.success) {
                        location.reload();
                    }
                });
            });
            
            // File uploads
            $('.upload-area').on('click', function() {
                $(this).find('input[type="file"]').click();
            });
            
            $('.upload-area input[type="file"]').on('change', function() {
                var file = this.files[0];
                var $area = $(this).parent();
                
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $area.find('.upload-preview').remove();
                        $area.append('<img src="' + e.target.result + '" class="upload-preview">');
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Apply customizations
            $('#customization-form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                formData.append('action', 'spfm_save_customizations');
                formData.append('nonce', nonce);
                formData.append('token', formToken);
                
                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.data.message || 'Failed to save customizations');
                        }
                    }
                });
            });
            
            // Live color preview
            $('input[type="color"]').on('input', function() {
                var name = $(this).attr('name');
                var value = $(this).val();
                
                switch(name) {
                    case 'primary_color':
                        $('.btn-primary').css('background', value);
                        break;
                    case 'background_color':
                        $('.spfm-form-wrapper').css('background-color', value);
                        break;
                    case 'header_bg_color':
                        $('.spfm-form-header').css('background', value);
                        break;
                }
            });
            
            // Form submission
            $('#spfm-customer-form').on('submit', function(e) {
                e.preventDefault();
                
                var $form = $(this);
                var $btn = $form.find('.spfm-submit-btn');
                var originalText = $btn.text();
                
                $btn.text('Submitting...').prop('disabled', true);
                
                var formData = new FormData(this);
                formData.append('action', 'spfm_customer_submit');
                formData.append('nonce', nonce);
                
                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            $('.spfm-form-body').html(
                                '<div class="spfm-success-message">' +
                                '<div class="icon"><i class="bi bi-check-circle"></i></div>' +
                                '<h2>Thank You!</h2>' +
                                '<p>' + response.data.message + '</p>' +
                                '</div>'
                            );
                        } else {
                            alert(response.data.message || 'Failed to submit form');
                            $btn.text(originalText).prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                        $btn.text(originalText).prop('disabled', false);
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php

// Helper function to render field
function spfm_render_customer_field($field) {
    $required = $field->is_required ? 'required' : '';
    $required_mark = $field->is_required ? '<span class="required">*</span>' : '';
    
    echo '<div class="spfm-form-group ' . esc_attr($field->css_class) . '">';
    
    switch ($field->field_type) {
        case 'text':
        case 'email':
        case 'number':
        case 'phone':
        case 'url':
        case 'password':
        case 'date':
        case 'time':
        case 'datetime':
            echo '<label for="' . esc_attr($field->field_name) . '">' . esc_html($field->field_label) . $required_mark . '</label>';
            $type = $field->field_type === 'phone' ? 'tel' : ($field->field_type === 'datetime' ? 'datetime-local' : $field->field_type);
            echo '<input type="' . esc_attr($type) . '" 
                    name="' . esc_attr($field->field_name) . '" 
                    id="' . esc_attr($field->field_name) . '" 
                    class="form-control" 
                    placeholder="' . esc_attr($field->placeholder) . '" 
                    value="' . esc_attr($field->default_value) . '" 
                    ' . $required . '>';
            break;
            
        case 'textarea':
            echo '<label for="' . esc_attr($field->field_name) . '">' . esc_html($field->field_label) . $required_mark . '</label>';
            echo '<textarea name="' . esc_attr($field->field_name) . '" 
                    id="' . esc_attr($field->field_name) . '" 
                    class="form-control" 
                    placeholder="' . esc_attr($field->placeholder) . '" 
                    rows="4" 
                    ' . $required . '>' . esc_textarea($field->default_value) . '</textarea>';
            break;
            
        case 'select':
            echo '<label for="' . esc_attr($field->field_name) . '">' . esc_html($field->field_label) . $required_mark . '</label>';
            echo '<select name="' . esc_attr($field->field_name) . '" 
                    id="' . esc_attr($field->field_name) . '" 
                    class="form-control form-select" 
                    ' . $required . '>';
            echo '<option value="">Select...</option>';
            $options = explode("\n", $field->field_options);
            foreach ($options as $option) {
                $option = trim($option);
                if (!empty($option)) {
                    echo '<option value="' . esc_attr($option) . '">' . esc_html($option) . '</option>';
                }
            }
            echo '</select>';
            break;
            
        case 'radio':
            echo '<label>' . esc_html($field->field_label) . $required_mark . '</label>';
            echo '<div class="spfm-radio-group">';
            $options = explode("\n", $field->field_options);
            foreach ($options as $index => $option) {
                $option = trim($option);
                if (!empty($option)) {
                    echo '<div class="form-check">';
                    echo '<input type="radio" 
                            name="' . esc_attr($field->field_name) . '" 
                            id="' . esc_attr($field->field_name . '_' . $index) . '" 
                            value="' . esc_attr($option) . '" 
                            class="form-check-input" 
                            ' . ($index === 0 && $field->is_required ? 'required' : '') . '>';
                    echo '<label class="form-check-label" for="' . esc_attr($field->field_name . '_' . $index) . '">' . esc_html($option) . '</label>';
                    echo '</div>';
                }
            }
            echo '</div>';
            break;
            
        case 'checkbox':
            echo '<label>' . esc_html($field->field_label) . $required_mark . '</label>';
            echo '<div class="spfm-checkbox-group">';
            $options = explode("\n", $field->field_options);
            foreach ($options as $index => $option) {
                $option = trim($option);
                if (!empty($option)) {
                    echo '<div class="form-check">';
                    echo '<input type="checkbox" 
                            name="' . esc_attr($field->field_name) . '[]" 
                            id="' . esc_attr($field->field_name . '_' . $index) . '" 
                            value="' . esc_attr($option) . '" 
                            class="form-check-input">';
                    echo '<label class="form-check-label" for="' . esc_attr($field->field_name . '_' . $index) . '">' . esc_html($option) . '</label>';
                    echo '</div>';
                }
            }
            echo '</div>';
            break;
            
        case 'file':
            echo '<label for="' . esc_attr($field->field_name) . '">' . esc_html($field->field_label) . $required_mark . '</label>';
            echo '<input type="file" 
                    name="' . esc_attr($field->field_name) . '" 
                    id="' . esc_attr($field->field_name) . '" 
                    class="form-control" 
                    ' . $required . '>';
            break;
            
        case 'heading':
            echo '<h3 class="spfm-section-heading">' . esc_html($field->field_label) . '</h3>';
            break;
            
        case 'paragraph':
            echo '<p class="spfm-paragraph">' . esc_html($field->field_options) . '</p>';
            break;
            
        case 'divider':
            echo '<hr class="spfm-divider">';
            break;
    }
    
    echo '</div>';
}
?>
