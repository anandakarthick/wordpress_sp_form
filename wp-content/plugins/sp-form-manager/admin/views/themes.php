<?php
if (!defined('ABSPATH')) {
    exit;
}

$themes_handler = SPFM_Themes::get_instance();
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$categories = $themes_handler->get_categories();
$fonts = $themes_handler->get_fonts();

// Get all themes
$all_themes = $themes_handler->get_all(array('per_page' => 100, 'is_template' => 1, 'status' => 1));
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-admin-appearance"></span> Website Templates
    </h1>
    
    <?php if ($action === 'list'): ?>
        <p class="description">Pre-built website templates that customers can choose from. Each template includes multiple pages (Home, About, Services, Contact, etc.)</p>
        
        <!-- Category Filter -->
        <div class="template-filters">
            <button class="filter-btn active" data-category="all">All Templates</button>
            <?php foreach ($categories as $cat_key => $cat_name): ?>
                <button class="filter-btn" data-category="<?php echo esc_attr($cat_key); ?>"><?php echo esc_html($cat_name); ?></button>
            <?php endforeach; ?>
        </div>
        
        <!-- Templates Grid -->
        <div class="templates-grid">
            <?php if (empty($all_themes)): ?>
                <div class="empty-state">
                    <span class="dashicons dashicons-admin-appearance"></span>
                    <h3>No Website Templates Found</h3>
                    <p>Please deactivate and reactivate the plugin to create default templates.</p>
                </div>
            <?php else: ?>
                <?php foreach ($all_themes as $theme): 
                    $features = json_decode($theme->features, true) ?: array();
                    $pages = $themes_handler->get_theme_pages($theme->id);
                ?>
                    <div class="template-card" data-category="<?php echo esc_attr($theme->category); ?>">
                        <div class="template-preview" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?> 0%, <?php echo esc_attr($theme->secondary_color); ?> 100%);">
                            <div class="preview-mockup">
                                <div class="mockup-header" style="background: <?php echo esc_attr($theme->header_bg_color); ?>;">
                                    <div class="mockup-nav">
                                        <?php foreach (array_slice($pages, 0, 4) as $page): ?>
                                            <span><?php echo esc_html($page->page_name); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="mockup-hero" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?>cc 0%, <?php echo esc_attr($theme->secondary_color); ?>cc 100%);">
                                    <div class="mockup-title"></div>
                                    <div class="mockup-subtitle"></div>
                                    <div class="mockup-btn" style="background: <?php echo esc_attr($theme->accent_color); ?>;"></div>
                                </div>
                                <div class="mockup-content" style="background: <?php echo esc_attr($theme->background_color); ?>;">
                                    <div class="mockup-cards">
                                        <div class="mockup-card"></div>
                                        <div class="mockup-card"></div>
                                        <div class="mockup-card"></div>
                                    </div>
                                </div>
                                <div class="mockup-footer" style="background: <?php echo esc_attr($theme->footer_bg_color); ?>;"></div>
                            </div>
                            <div class="preview-overlay">
                                <button class="btn-preview" onclick="previewTemplate(<?php echo $theme->id; ?>)">
                                    <span class="dashicons dashicons-visibility"></span> Preview
                                </button>
                            </div>
                        </div>
                        
                        <div class="template-info">
                            <div class="template-header">
                                <h3><?php echo esc_html($theme->name); ?></h3>
                                <span class="category-badge"><?php echo esc_html($categories[$theme->category] ?? $theme->category); ?></span>
                            </div>
                            
                            <p class="template-description"><?php echo esc_html($theme->description); ?></p>
                            
                            <div class="template-meta">
                                <div class="meta-item">
                                    <span class="dashicons dashicons-admin-page"></span>
                                    <span><?php echo count($pages); ?> Pages</span>
                                </div>
                                <div class="meta-item colors">
                                    <span class="color-dot" style="background: <?php echo esc_attr($theme->primary_color); ?>;"></span>
                                    <span class="color-dot" style="background: <?php echo esc_attr($theme->secondary_color); ?>;"></span>
                                    <span class="color-dot" style="background: <?php echo esc_attr($theme->accent_color); ?>;"></span>
                                </div>
                            </div>
                            
                            <div class="template-pages">
                                <strong>Pages:</strong>
                                <?php foreach ($pages as $page): ?>
                                    <span class="page-tag">
                                        <span class="dashicons <?php echo esc_attr($page->page_icon); ?>"></span>
                                        <?php echo esc_html($page->page_name); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (!empty($features)): ?>
                                <div class="template-features">
                                    <?php foreach (array_slice($features, 0, 4) as $feature): ?>
                                        <span class="feature-tag"><span class="dashicons dashicons-yes"></span> <?php echo esc_html($feature); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="template-actions">
                            <button class="button button-primary" onclick="previewTemplate(<?php echo $theme->id; ?>)">
                                <span class="dashicons dashicons-visibility"></span> Preview
                            </button>
                            <button class="button" onclick="viewPages(<?php echo $theme->id; ?>)">
                                <span class="dashicons dashicons-admin-page"></span> View Pages
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
    <?php elseif ($action === 'preview' && $id): ?>
        <?php 
        $theme = $themes_handler->get_theme_complete($id);
        if (!$theme): 
            echo '<p>Template not found.</p>';
            return;
        endif;
        ?>
        
        <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="page-title-action">← Back to Templates</a>
        
        <div class="template-preview-page">
            <!-- Sidebar with Pages -->
            <div class="preview-sidebar">
                <div class="theme-info-card">
                    <h2><?php echo esc_html($theme->name); ?></h2>
                    <p><?php echo esc_html($theme->description); ?></p>
                    
                    <div class="color-palette">
                        <h4>Color Palette</h4>
                        <div class="palette-colors">
                            <div class="palette-color">
                                <span style="background: <?php echo esc_attr($theme->primary_color); ?>;"></span>
                                <small>Primary</small>
                            </div>
                            <div class="palette-color">
                                <span style="background: <?php echo esc_attr($theme->secondary_color); ?>;"></span>
                                <small>Secondary</small>
                            </div>
                            <div class="palette-color">
                                <span style="background: <?php echo esc_attr($theme->accent_color); ?>;"></span>
                                <small>Accent</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="pages-nav">
                    <h4>Template Pages</h4>
                    <?php foreach ($theme->pages as $index => $page): ?>
                        <button class="page-nav-btn <?php echo $index === 0 ? 'active' : ''; ?>" 
                                data-page="<?php echo $page->id; ?>"
                                onclick="showPage(<?php echo $page->id; ?>, this)">
                            <span class="dashicons <?php echo esc_attr($page->page_icon); ?>"></span>
                            <span class="page-name"><?php echo esc_html($page->page_name); ?></span>
                            <span class="page-sections"><?php echo count($page->sections); ?> sections</span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Main Preview Area -->
            <div class="preview-main">
                <div class="preview-toolbar">
                    <div class="device-switcher">
                        <button class="device-btn active" data-device="desktop"><span class="dashicons dashicons-desktop"></span></button>
                        <button class="device-btn" data-device="tablet"><span class="dashicons dashicons-tablet"></span></button>
                        <button class="device-btn" data-device="mobile"><span class="dashicons dashicons-smartphone"></span></button>
                    </div>
                    <span class="current-page-name">Home</span>
                </div>
                
                <div class="preview-frame-container">
                    <div class="preview-frame" id="template-preview">
                        <!-- Page content will be loaded here -->
                        <?php foreach ($theme->pages as $index => $page): ?>
                            <div class="page-preview" id="page-<?php echo $page->id; ?>" style="<?php echo $index !== 0 ? 'display:none;' : ''; ?>">
                                <?php echo self::render_page_preview($theme, $page); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Pages Modal -->
