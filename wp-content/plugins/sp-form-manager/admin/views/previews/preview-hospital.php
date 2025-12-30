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

// Get repeater data with defaults
$services = isset($site_content['services']) && is_array($site_content['services']) ? $site_content['services'] : array(
    array('icon' => '❤️', 'name' => 'Cardiology', 'desc' => 'Heart and cardiovascular care'),
    array('icon' => '🧠', 'name' => 'Neurology', 'desc' => 'Brain and nervous system'),
    array('icon' => '🦴', 'name' => 'Orthopedics', 'desc' => 'Bone and joint specialists'),
    array('icon' => '👶', 'name' => 'Pediatrics', 'desc' => 'Children healthcare'),
    array('icon' => '👁️', 'name' => 'Ophthalmology', 'desc' => 'Eye care services'),
    array('icon' => '🦷', 'name' => 'Dental', 'desc' => 'Dental care services'),
);

$team = isset($site_content['team']) && is_array($site_content['team']) ? $site_content['team'] : array(
    array('name' => 'Dr. Sarah Johnson', 'role' => 'Chief Medical Officer', 'initial' => 'S'),
    array('name' => 'Dr. Michael Chen', 'role' => 'Head of Cardiology', 'initial' => 'M'),
    array('name' => 'Dr. Emily Brown', 'role' => 'Neurology Specialist', 'initial' => 'E'),
    array('name' => 'Dr. David Wilson', 'role' => 'Orthopedic Surgeon', 'initial' => 'D'),
);

$quick_features = isset($site_content['quick_features']) && is_array($site_content['quick_features']) ? $site_content['quick_features'] : array(
    array('icon' => '🚑', 'name' => 'Emergency Care', 'desc' => '24/7 emergency services with rapid response'),
    array('icon' => '📅', 'name' => 'Appointments', 'desc' => 'Easy online booking system'),
    array('icon' => '💊', 'name' => 'Pharmacy', 'desc' => 'On-site pharmacy services'),
    array('icon' => '📱', 'name' => 'Patient Portal', 'desc' => 'Access records online'),
);
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
    <div class="cards-grid" style="grid-template-columns: repeat(<?php echo min(count($quick_features), 4); ?>, 1fr);">
        <?php foreach ($quick_features as $feature): ?>
            <div class="card">
                <div class="card-icon"><?php echo esc_html($feature['icon'] ?? '✅'); ?></div>
                <h3><?php echo esc_html($feature['name'] ?? 'Feature'); ?></h3>
                <p><?php echo esc_html($feature['desc'] ?? ''); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Departments Section -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Our Departments</h2>
        <p>Comprehensive care across <?php echo esc_html($stat3Num); ?> medical specialties</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(<?php echo min(count($services), 6); ?>, 1fr);">
            <?php foreach ($services as $service): ?>
                <div class="card" style="padding: 25px 15px;">
                    <div class="card-icon" style="width: 60px; height: 60px; font-size: 28px;"><?php echo esc_html($service['icon'] ?? '🏥'); ?></div>
                    <h3 style="font-size: 14px;"><?php echo esc_html($service['name'] ?? 'Service'); ?></h3>
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
            <?php foreach ($team as $member): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div class="team-avatar"><?php echo esc_html($member['initial'] ?? substr($member['name'] ?? 'D', 0, 1)); ?></div>
                    </div>
                    <div class="team-info">
                        <h3><?php echo esc_html($member['name'] ?? 'Doctor'); ?></h3>
                        <div class="role"><?php echo esc_html($member['role'] ?? 'Specialist'); ?></div>
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
                <?php foreach (array_slice($services, 0, 5) as $service): ?>
                    <li><a href="#"><?php echo esc_html($service['name'] ?? 'Department'); ?></a></li>
                <?php endforeach; ?>
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
