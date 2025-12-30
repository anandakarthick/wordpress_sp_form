<?php
if (!defined('ABSPATH')) {
    exit;
}

$login_error = isset($_SESSION['spfm_login_error']) ? $_SESSION['spfm_login_error'] : '';
$register_error = isset($_SESSION['spfm_register_error']) ? $_SESSION['spfm_register_error'] : '';
$register_success = isset($_SESSION['spfm_register_success']) ? $_SESSION['spfm_register_success'] : '';

unset($_SESSION['spfm_login_error']);
unset($_SESSION['spfm_register_error']);
unset($_SESSION['spfm_register_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SP Form Manager - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
            min-height: 500px;
        }
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 40px;
            color: #fff;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-left h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .login-left p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        .login-left .features {
            margin-top: 30px;
        }
        .login-left .features li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .login-left .features li::before {
            content: "✓";
            margin-right: 10px;
            background: rgba(255,255,255,0.2);
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        .login-right {
            padding: 60px 40px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-right h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
        }
        .form-tab {
            flex: 1;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            color: #999;
            font-weight: 600;
            transition: all 0.3s;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
        }
        .form-tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        .form-tab:hover {
            color: #667eea;
        }
        .form-content {
            display: none;
        }
        .form-content.active {
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .alert-danger {
            background: #ffe6e6;
            color: #dc3545;
            border: 1px solid #ffcccc;
        }
        .alert-success {
            background: #e6ffe6;
            color: #28a745;
            border: 1px solid #ccffcc;
        }
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-left {
                padding: 40px 30px;
            }
            .login-right {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <h1>SP Form Manager</h1>
            <p>A powerful form management system to create, manage, and track your forms with custom fields.</p>
            <ul class="features">
                <li>Manage Customers</li>
                <li>Customize Themes</li>
                <li>Create Dynamic Forms</li>
                <li>Custom Field Builder</li>
                <li>Form Submissions Tracking</li>
            </ul>
        </div>
        <div class="login-right">
            <div class="form-tabs">
                <div class="form-tab active" data-tab="login">Login</div>
                <div class="form-tab" data-tab="register">Register</div>
            </div>
            
            <!-- Login Form -->
            <div class="form-content active" id="login-form">
                <?php if ($login_error): ?>
                    <div class="alert alert-danger"><?php echo esc_html($login_error); ?></div>
                <?php endif; ?>
                
                <?php if ($register_success): ?>
                    <div class="alert alert-success"><?php echo esc_html($register_success); ?></div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <?php wp_nonce_field('spfm_login', 'spfm_login_nonce'); ?>
                    
                    <div class="form-group">
                        <label for="username">Username or Email</label>
                        <input type="text" name="username" id="username" required placeholder="Enter your username or email">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required placeholder="Enter your password">
                    </div>
                    
                    <button type="submit" name="spfm_login_submit" class="btn-submit">Login</button>
                </form>
            </div>
            
            <!-- Register Form -->
            <div class="form-content" id="register-form">
                <?php if ($register_error): ?>
                    <div class="alert alert-danger"><?php echo esc_html($register_error); ?></div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <?php wp_nonce_field('spfm_register', 'spfm_register_nonce'); ?>
                    
                    <div class="form-group">
                        <label for="reg_full_name">Full Name</label>
                        <input type="text" name="full_name" id="reg_full_name" required placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_username">Username</label>
                        <input type="text" name="username" id="reg_username" required placeholder="Choose a username">
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_email">Email</label>
                        <input type="email" name="email" id="reg_email" required placeholder="Enter your email">
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_password">Password</label>
                        <input type="password" name="password" id="reg_password" required placeholder="Choose a password" minlength="6">
                    </div>
                    
                    <button type="submit" name="spfm_register_submit" class="btn-submit">Register</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.querySelectorAll('.form-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.form-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.form-content').forEach(c => c.classList.remove('active'));
                
                this.classList.add('active');
                document.getElementById(this.dataset.tab + '-form').classList.add('active');
            });
        });
    </script>
</body>
</html>