<div id="pages-modal" class="spfm-modal" style="display:none;">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3><span class="dashicons dashicons-admin-page"></span> <span id="modal-theme-name">Template Pages</span></h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body" id="pages-modal-content">
            <!-- Pages content loaded via AJAX -->
        </div>
    </div>
</div>

<style>
/* Template Filters */
.template-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 25px 0;
    padding: 15px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.filter-btn {
    padding: 8px 16px;
    background: #f0f0f0;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.3s;
}
.filter-btn:hover, .filter-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

/* Templates Grid */
.templates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 30px;
    margin-top: 20px;
}
.template-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    transition: all 0.3s;
}
.template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
.template-card.hidden {
    display: none;
}

/* Template Preview */
.template-preview {
    height: 240px;
    position: relative;
    overflow: hidden;
}
.preview-mockup {
    width: 90%;
    height: 200px;
    margin: 20px auto;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}
.mockup-header {
    height: 30px;
    display: flex;
    align-items: center;
    padding: 0 15px;
    border-bottom: 1px solid #eee;
}
.mockup-nav {
    display: flex;
    gap: 15px;
}
.mockup-nav span {
    font-size: 8px;
    color: #666;
}
.mockup-hero {
    height: 80px;
    padding: 15px;
    text-align: center;
}
.mockup-title {
    width: 60%;
    height: 10px;
    background: rgba(255,255,255,0.8);
    margin: 0 auto 8px;
    border-radius: 3px;
}
.mockup-subtitle {
    width: 40%;
    height: 6px;
    background: rgba(255,255,255,0.5);
    margin: 0 auto 10px;
    border-radius: 3px;
}
.mockup-btn {
    width: 50px;
    height: 14px;
    margin: 0 auto;
    border-radius: 7px;
}
.mockup-content {
    height: 60px;
    padding: 10px;
}
.mockup-cards {
    display: flex;
    gap: 8px;
    justify-content: center;
}
.mockup-card {
    width: 25%;
    height: 40px;
    background: #e9ecef;
    border-radius: 4px;
}
.mockup-footer {
    height: 20px;
}
.preview-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}
.template-card:hover .preview-overlay {
    opacity: 1;
}
.btn-preview {
    background: #fff;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}
.btn-preview:hover {
    background: #f0f0f0;
}

/* Template Info */
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
    margin-bottom: 15px;
    line-height: 1.5;
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
    font-size: 13px;
    color: #666;
}
.meta-item.colors {
    display: flex;
    gap: 5px;
}
.color-dot {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
.template-pages {
    margin-bottom: 12px;
}
.template-pages strong {
    font-size: 12px;
    color: #666;
}
.page-tag {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 11px;
    background: #f0f0f0;
    padding: 3px 8px;
    border-radius: 10px;
    margin: 3px 3px 0 0;
}
.page-tag .dashicons {
    font-size: 12px;
    width: 12px;
    height: 12px;
}
.template-features {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}
.feature-tag {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    font-size: 11px;
    color: #28a745;
}
.feature-tag .dashicons {
    font-size: 12px;
    width: 12px;
    height: 12px;
}

/* Template Actions */
.template-actions {
    padding: 15px 20px;
    background: #f8f9fa;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}
.template-actions .button {
    flex: 1;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.template-actions .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

/* Preview Page */
.template-preview-page {
    display: flex;
    gap: 25px;
    margin-top: 20px;
    height: calc(100vh - 150px);
}
.preview-sidebar {
    width: 300px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.theme-info-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}
.theme-info-card h2 {
    margin: 0 0 10px 0;
    font-size: 20px;
}
.theme-info-card p {
    color: #666;
    font-size: 14px;
    margin: 0;
}
.color-palette {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.color-palette h4 {
    margin: 0 0 12px 0;
    font-size: 13px;
    color: #666;
}
.palette-colors {
    display: flex;
    gap: 15px;
}
.palette-color {
    text-align: center;
}
.palette-color span {
    display: block;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    margin-bottom: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.palette-color small {
    font-size: 10px;
    color: #999;
}
.pages-nav {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    flex: 1;
    overflow-y: auto;
}
.pages-nav h4 {
    margin: 0 0 15px 0;
    font-size: 14px;
    color: #666;
}
.page-nav-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 12px 15px;
    background: #f8f9fa;
    border: 2px solid transparent;
    border-radius: 8px;
    cursor: pointer;
    text-align: left;
    margin-bottom: 8px;
    transition: all 0.2s;
}
.page-nav-btn:hover {
    background: #f0f0f0;
}
.page-nav-btn.active {
    background: #fff;
    border-color: #667eea;
}
.page-nav-btn .dashicons {
    color: #667eea;
}
.page-nav-btn .page-name {
    flex: 1;
    font-weight: 500;
}
.page-nav-btn .page-sections {
    font-size: 11px;
    color: #999;
}

/* Preview Main */
.preview-main {
    flex: 1;
    background: #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.preview-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background: #333;
    color: #fff;
}
.device-switcher {
    display: flex;
    gap: 5px;
}
.device-btn {
    background: rgba(255,255,255,0.1);
    border: none;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}
.device-btn.active {
    background: #667eea;
}
.current-page-name {
    font-size: 14px;
}
.preview-frame-container {
    flex: 1;
    padding: 20px;
    display: flex;
    justify-content: center;
    overflow: auto;
}
.preview-frame {
    width: 100%;
    max-width: 1000px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.15);
    overflow: hidden;
}

/* Page Preview Sections */
.page-preview-section {
    padding: 40px;
    border-bottom: 1px solid #eee;
}
.page-preview-section:last-child {
    border-bottom: none;
}
.section-header {
    margin-bottom: 25px;
}
.section-title {
    font-size: 14px;
    color: #667eea;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}
.section-name {
    font-size: 22px;
    margin: 0;
    color: #333;
}
.fields-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}
.field-item {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #667eea;
}
.field-label {
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
}
.field-type {
    font-size: 14px;
    font-weight: 500;
    color: #333;
}
.field-type .dashicons {
    margin-right: 5px;
    color: #667eea;
}

/* Modal */
.spfm-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-large {
    width: 90%;
    max-width: 900px;
    max-height: 90vh;
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
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.modal-header h3 {
    margin: 0;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
}
.close-modal {
    background: none;
    border: none;
    color: #fff;
    font-size: 28px;
    cursor: pointer;
}
.modal-body {
    padding: 25px;
    overflow-y: auto;
    max-height: 70vh;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px;
    background: #fff;
    border-radius: 15px;
}
.empty-state .dashicons {
    font-size: 60px;
    width: 60px;
    height: 60px;
    color: #ddd;
    margin-bottom: 15px;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Category filter
    $('.filter-btn').on('click', function() {
        var category = $(this).data('category');
        
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        if (category === 'all') {
            $('.template-card').removeClass('hidden');
        } else {
            $('.template-card').addClass('hidden');
            $('.template-card[data-category="' + category + '"]').removeClass('hidden');
        }
    });
    
    // Device switcher
    $('.device-btn').on('click', function() {
        $('.device-btn').removeClass('active');
        $(this).addClass('active');
        
        var device = $(this).data('device');
        var frame = $('.preview-frame');
        
        frame.removeClass('desktop tablet mobile');
        
        if (device === 'tablet') {
            frame.css('max-width', '768px');
        } else if (device === 'mobile') {
            frame.css('max-width', '375px');
        } else {
            frame.css('max-width', '1000px');
        }
    });
    
    // Close modal
    $('.close-modal').on('click', function() {
        $(this).closest('.spfm-modal').hide();
    });
});

function previewTemplate(id) {
    window.location.href = '<?php echo admin_url('admin.php?page=spfm-themes&action=preview&id='); ?>' + id;
}

function viewPages(id) {
    jQuery.post(spfm_ajax.ajax_url, {
        action: 'spfm_get_theme_pages',
        nonce: spfm_ajax.nonce,
        theme_id: id
    }, function(response) {
        if (response.success) {
            jQuery('#modal-theme-name').text(response.data.theme_name + ' - Pages');
            jQuery('#pages-modal-content').html(response.data.html);
            jQuery('#pages-modal').show();
        }
    });
}

function showPage(pageId, btn) {
    // Update active state
    jQuery('.page-nav-btn').removeClass('active');
    jQuery(btn).addClass('active');
    
    // Show selected page
    jQuery('.page-preview').hide();
    jQuery('#page-' + pageId).show();
    
    // Update toolbar
    jQuery('.current-page-name').text(jQuery(btn).find('.page-name').text());
}
</script>

<?php
// Helper function to render page preview
function render_page_preview($theme, $page) {
    ob_start();
    ?>
    <div class="page-preview-wrapper" style="font-family: <?php echo esc_attr($theme->font_family); ?>, sans-serif;">
        <!-- Page Header -->
        <div class="preview-page-header" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?> 0%, <?php echo esc_attr($theme->secondary_color); ?> 100%); padding: 60px 40px; color: #fff; text-align: center;">
            <h1 style="margin: 0; font-family: <?php echo esc_attr($theme->heading_font); ?>, sans-serif;"><?php echo esc_html($page->page_name); ?></h1>
            <p style="margin: 10px 0 0; opacity: 0.9;"><?php echo esc_html($page->page_description); ?></p>
        </div>
        
        <!-- Page Sections -->
        <?php foreach ($page->sections as $section): ?>
            <div class="page-preview-section">
                <div class="section-header">
                    <div class="section-title"><?php echo esc_html($section->section_type); ?></div>
                    <h2 class="section-name"><?php echo esc_html($section->section_name); ?></h2>
                </div>
                
                <div class="fields-grid">
                    <?php if (!empty($section->fields)): ?>
                        <?php foreach ($section->fields as $field): ?>
                            <div class="field-item">
                                <div class="field-label"><?php echo esc_html($field['label']); ?></div>
                                <div class="field-type">
                                    <?php 
                                    $icon = 'dashicons-edit';
                                    if ($field['type'] === 'image') $icon = 'dashicons-format-image';
                                    elseif ($field['type'] === 'textarea' || $field['type'] === 'editor') $icon = 'dashicons-text';
                                    elseif ($field['type'] === 'email') $icon = 'dashicons-email';
                                    elseif ($field['type'] === 'url') $icon = 'dashicons-admin-links';
                                    elseif ($field['type'] === 'repeater') $icon = 'dashicons-list-view';
                                    ?>
                                    <span class="dashicons <?php echo $icon; ?>"></span>
                                    <?php echo ucfirst($field['type']); ?>
                                    <?php if (!empty($field['required'])): ?>
                                        <span style="color: #dc3545;">*</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
?>
