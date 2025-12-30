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

// Get stats
$total_customers = count($customers);
$active_count = 0;
$with_company = 0;
foreach ($customers as $c) {
    if ($c->status == 1) $active_count++;
    if (!empty($c->company)) $with_company++;
}
?>

<div class="spfm-customers-wrap">
    <?php if ($action === 'list'): ?>
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <h1><span class="dashicons dashicons-groups"></span> Customers</h1>
                <p>Manage your customers and share forms directly with them</p>
            </div>
            <div class="header-actions">
                <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=add'); ?>" class="btn btn-white">
                    <span class="dashicons dashicons-plus-alt"></span> Add New Customer
                </a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <span class="dashicons dashicons-groups"></span>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $total_customers; ?></div>
                    <div class="stat-label">Total Customers</div>
                    <div class="stat-detail">All registered customers</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active">
                    <span class="dashicons dashicons-yes-alt"></span>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $active_count; ?></div>
                    <div class="stat-label">Active</div>
                    <div class="stat-detail">Currently active customers</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon business">
                    <span class="dashicons dashicons-building"></span>
                </div>
                <div class="stat-content">
                    <div class="stat-number"><?php echo $with_company; ?></div>
                    <div class="stat-label">Business</div>
                    <div class="stat-detail">Customers with company</div>
                </div>
            </div>
        </div>
        
        <!-- Customers List -->
        <?php if (empty($customers)): ?>
            <div class="empty-state-card">
                <div class="empty-icon">
                    <span class="dashicons dashicons-groups"></span>
                </div>
                <h3>No Customers Yet</h3>
                <p>Add your first customer to start sharing forms with them.</p>
                <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=add'); ?>" class="btn btn-primary">
                    <span class="dashicons dashicons-plus-alt"></span> Add Customer
                </a>
            </div>
        <?php else: ?>
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-businessman"></span> All Customers</h3>
                    <span class="badge"><?php echo $total_customers; ?> customers</span>
                </div>
                <div class="card-body no-padding">
                    <div class="customers-list">
                        <?php foreach ($customers as $c): ?>
                            <div class="customer-item">
                                <div class="customer-avatar">
                                    <?php echo strtoupper(substr($c->name, 0, 1)); ?>
                                </div>
                                <div class="customer-main">
                                    <div class="customer-info">
                                        <h4><?php echo esc_html($c->name); ?></h4>
                                        <?php if ($c->company): ?>
                                            <span class="company-name">
                                                <span class="dashicons dashicons-building"></span>
                                                <?php echo esc_html($c->company); ?>
                                            </span>
                                        <?php endif; ?>
                                        <div class="customer-meta">
                                            <span>
                                                <span class="dashicons dashicons-email"></span>
                                                <?php echo esc_html($c->email); ?>
                                            </span>
                                            <?php if ($c->phone): ?>
                                                <span>
                                                    <span class="dashicons dashicons-phone"></span>
                                                    <?php echo esc_html($c->phone); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($c->city || $c->country): ?>
                                                <span>
                                                    <span class="dashicons dashicons-location"></span>
                                                    <?php echo esc_html(implode(', ', array_filter([$c->city, $c->country]))); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="customer-badges">
                                    <span class="status-badge <?php echo $c->status ? 'active' : 'inactive'; ?>">
                                        <?php echo $c->status ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                <div class="customer-actions">
                                    <a href="<?php echo admin_url('admin.php?page=spfm-customers&action=edit&id=' . $c->id); ?>" class="btn btn-outline btn-sm">
                                        <span class="dashicons dashicons-edit"></span> Edit
                                    </a>
                                    <button class="btn btn-danger btn-sm delete-customer" data-id="<?php echo $c->id; ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Add/Edit Customer -->
        <div class="page-header">
            <div class="header-content">
                <a href="<?php echo admin_url('admin.php?page=spfm-customers'); ?>" class="back-link">
                    <span class="dashicons dashicons-arrow-left-alt"></span> Back to Customers
                </a>
                <h1><span class="dashicons dashicons-groups"></span> <?php echo $customer ? 'Edit Customer' : 'Add New Customer'; ?></h1>
                <p><?php echo $customer ? 'Update customer information' : 'Create a new customer record'; ?></p>
            </div>
        </div>
        
        <div class="customer-editor-container">
            <form id="customer-form">
                <input type="hidden" name="id" value="<?php echo $customer ? $customer->id : ''; ?>">
                
                <div class="editor-main">
                    <!-- Basic Information -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><span class="dashicons dashicons-id"></span> Basic Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="name">Full Name <span class="required">*</span></label>
                                    <input type="text" name="name" id="name" required 
                                           value="<?php echo $customer ? esc_attr($customer->name) : ''; ?>"
                                           placeholder="Enter customer name">
                                </div>
                                <div class="form-field">
                                    <label for="email">Email Address <span class="required">*</span></label>
                                    <input type="email" name="email" id="email" required 
                                           value="<?php echo $customer ? esc_attr($customer->email) : ''; ?>"
                                           placeholder="Enter email address">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" 
                                           value="<?php echo $customer ? esc_attr($customer->phone) : ''; ?>"
                                           placeholder="Enter phone number">
                                </div>
                                <div class="form-field">
                                    <label for="company">Company</label>
                                    <input type="text" name="company" id="company" 
                                           value="<?php echo $customer ? esc_attr($customer->company) : ''; ?>"
                                           placeholder="Enter company name">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address Information -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><span class="dashicons dashicons-location"></span> Address Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-field">
                                <label for="address">Street Address</label>
                                <textarea name="address" id="address" rows="2" placeholder="Enter street address"><?php echo $customer ? esc_textarea($customer->address) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="city">City</label>
                                    <input type="text" name="city" id="city" 
                                           value="<?php echo $customer ? esc_attr($customer->city) : ''; ?>"
                                           placeholder="Enter city">
                                </div>
                                <div class="form-field">
                                    <label for="state">State/Province</label>
                                    <input type="text" name="state" id="state" 
                                           value="<?php echo $customer ? esc_attr($customer->state) : ''; ?>"
                                           placeholder="Enter state or province">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="country">Country</label>
                                    <input type="text" name="country" id="country" 
                                           value="<?php echo $customer ? esc_attr($customer->country) : ''; ?>"
                                           placeholder="Enter country">
                                </div>
                                <div class="form-field">
                                    <label for="zip_code">ZIP/Postal Code</label>
                                    <input type="text" name="zip_code" id="zip_code" 
                                           value="<?php echo $customer ? esc_attr($customer->zip_code) : ''; ?>"
                                           placeholder="Enter postal code">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><span class="dashicons dashicons-edit-page"></span> Additional Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-field">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" rows="3" placeholder="Add any notes about this customer..."><?php echo $customer ? esc_textarea($customer->notes) : ''; ?></textarea>
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
                                       <?php checked($customer ? $customer->status : 1, 1); ?>>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Active Customer</span>
                        </div>
                        <p class="status-hint">Inactive customers won't appear in share dropdowns.</p>
                    </div>
                    
                    <div class="sidebar-card">
                        <h4>Actions</h4>
                        <button type="submit" class="btn btn-primary btn-block">
                            <span class="dashicons dashicons-saved"></span>
                            <?php echo $customer ? 'Update Customer' : 'Add Customer'; ?>
                        </button>
                        <a href="<?php echo admin_url('admin.php?page=spfm-customers'); ?>" class="btn btn-outline btn-block">
                            Cancel
                        </a>
                    </div>
                    
                    <?php if ($customer): ?>
                        <div class="sidebar-card">
                            <h4>Customer Details</h4>
                            <div class="detail-list">
                                <div class="detail-item">
                                    <label>Created</label>
                                    <span><?php echo date('M j, Y', strtotime($customer->created_at)); ?></span>
                                </div>
                                <div class="detail-item">
                                    <label>Last Updated</label>
                                    <span><?php echo date('M j, Y', strtotime($customer->updated_at)); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Share -->
                        <div class="sidebar-card">
                            <h4>Quick Share</h4>
                            <p class="quick-share-hint">Share a form with this customer</p>
                            <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="btn btn-outline btn-block btn-sm">
                                <span class="dashicons dashicons-share"></span> Go to Forms
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-customers-wrap {
    padding: 20px;
    max-width: 1400px;
    font-family: 'Inter', -apple-system, sans-serif;
}

