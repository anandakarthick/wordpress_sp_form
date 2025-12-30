<?php
/**
 * Preview: Diagnostic Lab - Tech-Forward Data-Centric
 * Uses site_content values for all editable fields
 */

// Get values from site_content (set in template-preview.php)
$businessName = get_site_content($site_content, 'business_name', 'PrecisionLab Diagnostics');
$tagline = get_site_content($site_content, 'tagline', 'NABL Accredited');
$aboutShort = get_site_content($site_content, 'about_short', 'Your trusted partner for accurate diagnostic testing. NABL accredited labs with state-of-the-art technology.');

$phone = get_site_content($site_content, 'phone', '+1 (555) 890-1234');
$emergency = get_site_content($site_content, 'emergency', '+1 (555) 890-9999');
$email = get_site_content($site_content, 'email', 'tests@precisionlab.com');
$hours = get_site_content($site_content, 'hours', '24/7 Online Booking');
$address = get_site_content($site_content, 'address', '111 Lab Lane, Diagnostic Center');

$heroHeadline = get_site_content($site_content, 'hero_headline', 'Accurate Results, Better Health');
$heroSubheadline = get_site_content($site_content, 'hero_subheadline', 'State-of-the-art diagnostic testing with quick turnaround. 500+ tests, online reports, and convenient home collection.');
$heroBtnPrimary = get_site_content($site_content, 'hero_btn_primary', 'Book a Test');
$heroBtnSecondary = get_site_content($site_content, 'hero_btn_secondary', 'View Test Catalog');

$stat1Num = get_site_content($site_content, 'stat1_num', '1M+');
$stat1Label = get_site_content($site_content, 'stat1_label', 'Tests Done');
$stat2Num = get_site_content($site_content, 'stat2_num', '99.9%');
$stat2Label = get_site_content($site_content, 'stat2_label', 'Accuracy');
$stat3Num = get_site_content($site_content, 'stat3_num', '50+');
$stat3Label = get_site_content($site_content, 'stat3_label', 'Centers');
$stat4Num = get_site_content($site_content, 'stat4_num', '24h');
$stat4Label = get_site_content($site_content, 'stat4_label', 'Report Time');

$ctaHeadline = get_site_content($site_content, 'cta_headline', 'Book Your Health Checkup Today');
$ctaDescription = get_site_content($site_content, 'cta_description', 'Take charge of your health with regular preventive testing. Accurate results, trusted care.');
$ctaButton = get_site_content($site_content, 'cta_button', 'Book a Test');

// Get repeater data with defaults
$services = isset($site_content['services']) && is_array($site_content['services']) ? $site_content['services'] : array(
    array('icon' => '🩸', 'name' => 'Blood Tests', 'desc' => 'Complete blood analysis'),
    array('icon' => '💉', 'name' => 'Diabetes', 'desc' => 'Blood sugar tests'),
    array('icon' => '🦋', 'name' => 'Thyroid', 'desc' => 'Thyroid panel'),
    array('icon' => '❤️', 'name' => 'Cardiac', 'desc' => 'Heart health markers'),
    array('icon' => '🫁', 'name' => 'Liver/Kidney', 'desc' => 'Organ function tests'),
    array('icon' => '🧬', 'name' => 'Allergy', 'desc' => 'Allergy testing'),
);

$team = isset($site_content['team']) && is_array($site_content['team']) ? $site_content['team'] : array(
    array('name' => 'Dr. Lab Expert', 'role' => 'Lab Director', 'initial' => 'L'),
    array('name' => 'Dr. Test Smith', 'role' => 'Pathologist', 'initial' => 'T'),
    array('name' => 'Dr. Sample Lee', 'role' => 'Lab Manager', 'initial' => 'S'),
);

$quick_features = isset($site_content['quick_features']) && is_array($site_content['quick_features']) ? $site_content['quick_features'] : array(
    array('icon' => '📱', 'name' => 'Online Reports', 'desc' => ''),
    array('icon' => '🏠', 'name' => 'Home Collection', 'desc' => ''),
    array('icon' => '⏱️', 'name' => '24h Results', 'desc' => ''),
    array('icon' => '✅', 'name' => 'NABL Certified', 'desc' => ''),
    array('icon' => '🚗', 'name' => 'Free Parking', 'desc' => ''),
);
?>

<!-- Header -->
<header class="header" id="home">
    <div class="logo">
        <span>🔬</span>
        <?php echo esc_html($businessName); ?>
    </div>
    <nav class="nav">
        <a href="#home">Home</a>
        <a href="#tests">Test Catalog</a>
        <a href="#packages">Health Packages</a>
        <a href="#home-collection">Home Collection</a>
        <a href="#about">Locations</a>
        <a href="#contact">Contact</a>
    </nav>
    <div style="display: flex; gap: 10px;">
        <a href="#contact" style="background: var(--background); color: var(--primary); padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">📱 Get Reports</a>
        <a href="#packages" class="header-cta">Book Test</a>
    </div>
</header>

<!-- Hero -->
<section class="hero" id="hero" style="padding: 80px 30px;">
    <div style="font-size: 60px; margin-bottom: 20px; display: flex; justify-content: center; gap: 15px;">
        <span style="animation: shake 0.5s ease-in-out infinite;">🧪</span>
        <span>🔬</span>
    </div>
    <h1><?php echo esc_html($heroHeadline); ?></h1>
    <p><?php echo esc_html($heroSubheadline); ?></p>
    <div class="hero-btns">
        <a href="#packages" class="btn-primary"><?php echo esc_html($heroBtnPrimary); ?></a>
        <a href="#tests" class="btn-outline"><?php echo esc_html($heroBtnSecondary); ?></a>
    </div>
</section>

