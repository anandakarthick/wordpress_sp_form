<?php
if (!defined('ABSPATH')) {
    exit;
}

$forms_handler = SPFM_Forms::get_instance();
$themes_handler = SPFM_Themes::get_instance();

$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get filter values
$filter_form = isset($_GET['form_id']) ? intval($_GET['form_id']) : '';
$filter_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

// Get all forms for filter
$all_forms = $forms_handler->get_all(array('per_page' => 100));

// Get submissions
$submissions = $forms_handler->get_submissions(array(
    'form_id' => $filter_form,
    'status' => $filter_status,
    'per_page' => 50
));

// Get stats
$stats = $forms_handler->get_submission_stats();

$statuses = array(
    'new' => array('label' => 'New', 'color' => '#007bff'),
    'in_progress' => array('label' => 'In Progress', 'color' => '#ffc107'),
    'completed' => array('label' => 'Completed', 'color' => '#28a745'),
    'cancelled' => array('label' => 'Cancelled', 'color' => '#dc3545')
);
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-text-page"></span> Submissions
    </h1>
    
    <?php if ($action === 'list'): ?>
        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <span class="dashicons dashicons-text-page"></span>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['total']; ?></span>
                    <span class="stat-label">Total Submissions</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                    <span class="dashicons dashicons-clock"></span>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['new']; ?></span>
                    <span class="stat-label">New</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                    <span class="dashicons dashicons-yes-alt"></span>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['completed']; ?></span>
                    <span class="stat-label">Completed</span>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters-bar">
            <form method="get" action="" class="filter-form">
                <input type="hidden" name="page" value="spfm-submissions">
                
                <select name="form_id" class="filter-select">
                    <option value="">All Forms</option>
                    <?php foreach ($all_forms as $f): ?>
                        <option value="<?php echo $f->id; ?>" <?php selected($filter_form, $f->id); ?>>
                            <?php echo esc_html($f->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <select name="status" class="filter-select">
                    <option value="">All Statuses</option>
                    <?php foreach ($statuses as $key => $status): ?>
                        <option value="<?php echo $key; ?>" <?php selected($filter_status, $key); ?>>
                            <?php echo $status['label']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="button">
                    <span class="dashicons dashicons-filter"></span> Filter
                </button>
                
                <?php if ($filter_form || $filter_status): ?>
                    <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="button">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Submissions List -->
        <?php if (empty($submissions)): ?>
            <div class="empty-state">
                <span class="dashicons dashicons-text-page"></span>
                <h3>No Submissions Yet</h3>
                <p>Submissions will appear here when customers submit their website orders.</p>
            </div>
        <?php else: ?>
            <div class="submissions-table-container">
                <table class="wp-list-table widefat striped submissions-table">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Customer</th>
                            <th>Form</th>
                            <th>Selected Template</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $sub): 
                            $customer_info = json_decode($sub->customer_info, true) ?: array();
                        ?>
                            <tr>
                                <td><strong>#<?php echo $sub->id; ?></strong></td>
                                <td>
                                    <div class="customer-info">
                                        <strong><?php echo esc_html($customer_info['name'] ?? 'Unknown'); ?></strong>
                                        <?php if (!empty($customer_info['email'])): ?>
                                            <br><small><?php echo esc_html($customer_info['email']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?php echo esc_html($sub->form_name); ?></td>
                                <td>
                                    <div class="template-info">
                                        <span class="template-colors">
                                            <span style="background: <?php echo esc_attr($sub->primary_color); ?>;"></span>
                                            <span style="background: <?php echo esc_attr($sub->secondary_color); ?>;"></span>
                                        </span>
                                        <?php echo esc_html($sub->theme_name); ?>
                                    </div>
                                </td>
                                <td>
                                    <select class="status-select" data-id="<?php echo $sub->id; ?>" 
                                            style="border-color: <?php echo $statuses[$sub->status]['color'] ?? '#ccc'; ?>;">
                                        <?php foreach ($statuses as $key => $status): ?>
                                            <option value="<?php echo $key; ?>" <?php selected($sub->status, $key); ?>>
                                                <?php echo $status['label']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <span title="<?php echo date('M j, Y g:i A', strtotime($sub->created_at)); ?>">
                                        <?php echo human_time_diff(strtotime($sub->created_at), current_time('timestamp')); ?> ago
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=spfm-submissions&action=view&id=' . $sub->id); ?>" class="button button-small">
                                        <span class="dashicons dashicons-visibility"></span> View
                                    </a>
                                    <button class="button button-small button-link-delete delete-submission" data-id="<?php echo $sub->id; ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
    <?php elseif ($action === 'view' && $id): ?>
        <?php 
        $submission = $forms_handler->get_submission_by_id($id);
        if (!$submission): 
            echo '<p>Submission not found.</p>';
            return;
        endif;
        
        $theme = $themes_handler->get_theme_complete($submission->selected_theme_id);
        $page_contents = json_decode($submission->page_contents, true) ?: array();
        $color_customizations = json_decode($submission->color_customizations, true) ?: array();
        $customer_info = json_decode($submission->customer_info, true) ?: array();
        ?>
        
        <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="page-title-action">← Back to Submissions</a>
        
        <div class="submission-view">
            <!-- Header -->
            <div class="submission-header">
                <div class="header-main">
                    <h2>Submission #<?php echo $submission->id; ?></h2>
                    <p>From: <?php echo esc_html($submission->form_name); ?></p>
                </div>
                <div class="header-actions">
                    <select class="status-select-large" id="submission-status" data-id="<?php echo $submission->id; ?>">
                        <?php foreach ($statuses as $key => $status): ?>
                            <option value="<?php echo $key; ?>" <?php selected($submission->status, $key); ?>
                                    style="background: <?php echo $status['color']; ?>;">
                                <?php echo $status['label']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="button button-primary" onclick="openPreview()">
                        <span class="dashicons dashicons-visibility"></span> Preview Website
                    </button>
                </div>
            </div>
            
            <div class="submission-content">
                <!-- Left Column: Details -->
                <div class="submission-details">
                    <!-- Customer Info -->
                    <div class="detail-card">
                        <h3><span class="dashicons dashicons-admin-users"></span> Customer Information</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <label>Name</label>
                                <span><?php echo esc_html($customer_info['name'] ?? '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <label>Email</label>
                                <span>
                                    <a href="mailto:<?php echo esc_attr($customer_info['email'] ?? ''); ?>">
                                        <?php echo esc_html($customer_info['email'] ?? '-'); ?>
                                    </a>
                                </span>
                            </div>
                            <div class="detail-item">
                                <label>Phone</label>
                                <span>
                                    <a href="tel:<?php echo esc_attr($customer_info['phone'] ?? ''); ?>">
                                        <?php echo esc_html($customer_info['phone'] ?? '-'); ?>
                                    </a>
                                </span>
                            </div>
                            <div class="detail-item">
                                <label>Submitted</label>
                                <span><?php echo date('F j, Y g:i A', strtotime($submission->created_at)); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Selected Template -->
                    <div class="detail-card">
                        <h3><span class="dashicons dashicons-admin-appearance"></span> Selected Template</h3>
                        <?php if ($theme): ?>
                            <div class="selected-template">
                                <div class="template-preview-mini" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?> 0%, <?php echo esc_attr($theme->secondary_color); ?> 100%);">
                                </div>
                                <div class="template-info">
                                    <h4><?php echo esc_html($theme->name); ?></h4>
                                    <span class="category"><?php echo esc_html(ucfirst($theme->category)); ?></span>
                                    <p><?php echo count($theme->pages); ?> pages</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <p>Template not found.</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Color Customizations -->
                    <?php if (!empty($color_customizations)): ?>
                        <div class="detail-card">
                            <h3><span class="dashicons dashicons-art"></span> Color Customizations</h3>
                            <div class="color-swatches">
                                <?php foreach ($color_customizations as $key => $color): ?>
                                    <div class="color-swatch">
                                        <span style="background: <?php echo esc_attr($color); ?>;"></span>
                                        <small><?php echo ucwords(str_replace('_', ' ', $key)); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Submission Meta -->
                    <div class="detail-card">
                        <h3><span class="dashicons dashicons-info"></span> Submission Details</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <label>IP Address</label>
                                <span><?php echo esc_html($submission->ip_address); ?></span>
                            </div>
                            <div class="detail-item full-width">
                                <label>User Agent</label>
                                <span class="small-text"><?php echo esc_html($submission->user_agent); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Page Contents -->
                <div class="submission-contents">
                    <div class="detail-card">
                        <h3><span class="dashicons dashicons-media-text"></span> Page Contents</h3>
                        
                        <?php if ($theme && !empty($theme->pages)): ?>
                            <div class="page-contents-accordion">
                                <?php foreach ($theme->pages as $page_index => $page): ?>
                                    <div class="accordion-item">
                                        <button class="accordion-header" onclick="toggleAccordion(this)">
                                            <span class="dashicons <?php echo esc_attr($page->page_icon); ?>"></span>
                                            <?php echo esc_html($page->page_name); ?>
                                            <span class="accordion-icon dashicons dashicons-arrow-down"></span>
                                        </button>
                                        <div class="accordion-content">
                                            <?php foreach ($page->sections as $sec_index => $section): ?>
                                                <div class="section-content">
                                                    <h5><?php echo esc_html($section->section_name); ?></h5>
                                                    <div class="fields-list">
                                                        <?php 
                                                        if (!empty($section->fields)):
                                                            foreach ($section->fields as $field): 
                                                                $field_key = "page_{$page_index}_sec_{$sec_index}_{$field['name']}";
                                                                $value = $page_contents[$field_key] ?? '';
                                                                
                                                                if (empty($value)) continue;
                                                        ?>
                                                            <div class="field-value">
                                                                <label><?php echo esc_html($field['label']); ?></label>
                                                                <div class="value">
                                                                    <?php if ($field['type'] === 'image'): ?>
                                                                        <img src="<?php echo esc_url($value); ?>" alt="" style="max-width: 100px;">
                                                                    <?php elseif ($field['type'] === 'editor' || $field['type'] === 'textarea'): ?>
                                                                        <?php echo nl2br(esc_html($value)); ?>
                                                                    <?php elseif ($field['type'] === 'url'): ?>
                                                                        <a href="<?php echo esc_url($value); ?>" target="_blank"><?php echo esc_html($value); ?></a>
                                                                    <?php else: ?>
                                                                        <?php echo esc_html($value); ?>
                                                                    <?php endif; ?>
                                                                </div>
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
                        <?php else: ?>
                            <pre><?php echo esc_html(json_encode($page_contents, JSON_PRETTY_PRINT)); ?></pre>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Preview Modal -->
        <div id="preview-modal" class="spfm-modal" style="display:none;">
            <div class="modal-content modal-fullscreen">
                <div class="modal-header">
                    <h3>Website Preview - Submission #<?php echo $submission->id; ?></h3>
                    <div class="modal-toolbar">
                        <button class="device-btn active" data-device="desktop"><span class="dashicons dashicons-desktop"></span></button>
                        <button class="device-btn" data-device="tablet"><span class="dashicons dashicons-tablet"></span></button>
                        <button class="device-btn" data-device="mobile"><span class="dashicons dashicons-smartphone"></span></button>
                    </div>
                    <button class="close-modal" onclick="closePreview()">&times;</button>
                </div>
                <div class="modal-body">
                    <iframe id="preview-iframe" src="<?php echo admin_url('admin-ajax.php?action=spfm_render_submission_preview&id=' . $submission->id . '&nonce=' . wp_create_nonce('spfm_nonce')); ?>"></iframe>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* Stats Cards */
.stats-cards {
    display: flex;
    gap: 20px;
    margin: 20px 0;
}
.stat-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    flex: 1;
}
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-icon .dashicons {
    color: #fff;
    font-size: 24px;
    width: 24px;
    height: 24px;
}
.stat-value {
    display: block;
    font-size: 28px;
    font-weight: 700;
    color: #333;
}
.stat-label {
    font-size: 13px;
    color: #666;
}

