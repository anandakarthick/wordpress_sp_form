<?php
/**
 * Preview: Cardiology Center - Heart-Focused Urgent
 * Uses site_content values for all editable fields
 */

// Get values from site_content (set in template-preview.php)
$businessName = get_site_content($site_content, 'business_name', 'HeartCare Center');
$tagline = get_site_content($site_content, 'tagline', '24/7 Cardiac Emergency Care');
$aboutShort = get_site_content($site_content, 'about_short', 'Protecting hearts, saving lives. Our cardiac team provides comprehensive care from prevention to intervention.');

$phone = get_site_content($site_content, 'phone', '+1 (555) 567-8901');
$emergency = get_site_content($site_content, 'emergency', '911');
$email = get_site_content($site_content, 'email', 'info@heartcare.com');
$hours = get_site_content($site_content, 'hours', '24/7 Emergency Care');
$address = get_site_content($site_content, 'address', '999 Heart Boulevard, Cardiac Center, Suite 100');

$heroHeadline = get_site_content($site_content, 'hero_headline', 'Your Heart in Expert Hands');
$heroSubheadline = get_site_content($site_content, 'hero_subheadline', 'Leading cardiac care from prevention to intervention. Our team of expert cardiologists provides comprehensive heart health services.');
$heroBtnPrimary = get_site_content($site_content, 'hero_btn_primary', 'Book Heart Screening');
$heroBtnSecondary = get_site_content($site_content, 'hero_btn_secondary', 'Our Expertise');

$stat1Num = get_site_content($site_content, 'stat1_num', '50K+');
$stat1Label = get_site_content($site_content, 'stat1_label', 'Procedures');
$stat2Num = get_site_content($site_content, 'stat2_num', '98%');
$stat2Label = get_site_content($site_content, 'stat2_label', 'Success Rate');
$stat3Num = get_site_content($site_content, 'stat3_num', '25');
$stat3Label = get_site_content($site_content, 'stat3_label', 'Cardiologists');
$stat4Num = get_site_content($site_content, 'stat4_num', '24/7');
$stat4Label = get_site_content($site_content, 'stat4_label', 'Cardiac ER');

$ctaHeadline = get_site_content($site_content, 'cta_headline', 'Cardiac Emergency?');
$ctaDescription = get_site_content($site_content, 'cta_description', 'Don\'t wait! Every second counts during a heart attack. Our cardiac ER is ready 24/7.');
$ctaButton = get_site_content($site_content, 'cta_button', 'Call 911 Now');

// Get repeater data with defaults
$services = isset($site_content['services']) && is_array($site_content['services']) ? $site_content['services'] : array(
    array('icon' => '🩺', 'name' => 'Diagnostics', 'desc' => 'ECG, Echo, Stress Tests'),
    array('icon' => '💓', 'name' => 'Cath Lab', 'desc' => 'Angiography & Angioplasty'),
    array('icon' => '🫀', 'name' => 'Heart Surgery', 'desc' => 'Bypass & Valve Repair'),
    array('icon' => '⚡', 'name' => 'Pacemakers', 'desc' => 'Device Implantation'),
);

$team = isset($site_content['team']) && is_array($site_content['team']) ? $site_content['team'] : array(
    array('name' => 'Dr. Heart Smith', 'role' => 'Chief Cardiologist', 'initial' => 'H'),
    array('name' => 'Dr. Beat Johnson', 'role' => 'Interventional', 'initial' => 'B'),
    array('name' => 'Dr. Pulse Lee', 'role' => 'Electrophysiologist', 'initial' => 'P'),
    array('name' => 'Dr. Care Wilson', 'role' => 'Heart Surgeon', 'initial' => 'C'),
);

$quick_features = isset($site_content['quick_features']) && is_array($site_content['quick_features']) ? $site_content['quick_features'] : array(
    array('icon' => '❤️', 'name' => 'Heart Attack', 'desc' => ''),
    array('icon' => '💓', 'name' => 'Arrhythmia', 'desc' => ''),
    array('icon' => '🫀', 'name' => 'Heart Failure', 'desc' => ''),
    array('icon' => '🩺', 'name' => 'Coronary Disease', 'desc' => ''),
);
?>

<!-- Emergency Alert Banner -->
<div class="alert-banner" style="animation: alertPulse 2s infinite;">
    <span>⚠️ <strong>Heart Attack Warning Signs:</strong> Chest pain, shortness of breath, arm pain → Call <?php echo esc_html($emergency); ?> immediately</span>
    <a href="tel:<?php echo esc_attr($emergency); ?>" style="background: #fff; color: #dc2626; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 700;">Call <?php echo esc_html($emergency); ?></a>
</div>

<style>
@keyframes alertPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}
@keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    25% { transform: scale(1.1); }
    50% { transform: scale(1); }
    75% { transform: scale(1.05); }
}
@keyframes ecgDraw {
    to { stroke-dashoffset: -200; }
}
</style>

