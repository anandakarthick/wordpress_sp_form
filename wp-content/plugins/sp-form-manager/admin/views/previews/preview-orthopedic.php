<?php
/**
 * Preview: Orthopedic Center - Motion-Focused Dynamic
 * Uses site_content values for all editable fields
 */

// Get values from site_content (set in template-preview.php)
$businessName = get_site_content($site_content, 'business_name', 'SpineFirst Orthopedics');
$tagline = get_site_content($site_content, 'tagline', 'Move Better, Live Better');
$aboutShort = get_site_content($site_content, 'about_short', 'Get back to doing what you love. Our team of orthopedic specialists will help you move better and live better.');

$phone = get_site_content($site_content, 'phone', '+1 (555) 789-0123');
$emergency = get_site_content($site_content, 'emergency', '+1 (555) 789-9999');
$email = get_site_content($site_content, 'email', 'info@spinefirst.com');
$hours = get_site_content($site_content, 'hours', 'Mon-Fri: 7am-6pm');
$address = get_site_content($site_content, 'address', '333 Motion Drive, Orthopedic Plaza');

$heroHeadline = get_site_content($site_content, 'hero_headline', 'Get Moving Again');
$heroSubheadline = get_site_content($site_content, 'hero_subheadline', 'Expert bone, joint, and spine care to get you back to the activities you love. From sports injuries to joint replacement.');
$heroBtnPrimary = get_site_content($site_content, 'hero_btn_primary', 'Schedule Consultation');
$heroBtnSecondary = get_site_content($site_content, 'hero_btn_secondary', 'Our Treatments');

$stat1Num = get_site_content($site_content, 'stat1_num', '25K+');
$stat1Label = get_site_content($site_content, 'stat1_label', 'Surgeries');
$stat2Num = get_site_content($site_content, 'stat2_num', '99%');
$stat2Label = get_site_content($site_content, 'stat2_label', 'Success Rate');
$stat3Num = get_site_content($site_content, 'stat3_num', '20');
$stat3Label = get_site_content($site_content, 'stat3_label', 'Surgeons');
$stat4Num = get_site_content($site_content, 'stat4_num', '4.9');
$stat4Label = get_site_content($site_content, 'stat4_label', 'Patient Rating');

$ctaHeadline = get_site_content($site_content, 'cta_headline', 'Ready to Move Without Pain?');
$ctaDescription = get_site_content($site_content, 'cta_description', 'Schedule your consultation and take the first step toward an active, pain-free life.');
$ctaButton = get_site_content($site_content, 'cta_button', 'Book Consultation');
?>

<!-- Header -->
<header class="header">
    <div class="logo">
        <span>🦴</span>
        <?php echo esc_html($businessName); ?>
    </div>
    <nav class="nav">
        <a href="#">Home</a>
        <a href="#">Services</a>
        <a href="#">Joint Replacement</a>
        <a href="#">Sports Medicine</a>
        <a href="#">Our Surgeons</a>
        <a href="#">Contact</a>
    </nav>
    <a href="#" class="header-cta">Book Consultation</a>
</header>

<!-- Hero -->
<section class="hero" style="padding: 100px 30px;">
    <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 30px;">
        <span style="font-size: 70px; animation: run 0.5s steps(2) infinite;">🏃</span>
        <span style="font-size: 30px; opacity: 0.6; animation: trail 1s ease-in-out infinite;">→→→</span>
    </div>
    <h1><?php echo esc_html($heroHeadline); ?></h1>
    <p><?php echo esc_html($heroSubheadline); ?></p>
    <div class="hero-btns">
        <a href="#" class="btn-primary"><?php echo esc_html($heroBtnPrimary); ?></a>
        <a href="#" class="btn-outline"><?php echo esc_html($heroBtnSecondary); ?></a>
    </div>
</section>

<style>
@keyframes run {
    0% { transform: translateX(0); }
    100% { transform: translateX(10px); }
}
@keyframes trail {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.8; }
}
</style>

