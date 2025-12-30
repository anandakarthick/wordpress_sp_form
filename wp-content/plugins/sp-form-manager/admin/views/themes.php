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

// Section icons
$section_icons = array(
    'header' => 'admin-home',
    'hero' => 'star-filled',
    'info_cards' => 'grid-view',
    'services' => 'heart',
    'stats' => 'chart-bar',
    'team' => 'groups',
    'contact_info' => 'location',
    'blog' => 'welcome-write-blog',
    'cta' => 'megaphone',
    'footer' => 'editor-kitchensink',
    'page_header' => 'heading',
    'content' => 'editor-alignleft',
    'mission' => 'flag',
    'gallery' => 'format-gallery',
    'testimonials' => 'format-quote',
    'faq' => 'editor-help'
);
?>

<div class="spfm-wrap">
    <?php if ($edit_theme): ?>
    <!-- Template Editor -->
    <div class="spfm-template-editor">
        <div class="editor-header">
            <div class="header-left">
                <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="back-link">
                    <span class="dashicons dashicons-arrow-left-alt"></span> Back to Templates
                </a>
                <h1><?php echo esc_html($edit_theme->name); ?></h1>
                <span class="template-type-badge"><?php echo $edit_theme->is_template ? 'Pre-built Template' : 'Custom Template'; ?></span>
            </div>
            <div class="header-right">
                <a href="<?php echo admin_url('admin.php?page=spfm-themes&action=preview&id=' . $edit_theme->id); ?>" class="btn btn-outline" target="_blank">
                    <span class="dashicons dashicons-visibility"></span> Preview
                </a>
                <button type="submit" form="theme-edit-form" class="btn btn-primary">
                    <span class="dashicons dashicons-saved"></span> Save Template
                </button>
            </div>
        </div>
        
        <!-- Editor Tabs -->
        <div class="editor-tabs">
            <button class="tab-btn active" data-tab="settings">
                <span class="dashicons dashicons-admin-settings"></span> Settings
            </button>
            <button class="tab-btn" data-tab="content">
                <span class="dashicons dashicons-edit-page"></span> Content
            </button>
            <button class="tab-btn" data-tab="colors">
                <span class="dashicons dashicons-art"></span> Colors & Fonts
            </button>
        </div>
        
        <form id="theme-edit-form" class="editor-form">
            <input type="hidden" name="id" value="<?php echo $edit_theme->id; ?>">
            <input type="hidden" name="action" value="spfm_save_theme">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('spfm_ajax'); ?>">
            
            <!-- Settings Tab -->
            <div class="tab-content active" id="tab-settings">
                <div class="editor-grid">
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
                                    <span class="value"><?php echo esc_html($categories[$edit_theme->category] ?? ucfirst($edit_theme->category)); ?></span>
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
            
            <!-- Content Tab -->
            <div class="tab-content" id="tab-content">
                <div class="content-editor-layout">
                    <!-- Page Navigation Sidebar -->
                    <div class="page-nav-sidebar">
                        <h4>Pages</h4>
                        <ul class="page-nav-list">
                            <?php if (!empty($edit_theme->pages)): ?>
                                <?php foreach ($edit_theme->pages as $pageIndex => $page): ?>
                                    <li>
                                        <a href="#" class="page-nav-item <?php echo $pageIndex === 0 ? 'active' : ''; ?>" data-page="<?php echo $pageIndex; ?>">
                                            <span class="dashicons dashicons-<?php 
                                                $pageIcons = array('Home' => 'admin-home', 'About' => 'info', 'Services' => 'heart', 'Doctors' => 'groups', 'Contact' => 'phone', 'Blog' => 'welcome-write-blog');
                                                echo $pageIcons[$page->page_name] ?? 'admin-page';
                                            ?>"></span>
                                            <?php echo esc_html($page->page_name); ?>
                                            <span class="section-count"><?php echo count($page->sections ?? array()); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                        
                        <!-- Global Sections -->
                        <h4 style="margin-top: 25px;">Global</h4>
                        <ul class="page-nav-list">
                            <li>
                                <a href="#" class="page-nav-item" data-page="global-header">
                                    <span class="dashicons dashicons-admin-home"></span>
                                    Header
                                </a>
                            </li>
                            <li>
                                <a href="#" class="page-nav-item" data-page="global-footer">
                                    <span class="dashicons dashicons-editor-kitchensink"></span>
                                    Footer
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Content Editor Area -->
                    <div class="content-editor-area">
                        <!-- Global Header Section -->
                        <div class="page-content-editor" data-page="global-header" style="display: none;">
                            <div class="page-editor-header">
                                <h2><span class="dashicons dashicons-admin-home"></span> Header Settings</h2>
                                <p>Configure the header that appears on all pages</p>
                            </div>
                            
                            <div class="editor-section">
                                <h3><span class="dashicons dashicons-format-image"></span> Logo & Branding</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Logo Text / Hospital Name</label>
                                        <input type="text" name="content[header][logo_text]" value="<?php echo esc_attr($edit_theme->name); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Tagline</label>
                                        <input type="text" name="content[header][tagline]" value="Quality Healthcare Services" placeholder="Short tagline">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="editor-section">
                                <h3><span class="dashicons dashicons-phone"></span> Contact Information</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="content[header][phone]" value="+1 (555) 123-4567">
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" name="content[header][email]" value="info@hospital.com">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Emergency Number</label>
                                        <input type="text" name="content[header][emergency]" value="+1 (555) 999-0000">
                                    </div>
                                    <div class="form-group">
                                        <label>CTA Button Text</label>
                                        <input type="text" name="content[header][cta_text]" value="Book Appointment">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Global Footer Section -->
                        <div class="page-content-editor" data-page="global-footer" style="display: none;">
                            <div class="page-editor-header">
                                <h2><span class="dashicons dashicons-editor-kitchensink"></span> Footer Settings</h2>
                                <p>Configure the footer that appears on all pages</p>
                            </div>
                            
                            <div class="editor-section">
                                <h3><span class="dashicons dashicons-info"></span> About Section</h3>
                                <div class="form-group">
                                    <label>Footer About Text</label>
                                    <textarea name="content[footer][about_text]" rows="3">Providing quality healthcare services with compassion and excellence for over 50 years.</textarea>
                                </div>
                            </div>
                            
                            <div class="editor-section">
                                <h3><span class="dashicons dashicons-location"></span> Contact Details</h3>
                                <div class="form-group">
                                    <label>Full Address</label>
                                    <textarea name="content[footer][address]" rows="3">123 Medical Center Drive