<style>
@keyframes shake {
    0%, 100% { transform: rotate(-5deg); }
    50% { transform: rotate(5deg); }
}
</style>

<!-- Features -->
<section style="background: #fff; padding: 40px 30px;" id="features">
    <div class="container" style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
        <?php foreach ($quick_features as $feature): ?>
            <a href="#tests" style="background: linear-gradient(135deg, var(--background), #fff); border: 2px solid var(--accent); color: var(--primary); padding: 12px 22px; border-radius: 25px; font-weight: 600; font-size: 14px; text-decoration: none;">
                <?php echo esc_html($feature['icon'] ?? '✅'); ?> <?php echo esc_html($feature['name'] ?? 'Feature'); ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Test Categories -->
<section style="background: var(--background);" id="tests">
    <div class="section-title">
        <h2>Test Categories</h2>
        <p>Comprehensive diagnostic testing for all your health needs</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(<?php echo min(count($services), 6); ?>, 1fr);">
            <?php foreach ($services as $c): ?>
                <div class="card" style="padding: 20px 15px;">
                    <div style="font-size: 35px; margin-bottom: 10px;"><?php echo esc_html($c['icon'] ?? '🧪'); ?></div>
                    <h3 style="font-size: 14px;"><?php echo esc_html($c['name'] ?? 'Test'); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Health Packages -->
<section style="background: #fff;" id="packages">
    <div class="section-title">
        <h2>Health Packages</h2>
        <p>Comprehensive checkup packages for preventive care</p>
    </div>
    <div class="container">
        <div class="packages-grid">
            <div class="package-card">
                <h3>Basic Health</h3>
                <div class="price">$99</div>
                <div class="tests">40+ Tests</div>
                <ul style="text-align: left; font-size: 13px; color: #64748b; margin-bottom: 20px; padding-left: 20px;">
                    <li>Complete Blood Count</li>
                    <li>Blood Sugar</li>
                    <li>Lipid Profile</li>
                    <li>Liver Function</li>
                </ul>
                <a href="#contact" class="btn-package">Book Now</a>
            </div>
            <div class="package-card featured">
                <span class="badge">Most Popular</span>
                <h3>Comprehensive</h3>
                <div class="price">$199</div>
                <div class="tests">70+ Tests</div>
                <ul style="text-align: left; font-size: 13px; margin-bottom: 20px; padding-left: 20px; opacity: 0.9;">
                    <li>All Basic Tests</li>
                    <li>Thyroid Profile</li>
                    <li>Cardiac Markers</li>
                    <li>Vitamin Panel</li>
                </ul>
                <a href="#contact" class="btn-package">Book Now</a>
            </div>
            <div class="package-card">
                <h3>Executive</h3>
                <div class="price">$349</div>
                <div class="tests">100+ Tests</div>
                <ul style="text-align: left; font-size: 13px; color: #64748b; margin-bottom: 20px; padding-left: 20px;">
                    <li>All Comprehensive Tests</li>
                    <li>Cancer Markers</li>
                    <li>Hormone Panel</li>
                    <li>ECG Included</li>
                </ul>
                <a href="#contact" class="btn-package">Book Now</a>
            </div>
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

<!-- Home Collection -->
<div class="promo-banner" id="home-collection">
    <div>
        <span style="font-size: 30px;">🏠</span>
    </div>
    <div>
        <h3>Home Collection Available</h3>
        <p style="opacity: 0.9; font-size: 14px;">Get your samples collected from the comfort of your home</p>
    </div>
    <a href="#contact" class="btn-white">Book Home Visit →</a>
</div>

<!-- How It Works -->
<section style="background: var(--background);" id="how-it-works">
    <div class="section-title">
        <h2>How It Works</h2>
        <p>Simple, convenient, and fast</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(4, 1fr);">
            <?php 
            $steps = array(
                array('icon' => '📅', 'name' => 'Book Online', 'desc' => 'Choose your tests'),
                array('icon' => '🏠', 'name' => 'Sample Collection', 'desc' => 'At lab or home'),
                array('icon' => '🔬', 'name' => 'Testing', 'desc' => 'NABL certified labs'),
                array('icon' => '📱', 'name' => 'Get Report', 'desc' => 'Online in 24hrs'),
            );
            foreach ($steps as $i => $step): ?>
                <div class="card">
                    <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--primary); color: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;"><?php echo $i + 1; ?></div>
                    <div style="font-size: 35px; margin-bottom: 10px;"><?php echo $step['icon']; ?></div>
                    <h3 style="font-size: 16px;"><?php echo $step['name']; ?></h3>
                    <p style="font-size: 13px;"><?php echo $step['desc']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section" id="cta">
    <h2><?php echo esc_html($ctaHeadline); ?></h2>
    <p><?php echo esc_html($ctaDescription); ?></p>
    <a href="#packages" class="btn-primary">🧪 <?php echo esc_html($ctaButton); ?></a>
</section>

<!-- Footer -->
<footer class="footer" id="contact">
    <div class="footer-grid">
        <div id="about">
            <h4>🔬 <?php echo esc_html($businessName); ?></h4>
            <p><?php echo esc_html($aboutShort); ?></p>
        </div>
        <div>
            <h4>Popular Tests</h4>
            <ul>
                <?php foreach (array_slice($services, 0, 5) as $service): ?>
                    <li><a href="#tests"><?php echo esc_html($service['name'] ?? 'Test'); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h4>Services</h4>
            <ul>
                <li><a href="#home-collection">Home Collection</a></li>
                <li><a href="#packages">Corporate Health</a></li>
                <li><a href="#packages">Health Packages</a></li>
                <li><a href="#contact">Online Reports</a></li>
                <li><a href="#about">Locations</a></li>
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
