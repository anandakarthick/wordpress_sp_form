/**
 * SP Form Manager - Frontend JavaScript
 */

(function($) {
    'use strict';
    
    // Form Submission Handler
    $(document).on('submit', '.spfm-form', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $wrapper = $form.closest('.spfm-form-wrapper');
        var $submitBtn = $form.find('.spfm-submit-btn');
        var originalText = $submitBtn.text();
        
        // Add loading state
        $form.addClass('loading');
        $submitBtn.text('Submitting...').prop('disabled', true);
        
        // Remove any existing messages
        $wrapper.find('.spfm-message').remove();
        
        // Prepare form data
        var formData = new FormData($form[0]);
        formData.append('action', 'spfm_submit_form');
        
        // Submit via AJAX
        $.ajax({
            url: spfm_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $form.removeClass('loading');
                $submitBtn.text(originalText).prop('disabled', false);
                
                if (response.success) {
                    // Show success message
                    $wrapper.prepend(
                        '<div class="spfm-message spfm-message-success">' +
                        '<strong>Success!</strong> ' + response.data.message +
                        '</div>'
                    );
                    
                    // Reset form
                    $form[0].reset();
                    
                    // Scroll to message
                    $('html, body').animate({
                        scrollTop: $wrapper.offset().top - 50
                    }, 300);
                    
                    // Auto-hide message after 5 seconds
                    setTimeout(function() {
                        $wrapper.find('.spfm-message-success').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }, 5000);
                    
                } else {
                    // Show error message
                    $wrapper.prepend(
                        '<div class="spfm-message spfm-message-error">' +
                        '<strong>Error!</strong> ' + response.data.message +
                        '</div>'
                    );
                }
            },
            error: function() {
                $form.removeClass('loading');
                $submitBtn.text(originalText).prop('disabled', false);
                
                $wrapper.prepend(
                    '<div class="spfm-message spfm-message-error">' +
                    '<strong>Error!</strong> An unexpected error occurred. Please try again.' +
                    '</div>'
                );
            }
        });
    });
    
    // Real-time validation
    $(document).on('blur', '.spfm-form input[required], .spfm-form select[required], .spfm-form textarea[required]', function() {
        var $field = $(this);
        var $group = $field.closest('.spfm-form-group');
        
        if (!$field.val()) {
            $group.addClass('has-error');
            $field.css('border-color', '#dc3545');
        } else {
            $group.removeClass('has-error');
            $field.css('border-color', '');
        }
    });
    
    // Email validation
    $(document).on('blur', '.spfm-form input[type="email"]', function() {
        var $field = $(this);
        var email = $field.val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $field.css('border-color', '#dc3545');
        } else {
            $field.css('border-color', '');
        }
    });
    
    // Phone formatting
    $(document).on('input', '.spfm-form input[type="tel"]', function() {
        var $field = $(this);
        var value = $field.val().replace(/\D/g, '');
        
        // Basic US phone formatting
        if (value.length >= 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
        } else if (value.length >= 3) {
            value = value.replace(/(\d{3})(\d{0,3})/, '($1) $2');
        }
        
        $field.val(value);
    });
    
    // Conditional field visibility (can be extended)
    function checkConditionalFields() {
        // Add conditional logic here based on form field configurations
    }
    
    $(document).on('change', '.spfm-form select, .spfm-form input[type="radio"], .spfm-form input[type="checkbox"]', checkConditionalFields);
    
})(jQuery);
