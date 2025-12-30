<?php
if (!defined('ABSPATH')) {
    exit;
}

$themes_handler = SPFM_Themes::get_instance();

$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle media uploads
wp_enqueue_media();

$theme = null;
if (($action === 'edit' || $action === 'view') && $id) {
    $theme = $themes_handler->get_theme_complete($id);
}

$templates = $themes_handler->get_templates();
$categories = $themes_handler->get_categories();
$fonts = $themes_handler->get_fonts();

$category_filter = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-admin-appearance"></span> Website Templates
    </h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=add'); ?>" class="page-title-action">Create Custom Template</a>
        
        <p class="description">Pre-built website templates that customers can choose from. Click on any template to edit its content and settings.</p>
        
        <!-- Category Filter -->
        <div class="category-filter">
            <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" 
               class="filter-btn <?php echo empty($category_filter) ? 'active' : ''; ?>">All</a>
            <?php foreach ($categories as $key => $label): ?>
                <a href="<?php echo admin_url('admin.php?page=spfm-themes&category=' . $key); ?>" 
                   class="filter-btn <?php echo $category_filter === $key ? 'active' : ''; ?>">
                    <?php echo esc_html($label); ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <!-- Templates Grid -->
        <div class="templates-grid">
            <?php 
            foreach ($templates as $t): 
                if ($category_filter && $t->category !== $category_filter) continue;
            ?>
                <div class="template-card">
                    <div class="template-preview" style="background: linear-gradient(135deg, <?php echo esc_attr($t->primary_color); ?> 0%, <?php echo esc_attr($t->secondary_color); ?> 100%);">
                        <?php if ($t->preview_image): ?>
                            <img src="<?php echo esc_url($t->preview_image); ?>" alt="<?php echo esc_attr($t->name); ?>">
                        <?php else: ?>
                            <div class="template-mockup">
                                <div class="mockup-header">
                                    <div class="mockup-nav">
                                        <span class="mockup-logo"></span>
                                        <span class="mockup-links"></span>
                                    </div>
                                </div>
                                <div class="mockup-hero">
                                    <div class="mockup-hero-text"></div>
                                    <div class="mockup-hero-btn"></div>
                                </div>
                                <div class="mockup-content">
                                    <div class="mockup-card"></div>
                                    <div class="mockup-card"></div>
                                    <div class="mockup-card"></div>
                                </div>
                                <div class="mockup-footer"></div>
                            </div>
                        <?php endif; ?>
                        <div class="template-overlay">
                            <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id=' . $t->id); ?>" class="overlay-btn">
                                <span class="dashicons dashicons-edit"></span> Edit Template
                            </a>
                            <button class="overlay-btn preview-btn" data-id="<?php echo $t->id; ?>">
                                <span class="dashicons dashicons-visibility"></span> Preview
                            </button>
                        </div>
                    </div>
                    <div class="template-info">
                        <div class="template-header">
                            <h3><?php echo esc_html($t->name); ?></h3>
                            <span class="category-badge"><?php echo esc_html($categories[$t->category] ?? ucfirst($t->category)); ?></span>
                        </div>
                        <p class="template-description"><?php echo esc_html($t->description); ?></p>
                        <div class="template-meta">
                            <span class="meta-item">
                                <span class="dashicons dashicons-admin-page"></span>
                                <?php 
                                $page_count = count($themes_handler->get_theme_pages($t->id));
                                echo $page_count . ' Pages';
                                ?>
                            </span>
                            <div class="color-dots">
                                <span style="background: <?php echo esc_attr($t->primary_color); ?>;" title="Primary"></span>
                                <span style="background: <?php echo esc_attr($t->secondary_color); ?>;" title="Secondary"></span>
                                <span style="background: <?php echo esc_attr($t->accent_color); ?>;" title="Accent"></span>
                            </div>
                        </div>
                        <div class="template-features">
                            <?php 
                            $features = json_decode($t->features, true) ?: array();
                            foreach (array_slice($features, 0, 3) as $feature): 
                            ?>
                                <span class="feature-tag"><?php echo esc_html($feature); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php elseif ($action === 'edit' && $theme): ?>
        <!-- Edit Template -->
        <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="page-title-action">← Back to Templates</a>
        
        <div class="template-editor">
            <form id="template-form" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $theme->id; ?>">
                
                <div class="editor-layout">
                    <!-- Main Editor -->
                    <div class="editor-main">
                        <!-- Basic Info -->
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-info"></span> Basic Information</h3>
                            
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="name">Template Name *</label>
                                    <input type="text" name="name" id="name" required 
                                           value="<?php echo esc_attr($theme->name); ?>">
                                </div>
                                <div class="form-field">
                                    <label for="category">Category</label>
                                    <select name="category" id="category">
                                        <?php foreach ($categories as $key => $label): ?>
                                            <option value="<?php echo $key; ?>" <?php selected($theme->category, $key); ?>>
                                                <?php echo esc_html($label); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-field">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="3"><?php echo esc_textarea($theme->description); ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Preview Image -->
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-format-image"></span> Template Preview Image</h3>
                            
                            <div class="image-upload-field">
                                <div class="image-preview" id="preview-image-container">
                                    <?php if ($theme->preview_image): ?>
                                        <img src="<?php echo esc_url($theme->preview_image); ?>" alt="Preview">
                                        <button type="button" class="remove-image" data-field="preview_image">&times;</button>
                                    <?php else: ?>
                                        <div class="no-image">
                                            <span class="dashicons dashicons-format-image"></span>
                                            <span>No preview image</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" name="preview_image" id="preview_image" value="<?php echo esc_attr($theme->preview_image); ?>">
                                <button type="button" class="button upload-image-btn" data-field="preview_image" data-container="preview-image-container">
                                    <span class="dashicons dashicons-upload"></span> Upload Preview Image
                                </button>
                                <p class="field-hint">Recommended size: 800x600px. This image will be shown in the template selection.</p>
                            </div>
                        </div>
                        
                        <!-- Colors -->
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-art"></span> Color Scheme</h3>
                            
                            <div class="color-grid">
                                <div class="color-field">
                                    <label for="primary_color">Primary Color</label>
                                    <div class="color-input-wrap">
                                        <input type="color" name="primary_color" id="primary_color" 
                                               value="<?php echo esc_attr($theme->primary_color); ?>">
                                        <input type="text" class="color-text" value="<?php echo esc_attr($theme->primary_color); ?>">
                                    </div>
                                </div>
                                <div class="color-field">
                                    <label for="secondary_color">Secondary Color</label>
                                    <div class="color-input-wrap">
                                        <input type="color" name="secondary_color" id="secondary_color" 
                                               value="<?php echo esc_attr($theme->secondary_color); ?>">
                                        <input type="text" class="color-text" value="<?php echo esc_attr($theme->secondary_color); ?>">
                                    </div>
                                </div>
                                <div class="color-field">
                                    <label for="accent_color">Accent Color</label>
                                    <div class="color-input-wrap">
                                        <input type="color" name="accent_color" id="accent_color" 
                                               value="<?php echo esc_attr($theme->accent_color); ?>">
                                        <input type="text" class="color-text" value="<?php echo esc_attr($theme->accent_color); ?>">
                                    </div>
                                </div>
                                <div class="color-field">
                                    <label for="background_color">Background</label>
                                    <div class="color-input-wrap">
                                        <input type="color" name="background_color" id="background_color" 
                                               value="<?php echo esc_attr($theme->background_color); ?>">
                                        <input type="text" class="color-text" value="<?php echo esc_attr($theme->background_color); ?>">
                                    </div>
                                </div>
                                <div class="color-field">
                                    <label for="text_color">Text Color</label>
                                    <div class="color-input-wrap">
                                        <input type="color" name="text_color" id="text_color" 
                                               value="<?php echo esc_attr($theme->text_color); ?>">
                                        <input type="text" class="color-text" value="<?php echo esc_attr($theme->text_color); ?>">
                                    </div>
                                </div>
                                <div class="color-field">
                                    <label for="header_bg_color">Header Background</label>
                                    <div class="color-input-wrap">
                                        <input type="color" name="header_bg_color" id="header_bg_color" 
                                               value="<?php echo esc_attr($theme->header_bg_color); ?>">
                                        <input type="text" class="color-text" value="<?php echo esc_attr($theme->header_bg_color); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Typography -->
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-editor-textcolor"></span> Typography</h3>
                            
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="font_family">Body Font</label>
                                    <select name="font_family" id="font_family">
                                        <?php foreach ($fonts as $font): ?>
                                            <option value="<?php echo $font; ?>" <?php selected($theme->font_family, $font); ?>>
                                                <?php echo $font; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label for="heading_font">Heading Font</label>
                                    <select name="heading_font" id="heading_font">
                                        <?php foreach ($fonts as $font): ?>
                                            <option value="<?php echo $font; ?>" <?php selected($theme->heading_font, $font); ?>>
                                                <?php echo $font; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pages -->
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-admin-page"></span> Template Pages</h3>
                            
                            <div class="pages-list">
                                <?php foreach ($theme->pages as $page_index => $page): ?>
                                    <div class="page-item" data-page-id="<?php echo $page->id; ?>">
                                        <div class="page-header" onclick="togglePageContent(this)">
                                            <span class="dashicons <?php echo esc_attr($page->page_icon); ?>"></span>
                                            <h4><?php echo esc_html($page->page_name); ?></h4>
                                            <?php if ($page->is_required): ?>
                                                <span class="required-badge">Required</span>
                                            <?php endif; ?>
                                            <span class="toggle-icon dashicons dashicons-arrow-down"></span>
                                        </div>
                                        <div class="page-content" style="display: none;">
                                            <p class="page-description"><?php echo esc_html($page->page_description); ?></p>
                                            
                                            <?php foreach ($page->sections as $sec_index => $section): ?>
                                                <div class="section-item">
                                                    <h5><?php echo esc_html($section->section_name); ?></h5>
                                                    <div class="section-fields">
                                                        <?php 
                                                        if (!empty($section->fields)):
                                                            foreach ($section->fields as $field): 
                                                                if ($field['type'] === 'repeater') continue;
                                                        ?>
                                                            <div class="field-item">
                                                                <span class="field-label"><?php echo esc_html($field['label']); ?></span>
                                                                <span class="field-type"><?php echo esc_html($field['type']); ?></span>
                                                            </div>
                                                        <?php 
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="info-box">
                                <span class="dashicons dashicons-info"></span>
                                <div>
                                    Page structure is pre-configured for this template type. Customers will fill in the content for each section when they use this template.
                                </div>
                            </div>
                        </div>
                        
                        <!-- Features -->
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-star-filled"></span> Features</h3>
                            
                            <div class="features-editor" id="features-editor">
                                <?php 
                                $features = json_decode($theme->features, true) ?: array();
                                foreach ($features as $index => $feature): 
                                ?>
                                    <div class="feature-item">
                                        <input type="text" name="features[]" value="<?php echo esc_attr($feature); ?>" placeholder="Feature name">
                                        <button type="button" class="remove-feature" onclick="removeFeature(this)">&times;</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="button add-feature-btn" onclick="addFeature()">
                                <span class="dashicons dashicons-plus-alt"></span> Add Feature
                            </button>
                        </div>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="editor-sidebar">
                        <div class="sidebar-card">
                            <h4>Publish</h4>
                            <div class="publish-info">
                                <div class="info-row">
                                    <span>Status:</span>
                                    <span class="status-badge <?php echo $theme->status ? 'active' : 'inactive'; ?>">
                                        <?php echo $theme->status ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span>Type:</span>
                                    <span><?php echo $theme->is_template ? 'Pre-built Template' : 'Custom Theme'; ?></span>
                                </div>
                            </div>
                            <div class="status-toggle">
                                <label>
                                    <input type="checkbox" name="status" value="1" <?php checked($theme->status, 1); ?>>
                                    Active (visible to customers)
                                </label>
                            </div>
                            <button type="submit" class="button button-primary button-large">
                                <span class="dashicons dashicons-saved"></span> Save Changes
                            </button>
                        </div>
                        
                        <div class="sidebar-card">
                            <h4>Live Preview</h4>
                            <div class="preview-box" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?>, <?php echo esc_attr($theme->secondary_color); ?>);">
                                <div class="preview-mockup">
                                    <div class="pm-header" style="background: <?php echo esc_attr($theme->header_bg_color); ?>;"></div>
                                    <div class="pm-hero"></div>
                                    <div class="pm-content" style="background: <?php echo esc_attr($theme->background_color); ?>;">
                                        <div class="pm-card"></div>
                                        <div class="pm-card"></div>
                                        <div class="pm-card"></div>
                                    </div>
                                </div>
                            </div>
                            <p class="preview-fonts" style="font-family: '<?php echo esc_attr($theme->font_family); ?>';">
                                Body: <?php echo esc_html($theme->font_family); ?>
                            </p>
                            <p class="preview-fonts" style="font-family: '<?php echo esc_attr($theme->heading_font); ?>'; font-weight: 700;">
                                Heading: <?php echo esc_html($theme->heading_font); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
    <?php elseif ($action === 'add'): ?>
        <!-- Add New Template -->
        <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="page-title-action">← Back to Templates</a>
        
        <div class="template-editor">
            <form id="template-form">
                <div class="editor-layout">
                    <div class="editor-main">
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-info"></span> Basic Information</h3>
                            
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="name">Template Name *</label>
                                    <input type="text" name="name" id="name" required placeholder="My Custom Template">
                                </div>
                                <div class="form-field">
                                    <label for="category">Category</label>
                                    <select name="category" id="category">
                                        <?php foreach ($categories as $key => $label): ?>
                                            <option value="<?php echo $key; ?>"><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-field">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="3" placeholder="Describe your template..."></textarea>
                            </div>
                            
                            <div class="form-field">
                                <label>Base Template</label>
                                <p class="field-hint">Select an existing template to duplicate its page structure:</p>
                                <select name="duplicate_from" id="duplicate_from">
                                    <option value="">-- Create from scratch --</option>
                                    <?php foreach ($templates as $t): ?>
                                        <option value="<?php echo $t->id; ?>"><?php echo esc_html($t->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-art"></span> Color Scheme</h3>
                            
                            <div class="color-grid">
                                <div class="color-field">
                                    <label for="primary_color">Primary</label>
                                    <div class="color-input-wrap">
                                        <input type="color" name="primary_color" id="primary_color" value="#667eea">
                                        <input type="text" class="color-text" value="#667eea">
                                    </div>
                                </div>
                                <div class="color-field">
                                    <label for="secondary_color">Secondary</label>
                                    <div class="color-input-wrap">
                                        <input type="color" name="secondary_color" id="secondary_color" value="#764ba2">
                                        <input type="text" class="color-text" value="#764ba2">
                                    </div>
                                </div>
                                <div class="color-field">
                                    <label for="accent_color">Accent</label>
                                    <div class="color-input-wrap">
                                        <input type="color" name="accent_color" id="accent_color" value="#28a745">
                                        <input type="text" class="color-text" value="#28a745">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="editor-sidebar">
                        <div class="sidebar-card">
                            <h4>Create Template</h4>
                            <button type="submit" class="button button-primary button-large">
                                <span class="dashicons dashicons-plus-alt"></span> Create Template
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- Preview Modal -->
<div id="template-preview-modal" class="spfm-modal" style="display:none;">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3 id="preview-modal-title">Template Preview</h3>
            <button class="close-modal" onclick="closePreviewModal()">&times;</button>
        </div>
        <div class="modal-body" id="preview-modal-body">
            <div class="loading">Loading preview...</div>
        </div>
    </div>
</div>

<style>
/* Category Filter */
.category-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 20px 0;
    padding: 15px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.filter-btn {
    padding: 8px 16px;
    background: #f0f0f0;
    border-radius: 20px;
    text-decoration: none;
    color: #666;
    font-size: 13px;
    transition: all 0.3s;
}
.filter-btn:hover {
    background: #e0e0e0;
    color: #333;
}
.filter-btn.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
}