/* Page Header */
.page-header {
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
.btn-primary { background: #0891b2; color: #fff; }
.btn-primary:hover { background: #0e7490; color: #fff; }
.btn-white { background: #fff; color: #0891b2; }
.btn-white:hover { background: #ecfeff; transform: translateY(-2px); }
.btn-outline { background: transparent; color: #0891b2; border: 2px solid #0891b2; }
.btn-outline:hover { background: #0891b2; color: #fff; }
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
.stat-icon.total { background: linear-gradient(135deg, #0891b2, #0e7490); }
.stat-icon.active { background: linear-gradient(135deg, #10b981, #059669); }
.stat-icon.business { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
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
.card-header .dashicons { color: #0891b2; }
.card-header .badge {
    background: #ecfeff;
    color: #0891b2;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.card-body { padding: 25px; }
.card-body.no-padding { padding: 0; }

/* Customers List */
.customers-list {
    display: flex;
    flex-direction: column;
}
.customer-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 25px;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.2s;
}
.customer-item:last-child { border-bottom: none; }
.customer-item:hover { background: #f8fafc; }
.customer-avatar {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0891b2, #0e7490);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 22px;
    flex-shrink: 0;
}
.customer-main {
    flex: 1;
}
.customer-info h4 {
    margin: 0 0 5px 0;
    font-size: 16px;
    color: #1e293b;
}
.company-name {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    color: #8b5cf6;
    background: #f3e8ff;
    padding: 3px 10px;
    border-radius: 15px;
    margin-bottom: 8px;
}
.company-name .dashicons {
    font-size: 12px;
    width: 12px;
    height: 12px;
}
.customer-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 8px;
}
.customer-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    color: #64748b;
}
.customer-meta .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
    color: #94a3b8;
}
.customer-badges {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
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
.customer-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

/* Customer Editor */
.customer-editor-container form {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 25px;
}
.editor-main {
    display: flex;
    flex-direction: column;
    gap: 0;
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
.form-field textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: border-color 0.2s;
}
.form-field input:focus,
.form-field textarea:focus {
    border-color: #0891b2;
    outline: none;
}
.form-field input::placeholder,
.form-field textarea::placeholder {
    color: #94a3b8;
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
    background: linear-gradient(135deg, #0891b2, #0e7490);
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
.quick-share-hint {
    margin: 0 0 15px 0;
    font-size: 13px;
    color: #64748b;
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
    background: linear-gradient(135deg, #0891b2, #0e7490);
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
    .customer-editor-container form { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .page-header { flex-direction: column; text-align: center; gap: 20px; }
    .customer-item { flex-direction: column; align-items: flex-start; gap: 15px; }
    .customer-badges { width: 100%; }
    .customer-actions { width: 100%; }
    .form-row { grid-template-columns: 1fr; }
}
</style>

<script>
var spfm_ajax = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('spfm_nonce'); ?>'
};

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
                $btn.html('<span class="dashicons dashicons-saved"></span> Save Customer').prop('disabled', false);
            }
        });
    });
    
    // Delete customer
    $('.delete-customer').on('click', function() {
        if (!confirm('Are you sure you want to delete this customer?')) return;
        
        var id = $(this).data('id');
        var $item = $(this).closest('.customer-item');
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_customer',
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
