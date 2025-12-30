<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;

$forms_handler = SPFM_Forms::get_instance();
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
$submission_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get all forms for filter
$all_forms = $forms_handler->get_all(array('per_page' => 100));

// Get submissions
$where = "WHERE 1=1";
if ($form_id) {
    $where .= $wpdb->prepare(" AND s.form_id = %d", $form_id);
}

$submissions = $wpdb->get_results("
    SELECT s.*, f.name as form_name, c.name as customer_name, c.email as customer_email
    FROM {$wpdb->prefix}spfm_form_submissions s
    LEFT JOIN {$wpdb->prefix}spfm_forms f ON s.form_id = f.id
    LEFT JOIN {$wpdb->prefix}spfm_customers c ON s.customer_id = c.id
    $where
    ORDER BY s.created_at DESC
    LIMIT 100
");

// Get single submission for view
$submission = null;
if ($action === 'view' && $submission_id) {
    $submission = $wpdb->get_row($wpdb->prepare("
        SELECT s.*, f.name as form_name, c.name as customer_name, c.email as customer_email, c.phone as customer_phone
        FROM {$wpdb->prefix}spfm_form_submissions s
        LEFT JOIN {$wpdb->prefix}spfm_forms f ON s.form_id = f.id
        LEFT JOIN {$wpdb->prefix}spfm_customers c ON s.customer_id = c.id
        WHERE s.id = %d
    ", $submission_id));
}

$statuses = array(
    'new' => array('label' => 'New', 'color' => '#007bff'),
    'pending' => array('label' => 'Pending', 'color' => '#ffc107'),
    'reviewed' => array('label' => 'Reviewed', 'color' => '#17a2b8'),
    'completed' => array('label' => 'Completed', 'color' => '#28a745'),
    'rejected' => array('label' => 'Rejected', 'color' => '#dc3545')
);
?>

<div class="wrap spfm-admin-wrap">
    <h1 class="wp-heading-inline">Form Submissions</h1>
    
    <?php if ($action === 'view' && $submission): ?>
        <!-- View Single Submission -->
        <a href="<?php echo admin_url('admin.php?page=spfm-submissions'); ?>" class="page-title-action">Back to Submissions</a>
        
        <div class="spfm-submission-detail">
            <div class="submission-header">
                <div class="header-info">
                    <h2><?php echo esc_html($submission->form_name); ?></h2>
                    <p>
                        <span class="dashicons dashicons-calendar-alt"></span>
                        Submitted: <?php echo date('F j, Y g:i A', strtotime($submission->created_at)); ?>
                    </p>
                </div>
                <div class="header-status">
                    <label>Status:</label>
                    <select id="submission-status" data-id="<?php echo $submission->id; ?>">
                        <?php foreach ($statuses as $key => $s): ?>
                            <option value="<?php echo $key; ?>" <?php selected($submission->status, $key); ?>>
                                <?php echo $s['label']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <?php if ($submission->customer_name): ?>
                <div class="submission-section">
                    <h3><span class="dashicons dashicons-businessman"></span> Customer Information</h3>
                    <table class="submission-table">
                        <tr>
                            <th>Name</th>
                            <td><?php echo esc_html($submission->customer_name); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><a href="mailto:<?php echo esc_attr($submission->customer_email); ?>"><?php echo esc_html($submission->customer_email); ?></a></td>
                        </tr>
                        <?php if ($submission->customer_phone): ?>
                            <tr>
                                <th>Phone</th>
                                <td><?php echo esc_html($submission->customer_phone); ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            <?php endif; ?>
            
            <div class="submission-section">
                <h3><span class="dashicons dashicons-editor-table"></span> Submission Data</h3>
                <?php 
                $data = json_decode($submission->submission_data, true);
                if ($data):
                ?>
                    <table class="submission-table">
                        <?php foreach ($data as $field_name => $field): ?>
                            <tr>
                                <th><?php echo esc_html($field['label']); ?></th>
                                <td>
                                    <?php 
                                    $value = $field['value'];
                                    if (is_array($value)) {
                                        echo esc_html(implode(', ', $value));
                                    } else {
                                        echo nl2br(esc_html($value));
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No submission data available.</p>
                <?php endif; ?>
            </div>
            
            <?php 
            $files = json_decode($submission->uploaded_files, true);
            if (!empty($files)):
            ?>
                <div class="submission-section">
                    <h3><span class="dashicons dashicons-media-default"></span> Uploaded Files</h3>
                    <div class="uploaded-files">
                        <?php foreach ($files as $file): ?>
                            <div class="file-item">
                                <span class="dashicons dashicons-media-default"></span>
                                <a href="<?php echo esc_url($file['url']); ?>" target="_blank">
                                    <?php echo esc_html($file['label']); ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php 
            $customizations = json_decode($submission->customizations, true);
            if (!empty($customizations)):
            ?>
                <div class="submission-section">
                    <h3><span class="dashicons dashicons-art"></span> Customer Customizations</h3>
                    <table class="submission-table">
                        <?php foreach ($customizations as $key => $value): ?>
                            <?php if (!empty($value)): ?>
                                <tr>
                                    <th><?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?></th>
                                    <td>
                                        <?php if (strpos($key, 'color') !== false): ?>
                                            <span class="color-preview" style="background: <?php echo esc_attr($value); ?>;"></span>
                                            <?php echo esc_html($value); ?>
                                        <?php elseif (strpos($key, 'url') !== false): ?>
                                            <a href="<?php echo esc_url($value); ?>" target="_blank">View Image</a>
                                        <?php else: ?>
                                            <?php echo esc_html($value); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
            
            <div class="submission-section">
                <h3><span class="dashicons dashicons-info"></span> Technical Info</h3>
                <table class="submission-table">
                    <tr>
                        <th>IP Address</th>
                        <td><?php echo esc_html($submission->ip_address); ?></td>
                    </tr>
                    <tr>
                        <th>User Agent</th>
                        <td><small><?php echo esc_html($submission->user_agent); ?></small></td>
                    </tr>
                </table>
            </div>
            
            <div class="submission-actions">
                <button class="button button-primary button-large" id="print-submission">
                    <span class="dashicons dashicons-printer"></span> Print
                </button>
                <button class="button button-link-delete button-large" id="delete-submission" data-id="<?php echo $submission->id; ?>">
                    <span class="dashicons dashicons-trash"></span> Delete
                </button>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Submissions List -->
        <div class="spfm-submissions-container">
            <!-- Filters -->
            <div class="submissions-filters">
                <form method="get">
                    <input type="hidden" name="page" value="spfm-submissions">
                    <select name="form_id">
                        <option value="">All Forms</option>
                        <?php foreach ($all_forms as $f): ?>
                            <option value="<?php echo $f->id; ?>" <?php selected($form_id, $f->id); ?>><?php echo esc_html($f->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="button">Filter</button>
                </form>
            </div>
            
            <!-- Stats Cards -->
            <div class="submissions-stats">
                <?php
                $total = count($submissions);
                $new_count = 0;
                $completed_count = 0;
                foreach ($submissions as $s) {
                    if ($s->status === 'new') $new_count++;
                    if ($s->status === 'completed') $completed_count++;
                }
                ?>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total; ?></div>
                    <div class="stat-label">Total Submissions</div>
                </div>
                <div class="stat-card stat-new">
                    <div class="stat-number"><?php echo $new_count; ?></div>
                    <div class="stat-label">New</div>
                </div>
                <div class="stat-card stat-completed">
                    <div class="stat-number"><?php echo $completed_count; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
            
            <?php if (empty($submissions)): ?>
                <div class="spfm-empty-state">
                    <span class="dashicons dashicons-format-aside"></span>
                    <p>No submissions yet.</p>
                </div>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Form</th>
                            <th width="20%">Customer</th>
                            <th width="20%">Summary</th>
                            <th width="15%">Date</th>
                            <th width="10%">Status</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $s): ?>
                            <?php 
                            $data = json_decode($s->submission_data, true);
                            $summary = '';
                            if ($data) {
                                $first_values = array_slice($data, 0, 2);
                                $parts = array();
                                foreach ($first_values as $f) {
                                    $val = is_array($f['value']) ? implode(', ', $f['value']) : $f['value'];
                                    $parts[] = substr($val, 0, 30);
                                }
                                $summary = implode(' | ', $parts);
                            }
                            ?>
                            <tr>
                                <td><?php echo $s->id; ?></td>
                                <td>
                                    <strong><?php echo esc_html($s->form_name); ?></strong>
                                </td>
                                <td>
                                    <?php if ($s->customer_name): ?>
                                        <?php echo esc_html($s->customer_name); ?>
                                        <br><small><?php echo esc_html($s->customer_email); ?></small>
                                    <?php else: ?>
                                        <span style="color:#999;">Anonymous</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo esc_html($summary); ?><?php echo strlen($summary) >= 60 ? '...' : ''; ?></small>
                                </td>
                                <td>
                                    <span title="<?php echo date('F j, Y g:i A', strtotime($s->created_at)); ?>">
                                        <?php echo human_time_diff(strtotime($s->created_at), current_time('timestamp')); ?> ago
                                    </span>
                                </td>
                                <td>
                                    <?php $status = isset($statuses[$s->status]) ? $statuses[$s->status] : $statuses['pending']; ?>
                                    <span class="spfm-status" style="background: <?php echo $status['color']; ?>20; color: <?php echo $status['color']; ?>;">
                                        <?php echo $status['label']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=spfm-submissions&action=view&id=' . $s->id); ?>" class="button button-small button-primary">
                                        <span class="dashicons dashicons-visibility"></span> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.spfm-submissions-container {
    margin-top: 20px;
}
.submissions-filters {
    background: #fff;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 20px;
}
.submissions-filters select {
    min-width: 200px;
}
.submissions-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}
.stat-card {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    border: 1px solid #ddd;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.stat-number {
    font-size: 36px;
    font-weight: 700;
    color: #333;
}
.stat-label {
    color: #666;
    margin-top: 5px;
}
.stat-new .stat-number { color: #007bff; }
.stat-completed .stat-number { color: #28a745; }
.spfm-status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}
.spfm-empty-state {
    background: #fff;
    text-align: center;
    padding: 60px 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.spfm-empty-state .dashicons {
    font-size: 60px;
    width: 60px;
    height: 60px;
    color: #ccc;
}
/* Submission Detail */
.spfm-submission-detail {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    margin-top: 20px;
    overflow: hidden;
}
.submission-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.submission-header h2 {
    margin: 0;
    color: #fff;
}
.submission-header p {
    margin: 10px 0 0;
    opacity: 0.9;
}
.header-status select {
    padding: 8px 15px;
    border-radius: 5px;
    border: 1px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.2);
    color: #fff;
}
.header-status label {
    margin-right: 10px;
}
.submission-section {
    padding: 25px 30px;
    border-bottom: 1px solid #eee;
}
.submission-section h3 {
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #333;
}
.submission-table {
    width: 100%;
    border-collapse: collapse;
}
.submission-table th {
    text-align: left;
    padding: 12px 15px;
    background: #f8f9fa;
    width: 200px;
    font-weight: 600;
    border: 1px solid #eee;
}
.submission-table td {
    padding: 12px 15px;
    border: 1px solid #eee;
}
.color-preview {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 4px;
    vertical-align: middle;
    margin-right: 8px;
    border: 1px solid #ddd;
}
.uploaded-files {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}
.file-item {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.submission-actions {
    padding: 25px 30px;
    display: flex;
    gap: 10px;
}
.submission-actions .dashicons {
    vertical-align: middle;
    margin-right: 5px;
}
@media print {
    .submission-actions,
    .header-status,
    .wp-admin #wpadminbar,
    .wp-admin #adminmenumain,
    .page-title-action {
        display: none !important;
    }
    .wp-admin #wpcontent {
        margin-left: 0 !important;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Update status
    $('#submission-status').on('change', function() {
        var id = $(this).data('id');
        var status = $(this).val();
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_update_submission_status',
            nonce: spfm_ajax.nonce,
            id: id,
            status: status
        }, function(response) {
            if (!response.success) {
                alert(response.data.message);
            }
        });
    });
    
    // Print
    $('#print-submission').on('click', function() {
        window.print();
    });
    
    // Delete
    $('#delete-submission').on('click', function() {
        if (!confirm('Are you sure you want to delete this submission?')) return;
        var id = $(this).data('id');
        
        $.post(spfm_ajax.ajax_url, {
            action: 'spfm_delete_submission',
            nonce: spfm_ajax.nonce,
            id: id
        }, function(response) {
            if (response.success) {
                window.location.href = '<?php echo admin_url('admin.php?page=spfm-submissions'); ?>';
            } else {
                alert(response.data.message);
            }
        });
    });
});
</script>
