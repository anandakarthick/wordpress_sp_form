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
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-admin-users"></span> Users
    </h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-users&action=add'); ?>" class="page-title-action">Add New User</a>
        
        <p class="description">Manage users who can access the SP Form Manager admin panel.</p>
        
        <div class="users-table-container">
            <table class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($u->full_name, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <strong><?php echo esc_html($u->full_name); ?></strong>
                                        <br><small>@<?php echo esc_html($u->username); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo esc_html($u->email); ?></td>
                            <td>
                                <span class="role-badge role-<?php echo $u->role; ?>">
                                    <?php echo ucfirst($u->role); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $u->status ? 'active' : 'inactive'; ?>">
                                    <?php echo $u->status ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($u->created_at)); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=spfm-users&action=edit&id=' . $u->id); ?>" class="button button-small">
                                    <span class="dashicons dashicons-edit"></span>
                                </a>
                                <?php if ($u->id !== 1): ?>
                                    <button class="button button-small button-link-delete delete-user" data-id="<?php echo $u->id; ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
    <?php else: ?>
        <!-- Add/Edit User -->
        <a href="<?php echo admin_url('admin.php?page=spfm-users'); ?>" class="page-title-action">← Back to Users</a>
        
        <div class="user-editor">
            <form id="user-form">
                <input type="hidden" name="id" value="<?php echo $user ? $user->id : ''; ?>">
                
                <div class="editor-section">
                    <h3>User Information</h3>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="full_name">Full Name *</label>
                            <input type="text" name="full_name" id="full_name" required 
                                   value="<?php echo $user ? esc_attr($user->full_name) : ''; ?>">
                        </div>
                        <div class="form-field">
                            <label for="email">Email Address *</label>
                            <input type="email" name="email" id="email" required 
                                   value="<?php echo $user ? esc_attr($user->email) : ''; ?>">
                        </div>
                    </div>
                    
                    <?php if (!$user): ?>
                        <div class="form-field">
                            <label for="username">Username *</label>
                            <input type="text" name="username" id="username" required 
                                   pattern="[a-zA-Z0-9_]+" title="Only letters, numbers, and underscores">
                            <p class="field-hint">Cannot be changed after creation</p>
                        </div>
                    <?php else: ?>
                        <div class="form-field">
                            <label>Username</label>
                            <input type="text" value="<?php echo esc_attr($user->username); ?>" disabled>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="password"><?php echo $user ? 'New Password' : 'Password *'; ?></label>
                            <input type="password" name="password" id="password" 
                                   <?php echo !$user ? 'required' : ''; ?>>
                            <?php if ($user): ?>
                                <p class="field-hint">Leave blank to keep current password</p>
                            <?php endif; ?>
                        </div>
                        <div class="form-field">
                            <label for="role">Role</label>
                            <select name="role" id="role">
                                <option value="admin" <?php selected($user ? $user->role : '', 'admin'); ?>>Admin</option>
                                <option value="user" <?php selected($user ? $user->role : '', 'user'); ?>>User</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-field checkbox-field">
                        <label>
                            <input type="checkbox" name="status" value="1" 
                                   <?php checked($user ? $user->status : 1, 1); ?>>
                            Active user
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="button button-primary button-large">
                        <?php echo $user ? 'Update User' : 'Create User'; ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=spfm-users'); ?>" class="button button-large">Cancel</a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
/* Users Table */
.users-table-container {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-top: 20px;
}
.users-table-container th {
    background: #f8f9fa;
    padding: 15px;
}
.users-table-container td {
    padding: 15px;
    vertical-align: middle;
}
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
}
.role-badge {
    display: inline-block;
    padding: 3px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 500;
}
.role-admin {
    background: #e7f3ff;
    color: #0073aa;
}
.role-user {
    background: #e9ecef;
    color: #495057;
}
.status-badge {
    display: inline-block;
    padding: 3px 12px;
    border-radius: 15px;
    font-size: 12px;
}
.status-badge.active {
    background: #d4edda;
    color: #155724;
}
.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}

/* User Editor */
.user-editor {
    max-width: 700px;
    margin-top: 20px;
}
.editor-section {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.editor-section h3 {
    margin: 0 0 20px 0;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-field {
    margin-bottom: 20px;
}
.form-field label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}
.form-field input,
.form-field select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.form-field input:focus,
.form-field select:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
}
.form-field input:disabled {
    background: #f0f0f0;
}
.field-hint {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
}
.checkbox-field label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}
.checkbox-field input {
    width: 18px;
    height: 18px;
}
.form-actions {
    display: flex;
    gap: 10px;
}

@media (max-width: 600px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
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
                $btn.text('Save User').prop('disabled', false);
            }
        });
    });
    
    // Delete user
    $('.delete-user').on('click', function() {
        if (!confirm('Are you sure you want to delete this user?')) return;
        
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_user',
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
});
</script>