/* Filters */
.filters-bar {
    background: #fff;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.filter-form {
    display: flex;
    gap: 10px;
    align-items: center;
}
.filter-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    min-width: 150px;
}

/* Submissions Table */
.submissions-table-container {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.submissions-table th {
    background: #f8f9fa;
    padding: 15px;
    font-weight: 600;
}
.submissions-table td {
    padding: 15px;
    vertical-align: middle;
}
.template-info {
    display: flex;
    align-items: center;
    gap: 10px;
}
.template-colors {
    display: flex;
    gap: 3px;
}
.template-colors span {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
.status-select {
    padding: 5px 10px;
    border-radius: 5px;
    border-width: 2px;
    font-size: 12px;
}

/* Submission View */
.submission-view {
    margin-top: 20px;
}
.submission-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 25px 30px;
    border-radius: 12px;
    color: #fff;
    margin-bottom: 25px;
}
.submission-header h2 {
    margin: 0;
    color: #fff;
}
.submission-header p {
    margin: 5px 0 0;
    opacity: 0.9;
}
.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}
.status-select-large {
    padding: 10px 15px;
    border-radius: 8px;
    border: 2px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.9);
    font-weight: 500;
}
.submission-content {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 25px;
}
.detail-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.detail-card h3 {
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
.detail-card h3 .dashicons {
    color: #667eea;
}
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}
.detail-item {
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}
.detail-item.full-width {
    grid-column: 1 / -1;
}
.detail-item label {
    display: block;
    font-size: 11px;
    color: #999;
    margin-bottom: 5px;
    text-transform: uppercase;
}
.detail-item span {
    font-weight: 500;
}
.small-text {
    font-size: 12px;
    word-break: break-all;
}

