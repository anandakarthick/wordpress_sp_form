<?php
if (!defined('ABSPATH')) {
    exit;
}

$themes_handler = SPFM_Themes::get_instance();
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$theme = null;

if ($action === 'edit' && $id) {
    $theme = $themes_handler->get_by_id($id);
}

// Get templates and custom themes
$templates = $themes_handler->get_all(array('is_template' => 1, 'per_page' => 50));
$custom_themes = $themes_handler->get_all(array('is_template' => 0, 'per_page' => 50));

$font_families = $themes_handler->get_font_families();
$button_styles = $themes_handler->get_button_styles();
$layout_styles = $themes_handler->get_layout_styles();
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">Themes</h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=add'); ?>" class="page-title-action">Create Custom Theme</a>
        
        <!-- Pre-built Templates -->
        <div class="spfm-section">
            <h2><span class="dashicons dashicons-layout"></span> Pre-built Templates</h2>
            <p class="description">Select a template to use with your forms. You can duplicate and customize them.</p>
            
            <div class="spfm-template-grid">
                <?php foreach ($templates as $t): ?>
                    <div class="spfm-template-card <?php echo $t->status ? '' : 'inactive'; ?>">
                        <div class="template-preview" style="background: linear-gradient(135deg, <?php echo esc_attr($t->primary_color); ?> 0%, <?php echo esc_attr($t->secondary_color); ?> 100%);">
                            <div class="template-preview-inner" style="background: <?php echo esc_attr($t->background_color); ?>; color: <?php echo esc_attr($t->text_color); ?>;">
                                <div class="preview-header" style="background: <?php echo esc_attr($t->header_bg_color); ?>;"></div>
                                <div class="preview-content">
                                    <div class="preview-input"></div>
                                    <div class="preview-input"></div>
                                    <div class="preview-btn" style="background: <?php echo esc_attr($t->primary_color); ?>;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="template-info">
                            <h3><?php echo esc_html($t->name); ?></h3>
                            <p><?php echo esc_html($t->description); ?></p>
                            <div class="template-colors">
                                <span class="color-dot" style="background: <?php echo esc_attr($t->primary_color); ?>;" title="Primary"></span>
                                <span class="color-dot" style="background: <?php echo esc_attr($t->secondary_color); ?>;" title="Secondary"></span>
                                <span class="color-dot" style="background: <?php echo esc_attr($t->background_color); ?>; border: 1px solid #ddd;" title="Background"></span>
                                <span class="color-dot" style="background: <?php echo esc_attr($t->header_bg_color); ?>;" title="Header"></span>
                            </div>
                            <div class="template-actions">
                                <button class="button button-small spfm-duplicate-theme" data-id="<?php echo $t->id; ?>">
                                    <span class="dashicons dashicons-admin-page"></span> Duplicate
                                </button>
                                <button class="button button-small spfm-toggle-status" data-id="<?php echo $t->id; ?>">
                                    <?php echo $t->status ? 'Deactivate' : 'Activate'; ?>
                                </button>
                                <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id=' . $t->id); ?>" class="button button-small">
                                    <span class="dashicons dashicons-edit"></span> Edit
                                </a>
                            </div>
                        </div>
                        <?php if (!$t->status): ?>
                            <div class="inactive-overlay">
                                <span>Inactive</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Custom Themes -->
        <div class="spfm-section">
            <h2><span class="dashicons dashicons-art"></span> Custom Themes</h2>
            <p class="description">Your custom themes created from templates or scratch.</p>
            
            <?php if (empty($custom_themes)): ?>
                <div class="spfm-empty-state">
                    <span class="dashicons dashicons-art"></span>
                    <p>No custom themes yet. Duplicate a template above or create a new one.</p>
                    <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=add'); ?>" class="button button-primary">Create Custom Theme</a>
                </div>
            <?php else: ?>
                <div class="spfm-template-grid">
                    <?php foreach ($custom_themes as $t): ?>
                        <div class="spfm-template-card <?php echo $t->status ? '' : 'inactive'; ?>">
                            <div class="template-preview" style="background: linear-gradient(135deg, <?php echo esc_attr($t->primary_color); ?> 0%, <?php echo esc_attr($t->secondary_color); ?> 100%);">
                                <div class="template-preview-inner" style="background: <?php echo esc_attr($t->background_color); ?>; color: <?php echo esc_attr($t->text_color); ?>;">
                                    <div class="preview-header" style="background: <?php echo esc_attr($t->header_bg_color); ?>;"></div>
                                    <div class="preview-content">
                                        <div class="preview-input"></div>
                                        <div class="preview-input"></div>
                                        <div class="preview-btn" style="background: <?php echo esc_attr($t->primary_color); ?>;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="template-info">
                                <h3><?php echo esc_html($t->name); ?></h3>
                                <p><?php echo esc_html($t->description ?: 'Custom theme'); ?></p>
                                <div class="template-colors">
                                    <span class="color-dot" style="background: <?php echo esc_attr($t->primary_color); ?>;"></span>
                                    <span class="color-dot" style="background: <?php echo esc_attr($t->secondary_color); ?>;"></span>
                                    <span class="color-dot" style="background: <?php echo esc_attr($t->background_color); ?>; border: 1px solid #ddd;"></span>
                                </div>
                                <div class="template-actions">
                                    <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id=' . $t->id); ?>" class="button button-small button-primary">
                                        <span class="dashicons dashicons-edit"></span> Edit
                                    </a>
                                    <button class="button button-small spfm-toggle-status" data-id="<?php echo $t->id; ?>">
                                        <?php echo $t->status ? 'Deactivate' : 'Activate'; ?>
                                    </button>
                                    <button class="button button-small button-link-delete spfm-delete-theme" data-id="<?php echo $t->id; ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                </div>
                            </div>
                            <?php if (!$t->status): ?>
                                <div class="inactive-overlay">
                                    <span>Inactive</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
    <?php else: ?>
        <!-- Add/Edit Theme Form -->
        <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="page-title-action">Back to Themes</a>
        
        <div class="spfm-theme-editor">
            <div class="editor-form">
                <form id="spfm-theme-form" class="spfm-admin-form">
                    <input type="hidden" name="id" value="<?php echo $theme ? $theme->id : ''; ?>">
                    <input type="hidden" name="is_template" value="<?php echo $theme ? $theme->is_template : 0; ?>">
                    
                    <div class="form-section">
                        <h3>Basic Info</h3>
                        <table class="form-table">
                            <tr>
                                <th><label for="name">Theme Name *</label></th>
                                <td><input type="text" name="name" id="name" class="regular-text" required value="<?php echo $theme ? esc_attr($theme->name) : ''; ?>"></td>
                            </tr>
                            <tr>
                                <th><label for="description">Description</label></th>
                                <td><textarea name="description" id="description" rows="2" class="large-text"><?php echo $theme ? esc_textarea($theme->description) : ''; ?></textarea></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="form-section">
                        <h3>Colors</h3>
                        <div class="color-grid">
                            <div class="color-field">
                                <label>Primary Color</label>
                                <input type="color" name="primary_color" id="primary_color" value="<?php echo $theme ? esc_attr($theme->primary_color) : '#007bff'; ?>">
                            </div>
                            <div class="color-field">
                                <label>Secondary Color</label>
                                <input type="color" name="secondary_color" id="secondary_color" value="<?php echo $theme ? esc_attr($theme->secondary_color) : '#6c757d'; ?>">
                            </div>
                            <div class="color-field">
                                <label>Background Color</label>
                                <input type="color" name="background_color" id="background_color" value="<?php echo $theme ? esc_attr($theme->background_color) : '#ffffff'; ?>">
                            </div>
                            <div class="color-field">
                                <label>Text Color</label>
                                <input type="color" name="text_color" id="text_color" value="<?php echo $theme ? esc_attr($theme->text_color) : '#333333'; ?>">
                            </div>
                            <div class="color-field">
                                <label>Accent Color</label>
                                <input type="color" name="accent_color" id="accent_color" value="<?php echo $theme ? esc_attr($theme->accent_color) : '#28a745'; ?>">
                            </div>
                            <div class="color-field">
                                <label>Header Background</label>
                                <input type="color" name="header_bg_color" id="header_bg_color" value="<?php echo $theme ? esc_attr($theme->header_bg_color) : '#667eea'; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Typography & Style</h3>
                        <table class="form-table">
                            <tr>
                                <th><label for="font_family">Body Font</label></th>
                                <td>
                                    <select name="font_family" id="font_family">
                                        <?php foreach ($font_families as $value => $label): ?>
                                            <option value="<?php echo esc_attr($value); ?>" <?php selected($theme ? $theme->font_family : 'Arial, sans-serif', $value); ?>><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="header_font">Header Font</label></th>
                                <td>
                                    <select name="header_font" id="header_font">
                                        <?php foreach ($font_families as $value => $label): ?>
                                            <option value="<?php echo esc_attr($value); ?>" <?php selected($theme ? $theme->header_font : 'Arial, sans-serif', $value); ?>><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="button_style">Button Style</label></th>
                                <td>
                                    <select name="button_style" id="button_style">
                                        <?php foreach ($button_styles as $value => $label): ?>
                                            <option value="<?php echo esc_attr($value); ?>" <?php selected($theme ? $theme->button_style : 'rounded', $value); ?>><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="layout_style">Layout Style</label></th>
                                <td>
                                    <select name="layout_style" id="layout_style">
                                        <?php foreach ($layout_styles as $value => $label): ?>
                                            <option value="<?php echo esc_attr($value); ?>" <?php selected($theme ? $theme->layout_style : 'default', $value); ?>><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="form-section">
                        <h3>Custom CSS</h3>
                        <textarea name="custom_css" id="custom_css" rows="8" class="large-text code"><?php echo $theme ? esc_textarea($theme->custom_css) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-section">
                        <table class="form-table">
                            <tr>
                                <th><label for="status">Status</label></th>
                                <td>
                                    <select name="status" id="status">
                                        <option value="1" <?php selected($theme ? $theme->status : 1, 1); ?>>Active</option>
                                        <option value="0" <?php selected($theme ? $theme->status : 1, 0); ?>>Inactive</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary button-large">
                            <?php echo $theme ? 'Update Theme' : 'Create Theme'; ?>
                        </button>
                        <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="button button-large">Cancel</a>
                    </p>
                </form>
            </div>
            
            <div class="editor-preview">
                <h3>Live Preview</h3>
                <div id="theme-preview" class="theme-preview-container">
                    <div class="preview-form-wrapper">
                        <div class="preview-header">
                            <h2>Sample Form</h2>
                            <p>Preview how your form will look</p>
                        </div>
                        <div class="preview-body">
                            <div class="preview-field">
                                <label>Full Name</label>
                                <input type="text" placeholder="Enter your name">
                            </div>
                            <div class="preview-field">
                                <label>Email Address</label>
                                <input type="email" placeholder="Enter your email">
                            </div>
                            <div class="preview-field">
                                <label>Message</label>
                                <textarea placeholder="Your message..."></textarea>
                            </div>
                            <button type="button" class="preview-submit">Submit Form</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-section {
    background: #fff;
    padding: 25px;
    margin: 20px 0;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.spfm-section h2 {
    margin-top: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.spfm-template-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    margin-top: 20px;
}
.spfm-template-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s;
    position: relative;
}
.spfm-template-card:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}
.spfm-template-card.inactive {
    opacity: 0.7;
}
.inactive-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #dc3545;
    color: #fff;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
}
.template-preview {
    height: 180px;
    padding: 20px;
}
.template-preview-inner {
    height: 100%;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
}
.preview-header {
    height: 40px;
}
.preview-content {
    padding: 15px;
}
.preview-input {
    height: 20px;
    background: rgba(0,0,0,0.1);
    border-radius: 4px;
    margin-bottom: 10px;
}
.preview-btn {
    height: 25px;
    border-radius: 15px;
    width: 60%;
    margin: 0 auto;
}
.template-info {
    padding: 20px;
}
.template-info h3 {
    margin: 0 0 5px 0;
    font-size: 16px;
}
.template-info p {
    color: #666;
    font-size: 13px;
    margin: 0 0 10px 0;
    line-height: 1.4;
}
.template-colors {
    display: flex;
    gap: 5px;
    margin-bottom: 15px;
}
.color-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
}
.template-actions {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}
.template-actions .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
    vertical-align: middle;
}
.spfm-empty-state {
    text-align: center;
    padding: 40px;
    color: #999;
}
.spfm-empty-state .dashicons {
    font-size: 50px;
    width: 50px;
    height: 50px;
    margin-bottom: 15px;
}
/* Theme Editor */
.spfm-theme-editor {
    display: flex;
    gap: 30px;
    margin-top: 20px;
}
.editor-form {
    flex: 1;
    background: #fff;
    padding: 25px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.editor-preview {
    width: 400px;
    position: sticky;
    top: 50px;
    height: fit-content;
}
.editor-preview h3 {
    margin-top: 0;
}
.theme-preview-container {
    background: #f5f5f5;
    border-radius: 10px;
    padding: 20px;
    min-height: 500px;
}
.preview-form-wrapper {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}
.preview-form-wrapper .preview-header {
    padding: 30px;
    text-align: center;
    color: #fff;
}
.preview-form-wrapper .preview-header h2 {
    margin: 0 0 5px 0;
    color: #fff;
}
.preview-form-wrapper .preview-header p {
    margin: 0;
    opacity: 0.9;
}
.preview-body {
    padding: 25px;
}
.preview-field {
    margin-bottom: 15px;
}
.preview-field label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}
.preview-field input,
.preview-field textarea {
    width: 100%;
    padding: 10px;
    border: 2px solid #ddd;
    border-radius: 6px;
}
.preview-submit {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 25px;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
}
.form-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}
.form-section h3 {
    margin-top: 0;
}
.color-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.color-field {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.color-field label {
    font-weight: 500;
    font-size: 13px;
}
.color-field input[type="color"] {
    width: 100%;
    height: 40px;
    border: 1px solid #ddd;
    border-radius: 5px;
    cursor: pointer;
}
@media (max-width: 1200px) {
    .spfm-theme-editor {
        flex-direction: column;
    }
    .editor-preview {
        width: 100%;
        position: static;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Live preview update
    function updatePreview() {
        var primary = $('#primary_color').val();
        var secondary = $('#secondary_color').val();
        var background = $('#background_color').val();
        var text = $('#text_color').val();
        var headerBg = $('#header_bg_color').val();
        var font = $('#font_family').val();
        var headerFont = $('#header_font').val();
        var buttonStyle = $('#button_style').val();
        
        $('.preview-form-wrapper').css({
            'background-color': background,
            'color': text,
            'font-family': font
        });
        
        $('.preview-form-wrapper .preview-header').css({
            'background': 'linear-gradient(135deg, ' + primary + ' 0%, ' + secondary + ' 100%)',
            'font-family': headerFont
        });
        
        var $btn = $('.preview-submit');
        $btn.css('background', primary);
        
        if (buttonStyle === 'gradient') {
            $btn.css('background', 'linear-gradient(135deg, ' + primary + ' 0%, ' + secondary + ' 100%)');
            $btn.css('border-radius', '25px');
        } else if (buttonStyle === 'outline') {
            $btn.css({
                'background': 'transparent',
                'border': '2px solid ' + primary,
                'color': primary
            });
        } else if (buttonStyle === 'square') {
            $btn.css('border-radius', '5px');
        }
        
        $('.preview-field input, .preview-field textarea').on('focus', function() {
            $(this).css('border-color', primary);
        }).on('blur', function() {
            $(this).css('border-color', '#ddd');
        });
    }
    
    $('input[type="color"], select').on('change input', updatePreview);
    updatePreview();
    
    // Duplicate theme
    $('.spfm-duplicate-theme').on('click', function() {
        var id = $(this).data('id');
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_duplicate_theme',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.data.message);
            }
        });
    });
    
    // Toggle status
    $('.spfm-toggle-status').on('click', function() {
        var id = $(this).data('id');
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_toggle_theme_status',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.data.message);
            }
        });
    });
    
    // Delete theme
    $('.spfm-delete-theme').on('click', function() {
        if (!confirm('Are you sure you want to delete this theme?')) return;
        var id = $(this).data('id');
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_theme',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.data.message);
            }
        });
    });
    
    // Save theme
    $('#spfm-theme-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var originalText = $btn.text();
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_theme&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-themes'); ?>';
            } else {
                alert(response.data.message);
                $btn.text(originalText).prop('disabled', false);
            }
        });
    });
});
</script>