Healthcare District
Your City, State 12345</textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="content[footer][phone]" value="+1 (555) 123-4567">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="content[footer][email]" value="info@hospital.com">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="editor-section">
                                <h3><span class="dashicons dashicons-clock"></span> Working Hours</h3>
                                <div class="form-group">
                                    <label>Working Hours Text</label>
                                    <textarea name="content[footer][working_hours]" rows="4">Monday - Friday: 8:00 AM - 8:00 PM
Saturday: 9:00 AM - 5:00 PM
Sunday: Emergency Only
Emergency: 24/7</textarea>
                                </div>
                            </div>
                            
                            <div class="editor-section">
                                <h3><span class="dashicons dashicons-share"></span> Social Links</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Facebook URL</label>
                                        <input type="url" name="content[footer][facebook]" placeholder="https://facebook.com/...">
                                    </div>
                                    <div class="form-group">
                                        <label>Twitter URL</label>
                                        <input type="url" name="content[footer][twitter]" placeholder="https://twitter.com/...">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Instagram URL</label>
                                        <input type="url" name="content[footer][instagram]" placeholder="https://instagram.com/...">
                                    </div>
                                    <div class="form-group">
                                        <label>LinkedIn URL</label>
                                        <input type="url" name="content[footer][linkedin]" placeholder="https://linkedin.com/...">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="editor-section">
                                <h3><span class="dashicons dashicons-admin-settings"></span> Copyright</h3>
                                <div class="form-group">
                                    <label>Copyright Text</label>
                                    <input type="text" name="content[footer][copyright]" value="© 2024 <?php echo esc_attr($edit_theme->name); ?>. All rights reserved.">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Page Content Editors -->
                        <?php if (!empty($edit_theme->pages)): ?>
                            <?php foreach ($edit_theme->pages as $pageIndex => $page): ?>
                                <div class="page-content-editor" data-page="<?php echo $pageIndex; ?>" style="<?php echo $pageIndex === 0 ? '' : 'display: none;'; ?>">
                                    <div class="page-editor-header">
                                        <h2>
                                            <span class="dashicons dashicons-<?php 
                                                $pageIcons = array('Home' => 'admin-home', 'About' => 'info', 'Services' => 'heart', 'Doctors' => 'groups', 'Contact' => 'phone', 'Blog' => 'welcome-write-blog');
                                                echo $pageIcons[$page->page_name] ?? 'admin-page';
                                            ?>"></span>
                                            <?php echo esc_html($page->page_name); ?> Page
                                        </h2>
                                        <p>Edit the content for <?php echo esc_html($page->page_name); ?> page</p>
                                    </div>
                                    
                                    <?php if (!empty($page->sections)): ?>
                                        <?php foreach ($page->sections as $sectionIndex => $section): 
                                            $sectionType = $section->section_type;
                                            $defaultValues = $section->default_values ?? array();
                                            $sectionIcon = $section_icons[$sectionType] ?? 'admin-generic';
                                        ?>
                                            <div class="editor-section section-card" data-section-type="<?php echo esc_attr($sectionType); ?>">
                                                <div class="section-card-header" onclick="toggleSection(this)">
                                                    <h3>
                                                        <span class="dashicons dashicons-<?php echo esc_attr($sectionIcon); ?>"></span>
                                                        <?php echo esc_html(ucwords(str_replace('_', ' ', $sectionType))); ?>
                                                    </h3>
                                                    <span class="toggle-icon dashicons dashicons-arrow-down-alt2"></span>
                                                </div>
                                                <div class="section-card-body">
                                                    <?php 
                                                    // Render fields based on section type
                                                    switch ($sectionType):
                                                        case 'hero':
                                                    ?>
                                                            <div class="form-group">
                                                                <label>Main Headline</label>
                                                                <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][headline]" 
                                                                       value="<?php echo esc_attr($defaultValues['headline'] ?? 'Your Health, Our Priority'); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Subheadline / Description</label>
                                                                <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][subheadline]" rows="3"><?php echo esc_textarea($defaultValues['subheadline'] ?? 'Providing compassionate, world-class healthcare services with state-of-the-art facilities.'); ?></textarea>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Primary Button Text</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][cta_text]" 
                                                                           value="<?php echo esc_attr($defaultValues['cta_text'] ?? 'Book Appointment'); ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Secondary Button Text</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][cta2_text]" 
                                                                           value="<?php echo esc_attr($defaultValues['cta2_text'] ?? 'Our Services'); ?>">
                                                                </div>
                                                            </div>
                                                    <?php
                                                        break;
                                                        
                                                        case 'info_cards':
                                                    ?>
                                                            <!-- Card 1 -->
                                                            <div class="card-group">
                                                                <h4>Card 1</h4>
                                                                <div class="form-row">
                                                                    <div class="form-group">
                                                                        <label>Title</label>
                                                                        <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][card1_title]" 
                                                                               value="<?php echo esc_attr($defaultValues['card1_title'] ?? '24/7 Emergency'); ?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Icon (emoji)</label>
                                                                        <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][card1_icon]" 
                                                                               value="<?php echo esc_attr($defaultValues['card1_icon'] ?? '🕐'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Description</label>
                                                                    <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][card1_text]" rows="2"><?php echo esc_textarea($defaultValues['card1_text'] ?? 'Round-the-clock emergency care with rapid response team.'); ?></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Card 2 -->
                                                            <div class="card-group">
                                                                <h4>Card 2</h4>
                                                                <div class="form-row">
                                                                    <div class="form-group">
                                                                        <label>Title</label>
                                                                        <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][card2_title]" 
                                                                               value="<?php echo esc_attr($defaultValues['card2_title'] ?? 'Easy Appointments'); ?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Icon (emoji)</label>
                                                                        <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][card2_icon]" 
                                                                               value="<?php echo esc_attr($defaultValues['card2_icon'] ?? '📅'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Description</label>
                                                                    <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][card2_text]" rows="2"><?php echo esc_textarea($defaultValues['card2_text'] ?? 'Book appointments online or call us anytime.'); ?></textarea>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Card 3 -->
                                                            <div class="card-group">
                                                                <h4>Card 3</h4>
                                                                <div class="form-row">
                                                                    <div class="form-group">
                                                                        <label>Title</label>
                                                                        <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][card3_title]" 
                                                                               value="<?php echo esc_attr($defaultValues['card3_title'] ?? 'Quality Care'); ?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Icon (emoji)</label>
                                                                        <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][card3_icon]" 
                                                                               value="<?php echo esc_attr($defaultValues['card3_icon'] ?? '🛡️'); ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Description</label>
                                                                    <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][card3_text]" rows="2"><?php echo esc_textarea($defaultValues['card3_text'] ?? 'Accredited facility with board-certified doctors.'); ?></textarea>
                                                                </div>
                                                            </div>
                                                    <?php
                                                        break;
                                                        
                                                        case 'services':
                                                    ?>
                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Section Title</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][section_title]" 
                                                                           value="<?php echo esc_attr($defaultValues['section_title'] ?? 'Our Medical Services'); ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Section Subtitle</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][section_subtitle]" 
                                                                           value="<?php echo esc_attr($defaultValues['section_subtitle'] ?? 'Comprehensive healthcare solutions for you and your family'); ?>">
                                                                </div>
                                                            </div>
                                                            
                                                            <h4 style="margin: 20px 0 15px;">Services List</h4>
                                                            <div class="services-repeater" id="services-repeater-<?php echo $pageIndex; ?>-<?php echo $sectionIndex; ?>">
                                                                <?php 
                                                                $services = array(
                                                                    array('icon' => '🚑', 'name' => 'Emergency Care', 'desc' => 'Round-the-clock emergency medical services.'),
                                                                    array('icon' => '🏥', 'name' => 'Surgery', 'desc' => 'Advanced surgical procedures with experienced surgeons.'),
                                                                    array('icon' => '💊', 'name' => 'Internal Medicine', 'desc' => 'Comprehensive diagnosis and treatment.'),
                                                                    array('icon' => '👶', 'name' => 'Pediatrics', 'desc' => 'Specialized healthcare for children.'),
                                                                    array('icon' => '❤️', 'name' => 'Cardiology', 'desc' => 'Expert care for heart conditions.'),
                                                                    array('icon' => '🦴', 'name' => 'Orthopedics', 'desc' => 'Treatment for bones, joints and spine.'),
                                                                );
                                                                foreach ($services as $sIdx => $service):
                                                                ?>
                                                                    <div class="repeater-item">
                                                                        <div class="repeater-item-header">
                                                                            <span>Service <?php echo $sIdx + 1; ?></span>
                                                                            <button type="button" class="btn-remove-item" onclick="removeRepeaterItem(this)">×</button>
                                                                        </div>
                                                                        <div class="repeater-item-body">
                                                                            <div class="form-row three-col">
                                                                                <div class="form-group">
                                                                                    <label>Icon</label>
                                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][services][<?php echo $sIdx; ?>][icon]" 
                                                                                           value="<?php echo esc_attr($service['icon']); ?>">
                                                                                </div>
                                                                                <div class="form-group" style="flex: 2;">
                                                                                    <label>Service Name</label>
                                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][services][<?php echo $sIdx; ?>][name]" 
                                                                                           value="<?php echo esc_attr($service['name']); ?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Description</label>
                                                                                <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][services][<?php echo $sIdx; ?>][desc]" 
                                                                                       value="<?php echo esc_attr($service['desc']); ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                            <button type="button" class="btn btn-small btn-add-item" onclick="addServiceItem(<?php echo $pageIndex; ?>, <?php echo $sectionIndex; ?>)">
                                                                <span class="dashicons dashicons-plus"></span> Add Service
                                                            </button>
                                                    <?php
                                                        break;
                                                        
                                                        case 'stats':
                                                    ?>
                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Stat 1 Number</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][stat1_number]" 
                                                                           value="<?php echo esc_attr($defaultValues['stat1_number'] ?? '50+'); ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Stat 1 Label</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][stat1_label]" 
                                                                           value="<?php echo esc_attr($defaultValues['stat1_label'] ?? 'Years Experience'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Stat 2 Number</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][stat2_number]" 
                                                                           value="<?php echo esc_attr($defaultValues['stat2_number'] ?? '200+'); ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Stat 2 Label</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][stat2_label]" 
                                                                           value="<?php echo esc_attr($defaultValues['stat2_label'] ?? 'Expert Doctors'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Stat 3 Number</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][stat3_number]" 
                                                                           value="<?php echo esc_attr($defaultValues['stat3_number'] ?? '100K+'); ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Stat 3 Label</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][stat3_label]" 
                                                                           value="<?php echo esc_attr($defaultValues['stat3_label'] ?? 'Patients Served'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Stat 4 Number</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][stat4_number]" 
                                                                           value="<?php echo esc_attr($defaultValues['stat4_number'] ?? '50+'); ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Stat 4 Label</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][stat4_label]" 
                                                                           value="<?php echo esc_attr($defaultValues['stat4_label'] ?? 'Specialties'); ?>">
                                                                </div>
                                                            </div>
                                                    <?php
                                                        break;
                                                        
                                                        case 'team':
                                                    ?>
                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Section Title</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][section_title]" 
                                                                           value="<?php echo esc_attr($defaultValues['section_title'] ?? 'Our Medical Team'); ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Section Subtitle</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][section_subtitle]" 
                                                                           value="<?php echo esc_attr($defaultValues['section_subtitle'] ?? 'Meet our experienced healthcare professionals'); ?>">
                                                                </div>
                                                            </div>
                                                            
                                                            <h4 style="margin: 20px 0 15px;">Team Members</h4>
                                                            <div class="team-repeater" id="team-repeater-<?php echo $pageIndex; ?>-<?php echo $sectionIndex; ?>">
                                                                <?php 
                                                                $doctors = array(
                                                                    array('name' => 'Dr. Sarah Johnson', 'specialty' => 'Chief Medical Officer', 'exp' => '25+ years experience'),
                                                                    array('name' => 'Dr. Michael Chen', 'specialty' => 'Cardiology', 'exp' => '20+ years experience'),
                                                                    array('name' => 'Dr. Emily Brown', 'specialty' => 'Pediatrics', 'exp' => '15+ years experience'),
                                                                );
                                                                foreach ($doctors as $dIdx => $doctor):
                                                                ?>
                                                                    <div class="repeater-item">
                                                                        <div class="repeater-item-header">
                                                                            <span>Doctor <?php echo $dIdx + 1; ?></span>
                                                                            <button type="button" class="btn-remove-item" onclick="removeRepeaterItem(this)">×</button>
                                                                        </div>
                                                                        <div class="repeater-item-body">
                                                                            <div class="form-row">
                                                                                <div class="form-group">
                                                                                    <label>Name</label>
                                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][doctors][<?php echo $dIdx; ?>][name]" 
                                                                                           value="<?php echo esc_attr($doctor['name']); ?>">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Specialty</label>
                                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][doctors][<?php echo $dIdx; ?>][specialty]" 
                                                                                           value="<?php echo esc_attr($doctor['specialty']); ?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Experience / Bio</label>
                                                                                <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][doctors][<?php echo $dIdx; ?>][exp]" 
                                                                                       value="<?php echo esc_attr($doctor['exp']); ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                            <button type="button" class="btn btn-small btn-add-item" onclick="addDoctorItem(<?php echo $pageIndex; ?>, <?php echo $sectionIndex; ?>)">
                                                                <span class="dashicons dashicons-plus"></span> Add Doctor
                                                            </button>
                                                    <?php
                                                        break;
                                                        
                                                        case 'cta':
                                                    ?>
                                                            <div class="form-group">
                                                                <label>CTA Headline</label>
                                                                <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][cta_headline]" 
                                                                       value="<?php echo esc_attr($defaultValues['cta_headline'] ?? 'Need Emergency Care?'); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>CTA Description</label>
                                                                <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][cta_description]" rows="2"><?php echo esc_textarea($defaultValues['cta_description'] ?? 'Our emergency department is open 24/7. Don\'t wait – get the care you need now.'); ?></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Button Text</label>
                                                                <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][cta_button]" 
                                                                       value="<?php echo esc_attr($defaultValues['cta_button'] ?? 'Call Emergency'); ?>">
                                                            </div>
                                                    <?php
                                                        break;
                                                        
                                                        case 'blog':
                                                    ?>
                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Section Title</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][section_title]" 
                                                                           value="<?php echo esc_attr($defaultValues['section_title'] ?? 'Health Blog'); ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Section Subtitle</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][section_subtitle]" 
                                                                           value="<?php echo esc_attr($defaultValues['section_subtitle'] ?? 'Expert health advice from our professionals'); ?>">
                                                                </div>
                                                            </div>
                                                            
                                                            <h4 style="margin: 20px 0 15px;">Blog Posts</h4>
                                                            <div class="blog-repeater" id="blog-repeater-<?php echo $pageIndex; ?>-<?php echo $sectionIndex; ?>">
                                                                <?php 
                                                                $posts = array(
                                                                    array('icon' => '🏃', 'title' => '10 Tips for a Healthier Lifestyle', 'excerpt' => 'Simple changes for better health.'),
                                                                    array('icon' => '🥗', 'title' => 'Nutrition Guide for Heart Health', 'excerpt' => 'Foods that keep your heart healthy.'),
                                                                    array('icon' => '😴', 'title' => 'The Importance of Quality Sleep', 'excerpt' => 'Why good sleep is essential.'),
                                                                );
                                                                foreach ($posts as $pIdx => $post):
                                                                ?>
                                                                    <div class="repeater-item">
                                                                        <div class="repeater-item-header">
                                                                            <span>Post <?php echo $pIdx + 1; ?></span>
                                                                            <button type="button" class="btn-remove-item" onclick="removeRepeaterItem(this)">×</button>
                                                                        </div>
                                                                        <div class="repeater-item-body">
                                                                            <div class="form-row three-col">
                                                                                <div class="form-group">
                                                                                    <label>Icon</label>
                                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][posts][<?php echo $pIdx; ?>][icon]" 
                                                                                           value="<?php echo esc_attr($post['icon']); ?>">
                                                                                </div>
                                                                                <div class="form-group" style="flex: 2;">
                                                                                    <label>Title</label>
                                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][posts][<?php echo $pIdx; ?>][title]" 
                                                                                           value="<?php echo esc_attr($post['title']); ?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Excerpt</label>
                                                                                <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][posts][<?php echo $pIdx; ?>][excerpt]" 
                                                                                       value="<?php echo esc_attr($post['excerpt']); ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                            <button type="button" class="btn btn-small btn-add-item" onclick="addBlogItem(<?php echo $pageIndex; ?>, <?php echo $sectionIndex; ?>)">
                                                                <span class="dashicons dashicons-plus"></span> Add Post
                                                            </button>
                                                    <?php
                                                        break;
                                                        
                                                        case 'page_header':
                                                    ?>
                                                            <div class="form-group">
                                                                <label>Page Title</label>
                                                                <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][page_title]" 
                                                                       value="<?php echo esc_attr($defaultValues['page_title'] ?? $page->page_name); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Breadcrumb Text</label>
                                                                <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][breadcrumb]" 
                                                                       value="<?php echo esc_attr($defaultValues['breadcrumb'] ?? 'Home / ' . $page->page_name); ?>">
                                                            </div>
                                                    <?php
                                                        break;
                                                        
                                                        case 'content':
                                                        case 'mission':
                                                    ?>
                                                            <div class="form-group">
                                                                <label>Section Title</label>
                                                                <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][section_title]" 
                                                                       value="<?php echo esc_attr($defaultValues['section_title'] ?? 'About Us'); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Content</label>
                                                                <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][content]" rows="5"><?php echo esc_textarea($defaultValues['content'] ?? 'Add your content here...'); ?></textarea>
                                                            </div>
                                                            <?php if ($sectionType === 'mission'): ?>
                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Mission Statement</label>
                                                                    <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][mission]" rows="3"><?php echo esc_textarea($defaultValues['mission'] ?? 'Our mission is to provide exceptional healthcare...'); ?></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Vision Statement</label>
                                                                    <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][vision]" rows="3"><?php echo esc_textarea($defaultValues['vision'] ?? 'Our vision is to be the leading healthcare provider...'); ?></textarea>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>
                                                    <?php
                                                        break;
                                                        
                                                        case 'contact_info':
                                                    ?>
                                                            <div class="form-group">
                                                                <label>Full Address</label>
                                                                <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][address]" rows="3"><?php echo esc_textarea($defaultValues['address'] ?? "123 Medical Center Drive\nYour City, State 12345"); ?></textarea>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group">
                                                                    <label>Phone</label>
                                                                    <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][phone]" 
                                                                           value="<?php echo esc_attr($defaultValues['phone'] ?? '+1 (555) 123-4567'); ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input type="email" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][email]" 
                                                                           value="<?php echo esc_attr($defaultValues['email'] ?? 'info@hospital.com'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Working Hours</label>
                                                                <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][working_hours]" rows="3"><?php echo esc_textarea($defaultValues['working_hours'] ?? "Monday - Friday: 8:00 AM - 8:00 PM\nSaturday: 9:00 AM - 5:00 PM\nSunday: Emergency Only"); ?></textarea>
                                                            </div>
                                                    <?php
                                                        break;
                                                        
                                                        default:
                                                    ?>
                                                            <div class="form-group">
                                                                <label>Section Title</label>
                                                                <input type="text" name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][section_title]" 
                                                                       value="<?php echo esc_attr($defaultValues['section_title'] ?? ucwords(str_replace('_', ' ', $sectionType))); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Content</label>
                                                                <textarea name="content[page_<?php echo $pageIndex; ?>][<?php echo $sectionIndex; ?>][content]" rows="4"><?php echo esc_textarea($defaultValues['content'] ?? ''); ?></textarea>
                                                            </div>
                                                    <?php
                                                        break;
                                                    endswitch;
                                                    ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="empty-sections">
                                            <span class="dashicons dashicons-info"></span>
                                            <p>No sections defined for this page yet.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Colors & Fonts Tab -->
            <div class="tab-content" id="tab-colors">
                <div class="editor-grid">
                    <div class="editor-main">
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
                                    <span class="color-hint">Main brand color</span>
                                </div>
                                <div class="color-item">
                                    <label>Secondary Color</label>
                                    <div class="color-input-group">
                                        <input type="color" name="secondary_color" value="<?php echo esc_attr($edit_theme->secondary_color); ?>">
                                        <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->secondary_color); ?>">
                                    </div>
                                    <span class="color-hint">Complementary color</span>
                                </div>
                                <div class="color-item">
                                    <label>Accent Color</label>
                                    <div class="color-input-group">
                                        <input type="color" name="accent_color" value="<?php echo esc_attr($edit_theme->accent_color); ?>">
                                        <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->accent_color); ?>">
                                    </div>
                                    <span class="color-hint">Highlights & buttons</span>
                                </div>
                                <div class="color-item">
                                    <label>Background Color</label>
                                    <div class="color-input-group">
                                        <input type="color" name="background_color" value="<?php echo esc_attr($edit_theme->background_color); ?>">
                                        <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->background_color); ?>">
                                    </div>
                                    <span class="color-hint">Page background</span>
                                </div>
                                <div class="color-item">
                                    <label>Text Color</label>
                                    <div class="color-input-group">
                                        <input type="color" name="text_color" value="<?php echo esc_attr($edit_theme->text_color); ?>">
                                        <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->text_color); ?>">
                                    </div>
                                    <span class="color-hint">Body text</span>
                                </div>
                                <div class="color-item">
                                    <label>Header Background</label>
                                    <div class="color-input-group">
                                        <input type="color" name="header_bg_color" value="<?php echo esc_attr($edit_theme->header_bg_color); ?>">
                                        <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->header_bg_color); ?>">
                                    </div>
                                    <span class="color-hint">Header area</span>
                                </div>
                                <div class="color-item">
                                    <label>Footer Background</label>
                                    <div class="color-input-group">
                                        <input type="color" name="footer_bg_color" value="<?php echo esc_attr($edit_theme->footer_bg_color ?: '#0f172a'); ?>">
                                        <input type="text" class="color-hex" value="<?php echo esc_attr($edit_theme->footer_bg_color ?: '#0f172a'); ?>">
                                    </div>
                                    <span class="color-hint">Footer area</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Typography -->
                        <div class="editor-section">
                            <h3><span class="dashicons dashicons-editor-textcolor"></span> Typography</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Body Font</label>
                                    <select name="font_family" id="body-font-select">
                                        <?php foreach ($fonts as $key => $label): ?>
                                            <option value="<?php echo esc_attr($key); ?>" <?php selected($edit_theme->font_family, $key); ?>>
                                                <?php echo esc_html($label); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="font-preview" id="body-font-preview" style="font-family: '<?php echo esc_attr($edit_theme->font_family); ?>';">
                                        The quick brown fox jumps over the lazy dog.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Heading Font</label>
                                    <select name="heading_font" id="heading-font-select">
                                        <?php foreach ($fonts as $key => $label): ?>
                                            <option value="<?php echo esc_attr($key); ?>" <?php selected($edit_theme->heading_font, $key); ?>>
                                                <?php echo esc_html($label); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="font-preview heading" id="heading-font-preview" style="font-family: '<?php echo esc_attr($edit_theme->heading_font); ?>';">
                                        Hospital Name
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar Preview -->
                    <div class="editor-sidebar">
                        <div class="sidebar-card color-preview-card">
                            <h4>Color Preview</h4>
                            <div class="color-preview-box" id="color-preview">
                                <div class="preview-header" style="background: <?php echo esc_attr($edit_theme->header_bg_color); ?>;">
                                    <span style="color: <?php echo esc_attr($edit_theme->primary_color); ?>;">Logo</span>
                                    <span style="background: <?php echo esc_attr($edit_theme->primary_color); ?>; color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 10px;">CTA</span>
                                </div>
                                <div class="preview-hero" style="background: linear-gradient(135deg, <?php echo esc_attr($edit_theme->primary_color); ?>, <?php echo esc_attr($edit_theme->secondary_color); ?>);">
                                    <span style="color: #fff;">Hero Section</span>
                                </div>
                                <div class="preview-content" style="background: <?php echo esc_attr($edit_theme->background_color); ?>; color: <?php echo esc_attr($edit_theme->text_color); ?>;">
                                    <span>Content Area</span>
                                    <div class="preview-accent" style="background: <?php echo esc_attr($edit_theme->accent_color); ?>;">Accent</div>
                                </div>
                                <div class="preview-footer" style="background: <?php echo esc_attr($edit_theme->footer_bg_color ?: '#0f172a'); ?>;">
                                    <span style="color: #fff;">Footer</span>
                                </div>
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
/* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap');

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

/* Editor Header */
.spfm-template-editor .editor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
}
.editor-header .header-left { display: flex; align-items: center; gap: 20px; }
.editor-header h1 { margin: 0; font-size: 24px; }
.editor-header .back-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #64748b;
    text-decoration: none;
    font-size: 14px;
}
.editor-header .back-link:hover { color: #0891b2; }
.template-type-badge {
    background: #f1f5f9;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 12px;
    color: #64748b;
}
.editor-header .header-right { display: flex; gap: 10px; }

/* Editor Tabs */
.editor-tabs {
    display: flex;
    gap: 5px;
    margin-bottom: 25px;
    background: #f1f5f9;
    padding: 5px;
    border-radius: 12px;
    width: fit-content;
}
.tab-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: transparent;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    color: #64748b;
    transition: all 0.3s;
}
.tab-btn:hover { color: #0891b2; }
.tab-btn.active {
    background: #fff;
    color: #0891b2;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.tab-content { display: none; }
.tab-content.active { display: block; }

/* Editor Grid */
.editor-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 30px;
}

/* Content Editor Layout */
.content-editor-layout {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 30px;
    min-height: 600px;
}

/* Page Navigation Sidebar */
.page-nav-sidebar {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    position: sticky;
    top: 50px;
    max-height: calc(100vh - 100px);
    overflow-y: auto;
}
.page-nav-sidebar h4 {
    margin: 0 0 15px 0;
    font-size: 12px;
    text-transform: uppercase;
    color: #94a3b8;
    letter-spacing: 0.5px;
}
.page-nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
.page-nav-list li { margin-bottom: 5px; }
.page-nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    color: #475569;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
}
.page-nav-item:hover {
    background: #f8fafc;
    color: #0891b2;
}
.page-nav-item.active {
    background: #ecfeff;
    color: #0891b2;
    font-weight: 600;
}
.page-nav-item .section-count {
    margin-left: auto;
    background: #e2e8f0;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    color: #64748b;
}
.page-nav-item.active .section-count {
    background: #0891b2;
    color: #fff;
}

