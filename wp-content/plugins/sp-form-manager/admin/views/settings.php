<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get current settings
$admin_email = get_option('spfm_admin_email', get_option('admin_email'));
$email_from_name = get_option('spfm_email_from_name', get_bloginfo('name'));
$email_from_address = get_option('spfm_email_from_address', get_option('admin_email'));
$smtp_enabled = get_option('spfm_smtp_enabled', 0);
$smtp_host = get_option('spfm_smtp_host', '');
$smtp_port = get_option('spfm_smtp_port', 587);
$smtp_encryption = get_option('spfm_smtp_encryption', 'tls');
$smtp_username = get_option('spfm_smtp_username', '');
$smtp_password = get_option('spfm_smtp_password', '');
$nexmo_api_key = get_option('spfm_nexmo_api_key', '');
$nexmo_api_secret = get_option('spfm_nexmo_api_secret', '');
$nexmo_from_number = get_option('spfm_nexmo_from_number', '');

$current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'email';
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-admin-settings"></span> Settings
    </h1>
    
    <!-- Tabs -->
    <div class="settings-tabs">
        <a href="<?php echo admin_url('admin.php?page=spfm-settings&tab=email'); ?>" 
           class="tab-btn <?php echo $current_tab === 'email' ? 'active' : ''; ?>">
            <span class="dashicons dashicons-email"></span> Email Settings
        </a>
        <a href="<?php echo admin_url('admin.php?page=spfm-settings&tab=smtp'); ?>" 
           class="tab-btn <?php echo $current_tab === 'smtp' ? 'active' : ''; ?>">
            <span class="dashicons dashicons-admin-network"></span> SMTP Configuration
        </a>
        <a href="<?php echo admin_url('admin.php?page=spfm-settings&tab=sms'); ?>" 
           class="tab-btn <?php echo $current_tab === 'sms' ? 'active' : ''; ?>">
            <span class="dashicons dashicons-smartphone"></span> SMS / WhatsApp
        </a>
        <a href="<?php echo admin_url('admin.php?page=spfm-settings&tab=system'); ?>" 
           class="tab-btn <?php echo $current_tab === 'system' ? 'active' : ''; ?>">
            <span class="dashicons dashicons-info"></span> System Info
        </a>
    </div>
    
    <form id="settings-form">
        <!-- Email Settings Tab -->
        <div class="tab-content <?php echo $current_tab === 'email' ? 'active' : ''; ?>" id="tab-email">
            <div class="settings-card">
                <div class="card-header">
                    <span class="dashicons dashicons-email"></span>
                    <h3>Email Settings</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-field">
                            <label for="admin_email">Admin Email</label>
                            <input type="email" name="admin_email" id="admin_email" 
                                   value="<?php echo esc_attr($admin_email); ?>"
                                   placeholder="admin@example.com">
                            <p class="field-hint">Notification emails will be sent to this address</p>
                        </div>
                        <div class="form-field">
                            <label for="email_from_name">From Name</label>
                            <input type="text" name="email_from_name" id="email_from_name" 
                                   value="<?php echo esc_attr($email_from_name); ?>"
                                   placeholder="Your Company Name">
                            <p class="field-hint">Name that appears in the From field</p>
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label for="email_from_address">From Email Address</label>
                        <input type="email" name="email_from_address" id="email_from_address" 
                               value="<?php echo esc_attr($email_from_address); ?>"
                               placeholder="noreply@example.com">
                        <p class="field-hint">Email address that appears in the From field</p>
                    </div>
                    
                    <div class="test-section">
                        <h4>Test Email</h4>
                        <div class="test-row">
                            <input type="email" id="test-email-address" placeholder="Enter email to test">
                            <button type="button" class="button" id="send-test-email">
                                <span class="dashicons dashicons-email-alt"></span> Send Test Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- SMTP Configuration Tab -->
        <div class="tab-content <?php echo $current_tab === 'smtp' ? 'active' : ''; ?>" id="tab-smtp">
            <div class="settings-card">
                <div class="card-header">
                    <span class="dashicons dashicons-admin-network"></span>
                    <h3>SMTP Configuration</h3>
                </div>
                <div class="card-body">
                    <div class="toggle-field">
                        <label class="toggle-switch">
                            <input type="checkbox" name="smtp_enabled" id="smtp_enabled" value="1" 
                                   <?php checked($smtp_enabled, 1); ?>>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label">Enable SMTP</span>
                    </div>
                    
                    <div id="smtp-settings" style="<?php echo $smtp_enabled ? '' : 'display:none;'; ?>">
                        <div class="presets-section">
                            <h4>Quick Presets</h4>
                            <div class="preset-buttons">
                                <button type="button" class="preset-btn" data-host="smtp.gmail.com" data-port="587" data-encryption="tls">
                                    <img src="https://www.google.com/favicon.ico" alt="Gmail"> Gmail
                                </button>
                                <button type="button" class="preset-btn" data-host="smtp.office365.com" data-port="587" data-encryption="tls">
                                    <img src="https://www.microsoft.com/favicon.ico" alt="Outlook"> Outlook
                                </button>
                                <button type="button" class="preset-btn" data-host="smtp.mail.yahoo.com" data-port="587" data-encryption="tls">
                                    <img src="https://www.yahoo.com/favicon.ico" alt="Yahoo"> Yahoo
                                </button>
                                <button type="button" class="preset-btn" data-host="smtp.sendgrid.net" data-port="587" data-encryption="tls">
                                    SendGrid
                                </button>
                                <button type="button" class="preset-btn" data-host="smtp.mailgun.org" data-port="587" data-encryption="tls">
                                    Mailgun
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-field">
                                <label for="smtp_host">SMTP Host</label>
                                <input type="text" name="smtp_host" id="smtp_host" 
                                       value="<?php echo esc_attr($smtp_host); ?>"
                                       placeholder="smtp.gmail.com">
                            </div>
                            <div class="form-field">
                                <label for="smtp_port">SMTP Port</label>
                                <input type="number" name="smtp_port" id="smtp_port" 
                                       value="<?php echo esc_attr($smtp_port); ?>"
                                       placeholder="587">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-field">
                                <label for="smtp_encryption">Encryption</label>
                                <select name="smtp_encryption" id="smtp_encryption">
                                    <option value="" <?php selected($smtp_encryption, ''); ?>>None</option>
                                    <option value="ssl" <?php selected($smtp_encryption, 'ssl'); ?>>SSL</option>
                                    <option value="tls" <?php selected($smtp_encryption, 'tls'); ?>>TLS (Recommended)</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="smtp_username">Username</label>
                                <input type="text" name="smtp_username" id="smtp_username" 
                                       value="<?php echo esc_attr($smtp_username); ?>"
                                       placeholder="your@email.com" autocomplete="off">
                            </div>
                        </div>
                        
                        <div class="form-field">
                            <label for="smtp_password">Password</label>
                            <div class="password-field">
                                <input type="password" name="smtp_password" id="smtp_password" 
                                       value="<?php echo esc_attr($smtp_password); ?>"
                                       placeholder="••••••••" autocomplete="new-password">
                                <button type="button" class="toggle-password">
                                    <span class="dashicons dashicons-visibility"></span>
                                </button>
                            </div>
                            <p class="field-hint">For Gmail with 2FA, use an App Password</p>
                        </div>
                        
                        <div class="info-box">
                            <span class="dashicons dashicons-info"></span>
                            <div>
                                <strong>Gmail Users:</strong> If you have 2-factor authentication enabled, 
                                you need to create an App Password. 
                                <a href="https://myaccount.google.com/apppasswords" target="_blank">Create App Password →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- SMS / WhatsApp Tab -->
        <div class="tab-content <?php echo $current_tab === 'sms' ? 'active' : ''; ?>" id="tab-sms">
            <div class="settings-card">
                <div class="card-header">
                    <span class="dashicons dashicons-smartphone"></span>
                    <h3>SMS / WhatsApp Configuration (Vonage/Nexmo)</h3>
                </div>
                <div class="card-body">
                    <div class="info-box">
                        <span class="dashicons dashicons-info"></span>
                        <div>
                            To send SMS messages, you need a Vonage (formerly Nexmo) account. 
                            <a href="https://dashboard.nexmo.com/sign-up" target="_blank">Sign up for Vonage →</a>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="nexmo_api_key">API Key</label>
                            <input type="text" name="nexmo_api_key" id="nexmo_api_key" 
                                   value="<?php echo esc_attr($nexmo_api_key); ?>"
                                   placeholder="Your API Key">
                        </div>
                        <div class="form-field">
                            <label for="nexmo_api_secret">API Secret</label>
                            <input type="password" name="nexmo_api_secret" id="nexmo_api_secret" 
                                   value="<?php echo esc_attr($nexmo_api_secret); ?>"
                                   placeholder="Your API Secret">
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label for="nexmo_from_number">From Number</label>
                        <input type="text" name="nexmo_from_number" id="nexmo_from_number" 
                               value="<?php echo esc_attr($nexmo_from_number); ?>"
                               placeholder="+1234567890">
                        <p class="field-hint">Your Vonage virtual number or sender ID</p>
                    </div>
                    
                    <div class="test-section">
                        <h4>Test SMS</h4>
                        <div class="test-row">
                            <input type="tel" id="test-sms-number" placeholder="Enter phone number to test">
                            <button type="button" class="button" id="send-test-sms">
                                <span class="dashicons dashicons-smartphone"></span> Send Test SMS
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Info Tab -->
        <div class="tab-content <?php echo $current_tab === 'system' ? 'active' : ''; ?>" id="tab-system">
            <div class="settings-card">
                <div class="card-header">
                    <span class="dashicons dashicons-info"></span>
                    <h3>System Information</h3>
                </div>
                <div class="card-body">
                    <table class="system-info-table">
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
                            <th>MySQL Version</th>
                            <td><?php global $wpdb; echo $wpdb->db_version(); ?></td>
                        </tr>
                        <tr>
                            <th>Database Prefix</th>
                            <td><?php global $wpdb; echo $wpdb->prefix; ?></td>
                        </tr>
                    </table>
                    
                    <h4>Database Tables</h4>
                    <table class="system-info-table">
                        <?php
                        global $wpdb;
                        $tables = array(
                            'spfm_users' => 'Users',
                            'spfm_customers' => 'Customers',
                            'spfm_themes' => 'Website Templates',
                            'spfm_theme_pages' => 'Theme Pages',
                            'spfm_page_sections' => 'Page Sections',
                            'spfm_forms' => 'Order Forms',
                            'spfm_form_shares' => 'Form Shares',
                            'spfm_form_submissions' => 'Submissions'
                        );
                        foreach ($tables as $table => $label):
                            $table_name = $wpdb->prefix . $table;
                            $exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
                            $count = $exists ? $wpdb->get_var("SELECT COUNT(*) FROM $table_name") : 0;
                        ?>
                            <tr>
                                <th><?php echo $label; ?></th>
                                <td>
                                    <?php if ($exists): ?>
                                        <span class="status-badge status-active">✓ OK</span>
                                        <span class="count-badge"><?php echo $count; ?> records</span>
                                    <?php else: ?>
                                        <span class="status-badge status-error">✗ Missing</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    
                    <div class="system-actions">
                        <button type="button" class="button" id="flush-rules">
                            <span class="dashicons dashicons-update"></span> Flush Rewrite Rules
                        </button>
                        <button type="button" class="button" id="recreate-tables">
                            <span class="dashicons dashicons-database"></span> Recreate Tables
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($current_tab !== 'system'): ?>
        <div class="save-bar">
            <button type="submit" class="button button-primary button-large">
                <span class="dashicons dashicons-saved"></span> Save Settings
            </button>
        </div>
        <?php endif; ?>
    </form>
