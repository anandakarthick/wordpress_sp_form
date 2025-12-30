<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;

$customers_handler = SPFM_Customers::get_instance();
$themes_handler = SPFM_Themes::get_instance();
$forms_handler = SPFM_Forms::get_instance();

$total_customers = $customers_handler->get_total();
$total_themes = $themes_handler->get_total();
$total_forms = $forms_handler->get_total();
$total_submissions = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}spfm_form_submissions");
$new_submissions = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}spfm_form_submissions WHERE status = 'new'");

// Recent submissions
$recent_submissions = $wpdb->get_results("
    SELECT s.*, f.name as form_name 
    FROM {$wpdb->prefix}spfm_form_submissions s
    LEFT JOIN {$wpdb->prefix}spfm_forms f ON s.form_id = f.id
    ORDER BY s.created_at DESC LIMIT 5
");

// Recent forms
$recent_forms = $forms_handler->get_all(array('per_page' => 5));
?>

<div class="wrap spfm-admin-wrap">
    <h1>SP Form Manager Dashboard</h1>
    
    <div class="spfm-dashboard">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-content">
                <h2>Welcome to SP Form Manager!</h2>
                <p>Create and share beautiful forms with your customers. Track submissions and customize themes easily.</p>
                <div class="welcome-actions">
                    <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="button button-primary">
                        <span class="dashicons dashicons-plus-alt2"></span> Create New Form
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="button">
                        <span class="dashicons dashicons-art"></span> Browse Themes
                    </a>
                </div>
            </div>
            <div class="welcome-illustration">
                <span class="dashicons dashicons-forms"></span>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="spfm-dashboard-cards">
            <div class="spfm-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <span class="dashicons dashicons-groups"></span>
                </div>
                <div class="card-content">
                    <h3><?php echo $total_customers; ?></h3>
                    <p>Customers</p>
                </div>
                <a href="<?php echo admin_url('admin.php?page=spfm-customers'); ?>" class="card-link">View All →</a>
            </div>
            
            <div class="spfm-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <span class="dashicons dashicons-art"></span>
                </div>
                <div class="card-content">
                    <h3><?php echo $total_themes; ?></h3>
                    <p>Themes</p>
                </div>
                <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="card-link">View All →</a>
            </div>
            
            <div class="spfm-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                    <span class="dashicons dashicons-feedback"></span>
                </div>
                <div class="card-content">
                    <h3><?php echo $total_forms; ?></h3>
                    <p>Forms</p>
                </div>
                <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="card-link">View All →</a>
            </div>
            
            <div class="spfm-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <span class="dashicons dashicons-format-aside"></span>
                </div>
                <div class="card-content">
                    <h3><?php echo $total_submissions; ?></h3>
                    <p>Submissions</p>
                    <?php if ($new_submissions > 0): ?>
                        <span class="new-badge"><?php echo $new_submissions; ?> new</span>
                    <?php endif; ?>
                </div>
                <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="card-link">View All →</a>
            </div>
        </div>
        
        <!-- Two Column Layout -->
        <div class="dashboard-columns">
            <!-- Recent Submissions -->
            <div class="dashboard-column">
                <div class="dashboard-widget">
                    <div class="widget-header">
                        <h3><span class="dashicons dashicons-format-aside"></span> Recent Submissions</h3>
                        <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="view-all">View All</a>
                    </div>
                    <div class="widget-body">
                        <?php if (empty($recent_submissions)): ?>
                            <p class="no-items">No submissions yet.</p>
                        <?php else: ?>
                            <ul class="submission-list">
                                <?php foreach ($recent_submissions as $s): ?>
                                    <?php 
                                    $data = json_decode($s->submission_data, true);
                                    $preview = '';
                                    if ($data) {
                                        $first = reset($data);
                                        $preview = is_array($first['value']) ? implode(', ', $first['value']) : substr($first['value'], 0, 40);
                                    }
                                    ?>
                                    <li>
                                        <a href="<?php echo admin_url('admin.php?page=spfm-submissions&action=view&id=' . $s->id); ?>">
                                            <div class="submission-info">
                                                <strong><?php echo esc_html($s->form_name); ?></strong>
                                                <span class="preview"><?php echo esc_html($preview); ?><?php echo strlen($preview) >= 40 ? '...' : ''; ?></span>
                                            </div>
                                            <span class="submission-time"><?php echo human_time_diff(strtotime($s->created_at), current_time('timestamp')); ?> ago</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Recent Forms -->
            <div class="dashboard-column">
                <div class="dashboard-widget">
                    <div class="widget-header">
                        <h3><span class="dashicons dashicons-feedback"></span> Your Forms</h3>
                        <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="add-new">+ New</a>
                    </div>
                    <div class="widget-body">
                        <?php if (empty($recent_forms)): ?>
                            <p class="no-items">No forms created yet. <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>">Create your first form</a></p>
                        <?php else: ?>
                            <ul class="form-list">
                                <?php foreach ($recent_forms as $f): ?>
                                    <?php $field_count = count($forms_handler->get_fields($f->id)); ?>
                                    <li>
                                        <div class="form-info">
                                            <strong><?php echo esc_html($f->name); ?></strong>
                                            <span class="meta">
                                                <?php echo $field_count; ?> fields
                                                • 
                                                <?php echo $f->status ? '<span class="status-active">Active</span>' : '<span class="status-inactive">Inactive</span>'; ?>
                                            </span>
                                        </div>
                                        <div class="form-actions">
                                            <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=share&id=' . $f->id); ?>" class="button button-small button-primary">Share</a>
                                            <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=edit&id=' . $f->id); ?>" class="button button-small">Edit</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="dashboard-widget">
                    <div class="widget-header">
                        <h3><span class="dashicons dashicons-performance"></span> Quick Actions</h3>
                    </div>
                    <div class="widget-body">
                        <div class="quick-actions">
                            <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=add'); ?>" class="quick-action">
                                <span class="dashicons dashicons-plus-alt"></span>
                                Add Customer
                            </a>
                            <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="quick-action">
                                <span class="dashicons dashicons-plus-alt"></span>
                                Create Form
                            </a>
                            <a href="<?php echo admin_url('admin.php?page=spfm-settings'); ?>" class="quick-action">
                                <span class="dashicons dashicons-admin-settings"></span>
                                Settings
                            </a>
                            <a href="<?php echo home_url('/spfm-login/'); ?>" target="_blank" class="quick-action">
                                <span class="dashicons dashicons-external"></span>
                                Frontend Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.spfm-dashboard {
    margin-top: 20px;
}
.welcome-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 15px;
    padding: 35px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
.welcome-content h2 {
    color: #fff;
    margin: 0 0 10px 0;
    font-size: 26px;
}
.welcome-content p {
    margin: 0 0 20px 0;
    opacity: 0.9;
    font-size: 15px;
}
.welcome-actions {
    display: flex;
    gap: 10px;
}
.welcome-actions .button {
    display: flex;
    align-items: center;
    gap: 5px;
}
.welcome-actions .button-primary {
    background: #fff;
    color: #667eea;
    border: none;
}
.welcome-actions .button:not(.button-primary) {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
}
.welcome-illustration .dashicons {
    font-size: 100px;
    width: 100px;
    height: 100px;
    opacity: 0.3;
}
.spfm-dashboard-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}
.spfm-card {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}
.spfm-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.card-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}
.card-icon .dashicons {
    color: #fff;
    font-size: 26px;
    width: 26px;
    height: 26px;
}
.card-content h3 {
    margin: 0;
    font-size: 32px;
    color: #333;
}
.card-content p {
    margin: 5px 0 0;
    color: #666;
}
.new-badge {
    display: inline-block;
    background: #dc3545;
    color: #fff;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    margin-left: 5px;
}
.card-link {
    margin-top: auto;
    padding-top: 15px;
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
}
.card-link:hover {
    text-decoration: underline;
}
.dashboard-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}
.dashboard-widget {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}
.widget-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px;
    border-bottom: 1px solid #eee;
}
.widget-header h3 {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
}
.view-all, .add-new {
    color: #667eea;
    text-decoration: none;
    font-size: 13px;
}
.add-new {
    background: #667eea;
    color: #fff;
    padding: 5px 12px;
    border-radius: 5px;
}
.widget-body {
    padding: 20px;
}
.no-items {
    color: #999;
    text-align: center;
    padding: 20px;
}
.submission-list, .form-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
.submission-list li, .form-list li {
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}
.submission-list li:last-child, .form-list li:last-child {
    border-bottom: 0;
}
.submission-list a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-decoration: none;
    color: #333;
}
.submission-info strong {
    display: block;
    margin-bottom: 3px;
}
.submission-info .preview {
    color: #999;
    font-size: 13px;
}
.submission-time {
    color: #999;
    font-size: 12px;
}
.form-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.form-info strong {
    display: block;
    margin-bottom: 3px;
}
.form-info .meta {
    color: #999;
    font-size: 12px;
}
.status-active { color: #28a745; }
.status-inactive { color: #dc3545; }
.form-actions {
    display: flex;
    gap: 5px;
}
.quick-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.quick-action {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    transition: background 0.3s;
}
.quick-action:hover {
    background: #e9ecef;
}
.quick-action .dashicons {
    color: #667eea;
}
@media (max-width: 1200px) {
    .spfm-dashboard-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    .dashboard-columns {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .spfm-dashboard-cards {
        grid-template-columns: 1fr;
    }
    .welcome-banner {
        flex-direction: column;
        text-align: center;
    }
    .welcome-illustration {
        display: none;
    }
    .welcome-actions {
        flex-direction: column;
    }
}
</style>
