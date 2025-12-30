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
?>

<style>
.nature-float {
    position: absolute;
    font-size: 30px;
    opacity: 0.2;
    z-index: 0;
    animation: floatGentle 5s ease-in-out infinite;
}
@keyframes floatGentle {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(10deg); }
}
</style>

<!-- Crisis Banner -->
<div class="alert-banner crisis">
    <span>💚 In crisis? You matter. Help is available 24/7.</span>
    <a href="tel:<?php echo esc_attr($emergency); ?>" style="background: #fff; color: var(--primary); padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 700;">Call <?php echo esc_html($emergency); ?></a>
</div>

<!-- Header -->
<header class="header" style="background: rgba(255,255,255,0.95);">
    <div class="logo">
        <span>🧘</span>
        <?php echo esc_html($businessName); ?>
    </div>
    <nav class="nav">
        <a href="#">Home</a>
        <a href="#">Services</a>
        <a href="#">What We Treat</a>
        <a href="#">Our Team</a>
        <a href="#">Resources</a>
        <a href="#">Contact</a>
    </nav>
    <a href="#" class="header-cta">Get Support</a>
</header>

<!-- Hero -->
<section class="hero" style="padding: 100px 30px; position: relative; overflow: hidden;">
    <span style="position: absolute; top: 30px; left: 5%; font-size: 30px; opacity: 0.15; animation: floatGentle 5s ease-in-out infinite;">🍃</span>
    <span style="position: absolute; top: 50px; right: 8%; font-size: 30px; opacity: 0.15; animation: floatGentle 5s ease-in-out infinite; animation-delay: 1s;">🦋</span>
    <span style="position: absolute; bottom: 40px; left: 8%; font-size: 30px; opacity: 0.15; animation: floatGentle 5s ease-in-out infinite; animation-delay: 2s;">🌸</span>
    <span style="position: absolute; bottom: 30px; right: 5%; font-size: 30px; opacity: 0.15; animation: floatGentle 5s ease-in-out infinite; animation-delay: 1.5s;">🌿</span>
    <div style="font-size: 80px; margin-bottom: 20px; animation: grow 3s ease-in-out infinite;">🌱</div>
    <h1><?php echo esc_html($heroHeadline); ?></h1>
    <p><?php echo esc_html($heroSubheadline); ?></p>
    <div class="hero-btns">
        <a href="#" class="btn-primary"><?php echo esc_html($heroBtnPrimary); ?></a>
    </div>
</section>

<style>
@keyframes grow {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
</style>

<!-- Services -->
<section style="background: #fff;">
    <div class="section-title">
        <h2>Our Services</h2>
        <p>Personalized care tailored to your unique needs</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(4, 1fr);">
            <?php 
            $services = array(
                array('icon' => '💬', 'name' => 'Individual Therapy', 'desc' => 'One-on-one counseling sessions'),
                array('icon' => '👥', 'name' => 'Group Sessions', 'desc' => 'Connect with others who understand'),
                array('icon' => '🧘', 'name' => 'Mindfulness', 'desc' => 'Learn techniques for inner peace'),
                array('icon' => '💊', 'name' => 'Psychiatry', 'desc' => 'Medication management when needed'),
            );
            foreach ($services as $svc): ?>
                <div class="card">
                    <div class="card-icon"><?php echo $svc['icon']; ?></div>
                    <h3><?php echo $svc['name']; ?></h3>
                    <p><?php echo $svc['desc']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Our Approach -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Our Approach</h2>
        <p>Healing happens in a safe, supportive environment</p>
    </div>
    <div class="container" style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
        <?php 
        $approach = array('✓ Judgment-free', '✓ Evidence-based', '✓ Personalized', '✓ Confidential', '✓ Compassionate');
        foreach ($approach as $a): ?>
            <span style="background: var(--primary); color: #fff; padding: 12px 25px; border-radius: 25px; font-weight: 600; font-size: 14px;">
                <?php echo $a; ?>
            </span>
        <?php endforeach; ?>
    </div>
</section>

<!-- What We Treat -->
<section style="background: #fff;">
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
<section style="background: var(--primary); color: #fff; padding: 60px 30px; text-align: center;">
    <div style="font-size: 40px; margin-bottom: 15px;">💚</div>
    <p style="font-size: 24px; font-style: italic; max-width: 700px; margin: 0 auto; font-weight: 500;">
        "You don't have to face this alone. Healing is possible, and we're here to walk beside you every step of the way."
    </p>
</section>

<!-- Team -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Our Caring Team</h2>
        <p>Licensed professionals dedicated to your wellbeing</p>
    </div>
    <div class="container">
        <div class="team-grid" style="grid-template-columns: repeat(3, 1fr);">
            <?php 
            $team = array(
                array('name' => 'Dr. Grace Hope', 'role' => 'Clinical Director', 'initial' => 'G'),
                array('name' => 'Dr. Peace Chen', 'role' => 'Psychiatrist', 'initial' => 'P'),
                array('name' => 'Sarah Calm', 'role' => 'Licensed Therapist', 'initial' => 'S'),
            );
            foreach ($team as $t): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div class="team-avatar"><?php echo $t['initial']; ?></div>
                    </div>
                    <div class="team-info">
                        <h3><?php echo $t['name']; ?></h3>
                        <div class="role"><?php echo $t['role']; ?></div>
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
    <a href="#" class="btn-primary">🌱 <?php echo esc_html($ctaButton); ?></a>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>🧘 <?php echo esc_html($businessName); ?></h4>
            <p><?php echo esc_html($aboutShort); ?></p>
        </div>
        <div>
            <h4>Services</h4>
            <ul>
                <li><a href="#">Individual Therapy</a></li>
                <li><a href="#">Group Therapy</a></li>
                <li><a href="#">Couples Counseling</a></li>
                <li><a href="#">Family Therapy</a></li>
                <li><a href="#">Psychiatry</a></li>
            </ul>
        </div>
        <div>
            <h4>Resources</h4>
            <ul>
                <li><a href="#">Crisis Resources</a></li>
                <li><a href="#">Self-Care Tips</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">FAQs</a></li>
                <li><a href="#">Insurance</a></li>
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
