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

// Get all available themes (templates only)
$available_themes = $themes_handler->get_templates();
$customers = $customers_handler->get_all(array('per_page' => 200, 'status' => 1));
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-media-document"></span> Website Orders
    </h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="page-title-action">Create New Order Form</a>
        
        <p class="description">Create order forms and share with customers. Customers can select a website template, fill their content, and submit.</p>
        
        <?php 
        $forms = $forms_handler->get_all(array('per_page' => 50));
        ?>
        
        <div class="forms-container">
            <?php if (empty($forms)): ?>
                <div class="empty-state">
                    <span class="dashicons dashicons-media-document"></span>
                    <h3>No Order Forms Yet</h3>
                    <p>Create your first order form to let customers select and customize website templates.</p>
                    <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="button button-primary button-hero">Create Order Form</a>
                </div>
            <?php else: ?>
                <div class="forms-grid">
                    <?php foreach ($forms as $f): 
                        $theme_ids = json_decode($f->available_themes, true) ?: array();
                        $submission_count = $forms_handler->get_submission_count($f->id);
                        $share_count = $forms_handler->get_share_count($f->id);
                    ?>
                        <div class="form-card">
                            <div class="form-card-header">
                                <h3><?php echo esc_html($f->name); ?></h3>
                                <span class="status-badge <?php echo $f->status ? 'active' : 'inactive'; ?>">
                                    <?php echo $f->status ? 'Active' : 'Inactive'; ?>
                                </span>
                            </div>
                            
                            <div class="form-card-body">
                                <?php if ($f->description): ?>
                                    <p class="form-description"><?php echo esc_html($f->description); ?></p>
                                <?php endif; ?>
                                
                                <div class="form-stats">
                                    <div class="stat-item">
                                        <span class="stat-icon"><span class="dashicons dashicons-admin-appearance"></span></span>
                                        <span class="stat-value"><?php echo count($theme_ids); ?></span>
                                        <span class="stat-label">Templates</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-icon"><span class="dashicons dashicons-share"></span></span>
                                        <span class="stat-value"><?php echo $share_count; ?></span>
                                        <span class="stat-label">Shares</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-icon"><span class="dashicons dashicons-text-page"></span></span>
                                        <span class="stat-value"><?php echo $submission_count; ?></span>
                                        <span class="stat-label">Submissions</span>
                                    </div>
                                </div>
                                
                                <?php if (!empty($theme_ids)): ?>
                                    <div class="selected-themes-preview">
                                        <?php 
                                        $selected_themes = $themes_handler->get_by_ids($theme_ids);
                                        foreach (array_slice($selected_themes, 0, 4) as $t): 
                                        ?>
                                            <div class="theme-mini" style="background: linear-gradient(135deg, <?php echo esc_attr($t->primary_color); ?> 0%, <?php echo esc_attr($t->secondary_color); ?> 100%);" title="<?php echo esc_attr($t->name); ?>"></div>
                                        <?php endforeach; ?>
                                        <?php if (count($theme_ids) > 4): ?>
                                            <span class="more-themes">+<?php echo count($theme_ids) - 4; ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-card-actions">
                                <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=share&id=' . $f->id); ?>" class="button button-primary">
                                    <span class="dashicons dashicons-share"></span> Share
                                </a>
                                <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=edit&id=' . $f->id); ?>" class="button">
                                    <span class="dashicons dashicons-edit"></span> Edit
                                </a>
                                <button class="button button-link-delete delete-form" data-id="<?php echo $f->id; ?>">
                                    <span class="dashicons dashicons-trash"></span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
    <?php elseif ($action === 'share' && $form): ?>
        <!-- Share Form View -->
        <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="page-title-action">← Back to Forms</a>
        
        <div class="share-container">
            <div class="share-header">
                <div class="share-header-content">
                    <h2><?php echo esc_html($form->name); ?></h2>
                    <p>Share this order form with customers via email, WhatsApp, or copy the link.</p>
                </div>
            </div>
            
            <div class="share-methods">
                <!-- Generate Link -->
                <div class="share-method-card">
                    <div class="method-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                        <span class="dashicons dashicons-admin-links"></span>
                    </div>
                    <h3>Share Link</h3>
                    <p>Generate a unique link to share with your customer</p>
                    
                    <div class="link-generator">
                        <button type="button" class="button button-primary" id="generate-link">Generate Link</button>
                        <div class="generated-link" style="display:none;">
                            <input type="text" id="share-link" readonly>
                            <button type="button" class="button copy-link">
                                <span class="dashicons dashicons-clipboard"></span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Email Share -->
                <div class="share-method-card">
                    <div class="method-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <span class="dashicons dashicons-email"></span>
                    </div>
                    <h3>Send via Email</h3>
                    <p>Send the form link directly to customer's email</p>
                    
                    <form id="email-share-form" class="share-form">
                        <div class="form-field">
                            <label>Select Customer or Enter Email</label>
                            <select name="customer_id" id="email-customer-select" class="customer-select">
                                <option value="">-- Select Customer --</option>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?php echo $c->id; ?>" data-email="<?php echo esc_attr($c->email); ?>" data-phone="<?php echo esc_attr($c->phone); ?>">
                                        <?php echo esc_html($c->name); ?> (<?php echo esc_html($c->email); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Email Address</label>
                            <input type="email" name="email" id="share-email" required placeholder="customer@example.com">
                        </div>
                        <button type="submit" class="button button-primary">
                            <span class="dashicons dashicons-email-alt"></span> Send Email
                        </button>
                    </form>
                </div>
                
                <!-- WhatsApp Share -->
                <div class="share-method-card">
                    <div class="method-icon" style="background: linear-gradient(135deg, #25d366, #128c7e);">
                        <span class="dashicons dashicons-smartphone"></span>
                    </div>
                    <h3>Send via WhatsApp/SMS</h3>
                    <p>Send the form link via SMS or WhatsApp</p>
                    
                    <form id="sms-share-form" class="share-form">
                        <div class="form-field">
                            <label>Select Customer or Enter Phone</label>
                            <select name="customer_id" id="sms-customer-select" class="customer-select">
                                <option value="">-- Select Customer --</option>
                                <?php foreach ($customers as $c): ?>
                                    <?php if (!empty($c->phone)): ?>
                                    <option value="<?php echo $c->id; ?>" data-email="<?php echo esc_attr($c->email); ?>" data-phone="<?php echo esc_attr($c->phone); ?>">
                                        <?php echo esc_html($c->name); ?> (<?php echo esc_html($c->phone); ?>)
                                    </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" id="share-phone" required placeholder="+1234567890">
                        </div>
                        <button type="submit" class="button button-primary">
                            <span class="dashicons dashicons-smartphone"></span> Send SMS
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Share History -->
            <div class="share-history">
                <h3><span class="dashicons dashicons-backup"></span> Share History</h3>
                <?php
                global $wpdb;
                $shares = $wpdb->get_results($wpdb->prepare(
                    "SELECT s.*, c.name as customer_name 
                     FROM {$wpdb->prefix}spfm_form_shares s 
                     LEFT JOIN {$wpdb->prefix}spfm_customers c ON s.customer_id = c.id 
                     WHERE s.form_id = %d 
                     ORDER BY s.created_at DESC LIMIT 20",
                    $form->id
                ));
                ?>
                
                <?php if (empty($shares)): ?>
                    <p class="no-history">No shares yet. Share this form to see history.</p>
                <?php else: ?>
                    <table class="wp-list-table widefat striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Shared To</th>
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
                                    <td>
                                        <?php if ($s->customer_name): ?>
                                            <strong><?php echo esc_html($s->customer_name); ?></strong><br>
                                        <?php endif; ?>
                                        <small><?php echo esc_html($s->shared_to); ?></small>
                                    </td>
                                    <td>
                                        <?php 
                                        $method_icons = array('link' => 'admin-links', 'email' => 'email', 'sms' => 'smartphone', 'whatsapp' => 'smartphone');
                                        ?>
                                        <span class="dashicons dashicons-<?php echo $method_icons[$s->shared_via] ?? 'admin-links'; ?>"></span>
                                        <?php echo ucfirst($s->shared_via); ?>
                                    </td>
                                    <td><?php echo $s->views; ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $s->status; ?>">
                                            <?php echo ucfirst($s->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo home_url('/spfm-form/' . $s->token . '/'); ?>" target="_blank" class="button button-small">
                                            <span class="dashicons dashicons-external"></span>
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
        <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="page-title-action">← Back to Forms</a>
        
        <?php $selected_themes = $form ? (json_decode($form->available_themes, true) ?: array()) : array(); ?>
        
        <div class="form-editor-container">
            <form id="spfm-form-editor">
                <input type="hidden" name="id" value="<?php echo $form ? $form->id : ''; ?>">
                
                <div class="editor-main">
                    <div class="editor-section">
                        <h3>Basic Information</h3>
                        
                        <div class="form-field">
                            <label for="name">Form Name *</label>
                            <input type="text" name="name" id="name" required 
                                   value="<?php echo $form ? esc_attr($form->name) : ''; ?>" 
                                   placeholder="e.g., Website Order Form">
                        </div>
                        
                        <div class="form-field">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="3" 
                                      placeholder="Describe what this form is for..."><?php echo $form ? esc_textarea($form->description) : ''; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="editor-section">
                        <h3><span class="dashicons dashicons-admin-appearance"></span> Select Website Templates</h3>
                        <p class="section-description">Choose which templates customers can select from. Click to toggle selection.</p>
                        
                        <div class="templates-selector">
                            <?php foreach ($available_themes as $theme): 
                                $is_selected = in_array($theme->id, $selected_themes);
                            ?>
                                <div class="template-select-card <?php echo $is_selected ? 'selected' : ''; ?>" 
                                     data-theme-id="<?php echo $theme->id; ?>">
                                    <div class="select-indicator">
                                        <span class="dashicons dashicons-yes-alt"></span>
                                    </div>
                                    <div class="template-preview-mini" style="background: linear-gradient(135deg, <?php echo esc_attr($theme->primary_color); ?> 0%, <?php echo esc_attr($theme->secondary_color); ?> 100%);"></div>
                                    <div class="template-select-info">
                                        <h4><?php echo esc_html($theme->name); ?></h4>
                                        <span class="category"><?php echo esc_html($themes_handler->get_categories()[$theme->category] ?? $theme->category); ?></span>
                                        <span class="pages-count"><?php echo count($themes_handler->get_theme_pages($theme->id)); ?> pages</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <input type="hidden" name="available_themes" id="available-themes" 
                               value="<?php echo esc_attr(json_encode($selected_themes)); ?>">
                    </div>
                    
                    <div class="editor-section">
                        <h3>Customer Options</h3>
                        
                        <div class="form-field checkbox-field">
                            <label>
                                <input type="checkbox" name="allow_theme_selection" value="1" 
                                       <?php checked($form ? $form->allow_theme_selection : 1, 1); ?>>
                                Allow customer to select from multiple templates
                            </label>
                            <p class="field-hint">If unchecked, customer will only see the first selected template.</p>
                        </div>
                        
                        <div class="form-field checkbox-field">
                            <label>
                                <input type="checkbox" name="allow_color_customization" value="1" 
                                       <?php checked($form ? $form->allow_color_customization : 1, 1); ?>>
                                Allow customer to customize colors
                            </label>
                            <p class="field-hint">Customer can change primary, secondary, and accent colors.</p>
                        </div>
                        
                        <div class="form-field checkbox-field">
                            <label>
                                <input type="checkbox" name="notify_admin" value="1" 
                                       <?php checked($form ? $form->notify_admin : 1, 1); ?>>
                                Send email notification on submission
                            </label>
                        </div>
                    </div>
                    
                    <div class="editor-section">
                        <h3>Messages</h3>
                        
                        <div class="form-field">
                            <label for="header_text">Header Text</label>
                            <input type="text" name="header_text" id="header_text" 
                                   value="<?php echo $form ? esc_attr($form->header_text) : 'Choose Your Website Template'; ?>" 
                                   placeholder="Choose Your Website Template">
                        </div>
                        
                        <div class="form-field">
                            <label for="success_message">Success Message</label>
                            <textarea name="success_message" id="success_message" rows="2" 
                                      placeholder="Thank you! Your website order has been submitted successfully."><?php echo $form ? esc_textarea($form->success_message) : 'Thank you! Your website order has been submitted successfully. We will contact you shortly.'; ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="editor-sidebar">
                    <div class="sidebar-card">
                        <h4>Publish</h4>
                        <div class="form-field">
                            <label>Status</label>
                            <select name="status">
                                <option value="1" <?php selected($form ? $form->status : 1, 1); ?>>Active</option>
                                <option value="0" <?php selected($form ? $form->status : 1, 0); ?>>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="button button-primary button-large" style="width:100%;">
                            <?php echo $form ? 'Update Form' : 'Create Form'; ?>
                        </button>
                    </div>
                    
                    <div class="sidebar-card">
                        <h4>Selected Templates</h4>
                        <div id="selected-count">
                            <span class="count"><?php echo count($selected_themes); ?></span> templates selected
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
/* Forms Grid */
.forms-container {
    margin-top: 20px;
}
.forms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
}
.form-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.form-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
}
.form-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.form-card-header h3 {
    margin: 0;
    font-size: 18px;
    color: #fff;
}
.status-badge {
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 11px;
}
.status-badge.active {
    background: rgba(255,255,255,0.2);
    color: #fff;
}
.status-badge.inactive {
    background: rgba(0,0,0,0.2);
    color: #fff;
}
.form-card-body {
    padding: 20px;
}
.form-description {
    color: #666;
    font-size: 14px;
    margin-bottom: 15px;
}
.form-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}
.stat-item {
    text-align: center;
}
.stat-icon {
    display: block;
    color: #667eea;
}
.stat-value {
    display: block;
    font-size: 20px;
    font-weight: 700;
    color: #333;
}
.stat-label {
    font-size: 11px;
    color: #999;
}
.selected-themes-preview {
    display: flex;
    gap: 8px;
    align-items: center;
}
.theme-mini {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
}
.more-themes {
    font-size: 12px;
    color: #666;
    background: #f0f0f0;
    padding: 8px 12px;
    border-radius: 8px;
}
.form-card-actions {
    display: flex;
    gap: 8px;
    padding: 15px 20px;
    background: #f8f9fa;
    border-top: 1px solid #eee;
}
.form-card-actions .button {
    display: flex;
    align-items: center;
    gap: 5px;
}
.form-card-actions .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

