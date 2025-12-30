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

// Get all themes
$all_themes = $themes_handler->get_all(array('per_page' => 100));
$templates = array_filter($all_themes, function($t) { return $t->is_template; });
$custom_themes = array_filter($all_themes, function($t) { return !$t->is_template; });

// Available fonts
$fonts = array(
    'Poppins' => 'Poppins, sans-serif',
    'Roboto' => 'Roboto, sans-serif',
    'Open Sans' => 'Open Sans, sans-serif',
    'Lato' => 'Lato, sans-serif',
    'Montserrat' => 'Montserrat, sans-serif',
    'Inter' => 'Inter, sans-serif',
    'Nunito' => 'Nunito, sans-serif',
    'Raleway' => 'Raleway, sans-serif',
    'Source Sans Pro' => 'Source Sans Pro, sans-serif',
    'Playfair Display' => 'Playfair Display, serif',
    'Merriweather' => 'Merriweather, serif'
);

// Button styles
$button_styles = array(
    'rounded' => 'Rounded',
    'pill' => 'Pill Shape',
    'square' => 'Square',
    'outline' => 'Outline',
    'gradient' => 'Gradient',
    'shadow' => 'With Shadow'
);

// Layout styles
$layout_styles = array(
    'default' => 'Default',
    'card' => 'Card Style',
    'minimal' => 'Minimal',
    'boxed' => 'Boxed',
    'gradient-header' => 'Gradient Header',
    'dark' => 'Dark Mode',
    'split' => 'Split Layout',
    'floating' => 'Floating Card'
);
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">Themes</h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=add'); ?>" class="page-title-action">Create Custom Theme</a>
        
        <!-- Pre-built Templates Section -->
        <div class="themes-section">
            <div class="section-header">
                <h2><span class="dashicons dashicons-art"></span> Pre-built Templates</h2>
                <p>Click on a template to preview or duplicate it to create your own custom theme.</p>
            </div>
            
            <div class="themes-grid">
                <?php foreach ($templates as $t): ?>
                    <div class="theme-card <?php echo $t->status ? '' : 'inactive'; ?>" data-theme-id="<?php echo $t->id; ?>">
                        <div class="theme-preview-box" style="background: linear-gradient(135deg, <?php echo esc_attr($t->primary_color); ?> 0%, <?php echo esc_attr($t->secondary_color); ?> 100%);">
                            <div class="preview-form">
                                <div class="preview-header" style="background: <?php echo esc_attr($t->header_bg_color ?: $t->primary_color); ?>;">
                                    <div class="preview-title"></div>
                                </div>
                                <div class="preview-body" style="background: <?php echo esc_attr($t->background_color); ?>;">
                                    <div class="preview-field"></div>
                                    <div class="preview-field short"></div>
                                    <div class="preview-btn" style="background: <?php echo esc_attr($t->accent_color ?: $t->primary_color); ?>;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="theme-info">
                            <h4><?php echo esc_html($t->name); ?></h4>
                            <div class="theme-colors">
                                <span class="color-dot" style="background: <?php echo esc_attr($t->primary_color); ?>;" title="Primary"></span>
                                <span class="color-dot" style="background: <?php echo esc_attr($t->secondary_color); ?>;" title="Secondary"></span>
                                <span class="color-dot" style="background: <?php echo esc_attr($t->background_color); ?>;" title="Background"></span>
                                <span class="color-dot" style="background: <?php echo esc_attr($t->text_color); ?>;" title="Text"></span>
                            </div>
                            <p class="theme-meta">
                                <span class="layout-badge"><?php echo esc_html($layout_styles[$t->layout_style] ?? 'Default'); ?></span>
                                <span class="status-badge <?php echo $t->status ? 'active' : 'inactive'; ?>">
                                    <?php echo $t->status ? 'Active' : 'Inactive'; ?>
                                </span>
                            </p>
                        </div>
                        <div class="theme-actions">
                            <button class="button preview-theme" data-id="<?php echo $t->id; ?>" title="Live Preview">
                                <span class="dashicons dashicons-visibility"></span> Preview
                            </button>
                            <button class="button duplicate-theme" data-id="<?php echo $t->id; ?>" title="Duplicate">
                                <span class="dashicons dashicons-admin-page"></span> Duplicate
                            </button>
                            <button class="button toggle-status" data-id="<?php echo $t->id; ?>" title="<?php echo $t->status ? 'Deactivate' : 'Activate'; ?>">
                                <span class="dashicons dashicons-<?php echo $t->status ? 'hidden' : 'visibility'; ?>"></span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Custom Themes Section -->
        <div class="themes-section">
            <div class="section-header">
                <h2><span class="dashicons dashicons-admin-customizer"></span> Custom Themes</h2>
                <p>Your custom themes created from templates or from scratch.</p>
            </div>
            
            <?php if (empty($custom_themes)): ?>
                <div class="empty-state">
                    <span class="dashicons dashicons-art"></span>
                    <p>No custom themes yet. Duplicate a template above or create your own!</p>
                    <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=add'); ?>" class="button button-primary">Create Custom Theme</a>
                </div>
            <?php else: ?>
                <div class="themes-grid">
                    <?php foreach ($custom_themes as $t): ?>
                        <div class="theme-card <?php echo $t->status ? '' : 'inactive'; ?>" data-theme-id="<?php echo $t->id; ?>">
                            <div class="theme-preview-box" style="background: linear-gradient(135deg, <?php echo esc_attr($t->primary_color); ?> 0%, <?php echo esc_attr($t->secondary_color); ?> 100%);">
                                <div class="preview-form">
                                    <div class="preview-header" style="background: <?php echo esc_attr($t->header_bg_color ?: $t->primary_color); ?>;">
                                        <div class="preview-title"></div>
                                    </div>
                                    <div class="preview-body" style="background: <?php echo esc_attr($t->background_color); ?>;">
                                        <div class="preview-field"></div>
                                        <div class="preview-field short"></div>
                                        <div class="preview-btn" style="background: <?php echo esc_attr($t->accent_color ?: $t->primary_color); ?>;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="theme-info">
                                <h4><?php echo esc_html($t->name); ?></h4>
                                <div class="theme-colors">
                                    <span class="color-dot" style="background: <?php echo esc_attr($t->primary_color); ?>;"></span>
                                    <span class="color-dot" style="background: <?php echo esc_attr($t->secondary_color); ?>;"></span>
                                    <span class="color-dot" style="background: <?php echo esc_attr($t->background_color); ?>;"></span>
                                    <span class="color-dot" style="background: <?php echo esc_attr($t->text_color); ?>;"></span>
                                </div>
                                <p class="theme-meta">
                                    <span class="layout-badge"><?php echo esc_html($layout_styles[$t->layout_style] ?? 'Default'); ?></span>
                                </p>
                            </div>
                            <div class="theme-actions">
                                <button class="button preview-theme" data-id="<?php echo $t->id; ?>">
                                    <span class="dashicons dashicons-visibility"></span> Preview
                                </button>
                                <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id=' . $t->id); ?>" class="button">
                                    <span class="dashicons dashicons-edit"></span> Edit
                                </a>
                                <button class="button button-link-delete delete-theme" data-id="<?php echo $t->id; ?>">
                                    <span class="dashicons dashicons-trash"></span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
    <?php elseif ($action === 'preview' && $id): ?>
        <!-- Live Preview Page -->
        <?php 
        $preview_theme = $themes_handler->get_by_id($id);
        if (!$preview_theme) {
            echo '<p>Theme not found.</p>';
            return;
        }
        ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="page-title-action">Back to Themes</a>
        
        <div class="theme-preview-page">
            <div class="preview-sidebar">
                <h3>Theme: <?php echo esc_html($preview_theme->name); ?></h3>
                
                <form id="preview-customize-form">
                    <input type="hidden" name="id" value="<?php echo $preview_theme->id; ?>">
                    
                    <div class="customize-section">
                        <h4>Colors</h4>
                        <div class="color-row">
                            <label>Primary</label>
                            <input type="color" name="primary_color" value="<?php echo esc_attr($preview_theme->primary_color); ?>" class="live-update">
                        </div>
                        <div class="color-row">
                            <label>Secondary</label>
                            <input type="color" name="secondary_color" value="<?php echo esc_attr($preview_theme->secondary_color); ?>" class="live-update">
                        </div>
                        <div class="color-row">
                            <label>Background</label>
                            <input type="color" name="background_color" value="<?php echo esc_attr($preview_theme->background_color); ?>" class="live-update">
                        </div>
                        <div class="color-row">
                            <label>Header</label>
                            <input type="color" name="header_bg_color" value="<?php echo esc_attr($preview_theme->header_bg_color ?: $preview_theme->primary_color); ?>" class="live-update">
                        </div>
                        <div class="color-row">
                            <label>Text</label>
                            <input type="color" name="text_color" value="<?php echo esc_attr($preview_theme->text_color); ?>" class="live-update">
                        </div>
                        <div class="color-row">
                            <label>Accent</label>
                            <input type="color" name="accent_color" value="<?php echo esc_attr($preview_theme->accent_color ?: $preview_theme->primary_color); ?>" class="live-update">
                        </div>
                    </div>
                    
                    <div class="customize-section">
                        <h4>Layout</h4>
                        <select name="layout_style" class="live-update">
                            <?php foreach ($layout_styles as $val => $label): ?>
                                <option value="<?php echo $val; ?>" <?php selected($preview_theme->layout_style, $val); ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="customize-section">
                        <h4>Button Style</h4>
                        <select name="button_style" class="live-update">
                            <?php foreach ($button_styles as $val => $label): ?>
                                <option value="<?php echo $val; ?>" <?php selected($preview_theme->button_style, $val); ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="customize-section">
                        <h4>Typography</h4>
                        <label>Body Font</label>
                        <select name="body_font" class="live-update">
                            <?php foreach ($fonts as $name => $family): ?>
                                <option value="<?php echo $name; ?>" <?php selected($preview_theme->body_font, $name); ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>Header Font</label>
                        <select name="header_font" class="live-update">
                            <?php foreach ($fonts as $name => $family): ?>
                                <option value="<?php echo $name; ?>" <?php selected($preview_theme->header_font, $name); ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="customize-actions">
                        <?php if (!$preview_theme->is_template): ?>
                            <button type="submit" class="button button-primary">Save Changes</button>
                        <?php else: ?>
                            <button type="button" class="button button-primary duplicate-from-preview" data-id="<?php echo $preview_theme->id; ?>">
                                Duplicate & Save as Custom
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <div class="preview-main">
                <div class="preview-toolbar">
                    <button class="device-btn active" data-device="desktop" title="Desktop">
                        <span class="dashicons dashicons-desktop"></span>
                    </button>
                    <button class="device-btn" data-device="tablet" title="Tablet">
                        <span class="dashicons dashicons-tablet"></span>
                    </button>
                    <button class="device-btn" data-device="mobile" title="Mobile">
                        <span class="dashicons dashicons-smartphone"></span>
                    </button>
                    <span class="preview-page-indicator">
                        Page <span id="current-page">1</span> of <span id="total-pages">3</span>
                    </span>
                </div>
                
                <div class="preview-frame-container">
                    <iframe id="preview-frame" src="<?php echo admin_url('admin-ajax.php?action=spfm_theme_preview&id=' . $preview_theme->id . '&nonce=' . wp_create_nonce('spfm_nonce')); ?>"></iframe>
                </div>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Add/Edit Theme -->
        <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="page-title-action">Back to Themes</a>
        
        <div class="theme-editor-container">
            <div class="editor-sidebar">
                <form id="spfm-theme-form" class="theme-form">
                    <input type="hidden" name="id" value="<?php echo $theme ? $theme->id : ''; ?>">
                    
                    <div class="form-section">
                        <h3>Basic Info</h3>
                        <div class="form-field">
                            <label for="name">Theme Name *</label>
                            <input type="text" name="name" id="name" required value="<?php echo $theme ? esc_attr($theme->name) : ''; ?>">
                        </div>
                        <div class="form-field">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="2"><?php echo $theme ? esc_textarea($theme->description) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Colors</h3>
                        <div class="colors-grid">
                            <div class="color-field">
                                <label>Primary</label>
                                <input type="color" name="primary_color" value="<?php echo $theme ? esc_attr($theme->primary_color) : '#667eea'; ?>" class="live-preview">
                            </div>
                            <div class="color-field">
                                <label>Secondary</label>
                                <input type="color" name="secondary_color" value="<?php echo $theme ? esc_attr($theme->secondary_color) : '#764ba2'; ?>" class="live-preview">
                            </div>
                            <div class="color-field">
                                <label>Background</label>
                                <input type="color" name="background_color" value="<?php echo $theme ? esc_attr($theme->background_color) : '#ffffff'; ?>" class="live-preview">
                            </div>
                            <div class="color-field">
                                <label>Text</label>
                                <input type="color" name="text_color" value="<?php echo $theme ? esc_attr($theme->text_color) : '#333333'; ?>" class="live-preview">
                            </div>
                            <div class="color-field">
                                <label>Accent</label>
                                <input type="color" name="accent_color" value="<?php echo $theme ? esc_attr($theme->accent_color) : '#667eea'; ?>" class="live-preview">
                            </div>
                            <div class="color-field">
                                <label>Header BG</label>
                                <input type="color" name="header_bg_color" value="<?php echo $theme ? esc_attr($theme->header_bg_color) : '#667eea'; ?>" class="live-preview">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Layout & Style</h3>
                        <div class="form-field">
                            <label>Layout Style</label>
                            <select name="layout_style" class="live-preview">
                                <?php foreach ($layout_styles as $val => $label): ?>
                                    <option value="<?php echo $val; ?>" <?php selected($theme ? $theme->layout_style : 'default', $val); ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Button Style</label>
                            <select name="button_style" class="live-preview">
                                <?php foreach ($button_styles as $val => $label): ?>
                                    <option value="<?php echo $val; ?>" <?php selected($theme ? $theme->button_style : 'rounded', $val); ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Typography</h3>
                        <div class="form-field">
                            <label>Body Font</label>
                            <select name="body_font" class="live-preview">
                                <?php foreach ($fonts as $name => $family): ?>
                                    <option value="<?php echo $name; ?>" <?php selected($theme ? $theme->body_font : 'Poppins', $name); ?>><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Header Font</label>
                            <select name="header_font" class="live-preview">
                                <?php foreach ($fonts as $name => $family): ?>
                                    <option value="<?php echo $name; ?>" <?php selected($theme ? $theme->header_font : 'Poppins', $name); ?>><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Custom CSS</h3>
                        <div class="form-field">
                            <textarea name="custom_css" id="custom_css" rows="6" placeholder="/* Add custom CSS here */"><?php echo $theme ? esc_textarea($theme->custom_css) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="form-field">
                            <label>Status</label>
                            <select name="status">
                                <option value="1" <?php selected($theme ? $theme->status : 1, 1); ?>>Active</option>
                                <option value="0" <?php selected($theme ? $theme->status : 1, 0); ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="button button-primary button-large">
                            <?php echo $theme ? 'Update Theme' : 'Create Theme'; ?>
                        </button>
                        <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="button button-large">Cancel</a>
                    </div>
                </form>
            </div>
            
            <div class="editor-preview">
                <div class="preview-toolbar">
                    <span>Live Preview</span>
                    <div class="device-buttons">
                        <button class="device-btn active" data-device="desktop"><span class="dashicons dashicons-desktop"></span></button>
                        <button class="device-btn" data-device="tablet"><span class="dashicons dashicons-tablet"></span></button>
                        <button class="device-btn" data-device="mobile"><span class="dashicons dashicons-smartphone"></span></button>
                    </div>
                </div>
                <div class="preview-frame-wrapper" id="preview-wrapper">
                    <div class="live-form-preview" id="live-preview">
                        <!-- Preview will be rendered here via JS -->
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="spfm-modal" style="display:none;">
    <div class="modal-content modal-fullscreen">
        <div class="modal-header">
            <h3 id="preview-modal-title">Theme Preview</h3>
            <div class="modal-toolbar">
                <button class="device-btn active" data-device="desktop"><span class="dashicons dashicons-desktop"></span></button>
                <button class="device-btn" data-device="tablet"><span class="dashicons dashicons-tablet"></span></button>
                <button class="device-btn" data-device="mobile"><span class="dashicons dashicons-smartphone"></span></button>
            </div>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <iframe id="modal-preview-frame" src=""></iframe>
        </div>
        <div class="modal-footer">
            <button class="button" onclick="jQuery('#preview-modal').hide();">Close</button>
            <button class="button duplicate-from-modal" id="duplicate-from-modal">Duplicate Theme</button>
            <a href="#" class="button button-primary" id="edit-theme-btn">Edit Theme</a>
        </div>
    </div>
