<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get current settings
$admin_email = get_option('spfm_admin_email', get_option('admin_email'));
$email_from_name = get_option('spfm_email_from_name', get_bloginfo('name'));
$email_from_address = get_option('spfm_email_from_address', get_option('admin_email'));
$nexmo_api_key = get_option('spfm_nexmo_api_key', '');
$nexmo_api_secret = get_option('spfm_nexmo_api_secret', '');
$nexmo_from_number = get_option('spfm_nexmo_from_number', '');
?>

<div class="wrap spfm-admin-wrap">
    <h1>Settings</h1>
    
    <div class="spfm-settings-container">
        <form id="spfm-settings-form">
            <!-- Email Settings -->
            <div class="settings-section">
                <div class="section-header">
                    <span class="section-icon" style="background: #28a745;">
                        <span class="dashicons dashicons-email"></span>
                    </span>
                    <div>
                        <h2>Email Settings</h2>
                        <p>Configure email notifications for form submissions.</p>
                    </div>
                </div>
                
                <table class="form-table">
                    <tr>
                        <th><label for="admin_email">Admin Email</label></th>
                        <td>
                            <input type="email" name="admin_email" id="admin_email" class="regular-text" 
                                   value="<?php echo esc_attr($admin_email); ?>" required>
                            <p class="description">Email address where form submission notifications will be sent.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="email_from_name">From Name</label></th>
                        <td>
                            <input type="text" name="email_from_name" id="email_from_name" class="regular-text" 
                                   value="<?php echo esc_attr($email_from_name); ?>">
                            <p class="description">Name that appears in the "From" field of sent emails.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="email_from_address">From Email</label></th>
                        <td>
                            <input type="email" name="email_from_address" id="email_from_address" class="regular-text" 
                                   value="<?php echo esc_attr($email_from_address); ?>">
                            <p class="description">Email address that appears in the "From" field.</p>
                        </td>
                    </tr>
                </table>
                
                <div class="section-actions">
                    <button type="button" class="button" id="test-email">
                        <span class="dashicons dashicons-email-alt"></span> Send Test Email
                    </button>
                </div>
            </div>
            
            <!-- Nexmo/Vonage Settings -->
            <div class="settings-section">
                <div class="section-header">
                    <span class="section-icon" style="background: #25d366;">
                        <span class="dashicons dashicons-smartphone"></span>
                    </span>
                    <div>
                        <h2>Nexmo/Vonage SMS Settings</h2>
                        <p>Configure SMS/WhatsApp notifications via Nexmo (Vonage) API.</p>
                    </div>
                </div>
                
                <div class="nexmo-info">
                    <p>
                        <strong>How to get Nexmo API credentials:</strong>
                    </p>
                    <ol>
                        <li>Sign up at <a href="https://www.vonage.com/" target="_blank">Vonage.com</a> (formerly Nexmo)</li>
                        <li>Go to your dashboard and find API settings</li>
                        <li>Copy your API Key and API Secret</li>
                        <li>Get a virtual phone number for sending SMS</li>
                    </ol>
                </div>
                
                <table class="form-table">
                    <tr>
                        <th><label for="nexmo_api_key">API Key</label></th>
                        <td>
                            <input type="text" name="nexmo_api_key" id="nexmo_api_key" class="regular-text" 
                                   value="<?php echo esc_attr($nexmo_api_key); ?>" autocomplete="off">
                            <p class="description">Your Nexmo/Vonage API Key.</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="nexmo_api_secret">API Secret</label></th>
                        <td>
                            <input type="password" name="nexmo_api_secret" id="nexmo_api_secret" class="regular-text" 
                                   value="<?php echo esc_attr($nexmo_api_secret); ?>" autocomplete="off">
                            <p class="description">Your Nexmo/Vonage API Secret.</p>
                            <button type="button" class="button button-small toggle-password">Show</button>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="nexmo_from_number">From Number</label></th>
                        <td>
                            <input type="text" name="nexmo_from_number" id="nexmo_from_number" class="regular-text" 
                                   value="<?php echo esc_attr($nexmo_from_number); ?>" placeholder="e.g., 12015551234">
                            <p class="description">Your Nexmo virtual number (without + or spaces). This is the sender number.</p>
                        </td>
                    </tr>
                </table>
                
                <div class="section-actions">
                    <button type="button" class="button" id="test-sms">
                        <span class="dashicons dashicons-smartphone"></span> Send Test SMS
                    </button>
                </div>
            </div>
            
            <!-- Save Button -->
            <p class="submit">
                <button type="submit" class="button button-primary button-large">
                    <span class="dashicons dashicons-yes"></span> Save All Settings
                </button>
            </p>
        </form>
        
        <!-- System Info -->
        <div class="settings-section">
            <div class="section-header">
                <span class="section-icon" style="background: #6c757d;">
                    <span class="dashicons dashicons-info"></span>
                </span>
                <div>
                    <h2>System Information</h2>
                    <p>Useful information for troubleshooting.</p>
                </div>
            </div>
            
            <table class="form-table system-info">
                <tr>
                    <th>Plugin Version</th>
                    <td><?php echo SPFM_VERSION; ?></td>
                </tr>
                <tr>
                    <th>WordPress Version</th>
                    <td><?php echo get_bloginfo('version'); ?></td>
                </tr>
                <tr>
                    <th>PHP Version</th>
                    <td><?php echo phpversion(); ?></td>
                </tr>
                <tr>
                    <th>Site URL</th>
                    <td><?php echo home_url(); ?></td>
                </tr>
                <tr>
                    <th>Form Share URL Format</th>
                    <td><code><?php echo home_url('/spfm-form/{token}/'); ?></code></td>
                </tr>
            </table>
            
            <div class="section-actions">
                <button type="button" class="button" id="flush-rules">
                    <span class="dashicons dashicons-update"></span> Flush Rewrite Rules
                </button>
                <button type="button" class="button" id="recreate-tables">
                    <span class="dashicons dashicons-database"></span> Recreate Database Tables
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div id="test-email-modal" class="spfm-modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Send Test Email</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-field">
                <label>Email Address</label>
                <input type="email" id="test-email-address" class="regular-text" value="<?php echo esc_attr($admin_email); ?>">
            </div>
        </div>
        <div class="modal-footer">
            <button class="button" onclick="jQuery('#test-email-modal').hide();">Cancel</button>
            <button class="button button-primary" id="send-test-email">Send Test</button>
        </div>
    </div>