</div>

<style>
/* Settings Tabs */
.settings-tabs {
    display: flex;
    gap: 5px;
    margin: 20px 0;
    background: #fff;
    padding: 5px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.tab-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: transparent;
    border: none;
    border-radius: 8px;
    color: #666;
    text-decoration: none;
    transition: all 0.3s;
}
.tab-btn:hover {
    background: #f0f0f0;
    color: #333;
}
.tab-btn.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}

/* Settings Card */
.settings-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}
.card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}
.card-header .dashicons {
    color: #667eea;
}
.card-header h3 {
    margin: 0;
}
.card-body {
    padding: 25px;
}

/* Form Fields */
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
.form-field select,
.form-field textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.form-field input:focus,
.form-field select:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
}
.field-hint {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
}

/* Toggle Switch */
.toggle-field {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}
.toggle-switch {
    position: relative;
    width: 50px;
    height: 26px;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ccc;
    transition: 0.3s;
    border-radius: 26px;
}
.toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background: #fff;
    transition: 0.3s;
    border-radius: 50%;
}
.toggle-switch input:checked + .toggle-slider {
    background: linear-gradient(135deg, #667eea, #764ba2);
}
.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(24px);
}
.toggle-label {
    font-weight: 500;
}

/* Presets */
.presets-section {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}
.presets-section h4 {
    margin: 0 0 15px 0;
    font-size: 14px;
    color: #666;
}
.preset-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.preset-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 15px;
    background: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
}
.preset-btn:hover {
    background: #e0e0e0;
}
.preset-btn img {
    width: 16px;
    height: 16px;
}