/* Templates Grid */
.templates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 25px;
    margin-top: 20px;
}
.template-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
.template-preview {
    position: relative;
    height: 220px;
    overflow: hidden;
}
.template-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.template-mockup {
    padding: 15px;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.mockup-header {
    background: rgba(255,255,255,0.95);
    padding: 8px 12px;
    border-radius: 6px;
    margin-bottom: 10px;
}
.mockup-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.mockup-logo {
    width: 60px;
    height: 8px;
    background: #333;
    border-radius: 4px;
}
.mockup-links {
    width: 100px;
    height: 6px;
    background: #ddd;
    border-radius: 3px;
}
.mockup-hero {
    background: rgba(255,255,255,0.2);
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 10px;
}
.mockup-hero-text {
    width: 80%;
    height: 12px;
    background: rgba(255,255,255,0.9);
    border-radius: 6px;
    margin: 0 auto 10px;
}
.mockup-hero-btn {
    width: 80px;
    height: 24px;
    background: rgba(255,255,255,0.95);
    border-radius: 12px;
    margin: 0 auto;
}
.mockup-content {
    display: flex;
    gap: 8px;
    flex: 1;
}
.mockup-card {
    flex: 1;
    background: rgba(255,255,255,0.9);
    border-radius: 6px;
}
.mockup-footer {
    height: 20px;
    background: rgba(0,0,0,0.3);
    border-radius: 6px;
    margin-top: 10px;
}
.template-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    transition: opacity 0.3s;
}
.template-card:hover .template-overlay {
    opacity: 1;
}
.overlay-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #fff;
    border: none;
    border-radius: 25px;
    color: #333;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}