</div>

<!-- Test SMS Modal -->
<div id="test-sms-modal" class="spfm-modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Send Test SMS</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-field">
                <label>Phone Number (with country code)</label>
                <input type="tel" id="test-sms-phone" class="regular-text" placeholder="+1234567890">
            </div>
        </div>
        <div class="modal-footer">
            <button class="button" onclick="jQuery('#test-sms-modal').hide();">Cancel</button>
            <button class="button button-primary" id="send-test-sms">Send Test</button>
        </div>
    </div>
</div>

<style>
.spfm-settings-container {
    max-width: 900px;
    margin-top: 20px;
}
.settings-section {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    margin-bottom: 25px;
    overflow: hidden;
}
.section-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 25px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}
.section-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.section-icon .dashicons {
    color: #fff;
    font-size: 24px;
    width: 24px;
    height: 24px;
}
.section-header h2 {
    margin: 0 0 5px 0;
    font-size: 18px;
}
.section-header p {
    margin: 0;
    color: #666;
}
.settings-section .form-table {
    padding: 20px 25px;
    margin: 0;
}
.settings-section .form-table th {
    padding: 15px 10px 15px 0;
    width: 200px;
}
.settings-section .form-table td {
    padding: 15px 10px;
}
.section-actions {
    padding: 15px 25px;
    background: #f8f9fa;
    border-top: 1px solid #eee;
}
.section-actions .button .dashicons {
    vertical-align: middle;
    margin-right: 5px;
}
.nexmo-info {
    background: #e7f3ff;
    border-left: 4px solid #007bff;
    padding: 15px 20px;
    margin: 20px 25px;
    border-radius: 0 5px 5px 0;
}
.nexmo-info ol {
    margin: 10px 0 0 20px;
}
.toggle-password {
    margin-top: 5px !important;
}
.system-info td code {
    background: #f0f0f0;
    padding: 3px 8px;
    border-radius: 3px;
}
/* Modal */
.spfm-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: #fff;
    border-radius: 10px;
    width: 400px;
    max-width: 90%;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
}
.modal-header h3 {
    margin: 0;
}
.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
}
.modal-body {
    padding: 20px;
}
.modal-body .form-field {
    margin-bottom: 15px;
}
.modal-body .form-field label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}
.modal-body .form-field input {
    width: 100%;
}
.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #eee;
    text-align: right;
}
.modal-footer .button {
    margin-left: 10px;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        var $input = $(this).siblings('input');
        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $(this).text('Hide');
        } else {
            $input.attr('type', 'password');
            $(this).text('Show');
        }
    });
    
    // Close modal
    $('.close-modal').on('click', function() {
        $(this).closest('.spfm-modal').hide();
    });
    
    // Save settings
    $('#spfm-settings-form').on('submit', function(e) {
        e.preventDefault();
        var $btn = $(this).find('button[type="submit"]');
        var originalText = $btn.html();
        
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $(this).serialize();
        formData += '&action=spfm_save_settings&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                alert('Settings saved successfully!');
            } else {
                alert(response.data.message || 'Failed to save settings.');
            }
            $btn.html(originalText).prop('disabled', false);
        });
    });
    
    // Test email
    $('#test-email').on('click', function() {
        $('#test-email-modal').show();
    });
    
    $('#send-test-email').on('click', function() {
        var email = $('#test-email-address').val();
        if (!email) {
            alert('Please enter an email address.');
            return;
        }
        
        $(this).text('Sending...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_test_email',
            nonce: spfm_ajax.nonce,
            email: email
        }, function(response) {
            alert(response.data.message);
            $('#test-email-modal').hide();
            $('#send-test-email').text('Send Test').prop('disabled', false);
        });
    });
    
    // Test SMS
    $('#test-sms').on('click', function() {
        $('#test-sms-modal').show();
    });
    
    $('#send-test-sms').on('click', function() {
        var phone = $('#test-sms-phone').val();
        if (!phone) {
            alert('Please enter a phone number.');
            return;
        }
        
        $(this).text('Sending...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_test_sms',
            nonce: spfm_ajax.nonce,
            phone: phone
        }, function(response) {
            alert(response.data.message);
            $('#test-sms-modal').hide();
            $('#send-test-sms').text('Send Test').prop('disabled', false);
        });
    });
    
    // Flush rewrite rules
    $('#flush-rules').on('click', function() {
        $(this).text('Flushing...').prop('disabled', true);
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_flush_rules',
            nonce: spfm_ajax.nonce
        }, function(response) {
            alert('Rewrite rules flushed!');
            location.reload();
        });
    });
    
    // Recreate tables
    $('#recreate-tables').on('click', function() {
        if (!confirm('This will recreate all database tables. Existing data will be preserved. Continue?')) return;
        $(this).text('Recreating...').prop('disabled', true);
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_recreate_tables',
            nonce: spfm_ajax.nonce
        }, function(response) {
            alert('Database tables recreated!');
            location.reload();
        });
    });
});
</script>
