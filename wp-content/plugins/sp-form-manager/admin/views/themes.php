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

// Category icons
$category_icons = array(
    'hospital' => '🏥',
    'dental' => '🦷',
    'eye_care' => '👁️',
    'pediatric' => '👶',
    'cardiology' => '❤️',
    'mental_health' => '🧠',
    'orthopedic' => '🦴',
    'diagnostic' => '🔬'
);
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
            $icon = $category_icons[$theme->category] ?? '🏥';
            
            // Get default values for preview
            $defaults = array();
            if (!empty($theme_complete->pages)) {
                foreach ($theme_complete->pages as $page) {
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
            $headline = isset($defaults['headline']) ? $defaults['headline'] : 'Your Health, Our Priority';
            $subheadline = isset($defaults['subheadline']) ? substr($defaults['subheadline'], 0, 60) . '...' : 'Quality healthcare services...';
        ?>
            <div class="template-card" data-category="<?php echo esc_attr($theme->category); ?>">
                <div class="template-preview">
                    <?php if ($theme->is_template): ?>
                        <span class="template-badge">Pre-built</span>
                    <?php endif; ?>
                    
                    <!-- Actual Template Preview -->
                    <div class="live-template-preview" style="--primary: <?php echo esc_attr($theme->primary_color); ?>; --secondary: <?php echo esc_attr($theme->secondary_color); ?>; --accent: <?php echo esc_attr($theme->accent_color); ?>; --bg: <?php echo esc_attr($theme->background_color); ?>; --text: <?php echo esc_attr($theme->text_color); ?>; --header-bg: <?php echo esc_attr($theme->header_bg_color); ?>; --footer-bg: <?php echo esc_attr($theme->footer_bg_color); ?>;">
                        <!-- Top Bar -->
                        <div class="prev-topbar">
                            <span>📞 +1 (555) 123-4567</span>
                            <span>🚨 Emergency</span>
                        </div>
                        <!-- Header -->
                        <div class="prev-header">
                            <div class="prev-logo">
                                <span class="prev-logo-icon"><?php echo $icon; ?></span>
                                <span class="prev-logo-text"><?php echo esc_html(substr($theme->name, 0, 15)); ?></span>
                            </div>
                            <div class="prev-nav">
                                <span>Home</span>
                                <span>About</span>
                                <span>Services</span>
                                <span>Contact</span>
                            </div>
                            <div class="prev-cta-btn">Book Now</div>
                        </div>
                        <!-- Hero -->
                        <div class="prev-hero">
                            <div class="prev-hero-content">
                                <h3><?php echo esc_html(substr($headline, 0, 25)); ?></h3>
                                <p><?php echo esc_html(substr($subheadline, 0, 40)); ?>...</p>
                                <div class="prev-hero-btns">
                                    <span class="prev-btn-primary">Get Started</span>
                                    <span class="prev-btn-secondary">Learn More</span>
                                </div>
                            </div>
                        </div>
                        <!-- Info Cards -->
                        <div class="prev-cards">
                            <div class="prev-card">
                                <div class="prev-card-icon">🕐</div>
                                <div class="prev-card-text">
                                    <strong>24/7 Emergency</strong>
                                    <span>Always available</span>
                                </div>
                            </div>
                            <div class="prev-card">
                                <div class="prev-card-icon">📅</div>
                                <div class="prev-card-text">
                                    <strong>Appointments</strong>
                                    <span>Easy booking</span>
                                </div>
                            </div>
                            <div class="prev-card">
                                <div class="prev-card-icon">🛡️</div>
                                <div class="prev-card-text">
                                    <strong>Quality Care</strong>
                                    <span>Certified doctors</span>
                                </div>
                            </div>
                        </div>
                        <!-- Services Section -->
                        <div class="prev-services">
                            <div class="prev-section-title">Our Services</div>
                            <div class="prev-services-grid">
                                <div class="prev-service">
                                    <div class="prev-service-icon">❤️</div>
                                    <span>Emergency</span>
                                </div>
                                <div class="prev-service">
                                    <div class="prev-service-icon">🏥</div>
                                    <span>Surgery</span>
                                </div>
                                <div class="prev-service">
                                    <div class="prev-service-icon">💊</div>
                                    <span>Medicine</span>
                                </div>
                                <div class="prev-service">
                                    <div class="prev-service-icon">👶</div>
                                    <span>Pediatrics</span>
                                </div>
                            </div>
                        </div>
                        <!-- Stats -->
                        <div class="prev-stats">
                            <div class="prev-stat">
                                <strong>50+</strong>
                                <span>Years</span>
                            </div>
                            <div class="prev-stat">
                                <strong>200+</strong>
                                <span>Doctors</span>
                            </div>
                            <div class="prev-stat">
                                <strong>100K+</strong>
                                <span>Patients</span>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="prev-footer">
                            <div class="prev-footer-cols">
                                <div class="prev-footer-col">
                                    <strong>About Us</strong>
                                    <span>Healthcare excellence...</span>
                                </div>
                                <div class="prev-footer-col">
                                    <strong>Quick Links</strong>
                                    <span>Home • About • Services</span>
                                </div>
                                <div class="prev-footer-col">
                                    <strong>Contact</strong>
                                    <span>📍 123 Medical Dr</span>
                                </div>
                            </div>
                            <div class="prev-copyright">© 2024 <?php echo esc_html(substr($theme->name, 0, 15)); ?></div>
                        </div>
                    </div>
                    
                    <!-- Hover Overlay with Buttons -->
                    <div class="template-overlay">
                        <div class="overlay-buttons">
                            <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id=' . $theme->id); ?>" class="overlay-btn btn-edit">
                                <span class="dashicons dashicons-edit"></span> Edit
                            </a>
                            <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=preview&id=' . $theme->id); ?>" class="overlay-btn btn-preview" target="_blank">
                                <span class="dashicons dashicons-visibility"></span> Preview
                            </a>
                            <button class="overlay-btn btn-duplicate" onclick="duplicateTemplate(<?php echo $theme->id; ?>)">
                                <span class="dashicons dashicons-admin-page"></span> Duplicate
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="template-info">
                    <div class="template-meta">
                        <span class="template-category" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?>, <?php echo esc_attr($theme->accent_color); ?>);">
                            <?php echo $icon; ?> <?php echo esc_html($categories[$theme->category] ?? ucfirst($theme->category)); ?>
                        </span>
                        <span class="template-pages"><?php echo $page_count; ?> Pages</span>
                    </div>
                    <h3><?php echo esc_html($theme->name); ?></h3>
                    <p><?php echo esc_html(wp_trim_words($theme->description, 12)); ?></p>
                    
                    <?php if (!empty($features)): ?>
                    <div class="template-features">
                        <?php foreach (array_slice($features, 0, 3) as $feature): ?>
                            <span class="feature-tag"><?php echo esc_html($feature); ?></span>
                        <?php endforeach; ?>
                        <?php if (count($features) > 3): ?>
                            <span class="feature-more">+<?php echo count($features) - 3; ?></span>
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
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
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
    height: 420px;
    position: relative;
    overflow: hidden;
    background: #f8fafc;
}

