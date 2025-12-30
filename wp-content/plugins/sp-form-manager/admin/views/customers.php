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

$customers = $customers_handler->get_all(array('per_page' => 50));
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">Customers</h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=add'); ?>" class="page-title-action">Add New Customer</a>
        
        <div class="spfm-customers-container">
            <?php if (empty($customers)): ?>
                <div class="empty-state">
                    <span class="dashicons dashicons-groups"></span>
                    <p>No customers yet. Add your first customer!</p>
                    <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=add'); ?>" class="button button-primary">Add Customer</a>
                </div>
            <?php else: ?>
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
                        <?php foreach ($customers as $c): ?>
                            <tr>
                                <td><?php echo $c->id; ?></td>
                                <td><strong><?php echo esc_html($c->name); ?></strong></td>
                                <td><a href="mailto:<?php echo esc_attr($c->email); ?>"><?php echo esc_html($c->email); ?></a></td>
                                <td><?php echo esc_html($c->phone ?: '-'); ?></td>
                                <td><?php echo esc_html($c->company ?: '-'); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $c->status ? 'active' : 'inactive'; ?>">
                                        <?php echo $c->status ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=edit&id=' . $c->id); ?>" class="button button-small" title="Edit">
                                        <span class="dashicons dashicons-edit"></span>
                                    </a>
                                    <button class="button button-small button-link-delete delete-customer" data-id="<?php echo $c->id; ?>" title="Delete">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
    <?php else: ?>
        <!-- Add/Edit Customer -->
        <a href="<?php echo admin_url('admin.php?page=spfm-customers'); ?>" class="page-title-action">Back to Customers</a>
        
        <div class="spfm-form-container">
            <form id="spfm-customer-form" class="spfm-admin-form">
                <input type="hidden" name="id" value="<?php echo $customer ? $customer->id : ''; ?>">
                
                <div class="form-columns">
                    <div class="form-column">
                        <h3>Basic Information</h3>
                        
                        <table class="form-table">
                            <tr>
                                <th><label for="name">Name *</label></th>
                                <td><input type="text" name="name" id="name" class="regular-text" required value="<?php echo $customer ? esc_attr($customer->name) : ''; ?>"></td>
                            </tr>
                            <tr>
                                <th><label for="email">Email *</label></th>
                                <td><input type="email" name="email" id="email" class="regular-text" required value="<?php echo $customer ? esc_attr($customer->email) : ''; ?>"></td>
                            </tr>
                            <tr>
                                <th><label for="phone">Phone</label></th>
                                <td><input type="tel" name="phone" id="phone" class="regular-text" value="<?php echo $customer ? esc_attr($customer->phone) : ''; ?>" placeholder="+1234567890"></td>
                            </tr>
                            <tr>
                                <th><label for="company">Company</label></th>
                                <td><input type="text" name="company" id="company" class="regular-text" value="<?php echo $customer ? esc_attr($customer->company) : ''; ?>"></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="form-column">
                        <h3>Address</h3>
                        
                        <table class="form-table">
                            <tr>
                                <th><label for="address">Address</label></th>
                                <td><textarea name="address" id="address" rows="2" class="large-text"><?php echo $customer ? esc_textarea($customer->address) : ''; ?></textarea></td>
                            </tr>
                            <tr>
                                <th><label for="city">City</label></th>
                                <td><input type="text" name="city" id="city" value="<?php echo $customer ? esc_attr($customer->city) : ''; ?>"></td>
                            </tr>
                            <tr>
                                <th><label for="state">State/Province</label></th>
                                <td><input type="text" name="state" id="state" value="<?php echo $customer ? esc_attr($customer->state) : ''; ?>"></td>
                            </tr>
                            <tr>
                                <th><label for="country">Country</label></th>
                                <td><input type="text" name="country" id="country" value="<?php echo $customer ? esc_attr($customer->country) : ''; ?>"></td>
                            </tr>
                            <tr>
                                <th><label for="zip_code">Zip/Postal Code</label></th>
                                <td><input type="text" name="zip_code" id="zip_code" value="<?php echo $customer ? esc_attr($customer->zip_code) : ''; ?>"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Additional Information</h3>
                    <table class="form-table">
                        <tr>
                            <th><label for="notes">Notes</label></th>
                            <td><textarea name="notes" id="notes" rows="3" class="large-text"><?php echo $customer ? esc_textarea($customer->notes) : ''; ?></textarea></td>
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
                </div>
                
                <p class="submit">
                    <button type="submit" class="button button-primary button-large">
                        <?php echo $customer ? 'Update Customer' : 'Create Customer'; ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=spfm-customers'); ?>" class="button button-large">Cancel</a>
                </p>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-customers-container,
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
    margin-bottom: 15px;
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
.form-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}
.form-column h3,
.form-section h3 {
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.form-section {
    margin-top: 30px;
}
@media (max-width: 900px) {
    .form-columns {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Delete customer
    $('.delete-customer').on('click', function() {
        if (!confirm('Are you sure you want to delete this customer?')) return;
        
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_customer',
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
    
    // Save customer
    $('#spfm-customer-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_customer&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-customers'); ?>';
            } else {
                alert(response.data.message);
                $btn.text('Save Customer').prop('disabled', false);
            }
        });
    });
});
</script>