.overlay-btn:hover {
    transform: scale(1.05);
    background: #f0f0f0;
}
.template-info {
    padding: 20px;
}
.template-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}
.template-header h3 {
    margin: 0;
    font-size: 18px;
}
.category-badge {
    font-size: 11px;
    background: #e9ecef;
    padding: 3px 10px;
    border-radius: 15px;
    color: #666;
}
.template-description {
    color: #666;
    font-size: 13px;
    margin: 0 0 15px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.template-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: #888;
}
.color-dots {
    display: flex;
    gap: 5px;
}
.color-dots span {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.template-features {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}
.feature-tag {
    font-size: 11px;
    background: #f0f0f0;
    padding: 3px 10px;
    border-radius: 10px;
    color: #666;
}

/* Template Editor */
.template-editor {
    margin-top: 20px;
}
.editor-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 25px;
}
.editor-section {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.editor-section h3 {
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
.editor-section h3 .dashicons {
    color: #667eea;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-field {
    margin-bottom: 20px;
}
.form-field label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}
.form-field input,
.form-field select,
.form-field textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.form-field input:focus,
.form-field select:focus,
.form-field textarea:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
}
.field-hint {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
}

/* Image Upload */
.image-upload-field {
    margin-bottom: 20px;
}
.image-preview {
    width: 100%;
    height: 200px;
    border: 2px dashed #ddd;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
}
.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.image-preview .no-image {
    text-align: center;
    color: #999;
}
.image-preview .no-image .dashicons {
    font-size: 48px;
    width: 48px;
    height: 48px;
    display: block;
    margin-bottom: 10px;
}
.remove-image {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    background: #dc3545;
    color: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
}