</div>

<style>
/* Themes Grid */
.themes-section {
    margin-top: 25px;
}
.section-header {
    margin-bottom: 20px;
}
.section-header h2 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 5px 0;
}
.section-header p {
    color: #666;
    margin: 0;
}
.themes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}
.theme-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.theme-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.theme-card.inactive {
    opacity: 0.6;
}
.theme-preview-box {
    height: 160px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.preview-form {
    width: 85%;
    height: 120px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 15px rgba(0,0,0,0.2);
}
.preview-header {
    height: 35px;
    padding: 10px;
}
.preview-title {
    width: 60%;
    height: 12px;
    background: rgba(255,255,255,0.5);
    border-radius: 3px;
}
.preview-body {
    padding: 12px;
}
.preview-field {
    height: 14px;
    background: #e0e0e0;
    border-radius: 3px;
    margin-bottom: 8px;
}
.preview-field.short {
    width: 70%;
}
.preview-btn {
    height: 16px;
    width: 50%;
    border-radius: 8px;
    margin: 10px auto 0;
}
.theme-info {
    padding: 15px;
}
.theme-info h4 {
    margin: 0 0 10px 0;
}
.theme-colors {
    display: flex;
    gap: 5px;
    margin-bottom: 10px;
}
.color-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
.theme-meta {
    display: flex;
    gap: 8px;
    margin: 0;
}
.layout-badge {
    font-size: 11px;
    background: #e9ecef;
    padding: 2px 8px;
    border-radius: 10px;
}
.status-badge {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 10px;
}
.status-badge.active {
    background: #d4edda;
    color: #155724;
}
.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}
.theme-actions {
    display: flex;
    gap: 5px;
    padding: 0 15px 15px;
}
.theme-actions .button {
    flex: 1;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
}
.theme-actions .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 12px;
}
.empty-state .dashicons {
    font-size: 50px;
    width: 50px;
    height: 50px;
    color: #ccc;
}

