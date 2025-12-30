<?php
/**
 * Template Preview: Default
 * Generic medical website preview
 */
?>
<div class="live-preview live-preview-default">
    <div class="lp-header">
        <div class="lp-logo">🏥 <strong>Medical Center</strong></div>
        <div class="lp-cta-btn">Contact</div>
    </div>
    <div class="lp-hero">
        <h2>Healthcare Website</h2>
        <p>Professional healthcare website</p>
        <div class="lp-hero-btns">
            <span class="btn-primary">Get Started</span>
        </div>
    </div>
    <div class="lp-services">
        <div class="service-item">📋 Services</div>
        <div class="service-item">👨‍⚕️ Doctors</div>
        <div class="service-item">📞 Contact</div>
    </div>
    <div class="lp-content">
        <p>Comprehensive healthcare solutions</p>
    </div>
    <div class="lp-footer">
        <span>© Medical Center</span>
    </div>
</div>

<style>
.live-preview-default {
    height: 100%;
    display: flex;
    flex-direction: column;
    font-size: 10px;
    background: var(--bg, #f8fafc);
}
.live-preview-default .lp-header {
    background: #fff;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.live-preview-default .lp-logo {
    color: var(--primary);
    font-size: 11px;
}
.live-preview-default .lp-cta-btn {
    background: var(--primary);
    color: #fff;
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 8px;
}
.live-preview-default .lp-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    padding: 30px 15px;
    text-align: center;
    flex: 0 0 auto;
}
.live-preview-default .lp-hero h2 {
    margin: 0 0 8px 0;
    font-size: 14px;
}
.live-preview-default .lp-hero p {
    margin: 0 0 12px 0;
    font-size: 9px;
    opacity: 0.9;
}
.live-preview-default .btn-primary {
    background: #fff;
    color: var(--primary);
    padding: 6px 16px;
    border-radius: 15px;
    font-size: 9px;
    font-weight: 600;
}
.live-preview-default .lp-services {
    display: flex;
    justify-content: space-around;
    padding: 15px;
    background: #fff;
}
.live-preview-default .service-item {
    text-align: center;
    font-size: 9px;
    color: var(--primary);
}
.live-preview-default .lp-content {
    flex: 1;
    padding: 20px;
    background: var(--bg);
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}
.live-preview-default .lp-footer {
    background: var(--secondary);
    color: #fff;
    padding: 10px;
    text-align: center;
    font-size: 8px;
}
</style>
