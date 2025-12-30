<?php
/**
 * Submission Preview Template
 * Renders a preview of the customer's submitted website
 */

if (!defined('ABSPATH')) {
    exit;
}

// Variables: $theme, $submission, $page_contents, $color_customizations
$primary = $color_customizations['primary_color'] ?? $theme->primary_color;
$secondary = $color_customizations['secondary_color'] ?? $theme->secondary_color;
$accent = $color_customizations['accent_color'] ?? $theme->accent_color ?? $primary;
$background = $color_customizations['background_color'] ?? $theme->background_color;
$text_color = $color_customizations['text_color'] ?? $theme->text_color;
$header_bg = $color_customizations['header_bg_color'] ?? $theme->header_bg_color ?? '#ffffff';
$footer_bg = $theme->footer_bg_color ?? '#1f2937';
$font = $theme->font_family ?? 'Inter';
$heading_font = $theme->heading_font ?? 'Poppins';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Preview</title>
    <link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($font); ?>:wght@400;500;600;700&family=<?php echo urlencode($heading_font); ?>:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: '<?php echo $font; ?>', sans-serif;
            background: <?php echo $background; ?>;
            color: <?php echo $text_color; ?>;
            line-height: 1.6;
        }
        
        /* Navigation */
        .site-nav {
            background: <?php echo $header_bg; ?>;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .site-logo {
            font-family: '<?php echo $heading_font; ?>', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: <?php echo $header_bg === '#ffffff' ? $primary : '#fff'; ?>;
            text-decoration: none;
        }
        
        .nav-menu {
            display: flex;
            gap: 30px;
            list-style: none;
        }
        
        .nav-menu a {
            color: <?php echo $header_bg === '#ffffff' ? $text_color : 'rgba(255,255,255,0.9)'; ?>;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .nav-menu a:hover {
            color: <?php echo $primary; ?>;
        }
        
        .nav-menu a.active {
            color: <?php echo $primary; ?>;
        }
        
        /* Page Tabs */
        .page-tabs {
            background: #f8f9fa;
            padding: 10px 30px;
            border-bottom: 1px solid #eee;
        }
        
        .page-tabs-inner {
            display: flex;
            gap: 10px;
            overflow-x: auto;
        }
        
        .page-tab {
            padding: 10px 20px;
            background: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.3s;
            white-space: nowrap;
        }
        
        .page-tab:hover {
            background: #e9ecef;
        }
        
        .page-tab.active {
            background: <?php echo $primary; ?>;
            color: #fff;
        }
        
        /* Page Content */
        .page-content {
            display: none;
        }
        
        .page-content.active {
            display: block;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, <?php echo $primary; ?> 0%, <?php echo $secondary; ?> 100%);
            padding: 100px 30px;
            text-align: center;
            color: #fff;
        }
        
        .hero-section h1 {
            font-family: '<?php echo $heading_font; ?>', sans-serif;
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .hero-section p {
            font-size: 20px;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 30px;
        }
        
        .btn-primary {
            display: inline-block;
            background: <?php echo $accent; ?>;
            color: #fff;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        /* Content Section */
        .content-section {
            padding: 80px 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            font-family: '<?php echo $heading_font; ?>', sans-serif;
            font-size: 36px;
            text-align: center;
            margin-bottom: 15px;
            color: <?php echo $primary; ?>;
        }
        
        .section-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 50px;
            font-size: 18px;
        }
        
        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        
        .feature-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 30px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, <?php echo $primary; ?>, <?php echo $secondary; ?>);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 32px;
            color: #fff;
        }
        
        .feature-card h3 {
            font-family: '<?php echo $heading_font; ?>', sans-serif;
            margin-bottom: 15px;
            font-size: 22px;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.7;
        }
        
        /* About Section */
        .about-section {
            background: #f8f9fa;
            padding: 80px 30px;
        }
        
        .about-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .about-content h2 {
            font-family: '<?php echo $heading_font; ?>', sans-serif;
            font-size: 36px;
            margin-bottom: 25px;
            color: <?php echo $primary; ?>;
        }
        
        .about-content p {
            font-size: 18px;
            line-height: 1.8;
            color: #555;
        }
        
        /* Contact Section */
        .contact-section {
            padding: 80px 30px;
            background: <?php echo $background; ?>;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .contact-item {
            text-align: center;
        }
        
        .contact-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, <?php echo $primary; ?>, <?php echo $secondary; ?>);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            color: #fff;
        }
        
        .contact-item h4 {
            margin-bottom: 10px;
            color: <?php echo $primary; ?>;
        }
        
        .contact-item p {
            color: #666;
        }
        
        /* Footer */
        .site-footer {
            background: <?php echo $footer_bg; ?>;
            color: #fff;
            padding: 50px 30px 30px;
            text-align: center;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: <?php echo $primary; ?>;
        }
        
        .footer-copyright {
            font-size: 14px;
            opacity: 0.7;
        }
        
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            .hero-section h1 {
                font-size: 32px;
            }
            .features-grid, .contact-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="site-nav">
        <a href="#" class="site-logo">
            <?php echo esc_html($page_contents['page_0_sec_0_headline'] ?? 'Your Brand'); ?>
        </a>
        <ul class="nav-menu">
            <?php foreach ($theme->pages as $page): ?>
                <li><a href="#<?php echo esc_attr($page->page_slug); ?>"><?php echo esc_html($page->page_name); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
    
    <!-- Page Tabs -->
    <div class="page-tabs">
        <div class="page-tabs-inner">
            <?php foreach ($theme->pages as $index => $page): ?>
                <button class="page-tab <?php echo $index === 0 ? 'active' : ''; ?>" 
                        onclick="showPage(<?php echo $index; ?>, this)">
                    <?php echo esc_html($page->page_name); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Pages Content -->
    <?php foreach ($theme->pages as $page_index => $page): ?>
        <div class="page-content <?php echo $page_index === 0 ? 'active' : ''; ?>" id="page-<?php echo $page_index; ?>">
            <?php
            // Render sections based on type
            foreach ($page->sections as $sec_index => $section):
                $section_key = "page_{$page_index}_sec_{$sec_index}";
                
                switch ($section->section_type):
                    case 'hero':
                        $headline = $page_contents[$section_key . '_headline'] ?? 'Welcome to Our Website';
                        $subheadline = $page_contents[$section_key . '_subheadline'] ?? 'Your success story starts here';
                        $cta_text = $page_contents[$section_key . '_cta_text'] ?? 'Get Started';
                        ?>
                        <section class="hero-section">
                            <h1><?php echo esc_html($headline); ?></h1>
                            <p><?php echo esc_html($subheadline); ?></p>
                            <a href="#contact" class="btn-primary"><?php echo esc_html($cta_text); ?></a>
                        </section>
                        <?php
                        break;
                        
                    case 'features':
                    case 'services':
                        $section_title = $page_contents[$section_key . '_section_title'] ?? $page_contents[$section_key . '_services_title'] ?? 'Our Services';
                        $section_desc = $page_contents[$section_key . '_section_description'] ?? $page_contents[$section_key . '_services_intro'] ?? '';
                        ?>
                        <section class="content-section">
                            <h2 class="section-title"><?php echo esc_html($section_title); ?></h2>
                            <?php if ($section_desc): ?>
                                <p class="section-subtitle"><?php echo esc_html($section_desc); ?></p>
                            <?php endif; ?>
                            <div class="features-grid">
                                <div class="feature-card">
                                    <div class="feature-icon"><i class="bi bi-star"></i></div>
                                    <h3>Quality Service</h3>
                                    <p>We provide top-notch services that exceed expectations.</p>
                                </div>
                                <div class="feature-card">
                                    <div class="feature-icon"><i class="bi bi-lightning"></i></div>
                                    <h3>Fast Delivery</h3>
                                    <p>Quick turnaround times without compromising quality.</p>
                                </div>
                                <div class="feature-card">
                                    <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                                    <h3>Reliable Support</h3>
                                    <p>24/7 support to help you whenever you need us.</p>
                                </div>
                            </div>
                        </section>
                        <?php
                        break;
                        
                    case 'content':
                        $title = $page_contents[$section_key . '_about_title'] ?? $page_contents[$section_key . '_section_title'] ?? $section->section_name;
                        $content = $page_contents[$section_key . '_about_content'] ?? $page_contents[$section_key . '_content'] ?? 'Content goes here...';
                        ?>
                        <section class="about-section">
                            <div class="about-content">
                                <h2><?php echo esc_html($title); ?></h2>
                                <p><?php echo nl2br(esc_html($content)); ?></p>
                            </div>
                        </section>
                        <?php
                        break;
                        
                    case 'team':
                        $title = $page_contents[$section_key . '_team_title'] ?? 'Meet Our Team';
                        ?>
                        <section class="content-section">
                            <h2 class="section-title"><?php echo esc_html($title); ?></h2>
                            <div class="features-grid">
                                <div class="feature-card">
                                    <div class="feature-icon"><i class="bi bi-person"></i></div>
                                    <h3>Team Member</h3>
                                    <p>Position / Role</p>
                                </div>
                                <div class="feature-card">
                                    <div class="feature-icon"><i class="bi bi-person"></i></div>
                                    <h3>Team Member</h3>
                                    <p>Position / Role</p>
                                </div>
                                <div class="feature-card">
                                    <div class="feature-icon"><i class="bi bi-person"></i></div>
                                    <h3>Team Member</h3>
                                    <p>Position / Role</p>
                                </div>
                            </div>
                        </section>
                        <?php
                        break;
                        
                    case 'contact':
                        $contact_title = $page_contents[$section_key . '_contact_title'] ?? 'Contact Us';
                        $address = $page_contents[$section_key . '_address'] ?? 'Your Address';
                        $phone = $page_contents[$section_key . '_phone'] ?? 'Your Phone';
                        $email = $page_contents[$section_key . '_email'] ?? 'Your Email';
                        ?>
                        <section class="contact-section" id="contact">
                            <h2 class="section-title"><?php echo esc_html($contact_title); ?></h2>
                            <p class="section-subtitle">We'd love to hear from you</p>
                            <div class="contact-grid">
                                <div class="contact-item">
                                    <div class="contact-icon"><i class="bi bi-geo-alt"></i></div>
                                    <h4>Address</h4>
                                    <p><?php echo nl2br(esc_html($address)); ?></p>
                                </div>
                                <div class="contact-item">
                                    <div class="contact-icon"><i class="bi bi-telephone"></i></div>
                                    <h4>Phone</h4>
                                    <p><?php echo esc_html($phone); ?></p>
                                </div>
                                <div class="contact-item">
                                    <div class="contact-icon"><i class="bi bi-envelope"></i></div>
                                    <h4>Email</h4>
                                    <p><?php echo esc_html($email); ?></p>
                                </div>
                            </div>
                        </section>
                        <?php
                        break;
                        
                    case 'social':
                        // Social links rendered in footer
                        break;
                        
                    default:
                        ?>
                        <section class="content-section">
                            <h2 class="section-title"><?php echo esc_html($section->section_name); ?></h2>
                            <p class="section-subtitle">Section content preview</p>
                        </section>
                        <?php
                        break;
                endswitch;
            endforeach;
            ?>
        </div>
    <?php endforeach; ?>
    
    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="social-links">
                <?php
                // Get social links from contact section
                $facebook = '';
                $twitter = '';
                $instagram = '';
                $linkedin = '';
                
                foreach ($theme->pages as $pi => $pg) {
                    foreach ($pg->sections as $si => $sec) {
                        if ($sec->section_type === 'social') {
                            $sk = "page_{$pi}_sec_{$si}";
                            $facebook = $page_contents[$sk . '_facebook'] ?? '';
                            $twitter = $page_contents[$sk . '_twitter'] ?? '';
                            $instagram = $page_contents[$sk . '_instagram'] ?? '';
                            $linkedin = $page_contents[$sk . '_linkedin'] ?? '';
                        }
                    }
                }
                ?>
                <?php if ($facebook): ?><a href="<?php echo esc_url($facebook); ?>"><i class="bi bi-facebook"></i></a><?php endif; ?>
                <?php if ($twitter): ?><a href="<?php echo esc_url($twitter); ?>"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
                <?php if ($instagram): ?><a href="<?php echo esc_url($instagram); ?>"><i class="bi bi-instagram"></i></a><?php endif; ?>
                <?php if ($linkedin): ?><a href="<?php echo esc_url($linkedin); ?>"><i class="bi bi-linkedin"></i></a><?php endif; ?>
                <?php if (!$facebook && !$twitter && !$instagram && !$linkedin): ?>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                <?php endif; ?>
            </div>
            <p class="footer-copyright">
                © <?php echo date('Y'); ?> <?php echo esc_html($page_contents['page_0_sec_0_headline'] ?? 'Your Company'); ?>. All rights reserved.
            </p>
        </div>
    </footer>
    
    <script>
        function showPage(index, btn) {
            // Update tabs
            document.querySelectorAll('.page-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            
            // Update content
            document.querySelectorAll('.page-content').forEach(p => p.classList.remove('active'));
            document.getElementById('page-' + index).classList.add('active');
            
            // Scroll to top
            window.scrollTo(0, 0);
        }
    </script>
</body>
</html>