/* Theme Editor */
.theme-editor-container {
    display: flex;
    gap: 25px;
    margin-top: 20px;
    height: calc(100vh - 150px);
}
.editor-sidebar {
    width: 380px;
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    overflow-y: auto;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.editor-preview {
    flex: 1;
    background: #f0f0f0;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.form-section {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}
.form-section h3 {
    margin: 0 0 15px 0;
    font-size: 14px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.form-field {
    margin-bottom: 12px;
}
.form-field label {
    display: block;
    margin-bottom: 5px;
    font-size: 13px;
    font-weight: 500;
}
.form-field input[type="text"],
.form-field textarea,
.form-field select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
}
.colors-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
.color-field {
    text-align: center;
}
.color-field label {
    display: block;
    font-size: 11px;
    margin-bottom: 5px;
}
.color-field input[type="color"] {
    width: 50px;
    height: 35px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

/* Preview Frame */
.preview-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    background: #333;
    color: #fff;
}
.device-buttons, .modal-toolbar {
    display: flex;
    gap: 5px;
}
.device-btn {
    background: rgba(255,255,255,0.1);
    border: none;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}
.device-btn.active {
    background: #667eea;
}
.preview-page-indicator {
    font-size: 12px;
    opacity: 0.8;
}
.preview-frame-wrapper {
    flex: 1;
    padding: 20px;
    display: flex;
    justify-content: center;
    overflow: auto;
}
.live-form-preview {
    width: 100%;
    max-width: 700px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.15);
    overflow: hidden;
    transition: all 0.3s;
}
.preview-frame-wrapper.tablet .live-form-preview {
    max-width: 768px;
}
.preview-frame-wrapper.mobile .live-form-preview {
    max-width: 375px;
}

/* Theme Preview Page */
.theme-preview-page {
    display: flex;
    gap: 25px;
    margin-top: 20px;
    height: calc(100vh - 150px);
}
.preview-sidebar {
    width: 320px;
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    overflow-y: auto;
}
.preview-sidebar h3 {
    margin: 0 0 20px 0;
}
.customize-section {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
.customize-section h4 {
    margin: 0 0 12px 0;
    font-size: 13px;
    color: #666;
}
.color-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.color-row label {
    font-size: 13px;
}
.color-row input[type="color"] {
    width: 40px;
    height: 30px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.customize-section select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 6px;
    margin-bottom: 10px;
}
.customize-actions {
    margin-top: 20px;
}
.customize-actions button {
    width: 100%;
}
.preview-main {
    flex: 1;
    background: #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.preview-frame-container {
    flex: 1;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    overflow: auto;
}
#preview-frame, #modal-preview-frame {
    width: 100%;
    max-width: 800px;
    height: 100%;
    min-height: 600px;
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.15);
    background: #fff;
    transition: all 0.3s;
}
.preview-frame-container.tablet #preview-frame {
    max-width: 768px;
}
.preview-frame-container.mobile #preview-frame {
    max-width: 375px;
}

