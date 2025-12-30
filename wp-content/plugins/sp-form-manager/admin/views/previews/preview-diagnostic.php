<?php
/**
 * Preview: Diagnostic Lab - Tech-Forward Data-Centric
 */
$logoText = get_preview_value($defaults, 'logo_text', 'PrecisionLab Diagnostics');
$phone = get_preview_value($defaults, 'phone', '+1 (555) 890-1234');
$headline = get_preview_value($defaults, 'headline', 'Accurate Results, Better Health');
$subheadline = get_preview_value($defaults, 'subheadline', 'State-of-the-art diagnostic testing with quick turnaround. 500+ tests, online reports, and convenient home collection.');
?>

<!-- Header -->
<header class="header">
    <div class="logo">
        <span>🔬</span>
        <?php echo esc_html($logoText); ?>
    </div>
    <nav class="nav">
        <a href="#">Home</a>
        <a href="#">Test Catalog</a>
        <a href="#">Health Packages</a>
        <a href="#">Home Collection</a>
        <a href="#">Locations</a>
        <a href="#">Contact</a>
    </nav>
    <div style="display: flex; gap: 10px;">
        <a href="#" style="background: var(--background); color: var(--primary); padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">📱 Get Reports</a>
        <a href="#" class="header-cta">Book Test</a>
    </div>
</header>

<!-- Hero -->
<section class="hero" style="padding: 80px 30px;">
    <div style="font-size: 60px; margin-bottom: 20px; display: flex; justify-content: center; gap: 15px;">
        <span style="animation: shake 0.5s ease-in-out infinite;">🧪</span>
        <span>🔬</span>
    </div>
    <h1><?php echo esc_html($headline); ?></h1>
    <p><?php echo esc_html($subheadline); ?></p>
    <div class="hero-btns">
        <a href="#" class="btn-primary">Book a Test</a>
        <a href="#" class="btn-outline">View Test Catalog</a>
    </div>
</section>

<style>
@keyframes shake {
    0%, 100% { transform: rotate(-5deg); }
    50% { transform: rotate(5deg); }
}
</style>

<!-- Features -->
<section style="background: #fff; padding: 40px 30px;">
    <div class="container" style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
        <?php 
        $features = array(
            '📱 Online Reports',
            '🏠 Home Collection', 
            '⏱️ 24h Results',
            '✅ NABL Certified',
            '🚗 Free Parking'
        );
        foreach ($features as $f): ?>
            <span style="background: linear-gradient(135deg, var(--background), #fff); border: 2px solid var(--accent); color: var(--primary); padding: 12px 22px; border-radius: 25px; font-weight: 600; font-size: 14px;">
                <?php echo $f; ?>
            </span>
        <?php endforeach; ?>
    </div>
</section>

<!-- Test Categories -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Test Categories</h2>
        <p>Comprehensive diagnostic testing for all your health needs</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(6, 1fr);">
            <?php 
            $categories = array(
                array('icon' => '🩸', 'name' => 'Blood Tests'),
                array('icon' => '💉', 'name' => 'Diabetes'),
                array('icon' => '🦋', 'name' => 'Thyroid'),
                array('icon' => '❤️', 'name' => 'Cardiac'),
                array('icon' => '🫁', 'name' => 'Liver/Kidney'),
                array('icon' => '🧬', 'name' => 'Allergy'),
            );
            foreach ($categories as $c): ?>
                <div class="card" style="padding: 20px 15px;">
                    <div style="font-size: 35px; margin-bottom: 10px;"><?php echo $c['icon']; ?></div>
                    <h3 style="font-size: 14px;"><?php echo $c['name']; ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Health Packages -->
<section style="background: #fff;">
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
                <a href="#" class="btn-package">Book Now</a>
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
                <a href="#" class="btn-package">Book Now</a>
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
                <a href="#" class="btn-package">Book Now</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="stats-section">
    <div class="stats-grid">
        <div>
            <div class="stat-num">1M+</div>
            <div class="stat-label">Tests Done</div>
        </div>
        <div>
            <div class="stat-num">99.9%</div>
            <div class="stat-label">Accuracy</div>
        </div>
        <div>
            <div class="stat-num">50+</div>
            <div class="stat-label">Centers</div>
        </div>
        <div>
            <div class="stat-num">24h</div>
            <div class="stat-label">Report Time</div>
        </div>
    </div>
</section>

<!-- Home Collection -->
<div class="promo-banner">
    <div>
        <span style="font-size: 30px;">🏠</span>
    </div>
    <div>
        <h3>Home Collection Available</h3>
        <p style="opacity: 0.9; font-size: 14px;">Get your samples collected from the comfort of your home</p>
    </div>
    <a href="#" class="btn-white">Book Home Visit →</a>
</div>

<!-- How It Works -->
<section style="background: var(--background);">
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
<section class="cta-section">
    <h2>Book Your Health Checkup Today</h2>
    <p>Take charge of your health with regular preventive testing. Accurate results, trusted care.</p>
    <a href="#" class="btn-primary">🧪 Book a Test</a>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>🔬 <?php echo esc_html($logoText); ?></h4>
            <p>Your trusted partner for accurate diagnostic testing. NABL accredited labs with state-of-the-art technology.</p>
        </div>
        <div>
            <h4>Popular Tests</h4>
            <ul>
                <li><a href="#">Complete Blood Count</a></li>
                <li><a href="#">Lipid Profile</a></li>
                <li><a href="#">Thyroid Panel</a></li>
                <li><a href="#">HbA1c</a></li>
                <li><a href="#">Vitamin D</a></li>
            </ul>
        </div>
        <div>
            <h4>Services</h4>
            <ul>
                <li><a href="#">Home Collection</a></li>
                <li><a href="#">Corporate Health</a></li>
                <li><a href="#">Health Packages</a></li>
                <li><a href="#">Online Reports</a></li>
                <li><a href="#">Locations</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact</h4>
            <p>
                📍 111 Lab Lane<br>
                Diagnostic Center<br><br>
                📞 <?php echo esc_html($phone); ?><br>
                ⏰ 24/7 Online Booking
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        © <?php echo date('Y'); ?> <?php echo esc_html($logoText); ?>. NABL Accredited.
    </div>
</footer>
