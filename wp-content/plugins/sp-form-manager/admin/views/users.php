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

$search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$per_page = 20;
$offset = ($paged - 1) * $per_page;

$where = "WHERE 1=1";
if (!empty($search)) {
    $search_like = '%' . $wpdb->esc_like($search) . '%';
    $where .= $wpdb->prepare(" AND (username LIKE %s OR email LIKE %s OR full_name LIKE %s)", $search_like, $search_like, $search_like);
}

$users = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table $where ORDER BY created_at DESC LIMIT %d OFFSET %d", $per_page, $offset));
$total = $wpdb->get_var("SELECT COUNT(*) FROM $table $where");
$total_pages = ceil($total / $per_page);
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">SP Form Users</h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-users&action=add'); ?>" class="page-title-action">Add New</a>
        
        <form method="get" class="spfm-search-form">
            <input type="hidden" name="page" value="spfm-users">
            <p class="search-box">
                <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search users...">
                <input type="submit" class="button" value="Search">
            </p>
        </form>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="20%">Username</th>
                    <th width="20%">Email</th>
                    <th width="20%">Full Name</th>
                    <th width="10%">Role</th>
                    <th width="10%">Status</th>
                    <th width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7">No users found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo $u->id; ?></td>
                            <td><strong><?php echo esc_html($u->username); ?></strong></td>
                            <td><?php echo esc_html($u->email); ?></td>
                            <td><?php echo esc_html($u->full_name); ?></td>
                            <td>
                                <span class="spfm-role spfm-role-<?php echo $u->role; ?>">
                                    <?php echo ucfirst($u->role); ?>
                                </span>
                            </td>
                            <td>
                                <span class="spfm-status spfm-status-<?php echo $u->status ? 'active' : 'inactive'; ?>">
                                    <?php echo $u->status ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=spfm-users&action=edit&id=' . $u->id); ?>" class="button button-small">Edit</a>
                                <?php if ($u->id != 1): ?>
                                    <button type="button" class="button button-small button-link-delete spfm-delete-user" data-id="<?php echo $u->id; ?>">Delete</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if ($total_pages > 1): ?>
            <div class="tablenav bottom">
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php echo $total; ?> items</span>
                    <span class="pagination-links">
                        <?php if ($paged > 1): ?>
                            <a class="prev-page button" href="<?php echo admin_url('admin.php?page=spfm-users&paged=' . ($paged - 1)); ?>">‹</a>
                        <?php endif; ?>
                        <span class="paging-input"><?php echo $paged; ?> of <?php echo $total_pages; ?></span>
                        <?php if ($paged < $total_pages): ?>
                            <a class="next-page button" href="<?php echo admin_url('admin.php?page=spfm-users&paged=' . ($paged + 1)); ?>">›</a>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-users'); ?>" class="page-title-action">Back to List</a>
        
        <div class="spfm-form-container">
            <form id="spfm-user-form" class="spfm-admin-form">
                <input type="hidden" name="id" value="<?php echo $user ? $user->id : ''; ?>">
                
                <table class="form-table">
                    <tr>
                        <th><label for="username">Username <span class="required">*</span></label></th>
                        <td>
                            <input type="text" name="username" id="username" class="regular-text" required value="<?php echo $user ? esc_attr($user->username) : ''; ?>" <?php echo $user ? 'readonly' : ''; ?>>
                            <?php if ($user): ?><p class="description">Username cannot be changed.</p><?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="email">Email <span class="required">*</span></label></th>
                        <td><input type="email" name="email" id="email" class="regular-text" required value="<?php echo $user ? esc_attr($user->email) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="full_name">Full Name <span class="required">*</span></label></th>
                        <td><input type="text" name="full_name" id="full_name" class="regular-text" required value="<?php echo $user ? esc_attr($user->full_name) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="password">Password <?php echo $user ? '' : '<span class="required">*</span>'; ?></label></th>
                        <td>
                            <input type="password" name="password" id="password" class="regular-text" <?php echo $user ? '' : 'required'; ?>>
                            <?php if ($user): ?><p class="description">Leave blank to keep current password.</p><?php endif; ?>
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
                    <button type="submit" class="button button-primary">
                        <?php echo $user ? 'Update User' : 'Add User'; ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=spfm-users'); ?>" class="button">Cancel</a>
                </p>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-search-form {
    float: right;
    margin-bottom: 10px;
}
.spfm-status, .spfm-role {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 12px;
}
.spfm-status-active {
    background: #d4edda;
    color: #155724;
}
.spfm-status-inactive {
    background: #f8d7da;
    color: #721c24;
}
.spfm-role-admin {
    background: #cce5ff;
    color: #004085;
}
.spfm-role-user {
    background: #e9ecef;
    color: #495057;
}
.spfm-form-container {
    background: #fff;
    padding: 20px;
    margin-top: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.required {
    color: #dc3545;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Delete user
    $('.spfm-delete-user').on('click', function() {
        if (!confirm('Are you sure you want to delete this user?')) {
            return;
        }
        
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        
        $.ajax({
            url: spfm_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'spfm_delete_user',
                nonce: spfm_ajax.nonce,
                id: id
            },
            success: function(response) {
                if (response.success) {
                    $row.fadeOut(300, function() { $(this).remove(); });
                } else {
                    alert(response.data.message);
                }
            }
        });
    });
    
    // Save user
    $('#spfm-user-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var originalText = $btn.text();
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_user&nonce=' + spfm_ajax.nonce;
        
        $.ajax({
            url: spfm_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?php echo admin_url('admin.php?page=spfm-users'); ?>';
                } else {
                    alert(response.data.message);
                    $btn.text(originalText).prop('disabled', false);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $btn.text(originalText).prop('disabled', false);
            }
        });
    });
});
</script>
