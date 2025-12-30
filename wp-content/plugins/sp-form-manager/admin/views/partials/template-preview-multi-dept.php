<?php
/**
 * Template Preview: Multi-Department Hospital
 * Professional hospital layout with departments grid
 */
?>
<div class="live-preview live-preview-hospital">
    <div class="lp-topbar">
        <span>🚨 24/7 Emergency</span>
        <span>📞 +1 (555) 123-4567</span>
    </div>
    <div class="lp-header">
        <div class="lp-logo">🏥 <strong>City General</strong></div>
        <div class="lp-nav">
            <span>Home</span>
            <span>Departments</span>
            <span>Doctors</span>
            <span>Services</span>
        </div>
        <div class="lp-cta-btn">Patient Portal</div>
    </div>
    <div class="lp-hero">
        <div class="lp-hero-badge">🏆 #1 Rated Hospital</div>
        <div class="lp-hero-content">
            <h2>World-Class Healthcare</h2>
            <p>Comprehensive care with 50+ departments</p>
            <div class="lp-hero-btns">
                <span class="btn-primary">Find a Doctor</span>
                <span class="btn-secondary">Our Services</span>
            </div>
        </div>
    </div>
    <div class="lp-departments">
        <div class="lp-dept-grid">
            <div class="dept-item">❤️ Cardiology</div>
            <div class="dept-item">🧠 Neurology</div>
            <div class="dept-item">🦴 Orthopedics</div>
            <div class="dept-item">👶 Pediatrics</div>
            <div class="dept-item">🔬 Oncology</div>
            <div class="dept-item">🫁 Pulmonology</div>
        </div>
    </div>
    <div class="lp-stats-bar">
        <div class="stat"><strong>500+</strong> Beds</div>
        <div class="stat"><strong>200+</strong> Doctors</div>
        <div class="stat"><strong>50+</strong> Depts</div>
        <div class="stat"><strong>1M+</strong> Patients</div>
    </div>
    <div class="lp-footer">
        <span>© City General Hospital</span>
    </div>
</div>

<style>
.live-preview-hospital {
    height: 100%;
    display: flex;
    flex-direction: column;
    font-size: 10px;
    background: var(--bg, #f0fdfa);
}
.live-preview-hospital .lp-topbar {
    background: var(--primary);
    color: #fff;
    padding: 4px 10px;
    display: flex;
    justify-content: space-between;
    font-size: 8px;
}
.live-preview-hospital .lp-header {
    background: #fff;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.live-preview-hospital .lp-logo {
    color: var(--primary);
    font-size: 12px;
}
.live-preview-hospital .lp-nav {
    display: flex;
    gap: 12px;
    color: #64748b;
    font-size: 9px;
}
.live-preview-hospital .lp-cta-btn {
    background: var(--primary);
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-hospital .lp-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    padding: 25px 15px;
    position: relative;
    flex: 0 0 auto;
}
.live-preview-hospital .lp-hero-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(255,255,255,0.2);
    padding: 3px 8px;
    border-radius: 10px;
    font-size: 7px;
}
.live-preview-hospital .lp-hero h2 {
    margin: 0 0 5px 0;
    font-size: 16px;
}
.live-preview-hospital .lp-hero p {
    margin: 0 0 12px 0;
    opacity: 0.9;
    font-size: 9px;
}
.live-preview-hospital .lp-hero-btns {
    display: flex;
    gap: 8px;
}
.live-preview-hospital .btn-primary {
    background: #fff;
    color: var(--primary);
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-hospital .btn-secondary {
    border: 1px solid #fff;
    color: #fff;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
}
.live-preview-hospital .lp-departments {
    padding: 15px;
    background: #fff;
    flex: 1;
}
.live-preview-hospital .lp-dept-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}
.live-preview-hospital .dept-item {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    padding: 10px 8px;
    border-radius: 6px;
    text-align: center;
    font-size: 8px;
}
.live-preview-hospital .lp-stats-bar {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    justify-content: space-around;
    padding: 12px;
    color: #fff;
}
.live-preview-hospital .stat {
    text-align: center;
    font-size: 8px;
}
.live-preview-hospital .stat strong {
    display: block;
    font-size: 12px;
}
.live-preview-hospital .lp-footer {
    background: #0f172a;
    color: #fff;
    padding: 8px;
    text-align: center;
    font-size: 8px;
    opacity: 0.8;
}
</style>
