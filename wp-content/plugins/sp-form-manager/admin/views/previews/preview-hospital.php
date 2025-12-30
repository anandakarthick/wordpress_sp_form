<?php
/**
 * Preview: General Hospital - Multi-Department Professional
 * Uses site_content values for all editable fields
 */

// Get values from site_content (set in template-preview.php)
$businessName = get_site_content($site_content, 'business_name', 'City General Hospital');
$tagline = get_site_content($site_content, 'tagline', 'Excellence in Healthcare');
$aboutShort = get_site_content($site_content, 'about_short', 'We have been serving our community for over 50 years, providing exceptional healthcare with compassion and excellence.');

$phone = get_site_content($site_content, 'phone', '+1 (555) 123-4567');
$emergency = get_site_content($site_content, 'emergency', '911');
$email = get_site_content($site_content, 'email', 'info@hospital.com');
$hours = get_site_content($site_content, 'hours', 'Mon-Fri: 8AM-8PM, Sat-Sun: 9AM-5PM');
$address = get_site_content($site_content, 'address', '123 Medical Center Drive, Healthcare City, State 12345');

$heroHeadline = get_site_content($site_content, 'hero_headline', 'World-Class Healthcare for Everyone');
$heroSubheadline = get_site_content($site_content, 'hero_subheadline', 'Comprehensive medical care with over 50 departments, 200+ expert physicians, and state-of-the-art facilities.');
$heroBtnPrimary = get_site_content($site_content, 'hero_btn_primary', 'Find a Doctor');
$heroBtnSecondary = get_site_content($site_content, 'hero_btn_secondary', 'Our Services');

$stat1Num = get_site_content($site_content, 'stat1_num', '500+');
$stat1Label = get_site_content($site_content, 'stat1_label', 'Hospital Beds');
$stat2Num = get_site_content($site_content, 'stat2_num', '200+');
$stat2Label = get_site_content($site_content, 'stat2_label', 'Expert Doctors');
$stat3Num = get_site_content($site_content, 'stat3_num', '50+');
$stat3Label = get_site_content($site_content, 'stat3_label', 'Departments');
$stat4Num = get_site_content($site_content, 'stat4_num', '1M+');
$stat4Label = get_site_content($site_content, 'stat4_label', 'Patients Served');

$ctaHeadline = get_site_content($site_content, 'cta_headline', 'Need Emergency Care?');
$ctaDescription = get_site_content($site_content, 'cta_description', 'Our emergency department is open 24/7 with expert trauma care and rapid response teams.');
$ctaButton = get_site_content($site_content, 'cta_button', 'Call Emergency');
?>

<!-- Top Bar -->
<div class="top-bar">
    <span>🚨 24/7 Emergency: <?php echo esc_html($emergency); ?> &nbsp;|&nbsp; 📞 <?php echo esc_html($phone); ?></span>
    <span>📍 <?php echo esc_html($address); ?></span>
</div>

<!-- Header -->
<header class="header">
    <div class="logo">
        <span>🏥</span>
        <?php echo esc_html($businessName); ?>
    </div>
    <nav class="nav">
        <a href="#">Home</a>
        <a href="#">Departments</a>
        <a href="#">Find a Doctor</a>
        <a href="#">Services</a>
        <a href="#">Patient Portal</a>
        <a href="#">Contact</a>
    </nav>
    <a href="#" class="header-cta">Book Appointment</a>
</header>

<!-- Hero -->
<section class="hero">
    <h1><?php echo esc_html($heroHeadline); ?></h1>
    <p><?php echo esc_html($heroSubheadline); ?></p>
    <div class="hero-btns">
        <a href="#" class="btn-primary"><?php echo esc_html($heroBtnPrimary); ?></a>
        <a href="#" class="btn-outline"><?php echo esc_html($heroBtnSecondary); ?></a>
    </div>
</section>

<!-- Quick Services Cards -->
<div class="container" style="margin-top: -60px; position: relative; z-index: 10;">
    <div class="cards-grid" style="grid-template-columns: repeat(4, 1fr);">
        <div class="card">
            <div class="card-icon">🚑</div>
            <h3>Emergency Care</h3>
            <p>24/7 emergency services with rapid response</p>
        </div>
        <div class="card">
            <div class="card-icon">📅</div>
            <h3>Appointments</h3>
            <p>Easy online booking system</p>
        </div>
        <div class="card">
            <div class="card-icon">💊</div>
            <h3>Pharmacy</h3>
            <p>On-site pharmacy services</p>
        </div>
        <div class="card">
            <div class="card-icon">📱</div>
            <h3>Patient Portal</h3>
            <p>Access records online</p>
        </div>
    </div>
