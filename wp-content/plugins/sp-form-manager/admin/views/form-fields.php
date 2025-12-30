<?php
if (!defined('ABSPATH')) {
    exit;
}

$forms_handler = SPFM_Forms::get_instance();
$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;

if (!$form_id) {
    echo '<div class="wrap"><h1>Form Not Found</h1><p>Please select a valid form.</p></div>';
    return;
}

$form = $forms_handler->get_by_id($form_id);
if (!$form) {
    echo '<div class="wrap"><h1>Form Not Found</h1><p>The requested form does not exist.</p></div>';
    return;
}

$fields = $forms_handler->get_fields($form_id);
$field_types = $forms_handler->get_field_types();

$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : 0;
$edit_field = null;

if ($action === 'edit' && $field_id) {
    $edit_field = $forms_handler->get_field_by_id($field_id);
}
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        Form Fields: <?php echo esc_html($form->name); ?>
    </h1>
    <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="page-title-action">Back to Forms</a>
    <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=share&id=' . $form_id); ?>" class="page-title-action button-primary" style="background:#667eea;border-color:#667eea;">Share Form</a>
    
    <div class="spfm-fields-container">
        <div class="fields-sidebar">
            <div class="sidebar-section">
                <h3><span class="dashicons dashicons-plus-alt2"></span> Add New Field</h3>
                <form id="spfm-field-form" class="field-form">
                    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                    <input type="hidden" name="id" id="field_id" value="<?php echo $edit_field ? $edit_field->id : ''; ?>">
                    
                    <div class="form-field">
                        <label for="field_label">Field Label *</label>
                        <input type="text" name="field_label" id="field_label" required value="<?php echo $edit_field ? esc_attr($edit_field->field_label) : ''; ?>">
                    </div>
                    
                    <div class="form-field">
                        <label for="field_name">Field Name *</label>
                        <input type="text" name="field_name" id="field_name" required value="<?php echo $edit_field ? esc_attr($edit_field->field_name) : ''; ?>" placeholder="auto-generated">
                    </div>
                    
                    <div class="form-field">
                        <label for="field_type">Field Type *</label>
                        <select name="field_type" id="field_type" required>
                            <?php foreach ($field_types as $value => $label): ?>
                                <option value="<?php echo esc_attr($value); ?>" <?php selected($edit_field ? $edit_field->field_type : 'text', $value); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-field field-options-group" style="<?php echo ($edit_field && in_array($edit_field->field_type, array('select', 'radio', 'checkbox', 'paragraph'))) ? '' : 'display:none;'; ?>">
                        <label for="field_options">Options (one per line)</label>
                        <textarea name="field_options" id="field_options" rows="4" placeholder="Option 1&#10;Option 2&#10;Option 3"><?php echo $edit_field ? esc_textarea($edit_field->field_options) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-field">
                        <label for="placeholder">Placeholder</label>
                        <input type="text" name="placeholder" id="placeholder" value="<?php echo $edit_field ? esc_attr($edit_field->placeholder) : ''; ?>">
                    </div>
                    
                    <div class="form-field">
                        <label for="default_value">Default Value</label>
                        <input type="text" name="default_value" id="default_value" value="<?php echo $edit_field ? esc_attr($edit_field->default_value) : ''; ?>">
                    </div>
                    
                    <div class="form-field">
                        <label for="css_class">CSS Class</label>
                        <input type="text" name="css_class" id="css_class" value="<?php echo $edit_field ? esc_attr($edit_field->css_class) : ''; ?>" placeholder="Optional custom CSS class">
                    </div>
                    
                    <div class="form-field">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_required" value="1" <?php checked($edit_field ? $edit_field->is_required : 0, 1); ?>>
                            Required Field
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="button button-primary">
                            <?php echo $edit_field ? 'Update Field' : 'Add Field'; ?>
                        </button>
                        <?php if ($edit_field): ?>
                            <a href="<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $form_id); ?>" class="button">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <div class="sidebar-section">
                <h3><span class="dashicons dashicons-info"></span> Form Info</h3>
                <div class="form-info">
                    <p><strong>Form ID:</strong> <?php echo $form->id; ?></p>
                    <p><strong>Status:</strong> 
                        <span class="status-badge <?php echo $form->status ? 'active' : 'inactive'; ?>">
                            <?php echo $form->status ? 'Active' : 'Inactive'; ?>
                        </span>
                    </p>
                    <p><strong>Fields:</strong> <?php echo count($fields); ?></p>
                </div>
                
                <div class="shortcode-info">
                    <label>Shortcode:</label>
                    <div class="shortcode-box">
                        <code id="shortcode-text">[spfm_form id="<?php echo $form->id; ?>"]</code>
                        <button type="button" class="button button-small copy-shortcode" title="Copy">
                            <span class="dashicons dashicons-clipboard"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="fields-main">
            <div class="fields-header">
                <h3><span class="dashicons dashicons-list-view"></span> Form Fields</h3>
                <p class="description">Drag and drop to reorder fields</p>
            </div>
            
            <?php if (empty($fields)): ?>
                <div class="empty-fields">
                    <span class="dashicons dashicons-welcome-add-page"></span>
                    <p>No fields yet. Add your first field using the form on the left.</p>
                </div>
            <?php else: ?>
                <ul id="sortable-fields" class="fields-list">
                    <?php foreach ($fields as $field): ?>
                        <li class="field-item" data-id="<?php echo $field->id; ?>">
                            <div class="field-handle">
                                <span class="dashicons dashicons-menu"></span>
                            </div>
                            <div class="field-info">
                                <span class="field-label"><?php echo esc_html($field->field_label); ?></span>
                                <span class="field-meta">
                                    <span class="field-type"><?php echo esc_html($field_types[$field->field_type] ?? $field->field_type); ?></span>
                                    <?php if ($field->is_required): ?>
                                        <span class="field-required">Required</span>
                                    <?php endif; ?>
                                    <span class="field-name"><?php echo esc_html($field->field_name); ?></span>
                                </span>
                            </div>
                            <div class="field-actions">
                                <a href="<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $form_id . '&action=edit&field_id=' . $field->id); ?>" class="button button-small" title="Edit">
                                    <span class="dashicons dashicons-edit"></span>
                                </a>
                                <button class="button button-small button-link-delete delete-field" data-id="<?php echo $field->id; ?>" title="Delete">
                                    <span class="dashicons dashicons-trash"></span>
                                </button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            
            <div class="fields-footer">
                <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=edit&id=' . $form_id); ?>" class="button">
                    <span class="dashicons dashicons-admin-settings"></span> Form Settings
                </a>
                <a href="<?php echo admin_url('admin.php?page=spfm-forms&action=share&id=' . $form_id); ?>" class="button button-primary">
                    <span class="dashicons dashicons-share"></span> Share Form
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.spfm-fields-container {
    display: flex;
    gap: 25px;
    margin-top: 20px;
}
.fields-sidebar {
    width: 350px;
    flex-shrink: 0;
}
.sidebar-section {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}
.sidebar-section h3 {
    margin: 0 0 20px 0;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
}
.field-form .form-field {
    margin-bottom: 15px;
}
.field-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    font-size: 13px;
}
.field-form input[type="text"],
.field-form select,
.field-form textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.field-form input:focus,
.field-form select:focus,
.field-form textarea:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 2px rgba(102,126,234,0.1);
}
.checkbox-label {
    display: flex !important;
    align-items: center;
    gap: 8px;
    font-weight: normal !important;
    cursor: pointer;
}
.checkbox-label input {
    width: 18px;
    height: 18px;
}
.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}
.form-info p {
    margin: 8px 0;
    font-size: 13px;
}
.status-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
}
.status-badge.active {
    background: #d4edda;
    color: #155724;
}
.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}
.shortcode-info {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.shortcode-info label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 13px;
}
.shortcode-box {
    display: flex;
    gap: 5px;
    align-items: center;
}
.shortcode-box code {
    flex: 1;
    background: #f0f0f0;
    padding: 8px 12px;
    border-radius: 5px;
    font-size: 12px;
}
.fields-main {
    flex: 1;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
}
.fields-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
.fields-header h3 {
    margin: 0 0 5px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.fields-header .description {
    margin: 0;
    color: #666;
}
.empty-fields {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}
.empty-fields .dashicons {
    font-size: 50px;
    width: 50px;
    height: 50px;
    margin-bottom: 15px;
}
.fields-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
.field-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border: 1px solid #eee;
    border-radius: 8px;
    margin-bottom: 10px;
    transition: all 0.2s;
}
.field-item:hover {
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.field-item.ui-sortable-helper {
    background: #fff;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}
.field-handle {
    cursor: move;
    color: #ccc;
}
.field-handle:hover {
    color: #667eea;
}
.field-info {
    flex: 1;
}
.field-label {
    display: block;
    font-weight: 600;
    margin-bottom: 3px;
}
.field-meta {
    display: flex;
    gap: 10px;
    font-size: 12px;
    color: #999;
}
.field-type {
    background: #e9ecef;
    padding: 2px 8px;
    border-radius: 3px;
}
.field-required {
    background: #fff3cd;
    color: #856404;
    padding: 2px 8px;
    border-radius: 3px;
}
.field-actions {
    display: flex;
    gap: 5px;
}
.field-actions .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}
.fields-footer {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}
.fields-footer .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
    vertical-align: middle;
    margin-right: 3px;
}
@media (max-width: 1200px) {
    .spfm-fields-container {
        flex-direction: column;
    }
    .fields-sidebar {
        width: 100%;
    }
}
</style>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
jQuery(document).ready(function($) {
    // Auto-generate field name from label
    $('#field_label').on('input', function() {
        var label = $(this).val();
        var name = label.toLowerCase()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_+|_+$/g, '');
        
        if (!$('#field_id').val()) { // Only auto-fill for new fields
            $('#field_name').val(name);
        }
    });
    
    // Show/hide options field based on field type
    $('#field_type').on('change', function() {
        var type = $(this).val();
        var needsOptions = ['select', 'radio', 'checkbox', 'paragraph'];
        
        if (needsOptions.indexOf(type) !== -1) {
            $('.field-options-group').show();
        } else {
            $('.field-options-group').hide();
        }
    });
    
    // Sortable fields
    $('#sortable-fields').sortable({
        handle: '.field-handle',
        placeholder: 'field-placeholder',
        update: function(event, ui) {
            var orders = {};
            $('#sortable-fields .field-item').each(function(index) {
                orders[$(this).data('id')] = index;
            });
            
            $.post(spfm_ajax.ajax_url, {
                action: 'spfm_reorder_fields',
                nonce: spfm_ajax.nonce,
                form_id: <?php echo $form_id; ?>,
                orders: orders
            });
        }
    });
    
    // Copy shortcode
    $('.copy-shortcode').on('click', function() {
        var text = $('#shortcode-text').text();
        navigator.clipboard.writeText(text);
        $(this).html('<span class="dashicons dashicons-yes"></span>');
        setTimeout(function() {
            $('.copy-shortcode').html('<span class="dashicons dashicons-clipboard"></span>');
        }, 2000);
    });
    
    // Save field
    $('#spfm-field-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_field&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                location.href = '<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $form_id); ?>';
            } else {
                alert(response.data.message);
                $btn.text('Save Field').prop('disabled', false);
            }
        });
    });
    
    // Delete field
    $('.delete-field').on('click', function() {
        if (!confirm('Are you sure you want to delete this field?')) return;
        
        var id = $(this).data('id');
        var $item = $(this).closest('.field-item');
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_field',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                $item.fadeOut(300, function() {
                    $(this).remove();
                });
            } else {
                alert(response.data.message);
            }
        });
    });
});
</script>
