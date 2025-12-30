<?php
/**
 * Preview: Mental Health Clinic - Calming Supportive
 * Uses site_content values for all editable fields
 */

// Get values from site_content (set in template-preview.php)
$businessName = get_site_content($site_content, 'business_name', 'Serenity Wellness');
$tagline = get_site_content($site_content, 'tagline', 'Your Safe Space for Healing');
$aboutShort = get_site_content($site_content, 'about_short', 'A safe space for healing and growth. We believe everyone deserves compassionate mental health care.');

$phone = get_site_content($site_content, 'phone', '+1 (555) 678-9012');
$emergency = get_site_content($site_content, 'emergency', '988');
$email = get_site_content($site_content, 'email', 'care@serenitywellness.com');
$hours = get_site_content($site_content, 'hours', 'Mon-Fri: 8am-8pm, Sat: 9am-3pm');
$address = get_site_content($site_content, 'address', '222 Peaceful Path, Wellness Center, Suite 300');

$heroHeadline = get_site_content($site_content, 'hero_headline', 'Your Journey to Wellness Begins Here');
$heroSubheadline = get_site_content($site_content, 'hero_subheadline', 'Compassionate mental health care in a safe, supportive environment. You\'re not alone – we\'re here to help.');
$heroBtnPrimary = get_site_content($site_content, 'hero_btn_primary', 'Start Your Journey');
$heroBtnSecondary = get_site_content($site_content, 'hero_btn_secondary', 'Learn More');

$stat1Num = get_site_content($site_content, 'stat1_num', '10K+');
$stat1Label = get_site_content($site_content, 'stat1_label', 'Patients Helped');
$stat2Num = get_site_content($site_content, 'stat2_num', '15+');
$stat2Label = get_site_content($site_content, 'stat2_label', 'Therapists');
$stat3Num = get_site_content($site_content, 'stat3_num', '98%');
$stat3Label = get_site_content($site_content, 'stat3_label', 'Satisfaction');
$stat4Num = get_site_content($site_content, 'stat4_num', '24/7');
$stat4Label = get_site_content($site_content, 'stat4_label', 'Crisis Support');

$ctaHeadline = get_site_content($site_content, 'cta_headline', 'Ready to Take the First Step?');
$ctaDescription = get_site_content($site_content, 'cta_description', 'Your journey to wellness begins with a single step. We\'re here to support you.');
$ctaButton = get_site_content($site_content, 'cta_button', 'Schedule Consultation');

// Get repeater data with defaults
$services = isset($site_content['services']) && is_array($site_content['services']) ? $site_content['services'] : array(
    array('icon' => '💬', 'name' => 'Individual Therapy', 'desc' => 'One-on-one counseling sessions'),
    array('icon' => '👥', 'name' => 'Group Sessions', 'desc' => 'Connect with others who understand'),
    array('icon' => '🧘', 'name' => 'Mindfulness', 'desc' => 'Learn techniques for inner peace'),
    array('icon' => '💊', 'name' => 'Psychiatry', 'desc' => 'Medication management when needed'),
);

$team = isset($site_content['team']) && is_array($site_content['team']) ? $site_content['team'] : array(
    array('name' => 'Dr. Grace Hope', 'role' => 'Clinical Director', 'initial' => 'G'),
    array('name' => 'Dr. Peace Chen', 'role' => 'Psychiatrist', 'initial' => 'P'),
    array('name' => 'Sarah Calm', 'role' => 'Licensed Therapist', 'initial' => 'S'),
);

$quick_features = isset($site_content['quick_features']) && is_array($site_content['quick_features']) ? $site_content['quick_features'] : array(
    array('icon' => '✓', 'name' => 'Judgment-free', 'desc' => ''),
    array('icon' => '✓', 'name' => 'Evidence-based', 'desc' => ''),
    array('icon' => '✓', 'name' => 'Personalized', 'desc' => ''),
    array('icon' => '✓', 'name' => 'Confidential', 'desc' => ''),
    array('icon' => '✓', 'name' => 'Compassionate', 'desc' => ''),
);
?>

