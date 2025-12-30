<?php
/**
 * Preview: Pediatric Hospital - Child-Friendly Playful
 * Uses site_content values for all editable fields
 */

// Get values from site_content (set in template-preview.php)
$businessName = get_site_content($site_content, 'business_name', 'Happy Kids Hospital');
$tagline = get_site_content($site_content, 'tagline', 'Where Healing is Fun!');
$aboutShort = get_site_content($site_content, 'about_short', 'Where healing is an adventure! We make hospital visits fun and comfortable for children of all ages.');

$phone = get_site_content($site_content, 'phone', '+1 (555) 456-7890');
$emergency = get_site_content($site_content, 'emergency', '911');
$email = get_site_content($site_content, 'email', 'info@happykids.com');
$hours = get_site_content($site_content, 'hours', 'Open 24/7');
$address = get_site_content($site_content, 'address', '123 Rainbow Lane, Kidsville, State 12345');

$heroHeadline = get_site_content($site_content, 'hero_headline', 'Where Little Heroes Get Big Care!');
$heroSubheadline = get_site_content($site_content, 'hero_subheadline', 'A magical place where healing happens with smiles. Our child-friendly environment makes hospital visits fun and less scary.');
$heroBtnPrimary = get_site_content($site_content, 'hero_btn_primary', 'Virtual Tour');
$heroBtnSecondary = get_site_content($site_content, 'hero_btn_secondary', 'Book Visit');

$stat1Num = get_site_content($site_content, 'stat1_num', '100K+');
$stat1Label = get_site_content($site_content, 'stat1_label', 'Kids Treated');
$stat2Num = get_site_content($site_content, 'stat2_num', '150');
$stat2Label = get_site_content($site_content, 'stat2_label', 'Specialists');
$stat3Num = get_site_content($site_content, 'stat3_num', '24/7');
$stat3Label = get_site_content($site_content, 'stat3_label', 'Pediatric ER');
$stat4Num = get_site_content($site_content, 'stat4_num', '4.9');
$stat4Label = get_site_content($site_content, 'stat4_label', 'Parent Rating');

$ctaHeadline = get_site_content($site_content, 'cta_headline', 'Schedule a Fun Visit!');
$ctaDescription = get_site_content($site_content, 'cta_description', 'Our kid-friendly facility makes healthcare an adventure. Book your child\'s appointment today!');
$ctaButton = get_site_content($site_content, 'cta_button', 'Book Appointment');
?>

<style>
.playful-float {
    position: absolute;
    font-size: 40px;
    opacity: 0.15;
    z-index: 0;
    animation: float 4s ease-in-out infinite;
}
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}
</style>

<!-- Header -->
<header class="header" style="background: linear-gradient(90deg, #fff 0%, #fef3c7 100%);">
    <div class="logo">
        <span>🧸</span>
        <?php echo esc_html($businessName); ?>
    </div>
    <nav class="nav" style="font-size: 20px;">
        <a href="#">🏠</a>
        <a href="#">👨‍⚕️</a>
        <a href="#">💉</a>
        <a href="#">👨‍👩‍👧</a>
        <a href="#">📞</a>
    </nav>
    <a href="#" class="header-cta">Book Visit 🎈</a>
</header>

<!-- Hero -->
<section class="hero" style="padding: 80px 30px; position: relative; overflow: hidden;">
    <span style="position: absolute; top: 20px; left: 5%; font-size: 40px; opacity: 0.2; animation: float 4s ease-in-out infinite;">⭐</span>
    <span style="position: absolute; top: 40px; right: 8%; font-size: 40px; opacity: 0.2; animation: float 4s ease-in-out infinite; animation-delay: 1s;">🎈</span>
    <span style="position: absolute; bottom: 30px; left: 8%; font-size: 40px; opacity: 0.2; animation: float 4s ease-in-out infinite; animation-delay: 2s;">🌈</span>
    <span style="position: absolute; bottom: 20px; right: 5%; font-size: 40px; opacity: 0.2; animation: float 4s ease-in-out infinite; animation-delay: 0.5s;">🦋</span>
    <div style="font-size: 80px; margin-bottom: 20px;">🦸‍♀️ 🦸‍♂️</div>
    <h1><?php echo esc_html($heroHeadline); ?></h1>
    <p><?php echo esc_html($heroSubheadline); ?></p>
    <div class="hero-btns">
        <a href="#" class="btn-primary">🎮 <?php echo esc_html($heroBtnPrimary); ?></a>
        <a href="#" class="btn-outline">📅 <?php echo esc_html($heroBtnSecondary); ?></a>
    </div>
</section>

<!-- Fun Features -->
<section style="background: #fff; padding: 40px 30px;">
    <div class="container" style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
        <?php 
        $features = array(
            '🎨 Colorful Rooms',
            '🎮 Play Areas', 
            '👨‍👩‍👧 Family Suites',
            '🤡 Clown Doctors',
            '🎁 Surprise Gifts'
        );
        foreach ($features as $f): ?>
            <span style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; padding: 15px 25px; border-radius: 25px; font-weight: 700; font-size: 14px;">
                <?php echo $f; ?>
            </span>
        <?php endforeach; ?>
    </div>
