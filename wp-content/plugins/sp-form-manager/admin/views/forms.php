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

<div class="spfm-forms-wrap">
    <?php if ($action === 'list'): ?>
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <h1><span class="dashicons dashicons-feedback"></span> Forms</h1>
                <p>Create forms and share with customers. Customers can select a website template, fill their content, and submit.</p>
            </div>
            <div class="header-actions">
                <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="btn btn-white">
                    <span class="dashicons dashicons-plus-alt"></span> Create New Form
                </a>
            </div>
        </div>
        
        <?php $forms = $forms_handler->get_all(array('per_page' => 50)); ?>
        
        <div class="forms-container">
            <?php if (empty($forms)): ?>
                <div class="empty-state-card">
                    <div class="empty-icon">
                        <span class="dashicons dashicons-feedback"></span>
                    </div>
                    <h3>No Forms Yet</h3>
                    <p>Create your first form to let customers select and customize website templates.</p>
                    <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=add'); ?>" class="btn btn-primary">
                        <span class="dashicons dashicons-plus-alt"></span> Create Form
                    </a>
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
                                <div class="form-title-area">
                                    <h3><?php echo esc_html($f->name); ?></h3>
                                    <span class="status-badge <?php echo $f->status ? 'active' : 'inactive'; ?>">
                                        <?php echo $f->status ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-card-body">
                                <?php if ($f->description): ?>
                                    <p class="form-description"><?php echo esc_html($f->description); ?></p>
                                <?php endif; ?>
                                
                                <div class="form-stats">
                                    <div class="stat-item">
                                        <div class="stat-icon templates">
                                            <span class="dashicons dashicons-layout"></span>
                                        </div>
                                        <div class="stat-info">
                                            <span class="stat-value"><?php echo count($theme_ids); ?></span>
                                            <span class="stat-label">Templates</span>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon shares">
                                            <span class="dashicons dashicons-share"></span>
                                        </div>
                                        <div class="stat-info">
                                            <span class="stat-value"><?php echo $share_count; ?></span>
                                            <span class="stat-label">Shares</span>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon submissions">
                                            <span class="dashicons dashicons-portfolio"></span>
                                        </div>
                                        <div class="stat-info">
                                            <span class="stat-value"><?php echo $submission_count; ?></span>
                                            <span class="stat-label">Submissions</span>
                                        </div>
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
                                <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=share&id=' . $f->id); ?>" class="btn btn-primary btn-sm">
                                    <span class="dashicons dashicons-share"></span> Share
                                </a>
                                <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=edit&id=' . $f->id); ?>" class="btn btn-outline btn-sm">
                                    <span class="dashicons dashicons-edit"></span> Edit
                                </a>
                                <button class="btn btn-danger btn-sm delete-form" data-id="<?php echo $f->id; ?>">
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
        <div class="page-header">
            <div class="header-content">
                <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="back-link">
                    <span class="dashicons dashicons-arrow-left-alt"></span> Back to Forms
                </a>
                <h1><span class="dashicons dashicons-share"></span> Share: <?php echo esc_html($form->name); ?></h1>
                <p>Share this form with customers via email, WhatsApp, or copy the link.</p>
            </div>
        </div>
        
        <div class="share-methods">
            <!-- Generate Link -->
            <div class="share-method-card">
                <div class="method-icon link">
                    <span class="dashicons dashicons-admin-links"></span>
                </div>
                <h3>Share Link</h3>
                <p>Generate a unique link to share with your customer</p>
                
                <div class="link-generator">
                    <button type="button" class="btn btn-primary" id="generate-link">
                        <span class="dashicons dashicons-admin-links"></span> Generate Link
                    </button>
                    <div class="generated-link" style="display:none;">
                        <input type="text" id="share-link" readonly>
                        <button type="button" class="btn btn-outline copy-link">
                            <span class="dashicons dashicons-clipboard"></span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Email Share -->
            <div class="share-method-card">
                <div class="method-icon email">
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
                    <button type="submit" class="btn btn-primary">
                        <span class="dashicons dashicons-email-alt"></span> Send Email
                    </button>
                </form>
            </div>
            
            <!-- WhatsApp Share -->
            <div class="share-method-card">
                <div class="method-icon whatsapp">
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
                    <button type="submit" class="btn btn-primary">
                        <span class="dashicons dashicons-smartphone"></span> Send SMS
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Share History -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><span class="dashicons dashicons-backup"></span> Share History</h3>
            </div>
            <div class="card-body">
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
                    <div class="empty-state-small">
                        <span class="dashicons dashicons-share"></span>
                        <p>No shares yet. Share this form to see history.</p>
                    </div>
                <?php else: ?>
                    <div class="history-list">
                        <?php foreach ($shares as $s): ?>
                            <div class="history-item">
                                <div class="history-icon <?php echo $s->shared_via; ?>">
                                    <?php 
                                    $method_icons = array('link' => 'admin-links', 'email' => 'email', 'sms' => 'smartphone', 'whatsapp' => 'smartphone');
                                    ?>
                                    <span class="dashicons dashicons-<?php echo $method_icons[$s->shared_via] ?? 'admin-links'; ?>"></span>
                                </div>
                                <div class="history-info">
                                    <strong><?php echo $s->customer_name ?: esc_html($s->shared_to); ?></strong>
                                    <span class="history-meta">
                                        <?php echo ucfirst($s->shared_via); ?> • 
                                        <?php echo human_time_diff(strtotime($s->created_at)); ?> ago •
                                        <?php echo $s->views; ?> views
                                    </span>
                                </div>
                                <span class="status-badge <?php echo $s->status; ?>"><?php echo ucfirst($s->status); ?></span>
                                <a href="<?php echo home_url('/?spfm_token=' . $s->token); ?>" target="_blank" class="btn btn-outline btn-sm">
                                    <span class="dashicons dashicons-external"></span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Add/Edit Form -->
        <div class="page-header">
            <div class="header-content">
                <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="back-link">
                    <span class="dashicons dashicons-arrow-left-alt"></span> Back to Forms
                </a>
                <h1><span class="dashicons dashicons-feedback"></span> <?php echo $form ? 'Edit Form' : 'Create New Form'; ?></h1>
                <p>Set up your form and select which templates customers can choose from.</p>
            </div>
        </div>
        
        <?php $selected_themes = $form ? (json_decode($form->available_themes, true) ?: array()) : array(); ?>
        
        <div class="form-editor-container">
            <form id="spfm-form-editor">
                <input type="hidden" name="id" value="<?php echo $form ? $form->id : ''; ?>">
                
                <div class="editor-main">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><span class="dashicons dashicons-info"></span> Basic Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-field">
                                <label for="name">Form Name *</label>
                                <input type="text" name="name" id="name" required 
                                       value="<?php echo $form ? esc_attr($form->name) : ''; ?>" 
                                       placeholder="e.g., Hospital Website Form">
                            </div>
                            
                            <div class="form-field">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="3" 
                                          placeholder="Describe what this form is for..."><?php echo $form ? esc_textarea($form->description) : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><span class="dashicons dashicons-layout"></span> Select Website Templates</h3>
                        </div>
                        <div class="card-body">
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
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <input type="hidden" name="available_themes" id="available-themes" 
                                   value="<?php echo esc_attr(json_encode($selected_themes)); ?>">
                        </div>
                    </div>
                    
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><span class="dashicons dashicons-admin-settings"></span> Customer Options</h3>
                        </div>
                        <div class="card-body">
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
                    </div>
                    
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><span class="dashicons dashicons-edit"></span> Messages</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-field">
                                <label for="header_text">Header Text</label>
                                <input type="text" name="header_text" id="header_text" 
                                       value="<?php echo $form ? esc_attr($form->header_text) : 'Choose Your Website Template'; ?>" 
                                       placeholder="Choose Your Website Template">
                            </div>
                            
                            <div class="form-field">
                                <label for="success_message">Success Message</label>
                                <textarea name="success_message" id="success_message" rows="2" 
                                          placeholder="Thank you! Your submission has been received successfully."><?php echo $form ? esc_textarea($form->success_message) : 'Thank you! Your submission has been received successfully. We will contact you shortly.'; ?></textarea>
                            </div>
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
                        <button type="submit" class="btn btn-primary btn-block">
                            <span class="dashicons dashicons-saved"></span>
                            <?php echo $form ? 'Update Form' : 'Create Form'; ?>
                        </button>
                    </div>
                    
                    <div class="sidebar-card">
                        <h4>Selected Templates</h4>
                        <div id="selected-count">
                            <span class="count"><?php echo count($selected_themes); ?></span>
                            <span>templates selected</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-forms-wrap {
    padding: 20px;
    max-width: 1400px;
    font-family: 'Inter', -apple-system, sans-serif;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
.back-link:hover {
    color: #fff;
}
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
.btn-white {
    background: #fff;
    color: #10b981;
}
.btn-white:hover {
    background: #f0fdf4;
    transform: translateY(-2px);
}
.btn-primary {
    background: #10b981;
    color: #fff;
}
.btn-primary:hover {
    background: #059669;
    color: #fff;
}
.btn-outline {
    background: transparent;
    color: #10b981;
    border: 2px solid #10b981;
}
.btn-outline:hover {
    background: #10b981;
    color: #fff;
}
.btn-danger {
    background: #fee2e2;
    color: #dc2626;
}
.btn-danger:hover {
    background: #dc2626;
    color: #fff;
}
.btn-sm {
    padding: 8px 16px;
    font-size: 13px;
}
.btn-block {
    width: 100%;
    justify-content: center;
}
.btn .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

/* Forms Grid */
.forms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 25px;
}
.form-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: all 0.3s;
}
.form-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}
.form-card-header {
    padding: 20px 25px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}