/* Share Container */
.share-container {
    margin-top: 20px;
}
.share-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 30px;
    border-radius: 12px;
    color: #fff;
    margin-bottom: 25px;
}
.share-header h2 {
    margin: 0 0 5px 0;
    color: #fff;
}
.share-header p {
    margin: 0;
    opacity: 0.9;
}
.share-methods {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin-bottom: 30px;
}
.share-method-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}
.method-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}
.method-icon .dashicons {
    color: #fff;
    font-size: 28px;
    width: 28px;
    height: 28px;
}
.share-method-card h3 {
    margin: 0 0 8px 0;
}
.share-method-card > p {
    color: #666;
    font-size: 14px;
    margin-bottom: 20px;
}
.share-form .form-field {
    margin-bottom: 15px;
}
.share-form label {
    display: block;
    margin-bottom: 5px;
    font-size: 13px;
    font-weight: 500;
}
.share-form input, .share-form select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}
.link-generator .generated-link {
    display: flex;
    gap: 5px;
    margin-top: 10px;
}
.link-generator input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: #f8f9fa;
}
.share-history {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}
.share-history h3 {
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.no-history {
    color: #999;
    text-align: center;
    padding: 30px;
}

/* Form Editor */
.form-editor-container {
    margin-top: 20px;
}
.form-editor-container form {
    display: flex;
    gap: 25px;
}
.editor-main {
    flex: 1;
}
.editor-section {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.editor-section h3 {
    margin: 0 0 20px 0;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    gap: 10px;
}
.section-description {
    color: #666;
    margin: -10px 0 20px 0;
}
.form-field {
    margin-bottom: 20px;
}
.form-field label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}
.form-field input[type="text"],
.form-field input[type="email"],
.form-field textarea,
.form-field select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.form-field input:focus,
.form-field textarea:focus,
.form-field select:focus {
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
.field-hint {
    margin: 5px 0 0 28px;
    color: #999;
    font-size: 12px;
}

/* Templates Selector */
.templates-selector {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}
.template-select-card {
    position: relative;
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.2s;
}
.template-select-card:hover {
    border-color: #667eea;
}
.template-select-card.selected {
    border-color: #667eea;
    background: #f0f4ff;
}
.select-indicator {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 24px;
    height: 24px;
    background: #e0e0e0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.template-select-card.selected .select-indicator {
    background: #667eea;
}
.select-indicator .dashicons {
    color: #fff;
    font-size: 16px;
    width: 16px;
    height: 16px;
    display: none;
}
.template-select-card.selected .select-indicator .dashicons {
    display: block;
}
.template-preview-mini {
    height: 80px;
    border-radius: 6px;
    margin-bottom: 10px;
}
.template-select-info h4 {
    margin: 0 0 5px 0;
    font-size: 14px;
}
.template-select-info .category {
    font-size: 11px;
    background: #e9ecef;
    padding: 2px 8px;
    border-radius: 10px;
    color: #666;
}
.template-select-info .pages-count {
    font-size: 11px;
    color: #999;
    margin-left: 5px;
}

/* Editor Sidebar */
.editor-sidebar {
    width: 280px;
    flex-shrink: 0;
}
.sidebar-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.sidebar-card h4 {
    margin: 0 0 15px 0;
    font-size: 14px;
    color: #666;
    text-transform: uppercase;
}
#selected-count {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}
#selected-count .count {
    font-size: 28px;
    font-weight: 700;
    color: #667eea;
    display: block;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 12px;
}
.empty-state .dashicons {
    font-size: 60px;
    width: 60px;
    height: 60px;
    color: #ddd;
    margin-bottom: 15px;
}

@media (max-width: 1200px) {
    .share-methods {
        grid-template-columns: 1fr;
    }
    .form-editor-container form {
        flex-direction: column;
    }
    .editor-sidebar {
        width: 100%;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    var formId = <?php echo $form ? $form->id : 0; ?>;
    
    // Template selection
    $('.template-select-card').on('click', function() {
        $(this).toggleClass('selected');
        updateSelectedThemes();
    });
    
    function updateSelectedThemes() {
        var selected = [];
        $('.template-select-card.selected').each(function() {
            selected.push($(this).data('theme-id'));
        });
        $('#available-themes').val(JSON.stringify(selected));
        $('#selected-count .count').text(selected.length);
    }
    
    // Customer select auto-fill
    $('.customer-select').on('change', function() {
        var option = $(this).find('option:selected');
        var email = option.data('email');
        var phone = option.data('phone');
        
        if ($(this).attr('id') === 'email-customer-select') {
            $('#share-email').val(email || '');
        } else {
            $('#share-phone').val(phone || '');
        }
    });
    
    // Generate link
    $('#generate-link').on('click', function() {
        var $btn = $(this);
        $btn.text('Generating...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_get_share_link',
            nonce: spfm_ajax.nonce,
            form_id: formId
        }, function(response) {
            if (response.success) {
                $('#share-link').val(response.data.url);
                $('.generated-link').show();
                $btn.hide();
            }
            $btn.text('Generate Link').prop('disabled', false);
        });
    });
    
    // Copy link
    $('.copy-link').on('click', function() {
        $('#share-link').select();
        document.execCommand('copy');
        $(this).html('<span class="dashicons dashicons-yes"></span>');
        setTimeout(function() {
            $('.copy-link').html('<span class="dashicons dashicons-clipboard"></span>');
        }, 2000);
    });
    
    // Email share
    $('#email-share-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        $btn.text('Sending...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_share_form',
            nonce: spfm_ajax.nonce,
            form_id: formId,
            method: 'email',
            email: $('#share-email').val(),
            customer_id: $('#email-customer-select').val()
        }, function(response) {
            alert(response.data.message);
            $btn.html('<span class="dashicons dashicons-email-alt"></span> Send Email').prop('disabled', false);
            if (response.success) {
                location.reload();
            }
        });
    });
    
    // SMS share
    $('#sms-share-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        $btn.text('Sending...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_share_form',
            nonce: spfm_ajax.nonce,
            form_id: formId,
            method: 'sms',
            phone: $('#share-phone').val(),
            customer_id: $('#sms-customer-select').val()
        }, function(response) {
            alert(response.data.message);
            $btn.html('<span class="dashicons dashicons-smartphone"></span> Send SMS').prop('disabled', false);
            if (response.success) {
                location.reload();
            }
        });
    });
    
    // Save form
    $('#spfm-form-editor').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        var selectedThemes = [];
        $('.template-select-card.selected').each(function() {
            selectedThemes.push($(this).data('theme-id'));
        });
        
        if (selectedThemes.length === 0) {
            alert('Please select at least one website template.');
            return;
        }
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_form&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-forms'); ?>';
            } else {
                alert(response.data.message);
                $btn.text('Save Form').prop('disabled', false);
            }
        });
    });
    
    // Delete form
    $('.delete-form').on('click', function() {
        if (!confirm('Are you sure you want to delete this form?')) return;
        
        var id = $(this).data('id');
        var $card = $(this).closest('.form-card');
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_form',
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
