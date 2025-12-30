<?php
/**
 * Admin Templates/Themes View
 * Professional Hospital Website Templates
 * Version 3.0 - Unique Designs with Button/Link Management
 */

if (!defined('ABSPATH')) {
    exit;
}

$themes_handler = SPFM_Themes::get_instance();
$categories = $themes_handler->get_categories();
$fonts = $themes_handler->get_fonts();

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

// Category icons and preview data
$category_data = array(
    'hospital' => array('icon' => '🏥', 'color' => '#0891b2', 'preview' => 'multi-dept'),
    'dental' => array('icon' => '🦷', 'color' => '#0ea5e9', 'preview' => 'smile'),
    'eye_care' => array('icon' => '👁️', 'color' => '#8b5cf6', 'preview' => 'vision'),
    'pediatric' => array('icon' => '👶', 'color' => '#f97316', 'preview' => 'playful'),
    'cardiology' => array('icon' => '❤️', 'color' => '#dc2626', 'preview' => 'heart'),
    'mental_health' => array('icon' => '🧠', 'color' => '#10b981', 'preview' => 'calm'),
    'orthopedic' => array('icon' => '🦴', 'color' => '#2563eb', 'preview' => 'motion'),
    'diagnostic' => array('icon' => '🔬', 'color' => '#0d9488', 'preview' => 'tech')
);
?>

