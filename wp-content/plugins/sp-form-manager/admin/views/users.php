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
    <h1 class="wp-heading-inline">Users</h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-users&action=add'); ?>" class="page-title-action">Add New User</a>
        
        <div class="spfm-users-container">
            <?php if (empty($users)): ?>
                <div class="empty-state">
                    <span class="dashicons dashicons-admin-users"></span>
                    <p>No users yet.</p>
                    <a href="<?php echo admin_url('admin.php?page=spfm-users&action=add'); ?>" class="button button-primary">Add User</a>
                </div>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Username</th>
                            <th width="25%">Full Name</th>
                            <th width="20%">Email</th>
                            <th width="10%">Role</th>
                            <th width="10%">Status</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?php echo $u->id; ?></td>
                                <td>
                                    <strong><?php echo esc_html($u->username); ?></strong>
                                </td>
                                <td><?php echo esc_html($u->full_name); ?></td>
                                <td><a href="mailto:<?php echo esc_attr($u->email); ?>"><?php echo esc_html($u->email); ?></a></td>
                                <td>
                                    <span class="role-badge <?php echo $u->role; ?>">
                                        <?php echo ucfirst($u->role); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $u->status ? 'active' : 'inactive'; ?>">
                                        <?php echo $u->status ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
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
            <?php endif; ?>
        </div>
        
    <?php else: ?>
        <!-- Add/Edit User -->
        <a href="<?php echo admin_url('admin.php?page=spfm-users'); ?>" class="page-title-action">Back to Users</a>
        
        <div class="spfm-form-container">
            <form id="spfm-user-form" class="spfm-admin-form">
                <input type="hidden" name="id" value="<?php echo $user ? $user->id : ''; ?>">
                
                <table class="form-table">
                    <?php if (!$user): ?>
                        <tr>
                            <th><label for="username">Username *</label></th>
                            <td>
                                <input type="text" name="username" id="username" class="regular-text" required>
                                <p class="description">Username cannot be changed after creation.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th>Username</th>
                            <td><strong><?php echo esc_html($user->username); ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    
                    <tr>
                        <th><label for="email">Email *</label></th>
                        <td><input type="email" name="email" id="email" class="regular-text" required value="<?php echo $user ? esc_attr($user->email) : ''; ?>"></td>
                    </tr>
                    
                    <tr>
                        <th><label for="full_name">Full Name *</label></th>
                        <td><input type="text" name="full_name" id="full_name" class="regular-text" required value="<?php echo $user ? esc_attr($user->full_name) : ''; ?>"></td>
                    </tr>
                    
                    <tr>
                        <th><label for="password">Password <?php echo $user ? '' : '*'; ?></label></th>
                        <td>
                            <input type="password" name="password" id="password" class="regular-text" <?php echo $user ? '' : 'required'; ?>>
                            <?php if ($user): ?>
                                <p class="description">Leave blank to keep current password.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label for="role">Role</label></th>
                        <td>
                            <select name="role" id="role">
                                <option value="user" <?php selected($user ? $user->role : 'user', 'user'); ?>>User</option>
                                <option value="admin" <?php selected($user ? $user->role : 'user', 'admin'); ?>>Admin</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label for="status">Status</label></th>
                        <td>
                            <select name="status" id="status">
                                <option value="1" <?php selected($user ? $user->status : 1, 1); ?>>Active</option>
                                <option value="0" <?php selected($user ? $user->status : 1, 0); ?>>Inactive</option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary button-large">
                        <?php echo $user ? 'Update User' : 'Create User'; ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=spfm-users'); ?>" class="button button-large">Cancel</a>
                </p>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-users-container,
.spfm-form-container {
    background: #fff;
    padding: 25px;
    margin-top: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
}
.empty-state .dashicons {
    font-size: 60px;
    width: 60px;
    height: 60px;
    color: #ccc;
}
.role-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 12px;
}
.role-badge.admin {
    background: #e3f2fd;
    color: #1565c0;
}
.role-badge.user {
    background: #e8f5e9;
    color: #2e7d32;
}
.status-badge {
    display: inline-block;
    padding: 3px 10px;
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
.wp-list-table .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}
</style>

<script>
jQuery(document).ready(function($) {
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
    
    // Save user
    $('#spfm-user-form').on('submit', function(e) {
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
});
</script>
