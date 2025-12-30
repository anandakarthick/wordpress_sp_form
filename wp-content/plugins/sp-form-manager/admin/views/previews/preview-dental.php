<?php
/**
 * Preview: Dental Clinic - Smile-Focused Modern
 * Uses site_content values for all editable fields
 */

// Get values from site_content (set in template-preview.php)
$businessName = get_site_content($site_content, 'business_name', 'Bright Smile Dental');
$tagline = get_site_content($site_content, 'tagline', 'Creating Beautiful Smiles');
$aboutShort = get_site_content($site_content, 'about_short', 'Creating beautiful, healthy smiles since 1999. We combine advanced technology with a gentle touch for exceptional dental care.');

$phone = get_site_content($site_content, 'phone', '+1 (555) 234-5678');
$emergency = get_site_content($site_content, 'emergency', '+1 (555) 234-9999');
$email = get_site_content($site_content, 'email', 'smile@brightdental.com');
$hours = get_site_content($site_content, 'hours', 'Mon-Fri: 8am-6pm, Sat: 9am-2pm');
$address = get_site_content($site_content, 'address', '456 Smile Avenue, Dental Plaza, Suite 200');

$heroHeadline = get_site_content($site_content, 'hero_headline', 'Your Perfect Smile Starts Here');
$heroSubheadline = get_site_content($site_content, 'hero_subheadline', 'Modern dentistry with a gentle touch. We create beautiful, healthy smiles for the whole family.');
$heroBtnPrimary = get_site_content($site_content, 'hero_btn_primary', 'Schedule Visit');
$heroBtnSecondary = get_site_content($site_content, 'hero_btn_secondary', 'Free Consultation');

$stat1Num = get_site_content($site_content, 'stat1_num', '15K+');
$stat1Label = get_site_content($site_content, 'stat1_label', 'Happy Smiles');
$stat2Num = get_site_content($site_content, 'stat2_num', '25+');
$stat2Label = get_site_content($site_content, 'stat2_label', 'Years Experience');
$stat3Num = get_site_content($site_content, 'stat3_num', '5.0');
$stat3Label = get_site_content($site_content, 'stat3_label', 'Star Rating');
$stat4Num = get_site_content($site_content, 'stat4_num', '98%');
$stat4Label = get_site_content($site_content, 'stat4_label', 'Satisfaction Rate');

$ctaHeadline = get_site_content($site_content, 'cta_headline', 'Ready for Your Best Smile?');
$ctaDescription = get_site_content($site_content, 'cta_description', 'Schedule your free consultation today and take the first step towards the smile you\'ve always wanted.');
$ctaButton = get_site_content($site_content, 'cta_button', 'Book Free Consultation');

// Get repeater data with defaults
$services = isset($site_content['services']) && is_array($site_content['services']) ? $site_content['services'] : array(
    array('icon' => '✨', 'name' => 'Teeth Whitening', 'desc' => 'Professional whitening for a brighter, more confident smile'),
    array('icon' => '📐', 'name' => 'Invisalign', 'desc' => 'Clear aligners for straighter teeth without metal braces'),
    array('icon' => '🔧', 'name' => 'Dental Implants', 'desc' => 'Permanent tooth replacement that looks and feels natural'),
    array('icon' => '👑', 'name' => 'Crowns & Veneers', 'desc' => 'Custom restorations for a perfect smile'),
    array('icon' => '🛡️', 'name' => 'Preventive Care', 'desc' => 'Regular cleanings and exams to maintain oral health'),
    array('icon' => '👨‍👩‍👧', 'name' => 'Family Dentistry', 'desc' => 'Gentle care for patients of all ages'),
);

$team = isset($site_content['team']) && is_array($site_content['team']) ? $site_content['team'] : array(
    array('name' => 'Dr. Amanda White', 'role' => 'Lead Dentist', 'initial' => 'A'),
    array('name' => 'Dr. James Lee', 'role' => 'Cosmetic Specialist', 'initial' => 'J'),
    array('name' => 'Dr. Maria Garcia', 'role' => 'Orthodontist', 'initial' => 'M'),
);

$quick_features = isset($site_content['quick_features']) && is_array($site_content['quick_features']) ? $site_content['quick_features'] : array(
    array('icon' => '✨', 'name' => 'Teeth Whitening', 'desc' => ''),
    array('icon' => '📐', 'name' => 'Invisalign', 'desc' => ''),
    array('icon' => '🔧', 'name' => 'Dental Implants', 'desc' => ''),
    array('icon' => '👑', 'name' => 'Crowns & Veneers', 'desc' => ''),
    array('icon' => '🦷', 'name' => 'Root Canal', 'desc' => ''),
    array('icon' => '😁', 'name' => 'Cosmetic Dentistry', 'desc' => ''),
);
?>

