<?php
if (!defined('ABSPATH')) {
    exit;
}

$customers_handler = SPFM_Customers::get_instance();
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$customer = null;
if (($action === 'edit') && $id) {
    $customer = $customers_handler->get_by_id($id);
}

$customers = $customers_handler->get_all(array('per_page' => 100));
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-groups"></span> Customers
    </h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=add'); ?>" class="page-title-action">Add New Customer</a>
        
        <p class="description">Manage your customers. You can share forms directly with customers.</p>
        
        <?php if (empty($customers)): ?>
            <div class="empty-state">
                <span class="dashicons dashicons-groups"></span>
                <h3>No Customers Yet</h3>
                <p>Add your first customer to start sharing forms.</p>
                <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=add'); ?>" class="button button-primary button-hero">Add Customer</a>
            </div>
        <?php else: ?>
            <div class="customers-grid">
                <?php foreach ($customers as $c): ?>
                    <div class="customer-card">
                        <div class="customer-avatar">
                            <?php echo strtoupper(substr($c->name, 0, 1)); ?>
                        </div>
                        <div class="customer-info">
                            <h3><?php echo esc_html($c->name); ?></h3>
                            <?php if ($c->company): ?>
                                <span class="company"><?php echo esc_html($c->company); ?></span>
                            <?php endif; ?>
                            <div class="contact-details">
                                <span><span class="dashicons dashicons-email"></span> <?php echo esc_html($c->email); ?></span>
                                <?php if ($c->phone): ?>
                                    <span><span class="dashicons dashicons-phone"></span> <?php echo esc_html($c->phone); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="customer-actions">
                            <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=edit&id=' . $c->id); ?>" class="button">
                                <span class="dashicons dashicons-edit"></span> Edit
                            </a>
                            <button class="button button-link-delete delete-customer" data-id="<?php echo $c->id; ?>">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Add/Edit Customer -->
        <a href="<?php echo admin_url('admin.php?page=spfm-customers'); ?>" class="page-title-action">← Back to Customers</a>
        
        <div class="customer-editor">
            <form id="customer-form">
                <input type="hidden" name="id" value="<?php echo $customer ? $customer->id : ''; ?>">
                
                <div class="editor-section">
                    <h3>Basic Information</h3>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="name">Full Name *</label>
                            <input type="text" name="name" id="name" required 
                                   value="<?php echo $customer ? esc_attr($customer->name) : ''; ?>">
                        </div>
                        <div class="form-field">
                            <label for="email">Email Address *</label>
                            <input type="email" name="email" id="email" required 
                                   value="<?php echo $customer ? esc_attr($customer->email) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="phone">Phone Number</label>
                            <input type="tel" name="phone" id="phone" 
                                   value="<?php echo $customer ? esc_attr($customer->phone) : ''; ?>">
                        </div>
                        <div class="form-field">
                            <label for="company">Company</label>
                            <input type="text" name="company" id="company" 
                                   value="<?php echo $customer ? esc_attr($customer->company) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="editor-section">
                    <h3>Address Information</h3>
                    
                    <div class="form-field">
                        <label for="address">Street Address</label>
                        <textarea name="address" id="address" rows="2"><?php echo $customer ? esc_textarea($customer->address) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" 
                                   value="<?php echo $customer ? esc_attr($customer->city) : ''; ?>">
                        </div>
                        <div class="form-field">
                            <label for="state">State/Province</label>
                            <input type="text" name="state" id="state" 
                                   value="<?php echo $customer ? esc_attr($customer->state) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" 
                                   value="<?php echo $customer ? esc_attr($customer->country) : ''; ?>">
                        </div>
                        <div class="form-field">
                            <label for="zip_code">ZIP/Postal Code</label>
                            <input type="text" name="zip_code" id="zip_code" 
                                   value="<?php echo $customer ? esc_attr($customer->zip_code) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="editor-section">
                    <h3>Additional Information</h3>
                    
                    <div class="form-field">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" rows="3"><?php echo $customer ? esc_textarea($customer->notes) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-field checkbox-field">
                        <label>
                            <input type="checkbox" name="status" value="1" 
                                   <?php checked($customer ? $customer->status : 1, 1); ?>>
                            Active customer
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="button button-primary button-large">
                        <?php echo $customer ? 'Update Customer' : 'Add Customer'; ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=spfm-customers'); ?>" class="button button-large">Cancel</a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
/* Customers Grid */
.customers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.customer-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.3s;
}
.customer-card:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.customer-avatar {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 700;
    flex-shrink: 0;
}
.customer-info {
    flex: 1;
    min-width: 0;
}
.customer-info h3 {
    margin: 0 0 3px 0;
    font-size: 16px;
}
.customer-info .company {
    font-size: 12px;
    color: #666;
    display: block;
    margin-bottom: 8px;
}
.contact-details {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}
.contact-details span {
    font-size: 12px;
    color: #888;
    display: flex;
    align-items: center;
    gap: 4px;
}
.contact-details .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
}
.customer-actions {
    display: flex;
    gap: 5px;
}
.customer-actions .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

/* Customer Editor */
.customer-editor {
    max-width: 800px;
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
.form-field textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.form-field input:focus,
.form-field textarea:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
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

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 12px;
    margin-top: 20px;
}
.empty-state .dashicons {
    font-size: 60px;
    width: 60px;
    height: 60px;
    color: #ddd;
    margin-bottom: 15px;
}

@media (max-width: 600px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Save customer
    $('#customer-form').on('submit', function(e) {
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
    
    // Delete customer
    $('.delete-customer').on('click', function() {
        if (!confirm('Are you sure you want to delete this customer?')) return;
        
        var id = $(this).data('id');
        var $card = $(this).closest('.customer-card');
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_customer',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                $card.fadeOut(300, function() { $(this).remove(); });
            } else {
                alert(response.data.message);
            }
        });
    });
});
</script>
