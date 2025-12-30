<?php
/**
 * Admin Dashboard View
 * Hospital Website Template System Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get statistics
global $wpdb;

$themes_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}spfm_themes WHERE status = 1");
$templates_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}spfm_themes WHERE is_template = 1 AND status = 1");
$custom_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}spfm_themes WHERE is_template = 0 AND status = 1");
$forms_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}spfm_forms WHERE status = 1");
$customers_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}spfm_customers WHERE status = 1");
$submissions_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}spfm_form_submissions");
$new_submissions = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}spfm_form_submissions WHERE status = 'new'");

// Recent submissions
$recent_submissions = $wpdb->get_results("
    SELECT s.*, f.name as form_name, t.name as theme_name 
    FROM {$wpdb->prefix}spfm_form_submissions s 
    LEFT JOIN {$wpdb->prefix}spfm_forms f ON s.form_id = f.id 
    LEFT JOIN {$wpdb->prefix}spfm_themes t ON s.selected_theme_id = t.id 
    ORDER BY s.created_at DESC 
    LIMIT 5
");
?>

<div class="spfm-dashboard">
    <!-- Welcome Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1>🏥 Hospital Website Template System</h1>
            <p>Create, customize, and share professional hospital website templates with your clients</p>
        </div>
        <div class="header-actions">
            <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=new'); ?>" class="btn btn-white">
                <span class="dashicons dashicons-plus-alt"></span> New Order Form
            </a>
            <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="btn btn-outline">
                <span class="dashicons dashicons-layout"></span> View Templates
            </a>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-templates">
            <div class="stat-icon">
                <span class="dashicons dashicons-layout"></span>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo intval($themes_count); ?></div>
                <div class="stat-label">Total Templates</div>
                <div class="stat-detail"><?php echo $templates_count; ?> pre-built, <?php echo $custom_count; ?> custom</div>
            </div>
        </div>
        
        <div class="stat-card stat-forms">
            <div class="stat-icon">
                <span class="dashicons dashicons-feedback"></span>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo intval($forms_count); ?></div>
                <div class="stat-label">Active Forms</div>
                <div class="stat-detail">Order forms ready to share</div>
            </div>
        </div>
        
        <div class="stat-card stat-submissions">
            <div class="stat-icon">
                <span class="dashicons dashicons-portfolio"></span>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo intval($submissions_count); ?></div>
                <div class="stat-label">Total Orders</div>
                <?php if ($new_submissions > 0): ?>
                    <div class="stat-detail stat-highlight"><?php echo $new_submissions; ?> new orders pending</div>
                <?php else: ?>
                    <div class="stat-detail">Website orders received</div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="stat-card stat-customers">
            <div class="stat-icon">
                <span class="dashicons dashicons-groups"></span>
            </div>
            <div class="stat-content">
                <div class="stat-number"><?php echo intval($customers_count); ?></div>
                <div class="stat-label">Customers</div>
                <div class="stat-detail">Registered clients</div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions + Recent Activity -->
    <div class="dashboard-grid">
        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><span class="dashicons dashicons-admin-tools"></span> Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="<?php echo admin_url('admin.php?page=spfm-themes'); ?>" class="action-btn">
                        <div class="action-icon" style="background: linear-gradient(135deg, #0891b2, #0e7490);">
                            <span class="dashicons dashicons-layout"></span>
                        </div>
                        <div class="action-text">
                            <strong>Hospital Templates</strong>
                            <span>Browse & customize templates</span>
                        </div>
                    </a>
                    
                    <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=new'); ?>" class="action-btn">
                        <div class="action-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <span class="dashicons dashicons-plus-alt"></span>
                        </div>
                        <div class="action-text">
                            <strong>Create Order Form</strong>
                            <span>Set up new form for clients</span>
                        </div>
                    </a>
                    
                    <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=new'); ?>" class="action-btn">
                        <div class="action-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                            <span class="dashicons dashicons-businessman"></span>
                        </div>
                        <div class="action-text">
                            <strong>Add Customer</strong>
                            <span>Register new client</span>
                        </div>
                    </a>
                    
                    <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="action-btn">
                        <div class="action-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <span class="dashicons dashicons-clipboard"></span>
                        </div>
                        <div class="action-text">
                            <strong>View Submissions</strong>
                            <span>Review website orders</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Recent Submissions -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><span class="dashicons dashicons-clock"></span> Recent Website Orders</h3>
                <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="view-all">View All →</a>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_submissions)): ?>
                    <div class="submissions-list">
                        <?php foreach ($recent_submissions as $sub): 
                            $customer_info = json_decode($sub->customer_info, true);
                            $status_class = 'status-' . $sub->status;
                        ?>
                            <div class="submission-item">
                                <div class="submission-avatar">
                                    <?php echo strtoupper(substr($customer_info['name'] ?? 'U', 0, 1)); ?>
                                </div>
                                <div class="submission-info">
                                    <strong><?php echo esc_html($customer_info['name'] ?? 'Unknown'); ?></strong>
                                    <span class="submission-meta">
                                        <?php echo esc_html($sub->theme_name); ?> • 
                                        <?php echo human_time_diff(strtotime($sub->created_at)); ?> ago
                                    </span>
                                </div>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo ucfirst($sub->status); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <span class="dashicons dashicons-portfolio"></span>
                        <p>No website orders yet</p>
                        <small>Orders will appear here when customers submit</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Template Categories -->
    <div class="dashboard-card full-width">
        <div class="card-header">
            <h3><span class="dashicons dashicons-category"></span> Available Template Categories</h3>
        </div>
        <div class="card-body">
            <div class="categories-grid">
                <div class="category-item">
                    <div class="category-icon" style="background: linear-gradient(135deg, #0891b2, #0e7490);">🏥</div>
                    <h4>General Hospital</h4>
                    <p>Complete hospital websites with departments, doctors & appointments</p>
                </div>
                <div class="category-item">
                    <div class="category-icon" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">🦷</div>
                    <h4>Dental Clinic</h4>
                    <p>Modern dental clinic with smile gallery & treatment info</p>
                </div>
                <div class="category-item">
                    <div class="category-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">👁️</div>
                    <h4>Eye Care Center</h4>
                    <p>Eye care & vision center with LASIK info & optical shop</p>
                </div>
                <div class="category-item">
                    <div class="category-icon" style="background: linear-gradient(135deg, #f97316, #ea580c);">👶</div>
                    <h4>Children's Hospital</h4>
                    <p>Child-friendly pediatric hospital design</p>
                </div>
                <div class="category-item">
                    <div class="category-icon" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">❤️</div>
                    <h4>Cardiology Center</h4>
                    <p>Specialized heart & cardiovascular care</p>
                </div>
                <div class="category-item">
                    <div class="category-icon" style="background: linear-gradient(135deg, #10b981, #059669);">🧠</div>
                    <h4>Mental Health</h4>
                    <p>Calm & supportive mental wellness clinic</p>
                </div>
                <div class="category-item">
                    <div class="category-icon" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">🦴</div>
                    <h4>Orthopedic Center</h4>
                    <p>Bone, joint & spine care specialists</p>
                </div>
                <div class="category-item">
                    <div class="category-icon" style="background: linear-gradient(135deg, #0d9488, #0f766e);">🔬</div>
                    <h4>Diagnostic Lab</h4>
                    <p>Medical testing & pathology services</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.spfm-dashboard {
    padding: 20px;
    max-width: 1400px;
}

/* Header */
.dashboard-header {
    background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
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
    font-size: 32px;
    font-weight: 700;
}
.header-content p {
    margin: 0;
    opacity: 0.9;
    font-size: 16px;
}
.header-actions {
    display: flex;
    gap: 12px;
}
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 14px;
}
.btn-white {
    background: #fff;
    color: #0891b2;
}
.btn-white:hover {
    background: #f0fdfa;
    color: #0891b2;
    transform: translateY(-2px);
}
.btn-outline {
    background: transparent;
    color: #fff;
    border: 2px solid rgba(255,255,255,0.5);
}
.btn-outline:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border-color: #fff;
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
.stat-icon .dashicons {
    font-size: 28px;
    width: 28px;
    height: 28px;
    color: #fff;
}
.stat-templates .stat-icon { background: linear-gradient(135deg, #0891b2, #0e7490); }
.stat-forms .stat-icon { background: linear-gradient(135deg, #10b981, #059669); }
.stat-submissions .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.stat-customers .stat-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.stat-content { flex: 1; }
.stat-number {
    font-size: 36px;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 5px;
}
.stat-label {
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 5px;
}
.stat-detail {
    font-size: 12px;
    color: #94a3b8;
}
.stat-highlight {
    color: #f59e0b;
    font-weight: 600;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 25px;
}
.dashboard-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}
.dashboard-card.full-width {
    grid-column: 1 / -1;
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
.card-header .dashicons {
    color: #0891b2;
}
.view-all {
    font-size: 13px;
    color: #0891b2;
    text-decoration: none;
    font-weight: 600;
}
.card-body {
    padding: 25px;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}
.action-btn {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 18px;
    background: #f8fafc;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s;
}
.action-btn:hover {
    background: #f1f5f9;
    transform: translateX(5px);
}
.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.action-icon .dashicons {
    color: #fff;
    font-size: 22px;
    width: 22px;
    height: 22px;
}
.action-text strong {
    display: block;
    color: #1e293b;
    margin-bottom: 3px;
    font-size: 14px;
}
.action-text span {
    font-size: 12px;
    color: #64748b;
}

/* Submissions List */
.submissions-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.submission-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 12px;
}
.submission-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #0891b2, #0e7490);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 18px;
    flex-shrink: 0;
}
.submission-info {
    flex: 1;
}
.submission-info strong {
    display: block;
    color: #1e293b;
    font-size: 14px;
}
.submission-meta {
    font-size: 12px;
    color: #64748b;
}
.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.status-new { background: #fef3c7; color: #d97706; }
.status-in_progress { background: #dbeafe; color: #2563eb; }
.status-completed { background: #dcfce7; color: #16a34a; }
.status-cancelled { background: #fee2e2; color: #dc2626; }

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px;
    color: #94a3b8;
}
.empty-state .dashicons {
    font-size: 48px;
    width: 48px;
    height: 48px;
    margin-bottom: 15px;
    color: #cbd5e1;
}
.empty-state p {
    margin: 0 0 5px 0;
    font-weight: 600;
    color: #64748b;
}

/* Categories Grid */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
.category-item {
    background: #f8fafc;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    transition: all 0.3s;
}
.category-item:hover {
    background: #f1f5f9;
    transform: translateY(-3px);
}
.category-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 28px;
}
.category-item h4 {
    margin: 0 0 8px 0;
    font-size: 15px;
    color: #1e293b;
}
.category-item p {
    margin: 0;
    font-size: 12px;
    color: #64748b;
    line-height: 1.5;
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .categories-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        text-align: center;
        gap: 25px;
    }
    .stats-grid { grid-template-columns: 1fr; }
    .dashboard-grid { grid-template-columns: 1fr; }
    .quick-actions { grid-template-columns: 1fr; }
    .categories-grid { grid-template-columns: 1fr; }
}
</style>
