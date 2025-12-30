<?php
if (!defined('ABSPATH')) {
    exit;
}

$forms_handler = SPFM_Forms::get_instance();
$themes_handler = SPFM_Themes::get_instance();
$customers_handler = SPFM_Customers::get_instance();

// Get stats
$total_forms = count($forms_handler->get_all(array('per_page' => 1000)));
$total_templates = count($themes_handler->get_templates());
$total_customers = $customers_handler->get_total();
$submission_stats = $forms_handler->get_submission_stats();

// Get recent submissions
$recent_submissions = $forms_handler->get_submissions(array('per_page' => 5));
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-layout"></span> SP Form Manager
    </h1>
    
    <div class="dashboard-container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="welcome-content">
                <h2>Welcome to SP Form Manager</h2>
                <p>Create website order forms, share with customers, and let them choose their perfect template.</p>
                <div class="welcome-actions">
                    <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="button button-primary button-hero">
                        <span class="dashicons dashicons-plus-alt"></span> Create Order Form
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="button button-hero">
                        <span class="dashicons dashicons-admin-appearance"></span> View Templates
                    </a>
                </div>
            </div>
            <div class="welcome-illustration">
                <div class="illustration-mockup">
                    <div class="mockup-browser">
                        <div class="browser-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="browser-content">
                            <div class="content-header"></div>
                            <div class="content-body">
                                <div class="content-card"></div>
                                <div class="content-card"></div>
                                <div class="content-card"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <span class="dashicons dashicons-media-document"></span>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $total_forms; ?></span>
                    <span class="stat-label">Order Forms</span>
                </div>
                <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="stat-link">
                    View All <span class="dashicons dashicons-arrow-right-alt2"></span>
                </a>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <span class="dashicons dashicons-admin-appearance"></span>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $total_templates; ?></span>
                    <span class="stat-label">Website Templates</span>
                </div>
                <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="stat-link">
                    View All <span class="dashicons dashicons-arrow-right-alt2"></span>
                </a>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                    <span class="dashicons dashicons-text-page"></span>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $submission_stats['total']; ?></span>
                    <span class="stat-label">Total Submissions</span>
                </div>
                <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="stat-link">
                    View All <span class="dashicons dashicons-arrow-right-alt2"></span>
                </a>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                    <span class="dashicons dashicons-groups"></span>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $total_customers; ?></span>
                    <span class="stat-label">Customers</span>
                </div>
                <a href="<?php echo admin_url('admin.php?page=spfm-customers'); ?>" class="stat-link">
                    View All <span class="dashicons dashicons-arrow-right-alt2"></span>
                </a>
            </div>
        </div>
        
        <!-- Two Column Layout -->
        <div class="dashboard-columns">
            <!-- Recent Submissions -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-text-page"></span> Recent Submissions</h3>
                    <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="view-all">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_submissions)): ?>
                        <div class="empty-state-mini">
                            <span class="dashicons dashicons-text-page"></span>
                            <p>No submissions yet</p>
                        </div>
                    <?php else: ?>
                        <div class="submissions-list">
                            <?php foreach ($recent_submissions as $sub): 
                                $customer_info = json_decode($sub->customer_info, true) ?: array();
                            ?>
                                <div class="submission-item">
                                    <div class="submission-icon">
                                        <span class="template-colors">
                                            <span style="background: <?php echo esc_attr($sub->primary_color); ?>;"></span>
                                            <span style="background: <?php echo esc_attr($sub->secondary_color); ?>;"></span>
                                        </span>
                                    </div>
                                    <div class="submission-info">
                                        <strong><?php echo esc_html($customer_info['name'] ?? 'Unknown'); ?></strong>
                                        <small><?php echo esc_html($sub->theme_name); ?></small>
                                    </div>
                                    <div class="submission-meta">
                                        <span class="status-badge status-<?php echo $sub->status; ?>"><?php echo ucfirst($sub->status); ?></span>
                                        <small><?php echo human_time_diff(strtotime($sub->created_at), current_time('timestamp')); ?> ago</small>
                                    </div>
                                    <a href="<?php echo admin_url('admin.php?page=spfm-submissions&action=view&id=' . $sub->id); ?>" class="button button-small">
                                        View
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-admin-tools"></span> Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="action-btn">
                            <span class="action-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                <span class="dashicons dashicons-plus-alt"></span>
                            </span>
                            <span class="action-text">
                                <strong>New Order Form</strong>
                                <small>Create a new website order form</small>
                            </span>
                        </a>
                        
                        <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=add'); ?>" class="action-btn">
                            <span class="action-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                                <span class="dashicons dashicons-admin-users"></span>
                            </span>
                            <span class="action-text">
                                <strong>Add Customer</strong>
                                <small>Register a new customer</small>
                            </span>
                        </a>
                        
                        <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="action-btn">
                            <span class="action-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                <span class="dashicons dashicons-admin-appearance"></span>
                            </span>
                            <span class="action-text">
                                <strong>Browse Templates</strong>
                                <small>View available website templates</small>
                            </span>
                        </a>
                        
                        <a href="<?php echo admin_url('admin.php?page=spfm-settings'); ?>" class="action-btn">
                            <span class="action-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                                <span class="dashicons dashicons-admin-settings"></span>
                            </span>
                            <span class="action-text">
                                <strong>Settings</strong>
                                <small>Configure email & notifications</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- How It Works -->
        <div class="how-it-works">
            <h3><span class="dashicons dashicons-info"></span> How It Works</h3>
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h4>Create Order Form</h4>
                    <p>Select which website templates customers can choose from</p>
                </div>
                <div class="step-arrow">→</div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <h4>Share with Customer</h4>
                    <p>Send the form link via email, WhatsApp, or copy the link</p>
                </div>
                <div class="step-arrow">→</div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <h4>Customer Fills Content</h4>
                    <p>Customer selects template, customizes colors, fills page content</p>
                </div>
                <div class="step-arrow">→</div>
                <div class="step-item">
                    <div class="step-number">4</div>
                    <h4>Review Submission</h4>
                    <p>View complete submission with preview and customer data</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    margin-top: 20px;
}