/* Live Template Preview - Mini Website */
.live-template-preview {
    width: 100%;
    height: 100%;
    font-size: 6px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    background: var(--bg, #f0fdfa);
}

/* Top Bar */
.prev-topbar {
    background: var(--primary);
    color: #fff;
    padding: 3px 8px;
    display: flex;
    justify-content: space-between;
    font-size: 5px;
}

/* Header */
.prev-header {
    background: var(--header-bg, #fff);
    padding: 6px 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
.prev-logo {
    display: flex;
    align-items: center;
    gap: 4px;
}
.prev-logo-icon {
    font-size: 10px;
}
.prev-logo-text {
    font-weight: 700;
    font-size: 7px;
    color: var(--primary);
}
.prev-nav {
    display: flex;
    gap: 8px;
    font-size: 5px;
    color: var(--text, #333);
}
.prev-cta-btn {
    background: var(--primary);
    color: #fff;
    padding: 3px 8px;
    border-radius: 10px;
    font-size: 5px;
    font-weight: 600;
}

/* Hero */
.prev-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    padding: 20px 15px;
    color: #fff;
    text-align: center;
}
.prev-hero h3 {
    font-size: 10px;
    margin: 0 0 4px 0;
    font-weight: 700;
}
.prev-hero p {
    font-size: 6px;
    opacity: 0.9;
    margin: 0 0 8px 0;
}
.prev-hero-btns {
    display: flex;
    gap: 6px;
    justify-content: center;
}
.prev-btn-primary {
    background: #fff;
    color: var(--primary);
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 5px;
    font-weight: 600;
}
.prev-btn-secondary {
    background: transparent;
    color: #fff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 5px;
    border: 1px solid rgba(255,255,255,0.5);
}

/* Info Cards */
.prev-cards {
    display: flex;
    gap: 6px;
    padding: 0 10px;
    margin-top: -10px;
    position: relative;
    z-index: 5;
}
.prev-card {
    flex: 1;
    background: #fff;
    border-radius: 6px;
    padding: 8px 6px;
    display: flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.prev-card-icon {
    width: 18px;
    height: 18px;
    background: linear-gradient(135deg, var(--primary), var(--accent, var(--primary)));
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
    flex-shrink: 0;
}
.prev-card-text {
    display: flex;
    flex-direction: column;
}
.prev-card-text strong {
    font-size: 5px;
    color: var(--text, #333);
}
.prev-card-text span {
    font-size: 4px;
    color: #666;
}

/* Services Section */
.prev-services {
    padding: 12px 10px;
    background: var(--bg, #f0fdfa);
}
.prev-section-title {
    text-align: center;
    font-size: 8px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 8px;
}
.prev-services-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 6px;
}
.prev-service {
    background: #fff;
    border-radius: 6px;
    padding: 8px 4px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}
.prev-service-icon {
    width: 20px;
    height: 20px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 4px;
    font-size: 9px;
}
.prev-service span {
    font-size: 5px;
    color: var(--text, #333);
}

/* Stats */
.prev-stats {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    padding: 10px 15px;
    display: flex;
    justify-content: space-around;
    color: #fff;
    text-align: center;
}
.prev-stat strong {
    display: block;
    font-size: 10px;
    font-weight: 700;
}
.prev-stat span {
    font-size: 5px;
    opacity: 0.9;
}

/* Footer */
.prev-footer {
    background: var(--footer-bg, #0f172a);
    padding: 10px;
    color: #fff;
    margin-top: auto;
}
.prev-footer-cols {
    display: flex;
    gap: 10px;
    margin-bottom: 6px;
}
.prev-footer-col {
    flex: 1;
}
.prev-footer-col strong {
    display: block;
    font-size: 5px;
    margin-bottom: 2px;
    position: relative;
    padding-bottom: 2px;
}
.prev-footer-col strong::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 12px;
    height: 1px;
    background: var(--primary);
}
.prev-footer-col span {
    font-size: 4px;
    opacity: 0.7;
    display: block;
    line-height: 1.4;
}
.prev-copyright {
    text-align: center;
    font-size: 4px;
    opacity: 0.5;
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 5px;
}

/* Template Badge */
.template-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(255,255,255,0.95);
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    color: #0891b2;
    z-index: 20;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* Overlay - FIXED */
.template-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.75);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 15;
}
.template-card:hover .template-overlay { 
    opacity: 1; 
    visibility: visible;
}

.overlay-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 20px;
}

.overlay-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 28px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s ease;
    min-width: 160px;
}
.overlay-btn .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
}
.btn-edit { 
    background: #fff; 
    color: #333; 
}
.btn-edit:hover {
    background: #f8fafc;
    transform: scale(1.05);
}
.btn-preview { 
    background: #0891b2; 
    color: #fff; 
}
.btn-preview:hover {
    background: #0e7490;
    transform: scale(1.05);
}
.btn-duplicate { 
    background: transparent; 
    color: #fff; 
    border: 2px solid #fff !important; 
}
.btn-duplicate:hover {
    background: rgba(255,255,255,0.1);
    transform: scale(1.05);
}

/* Template Info */
.template-info { padding: 20px; }
.template-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.template-category {
    font-size: 11px;
    color: #fff;
    padding: 5px 14px;
    border-radius: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}
.template-pages { font-size: 13px; color: #64748b; }
.template-info h3 { margin: 0 0 8px 0; font-size: 18px; color: #1e293b; }
.template-info p { margin: 0 0 15px 0; color: #64748b; font-size: 14px; line-height: 1.5; }

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
    });
    $('.color-hex').on('input', function() {
        const val = $(this).val();
        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            $(this).siblings('input[type="color"]').val(val);
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
