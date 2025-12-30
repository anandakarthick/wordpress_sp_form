<?php
/**
 * Preview: Eye Care Center - Vision-Centric Elegant
 * Uses site_content values for all editable fields
 */

// Get values from site_content (set in template-preview.php)
$businessName = get_site_content($site_content, 'business_name', 'ClearView Eye Center');
$tagline = get_site_content($site_content, 'tagline', 'Your Vision, Our Mission');
$aboutShort = get_site_content($site_content, 'about_short', 'Your vision is our mission. We\'ve helped over 50,000 patients see life more clearly with advanced eye care and vision correction.');

$phone = get_site_content($site_content, 'phone', '+1 (555) 345-6789');
$emergency = get_site_content($site_content, 'emergency', '+1 (555) 345-9999');
$email = get_site_content($site_content, 'email', 'care@clearvieweye.com');
$hours = get_site_content($site_content, 'hours', 'Mon-Sat: 8am-6pm');
$address = get_site_content($site_content, 'address', '789 Vision Way, Eye Care Plaza, Suite 100');

$heroHeadline = get_site_content($site_content, 'hero_headline', 'See Life More Clearly');
$heroSubheadline = get_site_content($site_content, 'hero_subheadline', 'Advanced eye care and vision correction from the region\'s leading ophthalmologists. Experience the freedom of clear vision.');
$heroBtnPrimary = get_site_content($site_content, 'hero_btn_primary', 'Free LASIK Consultation');
$heroBtnSecondary = get_site_content($site_content, 'hero_btn_secondary', 'Schedule Eye Exam');

$stat1Num = get_site_content($site_content, 'stat1_num', '50K+');
$stat1Label = get_site_content($site_content, 'stat1_label', 'LASIK Procedures');
$stat2Num = get_site_content($site_content, 'stat2_num', '99%');
$stat2Label = get_site_content($site_content, 'stat2_label', 'Success Rate');
$stat3Num = get_site_content($site_content, 'stat3_num', '20+');
$stat3Label = get_site_content($site_content, 'stat3_label', 'Years Experience');
$stat4Num = get_site_content($site_content, 'stat4_num', '4.9');
$stat4Label = get_site_content($site_content, 'stat4_label', 'Patient Rating');

$ctaHeadline = get_site_content($site_content, 'cta_headline', 'Ready to See Clearly?');
$ctaDescription = get_site_content($site_content, 'cta_description', 'Schedule your free LASIK consultation and discover if you\'re a candidate for vision freedom.');
$ctaButton = get_site_content($site_content, 'cta_button', 'Free LASIK Consultation');

// Get repeater data with defaults
$services = isset($site_content['services']) && is_array($site_content['services']) ? $site_content['services'] : array(
    array('icon' => '✨', 'name' => 'LASIK Surgery', 'desc' => 'Advanced laser vision correction with 99% success rate'),
    array('icon' => '🔬', 'name' => 'Cataract Surgery', 'desc' => 'Premium lens implants for crystal clear vision'),
    array('icon' => '👓', 'name' => 'Optical Boutique', 'desc' => 'Designer frames and premium lenses'),
    array('icon' => '👁️', 'name' => 'Comprehensive Exams', 'desc' => 'Thorough eye health and vision evaluations'),
    array('icon' => '💧', 'name' => 'Dry Eye Treatment', 'desc' => 'Advanced therapies for lasting relief'),
    array('icon' => '🩺', 'name' => 'Glaucoma Care', 'desc' => 'Expert management of eye pressure conditions'),
);

$team = isset($site_content['team']) && is_array($site_content['team']) ? $site_content['team'] : array(
    array('name' => 'Dr. Robert Vision', 'role' => 'LASIK Director', 'initial' => 'R'),
    array('name' => 'Dr. Lisa Chang', 'role' => 'Cataract Specialist', 'initial' => 'L'),
    array('name' => 'Dr. Mark Stevens', 'role' => 'Retina Specialist', 'initial' => 'M'),
);

$quick_features = isset($site_content['quick_features']) && is_array($site_content['quick_features']) ? $site_content['quick_features'] : array(
    array('icon' => '⏱️', 'name' => '15-Minute Procedure', 'desc' => ''),
    array('icon' => '😎', 'name' => 'No More Glasses', 'desc' => ''),
    array('icon' => '🏃', 'name' => 'Active Lifestyle', 'desc' => ''),
    array('icon' => '💰', 'name' => 'Long-term Savings', 'desc' => ''),
);
?>

<!-- Header -->
<header class="header" id="home">
    <div class="logo">
        <span>👁️</span>
        <?php echo esc_html($businessName); ?>
    </div>
    <nav class="nav">
        <a href="#home">Home</a>
        <a href="#services">Services</a>
        <a href="#lasik">LASIK</a>
        <a href="#doctors">Our Doctors</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
    </nav>
    <a href="#contact" class="header-cta">Book Eye Exam</a>
</header>