/* Modal */
.spfm-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-fullscreen {
    width: 95%;
    height: 95%;
    max-width: none;
    display: flex;
    flex-direction: column;
}
.modal-content {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
}
.modal-header {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    background: #333;
    color: #fff;
}
.modal-header h3 {
    margin: 0;
    flex: 1;
    color: #fff;
}
.close-modal {
    background: none;
    border: none;
    color: #fff;
    font-size: 28px;
    cursor: pointer;
    margin-left: 20px;
}
.modal-body {
    flex: 1;
    padding: 20px;
    background: #e9ecef;
    display: flex;
    justify-content: center;
    overflow: auto;
}
#modal-preview-frame {
    border-radius: 12px;
}
.modal-body.tablet #modal-preview-frame {
    max-width: 768px;
}
.modal-body.mobile #modal-preview-frame {
    max-width: 375px;
}
.modal-footer {
    padding: 15px 20px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    border-top: 1px solid #eee;
}
</style>

<script>
jQuery(document).ready(function($) {
    var currentPreviewThemeId = null;
    
    // Preview theme in modal
    $('.preview-theme').on('click', function() {
        var id = $(this).data('id');
        currentPreviewThemeId = id;
        var themeName = $(this).closest('.theme-card').find('h4').text();
        
        $('#preview-modal-title').text('Preview: ' + themeName);
        $('#modal-preview-frame').attr('src', spfm_ajax.ajax_url + '?action=spfm_theme_preview&id=' + id + '&nonce=' + spfm_ajax.nonce);
        $('#edit-theme-btn').attr('href', '<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id='); ?>' + id);
        $('#preview-modal').show();
    });
    
    // Close modal
    $('.close-modal').on('click', function() {
        $(this).closest('.spfm-modal').hide();
    });
    
    // Device switching in modal
    $('#preview-modal .device-btn').on('click', function() {
        $('#preview-modal .device-btn').removeClass('active');
        $(this).addClass('active');
        var device = $(this).data('device');
        $('#preview-modal .modal-body').removeClass('desktop tablet mobile').addClass(device);
    });
    
    // Device switching in preview page
    $('.preview-frame-container').closest('.preview-main').find('.device-btn').on('click', function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        var device = $(this).data('device');
        $('.preview-frame-container').removeClass('desktop tablet mobile').addClass(device);
    });
    
    // Device switching in editor
    $('.editor-preview .device-btn').on('click', function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        var device = $(this).data('device');
        $('#preview-wrapper').removeClass('desktop tablet mobile').addClass(device);
    });
    
    // Duplicate theme
    $('.duplicate-theme, .duplicate-from-modal').on('click', function() {
        var id = $(this).data('id') || currentPreviewThemeId;
        if (!id) return;
        
        $(this).text('Duplicating...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_duplicate_theme',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id='); ?>' + response.data.id;
            } else {
                alert(response.data.message || 'Failed to duplicate theme');
                location.reload();
            }
        });
    });
    
    // Toggle status
    $('.toggle-status').on('click', function() {
        var id = $(this).data('id');
        var $btn = $(this);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_toggle_theme_status',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });
    
    // Delete theme
    $('.delete-theme').on('click', function() {
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
    
    // Live preview in editor
    function updateLivePreview() {
        var form = $('#spfm-theme-form');
        var primaryColor = form.find('[name="primary_color"]').val();
        var secondaryColor = form.find('[name="secondary_color"]').val();
        var backgroundColor = form.find('[name="background_color"]').val();
        var textColor = form.find('[name="text_color"]').val();
        var accentColor = form.find('[name="accent_color"]').val();
        var headerBgColor = form.find('[name="header_bg_color"]').val();
        var bodyFont = form.find('[name="body_font"]').val();
        var buttonStyle = form.find('[name="button_style"]').val();
        var layoutStyle = form.find('[name="layout_style"]').val();
        
        var buttonRadius = '8px';
        if (buttonStyle === 'pill') buttonRadius = '50px';
        else if (buttonStyle === 'square') buttonRadius = '0';
        
        var headerGradient = 'linear-gradient(135deg, ' + primaryColor + ' 0%, ' + secondaryColor + ' 100%)';
        if (layoutStyle === 'minimal') headerGradient = primaryColor;
        
        var previewHtml = `
            <div class="form-preview-inner" style="font-family: ${bodyFont}, sans-serif; background: ${backgroundColor};">
                <div class="form-header" style="background: ${headerGradient}; padding: 40px 30px; text-align: center;">
                    <h2 style="color: #fff; margin: 0;">Sample Form</h2>
                    <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0;">This is a preview of your form theme</p>
                </div>
                <div class="form-body" style="padding: 30px; color: ${textColor};">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Full Name *</label>
                        <input type="text" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px;" placeholder="Enter your name">
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Email Address *</label>
                        <input type="email" style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px;" placeholder="Enter your email">
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Message</label>
                        <textarea style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px;" rows="4" placeholder="Your message..."></textarea>
                    </div>
                    <div style="text-align: center; margin-top: 25px;">
                        <button style="background: ${buttonStyle === 'gradient' ? headerGradient : accentColor}; color: #fff; border: ${buttonStyle === 'outline' ? '2px solid ' + accentColor : 'none'}; padding: 15px 40px; border-radius: ${buttonRadius}; font-size: 16px; cursor: pointer; ${buttonStyle === 'outline' ? 'background: transparent; color: ' + accentColor : ''} ${buttonStyle === 'shadow' ? 'box-shadow: 0 5px 20px rgba(0,0,0,0.2);' : ''}">Submit Form</button>
                    </div>
                </div>
            </div>
        `;
        
        $('#live-preview').html(previewHtml);
    }
    
    // Initialize live preview
    if ($('#spfm-theme-form').length) {
        updateLivePreview();
        $('.live-preview').on('input change', updateLivePreview);
    }
    
    // Save theme
    $('#spfm-theme-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_theme&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-themes'); ?>';
            } else {
                alert(response.data.message);
                $btn.text('Save Theme').prop('disabled', false);
            }
        });
    });
    
    // Live update in preview page
    $('#preview-customize-form .live-update').on('input change', function() {
        var form = $('#preview-customize-form');
        var params = form.serialize();
        params += '&action=spfm_theme_preview&nonce=' + spfm_ajax.nonce;
        $('#preview-frame').attr('src', spfm_ajax.ajax_url + '?' + params);
    });
    
    // Save from preview page
    $('#preview-customize-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_theme&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                alert('Theme saved successfully!');
                $btn.text('Save Changes').prop('disabled', false);
            } else {
                alert(response.data.message);
                $btn.text('Save Changes').prop('disabled', false);
            }
        });
    });
    
    // Duplicate from preview
    $('.duplicate-from-preview').on('click', function() {
        var id = $(this).data('id');
        var form = $('#preview-customize-form');
        
        $(this).text('Creating...').prop('disabled', true);
        
        // First duplicate, then update with current values
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_duplicate_theme',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                // Now update the new theme with current form values
                var formData = form.serialize();
                formData += '&action=spfm_save_theme&nonce=' + spfm_ajax.nonce;
                formData += '&id=' + response.data.id;
                
                $.post(spfm_ajax.ajax_url, formData, function(r) {
                    window.location.href = '<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id='); ?>' + response.data.id;
                });
            } else {
                alert('Failed to create custom theme');
                location.reload();
            }
        });
    });
});
</script>
