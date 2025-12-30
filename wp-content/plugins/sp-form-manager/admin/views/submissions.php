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
    'new' => array('label' => 'New', 'color' => '#f59e0b', 'bg' => '#fef3c7'),
    'in_progress' => array('label' => 'In Progress', 'color' => '#3b82f6', 'bg' => '#dbeafe'),
    'completed' => array('label' => 'Completed', 'color' => '#10b981', 'bg' => '#d1fae5'),
    'cancelled' => array('label' => 'Cancelled', 'color' => '#ef4444', 'bg' => '#fee2e2')
);
?>

<div class="spfm-submissions-wrap">
    <?php if ($action === 'list'): ?>
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <h1><span class="dashicons dashicons-portfolio"></span> Submissions</h1>
                <p>Review and manage customer website order submissions</p>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <span class="dashicons dashicons-portfolio"></span>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $stats['total']; ?></div>
                    <div class="stat-label">Total Submissions</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon new">
                    <span class="dashicons dashicons-clock"></span>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $stats['new']; ?></div>
                    <div class="stat-label">New</div>
                    <div class="stat-detail">Pending review</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon progress">
                    <span class="dashicons dashicons-update"></span>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $stats['in_progress'] ?? 0; ?></div>
                    <div class="stat-label">In Progress</div>
                    <div class="stat-detail">Being worked on</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completed">
                    <span class="dashicons dashicons-yes-alt"></span>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $stats['completed']; ?></div>
                    <div class="stat-label">Completed</div>
                    <div class="stat-detail">Successfully delivered</div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><span class="dashicons dashicons-filter"></span> Filter Submissions</h3>
            </div>
            <div class="card-body">
                <form method="get" action="" class="filter-form">
                    <input type="hidden" name="page" value="spfm-submissions">
                    
                    <div class="filter-group">
                        <label>Form</label>
                        <select name="form_id">
                            <option value="">All Forms</option>
                            <?php foreach ($all_forms as $f): ?>
                                <option value="<?php echo $f->id; ?>" <?php selected($filter_form, $f->id); ?>>
                                    <?php echo esc_html($f->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="">All Statuses</option>
                            <?php foreach ($statuses as $key => $status): ?>
                                <option value="<?php echo $key; ?>" <?php selected($filter_status, $key); ?>>
                                    <?php echo $status['label']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <span class="dashicons dashicons-search"></span> Filter
                        </button>
                        <?php if ($filter_form || $filter_status): ?>
                            <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="btn btn-outline">Clear</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Submissions List -->
        <?php if (empty($submissions)): ?>
            <div class="empty-state-card">
                <div class="empty-icon">
                    <span class="dashicons dashicons-portfolio"></span>
                </div>
                <h3>No Submissions Yet</h3>
                <p>Submissions will appear here when customers submit their website orders.</p>
            </div>
        <?php else: ?>
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-list-view"></span> All Submissions</h3>
                    <span class="badge"><?php echo count($submissions); ?> results</span>
                </div>
                <div class="card-body no-padding">
                    <div class="submissions-list">
                        <?php foreach ($submissions as $sub): 
                            $customer_info = json_decode($sub->customer_info, true) ?: array();
                            $status_data = $statuses[$sub->status] ?? $statuses['new'];
                        ?>
                            <div class="submission-item">
                                <div class="submission-avatar">
                                    <?php echo strtoupper(substr($customer_info['name'] ?? 'U', 0, 1)); ?>
                                </div>
                                <div class="submission-main">
                                    <div class="submission-info">
                                        <h4>
                                            <a href="<?php echo admin_url('admin.php?page=spfm-submissions&action=view&id=' . $sub->id); ?>">
                                                #<?php echo $sub->id; ?> - <?php echo esc_html($customer_info['name'] ?? 'Unknown'); ?>
                                            </a>
                                        </h4>
                                        <div class="submission-meta">
                                            <span><span class="dashicons dashicons-email"></span> <?php echo esc_html($customer_info['email'] ?? '-'); ?></span>
                                            <span><span class="dashicons dashicons-feedback"></span> <?php echo esc_html($sub->form_name); ?></span>
                                            <span><span class="dashicons dashicons-clock"></span> <?php echo human_time_diff(strtotime($sub->created_at)); ?> ago</span>
                                        </div>
                                    </div>
                                    <div class="submission-template">
                                        <div class="template-colors">
                                            <span style="background: <?php echo esc_attr($sub->primary_color); ?>;"></span>
                                            <span style="background: <?php echo esc_attr($sub->secondary_color); ?>;"></span>
                                        </div>
                                        <span class="template-name"><?php echo esc_html($sub->theme_name); ?></span>
                                    </div>
                                </div>
                                <div class="submission-status">
                                    <select class="status-select" data-id="<?php echo $sub->id; ?>" 
                                            style="background: <?php echo $status_data['bg']; ?>; color: <?php echo $status_data['color']; ?>; border-color: <?php echo $status_data['color']; ?>;">
                                        <?php foreach ($statuses as $key => $status): ?>
                                            <option value="<?php echo $key; ?>" <?php selected($sub->status, $key); ?>>
                                                <?php echo $status['label']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="submission-actions">
                                    <a href="<?php echo admin_url('admin.php?page=spfm-submissions&action=view&id=' . $sub->id); ?>" class="btn btn-primary btn-sm">
                                        <span class="dashicons dashicons-visibility"></span> View
                                    </a>
                                    <button class="btn btn-danger btn-sm delete-submission" data-id="<?php echo $sub->id; ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
    <?php elseif ($action === 'view' && $id): ?>
        <?php 
        $submission = $forms_handler->get_submission_by_id($id);
        if (!$submission): 
            echo '<div class="empty-state-card"><h3>Submission not found.</h3></div>';
            return;
        endif;
        
        $theme = $themes_handler->get_theme_complete($submission->selected_theme_id);
        $page_contents = json_decode($submission->page_contents, true) ?: array();
        $color_customizations = json_decode($submission->color_customizations, true) ?: array();
        $customer_info = json_decode($submission->customer_info, true) ?: array();
        $status_data = $statuses[$submission->status] ?? $statuses['new'];
        ?>
        
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="back-link">
                    <span class="dashicons dashicons-arrow-left-alt"></span> Back to Submissions
                </a>
                <h1><span class="dashicons dashicons-portfolio"></span> Submission #<?php echo $submission->id; ?></h1>
                <p>From: <?php echo esc_html($submission->form_name); ?> • Submitted <?php echo date('F j, Y g:i A', strtotime($submission->created_at)); ?></p>
            </div>
            <div class="header-actions">
                <select class="status-select-large" id="submission-status" data-id="<?php echo $submission->id; ?>"
                        style="background: <?php echo $status_data['bg']; ?>; color: <?php echo $status_data['color']; ?>; border-color: <?php echo $status_data['color']; ?>;">
                    <?php foreach ($statuses as $key => $status): ?>
                        <option value="<?php echo $key; ?>" <?php selected($submission->status, $key); ?>>
                            <?php echo $status['label']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-white" onclick="openPreview()">
                    <span class="dashicons dashicons-visibility"></span> Preview Website
                </button>
            </div>
        </div>
        
        <div class="submission-view-grid">
            <!-- Left Column -->
            <div class="submission-sidebar">
                <!-- Customer Info -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><span class="dashicons dashicons-admin-users"></span> Customer</h3>
                    </div>
                    <div class="card-body">
                        <div class="customer-profile">
                            <div class="customer-avatar">
                                <?php echo strtoupper(substr($customer_info['name'] ?? 'U', 0, 1)); ?>
                            </div>
                            <h4><?php echo esc_html($customer_info['name'] ?? 'Unknown'); ?></h4>
                        </div>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="dashicons dashicons-email"></span>
                                <a href="mailto:<?php echo esc_attr($customer_info['email'] ?? ''); ?>">
                                    <?php echo esc_html($customer_info['email'] ?? '-'); ?>
                                </a>
                            </div>
                            <div class="info-item">
                                <span class="dashicons dashicons-phone"></span>
                                <a href="tel:<?php echo esc_attr($customer_info['phone'] ?? ''); ?>">
                                    <?php echo esc_html($customer_info['phone'] ?? '-'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Selected Template -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><span class="dashicons dashicons-layout"></span> Template</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($theme): ?>
                            <div class="template-preview-box" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?> 0%, <?php echo esc_attr($theme->secondary_color); ?> 100%);">
                                <span class="template-icon">🏥</span>
                            </div>
                            <h4 class="template-title"><?php echo esc_html($theme->name); ?></h4>
                            <span class="template-category"><?php echo esc_html(ucfirst($theme->category)); ?></span>
                            <p class="template-pages"><?php echo count($theme->pages); ?> pages</p>
                        <?php else: ?>
                            <p class="text-muted">Template not found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Color Customizations -->
                <?php if (!empty($color_customizations)): ?>
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><span class="dashicons dashicons-art"></span> Colors</h3>
                        </div>
                        <div class="card-body">
                            <div class="color-swatches">
                                <?php foreach ($color_customizations as $key => $color): ?>
                                    <div class="color-swatch">
                                        <span class="swatch" style="background: <?php echo esc_attr($color); ?>;"></span>
                                        <small><?php echo ucwords(str_replace('_', ' ', $key)); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Meta Info -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><span class="dashicons dashicons-info"></span> Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="meta-list">
                            <div class="meta-item">
                                <label>IP Address</label>
                                <span><?php echo esc_html($submission->ip_address); ?></span>
                            </div>
                            <div class="meta-item">
                                <label>Submitted</label>
                                <span><?php echo date('M j, Y g:i A', strtotime($submission->created_at)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Page Contents -->
            <div class="submission-main-content">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><span class="dashicons dashicons-media-text"></span> Page Contents</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($theme && !empty($theme->pages)): ?>
                            <div class="pages-accordion">
                                <?php foreach ($theme->pages as $page_index => $page): ?>
                                    <div class="accordion-item <?php echo $page_index === 0 ? 'open' : ''; ?>">
                                        <button class="accordion-header" onclick="toggleAccordion(this)">
                                            <span class="dashicons <?php echo esc_attr($page->page_icon); ?>"></span>
                                            <?php echo esc_html($page->page_name); ?>
                                            <span class="accordion-arrow dashicons dashicons-arrow-down-alt2"></span>
                                        </button>
                                        <div class="accordion-content">
                                            <?php foreach ($page->sections as $sec_index => $section): ?>
                                                <div class="section-block">
                                                    <h5><?php echo esc_html($section->section_name); ?></h5>
                                                    <div class="fields-grid">
                                                        <?php 
                                                        if (!empty($section->fields)):
                                                            foreach ($section->fields as $field): 
                                                                $field_key = "page_{$page_index}_sec_{$sec_index}_{$field['name']}";
                                                                $value = $page_contents[$field_key] ?? '';
                                                                if (empty($value)) continue;
                                                        ?>
                                                            <div class="field-item">
                                                                <label><?php echo esc_html($field['label']); ?></label>
                                                                <div class="field-value">
                                                                    <?php if ($field['type'] === 'image'): ?>
                                                                        <img src="<?php echo esc_url($value); ?>" alt="">
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
                            <pre class="json-display"><?php echo esc_html(json_encode($page_contents, JSON_PRETTY_PRINT)); ?></pre>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Preview Modal -->
        <div id="preview-modal" class="preview-modal" style="display:none;">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Website Preview - Submission #<?php echo $submission->id; ?></h3>
                    <div class="device-switcher">
                        <button class="device-btn active" data-device="desktop"><span class="dashicons dashicons-desktop"></span></button>
                        <button class="device-btn" data-device="tablet"><span class="dashicons dashicons-tablet"></span></button>
                        <button class="device-btn" data-device="mobile"><span class="dashicons dashicons-smartphone"></span></button>
                    </div>
                    <button class="close-btn" onclick="closePreview()">&times;</button>
                </div>
                <div class="modal-body">
                    <iframe id="preview-iframe" src="<?php echo admin_url('admin-ajax.php?action=spfm_render_submission_preview&id=' . $submission->id . '&nonce=' . wp_create_nonce('spfm_nonce')); ?>"></iframe>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-submissions-wrap {
    padding: 20px;
    max-width: 1400px;
    font-family: 'Inter', -apple-system, sans-serif;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-radius: 20px;
    padding: 40px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.header-content h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
    color: #fff;
}
.header-content h1 .dashicons {
    font-size: 32px;
    width: 32px;
    height: 32px;
}
.header-content p {
    margin: 0;
    opacity: 0.9;
    font-size: 15px;
}
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 10px;
}
.back-link:hover { color: #fff; }
.header-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 14px;
    border: none;
    cursor: pointer;
}
.btn-primary { background: #f59e0b; color: #fff; }
.btn-primary:hover { background: #d97706; color: #fff; }
.btn-white { background: #fff; color: #f59e0b; }
.btn-white:hover { background: #fffbeb; transform: translateY(-2px); }
.btn-outline { background: transparent; color: #f59e0b; border: 2px solid #f59e0b; }
.btn-outline:hover { background: #f59e0b; color: #fff; }
.btn-danger { background: #fee2e2; color: #dc2626; }
.btn-danger:hover { background: #dc2626; color: #fff; }
.btn-sm { padding: 8px 16px; font-size: 13px; }
.btn .dashicons { font-size: 16px; width: 16px; height: 16px; }

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 25px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: all 0.3s;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-icon .dashicons { font-size: 28px; width: 28px; height: 28px; color: #fff; }
.stat-icon.total { background: linear-gradient(135deg, #f59e0b, #d97706); }
.stat-icon.new { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.stat-icon.progress { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.stat-icon.completed { background: linear-gradient(135deg, #10b981, #059669); }
.stat-content { flex: 1; }
.stat-number { font-size: 36px; font-weight: 700; color: #1e293b; line-height: 1; margin-bottom: 5px; }
.stat-label { font-size: 14px; font-weight: 600; color: #64748b; margin-bottom: 5px; }
.stat-detail { font-size: 12px; color: #94a3b8; }

/* Dashboard Card */
.dashboard-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    margin-bottom: 25px;
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #f1f5f9;
}
.card-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #1e293b;
}
.card-header .dashicons { color: #f59e0b; }
.card-header .badge {
    background: #fef3c7;
    color: #d97706;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.card-body { padding: 25px; }
.card-body.no-padding { padding: 0; }

/* Filter Form */
.filter-form {
    display: flex;
    gap: 15px;
    align-items: flex-end;
    flex-wrap: wrap;
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.filter-group label {
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
}
.filter-group select {
    padding: 10px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    min-width: 180px;
}
.filter-group select:focus {
    border-color: #f59e0b;
    outline: none;
}
.filter-actions {
    display: flex;
    gap: 10px;
}

/* Submissions List */
.submissions-list {
    display: flex;
    flex-direction: column;
}
.submission-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 25px;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.2s;
}
.submission-item:last-child { border-bottom: none; }
.submission-item:hover { background: #f8fafc; }
.submission-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 20px;
    flex-shrink: 0;
}
.submission-main {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}
.submission-info h4 {
    margin: 0 0 8px 0;
    font-size: 16px;
}
.submission-info h4 a {
    color: #1e293b;
    text-decoration: none;
}
.submission-info h4 a:hover { color: #f59e0b; }
.submission-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.submission-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    color: #64748b;
}
.submission-meta .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
    color: #94a3b8;
}
.submission-template {
    display: flex;
    align-items: center;
    gap: 10px;
}
.template-colors {
    display: flex;
    gap: 3px;
}
.template-colors span {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}
.template-name {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}
.submission-status { flex-shrink: 0; }
.status-select {
    padding: 8px 12px;
    border-radius: 8px;
    border: 2px solid;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}
.status-select-large {
    padding: 12px 20px;
    border-radius: 10px;
    border: 2px solid;
    font-size: 14px;
    font-weight: 600;
}
.submission-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

/* Submission View */
.submission-view-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 25px;
}
.submission-sidebar {
    display: flex;
    flex-direction: column;
    gap: 0;
}
.customer-profile {
    text-align: center;
    margin-bottom: 20px;
}
.customer-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 32px;
    margin: 0 auto 15px;
}
.customer-profile h4 {
    margin: 0;
    font-size: 18px;
    color: #1e293b;
}
.info-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 10px;
}
.info-item .dashicons {
    color: #f59e0b;
    font-size: 18px;
    width: 18px;
    height: 18px;
}
.info-item a {
    color: #1e293b;
    text-decoration: none;
}
.info-item a:hover { color: #f59e0b; }

/* Template Preview */
.template-preview-box {
    height: 100px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}
.template-icon { font-size: 40px; }
.template-title {
    margin: 0 0 8px 0;
    font-size: 16px;
    color: #1e293b;
}
.template-category {
    display: inline-block;
    background: #fef3c7;
    color: #d97706;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}
.template-pages {
    margin: 10px 0 0;
    color: #64748b;
    font-size: 13px;
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
.color-swatch .swatch {
    display: block;
    width: 45px;
    height: 45px;
    border-radius: 10px;
    margin-bottom: 5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.color-swatch small {
    font-size: 10px;
    color: #64748b;
}

/* Meta List */
.meta-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.meta-item {
    padding: 12px;
    background: #f8fafc;
    border-radius: 10px;
}
.meta-item label {
    display: block;
    font-size: 11px;
    color: #94a3b8;
    text-transform: uppercase;
    margin-bottom: 5px;
}
.meta-item span {
    font-size: 13px;
    color: #1e293b;
    font-weight: 500;
}

/* Accordion */
.pages-accordion {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.accordion-item {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}
.accordion-header {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 18px 20px;
    background: #f8fafc;
    border: none;
    cursor: pointer;
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
    text-align: left;
}
.accordion-header:hover { background: #f1f5f9; }
.accordion-header .dashicons:first-child { color: #f59e0b; }
.accordion-arrow {
    margin-left: auto;
    transition: transform 0.3s;
    color: #94a3b8;
}
.accordion-item.open .accordion-arrow { transform: rotate(180deg); }
.accordion-content {
    display: none;
    padding: 20px;
}
.accordion-item.open .accordion-content { display: block; }
.section-block {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
}
.section-block:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}
.section-block h5 {
    margin: 0 0 15px 0;
    color: #f59e0b;
    font-size: 14px;
}
.fields-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}
.field-item {
    padding: 15px;
    background: #f8fafc;
    border-radius: 10px;
}
.field-item label {
    display: block;
    font-size: 11px;
    color: #94a3b8;
    text-transform: uppercase;
    margin-bottom: 8px;
}
.field-item .field-value {
    font-size: 14px;
    color: #1e293b;
    line-height: 1.5;
}
.field-item img {
    max-width: 100%;
    border-radius: 8px;
}

/* Empty State */
.empty-state-card {
    text-align: center;
    padding: 80px 40px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}
.empty-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
}
.empty-icon .dashicons {
    font-size: 48px;
    width: 48px;
    height: 48px;
    color: #fff;
}
.empty-state-card h3 {
    margin: 0 0 10px 0;
    font-size: 24px;
    color: #1e293b;
}
.empty-state-card p {
    margin: 0;
    color: #64748b;
    font-size: 15px;
}

/* Preview Modal */
.preview-modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.9);
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-container {
    width: 95%;
    height: 95%;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
}
.modal-header {
    display: flex;
    align-items: center;
    padding: 15px 25px;
    background: #1e293b;
    color: #fff;
}
.modal-header h3 {
    margin: 0;
    flex: 1;
    font-size: 16px;
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
    padding: 8px 15px;
    border-radius: 8px;
    cursor: pointer;
}
.device-btn.active { background: #f59e0b; }
.close-btn {
    background: none;
    border: none;
    color: #fff;
    font-size: 32px;
    cursor: pointer;
    margin-left: 20px;
    line-height: 1;
}
.modal-body {
    flex: 1;
    padding: 25px;
    background: #e2e8f0;
    display: flex;
    justify-content: center;
    overflow: auto;
}
#preview-iframe {
    width: 100%;
    max-width: 1200px;
    height: 100%;
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    background: #fff;
    transition: max-width 0.3s;
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .submission-view-grid { grid-template-columns: 1fr; }
    .fields-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .page-header { flex-direction: column; text-align: center; gap: 20px; }
    .stats-grid { grid-template-columns: 1fr; }
    .submission-item { flex-direction: column; align-items: flex-start; }
    .submission-main { flex-direction: column; align-items: flex-start; }
}
</style>

<script>
var spfm_ajax = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('spfm_nonce'); ?>'
};

jQuery(document).ready(function($) {
    // Status change
    $('.status-select, .status-select-large').on('change', function() {
        var $select = $(this);
        var id = $select.data('id');
        var status = $select.val();
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_update_submission_status',
            nonce: spfm_ajax.nonce,
            id: id,
            status: status
        }, function(response) {
            if (response.success) {
                // Update colors based on status
                var colors = {
                    'new': { bg: '#fef3c7', color: '#f59e0b' },
                    'in_progress': { bg: '#dbeafe', color: '#3b82f6' },
                    'completed': { bg: '#d1fae5', color: '#10b981' },
                    'cancelled': { bg: '#fee2e2', color: '#ef4444' }
                };
                var c = colors[status];
                $select.css({
                    'background': c.bg,
                    'color': c.color,
                    'border-color': c.color
                });
            }
        });
    });
    
    // Delete submission
    $('.delete-submission').on('click', function() {
        if (!confirm('Are you sure you want to delete this submission?')) return;
        
        var id = $(this).data('id');
        var $item = $(this).closest('.submission-item');
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_submission',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                $item.fadeOut(300, function() { $(this).remove(); });
            } else {
                alert(response.data.message);
            }
        });
    });
    
    // Device switcher
    $('.device-btn').on('click', function() {
        $('.device-btn').removeClass('active');
        $(this).addClass('active');
        
        var device = $(this).data('device');
        var iframe = $('#preview-iframe');
        
        if (device === 'tablet') {
            iframe.css('max-width', '768px');
        } else if (device === 'mobile') {
            iframe.css('max-width', '375px');
        } else {
            iframe.css('max-width', '1200px');
        }
    });
});

function toggleAccordion(btn) {
    jQuery(btn).closest('.accordion-item').toggleClass('open');
}

function openPreview() {
    jQuery('#preview-modal').show();
}

function closePreview() {
    jQuery('#preview-modal').hide();
}
</script>
