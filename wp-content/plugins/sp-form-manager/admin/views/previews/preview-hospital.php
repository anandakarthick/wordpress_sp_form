<?php
/**
 * Preview: General Hospital - Multi-Department Professional
 */
$logoText = get_preview_value($defaults, 'logo_text', 'City General Hospital');
$phone = get_preview_value($defaults, 'phone', '+1 (555) 123-4567');
$emergency = get_preview_value($defaults, 'emergency_number', '911');
$headline = get_preview_value($defaults, 'headline', 'World-Class Healthcare for Everyone');
$subheadline = get_preview_value($defaults, 'subheadline', 'Comprehensive medical care with over 50 departments, 200+ expert physicians, and state-of-the-art facilities.');
?>

<!-- Top Bar -->
<div class="top-bar">
    <span>🚨 24/7 Emergency: <?php echo esc_html($emergency); ?> &nbsp;|&nbsp; 📞 <?php echo esc_html($phone); ?></span>
    <span>📍 123 Medical Center Drive, Healthcare City</span>
</div>

<!-- Header -->
<header class="header">
    <div class="logo">
        <span>🏥</span>
        <?php echo esc_html($logoText); ?>
    </div>
    <nav class="nav">
        <a href="#">Home</a>
        <a href="#">Departments</a>
        <a href="#">Find a Doctor</a>
        <a href="#">Services</a>
        <a href="#">Patient Portal</a>
        <a href="#">Contact</a>
    </nav>
    <a href="#" class="header-cta">Book Appointment</a>
</header>

<!-- Hero -->
<section class="hero">
    <h1><?php echo esc_html($headline); ?></h1>
    <p><?php echo esc_html($subheadline); ?></p>
    <div class="hero-btns">
        <a href="#" class="btn-primary">Find a Doctor</a>
        <a href="#" class="btn-outline">Our Services</a>
    </div>
</section>

<!-- Quick Services Cards -->
<div class="container" style="margin-top: -60px; position: relative; z-index: 10;">
    <div class="cards-grid" style="grid-template-columns: repeat(4, 1fr);">
        <div class="card">
            <div class="card-icon">🚑</div>
            <h3>Emergency Care</h3>
            <p>24/7 emergency services with rapid response</p>
        </div>
        <div class="card">
            <div class="card-icon">📅</div>
            <h3>Appointments</h3>
            <p>Easy online booking system</p>
        </div>
        <div class="card">
            <div class="card-icon">💊</div>
            <h3>Pharmacy</h3>
            <p>On-site pharmacy services</p>
        </div>
        <div class="card">
            <div class="card-icon">📱</div>
            <h3>Patient Portal</h3>
            <p>Access records online</p>
        </div>
    </div>
</div>

<!-- Departments Section -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Our Departments</h2>
        <p>Comprehensive care across 50+ medical specialties</p>
    </div>
    <div class="container">
        <div class="cards-grid" style="grid-template-columns: repeat(6, 1fr);">
            <?php 
            $departments = array(
                array('icon' => '❤️', 'name' => 'Cardiology'),
                array('icon' => '🧠', 'name' => 'Neurology'),
                array('icon' => '🦴', 'name' => 'Orthopedics'),
                array('icon' => '👶', 'name' => 'Pediatrics'),
                array('icon' => '🔬', 'name' => 'Oncology'),
                array('icon' => '🫁', 'name' => 'Pulmonology'),
                array('icon' => '👁️', 'name' => 'Ophthalmology'),
                array('icon' => '🦷', 'name' => 'Dental'),
                array('icon' => '🧬', 'name' => 'Genetics'),
                array('icon' => '🩺', 'name' => 'Internal Med'),
                array('icon' => '🤰', 'name' => 'OB/GYN'),
                array('icon' => '🧪', 'name' => 'Pathology'),
            );
            foreach ($departments as $dept): ?>
                <div class="card" style="padding: 25px 15px;">
                    <div class="card-icon" style="width: 60px; height: 60px; font-size: 28px;"><?php echo $dept['icon']; ?></div>
                    <h3 style="font-size: 14px;"><?php echo $dept['name']; ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stats-grid">
        <div>
            <div class="stat-num">500+</div>
            <div class="stat-label">Hospital Beds</div>
        </div>
        <div>
            <div class="stat-num">200+</div>
            <div class="stat-label">Expert Doctors</div>
        </div>
        <div>
            <div class="stat-num">50+</div>
            <div class="stat-label">Departments</div>
        </div>
        <div>
            <div class="stat-num">1M+</div>
            <div class="stat-label">Patients Served</div>
        </div>
    </div>
</section>

<!-- Featured Doctors -->
<section style="background: var(--background);">
    <div class="section-title">
        <h2>Our Expert Physicians</h2>
        <p>Meet our experienced and dedicated medical team</p>
    </div>
    <div class="container">
        <div class="team-grid">
            <?php 
            $doctors = array(
                array('name' => 'Dr. Sarah Johnson', 'role' => 'Chief Medical Officer', 'initial' => 'S'),
                array('name' => 'Dr. Michael Chen', 'role' => 'Head of Cardiology', 'initial' => 'M'),
                array('name' => 'Dr. Emily Brown', 'role' => 'Neurology Specialist', 'initial' => 'E'),
                array('name' => 'Dr. David Wilson', 'role' => 'Orthopedic Surgeon', 'initial' => 'D'),
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
    <h2>Need Emergency Care?</h2>
    <p>Our emergency department is open 24/7 with expert trauma care and rapid response teams.</p>
    <a href="#" class="btn-primary">🚨 Call Emergency: <?php echo esc_html($emergency); ?></a>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div>
            <h4>About Us</h4>
            <p><?php echo esc_html($logoText); ?> has been serving our community for over 50 years, providing exceptional healthcare with compassion and excellence.</p>
        </div>
        <div>
            <h4>Quick Links</h4>
            <ul>
                <li><a href="#">Find a Doctor</a></li>
                <li><a href="#">Patient Portal</a></li>
                <li><a href="#">Visitor Information</a></li>
                <li><a href="#">Careers</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
        <div>
            <h4>Departments</h4>
            <ul>
                <li><a href="#">Emergency Care</a></li>
                <li><a href="#">Cardiology</a></li>
                <li><a href="#">Neurology</a></li>
                <li><a href="#">Orthopedics</a></li>
                <li><a href="#">Pediatrics</a></li>
            </ul>
        </div>
        <div>
            <h4>Contact Info</h4>
            <p>
                📍 123 Medical Center Drive<br>
                Healthcare City, State 12345<br><br>
                📞 <?php echo esc_html($phone); ?><br>
                🚨 Emergency: <?php echo esc_html($emergency); ?>
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        © <?php echo date('Y'); ?> <?php echo esc_html($logoText); ?>. All rights reserved.
    </div>
</footer>