</div>

<!-- Departments Section -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Our Departments</h2>
        <p>Comprehensive care across <?php echo esc_html($stat3Num); ?> medical specialties</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(6, 1fr);">
            <?php 
            $departments = array(
                array('icon' => '❤️', 'name' => 'Cardiology'),
                array('icon' => '🧠', 'name' => 'Neurology'),
                array('icon' => '🦴', 'name' => 'Orthopedics'),
                array('icon' => '👶', 'name' => 'Pediatrics'),
                array('icon' => '🔬', 'name' => 'Oncology'),
                array('icon' => '🫁', 'name' => 'Pulmonology'),
                array('icon' => '👁️', 'name' => 'Ophthalmology'),
                array('icon' => '🦷', 'name' => 'Dental'),
                array('icon' => '🧬', 'name' => 'Genetics'),
                array('icon' => '🩺', 'name' => 'Internal Med'),
                array('icon' => '🤰', 'name' => 'OB/GYN'),
                array('icon' => '🧪', 'name' => 'Pathology'),
            );
            foreach ($departments as $dept): ?>
                <div class="card" style="padding: 25px 15px;">
                    <div class="card-icon" style="width: 60px; height: 60px; font-size: 28px;"><?php echo $dept['icon']; ?></div>
                    <h3 style="font-size: 14px;"><?php echo $dept['name']; ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stats-grid">
        <div>
            <div class="stat-num"><?php echo esc_html($stat1Num); ?></div>
            <div class="stat-label"><?php echo esc_html($stat1Label); ?></div>
        </div>
        <div>
            <div class="stat-num"><?php echo esc_html($stat2Num); ?></div>
            <div class="stat-label"><?php echo esc_html($stat2Label); ?></div>
        </div>
        <div>
            <div class="stat-num"><?php echo esc_html($stat3Num); ?></div>
            <div class="stat-label"><?php echo esc_html($stat3Label); ?></div>
        </div>
        <div>
            <div class="stat-num"><?php echo esc_html($stat4Num); ?></div>
            <div class="stat-label"><?php echo esc_html($stat4Label); ?></div>
        </div>
    </div>
</section>

<!-- Featured Doctors -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Our Expert Physicians</h2>
        <p>Meet our experienced and dedicated medical team</p>
    </div>
    <div class="container">
        <div class="team-grid">
            <?php 
            $doctors = array(
                array('name' => 'Dr. Sarah Johnson', 'role' => 'Chief Medical Officer', 'initial' => 'S'),
                array('name' => 'Dr. Michael Chen', 'role' => 'Head of Cardiology', 'initial' => 'M'),
                array('name' => 'Dr. Emily Brown', 'role' => 'Neurology Specialist', 'initial' => 'E'),
                array('name' => 'Dr. David Wilson', 'role' => 'Orthopedic Surgeon', 'initial' => 'D'),
            );
            foreach ($doctors as $doc): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div class="team-avatar"><?php echo $doc['initial']; ?></div>
                    </div>
                    <div class="team-info">
                        <h3><?php echo $doc['name']; ?></h3>
                        <div class="role"><?php echo $doc['role']; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <h2><?php echo esc_html($ctaHeadline); ?></h2>
    <p><?php echo esc_html($ctaDescription); ?></p>
    <a href="#" class="btn-primary">🚨 <?php echo esc_html($ctaButton); ?>: <?php echo esc_html($emergency); ?></a>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>About Us</h4>
            <p><?php echo esc_html($aboutShort); ?></p>
        </div>
        <div>
            <h4>Quick Links</h4>
            <ul>
                <li><a href="#">Find a Doctor</a></li>
                <li><a href="#">Patient Portal</a></li>
                <li><a href="#">Visitor Information</a></li>
                <li><a href="#">Careers</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
        <div>
            <h4>Departments</h4>
            <ul>
                <li><a href="#">Emergency Care</a></li>
                <li><a href="#">Cardiology</a></li>
                <li><a href="#">Neurology</a></li>
                <li><a href="#">Orthopedics</a></li>
                <li><a href="#">Pediatrics</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact Info</h4>
            <p>
                📍 <?php echo nl2br(esc_html($address)); ?><br><br>
                📞 <?php echo esc_html($phone); ?><br>
                ✉️ <?php echo esc_html($email); ?><br>
                🚨 Emergency: <?php echo esc_html($emergency); ?>
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        © <?php echo date('Y'); ?> <?php echo esc_html($businessName); ?>. All rights reserved.
    </div>
</footer>
