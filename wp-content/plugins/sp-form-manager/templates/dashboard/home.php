<?php
if (!defined('ABSPATH')) {
    exit;
}

$total_customers = $customers_handler->get_total();
$total_themes = $themes_handler->get_total();
$total_forms = $forms_handler->get_total();
?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number"><?php echo $total_customers; ?></div>
                    <div class="stat-label">Total Customers</div>
                </div>
                <div class="stat-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <a href="<?php echo home_url('/spfm-dashboard/?section=customers'); ?>" class="btn btn-sm btn-outline-primary mt-3">
                View All <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number"><?php echo $total_themes; ?></div>
                    <div class="stat-label">Total Themes</div>
                </div>
                <div class="stat-icon bg-success">
                    <i class="fas fa-palette"></i>
                </div>
            </div>
            <a href="<?php echo home_url('/spfm-dashboard/?section=themes'); ?>" class="btn btn-sm btn-outline-success mt-3">
                View All <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number"><?php echo $total_forms; ?></div>
                    <div class="stat-label">Total Forms</div>
                </div>
                <div class="stat-icon bg-warning">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <a href="<?php echo home_url('/spfm-dashboard/?section=forms'); ?>" class="btn btn-sm btn-outline-danger mt-3">
                View All <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?php echo home_url('/spfm-dashboard/?section=customers&action=add'); ?>" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Add Customer
                    </a>
                    <a href="<?php echo home_url('/spfm-dashboard/?section=themes&action=add'); ?>" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Add Theme
                    </a>
                    <a href="<?php echo home_url('/spfm-dashboard/?section=forms&action=add'); ?>" class="btn btn-warning text-white">
                        <i class="fas fa-plus me-2"></i>Create Form
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Items -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Recent Customers</h5>
                <a href="<?php echo home_url('/spfm-dashboard/?section=customers'); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php
                $recent_customers = $customers_handler->get_all(array('per_page' => 5));
                if (empty($recent_customers)):
                ?>
                    <p class="text-muted text-center py-3">No customers yet.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($recent_customers as $cust): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo esc_html($cust->name); ?></strong>
                                    <br><small class="text-muted"><?php echo esc_html($cust->email); ?></small>
                                </div>
                                <span class="badge-status <?php echo $cust->status ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?php echo $cust->status ? 'Active' : 'Inactive'; ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Recent Forms</h5>
                <a href="<?php echo home_url('/spfm-dashboard/?section=forms'); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php
                $recent_forms = $forms_handler->get_all(array('per_page' => 5));
                if (empty($recent_forms)):
                ?>
                    <p class="text-muted text-center py-3">No forms yet.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($recent_forms as $form): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo esc_html($form->name); ?></strong>
                                    <br><small class="text-muted">Shortcode: [spfm_form id="<?php echo $form->id; ?>"]</small>
                                </div>
                                <span class="badge-status <?php echo $form->status ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?php echo $form->status ? 'Active' : 'Inactive'; ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
