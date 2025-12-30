<?php
/**
 * Admin Templates/Themes View
 * Hospital Website Templates Management
 */

if (!defined('ABSPATH')) {
    exit;
}

$themes_handler = SPFM_Themes::get_instance();
$categories = $themes_handler->get_categories();
$fonts = $themes_handler->get_fonts();

// Check if we're in preview mode
$preview_mode = isset($_GET['action']) && $_GET['action'] === 'preview' && isset($_GET['id']);
$edit_mode = isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']);

if ($preview_mode) {
    $preview_theme = $themes_handler->get_theme_complete(intval($_GET['id']));
    if ($preview_theme) {
        include SPFM_PLUGIN_PATH . 'admin/views/template-preview.php';
        return;
    }
}

$themes = $themes_handler->get_all();

$edit_theme = null;
if ($edit_mode) {
    $edit_theme = $themes_handler->get_theme_complete(intval($_GET['id']));
}
?>

<div class="spfm-wrap">
    <?php if ($edit_theme): ?>
    <!-- Template Editor -->
    <div class="spfm-template-editor">
        <div class="editor-header">
            <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="back-link">
                <span class="dashicons dashicons-arrow-left-alt"></span> Back to Templates
            </a>
            <h1>Edit Template: <?php echo esc_html($edit_theme->name); ?></h1>
        </div>
        
        <form id="theme-edit-form" class="editor-form">
            <input type="hidden" name="id" value="<?php echo $edit_theme->id; ?>">
            
            <div class="editor-layout">
                <div class="editor-main">
                    <!-- Basic Information -->
                    <div class="editor-section">
                        <h3><span class="dashicons dashicons-info"></span> Basic Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Template Name <span class="required">*</span></label>
                                <input type="text" name="name" value="<?php echo esc_attr($edit_theme->name); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category">
                                    <?php foreach ($categories as $key => $label): ?>
                                        <option value="<?php echo esc_attr($key); ?>" <?php selected($edit_theme->category, $key); ?>>
                                            <?php echo esc_html($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="3"><?php echo esc_textarea($edit_theme->description); ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Color Scheme -->
                    <div class="editor-section">
                        <h3><span class="dashicons dashicons-art"></span> Color Scheme</h3>
                        <div class="color-grid">
                            <div class="color-item">
                                <label>Primary Color</label>
                                <div class="color-input-group">
                                    <input type="color" name="primary_color" value="<?php echo esc_attr($edit_theme->primary_color); ?>">
                                    <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->primary_color); ?>">
                                </div>
                            </div>
                            <div class="color-item">
                                <label>Secondary Color</label>
                                <div class="color-input-group">
                                    <input type="color" name="secondary_color" value="<?php echo esc_attr($edit_theme->secondary_color); ?>">
                                    <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->secondary_color); ?>">
                                </div>
                            </div>
                            <div class="color-item">
                                <label>Accent Color</label>
                                <div class="color-input-group">
                                    <input type="color" name="accent_color" value="<?php echo esc_attr($edit_theme->accent_color); ?>">
                                    <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->accent_color); ?>">
                                </div>
                            </div>
                            <div class="color-item">
                                <label>Background</label>
                                <div class="color-input-group">
                                    <input type="color" name="background_color" value="<?php echo esc_attr($edit_theme->background_color); ?>">
                                    <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->background_color); ?>">
                                </div>
                            </div>
                            <div class="color-item">
                                <label>Text Color</label>
                                <div class="color-input-group">
                                    <input type="color" name="text_color" value="<?php echo esc_attr($edit_theme->text_color); ?>">
                                    <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->text_color); ?>">
                                </div>
                            </div>
                            <div class="color-item">
                                <label>Header Background</label>
                                <div class="color-input-group">
                                    <input type="color" name="header_bg_color" value="<?php echo esc_attr($edit_theme->header_bg_color); ?>">
                                    <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->header_bg_color); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Typography -->
                    <div class="editor-section">
                        <h3><span class="dashicons dashicons-editor-textcolor"></span> Typography</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Body Font</label>
                                <select name="font_family">
                                    <?php foreach ($fonts as $key => $label): ?>
                                        <option value="<?php echo esc_attr($key); ?>" <?php selected($edit_theme->font_family, $key); ?>>
                                            <?php echo esc_html($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Heading Font</label>
                                <select name="heading_font">
                                    <?php foreach ($fonts as $key => $label): ?>
                                        <option value="<?php echo esc_attr($key); ?>" <?php selected($edit_theme->heading_font, $key); ?>>
                                            <?php echo esc_html($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Features -->
                    <div class="editor-section">
                        <h3><span class="dashicons dashicons-star-filled"></span> Features</h3>
                        <div class="features-editor" id="features-editor">
                            <?php 
                            $features = json_decode($edit_theme->features, true) ?: array();
                            foreach ($features as $feature): 
                            ?>
                                <div class="feature-tag">
                                    <input type="hidden" name="features[]" value="<?php echo esc_attr($feature); ?>">
                                    <span><?php echo esc_html($feature); ?></span>
                                    <button type="button" class="remove-feature" onclick="removeFeature(this)">×</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="add-feature-row">
                            <input type="text" id="new-feature" placeholder="Add a feature...">
                            <button type="button" class="btn btn-small" onclick="addFeature()">Add</button>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="editor-sidebar">
                    <div class="sidebar-card">
                        <h4>Publish</h4>
                        <div class="status-toggle">
                            <label class="switch">
                                <input type="checkbox" name="status" value="1" <?php checked($edit_theme->status, 1); ?>>
                                <span class="slider"></span>
                            </label>
                            <span>Active</span>
                        </div>
                        <div class="publish-info">
                            <p><strong>Type:</strong> <?php echo $edit_theme->is_template ? 'Pre-built Template' : 'Custom Template'; ?></p>
                            <p><strong>Category:</strong> <?php echo esc_html($categories[$edit_theme->category] ?? ucfirst($edit_theme->category)); ?></p>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <span class="dashicons dashicons-saved"></span> Save Changes
                        </button>
                        <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=preview&id=' . $edit_theme->id); ?>" class="btn btn-secondary btn-block" target="_blank" style="margin-top: 10px; text-align: center;">
                            <span class="dashicons dashicons-visibility"></span> Preview Template
                        </a>
                    </div>
                    
                    <div class="sidebar-card">
                        <h4>Live Preview</h4>
                        <div class="preview-mockup">
                            <div class="mockup-screen" style="--primary: <?php echo esc_attr($edit_theme->primary_color); ?>; --secondary: <?php echo esc_attr($edit_theme->secondary_color); ?>;">
                                <div class="mockup-header"></div>
                                <div class="mockup-hero" style="background: linear-gradient(135deg, <?php echo esc_attr($edit_theme->primary_color); ?>, <?php echo esc_attr($edit_theme->secondary_color); ?>);"></div>
                                <div class="mockup-content">
                                    <div class="mockup-card"></div>
                                    <div class="mockup-card"></div>
                                    <div class="mockup-card"></div>
                                </div>
                                <div class="mockup-footer" style="background: <?php echo esc_attr($edit_theme->footer_bg_color); ?>;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <?php else: ?>
    <!-- Templates Grid View -->
    <div class="spfm-header">
        <div class="header-left">
            <h1><span class="dashicons dashicons-layout"></span> Hospital Website Templates</h1>
            <p>Pre-built medical website templates ready for customization</p>
        </div>
        <div class="header-right">
            <button class="btn btn-primary" onclick="openCreateModal()">
                <span class="dashicons dashicons-plus-alt"></span> Create Custom Template
            </button>
        </div>
    </div>
    
    <!-- Category Filter -->
    <div class="category-filter">
        <button class="filter-btn active" data-category="all">All Templates</button>
        <?php 
        $used_categories = array();
        foreach ($themes as $theme) {
            $used_categories[$theme->category] = true;
        }
        foreach ($categories as $key => $label): 
            if (isset($used_categories[$key])):
        ?>
            <button class="filter-btn" data-category="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></button>
        <?php 
            endif;
        endforeach; 
        ?>
    </div>
    
    <!-- Templates Grid -->
    <div class="templates-grid">
        <?php foreach ($themes as $theme): 
            $features = json_decode($theme->features, true) ?: array();
            $theme_complete = $themes_handler->get_theme_complete($theme->id);
            $page_count = $theme_complete ? count($theme_complete->pages) : 0;
        ?>
            <div class="template-card" data-category="<?php echo esc_attr($theme->category); ?>">
                <div class="template-preview" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?> 0%, <?php echo esc_attr($theme->secondary_color); ?> 100%);">
                    <?php if ($theme->preview_image): ?>
                        <img src="<?php echo esc_url($theme->preview_image); ?>" alt="<?php echo esc_attr($theme->name); ?>">
                    <?php else: ?>
                        <div class="template-mockup">
                            <div class="mockup-nav">
                                <span class="mockup-logo"><?php echo esc_html(substr($theme->name, 0, 12)); ?></span>
                                <div class="mockup-menu">
                                    <span>Home</span>
                                    <span>About</span>
                                    <span>Services</span>
                                </div>
                            </div>
                            <div class="mockup-hero" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?>, <?php echo esc_attr($theme->secondary_color); ?>);">
                                <div class="hero-text">Your Health, Our Priority</div>
                                <div class="hero-btn" style="background: <?php echo esc_attr($theme->accent_color); ?>;">Book Now</div>
                            </div>
                            <div class="mockup-cards">
                                <div class="m-card"></div>
                                <div class="m-card"></div>
                                <div class="m-card"></div>
                            </div>
                            <div class="mockup-footer" style="background: <?php echo esc_attr($theme->footer_bg_color); ?>;"></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="template-overlay">
                        <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id=' . $theme->id); ?>" class="overlay-btn btn-edit">
                            <span class="dashicons dashicons-edit"></span> Edit Template
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=preview&id=' . $theme->id); ?>" class="overlay-btn btn-preview" target="_blank">
                            <span class="dashicons dashicons-visibility"></span> Preview
                        </a>
                        <button class="overlay-btn btn-duplicate" onclick="duplicateTemplate(<?php echo $theme->id; ?>)">
                            <span class="dashicons dashicons-admin-page"></span> Duplicate
                        </button>
                    </div>
                    
                    <?php if ($theme->is_template): ?>
                        <span class="template-badge">Pre-built</span>
                    <?php endif; ?>
                </div>
                
                <div class="template-info">
                    <div class="template-meta">
                        <span class="template-category" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?>, <?php echo esc_attr($theme->accent_color); ?>);">
                            <?php echo esc_html($categories[$theme->category] ?? ucfirst($theme->category)); ?>
                        </span>
                        <span class="template-pages"><?php echo $page_count; ?> Pages</span>
                    </div>
                    <h3><?php echo esc_html($theme->name); ?></h3>
                    <p><?php echo esc_html(wp_trim_words($theme->description, 15)); ?></p>
                    
                    <div class="template-colors">
                        <span class="color-dot" style="background: <?php echo esc_attr($theme->primary_color); ?>;" title="Primary"></span>
                        <span class="color-dot" style="background: <?php echo esc_attr($theme->secondary_color); ?>;" title="Secondary"></span>
                        <span class="color-dot" style="background: <?php echo esc_attr($theme->accent_color); ?>;" title="Accent"></span>
                        <span class="color-dot" style="background: <?php echo esc_attr($theme->background_color); ?>; border: 1px solid #ddd;" title="Background"></span>
                    </div>
                    
                    <?php if (!empty($features)): ?>
                    <div class="template-features">
                        <?php foreach (array_slice($features, 0, 3) as $feature): ?>
                            <span class="feature-tag"><?php echo esc_html($feature); ?></span>
                        <?php endforeach; ?>
                        <?php if (count($features) > 3): ?>
                            <span class="feature-more">+<?php echo count($features) - 3; ?> more</span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Create Template Modal -->
