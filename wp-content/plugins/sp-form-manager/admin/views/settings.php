<?php
if (!defined('ABSPATH')) {
    exit;
}

// Get current settings
$admin_email = get_option('spfm_admin_email', get_option('admin_email'));
$email_from_name = get_option('spfm_email_from_name', get_bloginfo('name'));
$email_from_address = get_option('spfm_email_from_address', get_option('admin_email'));

// SMTP Settings
$smtp_enabled = get_option('spfm_smtp_enabled', 0);
$smtp_host = get_option('spfm_smtp_host', '');
$smtp_port = get_option('spfm_smtp_port', '587');
$smtp_encryption = get_option('spfm_smtp_encryption', 'tls');
$smtp_username = get_option('spfm_smtp_username', '');
$smtp_password = get_option('spfm_smtp_password', '');

// Nexmo Settings
$nexmo_api_key = get_option('spfm_nexmo_api_key', '');
$nexmo_api_secret = get_option('spfm_nexmo_api_secret', '');
$nexmo_from_number = get_option('spfm_nexmo_from_number', '');
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-admin-settings"></span> Settings
    </h1>
    
    <div class="spfm-settings-page">
        <!-- Tabs -->
        <div class="settings-tabs">
            <button class="tab-btn active" data-tab="email">
                <span class="dashicons dashicons-email"></span> Email Settings
            </button>
            <button class="tab-btn" data-tab="smtp">
                <span class="dashicons dashicons-admin-network"></span> SMTP Configuration
            </button>
            <button class="tab-btn" data-tab="sms">
                <span class="dashicons dashicons-smartphone"></span> SMS/WhatsApp
            </button>
            <button class="tab-btn" data-tab="system">
                <span class="dashicons dashicons-info"></span> System Info
            </button>
        </div>
        
        <form id="spfm-settings-form">
            <!-- Email Settings Tab -->
            <div class="settings-tab-content active" id="tab-email">
                <div class="settings-card">
                    <div class="card-header">
                        <div class="header-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                            <span class="dashicons dashicons-email-alt"></span>
                        </div>
                        <div class="header-text">
                            <h2>Email Notification Settings</h2>
                            <p>Configure email notifications for form submissions.</p>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="admin_email">
                                    <span class="dashicons dashicons-admin-users"></span> Admin Email
                                </label>
                                <input type="email" name="admin_email" id="admin_email" class="form-control" 
                                       value="<?php echo esc_attr($admin_email); ?>" required>
                                <p class="help-text">Notifications will be sent to this email address.</p>
                            </div>
                        </div>
                        
                        <div class="form-row two-col">
                            <div class="form-group">
                                <label for="email_from_name">
                                    <span class="dashicons dashicons-businessman"></span> From Name
                                </label>
                                <input type="text" name="email_from_name" id="email_from_name" class="form-control" 
                                       value="<?php echo esc_attr($email_from_name); ?>">
                                <p class="help-text">Name shown in email "From" field.</p>
                            </div>
                            <div class="form-group">
                                <label for="email_from_address">
                                    <span class="dashicons dashicons-email"></span> From Email
                                </label>
                                <input type="email" name="email_from_address" id="email_from_address" class="form-control" 
                                       value="<?php echo esc_attr($email_from_address); ?>">
                                <p class="help-text">Email address shown in "From" field.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="button" class="button button-secondary" id="test-email">
                            <span class="dashicons dashicons-email-alt"></span> Send Test Email
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- SMTP Settings Tab -->
            <div class="settings-tab-content" id="tab-smtp">
                <div class="settings-card">
                    <div class="card-header">
                        <div class="header-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                            <span class="dashicons dashicons-admin-network"></span>
                        </div>
                        <div class="header-text">
                            <h2>SMTP Configuration</h2>
                            <p>Configure SMTP for reliable email delivery. Recommended for production sites.</p>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="toggle-label">
                                    <input type="checkbox" name="smtp_enabled" id="smtp_enabled" value="1" <?php checked($smtp_enabled, 1); ?>>
                                    <span class="toggle-switch"></span>
                                    <span class="toggle-text">Enable SMTP</span>
                                </label>
                                <p class="help-text">Enable to use custom SMTP server instead of PHP mail().</p>
                            </div>
                        </div>
                        
                        <div id="smtp-fields" style="<?php echo $smtp_enabled ? '' : 'display:none;'; ?>">
                            <div class="form-row two-col">
                                <div class="form-group">
                                    <label for="smtp_host">
                                        <span class="dashicons dashicons-admin-site"></span> SMTP Host
                                    </label>
                                    <input type="text" name="smtp_host" id="smtp_host" class="form-control" 
                                           value="<?php echo esc_attr($smtp_host); ?>" placeholder="smtp.gmail.com">
                                </div>
                                <div class="form-group">
                                    <label for="smtp_port">
                                        <span class="dashicons dashicons-admin-generic"></span> SMTP Port
                                    </label>
                                    <input type="number" name="smtp_port" id="smtp_port" class="form-control" 
                                           value="<?php echo esc_attr($smtp_port); ?>" placeholder="587">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="smtp_encryption">
                                        <span class="dashicons dashicons-lock"></span> Encryption
                                    </label>
                                    <select name="smtp_encryption" id="smtp_encryption" class="form-control">
                                        <option value="none" <?php selected($smtp_encryption, 'none'); ?>>None</option>
                                        <option value="ssl" <?php selected($smtp_encryption, 'ssl'); ?>>SSL</option>
                                        <option value="tls" <?php selected($smtp_encryption, 'tls'); ?>>TLS (Recommended)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row two-col">
                                <div class="form-group">
                                    <label for="smtp_username">
                                        <span class="dashicons dashicons-admin-users"></span> SMTP Username
                                    </label>
                                    <input type="text" name="smtp_username" id="smtp_username" class="form-control" 
                                           value="<?php echo esc_attr($smtp_username); ?>" placeholder="your@email.com" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="smtp_password">
                                        <span class="dashicons dashicons-lock"></span> SMTP Password
                                    </label>
                                    <div class="password-field">
                                        <input type="password" name="smtp_password" id="smtp_password" class="form-control" 
                                               value="<?php echo esc_attr($smtp_password); ?>" placeholder="••••••••" autocomplete="off">
                                        <button type="button" class="toggle-password">
                                            <span class="dashicons dashicons-visibility"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="smtp-presets">
                                <h4>Quick Presets</h4>
                                <div class="preset-buttons">
                                    <button type="button" class="button smtp-preset" data-host="smtp.gmail.com" data-port="587" data-encryption="tls">
                                        <img src="https://www.google.com/favicon.ico" alt="Gmail"> Gmail
                                    </button>
                                    <button type="button" class="button smtp-preset" data-host="smtp.office365.com" data-port="587" data-encryption="tls">
                                        <img src="https://www.microsoft.com/favicon.ico" alt="Outlook"> Outlook/Office 365
                                    </button>
                                    <button type="button" class="button smtp-preset" data-host="smtp.mail.yahoo.com" data-port="587" data-encryption="tls">
                                        <img src="https://www.yahoo.com/favicon.ico" alt="Yahoo"> Yahoo Mail
                                    </button>
                                    <button type="button" class="button smtp-preset" data-host="email-smtp.us-east-1.amazonaws.com" data-port="587" data-encryption="tls">
                                        <img src="https://aws.amazon.com/favicon.ico" alt="AWS"> Amazon SES
                                    </button>
                                    <button type="button" class="button smtp-preset" data-host="smtp.sendgrid.net" data-port="587" data-encryption="tls">
                                        SendGrid
                                    </button>
                                    <button type="button" class="button smtp-preset" data-host="smtp.mailgun.org" data-port="587" data-encryption="tls">
                                        Mailgun
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-box">
                            <span class="dashicons dashicons-info"></span>
                            <div>
                                <strong>Gmail Users:</strong> You'll need to enable "Less secure app access" or use an App Password if 2FA is enabled.
                                <a href="https://myaccount.google.com/apppasswords" target="_blank">Generate App Password →</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="button" class="button button-secondary" id="test-smtp">
                            <span class="dashicons dashicons-email-alt"></span> Test SMTP Connection
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- SMS/WhatsApp Tab -->
            <div class="settings-tab-content" id="tab-sms">
                <div class="settings-card">
                    <div class="card-header">
                        <div class="header-icon" style="background: linear-gradient(135deg, #25d366, #128c7e);">
                            <span class="dashicons dashicons-smartphone"></span>
                        </div>
                        <div class="header-text">
                            <h2>Nexmo/Vonage SMS Configuration</h2>
                            <p>Configure SMS and WhatsApp notifications via Nexmo (Vonage) API.</p>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="setup-guide">
                            <h4><span class="dashicons dashicons-book"></span> Setup Guide</h4>
                            <ol>
                                <li>Sign up at <a href="https://dashboard.nexmo.com/sign-up" target="_blank">Vonage Dashboard</a></li>
                                <li>Navigate to <strong>API Settings</strong> to find your API Key and Secret</li>
                                <li>Purchase a virtual phone number from <strong>Numbers → Buy Numbers</strong></li>
                                <li>Enter your credentials below</li>
                            </ol>
                        </div>
                        
                        <div class="form-row two-col">
                            <div class="form-group">
                                <label for="nexmo_api_key">
                                    <span class="dashicons dashicons-admin-network"></span> API Key
                                </label>
                                <input type="text" name="nexmo_api_key" id="nexmo_api_key" class="form-control" 
                                       value="<?php echo esc_attr($nexmo_api_key); ?>" placeholder="abcd1234" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="nexmo_api_secret">
                                    <span class="dashicons dashicons-lock"></span> API Secret
                                </label>
                                <div class="password-field">
                                    <input type="password" name="nexmo_api_secret" id="nexmo_api_secret" class="form-control" 
                                           value="<?php echo esc_attr($nexmo_api_secret); ?>" placeholder="••••••••" autocomplete="off">
                                    <button type="button" class="toggle-password">
                                        <span class="dashicons dashicons-visibility"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nexmo_from_number">
                                    <span class="dashicons dashicons-phone"></span> From Number
                                </label>
                                <input type="text" name="nexmo_from_number" id="nexmo_from_number" class="form-control" 
                                       value="<?php echo esc_attr($nexmo_from_number); ?>" placeholder="12015551234">
                                <p class="help-text">Your Nexmo virtual number (without + or spaces). This is the sender number for SMS.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="button" class="button button-secondary" id="test-sms">
                            <span class="dashicons dashicons-smartphone"></span> Send Test SMS
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- System Info Tab -->
            <div class="settings-tab-content" id="tab-system">
                <div class="settings-card">
                    <div class="card-header">
                        <div class="header-icon" style="background: linear-gradient(135deg, #6c757d, #495057);">
                            <span class="dashicons dashicons-info"></span>
                        </div>
                        <div class="header-text">
                            <h2>System Information</h2>
                            <p>Useful information for troubleshooting and maintenance.</p>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <table class="system-info-table">
                            <tr>
                                <th><span class="dashicons dashicons-admin-plugins"></span> Plugin Version</th>
                                <td><?php echo SPFM_VERSION; ?></td>
                            </tr>
                            <tr>
                                <th><span class="dashicons dashicons-wordpress"></span> WordPress Version</th>
                                <td><?php echo get_bloginfo('version'); ?></td>
                            </tr>
                            <tr>
                                <th><span class="dashicons dashicons-editor-code"></span> PHP Version</th>
                                <td><?php echo phpversion(); ?></td>
                            </tr>
                            <tr>
                                <th><span class="dashicons dashicons-admin-site"></span> Site URL</th>
                                <td><code><?php echo home_url(); ?></code></td>
                            </tr>
                            <tr>
                                <th><span class="dashicons dashicons-share"></span> Share URL Format</th>
                                <td><code><?php echo home_url('/spfm-form/{token}/'); ?></code></td>
                            </tr>
                            <tr>
                                <th><span class="dashicons dashicons-database"></span> Database Tables</th>
                                <td>
                                    <?php
                                    global $wpdb;
                                    $tables = array('spfm_customers', 'spfm_themes', 'spfm_forms', 'spfm_form_fields', 'spfm_form_shares', 'spfm_form_submissions', 'spfm_users');
                                    foreach ($tables as $table) {
                                        $exists = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}{$table}'");
                                        $status = $exists ? '<span class="status-ok">✓</span>' : '<span class="status-error">✗</span>';
                                        echo "{$status} {$table}<br>";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="card-footer">
                        <button type="button" class="button" id="flush-rules">
                            <span class="dashicons dashicons-update"></span> Flush Rewrite Rules
                        </button>
                        <button type="button" class="button" id="recreate-tables">
                            <span class="dashicons dashicons-database"></span> Recreate Tables
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Save Button -->
            <div class="settings-save">
                <button type="submit" class="button button-primary button-large">
                    <span class="dashicons dashicons-yes"></span> Save All Settings
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Test Email Modal -->
<div id="test-email-modal" class="spfm-modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><span class="dashicons dashicons-email-alt"></span> Send Test Email</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="test-email-address" class="form-control" value="<?php echo esc_attr($admin_email); ?>">
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
            <h3><span class="dashicons dashicons-smartphone"></span> Send Test SMS</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Phone Number (with country code)</label>
                <input type="tel" id="test-sms-phone" class="form-control" placeholder="+1234567890">
            </div>
        </div>
        <div class="modal-footer">
            <button class="button" onclick="jQuery('#test-sms-modal').hide();">Cancel</button>
            <button class="button button-primary" id="send-test-sms">Send Test</button>
        </div>
    </div>
