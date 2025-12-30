<?php
/**
 * Preview: Mental Health Clinic - Calming Supportive
 */
$logoText = get_preview_value($defaults, 'logo_text', 'Serenity Wellness');
$phone = get_preview_value($defaults, 'phone', '+1 (555) 678-9012');
$headline = get_preview_value($defaults, 'headline', 'Your Journey to Wellness Begins Here');
$subheadline = get_preview_value($defaults, 'subheadline', 'Compassionate mental health care in a safe, supportive environment. You\'re not alone – we\'re here to help.');
?>

<style>
.nature-float {
    position: fixed;
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

<span class="nature-float" style="top: 120px; left: 3%;">🍃</span>
<span class="nature-float" style="top: 250px; right: 5%; animation-delay: 1s;">🦋</span>
<span class="nature-float" style="top: 450px; left: 5%; animation-delay: 2s;">🌸</span>
<span class="nature-float" style="top: 650px; right: 3%; animation-delay: 1.5s;">🌿</span>

<!-- Crisis Banner -->
<div class="alert-banner crisis">
    <span>💚 In crisis? You matter. Help is available 24/7.</span>
    <a href="tel:988" style="background: #fff; color: var(--primary); padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 700;">Call 988</a>
</div>

<!-- Header -->
<header class="header" style="background: rgba(255,255,255,0.95);">
    <div class="logo">
        <span>🧘</span>
        <?php echo esc_html($logoText); ?>
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
<section class="hero" style="padding: 100px 30px;">
    <div style="font-size: 80px; margin-bottom: 20px; animation: grow 3s ease-in-out infinite;">🌱</div>
    <h1><?php echo esc_html($headline); ?></h1>
    <p><?php echo esc_html($subheadline); ?></p>
    <div class="hero-btns">
        <a href="#" class="btn-primary">Start Your Journey</a>
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
    <h2>Ready to Take the First Step?</h2>
    <p>Your journey to wellness begins with a single step. We're here to support you.</p>
    <a href="#" class="btn-primary">🌱 Schedule Consultation</a>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>🧘 <?php echo esc_html($logoText); ?></h4>
            <p>A safe space for healing and growth. We believe everyone deserves compassionate mental health care.</p>
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
                📍 222 Peaceful Path<br>
                Wellness Center, Suite 300<br><br>
                📞 <?php echo esc_html($phone); ?><br>
                💚 Crisis: 988
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        © <?php echo date('Y'); ?> <?php echo esc_html($logoText); ?>. 988 Crisis Line Available 24/7.
    </div>
</footer>
