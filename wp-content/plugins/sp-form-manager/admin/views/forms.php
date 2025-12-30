<?php
if (!defined('ABSPATH')) {
    exit;
}

$forms_handler = SPFM_Forms::get_instance();
$themes_handler = SPFM_Themes::get_instance();
$customers_handler = SPFM_Customers::get_instance();

$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$form = null;

if (($action === 'edit' || $action === 'share') && $id) {
    $form = $forms_handler->get_by_id($id);
}

$forms = $forms_handler->get_all(array('per_page' => 50));
$themes = $themes_handler->get_all_active();
$customers = $customers_handler->get_all(array('per_page' => 100, 'status' => 1));
$field_types = $forms_handler->get_field_types();
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">Forms</h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="page-title-action">Add New Form</a>
        
        <div class="spfm-form-container">
            <?php if (empty($forms)): ?>
                <div class="spfm-empty-state">
                    <span class="dashicons dashicons-forms"></span>
                    <p>No forms yet. Create your first form!</p>
                    <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="button button-primary">Create Form</a>
                </div>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="25%">Form Name</th>
                            <th width="15%">Theme</th>
                            <th width="10%">Fields</th>
                            <th width="10%">Submissions</th>
                            <th width="10%">Status</th>
                            <th width="25%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($forms as $f): ?>
                            <?php 
                            $field_count = count($forms_handler->get_fields($f->id));
                            $submission_count = $forms_handler->get_submission_count($f->id);
                            ?>
                            <tr>
                                <td><?php echo $f->id; ?></td>
                                <td>
                                    <strong><?php echo esc_html($f->name); ?></strong>
                                    <?php if ($f->description): ?>
                                        <br><small style="color:#666;"><?php echo esc_html(substr($f->description, 0, 50)); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($f->theme_name): ?>
                                        <span class="dashicons dashicons-art" style="color:#667eea;"></span>
                                        <?php echo esc_html($f->theme_name); ?>
                                    <?php else: ?>
                                        <span style="color:#999;">Default</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="spfm-badge"><?php echo $field_count; ?> fields</span>
                                </td>
                                <td>
                                    <span class="spfm-badge spfm-badge-info"><?php echo $submission_count; ?></span>
                                </td>
                                <td>
                                    <span class="spfm-status <?php echo $f->status ? 'spfm-status-active' : 'spfm-status-inactive'; ?>">
                                        <?php echo $f->status ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=share&id=' . $f->id); ?>" class="button button-small button-primary" title="Share Form">
                                        <span class="dashicons dashicons-share"></span> Share
                                    </a>
                                    <a href="<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $f->id); ?>" class="button button-small" title="Manage Fields">
                                        <span class="dashicons dashicons-list-view"></span> Fields
                                    </a>
                                    <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=edit&id=' . $f->id); ?>" class="button button-small" title="Edit">
                                        <span class="dashicons dashicons-edit"></span>
                                    </a>
                                    <button class="button button-small button-link-delete spfm-delete-form" data-id="<?php echo $f->id; ?>" title="Delete">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
    <?php elseif ($action === 'share' && $form): ?>
        <!-- Share Form -->
        <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="page-title-action">Back to Forms</a>
        
        <div class="spfm-share-container">
            <div class="share-header">
                <h2><span class="dashicons dashicons-share"></span> Share Form: <?php echo esc_html($form->name); ?></h2>
                <p>Share this form with customers via Email, WhatsApp/SMS, or generate a direct link.</p>
            </div>
            
            <div class="share-methods">
                <!-- Generate Link -->
                <div class="share-method-card">
                    <div class="method-icon" style="background: #17a2b8;">
                        <span class="dashicons dashicons-admin-links"></span>
                    </div>
                    <h3>Generate Link</h3>
                    <p>Create a unique shareable link for this form.</p>
                    <button class="button button-primary" id="generate-link" data-form-id="<?php echo $form->id; ?>">
                        Generate Link
                    </button>
                    <div class="generated-link" style="display:none;">
                        <input type="text" id="share-link" readonly class="regular-text">
                        <button class="button" id="copy-link">Copy</button>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="share-method-card">
                    <div class="method-icon" style="background: #28a745;">
                        <span class="dashicons dashicons-email"></span>
                    </div>
                    <h3>Share via Email</h3>
                    <p>Send the form link directly to customer's email.</p>
                    <form id="share-email-form">
                        <input type="hidden" name="form_id" value="<?php echo $form->id; ?>">
                        <input type="hidden" name="method" value="email">
                        <div class="form-field">
                            <label>Select Customer</label>
                            <select name="customer_id" id="email-customer">
                                <option value="">-- Or enter email manually --</option>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?php echo $c->id; ?>" data-email="<?php echo esc_attr($c->email); ?>">
                                        <?php echo esc_html($c->name . ' (' . $c->email . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Email Address *</label>
                            <input type="email" name="email" id="share-email" required class="regular-text" placeholder="customer@example.com">
                        </div>
                        <button type="submit" class="button button-primary">
                            <span class="dashicons dashicons-email-alt"></span> Send Email
                        </button>
                    </form>
                </div>
                
                <!-- WhatsApp/SMS -->
                <div class="share-method-card">
                    <div class="method-icon" style="background: #25d366;">
                        <span class="dashicons dashicons-smartphone"></span>
                    </div>
                    <h3>Share via WhatsApp/SMS</h3>
                    <p>Send the form link via WhatsApp or SMS (requires Nexmo configuration).</p>
                    <form id="share-whatsapp-form">
                        <input type="hidden" name="form_id" value="<?php echo $form->id; ?>">
                        <input type="hidden" name="method" value="whatsapp">
                        <div class="form-field">
                            <label>Select Customer</label>
                            <select name="customer_id" id="whatsapp-customer">
                                <option value="">-- Or enter phone manually --</option>
                                <?php foreach ($customers as $c): ?>
                                    <?php if ($c->phone): ?>
                                        <option value="<?php echo $c->id; ?>" data-phone="<?php echo esc_attr($c->phone); ?>">
                                            <?php echo esc_html($c->name . ' (' . $c->phone . ')'); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Phone Number * (with country code)</label>
                            <input type="tel" name="phone" id="share-phone" required class="regular-text" placeholder="+1234567890">
                        </div>
                        <button type="submit" class="button button-primary">
                            <span class="dashicons dashicons-smartphone"></span> Send SMS
                        </button>
                    </form>
                    <p class="description" style="margin-top:10px;">
                        <a href="<?php echo admin_url('admin.php?page=spfm-settings'); ?>">Configure Nexmo API</a> to enable SMS/WhatsApp.
                    </p>
                </div>
            </div>
            
            <!-- Share History -->
            <div class="share-history">
                <h3><span class="dashicons dashicons-backup"></span> Share History</h3>
                <?php
                global $wpdb;
                $shares = $wpdb->get_results($wpdb->prepare(
                    "SELECT s.*, c.name as customer_name FROM {$wpdb->prefix}spfm_form_shares s 
                     LEFT JOIN {$wpdb->prefix}spfm_customers c ON s.customer_id = c.id
                     WHERE s.form_id = %d ORDER BY s.created_at DESC LIMIT 20",
                    $form->id
                ));
                ?>
                <?php if (empty($shares)): ?>
                    <p class="no-history">No shares yet for this form.</p>
                <?php else: ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Method</th>
                                <th>Views</th>
                                <th>Status</th>
                                <th>Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($shares as $s): ?>
                                <tr>
                                    <td><?php echo date('M j, Y g:i A', strtotime($s->created_at)); ?></td>
                                    <td><?php echo $s->customer_name ? esc_html($s->customer_name) : '-'; ?></td>
                                    <td>
                                        <?php if ($s->shared_via === 'email'): ?>
                                            <span class="dashicons dashicons-email" style="color:#28a745;"></span> Email
                                        <?php elseif ($s->shared_via === 'whatsapp'): ?>
                                            <span class="dashicons dashicons-smartphone" style="color:#25d366;"></span> SMS
                                        <?php else: ?>
                                            <span class="dashicons dashicons-admin-links" style="color:#17a2b8;"></span> Link
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $s->views; ?></td>
                                    <td>
                                        <span class="spfm-status <?php echo $s->status === 'active' ? 'spfm-status-active' : 'spfm-status-inactive'; ?>">
                                            <?php echo ucfirst($s->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo SPFM_Share::get_share_url($s->token); ?>" target="_blank" class="button button-small">
                                            <span class="dashicons dashicons-external"></span> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Add/Edit Form -->
        <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="page-title-action">Back to Forms</a>
        
        <div class="spfm-form-container">
            <form id="spfm-form-form" class="spfm-admin-form">
                <input type="hidden" name="id" value="<?php echo $form ? $form->id : ''; ?>">
                
                <table class="form-table">
                    <tr>
                        <th><label for="name">Form Name *</label></th>
                        <td><input type="text" name="name" id="name" class="regular-text" required value="<?php echo $form ? esc_attr($form->name) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="description">Description</label></th>
                        <td><textarea name="description" id="description" rows="3" class="large-text"><?php echo $form ? esc_textarea($form->description) : ''; ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="theme_id">Theme</label></th>
                        <td>
                            <select name="theme_id" id="theme_id">
                                <option value="">Default Theme</option>
                                <?php foreach ($themes as $t): ?>
                                    <option value="<?php echo $t->id; ?>" <?php selected($form ? $form->theme_id : '', $t->id); ?>>
                                        <?php echo esc_html($t->name); ?> <?php echo $t->is_template ? '(Template)' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="header_text">Header Text</label></th>
                        <td><input type="text" name="header_text" id="header_text" class="regular-text" value="<?php echo $form ? esc_attr($form->header_text) : ''; ?>" placeholder="Form title displayed to users"></td>
                    </tr>
                    <tr>
                        <th><label for="footer_text">Footer Text</label></th>
                        <td><textarea name="footer_text" id="footer_text" rows="2" class="large-text" placeholder="Footer message (e.g., privacy policy, terms)"><?php echo $form ? esc_textarea($form->footer_text) : ''; ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="submit_button_text">Submit Button Text</label></th>
                        <td><input type="text" name="submit_button_text" id="submit_button_text" value="<?php echo $form ? esc_attr($form->submit_button_text) : 'Submit'; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="success_message">Success Message</label></th>
                        <td><textarea name="success_message" id="success_message" rows="2" class="large-text"><?php echo $form ? esc_textarea($form->success_message) : 'Thank you for your submission!'; ?></textarea></td>
                    </tr>
                    <tr>
                        <th>Options</th>
                        <td>
                            <label>
                                <input type="checkbox" name="allow_customization" value="1" <?php checked($form ? $form->allow_customization : 1, 1); ?>>
                                Allow customers to customize theme colors
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" name="notify_admin" value="1" <?php checked($form ? $form->notify_admin : 1, 1); ?>>
                                Send email notification on new submission
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="status">Status</label></th>
                        <td>
                            <select name="status" id="status">
                                <option value="1" <?php selected($form ? $form->status : 1, 1); ?>>Active</option>
                                <option value="0" <?php selected($form ? $form->status : 1, 0); ?>>Inactive</option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary button-large">
                        <?php echo $form ? 'Update Form' : 'Create Form'; ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="button button-large">Cancel</a>
                    
                    <?php if ($form): ?>
                        <a href="<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $form->id); ?>" class="button button-secondary button-large" style="margin-left: 20px;">
                            <span class="dashicons dashicons-list-view"></span> Manage Fields
                        </a>
                    <?php endif; ?>
                </p>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-form-container {
    background: #fff;
    padding: 25px;
    margin-top: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.spfm-badge {
    display: inline-block;
    padding: 3px 10px;
    background: #e9ecef;
    border-radius: 20px;
    font-size: 12px;
}
.spfm-badge-info {
    background: #d1ecf1;
    color: #0c5460;
}
.spfm-status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
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
.spfm-empty-state {
    text-align: center;
    padding: 60px 20px;
}
.spfm-empty-state .dashicons {
    font-size: 60px;
    width: 60px;
    height: 60px;
    color: #ccc;
}
/* Share Styles */
.spfm-share-container {
    margin-top: 20px;
}
.share-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 30px;
    border-radius: 10px 10px 0 0;
}
.share-header h2 {
    margin: 0 0 10px 0;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
}
.share-header p {
    margin: 0;
    opacity: 0.9;
}
.share-methods {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 30px;
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-top: 0;
}
.share-method-card {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.method-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}
.method-icon .dashicons {
    color: #fff;
    font-size: 24px;
    width: 24px;
    height: 24px;
}
.share-method-card h3 {
    margin: 0 0 10px 0;
}
.share-method-card p {
    color: #666;
    margin-bottom: 15px;
}
.form-field {
    margin-bottom: 15px;
}
.form-field label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}
.form-field input,
.form-field select {
    width: 100%;
}
.generated-link {
    margin-top: 15px;
    display: flex;
    gap: 5px;
}
.generated-link input {
    flex: 1;
}
.share-history {
    background: #fff;
    padding: 25px;
    border: 1px solid #ddd;
    border-radius: 0 0 10px 10px;
    border-top: 0;
}
.share-history h3 {
    margin-top: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.no-history {
    text-align: center;
    color: #999;
    padding: 30px;
}
@media (max-width: 1200px) {
    .share-methods {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Customer select auto-fill
    $('#email-customer').on('change', function() {
        var email = $(this).find(':selected').data('email');
        if (email) {
            $('#share-email').val(email);
        }
    });
    
    $('#whatsapp-customer').on('change', function() {
        var phone = $(this).find(':selected').data('phone');
        if (phone) {
            $('#share-phone').val(phone);
        }
    });
    
    // Generate link
    $('#generate-link').on('click', function() {
        var formId = $(this).data('form-id');
        var $btn = $(this);
        
        $btn.text('Generating...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_share_form',
            nonce: spfm_ajax.nonce,
            form_id: formId,
            method: 'link'
        }, function(response) {
            if (response.success) {
                $('#share-link').val(response.data.url);
                $('.generated-link').show();
            } else {
                alert(response.data.message);
            }
            $btn.text('Generate Link').prop('disabled', false);
        });
    });
    
    // Copy link
    $('#copy-link').on('click', function() {
        var $input = $('#share-link');
        $input.select();
        document.execCommand('copy');
        $(this).text('Copied!');
        setTimeout(function() {
            $('#copy-link').text('Copy');
        }, 2000);
    });
    
    // Share via email
    $('#share-email-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        $btn.text('Sending...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_share_form&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            alert(response.data.message);
            if (response.success) {
                location.reload();
            }
            $btn.html('<span class="dashicons dashicons-email-alt"></span> Send Email').prop('disabled', false);
        });
    });
    
    // Share via WhatsApp
    $('#share-whatsapp-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        $btn.text('Sending...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_share_form&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            alert(response.data.message);
            if (response.success) {
                location.reload();
            }
            $btn.html('<span class="dashicons dashicons-smartphone"></span> Send SMS').prop('disabled', false);
        });
    });
    
    // Delete form
    $('.spfm-delete-form').on('click', function() {
        if (!confirm('Are you sure? All fields and submissions will be deleted!')) return;
        var id = $(this).data('id');
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_form',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.data.message);
            }
        });
    });
    
    // Save form
    $('#spfm-form-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_form&nonce=' + spfm_ajax.nonce;
        
        // Handle unchecked checkboxes
        if (!$('input[name="allow_customization"]').is(':checked')) {
            formData += '&allow_customization=0';
        }
        if (!$('input[name="notify_admin"]').is(':checked')) {
            formData += '&notify_admin=0';
        }
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-forms'); ?>';
            } else {
                alert(response.data.message);
                $btn.text('Save Form').prop('disabled', false);
            }
        });
    });
});
</script>
