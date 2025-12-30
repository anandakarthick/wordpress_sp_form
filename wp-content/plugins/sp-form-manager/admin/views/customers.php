<?php
if (!defined('ABSPATH')) {
    exit;
}

$customers_handler = SPFM_Customers::get_instance();
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$customer = null;

if ($action === 'edit' && $id) {
    $customer = $customers_handler->get_by_id($id);
}

$search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$per_page = 20;

$customers = $customers_handler->get_all(array(
    'search' => $search,
    'page' => $paged,
    'per_page' => $per_page
));

$total = $customers_handler->get_total(array('search' => $search));
$total_pages = ceil($total / $per_page);
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">Customers</h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=add'); ?>" class="page-title-action">Add New</a>
        
        <form method="get" class="spfm-search-form">
            <input type="hidden" name="page" value="spfm-customers">
            <p class="search-box">
                <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search customers...">
                <input type="submit" class="button" value="Search">
            </p>
        </form>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="20%">Name</th>
                    <th width="20%">Email</th>
                    <th width="15%">Phone</th>
                    <th width="15%">Company</th>
                    <th width="10%">Status</th>
                    <th width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="7">No customers found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $cust): ?>
                        <tr>
                            <td><?php echo $cust->id; ?></td>
                            <td><strong><?php echo esc_html($cust->name); ?></strong></td>
                            <td><?php echo esc_html($cust->email); ?></td>
                            <td><?php echo esc_html($cust->phone); ?></td>
                            <td><?php echo esc_html($cust->company); ?></td>
                            <td>
                                <span class="spfm-status spfm-status-<?php echo $cust->status ? 'active' : 'inactive'; ?>">
                                    <?php echo $cust->status ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=edit&id=' . $cust->id); ?>" class="button button-small">Edit</a>
                                <button type="button" class="button button-small button-link-delete spfm-delete-customer" data-id="<?php echo $cust->id; ?>">Delete</button>
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
                            <a class="prev-page button" href="<?php echo admin_url('admin.php?page=spfm-customers&paged=' . ($paged - 1) . ($search ? '&s=' . urlencode($search) : '')); ?>">‹</a>
                        <?php endif; ?>
                        <span class="paging-input"><?php echo $paged; ?> of <?php echo $total_pages; ?></span>
                        <?php if ($paged < $total_pages): ?>
                            <a class="next-page button" href="<?php echo admin_url('admin.php?page=spfm-customers&paged=' . ($paged + 1) . ($search ? '&s=' . urlencode($search) : '')); ?>">›</a>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-customers'); ?>" class="page-title-action">Back to List</a>
        
        <div class="spfm-form-container">
            <form id="spfm-customer-form" class="spfm-admin-form">
                <input type="hidden" name="id" value="<?php echo $customer ? $customer->id : ''; ?>">
                
                <table class="form-table">
                    <tr>
                        <th><label for="name">Name <span class="required">*</span></label></th>
                        <td><input type="text" name="name" id="name" class="regular-text" required value="<?php echo $customer ? esc_attr($customer->name) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="email">Email <span class="required">*</span></label></th>
                        <td><input type="email" name="email" id="email" class="regular-text" required value="<?php echo $customer ? esc_attr($customer->email) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="phone">Phone</label></th>
                        <td><input type="text" name="phone" id="phone" class="regular-text" value="<?php echo $customer ? esc_attr($customer->phone) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="company">Company</label></th>
                        <td><input type="text" name="company" id="company" class="regular-text" value="<?php echo $customer ? esc_attr($customer->company) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="address">Address</label></th>
                        <td><textarea name="address" id="address" rows="3" class="large-text"><?php echo $customer ? esc_textarea($customer->address) : ''; ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="city">City</label></th>
                        <td><input type="text" name="city" id="city" class="regular-text" value="<?php echo $customer ? esc_attr($customer->city) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="state">State</label></th>
                        <td><input type="text" name="state" id="state" class="regular-text" value="<?php echo $customer ? esc_attr($customer->state) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="country">Country</label></th>
                        <td><input type="text" name="country" id="country" class="regular-text" value="<?php echo $customer ? esc_attr($customer->country) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="zip_code">ZIP Code</label></th>
                        <td><input type="text" name="zip_code" id="zip_code" class="regular-text" value="<?php echo $customer ? esc_attr($customer->zip_code) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="notes">Notes</label></th>
                        <td><textarea name="notes" id="notes" rows="4" class="large-text"><?php echo $customer ? esc_textarea($customer->notes) : ''; ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="status">Status</label></th>
                        <td>
                            <select name="status" id="status">
                                <option value="1" <?php selected($customer ? $customer->status : 1, 1); ?>>Active</option>
                                <option value="0" <?php selected($customer ? $customer->status : 1, 0); ?>>Inactive</option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php echo $customer ? 'Update Customer' : 'Add Customer'; ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=spfm-customers'); ?>" class="button">Cancel</a>
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
.spfm-status {
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
    // Delete customer
    $('.spfm-delete-customer').on('click', function() {
        if (!confirm('Are you sure you want to delete this customer?')) {
            return;
        }
        
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        
        $.ajax({
            url: spfm_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'spfm_delete_customer',
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
    
    // Save customer
    $('#spfm-customer-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var originalText = $btn.text();
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_customer&nonce=' + spfm_ajax.nonce;
        
        $.ajax({
            url: spfm_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?php echo admin_url('admin.php?page=spfm-customers'); ?>';
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