</section>

<!-- Services -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>🩺 Our Services for Kids</h2>
        <p>Specialized care designed just for children</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(4, 1fr);">
            <?php 
            $services = array(
                array('icon' => '👶', 'name' => 'Newborn Care'),
                array('icon' => '💉', 'name' => 'Vaccinations'),
                array('icon' => '🏥', 'name' => 'Pediatric Surgery'),
                array('icon' => '🧠', 'name' => 'Development'),
                array('icon' => '🫁', 'name' => 'Respiratory'),
                array('icon' => '🦴', 'name' => 'Orthopedics'),
                array('icon' => '❤️', 'name' => 'Cardiology'),
                array('icon' => '🎯', 'name' => 'Therapy'),
            );
            foreach ($services as $svc): ?>
                <div class="card" style="border: 3px solid var(--accent); background: linear-gradient(135deg, var(--background), #fff);">
                    <div class="card-icon" style="font-size: 40px; background: none;"><?php echo $svc['icon']; ?></div>
                    <h3 style="font-size: 16px;"><?php echo $svc['name']; ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="stats-section">
    <div class="stats-grid">
        <div>
            <div class="stat-num">🌟 <?php echo esc_html($stat1Num); ?></div>
            <div class="stat-label"><?php echo esc_html($stat1Label); ?></div>
        </div>
        <div>
            <div class="stat-num">👨‍⚕️ <?php echo esc_html($stat2Num); ?></div>
            <div class="stat-label"><?php echo esc_html($stat2Label); ?></div>
        </div>
        <div>
            <div class="stat-num">🏥 <?php echo esc_html($stat3Num); ?></div>
            <div class="stat-label"><?php echo esc_html($stat3Label); ?></div>
        </div>
        <div>
            <div class="stat-num">⭐ <?php echo esc_html($stat4Num); ?></div>
            <div class="stat-label"><?php echo esc_html($stat4Label); ?></div>
        </div>
    </div>
</section>

<!-- Team -->
<section style="background: #fff;">
    <div class="section-title">
        <h2>👨‍⚕️ Our Friendly Doctors</h2>
        <p>Pediatric specialists who love working with kids</p>
    </div>
    <div class="container">
        <div class="team-grid" style="grid-template-columns: repeat(4, 1fr);">
            <?php 
            $doctors = array(
                array('name' => 'Dr. Joy Smith', 'role' => 'Pediatrician', 'emoji' => '👩‍⚕️'),
                array('name' => 'Dr. Happy Lee', 'role' => 'Child Surgeon', 'emoji' => '👨‍⚕️'),
                array('name' => 'Dr. Fun Garcia', 'role' => 'Neurologist', 'emoji' => '👩‍⚕️'),
                array('name' => 'Dr. Care Wilson', 'role' => 'Cardiologist', 'emoji' => '👨‍⚕️'),
            );
            foreach ($doctors as $doc): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div style="font-size: 60px;"><?php echo $doc['emoji']; ?></div>
                    </div>
                    <div class="team-info">
                        <h3 style="font-size: 16px;"><?php echo $doc['name']; ?></h3>
                        <div class="role"><?php echo $doc['role']; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <h2>🎈 <?php echo esc_html($ctaHeadline); ?></h2>
    <p><?php echo esc_html($ctaDescription); ?></p>
    <a href="#" class="btn-primary">📅 <?php echo esc_html($ctaButton); ?></a>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>🧸 <?php echo esc_html($businessName); ?></h4>
            <p><?php echo esc_html($aboutShort); ?></p>
        </div>
        <div>
            <h4>Services</h4>
            <ul>
                <li><a href="#">👶 Newborn Care</a></li>
                <li><a href="#">💉 Vaccinations</a></li>
                <li><a href="#">🏥 Surgery</a></li>
                <li><a href="#">🧠 Development</a></li>
                <li><a href="#">🚑 Emergency</a></li>
            </ul>
        </div>
        <div>
            <h4>For Families</h4>
            <ul>
                <li><a href="#">Visitor Info</a></li>
                <li><a href="#">Play Areas</a></li>
                <li><a href="#">Family Rooms</a></li>
                <li><a href="#">Parking</a></li>
                <li><a href="#">Cafeteria</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact</h4>
            <p>
                📍 <?php echo nl2br(esc_html($address)); ?><br><br>
                📞 <?php echo esc_html($phone); ?><br>
                ✉️ <?php echo esc_html($email); ?><br>
                🚑 Emergency: <?php echo esc_html($emergency); ?>
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        © <?php echo date('Y'); ?> <?php echo esc_html($businessName); ?> 🎈 <?php echo esc_html($tagline); ?>
    </div>
</footer>