<style>
@keyframes floatGentle {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(10deg); }
}
@keyframes grow {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
</style>

<!-- Crisis Banner -->
<div class="alert-banner crisis">
    <span>💚 In crisis? You matter. Help is available 24/7.</span>
    <a href="tel:<?php echo esc_attr($emergency); ?>" style="background: #fff; color: var(--primary); padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 700;">Call <?php echo esc_html($emergency); ?></a>
</div>

<!-- Header -->
<header class="header" id="home" style="background: rgba(255,255,255,0.95);">
    <div class="logo">
        <span>🧘</span>
        <?php echo esc_html($businessName); ?>
    </div>
    <nav class="nav">
        <a href="#home">Home</a>
        <a href="#services">Services</a>
        <a href="#conditions">What We Treat</a>
        <a href="#team">Our Team</a>
        <a href="#about">Resources</a>
        <a href="#contact">Contact</a>
    </nav>
    <a href="#contact" class="header-cta">Get Support</a>
</header>

<!-- Hero -->
<section class="hero" id="hero" style="padding: 100px 30px; position: relative; overflow: hidden;">
    <span style="position: absolute; top: 30px; left: 5%; font-size: 30px; opacity: 0.15; animation: floatGentle 5s ease-in-out infinite;">🍃</span>
    <span style="position: absolute; top: 50px; right: 8%; font-size: 30px; opacity: 0.15; animation: floatGentle 5s ease-in-out infinite; animation-delay: 1s;">🦋</span>
    <span style="position: absolute; bottom: 40px; left: 8%; font-size: 30px; opacity: 0.15; animation: floatGentle 5s ease-in-out infinite; animation-delay: 2s;">🌸</span>
    <span style="position: absolute; bottom: 30px; right: 5%; font-size: 30px; opacity: 0.15; animation: floatGentle 5s ease-in-out infinite; animation-delay: 1.5s;">🌿</span>
    <div style="font-size: 80px; margin-bottom: 20px; animation: grow 3s ease-in-out infinite;">🌱</div>
    <h1><?php echo esc_html($heroHeadline); ?></h1>
    <p><?php echo esc_html($heroSubheadline); ?></p>
    <div class="hero-btns">
        <a href="#contact" class="btn-primary"><?php echo esc_html($heroBtnPrimary); ?></a>
    </div>
</section>

<!-- Services -->
<section style="background: #fff;" id="services">
    <div class="section-title">
        <h2>Our Services</h2>
        <p>Personalized care tailored to your unique needs</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(<?php echo min(count($services), 4); ?>, 1fr);">
            <?php foreach ($services as $svc): ?>
                <div class="card">
                    <div class="card-icon"><?php echo esc_html($svc['icon'] ?? '💬'); ?></div>
                    <h3><?php echo esc_html($svc['name'] ?? 'Service'); ?></h3>
                    <p><?php echo esc_html($svc['desc'] ?? ''); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Our Approach -->
<section style="background: var(--background);" id="approach">
    <div class="section-title">
        <h2>Our Approach</h2>
        <p>Healing happens in a safe, supportive environment</p>
    </div>
    <div class="container" style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
        <?php foreach ($quick_features as $feature): ?>
            <a href="#services" style="background: var(--primary); color: #fff; padding: 12px 25px; border-radius: 25px; font-weight: 600; font-size: 14px; text-decoration: none;">
                <?php echo esc_html($feature['icon'] ?? '✓'); ?> <?php echo esc_html($feature['name'] ?? 'Feature'); ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- What We Treat -->
<section style="background: #fff;" id="conditions">
    <div class="section-title">
        <h2>What We Treat</h2>
        <p>Support for a wide range of mental health concerns</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(6, 1fr);">
            <?php 
            $conditions = array(
                array('icon' => '😰', 'name' => 'Anxiety'),
                array('icon' => '😔', 'name' => 'Depression'),
                array('icon' => '💔', 'name' => 'Trauma/PTSD'),
                array('icon' => '😤', 'name' => 'Stress'),
                array('icon' => '👨‍👩‍👧', 'name' => 'Family Issues'),
                array('icon' => '🍷', 'name' => 'Addiction'),
            );
            foreach ($conditions as $c): ?>
                <div class="card" style="padding: 20px 15px;">
                    <div style="font-size: 35px; margin-bottom: 10px;"><?php echo $c['icon']; ?></div>
                    <h3 style="font-size: 14px;"><?php echo $c['name']; ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Quote -->
<section style="background: var(--primary); color: #fff; padding: 60px 30px; text-align: center;" id="quote">
    <div style="font-size: 40px; margin-bottom: 15px;">💚</div>
    <p style="font-size: 24px; font-style: italic; max-width: 700px; margin: 0 auto; font-weight: 500;">
        "You don't have to face this alone. Healing is possible, and we're here to walk beside you every step of the way."
    </p>
</section>

<!-- Team -->
<section style="background: var(--background);" id="team">
    <div class="section-title">
        <h2>Our Caring Team</h2>
        <p>Licensed professionals dedicated to your wellbeing</p>
    </div>
    <div class="container">
        <div class="team-grid" style="grid-template-columns: repeat(<?php echo min(count($team), 4); ?>, 1fr);">
            <?php foreach ($team as $t): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div class="team-avatar"><?php echo esc_html($t['initial'] ?? substr($t['name'] ?? 'D', 0, 1)); ?></div>
                    </div>
                    <div class="team-info">
                        <h3><?php echo esc_html($t['name'] ?? 'Therapist'); ?></h3>
                        <div class="role"><?php echo esc_html($t['role'] ?? 'Counselor'); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section" id="cta">
    <h2><?php echo esc_html($ctaHeadline); ?></h2>
    <p><?php echo esc_html($ctaDescription); ?></p>
    <a href="#contact" class="btn-primary">🌱 <?php echo esc_html($ctaButton); ?></a>
</section>

<!-- Footer -->
<footer class="footer" id="contact">
    <div class="footer-grid">
        <div id="about">
            <h4>🧘 <?php echo esc_html($businessName); ?></h4>
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
            <h4>Resources</h4>
            <ul>
                <li><a href="#conditions">Crisis Resources</a></li>
                <li><a href="#about">Self-Care Tips</a></li>
                <li><a href="#about">Blog</a></li>
                <li><a href="#contact">FAQs</a></li>
                <li><a href="#contact">Insurance</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact</h4>
            <p>
                📍 <?php echo nl2br(esc_html($address)); ?><br><br>
                📞 <?php echo esc_html($phone); ?><br>
                ✉️ <?php echo esc_html($email); ?><br>
                💚 Crisis: <?php echo esc_html($emergency); ?>
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        © <?php echo date('Y'); ?> <?php echo esc_html($businessName); ?>. <?php echo esc_html($emergency); ?> Crisis Line Available 24/7.
    </div>
</footer>
