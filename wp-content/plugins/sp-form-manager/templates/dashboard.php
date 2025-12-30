<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!SPFM_Auth::is_logged_in()) {
    wp_redirect(home_url('/spfm-login/'));
    exit;
}

$current_user_name = SPFM_Auth::get_current_user_name();
$current_user_role = SPFM_Auth::get_current_user_role();

$customers_handler = SPFM_Customers::get_instance();
$themes_handler = SPFM_Themes::get_instance();
$forms_handler = SPFM_Forms::get_instance();

$section = isset($_GET['section']) ? sanitize_text_field($_GET['section']) : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SP Form Manager - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            padding: 20px 0;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-brand {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            padding: 10px 25px 30px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .sidebar-menu li a i {
            margin-right: 12px;
            font-size: 1.2rem;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px 30px;
            min-height: 100vh;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .top-bar h1 {
            font-size: 1.5rem;
            color: #333;
            margin: 0;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-info .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
        }
        .btn-logout {
            padding: 8px 20px;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .btn-logout:hover {
            background: #c82333;
            color: #fff;
        }
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
        }
        .stat-card .icon.customers { background: linear-gradient(135deg, #667eea, #764ba2); }
        .stat-card .icon.themes { background: linear-gradient(135deg, #f093fb, #f5576c); }
        .stat-card .icon.forms { background: linear-gradient(135deg, #4facfe, #00f2fe); }
        .stat-card .info h3 {
            font-size: 2rem;
            color: #333;
            margin: 0;
        }
        .stat-card .info p {
            color: #999;
            margin: 5px 0 0;
        }
        .content-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 20px;
        }
        .content-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .content-card-header h2 {
            font-size: 1.3rem;
            color: #333;
            margin: 0;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            color: #fff;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #eee;
        }
        .badge-active {
            background: #d4edda;
            color: #155724;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .action-btns .btn {
            padding: 5px 12px;
            font-size: 0.85rem;
            margin-right: 5px;
        }
        .color-preview {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 3px;
            border: 1px solid #ddd;
            vertical-align: middle;
            margin-right: 5px;
        }
        /* Modal Styles */
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }
        .modal-header .btn-close {
            filter: invert(1);
        }
        .form-label {
            font-weight: 500;
            color: #555;
        }
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            .sidebar-brand {
                font-size: 0;
                text-align: center;
            }
            .sidebar-brand::first-letter {
                font-size: 1.5rem;
            }
            .sidebar-menu li a span {
                display: none;
            }
            .main-content {
                margin-left: 70px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-grid-3x3-gap-fill"></i> SP Form
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="?section=dashboard" class="<?php echo $section === 'dashboard' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="?section=customers" class="<?php echo $section === 'customers' ? 'active' : ''; ?>">
                    <i class="bi bi-people"></i>
                    <span>Customers</span>
                </a>
            </li>
            <li>
                <a href="?section=themes" class="<?php echo $section === 'themes' ? 'active' : ''; ?>">
                    <i class="bi bi-palette"></i>
                    <span>Themes</span>
                </a>
            </li>
            <li>
                <a href="?section=forms" class="<?php echo $section === 'forms' ? 'active' : ''; ?>">
                    <i class="bi bi-ui-checks-grid"></i>
                    <span>Forms</span>
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <h1><?php echo ucfirst($section); ?></h1>
            <div class="user-info">
                <div class="avatar"><?php echo strtoupper(substr($current_user_name, 0, 1)); ?></div>
                <span><?php echo esc_html($current_user_name); ?></span>
                <a href="<?php echo home_url('/?spfm_logout=1'); ?>" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
        
        <?php if ($section === 'dashboard'): ?>
            <!-- Dashboard Content -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="icon customers">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="info">
                        <h3><?php echo $customers_handler->get_total(); ?></h3>
                        <p>Total Customers</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon themes">
                        <i class="bi bi-palette"></i>
                    </div>
                    <div class="info">
                        <h3><?php echo $themes_handler->get_total(); ?></h3>
                        <p>Total Themes</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="icon forms">
                        <i class="bi bi-ui-checks-grid"></i>
                    </div>
                    <div class="info">
                        <h3><?php echo $forms_handler->get_total(); ?></h3>
                        <p>Total Forms</p>
                    </div>
                </div>
            </div>
            
            <div class="content-card">
                <h3>Welcome to SP Form Manager!</h3>
                <p>Use the sidebar menu to navigate between Customers, Themes, and Forms. Each section allows you to perform full CRUD operations.</p>
            </div>
            
        <?php elseif ($section === 'customers'): ?>
            <!-- Customers Section -->
            <?php
            $customers = $customers_handler->get_all(array('per_page' => 50));
            ?>
            <div class="content-card">
                <div class="content-card-header">
                    <h2><i class="bi bi-people"></i> Customers</h2>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#customerModal" onclick="resetCustomerForm()">
                        <i class="bi bi-plus-lg"></i> Add Customer
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customers-table-body">
                            <?php if (empty($customers)): ?>
                                <tr><td colspan="7" class="text-center">No customers found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($customers as $c): ?>
                                    <tr data-id="<?php echo $c->id; ?>">
                                        <td><?php echo $c->id; ?></td>
                                        <td><strong><?php echo esc_html($c->name); ?></strong></td>
                                        <td><?php echo esc_html($c->email); ?></td>
                                        <td><?php echo esc_html($c->phone); ?></td>
                                        <td><?php echo esc_html($c->company); ?></td>
                                        <td>
                                            <span class="<?php echo $c->status ? 'badge-active' : 'badge-inactive'; ?>">
                                                <?php echo $c->status ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="action-btns">
                                            <button class="btn btn-sm btn-outline-primary edit-customer" data-id="<?php echo $c->id; ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-customer" data-id="<?php echo $c->id; ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        <?php elseif ($section === 'themes'): ?>
            <!-- Themes Section -->
            <?php
            $themes = $themes_handler->get_all(array('per_page' => 50));
            ?>
            <div class="content-card">
                <div class="content-card-header">
                    <h2><i class="bi bi-palette"></i> Themes</h2>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#themeModal" onclick="resetThemeForm()">
                        <i class="bi bi-plus-lg"></i> Add Theme
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Colors</th>
                                <th>Font</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="themes-table-body">
                            <?php if (empty($themes)): ?>
                                <tr><td colspan="6" class="text-center">No themes found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($themes as $t): ?>
                                    <tr data-id="<?php echo $t->id; ?>">
                                        <td><?php echo $t->id; ?></td>
                                        <td><strong><?php echo esc_html($t->name); ?></strong></td>
                                        <td>
                                            <span class="color-preview" style="background-color: <?php echo esc_attr($t->primary_color); ?>" title="Primary"></span>
                                            <span class="color-preview" style="background-color: <?php echo esc_attr($t->secondary_color); ?>" title="Secondary"></span>
                                            <span class="color-preview" style="background-color: <?php echo esc_attr($t->background_color); ?>" title="Background"></span>
                                        </td>
                                        <td><?php echo esc_html($t->font_family); ?></td>
                                        <td>
                                            <span class="<?php echo $t->status ? 'badge-active' : 'badge-inactive'; ?>">
                                                <?php echo $t->status ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="action-btns">
                                            <button class="btn btn-sm btn-outline-primary edit-theme" data-id="<?php echo $t->id; ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-theme" data-id="<?php echo $t->id; ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        <?php elseif ($section === 'forms'): ?>
            <!-- Forms Section -->
            <?php
            $forms = $forms_handler->get_all(array('per_page' => 50));
            $all_themes = $themes_handler->get_all_active();
            $field_types = $forms_handler->get_field_types();
            ?>
            <div class="content-card">
                <div class="content-card-header">
                    <h2><i class="bi bi-ui-checks-grid"></i> Forms</h2>
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#formModal" onclick="resetFormForm()">
                        <i class="bi bi-plus-lg"></i> Add Form
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Theme</th>
                                <th>Fields</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="forms-table-body">
                            <?php if (empty($forms)): ?>
                                <tr><td colspan="6" class="text-center">No forms found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($forms as $f): ?>
                                    <?php $field_count = count($forms_handler->get_fields($f->id)); ?>
                                    <tr data-id="<?php echo $f->id; ?>">
                                        <td><?php echo $f->id; ?></td>
                                        <td><strong><?php echo esc_html($f->name); ?></strong></td>
                                        <td><?php echo $f->theme_name ? esc_html($f->theme_name) : '-'; ?></td>
                                        <td><span class="badge bg-secondary"><?php echo $field_count; ?> fields</span></td>
                                        <td>
                                            <span class="<?php echo $f->status ? 'badge-active' : 'badge-inactive'; ?>">
                                                <?php echo $f->status ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="action-btns">
                                            <button class="btn btn-sm btn-outline-success manage-fields" data-id="<?php echo $f->id; ?>" data-name="<?php echo esc_attr($f->name); ?>">
                                                <i class="bi bi-list-check"></i> Fields
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary edit-form" data-id="<?php echo $f->id; ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-form" data-id="<?php echo $f->id; ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Customer Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerModalTitle">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="customer-form">
                        <input type="hidden" name="id" id="customer_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" id="customer_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" id="customer_email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" id="customer_phone" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company</label>
                                <input type="text" name="company" id="customer_company" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" id="customer_address" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" id="customer_city" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" id="customer_state" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" id="customer_country" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" name="zip_code" id="customer_zip_code" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" id="customer_status" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" id="customer_notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" id="save-customer">Save Customer</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Theme Modal -->
    <div class="modal fade" id="themeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="themeModalTitle">Add Theme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="theme-form">
                        <input type="hidden" name="id" id="theme_id">
                        <div class="mb-3">
                            <label class="form-label">Theme Name *</label>
                            <input type="text" name="name" id="theme_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="theme_description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Primary Color</label>
                                <input type="color" name="primary_color" id="theme_primary_color" class="form-control form-control-color" value="#007bff">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Secondary Color</label>
                                <input type="color" name="secondary_color" id="theme_secondary_color" class="form-control form-control-color" value="#6c757d">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Background Color</label>
                                <input type="color" name="background_color" id="theme_background_color" class="form-control form-control-color" value="#ffffff">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Text Color</label>
                                <input type="color" name="text_color" id="theme_text_color" class="form-control form-control-color" value="#333333">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Font Family</label>
                            <select name="font_family" id="theme_font_family" class="form-select">
                                <option value="Arial, sans-serif">Arial</option>
                                <option value="Helvetica, sans-serif">Helvetica</option>
                                <option value="Georgia, serif">Georgia</option>
                                <option value="Times New Roman, serif">Times New Roman</option>
                                <option value="Verdana, sans-serif">Verdana</option>
                                <option value="Roboto, sans-serif">Roboto</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Custom CSS</label>
                            <textarea name="custom_css" id="theme_custom_css" class="form-control" rows="4" placeholder="/* Add custom styles */"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="theme_status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" id="save-theme">Save Theme</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Modal -->
    <div class="modal fade" id="formModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalTitle">Add Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-form">
                        <input type="hidden" name="id" id="form_id">
                        <div class="mb-3">
                            <label class="form-label">Form Name *</label>
                            <input type="text" name="name" id="form_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="form_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Theme</label>
                            <select name="theme_id" id="form_theme_id" class="form-select">
                                <option value="">Select Theme...</option>
                                <?php foreach ($all_themes as $t): ?>
                                    <option value="<?php echo $t->id; ?>"><?php echo esc_html($t->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="form_status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" id="save-form">Save Form</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Fields Modal -->
    <div class="modal fade" id="fieldsModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Fields: <span id="fields-form-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Form Fields</h6>
                        <button class="btn btn-sm btn-primary-custom" id="add-field-btn">
                            <i class="bi bi-plus-lg"></i> Add Field
                        </button>
                    </div>
                    <input type="hidden" id="current_form_id">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Label</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Required</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="fields-table-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Field Modal -->
    <div class="modal fade" id="fieldModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fieldModalTitle">Add Field</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="field-form">
                        <input type="hidden" name="id" id="field_id">
                        <input type="hidden" name="form_id" id="field_form_id">
                        <div class="mb-3">
                            <label class="form-label">Field Label *</label>
                            <input type="text" name="field_label" id="field_label" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Field Name *</label>
                            <input type="text" name="field_name" id="field_name" class="form-control" required pattern="[a-z0-9_-]+">
                            <small class="text-muted">Lowercase letters, numbers, underscores only</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Field Type *</label>
                            <select name="field_type" id="field_type" class="form-select" required>
                                <?php foreach ($field_types as $value => $label): ?>
                                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3" id="options-group">
                            <label class="form-label">Options</label>
                            <textarea name="field_options" id="field_options" class="form-control" rows="3" placeholder="One option per line"></textarea>
                            <small class="text-muted">For select, radio, and checkbox fields</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Placeholder</label>
                            <input type="text" name="placeholder" id="field_placeholder" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Default Value</label>
                            <input type="text" name="default_value" id="field_default_value" class="form-control">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_required" id="field_is_required" class="form-check-input" value="1">
                            <label class="form-check-label">Required Field</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">CSS Class</label>
                            <input type="text" name="css_class" id="field_css_class" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="field_status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary-custom" id="save-field">Save Field</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        const nonce = '<?php echo wp_create_nonce('spfm_nonce'); ?>';
        
        // Reset forms
        function resetCustomerForm() {
            document.getElementById('customer-form').reset();
            document.getElementById('customer_id').value = '';
            document.getElementById('customerModalTitle').textContent = 'Add Customer';
        }
        
        function resetThemeForm() {
            document.getElementById('theme-form').reset();
            document.getElementById('theme_id').value = '';
            document.getElementById('theme_primary_color').value = '#007bff';
            document.getElementById('theme_secondary_color').value = '#6c757d';
            document.getElementById('theme_background_color').value = '#ffffff';
            document.getElementById('theme_text_color').value = '#333333';
            document.getElementById('themeModalTitle').textContent = 'Add Theme';
        }
        
        function resetFormForm() {
            document.getElementById('form-form').reset();
            document.getElementById('form_id').value = '';
            document.getElementById('formModalTitle').textContent = 'Add Form';
        }
        
        function resetFieldForm() {
            document.getElementById('field-form').reset();
            document.getElementById('field_id').value = '';
            document.getElementById('fieldModalTitle').textContent = 'Add Field';
        }
        
        $(document).ready(function() {
            // Toggle options field based on type
            $('#field_type').on('change', function() {
                var needsOptions = ['select', 'radio', 'checkbox'].includes($(this).val());
                $('#options-group').toggle(needsOptions);
            });
            
            // Auto-generate field name
            $('#field_label').on('input', function() {
                if (!$('#field_name').val()) {
                    var name = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '');
                    $('#field_name').val(name);
                }
            });
            
            // ==================== CUSTOMERS ====================
            // Save Customer
            $('#save-customer').on('click', function() {
                var formData = $('#customer-form').serialize();
                formData += '&action=spfm_save_customer&nonce=' + nonce;
                
                $.post(ajaxUrl, formData, function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message);
                    }
                });
            });
            
            // Edit Customer
            $(document).on('click', '.edit-customer', function() {
                var id = $(this).data('id');
                $.post(ajaxUrl, { action: 'spfm_get_customer', nonce: nonce, id: id }, function(response) {
                    if (response.success) {
                        var c = response.data.customer;
                        $('#customer_id').val(c.id);
                        $('#customer_name').val(c.name);
                        $('#customer_email').val(c.email);
                        $('#customer_phone').val(c.phone);
                        $('#customer_company').val(c.company);
                        $('#customer_address').val(c.address);
                        $('#customer_city').val(c.city);
                        $('#customer_state').val(c.state);
                        $('#customer_country').val(c.country);
                        $('#customer_zip_code').val(c.zip_code);
                        $('#customer_notes').val(c.notes);
                        $('#customer_status').val(c.status);
                        $('#customerModalTitle').text('Edit Customer');
                        new bootstrap.Modal(document.getElementById('customerModal')).show();
                    }
                });
            });
            
            // Delete Customer
            $(document).on('click', '.delete-customer', function() {
                if (!confirm('Are you sure?')) return;
                var id = $(this).data('id');
                var $row = $(this).closest('tr');
                $.post(ajaxUrl, { action: 'spfm_delete_customer', nonce: nonce, id: id }, function(response) {
                    if (response.success) {
                        $row.fadeOut(300, function() { $(this).remove(); });
                    } else {
                        alert(response.data.message);
                    }
                });
            });
            
            // ==================== THEMES ====================
            // Save Theme
            $('#save-theme').on('click', function() {
                var formData = $('#theme-form').serialize();
                formData += '&action=spfm_save_theme&nonce=' + nonce;
                
                $.post(ajaxUrl, formData, function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message);
                    }
                });
            });
            
            // Edit Theme
            $(document).on('click', '.edit-theme', function() {
                var id = $(this).data('id');
                $.post(ajaxUrl, { action: 'spfm_get_theme', nonce: nonce, id: id }, function(response) {
                    if (response.success) {
                        var t = response.data.theme;
                        $('#theme_id').val(t.id);
                        $('#theme_name').val(t.name);
                        $('#theme_description').val(t.description);
                        $('#theme_primary_color').val(t.primary_color);
                        $('#theme_secondary_color').val(t.secondary_color);
                        $('#theme_background_color').val(t.background_color);
                        $('#theme_text_color').val(t.text_color);
                        $('#theme_font_family').val(t.font_family);
                        $('#theme_custom_css').val(t.custom_css);
                        $('#theme_status').val(t.status);
                        $('#themeModalTitle').text('Edit Theme');
                        new bootstrap.Modal(document.getElementById('themeModal')).show();
                    }
                });
            });
            
            // Delete Theme
            $(document).on('click', '.delete-theme', function() {
                if (!confirm('Are you sure?')) return;
                var id = $(this).data('id');
                var $row = $(this).closest('tr');
                $.post(ajaxUrl, { action: 'spfm_delete_theme', nonce: nonce, id: id }, function(response) {
                    if (response.success) {
                        $row.fadeOut(300, function() { $(this).remove(); });
                    } else {
                        alert(response.data.message);
                    }
                });
            });
            
            // ==================== FORMS ====================
            // Save Form
            $('#save-form').on('click', function() {
                var formData = $('#form-form').serialize();
                formData += '&action=spfm_save_form&nonce=' + nonce;
                
                $.post(ajaxUrl, formData, function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message);
                    }
                });
            });
            
            // Edit Form
            $(document).on('click', '.edit-form', function() {
                var id = $(this).data('id');
                $.post(ajaxUrl, { action: 'spfm_get_form', nonce: nonce, id: id }, function(response) {
                    if (response.success) {
                        var f = response.data.form;
                        $('#form_id').val(f.id);
                        $('#form_name').val(f.name);
                        $('#form_description').val(f.description);
                        $('#form_theme_id').val(f.theme_id);
                        $('#form_status').val(f.status);
                        $('#formModalTitle').text('Edit Form');
                        new bootstrap.Modal(document.getElementById('formModal')).show();
                    }
                });
            });
            
            // Delete Form
            $(document).on('click', '.delete-form', function() {
                if (!confirm('Are you sure? All fields will be deleted too.')) return;
                var id = $(this).data('id');
                var $row = $(this).closest('tr');
                $.post(ajaxUrl, { action: 'spfm_delete_form', nonce: nonce, id: id }, function(response) {
                    if (response.success) {
                        $row.fadeOut(300, function() { $(this).remove(); });
                    } else {
                        alert(response.data.message);
                    }
                });
            });
            
            // ==================== FORM FIELDS ====================
            // Manage Fields
            $(document).on('click', '.manage-fields', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#current_form_id').val(id);
                $('#fields-form-name').text(name);
                loadFields(id);
                new bootstrap.Modal(document.getElementById('fieldsModal')).show();
            });
            
            function loadFields(formId) {
                $.post(ajaxUrl, { action: 'spfm_get_form', nonce: nonce, id: formId }, function(response) {
                    if (response.success) {
                        var fields = response.data.fields;
                        var html = '';
                        if (fields.length === 0) {
                            html = '<tr><td colspan="6" class="text-center">No fields yet.</td></tr>';
                        } else {
                            fields.forEach(function(f) {
                                html += '<tr data-id="' + f.id + '">';
                                html += '<td>' + f.field_label + '</td>';
                                html += '<td><code>' + f.field_name + '</code></td>';
                                html += '<td>' + f.field_type + '</td>';
                                html += '<td>' + (f.is_required == 1 ? '<i class="bi bi-check-circle text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>') + '</td>';
                                html += '<td><span class="' + (f.status == 1 ? 'badge-active' : 'badge-inactive') + '">' + (f.status == 1 ? 'Active' : 'Inactive') + '</span></td>';
                                html += '<td>';
                                html += '<button class="btn btn-sm btn-outline-primary edit-field" data-id="' + f.id + '"><i class="bi bi-pencil"></i></button> ';
                                html += '<button class="btn btn-sm btn-outline-danger delete-field" data-id="' + f.id + '"><i class="bi bi-trash"></i></button>';
                                html += '</td></tr>';
                            });
                        }
                        $('#fields-table-body').html(html);
                    }
                });
            }
            
            // Add Field Button
            $('#add-field-btn').on('click', function() {
                resetFieldForm();
                $('#field_form_id').val($('#current_form_id').val());
                $('#options-group').hide();
                new bootstrap.Modal(document.getElementById('fieldModal')).show();
            });
            
            // Save Field
            $('#save-field').on('click', function() {
                var formData = $('#field-form').serialize();
                if (!$('#field_is_required').is(':checked')) {
                    formData += '&is_required=0';
                }
                formData += '&action=spfm_save_field&nonce=' + nonce;
                
                $.post(ajaxUrl, formData, function(response) {
                    if (response.success) {
                        bootstrap.Modal.getInstance(document.getElementById('fieldModal')).hide();
                        loadFields($('#current_form_id').val());
                    } else {
                        alert(response.data.message);
                    }
                });
            });
            
            // Edit Field
            $(document).on('click', '.edit-field', function() {
                var id = $(this).data('id');
                $.post(ajaxUrl, { action: 'spfm_get_field', nonce: nonce, id: id }, function(response) {
                    if (response.success) {
                        var f = response.data.field;
                        $('#field_id').val(f.id);
                        $('#field_form_id').val(f.form_id);
                        $('#field_label').val(f.field_label);
                        $('#field_name').val(f.field_name);
                        $('#field_type').val(f.field_type);
                        $('#field_options').val(f.field_options);
                        $('#field_placeholder').val(f.placeholder);
                        $('#field_default_value').val(f.default_value);
                        $('#field_is_required').prop('checked', f.is_required == 1);
                        $('#field_css_class').val(f.css_class);
                        $('#field_status').val(f.status);
                        
                        var needsOptions = ['select', 'radio', 'checkbox'].includes(f.field_type);
                        $('#options-group').toggle(needsOptions);
                        
                        $('#fieldModalTitle').text('Edit Field');
                        new bootstrap.Modal(document.getElementById('fieldModal')).show();
                    }
                });
            });
            
            // Delete Field
            $(document).on('click', '.delete-field', function() {
                if (!confirm('Are you sure?')) return;
                var id = $(this).data('id');
                $.post(ajaxUrl, { action: 'spfm_delete_field', nonce: nonce, id: id }, function(response) {
                    if (response.success) {
                        loadFields($('#current_form_id').val());
                    } else {
                        alert(response.data.message);
                    }
                });
            });
        });
    </script>
</body>
</html>