<div class="spfm-wrap">
    <?php if ($edit_theme): ?>
    <!-- ==================== TEMPLATE EDITOR ==================== -->
    <div class="spfm-template-editor">
        <div class="editor-header">
            <div class="header-left">
                <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="back-link">
                    <span class="dashicons dashicons-arrow-left-alt"></span> Back to Templates
                </a>
                <h1><?php echo esc_html($edit_theme->name); ?></h1>
                <span class="template-badge <?php echo $edit_theme->is_template ? 'prebuilt' : 'custom'; ?>">
                    <?php echo $edit_theme->is_template ? 'Pre-built Template' : 'Custom Template'; ?>
                </span>
            </div>
            <div class="header-right">
                <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=preview&id=' . $edit_theme->id); ?>" class="btn btn-outline" target="_blank">
                    <span class="dashicons dashicons-visibility"></span> Live Preview
                </a>
                <button type="submit" form="theme-edit-form" class="btn btn-primary">
                    <span class="dashicons dashicons-saved"></span> Save Changes
                </button>
            </div>
        </div>
        
        <!-- Editor Tabs -->
        <div class="editor-tabs">
            <button class="tab-btn active" data-tab="settings">
                <span class="dashicons dashicons-admin-settings"></span> Settings
            </button>
            <button class="tab-btn" data-tab="pages">
                <span class="dashicons dashicons-admin-page"></span> Pages & Sections
            </button>
            <button class="tab-btn" data-tab="buttons">
                <span class="dashicons dashicons-admin-links"></span> Buttons & Links
            </button>
            <button class="tab-btn" data-tab="colors">
                <span class="dashicons dashicons-art"></span> Colors & Fonts
            </button>
        </div>
        
        <form id="theme-edit-form">
            <input type="hidden" name="id" value="<?php echo $edit_theme->id; ?>">
            <input type="hidden" name="action" value="spfm_save_theme">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('spfm_ajax'); ?>">
            
            <!-- TAB: Settings -->
            <div class="tab-content active" id="tab-settings">
                <div class="editor-grid">
                    <div class="editor-main">
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
                                                <?php echo ($category_data[$key]['icon'] ?? '🏥') . ' ' . esc_html($label); ?>
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
                        
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-star-filled"></span> Features</h3>
                            <p class="section-desc">Add key features that describe this template</p>
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
                                <input type="text" id="new-feature" placeholder="Add a feature (e.g., 24/7 Emergency)">
                                <button type="button" class="btn btn-small" onclick="addFeature()">+ Add</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="editor-sidebar">
                        <div class="sidebar-card">
                            <h4>Status</h4>
                            <div class="status-toggle">
                                <label class="switch">
                                    <input type="checkbox" name="status" value="1" <?php checked($edit_theme->status, 1); ?>>
                                    <span class="slider"></span>
                                </label>
                                <span>Active</span>
                            </div>
                        </div>
                        <div class="sidebar-card">
                            <h4>Template Info</h4>
                            <div class="info-list">
                                <div class="info-item">
                                    <span class="label">Category:</span>
                                    <span class="value"><?php echo ($category_data[$edit_theme->category]['icon'] ?? '') . ' ' . esc_html($categories[$edit_theme->category] ?? 'Hospital'); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Pages:</span>
                                    <span class="value"><?php echo count($edit_theme->pages ?? array()); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Type:</span>
                                    <span class="value"><?php echo $edit_theme->is_template ? 'Pre-built' : 'Custom'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- TAB: Pages & Sections -->
            <div class="tab-content" id="tab-pages">
                <div class="pages-editor-layout">
                    <div class="pages-sidebar">
                        <h4>Pages</h4>
                        <ul class="pages-list">
                            <?php if (!empty($edit_theme->pages)): ?>
                                <?php foreach ($edit_theme->pages as $pageIndex => $page): ?>
                                    <li>
                                        <a href="#" class="page-item <?php echo $pageIndex === 0 ? 'active' : ''; ?>" data-page="<?php echo $pageIndex; ?>">
                                            <span class="dashicons <?php echo esc_attr($page->page_icon); ?>"></span>
                                            <?php echo esc_html($page->page_name); ?>
                                            <span class="section-count"><?php echo count($page->sections ?? array()); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div class="pages-content">
                        <?php if (!empty($edit_theme->pages)): ?>
                            <?php foreach ($edit_theme->pages as $pageIndex => $page): ?>
                                <div class="page-editor" data-page="<?php echo $pageIndex; ?>" style="<?php echo $pageIndex === 0 ? '' : 'display: none;'; ?>">
                                    <div class="page-header">
                                        <h2>
                                            <span class="dashicons <?php echo esc_attr($page->page_icon); ?>"></span>
                                            <?php echo esc_html($page->page_name); ?> Page
                                        </h2>
                                        <p><?php echo esc_html($page->page_description); ?></p>
                                    </div>
                                    
                                    <?php if (!empty($page->sections)): ?>
                                        <?php foreach ($page->sections as $sectionIndex => $section): 
                                            $defaults = is_array($section->default_values) ? $section->default_values : (json_decode($section->default_values, true) ?: array());
                                            $fields = is_array($section->fields) ? $section->fields : (json_decode($section->fields, true) ?: array());
                                        ?>
                                            <div class="section-card">
                                                <div class="section-header" onclick="toggleSection(this)">
                                                    <h3>
                                                        <span class="section-icon"><?php echo get_section_icon($section->section_type); ?></span>
                                                        <?php echo esc_html($section->section_name); ?>
                                                    </h3>
                                                    <div class="section-meta">
                                                        <span class="section-type-badge"><?php echo esc_html($section->section_type); ?></span>
                                                        <span class="toggle-icon dashicons dashicons-arrow-down-alt2"></span>
                                                    </div>
                                                </div>
                                                <div class="section-body">
                                                    <?php foreach ($fields as $field): 
                                                        $fieldValue = $defaults[$field['name']] ?? '';
                                                    ?>
                                                        <div class="form-group">
                                                            <label><?php echo esc_html($field['label']); ?></label>
                                                            <?php if ($field['type'] === 'textarea' || $field['type'] === 'editor'): ?>
                                                                <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][<?php echo $field['name']; ?>]" rows="3"><?php echo esc_textarea(is_array($fieldValue) ? json_encode($fieldValue) : $fieldValue); ?></textarea>
                                                            <?php elseif ($field['type'] === 'url'): ?>
                                                                <div class="url-input-group">
                                                                    <span class="url-icon">🔗</span>
                                                                    <input type="url" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][<?php echo $field['name']; ?>]" value="<?php echo esc_attr($fieldValue); ?>" placeholder="https://...">
                                                                </div>
                                                            <?php elseif ($field['type'] === 'image'): ?>
                                                                <div class="image-upload">
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][<?php echo $field['name']; ?>]" value="<?php echo esc_attr($fieldValue); ?>" placeholder="Image URL or upload">
                                                                    <button type="button" class="btn btn-small">Upload</button>
                                                                </div>
                                                            <?php elseif ($field['type'] === 'icon'): ?>
                                                                <div class="icon-picker">
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][<?php echo $field['name']; ?>]" value="<?php echo esc_attr($fieldValue); ?>" class="icon-input">
                                                                    <div class="icon-preview"><?php echo $fieldValue ?: '📋'; ?></div>
                                                                </div>
                                                            <?php elseif ($field['type'] === 'repeater'): ?>
                                                                <div class="repeater-notice">
                                                                    <span class="dashicons dashicons-info"></span>
                                                                    This is a repeater field. Items can be added in the customer form.
                                                                </div>
                                                            <?php else: ?>
                                                                <input type="<?php echo $field['type'] === 'email' ? 'email' : 'text'; ?>" 
                                                                       name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][<?php echo $field['name']; ?>]" 
                                                                       value="<?php echo esc_attr($fieldValue); ?>">
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="empty-state">
                                            <span class="dashicons dashicons-welcome-add-page"></span>
                                            <p>No sections defined for this page</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- TAB: Buttons & Links -->
            <div class="tab-content" id="tab-buttons">
                <div class="buttons-editor">
                    <div class="editor-section">
                        <h3><span class="dashicons dashicons-button"></span> Global Buttons</h3>
                        <p class="section-desc">Configure the main call-to-action buttons that appear across the website</p>
                        
                        <div class="button-config-card">
                            <h4>Primary CTA Button</h4>
                            <div class="form-row three-col">
                                <div class="form-group">
                                    <label>Button Text</label>
                                    <input type="text" name="buttons[primary][text]" value="Book Appointment" placeholder="e.g., Book Appointment">
                                </div>
                                <div class="form-group">
                                    <label>Button Link</label>
                                    <input type="text" name="buttons[primary][link]" value="/contact" placeholder="e.g., /contact or #section">
                                </div>
                                <div class="form-group">
                                    <label>Button Style</label>
                                    <select name="buttons[primary][style]">
                                        <option value="filled">Filled (Solid)</option>
                                        <option value="outline">Outline</option>
                                        <option value="gradient">Gradient</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="button-config-card">
                            <h4>Secondary CTA Button</h4>
                            <div class="form-row three-col">
                                <div class="form-group">
                                    <label>Button Text</label>
                                    <input type="text" name="buttons[secondary][text]" value="Our Services" placeholder="e.g., Learn More">
                                </div>
                                <div class="form-group">
                                    <label>Button Link</label>
                                    <input type="text" name="buttons[secondary][link]" value="/services" placeholder="e.g., /services">
                                </div>
                                <div class="form-group">
                                    <label>Button Style</label>
                                    <select name="buttons[secondary][style]">
                                        <option value="outline" selected>Outline</option>
                                        <option value="filled">Filled (Solid)</option>
                                        <option value="text">Text Only</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="button-config-card">
                            <h4>Header CTA Button</h4>
                            <div class="form-row three-col">
                                <div class="form-group">
                                    <label>Button Text</label>
                                    <input type="text" name="buttons[header][text]" value="Patient Portal" placeholder="e.g., Patient Portal">
                                </div>
                                <div class="form-group">
                                    <label>Button Link</label>
                                    <input type="text" name="buttons[header][link]" value="/portal" placeholder="e.g., /portal">
                                </div>
                                <div class="form-group">
                                    <label>Button Style</label>
                                    <select name="buttons[header][style]">
                                        <option value="filled">Filled (Solid)</option>
                                        <option value="outline">Outline</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="button-config-card emergency">
                            <h4>🚨 Emergency Button</h4>
                            <div class="form-row three-col">
                                <div class="form-group">
                                    <label>Button Text</label>
                                    <input type="text" name="buttons[emergency][text]" value="Emergency: 911" placeholder="e.g., Emergency: 911">
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="buttons[emergency][link]" value="tel:911" placeholder="tel:911">
                                </div>
                                <div class="form-group">
                                    <label>Show in Header</label>
                                    <select name="buttons[emergency][show]">
                                        <option value="1">Yes - Always Show</option>
                                        <option value="0">No - Hide</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="editor-section">
                        <h3><span class="dashicons dashicons-admin-links"></span> Navigation Links</h3>
                        <p class="section-desc">Configure menu and navigation links</p>
                        
                        <div class="links-manager">
                            <div class="links-list" id="nav-links">
                                <?php
                                $nav_links = array(
                                    array('text' => 'Home', 'url' => '/', 'target' => '_self'),
                                    array('text' => 'About', 'url' => '/about', 'target' => '_self'),
                                    array('text' => 'Services', 'url' => '/services', 'target' => '_self'),
                                    array('text' => 'Doctors', 'url' => '/doctors', 'target' => '_self'),
                                    array('text' => 'Contact', 'url' => '/contact', 'target' => '_self')
                                );
                                foreach ($nav_links as $i => $link):
                                ?>
                                    <div class="link-item">
                                        <div class="link-drag-handle">≡</div>
                                        <input type="text" name="links[nav][<?php echo $i; ?>][text]" value="<?php echo esc_attr($link['text']); ?>" placeholder="Link Text">
                                        <input type="text" name="links[nav][<?php echo $i; ?>][url]" value="<?php echo esc_attr($link['url']); ?>" placeholder="URL">
                                        <select name="links[nav][<?php echo $i; ?>][target]">
                                            <option value="_self" <?php selected($link['target'], '_self'); ?>>Same Tab</option>
                                            <option value="_blank" <?php selected($link['target'], '_blank'); ?>>New Tab</option>
                                        </select>
                                        <button type="button" class="btn-remove" onclick="removeLink(this)">×</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-small btn-add-link" onclick="addNavLink()">
                                <span class="dashicons dashicons-plus"></span> Add Navigation Link
                            </button>
                        </div>
                    </div>
                    
                    <div class="editor-section">
                        <h3><span class="dashicons dashicons-share"></span> Social Media Links</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label><span class="social-icon">📘</span> Facebook</label>
                                <input type="url" name="links[social][facebook]" placeholder="https://facebook.com/...">
                            </div>
                            <div class="form-group">
                                <label><span class="social-icon">🐦</span> Twitter/X</label>
                                <input type="url" name="links[social][twitter]" placeholder="https://twitter.com/...">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label><span class="social-icon">📸</span> Instagram</label>
                                <input type="url" name="links[social][instagram]" placeholder="https://instagram.com/...">
                            </div>
                            <div class="form-group">
                                <label><span class="social-icon">💼</span> LinkedIn</label>
                                <input type="url" name="links[social][linkedin]" placeholder="https://linkedin.com/...">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label><span class="social-icon">🎬</span> YouTube</label>
                                <input type="url" name="links[social][youtube]" placeholder="https://youtube.com/...">
                            </div>
                            <div class="form-group">
                                <label><span class="social-icon">💬</span> WhatsApp</label>
                                <input type="url" name="links[social][whatsapp]" placeholder="https://wa.me/...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- TAB: Colors & Fonts -->
            <div class="tab-content" id="tab-colors">
                <div class="editor-grid">
                    <div class="editor-main">
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-art"></span> Color Scheme</h3>
                            <div class="color-grid">
                                <?php
                                $colors = array(
                                    'primary_color' => array('Primary Color', 'Main brand color', $edit_theme->primary_color),
                                    'secondary_color' => array('Secondary Color', 'Complementary', $edit_theme->secondary_color),
                                    'accent_color' => array('Accent Color', 'Highlights & CTAs', $edit_theme->accent_color),
                                    'background_color' => array('Background', 'Page background', $edit_theme->background_color),
                                    'text_color' => array('Text Color', 'Body text', $edit_theme->text_color),
                                    'header_bg_color' => array('Header BG', 'Header area', $edit_theme->header_bg_color),
                                    'footer_bg_color' => array('Footer BG', 'Footer area', $edit_theme->footer_bg_color ?: '#0f172a')
                                );
                                foreach ($colors as $name => $info):
                                ?>
                                <div class="color-item">
                                    <label><?php echo $info[0]; ?></label>
                                    <div class="color-input-group">
                                        <input type="color" name="<?php echo $name; ?>" value="<?php echo esc_attr($info[2]); ?>">
                                        <input type="text" class="color-hex" value="<?php echo esc_attr($info[2]); ?>">
                                    </div>
                                    <span class="color-hint"><?php echo $info[1]; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-editor-textcolor"></span> Typography</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Body Font</label>
                                    <select name="font_family" id="body-font">
                                        <?php foreach ($fonts as $key => $label): ?>
                                            <option value="<?php echo esc_attr($key); ?>" <?php selected($edit_theme->font_family, $key); ?>><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="font-preview" id="body-preview" style="font-family: '<?php echo esc_attr($edit_theme->font_family); ?>';">
                                        The quick brown fox jumps over the lazy dog.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Heading Font</label>
                                    <select name="heading_font" id="heading-font">
                                        <?php foreach ($fonts as $key => $label): ?>
                                            <option value="<?php echo esc_attr($key); ?>" <?php selected($edit_theme->heading_font, $key); ?>><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="font-preview heading" id="heading-preview" style="font-family: '<?php echo esc_attr($edit_theme->heading_font); ?>';">
                                        Hospital Name
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="editor-sidebar">
                        <div class="sidebar-card">
                            <h4>Live Preview</h4>
                            <div class="color-preview-box" id="color-preview">
                                <div class="preview-header" style="background: <?php echo esc_attr($edit_theme->header_bg_color); ?>;">
                                    <span style="color: <?php echo esc_attr($edit_theme->primary_color); ?>;">🏥 Logo</span>
                                    <span class="preview-btn" style="background: <?php echo esc_attr($edit_theme->primary_color); ?>;">CTA</span>
                                </div>
                                <div class="preview-hero" style="background: linear-gradient(135deg, <?php echo esc_attr($edit_theme->primary_color); ?>, <?php echo esc_attr($edit_theme->secondary_color); ?>);">
                                    Hero Section
                                </div>
                                <div class="preview-content" style="background: <?php echo esc_attr($edit_theme->background_color); ?>; color: <?php echo esc_attr($edit_theme->text_color); ?>;">
                                    <span>Content Area</span>
                                    <span class="preview-accent" style="background: <?php echo esc_attr($edit_theme->accent_color); ?>;">Accent</span>
                                </div>
                                <div class="preview-footer" style="background: <?php echo esc_attr($edit_theme->footer_bg_color ?: '#0f172a'); ?>;">
                                    Footer
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <?php else: ?>
    <!-- ==================== TEMPLATES GRID VIEW ==================== -->
    <div class="spfm-header">
        <div class="header-left">
            <h1><span class="dashicons dashicons-layout"></span> Website Templates</h1>
            <p>8 unique professional medical website designs</p>
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
        $used = array();
        foreach ($themes as $t) $used[$t->category] = true;
        foreach ($categories as $key => $label): 
            if (isset($used[$key])):
                $data = $category_data[$key] ?? array('icon' => '🏥', 'color' => '#0891b2');
        ?>
            <button class="filter-btn" data-category="<?php echo esc_attr($key); ?>" style="--filter-color: <?php echo $data['color']; ?>">
                <?php echo $data['icon'] . ' ' . esc_html($label); ?>
            </button>
        <?php endif; endforeach; ?>
    </div>
    
    <!-- Templates Grid -->
    <div class="templates-grid">
        <?php foreach ($themes as $theme): 
            $features = json_decode($theme->features, true) ?: array();
            $theme_complete = $themes_handler->get_theme_complete($theme->id);
            $page_count = $theme_complete ? count($theme_complete->pages) : 0;
            $cat_data = $category_data[$theme->category] ?? array('icon' => '🏥', 'color' => '#0891b2', 'preview' => 'default');
        ?>
            <div class="template-card" data-category="<?php echo esc_attr($theme->category); ?>">
                <div class="template-preview" style="--primary: <?php echo esc_attr($theme->primary_color); ?>; --secondary: <?php echo esc_attr($theme->secondary_color); ?>; --accent: <?php echo esc_attr($theme->accent_color); ?>; --bg: <?php echo esc_attr($theme->background_color); ?>;">
                    <?php if ($theme->is_template): ?>
                        <span class="template-badge">Pre-built</span>
                    <?php endif; ?>
                    
                    <!-- Unique Preview Based on Category -->
                    <?php 
                    $preview_file = SPFM_PLUGIN_PATH . 'admin/views/partials/template-preview-' . $cat_data['preview'] . '.php';
                    if (file_exists($preview_file)) {
                        include $preview_file;
                    } else {
                        include SPFM_PLUGIN_PATH . 'admin/views/partials/template-preview-default.php';
                    }
                    ?>
                    
                    <!-- Hover Overlay -->
                    <div class="template-overlay">
                        <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id=' . $theme->id); ?>" class="overlay-btn btn-edit">
                            <span class="dashicons dashicons-edit"></span> Edit Template
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=preview&id=' . $theme->id); ?>" class="overlay-btn btn-preview" target="_blank">
                            <span class="dashicons dashicons-visibility"></span> Live Preview
                        </a>
                        <button class="overlay-btn btn-duplicate" onclick="duplicateTemplate(<?php echo $theme->id; ?>)">
                            <span class="dashicons dashicons-admin-page"></span> Duplicate
                        </button>
                    </div>
                </div>
                
                <div class="template-info">
                    <div class="template-meta">
                        <span class="template-category" style="background: <?php echo esc_attr($cat_data['color']); ?>;">
                            <?php echo $cat_data['icon'] . ' ' . esc_html($categories[$theme->category] ?? 'Hospital'); ?>
                        </span>
                        <span class="template-pages"><?php echo $page_count; ?> Pages</span>
                    </div>
                    <h3><?php echo esc_html($theme->name); ?></h3>
                    <p><?php echo esc_html(wp_trim_words($theme->description, 15)); ?></p>
                    
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
    <div class="modal-content">
        <div class="modal-header">
            <h2><span class="dashicons dashicons-plus-alt"></span> Create Custom Template</h2>
            <button class="modal-close" onclick="closeModal('create-modal')">&times;</button>
        </div>
        <form id="create-template-form">
            <div class="modal-body">
                <div class="form-group">
                    <label>Template Name <span class="required">*</span></label>
                    <input type="text" name="name" required placeholder="e.g., My Clinic Website">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <?php foreach ($categories as $key => $label): 
                                $icon = $category_data[$key]['icon'] ?? '🏥';
                            ?>
                                <option value="<?php echo esc_attr($key); ?>"><?php echo $icon . ' ' . esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Copy From</label>
                        <select name="duplicate_from">
                            <option value="">-- Start from scratch --</option>
                            <?php foreach ($themes as $t): ?>
                                <option value="<?php echo $t->id; ?>"><?php echo esc_html($t->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="2" placeholder="Brief description of your template"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('create-modal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Template</button>
            </div>
        </form>
    </div>
</div>

<?php
// Helper function for section icons
function get_section_icon($type) {
    $icons = array(
        'header' => '📌', 'topbar' => '📢', 'hero' => '🌟', 'hero_split' => '🌟', 'hero_smile' => '😊',
        'hero_eye' => '👁️', 'hero_kids' => '🎈', 'hero_heart' => '❤️', 'hero_peaceful' => '🌿',
        'hero_motion' => '🏃', 'hero_lab' => '🔬', 'service_cards' => '🎯', 'services' => '⚕️',
        'services_pills' => '💊', 'services_eye' => '👓', 'services_kids' => '🧸', 'services_cardio' => '💓',
        'services_mental' => '🧘', 'stats' => '📊', 'stats_bar' => '📈', 'stats_kids' => '🌈',
        'doctors_carousel' => '👨‍⚕️', 'doctors_grid' => '👩‍⚕️', 'team' => '👥', 'team_grid' => '👥',
        'cta' => '📣', 'cta_emergency' => '🚨', 'cta_consultation' => '📞', 'cta_rehab' => '💪',
        'footer' => '📍', 'footer_hospital' => '🏥', 'footer_dental' => '🦷', 'footer_kids' => '🎈',
        'page_header' => '📄', 'content' => '📝', 'contact_info' => '📞', 'contact_cards' => '📇',
        'contact_dental' => '🦷', 'google_map' => '🗺️', 'testimonials' => '💬', 'testimonials_slider' => '⭐',
        'gallery_preview' => '🖼️', 'before_after' => '✨', 'departments_grid' => '🏢', 'departments_full' => '🏛️',
        'features_grid' => '✅', 'features_playful' => '🎨', 'features_lab' => '🧪', 'lasik_banner' => '👁️',
        'insurance_info' => '💳', 'packages' => '📦', 'test_categories' => '🔬', 'emergency_alert' => '🚨',
        'crisis_banner' => '💚', 'approach_section' => '🎯', 'specialties_grid' => '🦴', 'portal_features' => '📱',
        'doctor_search' => '🔍', 'services_detailed' => '📋', 'services_list' => '📝', 'services_tabs' => '📑',
        'first_visit' => '👋', 'awards' => '🏆', 'history' => '📜', 'mission' => '🎯'
    );
    return $icons[$type] ?? '📄';
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

:root {
    --primary: #0891b2;
    --secondary: #0e7490;
    --accent: #06b6d4;
}

.spfm-wrap { padding: 20px; max-width: 1600px; font-family: 'Inter', -apple-system, sans-serif; }

/* Header */
.spfm-header {
    display: flex; justify-content: space-between; align-items: center;
    background: linear-gradient(135deg, #0891b2, #0e7490); padding: 30px; border-radius: 16px; color: #fff; margin-bottom: 25px;
}
.spfm-header h1 { margin: 0 0 5px 0; font-size: 26px; display: flex; align-items: center; gap: 10px; }
.spfm-header p { margin: 0; opacity: 0.9; }
.spfm-header .btn-primary { background: #fff; color: #0891b2; }

/* Buttons */
.btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; font-size: 14px; }
.btn-primary { background: #0891b2; color: #fff; }
.btn-primary:hover { background: #0e7490; transform: translateY(-1px); }
.btn-secondary { background: #f1f5f9; color: #475569; }
.btn-outline { background: transparent; color: #0891b2; border: 2px solid #0891b2; }
.btn-small { padding: 8px 14px; font-size: 13px; }

/* Category Filter */
.category-filter { display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap; }
.filter-btn { padding: 10px 20px; background: #fff; border: 2px solid #e2e8f0; border-radius: 25px; cursor: pointer; font-weight: 500; transition: all 0.2s; }
.filter-btn:hover { border-color: var(--filter-color, #0891b2); color: var(--filter-color, #0891b2); }
.filter-btn.active { background: var(--filter-color, #0891b2); color: #fff; border-color: var(--filter-color, #0891b2); }

/* Templates Grid */
.templates-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 25px; }
.template-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: all 0.3s; }
.template-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
.template-card.hidden { display: none; }

.template-preview { height: 380px; position: relative; overflow: hidden; background: #f8fafc; }
.template-badge { position: absolute; top: 12px; left: 12px; background: rgba(255,255,255,0.95); padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; color: #0891b2; z-index: 20; }

/* Preview Placeholder (for templates without custom preview file) */
.preview-placeholder {
    width: 100%; height: 100%; display: flex; flex-direction: column;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    padding: 20px; color: #fff;
}
.preview-placeholder .preview-header { background: rgba(255,255,255,0.95); padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; }
.preview-placeholder .preview-content { flex: 1; display: flex; flex-direction: column; gap: 10px; }
.preview-placeholder .preview-section { background: rgba(255,255,255,0.15); padding: 15px; border-radius: 8px; }

/* Overlay */
.template-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.8); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; opacity: 0; visibility: hidden; transition: all 0.3s; z-index: 15; padding: 20px; }
.template-card:hover .template-overlay { opacity: 1; visibility: visible; }
.overlay-btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.2s; min-width: 160px; }
.btn-edit { background: #fff; color: #333; }
.btn-preview { background: #0891b2; color: #fff; }
.btn-duplicate { background: transparent; color: #fff; border: 2px solid #fff !important; }
.overlay-btn:hover { transform: scale(1.03); }

/* Template Info */
.template-info { padding: 20px; }
.template-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.template-category { font-size: 11px; color: #fff; padding: 5px 12px; border-radius: 15px; font-weight: 600; }
.template-pages { font-size: 13px; color: #64748b; }
.template-info h3 { margin: 0 0 8px 0; font-size: 18px; color: #1e293b; }
.template-info p { margin: 0 0 12px 0; color: #64748b; font-size: 14px; line-height: 1.5; }
.template-features { display: flex; flex-wrap: wrap; gap: 6px; }
.feature-tag { font-size: 11px; background: #f1f5f9; padding: 4px 10px; border-radius: 12px; color: #475569; }
.feature-more { font-size: 11px; color: #0891b2; font-weight: 600; }

/* ==================== EDITOR STYLES ==================== */
.spfm-template-editor .editor-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 20px 0; margin-bottom: 25px; border-bottom: 1px solid #e2e8f0;
}
.editor-header .header-left { display: flex; align-items: center; gap: 20px; }
.editor-header h1 { margin: 0; font-size: 24px; }
.editor-header .back-link { display: flex; align-items: center; gap: 5px; color: #64748b; text-decoration: none; }
.editor-header .back-link:hover { color: #0891b2; }
.template-badge { padding: 5px 12px; border-radius: 15px; font-size: 12px; }
.template-badge.prebuilt { background: #dbeafe; color: #1d4ed8; }
.template-badge.custom { background: #dcfce7; color: #166534; }
.editor-header .header-right { display: flex; gap: 10px; }

/* Editor Tabs */
.editor-tabs { display: flex; gap: 5px; margin-bottom: 25px; background: #f1f5f9; padding: 5px; border-radius: 12px; width: fit-content; }
.tab-btn { display: flex; align-items: center; gap: 8px; padding: 12px 20px; background: transparent; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; color: #64748b; transition: all 0.2s; }
.tab-btn:hover { color: #0891b2; }
.tab-btn.active { background: #fff; color: #0891b2; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.tab-content { display: none; animation: fadeIn 0.3s; }
.tab-content.active { display: block; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

/* Editor Grid */
.editor-grid { display: grid; grid-template-columns: 1fr 320px; gap: 25px; }
.editor-section { background: #fff; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.editor-section h3 { margin: 0 0 5px 0; font-size: 16px; display: flex; align-items: center; gap: 8px; }
.editor-section .section-desc { margin: 0 0 20px 0; color: #64748b; font-size: 14px; }

/* Form Elements */
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-row.three-col { grid-template-columns: 1fr 1fr 1fr; }
.form-group { margin-bottom: 18px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 13px; color: #334155; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: border-color 0.2s; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #0891b2; outline: none; }
.required { color: #dc2626; }

/* URL Input */
.url-input-group { display: flex; align-items: center; border: 2px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
.url-input-group .url-icon { padding: 10px; background: #f1f5f9; }
.url-input-group input { border: none; flex: 1; padding: 10px; }
.url-input-group input:focus { outline: none; }

/* Icon Picker */
.icon-picker { display: flex; gap: 10px; align-items: center; }
.icon-picker .icon-input { flex: 1; }
.icon-picker .icon-preview { width: 40px; height: 40px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; }

/* Image Upload */
.image-upload { display: flex; gap: 10px; }
.image-upload input { flex: 1; }

/* Repeater Notice */
.repeater-notice { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px; color: #0369a1; font-size: 13px; display: flex; align-items: center; gap: 8px; }

/* Pages Editor */
.pages-editor-layout { display: grid; grid-template-columns: 240px 1fr; gap: 25px; min-height: 600px; }
.pages-sidebar { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.pages-sidebar h4 { margin: 0 0 15px 0; font-size: 12px; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.5px; }
.pages-list { list-style: none; margin: 0; padding: 0; }
.pages-list li { margin-bottom: 5px; }
.page-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: #475569; text-decoration: none; font-size: 14px; transition: all 0.2s; }
.page-item:hover { background: #f8fafc; color: #0891b2; }
.page-item.active { background: #ecfeff; color: #0891b2; font-weight: 600; }
.page-item .section-count { margin-left: auto; background: #e2e8f0; padding: 2px 8px; border-radius: 10px; font-size: 11px; }
.page-item.active .section-count { background: #0891b2; color: #fff; }

.pages-content { }
.page-editor { }
.page-header { margin-bottom: 20px; }
.page-header h2 { margin: 0 0 5px 0; font-size: 20px; display: flex; align-items: center; gap: 10px; }
.page-header p { margin: 0; color: #64748b; }

/* Section Cards */
.section-card { background: #fff; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow: hidden; }
.section-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; background: #f8fafc; cursor: pointer; transition: background 0.2s; }
.section-header:hover { background: #f1f5f9; }
.section-header h3 { margin: 0; font-size: 15px; display: flex; align-items: center; gap: 10px; }
.section-icon { font-size: 18px; }
.section-meta { display: flex; align-items: center; gap: 10px; }
.section-type-badge { font-size: 11px; background: #e2e8f0; padding: 3px 10px; border-radius: 10px; color: #64748b; }
.toggle-icon { color: #94a3b8; transition: transform 0.2s; }
.section-card.collapsed .toggle-icon { transform: rotate(-90deg); }
.section-card.collapsed .section-body { display: none; }
.section-body { padding: 20px; border-top: 1px solid #e2e8f0; }

/* Empty State */
.empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
.empty-state .dashicons { font-size: 48px; width: 48px; height: 48px; margin-bottom: 15px; }

/* Buttons & Links Editor */
.buttons-editor { max-width: 900px; }
.button-config-card { background: #f8fafc; border-radius: 10px; padding: 20px; margin-bottom: 15px; }
.button-config-card h4 { margin: 0 0 15px 0; font-size: 14px; color: #334155; }
.button-config-card.emergency { background: #fef2f2; border: 1px solid #fecaca; }
.button-config-card.emergency h4 { color: #dc2626; }

/* Links Manager */
.links-manager { }
.links-list { margin-bottom: 15px; }
.link-item { display: flex; gap: 10px; align-items: center; padding: 10px; background: #f8fafc; border-radius: 8px; margin-bottom: 8px; }
.link-drag-handle { cursor: move; color: #94a3b8; }
.link-item input, .link-item select { padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 13px; }
.link-item input:first-of-type { width: 150px; }
.link-item input:nth-of-type(2) { flex: 1; }
.link-item select { width: 100px; }
.btn-remove { background: none; border: none; color: #dc2626; font-size: 18px; cursor: pointer; padding: 5px; }
.btn-add-link { border: 2px dashed #0891b2 !important; background: transparent !important; color: #0891b2 !important; }

/* Social Icons */
.social-icon { margin-right: 5px; }

/* Colors */
.color-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; }
.color-item label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; }
.color-input-group { display: flex; gap: 10px; align-items: center; }
.color-input-group input[type="color"] { width: 45px; height: 38px; padding: 0; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; }
.color-input-group .color-hex { flex: 1; padding: 8px; }
.color-hint { display: block; font-size: 11px; color: #94a3b8; margin-top: 5px; }

/* Font Preview */
.font-preview { margin-top: 10px; padding: 15px; background: #f8fafc; border-radius: 8px; font-size: 16px; }
.font-preview.heading { font-size: 22px; font-weight: 700; }

/* Color Preview */
.color-preview-box { border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; }
.color-preview-box .preview-header { padding: 10px 15px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; font-weight: 600; }
.color-preview-box .preview-btn { padding: 4px 10px; border-radius: 10px; color: #fff; font-size: 10px; }
.color-preview-box .preview-hero { padding: 25px 15px; text-align: center; color: #fff; font-size: 14px; font-weight: 600; }
.color-preview-box .preview-content { padding: 20px 15px; font-size: 12px; display: flex; justify-content: space-between; align-items: center; }
.color-preview-box .preview-accent { padding: 4px 12px; border-radius: 12px; color: #fff; font-size: 10px; }
.color-preview-box .preview-footer { padding: 12px 15px; text-align: center; font-size: 11px; color: #fff; }

/* Sidebar */
.editor-sidebar { }
.sidebar-card { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.sidebar-card h4 { margin: 0 0 15px 0; font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
.status-toggle { display: flex; align-items: center; gap: 12px; }
.info-list .info-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
.info-item:last-child { border-bottom: none; }
.info-item .label { color: #64748b; }
.info-item .value { font-weight: 600; }

/* Switch Toggle */
.switch { position: relative; display: inline-block; width: 44px; height: 24px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; inset: 0; background: #cbd5e1; transition: 0.3s; border-radius: 24px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background: #fff; transition: 0.3s; border-radius: 50%; }
input:checked + .slider { background: #10b981; }
input:checked + .slider:before { transform: translateX(20px); }

/* Features Editor */
.features-editor { display: flex; flex-wrap: wrap; gap: 8px; min-height: 50px; padding: 15px; border: 2px dashed #e2e8f0; border-radius: 8px; margin-bottom: 15px; }
.features-editor .feature-tag { display: flex; align-items: center; gap: 8px; background: #f1f5f9; padding: 6px 12px; border-radius: 15px; font-size: 13px; }
.features-editor .remove-feature { background: none; border: none; color: #dc2626; cursor: pointer; font-size: 16px; padding: 0; }
.add-feature-row { display: flex; gap: 10px; }
.add-feature-row input { flex: 1; }

/* Modal */
.spfm-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center; }
.spfm-modal.active { display: flex; }
.modal-content { background: #fff; border-radius: 16px; width: 90%; max-width: 500px; max-height: 90vh; overflow: hidden; }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; border-bottom: 1px solid #e2e8f0; }
.modal-header h2 { margin: 0; font-size: 18px; display: flex; align-items: center; gap: 10px; }
.modal-close { background: none; border: none; font-size: 28px; cursor: pointer; color: #94a3b8; line-height: 1; }
.modal-body { padding: 25px; overflow-y: auto; }
.modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding: 20px 25px; border-top: 1px solid #e2e8f0; }

/* Responsive */
@media (max-width: 1200px) {
    .editor-grid, .pages-editor-layout { grid-template-columns: 1fr; }
    .pages-sidebar { position: relative; }
}
@media (max-width: 768px) {
    .templates-grid { grid-template-columns: 1fr; }
    .spfm-header { flex-direction: column; gap: 15px; text-align: center; }
    .form-row, .form-row.three-col { grid-template-columns: 1fr; }
    .editor-tabs { flex-wrap: wrap; width: 100%; }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.tab-btn').on('click', function() {
        const tab = $(this).data('tab');
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');
        $('.tab-content').removeClass('active');
        $('#tab-' + tab).addClass('active');
    });
    
    // Page navigation
    $('.page-item').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        $('.page-item').removeClass('active');
        $(this).addClass('active');
        $('.page-editor').hide();
        $('.page-editor[data-page="' + page + '"]').show();
    });
    
    // Category filter
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        const category = $(this).data('category');
        if (category === 'all') {
            $('.template-card').removeClass('hidden');
        } else {
            $('.template-card').each(function() {
                $(this).toggleClass('hidden', $(this).data('category') !== category);
            });
        }
    });
    
    // Color sync
    $('input[type="color"]').on('input', function() {
        $(this).siblings('.color-hex').val($(this).val());
        updateColorPreview();
    });
    $('.color-hex').on('input', function() {
        if (/^#[0-9A-Fa-f]{6}$/.test($(this).val())) {
            $(this).siblings('input[type="color"]').val($(this).val());
            updateColorPreview();
        }
    });
    
    function updateColorPreview() {
        const p = $('input[name="primary_color"]').val();
        const s = $('input[name="secondary_color"]').val();
        const a = $('input[name="accent_color"]').val();
        const bg = $('input[name="background_color"]').val();
        const txt = $('input[name="text_color"]').val();
        const hdr = $('input[name="header_bg_color"]').val();
        const ftr = $('input[name="footer_bg_color"]').val();
        
        const preview = $('#color-preview');
        preview.find('.preview-header').css('background', hdr);
        preview.find('.preview-header span:first').css('color', p);
        preview.find('.preview-btn').css('background', p);
        preview.find('.preview-hero').css('background', 'linear-gradient(135deg, ' + p + ', ' + s + ')');
        preview.find('.preview-content').css({'background': bg, 'color': txt});
        preview.find('.preview-accent').css('background', a);
        preview.find('.preview-footer').css('background', ftr);
    }
    
    // Font preview
    $('#body-font').on('change', function() {
        $('#body-preview').css('font-family', $(this).val());
    });
    $('#heading-font').on('change', function() {
        $('#heading-preview').css('font-family', $(this).val());
    });
    
    // Save form
    $('#theme-edit-form').on('submit', function(e) {
        e.preventDefault();
        const $btn = $(this).find('button[type="submit"]');
        $btn.text('Saving...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, $(this).serialize(), function(response) {
            alert(response.success ? 'Saved successfully!' : (response.data?.message || 'Error'));
            $btn.html('<span class="dashicons dashicons-saved"></span> Save Changes').prop('disabled', false);
        });
    });
    
    // Create template
    $('#create-template-form').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serializeArray();
        formData.push({name: 'action', value: 'spfm_save_theme'});
        formData.push({name: 'nonce', value: spfm_ajax.nonce});
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id='); ?>' + response.data.id;
            } else {
                alert(response.data?.message || 'Error');
            }
        });
    });
});

// Toggle section
function toggleSection(header) {
    header.closest('.section-card').classList.toggle('collapsed');
}

// Features
function addFeature() {
    const input = document.getElementById('new-feature');
    if (input.value.trim()) {
        const tag = document.createElement('div');
        tag.className = 'feature-tag';
        tag.innerHTML = '<input type="hidden" name="features[]" value="' + input.value + '"><span>' + input.value + '</span><button type="button" class="remove-feature" onclick="removeFeature(this)">×</button>';
        document.getElementById('features-editor').appendChild(tag);
        input.value = '';
    }
}
function removeFeature(btn) { btn.closest('.feature-tag').remove(); }

// Navigation links
function addNavLink() {
    const container = document.getElementById('nav-links');
    const count = container.querySelectorAll('.link-item').length;
    const item = document.createElement('div');
    item.className = 'link-item';
    item.innerHTML = '<div class="link-drag-handle">≡</div><input type="text" name="links[nav][' + count + '][text]" placeholder="Link Text"><input type="text" name="links[nav][' + count + '][url]" placeholder="URL"><select name="links[nav][' + count + '][target]"><option value="_self">Same Tab</option><option value="_blank">New Tab</option></select><button type="button" class="btn-remove" onclick="removeLink(this)">×</button>';
    container.appendChild(item);
}
function removeLink(btn) { btn.closest('.link-item').remove(); }

// Modal
function openCreateModal() { document.getElementById('create-modal').classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

// Duplicate
function duplicateTemplate(id) {
    if (confirm('Create a copy of this template?')) {
        jQuery.post(spfm_ajax.ajax_url, {action: 'spfm_duplicate_theme', nonce: spfm_ajax.nonce, id: id}, function(r) {
            if (r.success) window.location.href = '<?php echo admin_url('admin.php?page=spfm-themes&action=edit&id='); ?>' + r.data.id;
        });
    }
}
</script>