</div>

<style>
.spfm-settings-page {
    max-width: 1000px;
    margin-top: 20px;
}

/* Tabs */
.settings-tabs {
    display: flex;
    gap: 5px;
    margin-bottom: 25px;
    background: #fff;
    padding: 10px;
    border-radius: 12px;
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
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    color: #666;
    transition: all 0.3s;
}
.tab-btn:hover {
    background: #f5f5f5;
    color: #333;
}
.tab-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.tab-btn .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
}

/* Tab Content */
.settings-tab-content {
    display: none;
}
.settings-tab-content.active {
    display: block;
}

/* Cards */
.settings-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    overflow: hidden;
}
.card-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 25px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}
.header-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.header-icon .dashicons {
    font-size: 28px;
    width: 28px;
    height: 28px;
    color: #fff;
}
.header-text h2 {
    margin: 0 0 5px 0;
    font-size: 20px;
}
.header-text p {
    margin: 0;
    color: #666;
}
.card-body {
    padding: 25px;
}
.card-footer {
    padding: 20px 25px;
    background: #f8f9fa;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
}
.card-footer .button .dashicons {
    margin-right: 5px;
    vertical-align: middle;
}

/* Form Styles */
.form-row {
    margin-bottom: 20px;
}
.form-row.two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-group {
    margin-bottom: 0;
}
.form-group label {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
    color: #333;
}
.form-group label .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
    color: #667eea;
}
.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
}
.form-control:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
}
.help-text {
    margin: 8px 0 0;
    font-size: 12px;
    color: #888;
}

