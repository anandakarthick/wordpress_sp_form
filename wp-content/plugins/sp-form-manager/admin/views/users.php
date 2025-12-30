<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table = $wpdb->prefix . 'spfm_users';

$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$user = null;
if ($action === 'edit' && $id) {
    $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
}

$users = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC");

// Get stats
$total_users = count($users);
$admin_count = 0;
$active_count = 0;
foreach ($users as $u) {
    if ($u->role === 'admin') $admin_count++;
    if ($u->status == 1) $active_count++;
}
?>

<div class="spfm-users-wrap">
    <?php if ($action === 'list'): ?>
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <h1><span class="dashicons dashicons-admin-users"></span> Users</h1>
                <p>Manage users who can access the SP Form Manager admin panel</p>
            </div>
            <div class="header-actions">
                <a href="<?php echo admin_url('admin.php?page=spfm-users&action=add'); ?>" class="btn btn-white">
                    <span class="dashicons dashicons-plus-alt"></span> Add New User
                </a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <span class="dashicons dashicons-admin-users"></span>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $total_users; ?></div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-detail">All registered users</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon admins">
                    <span class="dashicons dashicons-shield-alt"></span>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $admin_count; ?></div>
                    <div class="stat-label">Administrators</div>
                    <div class="stat-detail">Full access users</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active">
                    <span class="dashicons dashicons-yes-alt"></span>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $active_count; ?></div>
                    <div class="stat-label">Active Users</div>
                    <div class="stat-detail">Can login to system</div>
                </div>
            </div>
        </div>
        
        <!-- Users List -->
        <?php if (empty($users)): ?>
            <div class="empty-state-card">
                <div class="empty-icon">
                    <span class="dashicons dashicons-admin-users"></span>
                </div>
                <h3>No Users Yet</h3>
                <p>Add your first user to get started.</p>
                <a href="<?php echo admin_url('admin.php?page=spfm-users&action=add'); ?>" class="btn btn-primary">
                    <span class="dashicons dashicons-plus-alt"></span> Add New User
                </a>
            </div>
        <?php else: ?>
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-groups"></span> All Users</h3>
                    <span class="badge"><?php echo $total_users; ?> users</span>
                </div>
                <div class="card-body no-padding">
                    <div class="users-list">
                        <?php foreach ($users as $u): ?>
                            <div class="user-item">
                                <div class="user-avatar <?php echo $u->role; ?>">
                                    <?php echo strtoupper(substr($u->full_name, 0, 1)); ?>
                                </div>
                                <div class="user-main">
                                    <div class="user-info">
                                        <h4><?php echo esc_html($u->full_name); ?></h4>
                                        <div class="user-meta">
                                            <span><span class="dashicons dashicons-admin-users"></span> @<?php echo esc_html($u->username); ?></span>
                                            <span><span class="dashicons dashicons-email"></span> <?php echo esc_html($u->email); ?></span>
                                            <span><span class="dashicons dashicons-calendar"></span> Joined <?php echo date('M j, Y', strtotime($u->created_at)); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="user-badges">
                                    <span class="role-badge role-<?php echo $u->role; ?>">
                                        <?php if ($u->role === 'admin'): ?>
                                            <span class="dashicons dashicons-shield-alt"></span>
                                        <?php else: ?>
                                            <span class="dashicons dashicons-admin-users"></span>
                                        <?php endif; ?>
                                        <?php echo ucfirst($u->role); ?>
                                    </span>
                                    <span class="status-badge <?php echo $u->status ? 'active' : 'inactive'; ?>">
                                        <?php echo $u->status ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                <div class="user-actions">
                                    <a href="<?php echo admin_url('admin.php?page=spfm-users&action=edit&id=' . $u->id); ?>" class="btn btn-outline btn-sm">
                                        <span class="dashicons dashicons-edit"></span> Edit
                                    </a>
                                    <?php if ($u->id !== 1): ?>
                                        <button class="btn btn-danger btn-sm delete-user" data-id="<?php echo $u->id; ?>">
                                            <span class="dashicons dashicons-trash"></span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Add/Edit User -->
        <div class="page-header">
            <div class="header-content">
                <a href="<?php echo admin_url('admin.php?page=spfm-users'); ?>" class="back-link">
                    <span class="dashicons dashicons-arrow-left-alt"></span> Back to Users
                </a>
                <h1><span class="dashicons dashicons-admin-users"></span> <?php echo $user ? 'Edit User' : 'Add New User'; ?></h1>
                <p><?php echo $user ? 'Update user information and permissions' : 'Create a new user account'; ?></p>
            </div>
        </div>
        
        <div class="user-editor-container">
            <form id="user-form">
                <input type="hidden" name="id" value="<?php echo $user ? $user->id : ''; ?>">
                
                <div class="editor-main">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><span class="dashicons dashicons-id"></span> User Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="full_name">Full Name <span class="required">*</span></label>
                                    <input type="text" name="full_name" id="full_name" required 
                                           value="<?php echo $user ? esc_attr($user->full_name) : ''; ?>"
                                           placeholder="Enter full name">
                                </div>
                                <div class="form-field">
                                    <label for="email">Email Address <span class="required">*</span></label>
                                    <input type="email" name="email" id="email" required 
                                           value="<?php echo $user ? esc_attr($user->email) : ''; ?>"
                                           placeholder="Enter email address">
                                </div>
                            </div>
                            
                            <?php if (!$user): ?>
                                <div class="form-field">
                                    <label for="username">Username <span class="required">*</span></label>
                                    <input type="text" name="username" id="username" required 
                                           pattern="[a-zA-Z0-9_]+" 
                                           title="Only letters, numbers, and underscores"
                                           placeholder="Enter username">
                                    <p class="field-hint">Only letters, numbers, and underscores. Cannot be changed after creation.</p>
                                </div>
                            <?php else: ?>
                                <div class="form-field">
                                    <label>Username</label>
                                    <input type="text" value="<?php echo esc_attr($user->username); ?>" disabled class="disabled-input">
                                    <p class="field-hint">Username cannot be changed.</p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="password"><?php echo $user ? 'New Password' : 'Password'; ?> <?php echo !$user ? '<span class="required">*</span>' : ''; ?></label>
                                    <input type="password" name="password" id="password" 
                                           <?php echo !$user ? 'required' : ''; ?>
                                           placeholder="<?php echo $user ? 'Leave blank to keep current' : 'Enter password'; ?>">
                                    <?php if ($user): ?>
                                        <p class="field-hint">Leave blank to keep current password.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="form-field">
                                    <label for="role">Role</label>
                                    <select name="role" id="role">
                                        <option value="admin" <?php selected($user ? $user->role : '', 'admin'); ?>>Administrator</option>
                                        <option value="user" <?php selected($user ? $user->role : '', 'user'); ?>>User</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="editor-sidebar">
                    <div class="sidebar-card">
                        <h4>Status</h4>
                        <div class="status-toggle">
                            <label class="toggle-switch">
                                <input type="checkbox" name="status" value="1" 
                                       <?php checked($user ? $user->status : 1, 1); ?>>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Active User</span>
                        </div>
                        <p class="status-hint">Inactive users cannot log in to the system.</p>
                    </div>
                    
                    <div class="sidebar-card">
                        <h4>Actions</h4>
                        <button type="submit" class="btn btn-primary btn-block">
                            <span class="dashicons dashicons-saved"></span>
                            <?php echo $user ? 'Update User' : 'Create User'; ?>
                        </button>
                        <a href="<?php echo admin_url('admin.php?page=spfm-users'); ?>" class="btn btn-outline btn-block">
                            Cancel
                        </a>
                    </div>
                    
                    <?php if ($user): ?>
                        <div class="sidebar-card">
                            <h4>User Details</h4>
                            <div class="detail-list">
                                <div class="detail-item">
                                    <label>Created</label>
                                    <span><?php echo date('M j, Y g:i A', strtotime($user->created_at)); ?></span>
                                </div>
                                <?php if (!empty($user->last_login)): ?>
                                    <div class="detail-item">
                                        <label>Last Login</label>
                                        <span><?php echo date('M j, Y g:i A', strtotime($user->last_login)); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-users-wrap {
    padding: 20px;
    max-width: 1400px;
    font-family: 'Inter', -apple-system, sans-serif;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
.btn-primary { background: #8b5cf6; color: #fff; }
.btn-primary:hover { background: #7c3aed; color: #fff; }
.btn-white { background: #fff; color: #8b5cf6; }
.btn-white:hover { background: #f5f3ff; transform: translateY(-2px); }
.btn-outline { background: transparent; color: #8b5cf6; border: 2px solid #8b5cf6; }
.btn-outline:hover { background: #8b5cf6; color: #fff; }
.btn-danger { background: #fee2e2; color: #dc2626; }
.btn-danger:hover { background: #dc2626; color: #fff; }
.btn-sm { padding: 8px 16px; font-size: 13px; }
.btn-block { width: 100%; justify-content: center; margin-bottom: 10px; }
.btn-block:last-child { margin-bottom: 0; }
.btn .dashicons { font-size: 16px; width: 16px; height: 16px; }

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
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
.stat-icon.total { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.stat-icon.admins { background: linear-gradient(135deg, #f59e0b, #d97706); }
.stat-icon.active { background: linear-gradient(135deg, #10b981, #059669); }
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
.card-header .dashicons { color: #8b5cf6; }
.card-header .badge {
    background: #f3e8ff;
    color: #7c3aed;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.card-body { padding: 25px; }
.card-body.no-padding { padding: 0; }

/* Users List */
.users-list {
    display: flex;
    flex-direction: column;
}
.user-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 25px;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.2s;
}
.user-item:last-child { border-bottom: none; }
.user-item:hover { background: #f8fafc; }
.user-avatar {
    width: 55px;
    height: 55px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 22px;
    flex-shrink: 0;
}
.user-avatar.admin { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.user-avatar.user { background: linear-gradient(135deg, #64748b, #475569); }
.user-main {
    flex: 1;
}
.user-info h4 {
    margin: 0 0 8px 0;
    font-size: 16px;
    color: #1e293b;
}
.user-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.user-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    color: #64748b;
}
.user-meta .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
    color: #94a3b8;
}
.user-badges {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
}
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.role-badge .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
}
.role-admin {
    background: #f3e8ff;
    color: #7c3aed;
}
.role-user {
    background: #f1f5f9;
    color: #64748b;
}
.status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-badge.active {
    background: #d1fae5;
    color: #059669;
}
.status-badge.inactive {
    background: #fee2e2;
    color: #dc2626;
}
.user-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

/* User Editor */
.user-editor-container form {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 25px;
}
.editor-main {
    display: flex;
    flex-direction: column;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-field {
    margin-bottom: 20px;
}
.form-field:last-child { margin-bottom: 0; }
.form-field label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
    color: #334155;
}
.form-field label .required {
    color: #dc2626;
}
.form-field input,
.form-field select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: border-color 0.2s;
}
.form-field input:focus,
.form-field select:focus {
    border-color: #8b5cf6;
    outline: none;
}
.form-field input.disabled-input {
    background: #f8fafc;
    color: #94a3b8;
}
.field-hint {
    margin: 8px 0 0 0;
    color: #94a3b8;
    font-size: 13px;
}

/* Sidebar */
.editor-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.sidebar-card {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}
.sidebar-card h4 {
    margin: 0 0 20px 0;
    font-size: 14px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Toggle Switch */
.status-toggle {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 28px;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: #e2e8f0;
    transition: 0.3s;
    border-radius: 28px;
}
.toggle-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.toggle-switch input:checked + .toggle-slider {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}
.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(22px);
}
.toggle-label {
    font-weight: 600;
    color: #1e293b;
}
.status-hint {
    margin: 0;
    font-size: 13px;
    color: #94a3b8;
}

/* Detail List */
.detail-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.detail-item {
    padding: 12px;
    background: #f8fafc;
    border-radius: 10px;
}
.detail-item label {
    display: block;
    font-size: 11px;
    color: #94a3b8;
    text-transform: uppercase;
    margin-bottom: 5px;
}
.detail-item span {
    font-size: 13px;
    color: #1e293b;
    font-weight: 500;
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
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
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
    margin: 0 0 25px 0;
    color: #64748b;
    font-size: 15px;
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid { grid-template-columns: 1fr; }
    .user-editor-container form { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .page-header { flex-direction: column; text-align: center; gap: 20px; }
    .user-item { flex-direction: column; align-items: flex-start; gap: 15px; }
    .user-badges { width: 100%; }
    .user-actions { width: 100%; }
    .form-row { grid-template-columns: 1fr; }
}
</style>

<script>
var spfm_ajax = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('spfm_nonce'); ?>'
};

jQuery(document).ready(function($) {
    // Save user
    $('#user-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_user&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-users'); ?>';
            } else {
                alert(response.data.message);
                $btn.html('<span class="dashicons dashicons-saved"></span> Save User').prop('disabled', false);
            }
        });
    });
    
    // Delete user
    $('.delete-user').on('click', function() {
        if (!confirm('Are you sure you want to delete this user?')) return;
        
        var id = $(this).data('id');
        var $item = $(this).closest('.user-item');
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_user',
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
});
</script>
