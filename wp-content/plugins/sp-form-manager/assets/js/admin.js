/**
 * SP Form Manager - Admin JavaScript
 */

(function($) {
    'use strict';
    
    // Confirm before delete
    $(document).on('click', '.button-link-delete', function(e) {
        if (!confirm('Are you sure you want to delete this item?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Auto-generate slug from name
    $(document).on('input', '#field_label', function() {
        var $nameField = $('#field_name');
        if (!$nameField.val() || $nameField.data('auto')) {
            var slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '_')
                .replace(/^_|_$/g, '');
            $nameField.val(slug).data('auto', true);
        }
    });
    
    $(document).on('input', '#field_name', function() {
        $(this).data('auto', false);
    });
    
    // Color picker live preview for themes
    function updateThemePreview() {
        var primaryColor = $('#theme_primary_color').val();
        var secondaryColor = $('#theme_secondary_color').val();
        var backgroundColor = $('#theme_background_color').val();
        var textColor = $('#theme_text_color').val();
        var fontFamily = $('#theme_font_family').val();
        
        $('#theme-preview').css({
            'background-color': backgroundColor,
            'color': textColor,
            'font-family': fontFamily
        });
        
        $('.preview-btn-primary').css({
            'background-color': primaryColor,
            'color': '#fff'
        });
        
        $('.preview-btn-secondary').css({
            'background-color': secondaryColor,
            'color': '#fff'
        });
        
        $('.preview-field input').css({
            'border-color': primaryColor
        });
    }
    
    $(document).on('input change', '#theme_primary_color, #theme_secondary_color, #theme_background_color, #theme_text_color, #theme_font_family', updateThemePreview);
    
    // Initialize preview on page load
    if ($('#theme-preview').length) {
        updateThemePreview();
    }
    
    // Toggle field options visibility based on type
    function toggleOptionsField() {
        var type = $('#field_type').val();
        var needsOptions = ['select', 'radio', 'checkbox', 'paragraph'].indexOf(type) !== -1;
        $('.options-row').toggle(needsOptions);
    }
    
    $(document).on('change', '#field_type', toggleOptionsField);
    
    // Sortable fields (requires jQuery UI)
    if ($('#sortable-fields').length && $.fn.sortable) {
        $('#sortable-fields').sortable({
            handle: '.drag-handle',
            axis: 'y',
            update: function(event, ui) {
                var orders = {};
                $('#sortable-fields tr').each(function(index) {
                    var id = $(this).data('id');
                    if (id) {
                        orders[id] = index;
                    }
                });
                
                // Save order via AJAX
                $.ajax({
                    url: spfm_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'spfm_reorder_fields',
                        nonce: spfm_ajax.nonce,
                        form_id: $('input[name="form_id"]').val(),
                        orders: orders
                    }
                });
            }
        });
    }
    
    // Copy shortcode to clipboard
    $(document).on('click', '.spfm-shortcode-info code', function() {
        var $code = $(this);
        var text = $code.text();
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                var originalText = $code.text();
                $code.text('Copied!');
                setTimeout(function() {
                    $code.text(originalText);
                }, 1500);
            });
        }
    });
    
    // AJAX form submissions with loading state
    function submitAjaxForm($form, action, successCallback) {
        var $btn = $form.find('button[type="submit"]');
        var originalText = $btn.text();
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $form.serialize();
        formData += '&action=' + action + '&nonce=' + spfm_ajax.nonce;
        
        $.ajax({
            url: spfm_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    if (typeof successCallback === 'function') {
                        successCallback(response);
                    }
                } else {
                    alert(response.data.message || 'An error occurred.');
                    $btn.text(originalText).prop('disabled', false);
                }
            },
            error: function() {
                alert('An unexpected error occurred. Please try again.');
                $btn.text(originalText).prop('disabled', false);
            }
        });
    }
    
    // Bulk actions
    $(document).on('click', '#bulk-select-all', function() {
        var checked = $(this).prop('checked');
        $('.bulk-select-item').prop('checked', checked);
    });
    
    $(document).on('click', '#bulk-delete-btn', function() {
        var selected = $('.bulk-select-item:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (selected.length === 0) {
            alert('Please select items to delete.');
            return;
        }
        
        if (!confirm('Are you sure you want to delete ' + selected.length + ' item(s)?')) {
            return;
        }
        
        // Implement bulk delete AJAX call
    });
    
    // Form validation
    $(document).on('submit', '.spfm-admin-form', function(e) {
        var $form = $(this);
        var isValid = true;
        
        $form.find('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                $(this).css('border-color', '#dc3545');
                isValid = false;
            } else {
                $(this).css('border-color', '');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
})(jQuery);