<!-- Header -->
<header class="header" id="home">
    <div class="logo">
        <span>❤️</span>
        <?php echo esc_html($businessName); ?>
    </div>
    <nav class="nav">
        <a href="#home">Home</a>
        <a href="#services">Services</a>
        <a href="#conditions">Conditions</a>
        <a href="#doctors">Our Doctors</a>
        <a href="#about">Heart Health</a>
        <a href="#contact">Contact</a>
    </nav>
    <a href="tel:<?php echo esc_attr($emergency); ?>" style="background: #dc2626; color: #fff; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: 700; animation: pulse 1.5s infinite;">🚨 Emergency</a>
</header>

<!-- Hero -->
<section class="hero" id="hero" style="padding: 100px 30px;">
    <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 30px;">
        <span style="font-size: 70px; animation: heartbeat 1s ease-in-out infinite;">❤️</span>
        <svg viewBox="0 0 100 30" style="width: 120px; height: 40px;">
            <path d="M0,15 L20,15 L25,5 L30,25 L35,10 L40,20 L45,15 L100,15" 
                  stroke="white" stroke-width="2" fill="none" style="stroke-dasharray: 200; animation: ecgDraw 2s linear infinite;"/>
        </svg>
    </div>
    <h1><?php echo esc_html($heroHeadline); ?></h1>
    <p><?php echo esc_html($heroSubheadline); ?></p>
    <div class="hero-btns">
        <a href="#contact" class="btn-primary"><?php echo esc_html($heroBtnPrimary); ?></a>
        <a href="#services" class="btn-outline"><?php echo esc_html($heroBtnSecondary); ?></a>
    </div>
</section>

<!-- Services -->
<section style="background: #fff;" id="services">
    <div class="section-title">
        <h2>Cardiac Services</h2>
        <p>Comprehensive heart care from diagnosis to treatment</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(<?php echo min(count($services), 4); ?>, 1fr);">
            <?php foreach ($services as $svc): ?>
                <div class="card">
                    <div class="card-icon"><?php echo esc_html($svc['icon'] ?? '❤️'); ?></div>
                    <h3><?php echo esc_html($svc['name'] ?? 'Service'); ?></h3>
                    <p><?php echo esc_html($svc['desc'] ?? ''); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Conditions We Treat -->
<section style="background: var(--background);" id="conditions">
    <div class="section-title">
        <h2>Conditions We Treat</h2>
        <p>Expert care for all heart-related conditions</p>
    </div>
    <div class="container" style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
        <?php foreach ($quick_features as $feature): ?>
            <a href="#services" style="background: #fff; border: 2px solid var(--primary); color: var(--primary); padding: 12px 25px; border-radius: 25px; font-weight: 600; font-size: 14px; text-decoration: none;">
                <?php echo esc_html($feature['icon'] ?? '❤️'); ?> <?php echo esc_html($feature['name'] ?? 'Condition'); ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Stats -->
<section class="stats-section" id="stats">
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

<!-- Team -->
<section style="background: #fff;" id="doctors">
    <div class="section-title">
        <h2>Our Cardiologists</h2>
        <p>Board-certified heart specialists</p>
    </div>
    <div class="container">
        <div class="team-grid" style="grid-template-columns: repeat(<?php echo min(count($team), 4); ?>, 1fr);">
            <?php foreach ($team as $doc): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div class="team-avatar"><?php echo esc_html($doc['initial'] ?? substr($doc['name'] ?? 'D', 0, 1)); ?></div>
                    </div>
                    <div class="team-info">
                        <h3 style="font-size: 16px;"><?php echo esc_html($doc['name'] ?? 'Doctor'); ?></h3>
                        <div class="role"><?php echo esc_html($doc['role'] ?? 'Cardiologist'); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section" id="cta" style="background: linear-gradient(135deg, #dc2626, var(--primary));">
    <h2>🚨 <?php echo esc_html($ctaHeadline); ?></h2>
    <p><?php echo esc_html($ctaDescription); ?></p>
    <a href="tel:<?php echo esc_attr($emergency); ?>" class="btn-primary" style="background: #fff; color: #dc2626;">📞 <?php echo esc_html($ctaButton); ?></a>
</section>

<!-- Footer -->
<footer class="footer" id="contact">
    <div class="footer-grid">
        <div id="about">
            <h4>❤️ <?php echo esc_html($businessName); ?></h4>
            <p><?php echo esc_html($aboutShort); ?></p>
        </div>
        <div>
            <h4>Services</h4>
            <ul>
                <?php foreach (array_slice($services, 0, 5) as $service): ?>
                    <li><a href="#services"><?php echo esc_html($service['name'] ?? 'Service'); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h4>Heart Health</h4>
            <ul>
                <li><a href="#conditions">Risk Assessment</a></li>
                <li><a href="#about">Prevention Tips</a></li>
                <li><a href="#about">Diet & Exercise</a></li>
                <li><a href="#conditions">Warning Signs</a></li>
                <li><a href="#about">Patient Stories</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact</h4>
            <p>
                📍 <?php echo nl2br(esc_html($address)); ?><br><br>
                📞 <?php echo esc_html($phone); ?><br>
                ✉️ <?php echo esc_html($email); ?><br>
                🚨 Emergency: <?php echo esc_html($emergency); ?>
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        © <?php echo date('Y'); ?> <?php echo esc_html($businessName); ?>. <?php echo esc_html($tagline); ?>.
    </div>
</footer>