/* Password Field */
.password-field {
    position: relative;
}
.password-field input {
    padding-right: 45px;
}
.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #999;
}
.toggle-password:hover {
    color: #667eea;
}

/* Toggle Switch */
.toggle-label {
    display: flex !important;
    align-items: center;
    gap: 12px;
    cursor: pointer;
}
.toggle-label input {
    display: none;
}
.toggle-switch {
    width: 50px;
    height: 26px;
    background: #ddd;
    border-radius: 26px;
    position: relative;
    transition: all 0.3s;
}
.toggle-switch::after {
    content: '';
    position: absolute;
    width: 22px;
    height: 22px;
    background: #fff;
    border-radius: 50%;
    top: 2px;
    left: 2px;
    transition: all 0.3s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.toggle-label input:checked + .toggle-switch {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.toggle-label input:checked + .toggle-switch::after {
    left: 26px;
}
.toggle-text {
    font-weight: 600;
}

/* SMTP Presets */
.smtp-presets {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.smtp-presets h4 {
    margin: 0 0 15px 0;
    font-size: 14px;
    color: #666;
}
.preset-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.smtp-preset {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 15px;
}
.smtp-preset img {
    width: 16px;
    height: 16px;
}

/* Info Box */
.info-box {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 15px;
    background: #e8f4fd;
    border-left: 4px solid #007bff;
    border-radius: 0 8px 8px 0;
    margin-top: 20px;
}
.info-box .dashicons {
    color: #007bff;
    flex-shrink: 0;
}
.info-box a {
    color: #007bff;
}

/* Setup Guide */
.setup-guide {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}
.setup-guide h4 {
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.setup-guide ol {
    margin: 0;
    padding-left: 25px;
}
.setup-guide li {
    margin-bottom: 8px;
}

/* System Info Table */
.system-info-table {
    width: 100%;
    border-collapse: collapse;
}
.system-info-table th,
.system-info-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.system-info-table th {
    width: 200px;
    font-weight: 600;
    background: #f8f9fa;
}
.system-info-table th .dashicons {
    margin-right: 8px;
    color: #667eea;
    vertical-align: middle;
}
.system-info-table code {
    background: #e9ecef;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 13px;
}
.status-ok {
    color: #28a745;
}
.status-error {
    color: #dc3545;
}

/* Save Button */
.settings-save {
    text-align: center;
    padding: 25px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}
.settings-save .button-large {
    padding: 12px 40px;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* Modal */
.spfm-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: #fff;
    border-radius: 12px;
    width: 450px;
    max-width: 90%;
    overflow: hidden;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.modal-header h3 {
    margin: 0;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
}
.close-modal {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #fff;
    opacity: 0.8;
}
.close-modal:hover {
    opacity: 1;
}
.modal-body {
    padding: 25px;
}
.modal-footer {
    padding: 15px 25px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

@media (max-width: 768px) {
    .settings-tabs {
        flex-wrap: wrap;
    }
    .form-row.two-col {
        grid-template-columns: 1fr;
    }
    .card-header {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.tab-btn').on('click', function() {
        var tab = $(this).data('tab');
        
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');
        
        $('.settings-tab-content').removeClass('active');
        $('#tab-' + tab).addClass('active');
    });
    
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        var $input = $(this).siblings('input');
        var $icon = $(this).find('.dashicons');
        
        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
        } else {
            $input.attr('type', 'password');
            $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
        }
    });
    
    // Toggle SMTP fields
    $('#smtp_enabled').on('change', function() {
        if ($(this).is(':checked')) {
            $('#smtp-fields').slideDown();
        } else {
            $('#smtp-fields').slideUp();
        }
    });
    
    // SMTP Presets
    $('.smtp-preset').on('click', function() {
        $('#smtp_host').val($(this).data('host'));
        $('#smtp_port').val($(this).data('port'));
        $('#smtp_encryption').val($(this).data('encryption'));
    });
    
    // Close modal
    $('.close-modal').on('click', function() {
        $(this).closest('.spfm-modal').hide();
    });
    
    // Save settings
    $('#spfm-settings-form').on('submit', function(e) {
        e.preventDefault();
        var $btn = $(this).find('button[type="submit"]');
        var originalHtml = $btn.html();
        
        $btn.html('<span class="dashicons dashicons-update spin"></span> Saving...').prop('disabled', true);
        
        var formData = $(this).serialize();
        formData += '&action=spfm_save_settings&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                alert('Settings saved successfully!');
            } else {
                alert(response.data.message || 'Failed to save settings.');
            }
            $btn.html(originalHtml).prop('disabled', false);
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
    
    // Test SMTP
    $('#test-smtp').on('click', function() {
        var email = $('#admin_email').val();
        $(this).text('Testing...').prop('disabled', true);
        
        // Save settings first, then test
        var formData = $('#spfm-settings-form').serialize();
        formData += '&action=spfm_save_settings&nonce=' + spfm_ajax.nonce;
        
        $.post(spfm_ajax.ajax_url, formData, function() {
            $.post(spfm_ajax.ajax_url, {
                action: 'spfm_test_email',
                nonce: spfm_ajax.nonce,
                email: email
            }, function(response) {
                alert(response.data.message);
                $('#test-smtp').text('Test SMTP Connection').prop('disabled', false);
            });
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
            alert('Rewrite rules flushed successfully!');
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
            alert('Database tables recreated successfully!');
            location.reload();
        });
    });
});
</script>