/* Selected Template */
.selected-template {
    display: flex;
    gap: 15px;
    align-items: center;
}
.template-preview-mini {
    width: 80px;
    height: 60px;
    border-radius: 8px;
    flex-shrink: 0;
}
.selected-template .template-info h4 {
    margin: 0 0 5px 0;
}
.selected-template .category {
    font-size: 11px;
    background: #e9ecef;
    padding: 2px 8px;
    border-radius: 10px;
}

/* Color Swatches */
.color-swatches {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}
.color-swatch {
    text-align: center;
}
.color-swatch span {
    display: block;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    margin-bottom: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.color-swatch small {
    font-size: 10px;
    color: #666;
}

/* Accordion */
.accordion-item {
    border: 1px solid #eee;
    border-radius: 8px;
    margin-bottom: 10px;
    overflow: hidden;
}
.accordion-header {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 15px;
    background: #f8f9fa;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    text-align: left;
}
.accordion-header .dashicons:first-child {
    color: #667eea;
}
.accordion-icon {
    margin-left: auto;
    transition: transform 0.3s;
}
.accordion-item.open .accordion-icon {
    transform: rotate(180deg);
}
.accordion-content {
    display: none;
    padding: 15px;
}
.accordion-item.open .accordion-content {
    display: block;
}
.section-content {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
.section-content:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}
.section-content h5 {
    margin: 0 0 15px 0;
    color: #667eea;
}
.field-value {
    margin-bottom: 12px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 6px;
}
.field-value label {
    display: block;
    font-size: 11px;
    color: #999;
    margin-bottom: 5px;
    text-transform: uppercase;
}
.field-value .value {
    font-size: 14px;
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
.modal-fullscreen {
    width: 95%;
    height: 95%;
    display: flex;
    flex-direction: column;
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
    color: #fff;
    flex: 1;
}
.modal-toolbar {
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
    background: #667eea;
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
#preview-iframe {
    width: 100%;
    max-width: 1000px;
    height: 100%;
    border: none;
    border-radius: 10px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.1);
    background: #fff;
    transition: all 0.3s;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 12px;
}
.empty-state .dashicons {
    font-size: 60px;
    width: 60px;
    height: 60px;
    color: #ddd;
}

@media (max-width: 1200px) {
    .submission-content {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Status change
    $('.status-select, .status-select-large').on('change', function() {
        var id = $(this).data('id');
        var status = $(this).val();
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_update_submission_status',
            nonce: spfm_ajax.nonce,
            id: id,
            status: status
        }, function(response) {
            if (!response.success) {
                alert('Failed to update status');
            }
        });
    });
    
    // Delete submission
    $('.delete-submission').on('click', function() {
        if (!confirm('Are you sure you want to delete this submission?')) return;
        
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_submission',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                $row.fadeOut(300, function() { $(this).remove(); });
            } else {
                alert(response.data.message);
            }
        });
    });
    
    // Device switcher in modal
    $('#preview-modal .device-btn').on('click', function() {
        $('#preview-modal .device-btn').removeClass('active');
        $(this).addClass('active');
        
        var device = $(this).data('device');
        var iframe = $('#preview-iframe');
        
        if (device === 'tablet') {
            iframe.css('max-width', '768px');
        } else if (device === 'mobile') {
            iframe.css('max-width', '375px');
        } else {
            iframe.css('max-width', '1000px');
        }
    });
});

function toggleAccordion(btn) {
    var item = jQuery(btn).closest('.accordion-item');
    item.toggleClass('open');
}

function openPreview() {
    jQuery('#preview-modal').show();
}

function closePreview() {
    jQuery('#preview-modal').hide();
}
</script>