<!-- Hero -->
<section class="hero" id="hero" style="padding: 100px 30px;">
    <div style="display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 30px;">
        <span style="font-size: 80px; animation: pulse 2s infinite;">👁️</span>
        <div style="display: flex; flex-direction: column; gap: 5px; opacity: 0.5;">
            <span style="display: block; height: 4px; background: #fff; border-radius: 4px; width: 60px;"></span>
            <span style="display: block; height: 4px; background: #fff; border-radius: 4px; width: 50px;"></span>
            <span style="display: block; height: 4px; background: #fff; border-radius: 4px; width: 40px;"></span>
        </div>
    </div>
    <h1><?php echo esc_html($heroHeadline); ?></h1>
    <p><?php echo esc_html($heroSubheadline); ?></p>
    <div class="hero-btns">
        <a href="#lasik" class="btn-primary"><?php echo esc_html($heroBtnPrimary); ?></a>
        <a href="#contact" class="btn-outline"><?php echo esc_html($heroBtnSecondary); ?></a>
    </div>
</section>

<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
</style>

<!-- LASIK Promo Banner -->
<div class="promo-banner" id="lasik">
    <div>
        <h3>✨ LASIK Vision Correction</h3>
        <p style="opacity: 0.9; font-size: 14px;">Free yourself from glasses and contacts forever</p>
    </div>
    <div class="price">Starting at $1,999/eye</div>
    <a href="#contact" class="btn-white">Learn More →</a>
</div>

<!-- Services -->
<section style="background: var(--background);" id="services">
    <div class="section-title">
        <h2>Comprehensive Eye Care Services</h2>
        <p>From routine exams to advanced surgical procedures</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(3, 1fr);">
            <?php foreach ($services as $svc): ?>
                <div class="card">
                    <div class="card-icon"><?php echo esc_html($svc['icon'] ?? '👁️'); ?></div>
                    <h3><?php echo esc_html($svc['name'] ?? 'Service'); ?></h3>
                    <p><?php echo esc_html($svc['desc'] ?? ''); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
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

<!-- Why LASIK -->
<section style="background: #fff;" id="benefits">
    <div class="section-title">
        <h2>Why Choose LASIK?</h2>
        <p>Life-changing benefits of laser vision correction</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(<?php echo min(count($quick_features), 4); ?>, 1fr);">
            <?php foreach ($quick_features as $feature): ?>
                <div class="card" style="padding: 25px;">
                    <div class="card-icon" style="width: 60px; height: 60px; font-size: 28px;"><?php echo esc_html($feature['icon'] ?? '✅'); ?></div>
                    <h3 style="font-size: 16px;"><?php echo esc_html($feature['name'] ?? 'Benefit'); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Doctors -->
<section style="background: var(--background);" id="doctors">
    <div class="section-title">
        <h2>Our Eye Care Specialists</h2>
        <p>Board-certified ophthalmologists and optometrists</p>
    </div>
    <div class="container">
        <div class="team-grid" style="grid-template-columns: repeat(<?php echo min(count($team), 4); ?>, 1fr);">
            <?php foreach ($team as $doc): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div class="team-avatar"><?php echo esc_html($doc['initial'] ?? substr($doc['name'] ?? 'D', 0, 1)); ?></div>
                    </div>
                    <div class="team-info">
                        <h3><?php echo esc_html($doc['name'] ?? 'Doctor'); ?></h3>
                        <div class="role"><?php echo esc_html($doc['role'] ?? 'Specialist'); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section" id="cta">
    <h2><?php echo esc_html($ctaHeadline); ?></h2>
    <p><?php echo esc_html($ctaDescription); ?></p>
    <a href="#contact" class="btn-primary">👁️ <?php echo esc_html($ctaButton); ?></a>
</section>

<!-- Footer -->
<footer class="footer" id="contact">
    <div class="footer-grid">
        <div id="about">
            <h4>👁️ <?php echo esc_html($businessName); ?></h4>
            <p><?php echo esc_html($aboutShort); ?></p>
        </div>
        <div>
            <h4>Our Services</h4>
            <ul>
                <?php foreach (array_slice($services, 0, 5) as $service): ?>
                    <li><a href="#services"><?php echo esc_html($service['name'] ?? 'Service'); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h4>Resources</h4>
            <ul>
                <li><a href="#lasik">Am I a LASIK Candidate?</a></li>
                <li><a href="#contact">Financing Options</a></li>
                <li><a href="#contact">Insurance Info</a></li>
                <li><a href="#contact">Patient Portal</a></li>
                <li><a href="#contact">FAQs</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact Us</h4>
            <p>
                📍 <?php echo nl2br(esc_html($address)); ?><br><br>
                📞 <?php echo esc_html($phone); ?><br>
                ✉️ <?php echo esc_html($email); ?><br>
                ⏰ <?php echo esc_html($hours); ?>
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        © <?php echo date('Y'); ?> <?php echo esc_html($businessName); ?>. <?php echo esc_html($tagline); ?>.
    </div>
</footer>
