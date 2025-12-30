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

<div class="spfm-settings-wrap">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1><span class="dashicons dashicons-admin-settings"></span> Settings</h1>
            <p>Configure email, SMS, and system settings for your form manager</p>
        </div>
    </div>
    
    <!-- Settings Tabs -->
    <div class="settings-nav">
        <a href="<?php echo admin_url('admin.php?page=spfm-settings&tab=email'); ?>" 
           class="nav-tab <?php echo $current_tab === 'email' ? 'active' : ''; ?>">
            <span class="tab-icon email"><span class="dashicons dashicons-email"></span></span>
            <span class="tab-text">
                <strong>Email Settings</strong>
                <small>Configure notification emails</small>
            </span>
        </a>
        <a href="<?php echo admin_url('admin.php?page=spfm-settings&tab=smtp'); ?>" 
           class="nav-tab <?php echo $current_tab === 'smtp' ? 'active' : ''; ?>">
            <span class="tab-icon smtp"><span class="dashicons dashicons-admin-network"></span></span>
            <span class="tab-text">
                <strong>SMTP Configuration</strong>
                <small>Custom mail server settings</small>
            </span>
        </a>
        <a href="<?php echo admin_url('admin.php?page=spfm-settings&tab=sms'); ?>" 
           class="nav-tab <?php echo $current_tab === 'sms' ? 'active' : ''; ?>">
            <span class="tab-icon sms"><span class="dashicons dashicons-smartphone"></span></span>
            <span class="tab-text">
                <strong>SMS / WhatsApp</strong>
                <small>Vonage messaging setup</small>
            </span>
        </a>
        <a href="<?php echo admin_url('admin.php?page=spfm-settings&tab=system'); ?>" 
           class="nav-tab <?php echo $current_tab === 'system' ? 'active' : ''; ?>">
            <span class="tab-icon system"><span class="dashicons dashicons-info"></span></span>
            <span class="tab-text">
                <strong>System Info</strong>
                <small>Database & diagnostics</small>
            </span>
        </a>
    </div>
    
    <form id="settings-form">
        <!-- Email Settings Tab -->
        <div class="tab-content <?php echo $current_tab === 'email' ? 'active' : ''; ?>" id="tab-email">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-email"></span> Email Settings</h3>
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
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-email-alt"></span> Test Email</h3>
                </div>
                <div class="card-body">
                    <p class="section-desc">Send a test email to verify your email settings are working correctly.</p>
                    <div class="test-row">
                        <input type="email" id="test-email-address" placeholder="Enter email address to test">
                        <button type="button" class="btn btn-primary" id="send-test-email">
                            <span class="dashicons dashicons-email-alt"></span> Send Test Email
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- SMTP Configuration Tab -->
        <div class="tab-content <?php echo $current_tab === 'smtp' ? 'active' : ''; ?>" id="tab-smtp">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-admin-network"></span> SMTP Configuration</h3>
                </div>
                <div class="card-body">
                    <div class="toggle-box">
                        <label class="toggle-switch">
                            <input type="checkbox" name="smtp_enabled" id="smtp_enabled" value="1" 
                                   <?php checked($smtp_enabled, 1); ?>>
                            <span class="toggle-slider"></span>
                        </label>
                        <div class="toggle-info">
                            <strong>Enable Custom SMTP</strong>
                            <p>Use your own mail server instead of WordPress default mail</p>
                        </div>
                    </div>
                    
                    <div id="smtp-settings" style="<?php echo $smtp_enabled ? '' : 'display:none;'; ?>">
                        <div class="presets-section">
                            <h4>Quick Presets</h4>
                            <div class="preset-buttons">
                                <button type="button" class="preset-btn" data-host="smtp.gmail.com" data-port="587" data-encryption="tls">
                                    <span class="preset-icon gmail">G</span> Gmail
                                </button>
                                <button type="button" class="preset-btn" data-host="smtp.office365.com" data-port="587" data-encryption="tls">
                                    <span class="preset-icon outlook">O</span> Outlook
                                </button>
                                <button type="button" class="preset-btn" data-host="smtp.mail.yahoo.com" data-port="587" data-encryption="tls">
                                    <span class="preset-icon yahoo">Y</span> Yahoo
                                </button>
                                <button type="button" class="preset-btn" data-host="smtp.sendgrid.net" data-port="587" data-encryption="tls">
                                    <span class="preset-icon sendgrid">S</span> SendGrid
                                </button>
                                <button type="button" class="preset-btn" data-host="smtp.mailgun.org" data-port="587" data-encryption="tls">
                                    <span class="preset-icon mailgun">M</span> Mailgun
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
                            <span class="info-icon"><span class="dashicons dashicons-info"></span></span>
                            <div class="info-content">
                                <strong>Gmail Users</strong>
                                <p>If you have 2-factor authentication enabled, you need to create an App Password.</p>
                                <a href="https://myaccount.google.com/apppasswords" target="_blank" class="info-link">
                                    Create App Password <span class="dashicons dashicons-external"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- SMS / WhatsApp Tab -->
        <div class="tab-content <?php echo $current_tab === 'sms' ? 'active' : ''; ?>" id="tab-sms">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-smartphone"></span> SMS / WhatsApp Configuration</h3>
                </div>
                <div class="card-body">
                    <div class="info-box info-primary">
                        <span class="info-icon"><span class="dashicons dashicons-info"></span></span>
                        <div class="info-content">
                            <strong>Vonage (Nexmo) Integration</strong>
                            <p>To send SMS messages, you need a Vonage account. Sign up to get your API credentials.</p>
                            <a href="https://dashboard.nexmo.com/sign-up" target="_blank" class="info-link">
                                Sign up for Vonage <span class="dashicons dashicons-external"></span>
                            </a>
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
                            <div class="password-field">
                                <input type="password" name="nexmo_api_secret" id="nexmo_api_secret" 
                                       value="<?php echo esc_attr($nexmo_api_secret); ?>"
                                       placeholder="Your API Secret">
                                <button type="button" class="toggle-password">
                                    <span class="dashicons dashicons-visibility"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label for="nexmo_from_number">From Number / Sender ID</label>
                        <input type="text" name="nexmo_from_number" id="nexmo_from_number" 
                               value="<?php echo esc_attr($nexmo_from_number); ?>"
                               placeholder="+1234567890">
                        <p class="field-hint">Your Vonage virtual number or alphanumeric sender ID</p>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-smartphone"></span> Test SMS</h3>
                </div>
                <div class="card-body">
                    <p class="section-desc">Send a test SMS to verify your Vonage settings are working correctly.</p>
                    <div class="test-row">
                        <input type="tel" id="test-sms-number" placeholder="Enter phone number (e.g., +1234567890)">
                        <button type="button" class="btn btn-primary" id="send-test-sms">
                            <span class="dashicons dashicons-smartphone"></span> Send Test SMS
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Info Tab -->
        <div class="tab-content <?php echo $current_tab === 'system' ? 'active' : ''; ?>" id="tab-system">
            <div class="system-grid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><span class="dashicons dashicons-info"></span> Environment</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Plugin Version</span>
                                <span class="info-value"><?php echo SPFM_VERSION; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">WordPress</span>
                                <span class="info-value"><?php echo get_bloginfo('version'); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">PHP Version</span>
                                <span class="info-value"><?php echo phpversion(); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">MySQL Version</span>
                                <span class="info-value"><?php global $wpdb; echo $wpdb->db_version(); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Database Prefix</span>
                                <span class="info-value"><?php echo $wpdb->prefix; ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Site URL</span>
                                <span class="info-value"><?php echo home_url(); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><span class="dashicons dashicons-database"></span> Database Tables</h3>
                    </div>
                    <div class="card-body no-padding">
                        <div class="table-status-list">
                            <?php
                            global $wpdb;
                            $tables = array(
                                'spfm_users' => array('label' => 'Users', 'icon' => 'admin-users'),
                                'spfm_customers' => array('label' => 'Customers', 'icon' => 'groups'),
                                'spfm_themes' => array('label' => 'Templates', 'icon' => 'layout'),
                                'spfm_theme_pages' => array('label' => 'Theme Pages', 'icon' => 'admin-page'),
                                'spfm_page_sections' => array('label' => 'Page Sections', 'icon' => 'screenoptions'),
                                'spfm_forms' => array('label' => 'Order Forms', 'icon' => 'feedback'),
                                'spfm_form_shares' => array('label' => 'Form Shares', 'icon' => 'share'),
                                'spfm_form_submissions' => array('label' => 'Submissions', 'icon' => 'portfolio')
                            );
                            foreach ($tables as $table => $info):
                                $table_name = $wpdb->prefix . $table;
                                $exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
                                $count = $exists ? $wpdb->get_var("SELECT COUNT(*) FROM $table_name") : 0;
                            ?>
                                <div class="table-status-item">
                                    <div class="table-icon">
                                        <span class="dashicons dashicons-<?php echo $info['icon']; ?>"></span>
                                    </div>
                                    <div class="table-info">
                                        <strong><?php echo $info['label']; ?></strong>
                                        <small><?php echo $table_name; ?></small>
                                    </div>
                                    <div class="table-status">
                                        <?php if ($exists): ?>
                                            <span class="status-badge active">
                                                <span class="dashicons dashicons-yes"></span> OK
                                            </span>
                                            <span class="record-count"><?php echo number_format($count); ?> records</span>
                                        <?php else: ?>
                                            <span class="status-badge error">
                                                <span class="dashicons dashicons-no"></span> Missing
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><span class="dashicons dashicons-admin-tools"></span> Maintenance Tools</h3>
                </div>
                <div class="card-body">
                    <div class="tools-grid">
                        <div class="tool-item">
                            <div class="tool-icon">
                                <span class="dashicons dashicons-update"></span>
                            </div>
                            <div class="tool-info">
                                <strong>Flush Rewrite Rules</strong>
                                <p>Clear and regenerate WordPress permalink rules</p>
                            </div>
                            <button type="button" class="btn btn-outline" id="flush-rules">
                                <span class="dashicons dashicons-update"></span> Flush Rules
                            </button>
                        </div>
                        <div class="tool-item">
                            <div class="tool-icon warning">
                                <span class="dashicons dashicons-database"></span>
                            </div>
                            <div class="tool-info">
                                <strong>Recreate Database Tables</strong>
                                <p>Rebuild tables and reinstall default templates</p>
                            </div>
                            <button type="button" class="btn btn-danger" id="recreate-tables">
                                <span class="dashicons dashicons-database"></span> Recreate Tables
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($current_tab !== 'system'): ?>
        <div class="save-bar">
            <button type="submit" class="btn btn-primary btn-lg">
                <span class="dashicons dashicons-saved"></span> Save Settings
            </button>
        </div>
        <?php endif; ?>
    </form>