/* Color Grid */
.color-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.color-field label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 500;
}
.color-input-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
.color-input-wrap input[type="color"] {
    width: 50px;
    height: 40px;
    padding: 0;
    border: 2px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
}
.color-input-wrap .color-text {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: monospace;
}

/* Pages List */
.pages-list {
    margin-bottom: 20px;
}
.page-item {
    border: 1px solid #eee;
    border-radius: 10px;
    margin-bottom: 10px;
    overflow: hidden;
}
.page-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: #f8f9fa;
    cursor: pointer;
}
.page-header h4 {
    margin: 0;
    flex: 1;
}
.page-header .dashicons:first-child {
    color: #667eea;
}
.required-badge {
    font-size: 10px;
    background: #dc3545;
    color: #fff;
    padding: 2px 8px;
    border-radius: 10px;
}
.toggle-icon {
    transition: transform 0.3s;
}
.page-item.open .toggle-icon {
    transform: rotate(180deg);
}
.page-content {
    padding: 20px;
    border-top: 1px solid #eee;
}
.page-description {
    color: #666;
    margin-bottom: 15px;
}
.section-item {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
}
.section-item h5 {
    margin: 0 0 10px 0;
    color: #667eea;
}
.section-fields {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.field-item {
    font-size: 12px;
    background: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    border: 1px solid #eee;
}
.field-type {
    color: #999;
    margin-left: 5px;
}

/* Features Editor */
.features-editor {
    margin-bottom: 15px;
}
.feature-item {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}
.feature-item input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}
.remove-feature {
    width: 36px;
    height: 36px;
    background: #f8d7da;
    color: #dc3545;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 18px;
}