/* Password Field */
.password-field {
    display: flex;
    gap: 5px;
}
.password-field input {
    flex: 1;
}
.toggle-password {
    padding: 0 15px;
    background: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
}

/* Info Box */
.info-box {
    display: flex;
    gap: 15px;
    padding: 15px;
    background: #e7f3ff;
    border-radius: 8px;
    margin: 20px 0;
}
.info-box .dashicons {
    color: #0073aa;
    flex-shrink: 0;
}
.info-box a {
    color: #0073aa;
}

/* Test Section */
.test-section {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.test-section h4 {
    margin: 0 0 15px 0;
}
.test-row {
    display: flex;
    gap: 10px;
}
.test-row input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

/* System Info */
.system-info-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 25px;
}
.system-info-table th,
.system-info-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: left;
}
.system-info-table th {
    width: 200px;
    color: #666;
    font-weight: 500;
}
.status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 12px;
}
.status-active {
    background: #d4edda;
    color: #155724;
}
.status-error {
    background: #f8d7da;
    color: #721c24;
}
.count-badge {
    margin-left: 10px;
    font-size: 12px;
    color: #999;
}
.system-actions {
    display: flex;
    gap: 10px;
}

/* Save Bar */
.save-bar {
    position: sticky;
    bottom: 0;
    background: #fff;
    padding: 15px 20px;
    border-top: 1px solid #eee;
    margin: 0 -20px -20px;
    border-radius: 0 0 12px 12px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    .settings-tabs {
        flex-wrap: wrap;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Toggle SMTP settings
    $('#smtp_enabled').on('change', function() {
        if ($(this).is(':checked')) {
            $('#smtp-settings').slideDown();
        } else {
            $('#smtp-settings').slideUp();
        }
    });
    
    // Preset buttons
    $('.preset-btn').on('click', function() {
        $('#smtp_host').val($(this).data('host'));
        $('#smtp_port').val($(this).data('port'));
        $('#smtp_encryption').val($(this).data('encryption'));
    });
    
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        var input = $(this).siblings('input');
        var icon = $(this).find('.dashicons');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
        } else {
            input.attr('type', 'password');
            icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
        }
    });
    
    // Save settings
    $('#settings-form').on('submit', function(e) {
        e.preventDefault();
        
        var $btn = $(this).find('button[type="submit"]');
        $btn.text('Saving...').prop('disabled', true);
        
        var formData = $(this).serialize();
        formData += '&action=spfm_save_settings&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                alert('Settings saved successfully!');
            } else {
                alert(response.data.message);
            }
            $btn.html('<span class="dashicons dashicons-saved"></span> Save Settings').prop('disabled', false);
        });
    });
    
    // Test email
    $('#send-test-email').on('click', function() {
        var email = $('#test-email-address').val();
        if (!email) {
            alert('Please enter an email address.');
            return;
        }
        
        var $btn = $(this);
        $btn.text('Sending...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_test_email',
            nonce: spfm_ajax.nonce,
            email: email
        }, function(response) {
            alert(response.data.message);
            $btn.html('<span class="dashicons dashicons-email-alt"></span> Send Test Email').prop('disabled', false);
        });
    });
    
    // Test SMS
    $('#send-test-sms').on('click', function() {
        var phone = $('#test-sms-number').val();
        if (!phone) {
            alert('Please enter a phone number.');
            return;
        }
        
        var $btn = $(this);
        $btn.text('Sending...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_test_sms',
            nonce: spfm_ajax.nonce,
            phone: phone
        }, function(response) {
            alert(response.data.message);
            $btn.html('<span class="dashicons dashicons-smartphone"></span> Send Test SMS').prop('disabled', false);
        });
    });
    
    // Flush rules
    $('#flush-rules').on('click', function() {
        var $btn = $(this);
        $btn.text('Flushing...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_flush_rules',
            nonce: spfm_ajax.nonce
        }, function(response) {
            alert(response.data.message);
            $btn.html('<span class="dashicons dashicons-update"></span> Flush Rewrite Rules').prop('disabled', false);
        });
    });
    
    // Recreate tables
    $('#recreate-tables').on('click', function() {
        if (!confirm('This will recreate all database tables. Existing data will be preserved. Continue?')) return;
        
        var $btn = $(this);
        $btn.text('Recreating...').prop('disabled', true);
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_recreate_tables',
            nonce: spfm_ajax.nonce
        }, function(response) {
            alert(response.data.message);
            location.reload();
        });
    });
});
</script>