<!-- Specialties -->
<section style="background: #fff;">
    <div class="section-title">
        <h2>Our Specialties</h2>
        <p>Comprehensive orthopedic care for every part of your body</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(5, 1fr);">
            <?php 
            $specialties = array(
                array('icon' => '🦴', 'name' => 'Joint Replacement', 'desc' => 'Hip • Knee • Shoulder'),
                array('icon' => '🏃', 'name' => 'Sports Medicine', 'desc' => 'ACL • Rotator Cuff'),
                array('icon' => '🧠', 'name' => 'Spine Surgery', 'desc' => 'Minimally Invasive'),
                array('icon' => '✋', 'name' => 'Hand & Wrist', 'desc' => 'Carpal Tunnel'),
                array('icon' => '🦶', 'name' => 'Foot & Ankle', 'desc' => 'Bunions • Fractures'),
            );
            foreach ($specialties as $s): ?>
                <div class="card" style="<?php echo $s['name'] == 'Joint Replacement' ? 'border: 3px solid var(--primary); background: linear-gradient(135deg, #eff6ff, #dbeafe);' : ''; ?>">
                    <div class="card-icon"><?php echo $s['icon']; ?></div>
                    <h3 style="font-size: 16px;"><?php echo $s['name']; ?></h3>
                    <p style="font-size: 12px;"><?php echo $s['desc']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Procedures -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Common Procedures</h2>
    </div>
    <div class="container" style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
        <?php 
        $procedures = array('Hip Replacement', 'Knee Replacement', 'ACL Reconstruction', 'Rotator Cuff Repair', 'Spinal Fusion', 'Arthroscopy', 'Fracture Repair', 'Carpal Tunnel');
        foreach ($procedures as $p): ?>
            <span style="background: #fff; border: 2px solid var(--accent); color: var(--primary); padding: 10px 20px; border-radius: 20px; font-weight: 600; font-size: 13px;">
                <?php echo $p; ?>
            </span>
        <?php endforeach; ?>
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

<!-- Rehab CTA -->
<div class="promo-banner">
    <div>
        <span style="font-size: 30px;">💪</span>
    </div>
    <div>
        <h3>Full Rehabilitation Programs</h3>
        <p style="opacity: 0.9; font-size: 14px;">Physical therapy and rehab to get you back to 100%</p>
    </div>
    <a href="#" class="btn-white">Learn More →</a>
</div>

<!-- Team -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Our Orthopedic Surgeons</h2>
        <p>Fellowship-trained specialists in every area</p>
    </div>
    <div class="container">
        <div class="team-grid" style="grid-template-columns: repeat(4, 1fr);">
            <?php 
            $surgeons = array(
                array('name' => 'Dr. John Bone', 'role' => 'Chief of Surgery', 'initial' => 'J'),
                array('name' => 'Dr. Lisa Joint', 'role' => 'Joint Replacement', 'initial' => 'L'),
                array('name' => 'Dr. Mike Spine', 'role' => 'Spine Specialist', 'initial' => 'M'),
                array('name' => 'Dr. Sarah Sport', 'role' => 'Sports Medicine', 'initial' => 'S'),
            );
            foreach ($surgeons as $s): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div class="team-avatar"><?php echo $s['initial']; ?></div>
                    </div>
                    <div class="team-info">
                        <h3 style="font-size: 16px;"><?php echo $s['name']; ?></h3>
                        <div class="role"><?php echo $s['role']; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2><?php echo esc_html($ctaHeadline); ?></h2>
    <p><?php echo esc_html($ctaDescription); ?></p>
    <a href="#" class="btn-primary">📅 <?php echo esc_html($ctaButton); ?></a>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>🦴 <?php echo esc_html($businessName); ?></h4>
            <p><?php echo esc_html($aboutShort); ?></p>
        </div>
        <div>
            <h4>Services</h4>
            <ul>
                <li><a href="#">Joint Replacement</a></li>
                <li><a href="#">Sports Medicine</a></li>
                <li><a href="#">Spine Surgery</a></li>
                <li><a href="#">Hand Surgery</a></li>
                <li><a href="#">Physical Therapy</a></li>
            </ul>
        </div>
        <div>
            <h4>Patient Info</h4>
            <ul>
                <li><a href="#">Preparing for Surgery</a></li>
                <li><a href="#">Recovery Guide</a></li>
                <li><a href="#">Insurance</a></li>
                <li><a href="#">Patient Portal</a></li>
                <li><a href="#">FAQs</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact</h4>
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