<!-- Header -->
<header class="header">
    <div class="logo">
        <span>🦷</span>
        <?php echo esc_html($businessName); ?>
    </div>
    <nav class="nav">
        <a href="#">Home</a>
        <a href="#">Services</a>
        <a href="#">Smile Gallery</a>
        <a href="#">Our Team</a>
        <a href="#">New Patients</a>
        <a href="#">Contact</a>
    </nav>
    <div style="display: flex; align-items: center; gap: 15px;">
        <span style="color: var(--primary); font-weight: 600;">📞 <?php echo esc_html($phone); ?></span>
        <a href="#" class="header-cta">Book Your Smile ✨</a>
    </div>
</header>

<!-- Hero -->
<section class="hero" style="text-align: center; padding: 100px 30px;">
    <div style="font-size: 100px; margin-bottom: 20px; animation: bounce 2s infinite;">😊</div>
    <h1><?php echo esc_html($heroHeadline); ?></h1>
    <p><?php echo esc_html($heroSubheadline); ?></p>
    <div style="background: rgba(255,255,255,0.2); display: inline-block; padding: 10px 25px; border-radius: 30px; margin-bottom: 30px; font-size: 16px;">
        ✨ Free Whitening with New Patient Exam!
    </div>
    <div class="hero-btns">
        <a href="#" class="btn-primary"><?php echo esc_html($heroBtnPrimary); ?></a>
        <a href="#" class="btn-outline"><?php echo esc_html($heroBtnSecondary); ?></a>
    </div>
</section>

<style>
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}
</style>

<!-- Services Pills -->
<section style="background: #fff; padding: 40px 30px;">
    <div class="container" style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
        <?php foreach (array_slice($quick_features, 0, 6) as $feature): ?>
            <span style="background: linear-gradient(135deg, var(--primary), var(--accent)); color: #fff; padding: 12px 25px; border-radius: 30px; font-weight: 600; font-size: 14px;">
                <?php echo esc_html($feature['icon'] ?? '✨'); ?> <?php echo esc_html($feature['name'] ?? 'Service'); ?>
            </span>
        <?php endforeach; ?>
    </div>
</section>

<!-- Before/After Gallery -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>😁 Smile Transformations</h2>
        <p>See the amazing results our patients have achieved</p>
    </div>
    <div class="container">
        <div class="gallery-grid" style="grid-template-columns: repeat(4, 1fr);">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="gallery-item">
                    <div class="before">Before</div>
                    <div class="after">After ✨</div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- Services Detail -->
<section style="background: #fff;">
    <div class="section-title">
        <h2>Our Dental Services</h2>
        <p>Comprehensive dental care for your entire family</p>
    </div>
    <div class="container">
        <div class="cards-grid">
            <?php foreach ($services as $svc): ?>
                <div class="card">
                    <div class="card-icon"><?php echo esc_html($svc['icon'] ?? '🦷'); ?></div>
                    <h3><?php echo esc_html($svc['name'] ?? 'Service'); ?></h3>
                    <p><?php echo esc_html($svc['desc'] ?? ''); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats -->
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

<!-- Team -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Meet Our Dental Team</h2>
        <p>Experienced, gentle, and dedicated to your smile</p>
    </div>
    <div class="container">
        <div class="team-grid" style="grid-template-columns: repeat(<?php echo min(count($team), 4); ?>, 1fr);">
            <?php foreach ($team as $member): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div class="team-avatar"><?php echo esc_html($member['initial'] ?? substr($member['name'] ?? 'D', 0, 1)); ?></div>
                    </div>
                    <div class="team-info">
                        <h3><?php echo esc_html($member['name'] ?? 'Doctor'); ?></h3>
                        <div class="role"><?php echo esc_html($member['role'] ?? 'Dentist'); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Insurance Banner -->
<section style="background: var(--primary); color: #fff; padding: 40px 30px; text-align: center;">
    <h3 style="font-size: 24px; margin-bottom: 15px;">💳 We Accept Most Insurance Plans</h3>
    <p style="opacity: 0.9; margin-bottom: 20px;">Plus flexible financing options available for all treatments</p>
    <div style="display: flex; justify-content: center; gap: 30px; font-size: 14px; opacity: 0.8;">
        <span>Delta Dental</span>
        <span>Cigna</span>
        <span>Aetna</span>
        <span>MetLife</span>
        <span>Blue Cross</span>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <h2><?php echo esc_html($ctaHeadline); ?></h2>
    <p><?php echo esc_html($ctaDescription); ?></p>
    <a href="#" class="btn-primary">📅 <?php echo esc_html($ctaButton); ?></a>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>🦷 <?php echo esc_html($businessName); ?></h4>
            <p><?php echo esc_html($aboutShort); ?></p>
        </div>
        <div>
            <h4>Our Services</h4>
            <ul>
                <?php foreach (array_slice($services, 0, 5) as $service): ?>
                    <li><a href="#"><?php echo esc_html($service['name'] ?? 'Service'); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h4>Patient Info</h4>
            <ul>
                <li><a href="#">New Patients</a></li>
                <li><a href="#">Insurance</a></li>
                <li><a href="#">Financing</a></li>
                <li><a href="#">Patient Forms</a></li>
                <li><a href="#">FAQs</a></li>
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
