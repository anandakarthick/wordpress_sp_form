<?php
/**
 * Preview: Eye Care Center - Vision-Centric Elegant
 */
$logoText = get_preview_value($defaults, 'logo_text', 'ClearView Eye Center');
$phone = get_preview_value($defaults, 'phone', '+1 (555) 345-6789');
$headline = get_preview_value($defaults, 'headline', 'See Life More Clearly');
$subheadline = get_preview_value($defaults, 'subheadline', 'Advanced eye care and vision correction from the region\'s leading ophthalmologists. Experience the freedom of clear vision.');
?>

<!-- Header -->
<header class="header">
    <div class="logo">
        <span>👁️</span>
        <?php echo esc_html($logoText); ?>
    </div>
    <nav class="nav">
        <a href="#">Home</a>
        <a href="#">Services</a>
        <a href="#">LASIK</a>
        <a href="#">Optical Shop</a>
        <a href="#">Our Doctors</a>
        <a href="#">Contact</a>
    </nav>
    <a href="#" class="header-cta">Book Eye Exam</a>
</header>

<!-- Hero -->
<section class="hero" style="padding: 100px 30px;">
    <div style="display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 30px;">
        <span style="font-size: 80px; animation: pulse 2s infinite;">👁️</span>
        <div style="display: flex; flex-direction: column; gap: 5px; opacity: 0.5;">
            <span style="display: block; height: 4px; background: #fff; border-radius: 4px; width: 60px;"></span>
            <span style="display: block; height: 4px; background: #fff; border-radius: 4px; width: 50px;"></span>
            <span style="display: block; height: 4px; background: #fff; border-radius: 4px; width: 40px;"></span>
        </div>
    </div>
    <h1><?php echo esc_html($headline); ?></h1>
    <p><?php echo esc_html($subheadline); ?></p>
    <div class="hero-btns">
        <a href="#" class="btn-primary">Free LASIK Consultation</a>
        <a href="#" class="btn-outline">Schedule Eye Exam</a>
    </div>
</section>

<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
</style>

<!-- LASIK Promo Banner -->
<div class="promo-banner">
    <div>
        <h3>✨ LASIK Vision Correction</h3>
        <p style="opacity: 0.9; font-size: 14px;">Free yourself from glasses and contacts forever</p>
    </div>
    <div class="price">Starting at $1,999/eye</div>
    <a href="#" class="btn-white">Learn More →</a>
</div>

<!-- Services -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Comprehensive Eye Care Services</h2>
        <p>From routine exams to advanced surgical procedures</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(3, 1fr);">
            <?php 
            $services = array(
                array('icon' => '✨', 'name' => 'LASIK Surgery', 'desc' => 'Advanced laser vision correction with 99% success rate'),
                array('icon' => '🔬', 'name' => 'Cataract Surgery', 'desc' => 'Premium lens implants for crystal clear vision'),
                array('icon' => '👓', 'name' => 'Optical Boutique', 'desc' => 'Designer frames and premium lenses'),
                array('icon' => '👁️', 'name' => 'Comprehensive Exams', 'desc' => 'Thorough eye health and vision evaluations'),
                array('icon' => '💧', 'name' => 'Dry Eye Treatment', 'desc' => 'Advanced therapies for lasting relief'),
                array('icon' => '🩺', 'name' => 'Glaucoma Care', 'desc' => 'Expert management of eye pressure conditions'),
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

<!-- Stats -->
<section class="stats-section">
    <div class="stats-grid">
        <div>
            <div class="stat-num">50K+</div>
            <div class="stat-label">LASIK Procedures</div>
        </div>
        <div>
            <div class="stat-num">99%</div>
            <div class="stat-label">Success Rate</div>
        </div>
        <div>
            <div class="stat-num">20+</div>
            <div class="stat-label">Years Experience</div>
        </div>
        <div>
            <div class="stat-num">4.9</div>
            <div class="stat-label">Patient Rating</div>
        </div>
    </div>
</section>

<!-- Why LASIK -->
<section style="background: #fff;">
    <div class="section-title">
        <h2>Why Choose LASIK?</h2>
        <p>Life-changing benefits of laser vision correction</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(4, 1fr);">
            <?php 
            $benefits = array(
                array('icon' => '⏱️', 'name' => '15-Minute Procedure'),
                array('icon' => '😎', 'name' => 'No More Glasses'),
                array('icon' => '🏃', 'name' => 'Active Lifestyle'),
                array('icon' => '💰', 'name' => 'Long-term Savings'),
            );
            foreach ($benefits as $b): ?>
                <div class="card" style="padding: 25px;">
                    <div class="card-icon" style="width: 60px; height: 60px; font-size: 28px;"><?php echo $b['icon']; ?></div>
                    <h3 style="font-size: 16px;"><?php echo $b['name']; ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Doctors -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Our Eye Care Specialists</h2>
        <p>Board-certified ophthalmologists and optometrists</p>
    </div>
    <div class="container">
        <div class="team-grid" style="grid-template-columns: repeat(3, 1fr);">
            <?php 
            $doctors = array(
                array('name' => 'Dr. Robert Vision', 'role' => 'LASIK Director', 'initial' => 'R'),
                array('name' => 'Dr. Lisa Chang', 'role' => 'Cataract Specialist', 'initial' => 'L'),
                array('name' => 'Dr. Mark Stevens', 'role' => 'Retina Specialist', 'initial' => 'M'),
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
    <h2>Ready to See Clearly?</h2>
    <p>Schedule your free LASIK consultation and discover if you're a candidate for vision freedom.</p>
    <a href="#" class="btn-primary">👁️ Free LASIK Consultation</a>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>👁️ <?php echo esc_html($logoText); ?></h4>
            <p>Your vision is our mission. We've helped over 50,000 patients see life more clearly with advanced eye care and vision correction.</p>
        </div>
        <div>
            <h4>Our Services</h4>
            <ul>
                <li><a href="#">LASIK Surgery</a></li>
                <li><a href="#">Cataract Surgery</a></li>
                <li><a href="#">Eye Exams</a></li>
                <li><a href="#">Optical Shop</a></li>
                <li><a href="#">Contact Lenses</a></li>
            </ul>
        </div>
        <div>
            <h4>Resources</h4>
            <ul>
                <li><a href="#">Am I a LASIK Candidate?</a></li>
                <li><a href="#">Financing Options</a></li>
                <li><a href="#">Insurance Info</a></li>
                <li><a href="#">Patient Portal</a></li>
                <li><a href="#">FAQs</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact Us</h4>
            <p>
                📍 789 Vision Way<br>
                Eye Care Plaza, Suite 100<br><br>
                📞 <?php echo esc_html($phone); ?><br>
                ⏰ Mon-Sat: 8am-6pm
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        © <?php echo date('Y'); ?> <?php echo esc_html($logoText); ?>. Your Vision, Our Mission.
    </div>
</footer>