/* Sidebar */
.editor-sidebar {
    position: sticky;
    top: 50px;
}
.sidebar-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.sidebar-card h4 {
    margin: 0 0 15px 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.publish-info {
    margin-bottom: 15px;
}
.info-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 13px;
}
.status-badge {
    padding: 2px 10px;
    border-radius: 10px;
    font-size: 11px;
}
.status-badge.active {
    background: #d4edda;
    color: #155724;
}
.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}
.status-toggle {
    margin-bottom: 15px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 6px;
}
.status-toggle label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}
.sidebar-card .button-large {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* Preview Box */
.preview-box {
    height: 150px;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 15px;
}
.preview-mockup {
    height: 100%;
    background: #fff;
    border-radius: 6px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.pm-header {
    height: 20px;
}
.pm-hero {
    height: 40px;
    background: currentColor;
    opacity: 0.3;
}
.pm-content {
    flex: 1;
    padding: 8px;
    display: flex;
    gap: 5px;
}
.pm-card {
    flex: 1;
    background: #eee;
    border-radius: 3px;
}
.preview-fonts {
    font-size: 12px;
    margin: 5px 0;
    color: #666;
}

/* Info Box */
.info-box {
    display: flex;
    gap: 15px;
    padding: 15px;
    background: #e7f3ff;
    border-radius: 8px;
    margin-top: 15px;
}
.info-box .dashicons {
    color: #0073aa;
    flex-shrink: 0;
}

/* Modal */
.spfm-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.8);
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-large {
    width: 90%;
    max-width: 1200px;
    max-height: 90vh;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}
.modal-header h3 {
    margin: 0;
}
.close-modal {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #666;
}
.modal-body {
    flex: 1;
    overflow: auto;
    padding: 20px;
}
.loading {
    text-align: center;
    padding: 40px;
    color: #666;
}

@media (max-width: 1200px) {
    .editor-layout {
        grid-template-columns: 1fr;
    }
    .editor-sidebar {
        position: static;
    }
    .color-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .templates-grid {
        grid-template-columns: 1fr;
    }
    .form-row, .color-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Color picker sync
    $('input[type="color"]').on('input', function() {
        $(this).siblings('.color-text').val($(this).val());
    });
    $('.color-text').on('input', function() {
        var val = $(this).val();
        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            $(this).siblings('input[type="color"]').val(val);
        }
    });
    
    // Image upload
    $('.upload-image-btn').on('click', function() {
        var field = $(this).data('field');
        var container = $(this).data('container');
        
        var frame = wp.media({
            title: 'Select Image',
            button: { text: 'Use Image' },
            multiple: false
        });
        
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#' + field).val(attachment.url);
            $('#' + container).html(
                '<img src="' + attachment.url + '" alt="Preview">' +
                '<button type="button" class="remove-image" data-field="' + field + '">&times;</button>'
            );
        });
        
        frame.open();
    });
    
    // Remove image
    $(document).on('click', '.remove-image', function() {
        var field = $(this).data('field');
        $('#' + field).val('');
        $(this).parent().html(
            '<div class="no-image"><span class="dashicons dashicons-format-image"></span><span>No preview image</span></div>'
        );
    });
    
    // Save template
    $('#template-form').on('submit', function(e) {
        e.preventDefault();
        
        var $btn = $(this).find('button[type="submit"]');
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $(this).serialize();
        formData += '&action=spfm_save_theme&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                if (!$('#template-form input[name="id"]').val()) {
                    window.location.href = '<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id='); ?>' + response.data.id;
                } else {
                    alert('Template saved successfully!');
                    $btn.html('<span class="dashicons dashicons-saved"></span> Save Changes').prop('disabled', false);
                }
            } else {
                alert(response.data.message);
                $btn.html('<span class="dashicons dashicons-saved"></span> Save Changes').prop('disabled', false);
            }
        });
    });
    
    // Preview template
    $('.preview-btn').on('click', function() {
        var id = $(this).data('id');
        $('#template-preview-modal').show();
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_get_theme_pages',
            nonce: spfm_ajax.nonce,
            theme_id: id
        }, function(response) {
            if (response.success) {
                $('#preview-modal-title').text(response.data.theme_name + ' - Preview');
                $('#preview-modal-body').html(response.data.html);
            }
        });
    });
});

function togglePageContent(header) {
    var item = jQuery(header).closest('.page-item');
    item.toggleClass('open');
    item.find('.page-content').slideToggle(300);
}

function closePreviewModal() {
    jQuery('#template-preview-modal').hide();
}

function addFeature() {
    jQuery('#features-editor').append(
        '<div class="feature-item">' +
        '<input type="text" name="features[]" placeholder="Feature name">' +
        '<button type="button" class="remove-feature" onclick="removeFeature(this)">&times;</button>' +
        '</div>'
    );
}

function removeFeature(btn) {
    jQuery(btn).closest('.feature-item').remove();
}
</script>