</div>

<style>
.spfm-settings-wrap {
    padding: 20px;
    max-width: 1200px;
    font-family: 'Inter', -apple-system, sans-serif;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, #475569 0%, #334155 100%);
    border-radius: 20px;
    padding: 40px;
    color: #fff;
    margin-bottom: 30px;
}
.page-header .header-content h1 {
    margin: 0 0 10px 0;
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
    color: #fff;
}
.page-header .header-content h1 .dashicons {
    font-size: 32px;
    width: 32px;
    height: 32px;
}
.page-header .header-content p {
    margin: 0;
    opacity: 0.9;
    font-size: 15px;
}

/* Settings Navigation */
.settings-nav {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 30px;
}
.nav-tab {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #fff;
    border-radius: 16px;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.3s;
    border: 2px solid transparent;
}
.nav-tab:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.nav-tab.active {
    border-color: #475569;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}
.tab-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.tab-icon .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
    color: #fff;
}
.tab-icon.email { background: linear-gradient(135deg, #10b981, #059669); }
.tab-icon.smtp { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.tab-icon.sms { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.tab-icon.system { background: linear-gradient(135deg, #f59e0b, #d97706); }
.tab-text {
    display: flex;
    flex-direction: column;
}
.tab-text strong {
    color: #1e293b;
    font-size: 14px;
}
.tab-text small {
    color: #64748b;
    font-size: 12px;
}

/* Tab Content */
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
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
.card-header .dashicons { color: #475569; }
.card-body { padding: 25px; }
.card-body.no-padding { padding: 0; }

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
.btn-primary { background: #475569; color: #fff; }
.btn-primary:hover { background: #334155; color: #fff; }
.btn-outline { background: transparent; color: #475569; border: 2px solid #475569; }
.btn-outline:hover { background: #475569; color: #fff; }
.btn-danger { background: #fee2e2; color: #dc2626; }
.btn-danger:hover { background: #dc2626; color: #fff; }
.btn-lg { padding: 15px 30px; font-size: 15px; }
.btn .dashicons { font-size: 16px; width: 16px; height: 16px; }

/* Form Fields */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-field {
    margin-bottom: 20px;
}
.form-field:last-child { margin-bottom: 0; }
.form-field label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
    color: #334155;
}
.form-field input,
.form-field select,
.form-field textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: border-color 0.2s;
}
.form-field input:focus,
.form-field select:focus,
.form-field textarea:focus {
    border-color: #475569;
    outline: none;
}
.form-field input::placeholder { color: #94a3b8; }
.field-hint {
    margin: 8px 0 0 0;
    color: #94a3b8;
    font-size: 13px;
}
.section-desc {
    margin: 0 0 20px 0;
    color: #64748b;
    font-size: 14px;
}

/* Toggle Box */
.toggle-box {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    margin-bottom: 25px;
}
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 56px;
    height: 30px;
    flex-shrink: 0;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: #cbd5e1;
    transition: 0.3s;
    border-radius: 30px;
}
.toggle-slider:before {
    position: absolute;
    content: "";
    height: 24px;
    width: 24px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
.toggle-switch input:checked + .toggle-slider {
    background: linear-gradient(135deg, #475569, #334155);
}
.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(26px);
}
.toggle-info strong {
    display: block;
    color: #1e293b;
    margin-bottom: 3px;
}
.toggle-info p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

/* Presets Section */
.presets-section {
    margin-bottom: 25px;
    padding-bottom: 25px;
    border-bottom: 1px solid #e2e8f0;
}
.presets-section h4 {
    margin: 0 0 15px 0;
    font-size: 14px;
    color: #64748b;
    font-weight: 600;
}
.preset-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.preset-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 18px;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    color: #334155;
    transition: all 0.2s;
}
.preset-btn:hover {
    border-color: #475569;
    background: #f1f5f9;
}
.preset-icon {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
}
.preset-icon.gmail { background: #ea4335; }
.preset-icon.outlook { background: #0078d4; }
.preset-icon.yahoo { background: #6001d2; }
.preset-icon.sendgrid { background: #1a82e2; }
.preset-icon.mailgun { background: #d9453d; }

/* Password Field */
.password-field {
    display: flex;
    gap: 8px;
}
.password-field input {
    flex: 1;
}
.toggle-password {
    padding: 0 15px;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
}
.toggle-password:hover {
    background: #f1f5f9;
    border-color: #475569;
}

/* Info Box */
.info-box {
    display: flex;
    gap: 15px;
    padding: 20px;
    background: #f0f9ff;
    border-radius: 12px;
    margin-top: 20px;
    border: 1px solid #bae6fd;
}
.info-box.info-primary {
    background: #f0fdf4;
    border-color: #bbf7d0;
}
.info-icon {
    width: 40px;
    height: 40px;
    background: #0ea5e9;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.info-box.info-primary .info-icon { background: #10b981; }
.info-icon .dashicons {
    color: #fff;
    font-size: 20px;
    width: 20px;
    height: 20px;
}
.info-content strong {
    display: block;
    color: #1e293b;
    margin-bottom: 5px;
}
.info-content p {
    margin: 0 0 10px 0;
    color: #64748b;
    font-size: 13px;
}
.info-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #0ea5e9;
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
}
.info-box.info-primary .info-link { color: #10b981; }
.info-link .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
}

/* Test Row */
.test-row {
    display: flex;
    gap: 12px;
}
.test-row input {
    flex: 1;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
}
.test-row input:focus {
    border-color: #475569;
    outline: none;
}

/* System Grid */
.system-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}
.info-item {
    padding: 15px;
    background: #f8fafc;
    border-radius: 10px;
}
.info-label {
    display: block;
    font-size: 12px;
    color: #64748b;
    margin-bottom: 5px;
}
.info-value {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

/* Table Status List */
.table-status-list {
    display: flex;
    flex-direction: column;
}
.table-status-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
}
.table-status-item:last-child { border-bottom: none; }
.table-icon {
    width: 40px;
    height: 40px;
    background: #f1f5f9;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.table-icon .dashicons {
    color: #64748b;
    font-size: 18px;
    width: 18px;
    height: 18px;
}
.table-info {
    flex: 1;
}
.table-info strong {
    display: block;
    color: #1e293b;
    font-size: 14px;
}
.table-info small {
    color: #94a3b8;
    font-size: 11px;
}
.table-status {
    display: flex;
    align-items: center;
    gap: 10px;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}
.status-badge .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
}
.status-badge.active {
    background: #d1fae5;
    color: #059669;
}
.status-badge.error {
    background: #fee2e2;
    color: #dc2626;
}
.record-count {
    font-size: 12px;
    color: #64748b;
}

/* Tools Grid */
.tools-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
.tool-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 25px;
    background: #f8fafc;
    border-radius: 12px;
}
.tool-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #475569, #334155);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.tool-icon.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}
.tool-icon .dashicons {
    color: #fff;
    font-size: 24px;
    width: 24px;
    height: 24px;
}
.tool-info {
    flex: 1;
}
.tool-info strong {
    display: block;
    color: #1e293b;
    margin-bottom: 5px;
}
.tool-info p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

/* Save Bar */
.save-bar {
    position: sticky;
    bottom: 20px;
    background: #fff;
    padding: 20px 25px;
    border-radius: 16px;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
}

/* Responsive */
@media (max-width: 1024px) {
    .settings-nav { grid-template-columns: repeat(2, 1fr); }
    .system-grid { grid-template-columns: 1fr; }
    .tools-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .settings-nav { grid-template-columns: 1fr; }
    .form-row { grid-template-columns: 1fr; }
    .info-grid { grid-template-columns: 1fr; }
}
</style>

<script>
var spfm_ajax = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('spfm_nonce'); ?>'
};

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
        
        // Visual feedback
        $('.preset-btn').removeClass('selected');
        $(this).addClass('selected');
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
                // Show success message
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
            $btn.html('<span class="dashicons dashicons-update"></span> Flush Rules').prop('disabled', false);
        });
    });
    
    // Recreate tables
    $('#recreate-tables').on('click', function() {
        if (!confirm('This will recreate all database tables and reinstall default templates. Existing data will be preserved where possible. Continue?')) return;
        
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