/* Content Editor Area */
.content-editor-area {
    min-height: 500px;
}
.page-content-editor { animation: fadeIn 0.3s; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.page-editor-header {
    margin-bottom: 25px;
}
.page-editor-header h2 {
    margin: 0 0 5px 0;
    font-size: 22px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.page-editor-header p {
    margin: 0;
    color: #64748b;
}

/* Section Cards */
.section-card {
    background: #fff;
    border-radius: 12px;
    margin-bottom: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow: hidden;
}
.section-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px;
    background: #f8fafc;
    cursor: pointer;
    transition: background 0.2s;
}
.section-card-header:hover { background: #f1f5f9; }
.section-card-header h3 {
    margin: 0;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.section-card-header h3 .dashicons { color: #0891b2; }
.toggle-icon { color: #94a3b8; transition: transform 0.3s; }
.section-card.collapsed .toggle-icon { transform: rotate(-90deg); }
.section-card.collapsed .section-card-body { display: none; }
.section-card-body { padding: 20px; }

/* Card Group */
.card-group {
    background: #f8fafc;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}
.card-group h4 {
    margin: 0 0 12px 0;
    font-size: 13px;
    color: #64748b;
}

/* Repeater Items */
.repeater-item {
    background: #f8fafc;
    border-radius: 8px;
    margin-bottom: 10px;
    overflow: hidden;
}
.repeater-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    background: #e2e8f0;
    font-size: 13px;
    font-weight: 600;
}
.btn-remove-item {
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    font-size: 18px;
    padding: 0;
    line-height: 1;
}
.btn-remove-item:hover { color: #dc2626; }
.repeater-item-body { padding: 15px; }

.btn-add-item {
    margin-top: 10px;
    background: #ecfeff !important;
    color: #0891b2 !important;
    border: 1px dashed #0891b2 !important;
}
.btn-add-item:hover {
    background: #cffafe !important;
}

/* Empty Sections */
.empty-sections {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}
.empty-sections .dashicons {
    font-size: 48px;
    width: 48px;
    height: 48px;
    margin-bottom: 15px;
}

/* Editor Section */
.editor-section {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
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

/* Form Elements */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-row.three-col {
    grid-template-columns: 80px 1fr 1fr;
}
.form-group { margin-bottom: 18px; }
.form-group:last-child { margin-bottom: 0; }
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 13px;
    color: #334155;
}
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 14px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
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
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
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
    width: 45px;
    height: 38px;
    padding: 0;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
}
.color-input-group .color-hex {
    flex: 1;
    padding: 8px 12px;
}
.color-hint {
    display: block;
    font-size: 11px;
    color: #94a3b8;
    margin-top: 5px;
}

/* Font Preview */
.font-preview {
    margin-top: 10px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 8px;
    font-size: 16px;
    color: #334155;
}
.font-preview.heading {
    font-size: 24px;
    font-weight: 700;
}

/* Color Preview Card */
.color-preview-card { position: sticky; top: 50px; }
.color-preview-box {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}
.color-preview-box .preview-header {
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    font-weight: 600;
}
.color-preview-box .preview-hero {
    padding: 25px 15px;
    text-align: center;
    font-size: 14px;
    font-weight: 600;
}
.color-preview-box .preview-content {
    padding: 20px 15px;
    font-size: 12px;
}
.color-preview-box .preview-accent {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 15px;
    color: #fff;
    font-size: 10px;
    margin-top: 10px;
}
.color-preview-box .preview-footer {
    padding: 15px;
    text-align: center;
    font-size: 11px;
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
    font-size: 13px;
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
    font-size: 13px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.status-toggle {
    display: flex;
    align-items: center;
    gap: 12px;
}
.info-list { }
.info-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
}
.info-item:last-child { border-bottom: none; }
.info-item .label { color: #64748b; }
.info-item .value { font-weight: 600; color: #334155; }

/* Switch Toggle */
.switch {
    position: relative;
    display: inline-block;
    width: 46px;
    height: 24px;
}
.switch input { opacity: 0; width: 0; height: 0; }
.slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 24px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
}
input:checked + .slider { background-color: #10b981; }
input:checked + .slider:before { transform: translateX(22px); }

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    text-decoration: none;
    font-size: 14px;
}
.btn-primary { background: #0891b2; color: #fff; }
.btn-primary:hover { background: #0e7490; }
.btn-secondary { background: #f1f5f9; color: #475569; }
.btn-secondary:hover { background: #e2e8f0; }
.btn-outline { background: transparent; color: #0891b2; border: 2px solid #0891b2; }
.btn-outline:hover { background: #ecfeff; }
.btn-small { padding: 8px 14px; font-size: 13px; }
.btn-block { width: 100%; justify-content: center; }

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
.prev-logo-icon { font-size: 10px; }
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

/* Overlay */
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
.btn-edit { background: #fff; color: #333; }
.btn-edit:hover { background: #f8fafc; transform: scale(1.05); }
.btn-preview { background: #0891b2; color: #fff; }
.btn-preview:hover { background: #0e7490; transform: scale(1.05); }
.btn-duplicate { background: transparent; color: #fff; border: 2px solid #fff !important; }
.btn-duplicate:hover { background: rgba(255,255,255,0.1); transform: scale(1.05); }

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

@media (max-width: 1200px) {
    .editor-grid { grid-template-columns: 1fr; }
    .content-editor-layout { grid-template-columns: 1fr; }
    .page-nav-sidebar { position: relative; top: 0; max-height: none; margin-bottom: 20px; }
}
@media (max-width: 768px) {
    .templates-grid { grid-template-columns: 1fr; }
    .spfm-header { flex-direction: column; gap: 20px; }
    .editor-header { flex-direction: column; gap: 15px; align-items: flex-start; }
    .form-row { grid-template-columns: 1fr; }
    .color-grid { grid-template-columns: 1fr 1fr; }
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
    $('.page-nav-item').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        
        $('.page-nav-item').removeClass('active');
        $(this).addClass('active');
        
        $('.page-content-editor').hide();
        $('.page-content-editor[data-page="' + page + '"]').show();
    });
    
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
        updateColorPreview();
    });
    $('.color-hex').on('input', function() {
        const val = $(this).val();
        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            $(this).siblings('input[type="color"]').val(val);
            updateColorPreview();
        }
    });
    
    function updateColorPreview() {
        const primary = $('input[name="primary_color"]').val();
        const secondary = $('input[name="secondary_color"]').val();
        const accent = $('input[name="accent_color"]').val();
        const bg = $('input[name="background_color"]').val();
        const text = $('input[name="text_color"]').val();
        const headerBg = $('input[name="header_bg_color"]').val();
        const footerBg = $('input[name="footer_bg_color"]').val();
        
        const preview = $('#color-preview');
        preview.find('.preview-header').css('background', headerBg);
        preview.find('.preview-header span:first').css('color', primary);
        preview.find('.preview-header span:last').css('background', primary);
        preview.find('.preview-hero').css('background', 'linear-gradient(135deg, ' + primary + ', ' + secondary + ')');
        preview.find('.preview-content').css({ 'background': bg, 'color': text });
        preview.find('.preview-accent').css('background', accent);
        preview.find('.preview-footer').css('background', footerBg);
    }
    
    // Font Preview
    $('#body-font-select').on('change', function() {
        $('#body-font-preview').css('font-family', $(this).val());
    });
    $('#heading-font-select').on('change', function() {
        $('#heading-font-preview').css('font-family', $(this).val());
    });
    
    // Theme Edit Form Submit
    $('#theme-edit-form').on('submit', function(e) {
        e.preventDefault();
        
        const $btn = $(this).find('button[type="submit"]');
        $btn.text('Saving...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, $(this).serialize(), function(response) {
            if (response.success) {
                alert('Template saved successfully!');
            } else {
                alert(response.data.message || 'Failed to save template.');
            }
            $btn.html('<span class="dashicons dashicons-saved"></span> Save Template').prop('disabled', false);
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

// Toggle Section
function toggleSection(header) {
    const section = header.closest('.section-card');
    section.classList.toggle('collapsed');
}

// Add Feature
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

// Remove Repeater Item
function removeRepeaterItem(btn) {
    btn.closest('.repeater-item').remove();
}

// Add Service Item
function addServiceItem(pageIndex, sectionIndex) {
    const container = document.getElementById(`services-repeater-${pageIndex}-${sectionIndex}`);
    const count = container.querySelectorAll('.repeater-item').length;
    
    const item = document.createElement('div');
    item.className = 'repeater-item';
    item.innerHTML = `
        <div class="repeater-item-header">
            <span>Service ${count + 1}</span>
            <button type="button" class="btn-remove-item" onclick="removeRepeaterItem(this)">×</button>
        </div>
        <div class="repeater-item-body">
            <div class="form-row three-col">
                <div class="form-group">
                    <label>Icon</label>
                    <input type="text" name="content[page_${pageIndex}][${sectionIndex}][services][${count}][icon]" value="⭐">
                </div>
                <div class="form-group" style="flex: 2;">
                    <label>Service Name</label>
                    <input type="text" name="content[page_${pageIndex}][${sectionIndex}][services][${count}][name]" value="">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="content[page_${pageIndex}][${sectionIndex}][services][${count}][desc]" value="">
            </div>
        </div>
    `;
    container.appendChild(item);
}

// Add Doctor Item
function addDoctorItem(pageIndex, sectionIndex) {
    const container = document.getElementById(`team-repeater-${pageIndex}-${sectionIndex}`);
    const count = container.querySelectorAll('.repeater-item').length;
    
    const item = document.createElement('div');
    item.className = 'repeater-item';
    item.innerHTML = `
        <div class="repeater-item-header">
            <span>Doctor ${count + 1}</span>
            <button type="button" class="btn-remove-item" onclick="removeRepeaterItem(this)">×</button>
        </div>
        <div class="repeater-item-body">
            <div class="form-row">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="content[page_${pageIndex}][${sectionIndex}][doctors][${count}][name]" value="">
                </div>
                <div class="form-group">
                    <label>Specialty</label>
                    <input type="text" name="content[page_${pageIndex}][${sectionIndex}][doctors][${count}][specialty]" value="">
                </div>
            </div>
            <div class="form-group">
                <label>Experience / Bio</label>
                <input type="text" name="content[page_${pageIndex}][${sectionIndex}][doctors][${count}][exp]" value="">
            </div>
        </div>
    `;
    container.appendChild(item);
}

// Add Blog Item
function addBlogItem(pageIndex, sectionIndex) {
    const container = document.getElementById(`blog-repeater-${pageIndex}-${sectionIndex}`);
    const count = container.querySelectorAll('.repeater-item').length;
    
    const item = document.createElement('div');
    item.className = 'repeater-item';
    item.innerHTML = `
        <div class="repeater-item-header">
            <span>Post ${count + 1}</span>
            <button type="button" class="btn-remove-item" onclick="removeRepeaterItem(this)">×</button>
        </div>
        <div class="repeater-item-body">
            <div class="form-row three-col">
                <div class="form-group">
                    <label>Icon</label>
                    <input type="text" name="content[page_${pageIndex}][${sectionIndex}][posts][${count}][icon]" value="📝">
                </div>
                <div class="form-group" style="flex: 2;">
                    <label>Title</label>
                    <input type="text" name="content[page_${pageIndex}][${sectionIndex}][posts][${count}][title]" value="">
                </div>
            </div>
            <div class="form-group">
                <label>Excerpt</label>
                <input type="text" name="content[page_${pageIndex}][${sectionIndex}][posts][${count}][excerpt]" value="">
            </div>
        </div>
    `;
    container.appendChild(item);
}

function openCreateModal() {
    document.getElementById('create-modal').classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
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