/* Welcome Card */
.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 40px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    overflow: hidden;
}
.welcome-content h2 {
    margin: 0 0 10px 0;
    font-size: 28px;
    color: #fff;
}
.welcome-content p {
    margin: 0 0 25px 0;
    opacity: 0.9;
    font-size: 16px;
    max-width: 500px;
}
.welcome-actions {
    display: flex;
    gap: 15px;
}
.welcome-actions .button {
    display: flex;
    align-items: center;
    gap: 8px;
}
.welcome-actions .button-primary {
    background: #fff;
    color: #667eea;
    border-color: #fff;
}
.welcome-actions .button:not(.button-primary) {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.3);
    color: #fff;
}
.welcome-illustration {
    flex-shrink: 0;
}
.illustration-mockup {
    transform: rotate(-5deg);
}
.mockup-browser {
    width: 300px;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
}
.browser-dots {
    background: #f0f0f0;
    padding: 10px 15px;
    display: flex;
    gap: 6px;
}
.browser-dots span {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #ddd;
}
.browser-content {
    padding: 15px;
}
.content-header {
    height: 30px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 6px;
    margin-bottom: 15px;
}
.content-body {
    display: flex;
    gap: 10px;
}
.content-card {
    flex: 1;
    height: 50px;
    background: #e9ecef;
    border-radius: 6px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    position: relative;
}
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}
.stat-icon .dashicons {
    color: #fff;
    font-size: 24px;
    width: 24px;
    height: 24px;
}
.stat-value {
    display: block;
    font-size: 32px;
    font-weight: 700;
    color: #333;
}
.stat-label {
    font-size: 14px;
    color: #666;
}
.stat-link {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: #667eea;
    text-decoration: none;
    margin-top: 15px;
}

/* Dashboard Columns */
.dashboard-columns {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 25px;
    margin-bottom: 30px;
}
.dashboard-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}
.card-header h3 {
    margin: 0;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.card-header .dashicons {
    color: #667eea;
}
.view-all {
    font-size: 13px;
    color: #667eea;
    text-decoration: none;
}
.card-body {
    padding: 20px;
}

/* Submissions List */
.submissions-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.submission-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}
.template-colors {
    display: flex;
    gap: 3px;
}
.template-colors span {
    width: 15px;
    height: 30px;
    border-radius: 4px;
}
.submission-info {
    flex: 1;
}
.submission-info strong {
    display: block;
}
.submission-info small {
    color: #666;
}
.submission-meta {
    text-align: right;
}
.submission-meta small {
    display: block;
    color: #999;
    margin-top: 5px;
}
.status-badge {
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 15px;
}
.status-new { background: #cce5ff; color: #004085; }
.status-in_progress { background: #fff3cd; color: #856404; }
.status-completed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }

/* Quick Actions */
.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.action-btn {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
}
.action-btn:hover {
    background: #f0f0f0;
    transform: translateX(5px);
}
.action-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.action-icon .dashicons {
    color: #fff;
    font-size: 20px;
    width: 20px;
    height: 20px;
}
.action-text strong {
    display: block;
    margin-bottom: 2px;
}
.action-text small {
    color: #666;
}

/* Empty State */
.empty-state-mini {
    text-align: center;
    padding: 30px;
    color: #999;
}
.empty-state-mini .dashicons {
    font-size: 40px;
    width: 40px;
    height: 40px;
    margin-bottom: 10px;
}

/* How It Works */
.how-it-works {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}
.how-it-works h3 {
    margin: 0 0 25px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.how-it-works h3 .dashicons {
    color: #667eea;
}
.steps-grid {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.step-item {
    flex: 1;
    text-align: center;
    padding: 0 20px;
}
.step-number {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 700;
    margin: 0 auto 15px;
}
.step-item h4 {
    margin: 0 0 8px 0;
    font-size: 16px;
}
.step-item p {
    margin: 0;
    font-size: 13px;
    color: #666;
}
.step-arrow {
    font-size: 24px;
    color: #ddd;
    flex-shrink: 0;
}

@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .dashboard-columns {
        grid-template-columns: 1fr;
    }
    .welcome-card {
        flex-direction: column;
        text-align: center;
    }
    .welcome-illustration {
        display: none;
    }
    .steps-grid {
        flex-wrap: wrap;
        gap: 20px;
    }
    .step-arrow {
        display: none;
    }
    .step-item {
        flex-basis: calc(50% - 20px);
    }
}
</style>
