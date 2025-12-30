<?php
if (!defined('ABSPATH')) {
    exit;
}

$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;

if (!$form_id) {
    wp_redirect(admin_url('admin.php?page=spfm-forms'));
    exit;
}

$forms_handler = SPFM_Forms::get_instance();
$form = $forms_handler->get_by_id($form_id);

if (!$form) {
    wp_redirect(admin_url('admin.php?page=spfm-forms'));
    exit;
}

$fields = $forms_handler->get_fields($form_id);
$field_types = $forms_handler->get_field_types();
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : 0;
$field = null;

if ($action === 'edit' && $field_id) {
    $field = $forms_handler->get_field_by_id($field_id);
}
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        Form Fields: <?php echo esc_html($form->name); ?>
    </h1>
    
    <?php if ($action === 'list'): ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $form_id . '&action=add'); ?>" class="page-title-action">Add Field</a>
        <a href="<?php echo admin_url('admin.php?page=spfm-forms'); ?>" class="page-title-action">Back to Forms</a>
        
        <div class="spfm-fields-container">
            <div class="spfm-fields-list">
                <h3>Form Fields</h3>
                <p class="description">Drag and drop to reorder fields.</p>
                
                <table class="wp-list-table widefat fixed striped" id="fields-table">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th width="25%">Label</th>
                            <th width="20%">Name</th>
                            <th width="15%">Type</th>
                            <th width="10%">Required</th>
                            <th width="10%">Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-fields">
                        <?php if (empty($fields)): ?>
                            <tr class="no-fields">
                                <td colspan="7">No fields added yet. <a href="<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $form_id . '&action=add'); ?>">Add your first field</a>.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($fields as $f): ?>
                                <tr data-id="<?php echo $f->id; ?>">
                                    <td class="drag-handle"><span class="dashicons dashicons-menu"></span></td>
                                    <td><strong><?php echo esc_html($f->field_label); ?></strong></td>
                                    <td><code><?php echo esc_html($f->field_name); ?></code></td>
                                    <td><?php echo isset($field_types[$f->field_type]) ? $field_types[$f->field_type] : $f->field_type; ?></td>
                                    <td><?php echo $f->is_required ? '<span class="dashicons dashicons-yes" style="color:green;"></span>' : '<span class="dashicons dashicons-no" style="color:#999;"></span>'; ?></td>
                                    <td>
                                        <span class="spfm-status spfm-status-<?php echo $f->status ? 'active' : 'inactive'; ?>">
                                            <?php echo $f->status ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $form_id . '&action=edit&field_id=' . $f->id); ?>" class="button button-small">Edit</a>
                                        <button type="button" class="button button-small button-link-delete spfm-delete-field" data-id="<?php echo $f->id; ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="spfm-form-preview">
                <h3>Form Preview</h3>
                <div class="preview-container">
                    <?php echo $forms_handler->render_form($form_id); ?>
                </div>
            </div>
        </div>
        
    <?php else: ?>
        <a href="<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $form_id); ?>" class="page-title-action">Back to Fields</a>
        
        <div class="spfm-form-container">
            <form id="spfm-field-form" class="spfm-admin-form">
                <input type="hidden" name="id" value="<?php echo $field ? $field->id : ''; ?>">
                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                
                <table class="form-table">
                    <tr>
                        <th><label for="field_label">Field Label <span class="required">*</span></label></th>
                        <td><input type="text" name="field_label" id="field_label" class="regular-text" required value="<?php echo $field ? esc_attr($field->field_label) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="field_name">Field Name <span class="required">*</span></label></th>
                        <td>
                            <input type="text" name="field_name" id="field_name" class="regular-text" required value="<?php echo $field ? esc_attr($field->field_name) : ''; ?>" pattern="[a-z0-9_-]+">
                            <p class="description">Lowercase letters, numbers, underscores, and hyphens only.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="field_type">Field Type <span class="required">*</span></label></th>
                        <td>
                            <select name="field_type" id="field_type" required>
                                <?php foreach ($field_types as $value => $label): ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php selected($field ? $field->field_type : 'text', $value); ?>><?php echo esc_html($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="options-row">
                        <th><label for="field_options">Options</label></th>
                        <td>
                            <textarea name="field_options" id="field_options" rows="4" class="large-text"><?php echo $field ? esc_textarea($field->field_options) : ''; ?></textarea>
                            <p class="description">Enter one option per line. For select, radio, and checkbox fields.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="placeholder">Placeholder</label></th>
                        <td><input type="text" name="placeholder" id="placeholder" class="regular-text" value="<?php echo $field ? esc_attr($field->placeholder) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="default_value">Default Value</label></th>
                        <td><input type="text" name="default_value" id="default_value" class="regular-text" value="<?php echo $field ? esc_attr($field->default_value) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="is_required">Required</label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="is_required" id="is_required" value="1" <?php checked($field ? $field->is_required : 0, 1); ?>>
                                This field is required
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="css_class">CSS Class</label></th>
                        <td><input type="text" name="css_class" id="css_class" class="regular-text" value="<?php echo $field ? esc_attr($field->css_class) : ''; ?>"></td>
                    </tr>
                    <tr>
                        <th><label for="status">Status</label></th>
                        <td>
                            <select name="status" id="status">
                                <option value="1" <?php selected($field ? $field->status : 1, 1); ?>>Active</option>
                                <option value="0" <?php selected($field ? $field->status : 1, 0); ?>>Inactive</option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php echo $field ? 'Update Field' : 'Add Field'; ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $form_id); ?>" class="button">Cancel</a>
                </p>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-fields-container {
    display: flex;
    gap: 30px;
    margin-top: 20px;
}
.spfm-fields-list {
    flex: 2;
}
.spfm-form-preview {
    flex: 1;
    background: #fff;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.drag-handle {
    cursor: move;
    color: #999;
}
.drag-handle .dashicons {
    vertical-align: middle;
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
#sortable-fields tr.ui-sortable-helper {
    background: #f0f0f0;
}
.preview-container {
    padding: 10px;
    border: 1px solid #eee;
    border-radius: 3px;
    background: #fafafa;
}
</style>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
jQuery(document).ready(function($) {
    // Show/hide options field based on type
    function toggleOptionsField() {
        var type = $('#field_type').val();
        var needsOptions = ['select', 'radio', 'checkbox', 'paragraph'].indexOf(type) !== -1;
        $('.options-row').toggle(needsOptions);
    }
    
    $('#field_type').on('change', toggleOptionsField);
    toggleOptionsField();
    
    // Auto-generate field name from label
    $('#field_label').on('input', function() {
        if (!$('#field_name').val()) {
            var name = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '_')
                .replace(/^_|_$/g, '');
            $('#field_name').val(name);
        }
    });
    
    // Sortable fields
    $('#sortable-fields').sortable({
        handle: '.drag-handle',
        update: function(event, ui) {
            var orders = {};
            $('#sortable-fields tr').each(function(index) {
                var id = $(this).data('id');
                if (id) {
                    orders[id] = index;
                }
            });
            
            $.ajax({
                url: spfm_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'spfm_reorder_fields',
                    nonce: spfm_ajax.nonce,
                    form_id: <?php echo $form_id; ?>,
                    orders: orders
                }
            });
        }
    });
    
    // Delete field
    $('.spfm-delete-field').on('click', function() {
        if (!confirm('Are you sure you want to delete this field?')) {
            return;
        }
        
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        
        $.ajax({
            url: spfm_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'spfm_delete_field',
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
    
    // Save field
    $('#spfm-field-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var originalText = $btn.text();
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=spfm_save_field&nonce=' + spfm_ajax.nonce;
        
        // Handle checkbox
        if (!$('#is_required').is(':checked')) {
            formData += '&is_required=0';
        }
        
        $.ajax({
            url: spfm_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?php echo admin_url('admin.php?page=spfm-form-fields&form_id=' . $form_id); ?>';
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