<div class="spfm-modal" id="create-modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2><span class="dashicons dashicons-plus-alt"></span> Create Custom Template</h2>
            <button class="modal-close" onclick="closeModal('create-modal')">&times;</button>
        </div>
        <form id="create-template-form">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Template Name <span class="required">*</span></label>
                        <input type="text" name="name" required placeholder="e.g., My Hospital Template">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <?php foreach ($categories as $key => $label): ?>
                                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="2" placeholder="Brief description of the template"></textarea>
                </div>
                <div class="form-group">
                    <label>Base Template (Copy structure from)</label>
                    <select name="duplicate_from">
                        <option value="">-- Start from scratch --</option>
                        <?php foreach ($themes as $theme): ?>
                            <option value="<?php echo $theme->id; ?>"><?php echo esc_html($theme->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="hint">Select a template to copy its pages and sections structure.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('create-modal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Template</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Templates Grid Styles */
.spfm-wrap { padding: 20px; max-width: 1600px; }

.spfm-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
    padding: 30px;
    border-radius: 16px;
    color: #fff;
}
.spfm-header h1 { margin: 0 0 5px 0; font-size: 28px; display: flex; align-items: center; gap: 10px; }
.spfm-header p { margin: 0; opacity: 0.9; }
.spfm-header .btn-primary { background: #fff; color: #0891b2; }
.spfm-header .btn-primary:hover { background: #f0fdfa; }

/* Category Filter */
.category-filter {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}
.filter-btn {
    padding: 10px 20px;
    background: #fff;
    border: 2px solid #e2e8f0;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
}
.filter-btn:hover { border-color: #0891b2; color: #0891b2; }
.filter-btn.active { background: #0891b2; color: #fff; border-color: #0891b2; }

/* Templates Grid */
.templates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
}

.template-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
.template-card.hidden { display: none; }

.template-preview {
    height: 240px;
    position: relative;
    overflow: hidden;
}
.template-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Mockup Styles */
.template-mockup {
    width: 90%;
    height: 220px;
    margin: 10px auto;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.2);
}
.mockup-nav {
    height: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 12px;
    border-bottom: 1px solid #eee;
    font-size: 9px;
}
.mockup-logo { font-weight: 700; color: #0891b2; }
.mockup-menu { display: flex; gap: 10px; color: #64748b; }
.mockup-hero {
    height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #fff;
}
.hero-text { font-size: 11px; font-weight: 700; margin-bottom: 8px; }
.hero-btn { font-size: 8px; padding: 4px 12px; border-radius: 12px; }
.mockup-cards {
    display: flex;
    gap: 8px;
    padding: 12px;
}
.m-card { flex: 1; height: 50px; background: #f1f5f9; border-radius: 6px; }
.mockup-footer { height: 25px; margin-top: auto; }

.template-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 10px;
    opacity: 0;
    transition: opacity 0.3s;
}
.template-card:hover .template-overlay { opacity: 1; }

.overlay-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    min-width: 150px;
    justify-content: center;
    text-decoration: none;
}
.btn-edit { background: #fff; color: #333; }
.btn-preview { background: #0891b2; color: #fff; }
.btn-duplicate { background: transparent; color: #fff; border: 2px solid #fff !important; }

.template-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: rgba(255,255,255,0.95);
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    color: #0891b2;
}

.template-info { padding: 25px; }
.template-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.template-category {
    font-size: 11px;
    color: #fff;
    padding: 4px 12px;
    border-radius: 15px;
    font-weight: 600;
}
.template-pages { font-size: 13px; color: #64748b; }
.template-info h3 { margin: 0 0 8px 0; font-size: 18px; }
.template-info p { margin: 0 0 15px 0; color: #64748b; font-size: 14px; line-height: 1.5; }

.template-colors {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
}
.color-dot {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.template-features {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.feature-tag {
    font-size: 11px;
    background: #f1f5f9;
    padding: 4px 10px;
    border-radius: 12px;
    color: #475569;
}
.feature-more {
    font-size: 11px;
    color: #0891b2;
    font-weight: 600;
}

/* Editor Styles */
.spfm-template-editor { max-width: 1400px; }
.editor-header {
    margin-bottom: 30px;
}
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #64748b;
    text-decoration: none;
    margin-bottom: 15px;
}
.back-link:hover { color: #0891b2; }
.editor-header h1 { margin: 0; font-size: 28px; }

.editor-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 30px;
}

.editor-section {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.editor-section h3 {
    margin: 0 0 20px 0;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #1e293b;
}
.editor-section h3 .dashicons { color: #0891b2; }

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-group { margin-bottom: 20px; }
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
    color: #334155;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #0891b2;
    outline: none;
}
.hint { font-size: 12px; color: #94a3b8; margin-top: 5px; }

/* Color Grid */
.color-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.color-item label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 600;
}
.color-input-group {
    display: flex;
    gap: 10px;
    align-items: center;
}
.color-input-group input[type="color"] {
    width: 50px;
    height: 40px;
    padding: 0;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}
.color-input-group .color-hex {
    flex: 1;
    padding: 8px 12px;
}

/* Features Editor */
.features-editor {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 15px;
    min-height: 40px;
    padding: 10px;
    border: 2px dashed #e2e8f0;
    border-radius: 8px;
}
.features-editor .feature-tag {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f1f5f9;
    padding: 6px 12px;
    border-radius: 15px;
}
.features-editor .remove-feature {
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    font-size: 16px;
    padding: 0;
}
.add-feature-row {
    display: flex;
    gap: 10px;
}
.add-feature-row input { flex: 1; }

/* Sidebar */
.editor-sidebar { }
.sidebar-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.sidebar-card h4 {
    margin: 0 0 15px 0;
    font-size: 14px;
    color: #64748b;
}
.status-toggle {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}
.publish-info { font-size: 13px; color: #64748b; margin-bottom: 15px; }
.publish-info p { margin: 5px 0; }

/* Switch Toggle */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
}
.switch input { opacity: 0; width: 0; height: 0; }
.slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 26px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
}
input:checked + .slider { background-color: #10b981; }
input:checked + .slider:before { transform: translateX(24px); }

/* Preview Mockup */
.preview-mockup { }
.mockup-screen {
    width: 100%;
    height: 200px;
    background: #f8fafc;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}
.mockup-screen .mockup-header {
    height: 25px;
    background: #fff;
    border-bottom: 1px solid #eee;
}
.mockup-screen .mockup-hero {
    height: 60px;
}
.mockup-screen .mockup-content {
    display: flex;
    gap: 8px;
    padding: 10px;
}
.mockup-screen .mockup-card {
    flex: 1;
    height: 40px;
    background: #e2e8f0;
    border-radius: 4px;
}
.mockup-screen .mockup-footer {
    height: 25px;
    margin-top: auto;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    text-decoration: none;
}
.btn-primary { background: #0891b2; color: #fff; }
.btn-primary:hover { background: #0e7490; }
.btn-secondary { background: #f1f5f9; color: #475569; }
.btn-secondary:hover { background: #e2e8f0; }
.btn-small { padding: 8px 16px; font-size: 13px; }
.btn-block { width: 100%; justify-content: center; }

/* Modal Styles */
.spfm-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}
.spfm-modal.active { display: flex; }
.modal-content {
    background: #fff;
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.modal-lg { max-width: 600px; }
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #e2e8f0;
}
.modal-header h2 {
    margin: 0;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.modal-close {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #94a3b8;
}
.modal-body {
    padding: 25px;
    overflow-y: auto;
}
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 25px;
    border-top: 1px solid #e2e8f0;
}

.required { color: #ef4444; }

@media (max-width: 1024px) {
    .editor-layout { grid-template-columns: 1fr; }
    .form-row { grid-template-columns: 1fr; }
    .color-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .templates-grid { grid-template-columns: 1fr; }
    .spfm-header { flex-direction: column; gap: 20px; }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Category Filter
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        const category = $(this).data('category');
        
        if (category === 'all') {
            $('.template-card').removeClass('hidden');
        } else {
            $('.template-card').each(function() {
                if ($(this).data('category') === category) {
                    $(this).removeClass('hidden');
                } else {
                    $(this).addClass('hidden');
                }
            });
        }
    });
    
    // Color Picker Sync
    $('input[type="color"]').on('input', function() {
        $(this).siblings('.color-hex').val($(this).val());
        updatePreviewMockup();
    });
    $('.color-hex').on('input', function() {
        const val = $(this).val();
        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            $(this).siblings('input[type="color"]').val(val);
            updatePreviewMockup();
        }
    });
    
    // Theme Edit Form Submit
    $('#theme-edit-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serializeArray();
        formData.push({ name: 'action', value: 'spfm_save_theme' });
        formData.push({ name: 'nonce', value: spfm_ajax.nonce });
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                alert('Template saved successfully!');
            } else {
                alert(response.data.message || 'Failed to save template.');
            }
        });
    });
    
    // Create Template Form
    $('#create-template-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serializeArray();
        formData.push({ name: 'action', value: 'spfm_save_theme' });
        formData.push({ name: 'nonce', value: spfm_ajax.nonce });
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id='); ?>' + response.data.id;
            } else {
                alert(response.data.message || 'Failed to create template.');
            }
        });
    });
});

function openCreateModal() {
    document.getElementById('create-modal').classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function addFeature() {
    const input = document.getElementById('new-feature');
    const value = input.value.trim();
    
    if (value) {
        const tag = document.createElement('div');
        tag.className = 'feature-tag';
        tag.innerHTML = `
            <input type="hidden" name="features[]" value="${value}">
            <span>${value}</span>
            <button type="button" class="remove-feature" onclick="removeFeature(this)">×</button>
        `;
        document.getElementById('features-editor').appendChild(tag);
        input.value = '';
    }
}

function removeFeature(btn) {
    btn.closest('.feature-tag').remove();
}

function updatePreviewMockup() {
    const primary = document.querySelector('input[name="primary_color"]').value;
    const secondary = document.querySelector('input[name="secondary_color"]').value;
    const footer = document.querySelector('input[name="footer_bg_color"]')?.value || '#0f172a';
    
    const hero = document.querySelector('.mockup-screen .mockup-hero');
    if (hero) {
        hero.style.background = `linear-gradient(135deg, ${primary}, ${secondary})`;
    }
    
    const footerEl = document.querySelector('.mockup-screen .mockup-footer');
    if (footerEl) {
        footerEl.style.background = footer;
    }
}

function duplicateTemplate(id) {
    if (confirm('Create a copy of this template?')) {
        jQuery.post(spfm_ajax.ajax_url, {
            action: 'spfm_duplicate_theme',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id='); ?>' + response.data.id;
            } else {
                alert('Failed to duplicate template.');
            }
        });
    }
}
</script>