.form-title-area {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.form-card-header h3 {
    margin: 0;
    font-size: 18px;
    color: #fff;
    font-weight: 600;
}
.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.status-badge.active {
    background: rgba(255,255,255,0.2);
    color: #fff;
}
.status-badge.inactive {
    background: rgba(0,0,0,0.2);
    color: #fff;
}
.status-badge.sent { background: #dbeafe; color: #2563eb; }
.status-badge.submitted { background: #dcfce7; color: #16a34a; }
.status-badge.pending { background: #fef3c7; color: #d97706; }
.status-badge.new { background: #fef3c7; color: #d97706; }

.form-card-body {
    padding: 25px;
}
.form-description {
    color: #64748b;
    font-size: 14px;
    margin: 0 0 20px 0;
    line-height: 1.5;
}
.form-stats {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}
.stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    padding: 12px;
    background: #f8fafc;
    border-radius: 10px;
}
.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-icon .dashicons {
    color: #fff;
    font-size: 18px;
    width: 18px;
    height: 18px;
}
.stat-icon.templates { background: linear-gradient(135deg, #0891b2, #0e7490); }
.stat-icon.shares { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.stat-icon.submissions { background: linear-gradient(135deg, #f59e0b, #d97706); }
.stat-info {
    display: flex;
    flex-direction: column;
}
.stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
}
.stat-label {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 3px;
}

.selected-themes-preview {
    display: flex;
    gap: 8px;
    align-items: center;
}
.theme-mini {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.more-themes {
    font-size: 12px;
    color: #64748b;
    background: #f1f5f9;
    padding: 10px 14px;
    border-radius: 10px;
    font-weight: 600;
}

.form-card-actions {
    display: flex;
    gap: 10px;
    padding: 20px 25px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

/* Share Methods */
.share-methods {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin-bottom: 30px;
}
.share-method-card {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
}
.method-icon {
    width: 70px;
    height: 70px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}
.method-icon .dashicons {
    color: #fff;
    font-size: 32px;
    width: 32px;
    height: 32px;
}
.method-icon.link { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.method-icon.email { background: linear-gradient(135deg, #10b981, #059669); }
.method-icon.whatsapp { background: linear-gradient(135deg, #25d366, #128c7e); }
.share-method-card h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    color: #1e293b;
}
.share-method-card > p {
    color: #64748b;
    font-size: 14px;
    margin: 0 0 25px 0;
}
.share-form .form-field {
    margin-bottom: 15px;
}
.share-form label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
}
.share-form input, .share-form select {
    width: 100%;
    padding: 12px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
}
.share-form input:focus, .share-form select:focus {
    border-color: #10b981;
    outline: none;
}
.link-generator .generated-link {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}
.link-generator input {
    flex: 1;
    padding: 12px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
}

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
.card-header .dashicons {
    color: #10b981;
}
.card-body {
    padding: 25px;
}

/* History List */
.history-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.history-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 12px;
}
.history-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.history-icon .dashicons {
    color: #fff;
    font-size: 20px;
    width: 20px;
    height: 20px;
}
.history-icon.link { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.history-icon.email { background: linear-gradient(135deg, #10b981, #059669); }
.history-icon.sms, .history-icon.whatsapp { background: linear-gradient(135deg, #25d366, #128c7e); }
.history-info {
    flex: 1;
}
.history-info strong {
    display: block;
    color: #1e293b;
    font-size: 14px;
}
.history-meta {
    font-size: 12px;
    color: #64748b;
}

/* Form Editor */
.form-editor-container form {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 25px;
}
.editor-main {
    display: flex;
    flex-direction: column;
    gap: 0;
}
.form-field {
    margin-bottom: 20px;
}
.form-field:last-child {
    margin-bottom: 0;
}
.form-field label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
    color: #334155;
}
.form-field input[type="text"],
.form-field input[type="email"],
.form-field textarea,
.form-field select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: border-color 0.2s;
}
.form-field input:focus,
.form-field textarea:focus,
.form-field select:focus {
    border-color: #10b981;
    outline: none;
}
.checkbox-field label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-weight: 500;
}
.checkbox-field input {
    width: 20px;
    height: 20px;
    accent-color: #10b981;
}
.field-hint {
    margin: 8px 0 0 32px;
    color: #94a3b8;
    font-size: 13px;
}
.section-description {
    color: #64748b;
    margin: 0 0 20px 0;
    font-size: 14px;
}

/* Templates Selector */
.templates-selector {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 15px;
}
.template-select-card {
    position: relative;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.2s;
}
.template-select-card:hover {
    border-color: #10b981;
    transform: translateY(-2px);
}
.template-select-card.selected {
    border-color: #10b981;
    background: #f0fdf4;
}
.select-indicator {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 26px;
    height: 26px;
    background: #e2e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.template-select-card.selected .select-indicator {
    background: #10b981;
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
    border-radius: 8px;
    margin-bottom: 12px;
}
.template-select-info h4 {
    margin: 0 0 6px 0;
    font-size: 14px;
    color: #1e293b;
}
.template-select-info .category {
    font-size: 11px;
    background: #e2e8f0;
    padding: 3px 10px;
    border-radius: 10px;
    color: #64748b;
}

/* Editor Sidebar */
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
#selected-count {
    text-align: center;
    padding: 20px;
    background: #f0fdf4;
    border-radius: 12px;
}
#selected-count .count {
    font-size: 36px;
    font-weight: 700;
    color: #10b981;
    display: block;
    line-height: 1;
    margin-bottom: 5px;
}
#selected-count span {
    font-size: 13px;
    color: #64748b;
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
    background: linear-gradient(135deg, #10b981, #059669);
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
.empty-state-small {
    text-align: center;
    padding: 40px;
    color: #94a3b8;
}
.empty-state-small .dashicons {
    font-size: 40px;
    width: 40px;
    height: 40px;
    color: #cbd5e1;
    margin-bottom: 15px;
}

/* Responsive */
@media (max-width: 1200px) {
    .share-methods {
        grid-template-columns: 1fr;
    }
    .form-editor-container form {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    .forms-grid {
        grid-template-columns: 1fr;
    }
    .form-stats {
        flex-direction: column;
    }
}
</style>

<script>
var spfm_ajax = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('spfm_nonce'); ?>'
};

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
            $btn.html('<span class="dashicons dashicons-admin-links"></span> Generate Link').prop('disabled', false);
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
                $btn.html('<span class="dashicons dashicons-saved"></span> Save Form').prop('disabled', false);
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
