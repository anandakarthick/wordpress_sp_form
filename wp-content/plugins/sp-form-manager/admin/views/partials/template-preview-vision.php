<?php
/**
 * Template Preview: Eye Care Center - Vision Focused
 * Professional eye care with LASIK promotion
 */
?>
<div class="live-preview live-preview-eye">
    <div class="lp-header">
        <div class="lp-logo">👁️ <strong>ClearView Eye</strong></div>
        <div class="lp-nav">
            <span>Services</span>
            <span>LASIK</span>
            <span>Optical</span>
        </div>
        <div class="lp-cta-btn">Book Exam</div>
    </div>
    <div class="lp-hero">
        <div class="lp-eye-animation">
            <div class="eye-icon">👁️</div>
            <div class="vision-lines">
                <span></span><span></span><span></span>
            </div>
        </div>
        <h2>See Life More Clearly</h2>
        <p>Advanced eye care & vision correction</p>
        <div class="lp-hero-btns">
            <span class="btn-primary">Free LASIK Consult</span>
            <span class="btn-secondary">Eye Exam</span>
        </div>
    </div>
    <div class="lp-lasik-promo">
        <div class="promo-icon">✨</div>
        <div class="promo-text">
            <strong>LASIK Special</strong>
            <span>Starting at $1,999/eye</span>
        </div>
        <div class="promo-btn">Learn More →</div>
    </div>
    <div class="lp-services">
        <div class="service-card">
            <div class="service-icon">✨</div>
            <div class="service-name">LASIK</div>
            <div class="service-desc">Vision Freedom</div>
        </div>
        <div class="service-card">
            <div class="service-icon">🔬</div>
            <div class="service-name">Cataract</div>
            <div class="service-desc">Clear Vision</div>
        </div>
        <div class="service-card">
            <div class="service-icon">👓</div>
            <div class="service-name">Optical</div>
            <div class="service-desc">Designer Frames</div>
        </div>
    </div>
    <div class="lp-stats">
        <div class="stat"><strong>50K+</strong> LASIK Done</div>
        <div class="stat"><strong>99%</strong> Success</div>
        <div class="stat"><strong>20+</strong> Years</div>
    </div>
    <div class="lp-footer">
        <span>© ClearView Eye Center</span>
    </div>
</div>

<style>
.live-preview-eye {
    height: 100%;
    display: flex;
    flex-direction: column;
    font-size: 10px;
    background: var(--bg, #faf5ff);
}
.live-preview-eye .lp-header {
    background: #fff;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.live-preview-eye .lp-logo {
    color: var(--primary);
    font-size: 11px;
}
.live-preview-eye .lp-nav {
    display: flex;
    gap: 10px;
    color: #64748b;
    font-size: 8px;
}
.live-preview-eye .lp-cta-btn {
    background: var(--primary);
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 8px;
}
.live-preview-eye .lp-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    padding: 20px 15px;
    text-align: center;
}
.live-preview-eye .lp-eye-animation {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin-bottom: 10px;
}
.live-preview-eye .eye-icon {
    font-size: 35px;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
.live-preview-eye .vision-lines {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.live-preview-eye .vision-lines span {
    display: block;
    height: 2px;
    background: rgba(255,255,255,0.5);
    border-radius: 2px;
}
.live-preview-eye .vision-lines span:nth-child(1) { width: 30px; }
.live-preview-eye .vision-lines span:nth-child(2) { width: 25px; }
.live-preview-eye .vision-lines span:nth-child(3) { width: 20px; }
.live-preview-eye .lp-hero h2 {
    margin: 0 0 5px 0;
    font-size: 14px;
}
.live-preview-eye .lp-hero p {
    margin: 0 0 10px 0;
    opacity: 0.9;
    font-size: 9px;
}
.live-preview-eye .lp-hero-btns {
    display: flex;
    justify-content: center;
    gap: 8px;
}
.live-preview-eye .btn-primary {
    background: #fff;
    color: var(--primary);
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-eye .btn-secondary {
    border: 1px solid #fff;
    color: #fff;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
}
.live-preview-eye .lp-lasik-promo {
    background: linear-gradient(90deg, var(--accent), var(--primary));
    color: #fff;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.live-preview-eye .promo-icon {
    font-size: 20px;
}
.live-preview-eye .promo-text {
    flex: 1;
}
.live-preview-eye .promo-text strong {
    display: block;
    font-size: 10px;
}
.live-preview-eye .promo-text span {
    font-size: 8px;
    opacity: 0.9;
}
.live-preview-eye .promo-btn {
    background: rgba(255,255,255,0.2);
    padding: 4px 10px;
    border-radius: 10px;
    font-size: 8px;
}
.live-preview-eye .lp-services {
    display: flex;
    gap: 8px;
    padding: 15px;
    background: #fff;
    flex: 1;
}
.live-preview-eye .service-card {
    flex: 1;
    background: var(--bg);
    border-radius: 8px;
    padding: 12px 8px;
    text-align: center;
}
.live-preview-eye .service-icon {
    font-size: 20px;
    margin-bottom: 5px;
}
.live-preview-eye .service-name {
    font-weight: 700;
    font-size: 9px;
    color: var(--primary);
}
.live-preview-eye .service-desc {
    font-size: 7px;
    color: #64748b;
}
.live-preview-eye .lp-stats {
    display: flex;
    justify-content: space-around;
    padding: 12px;
    background: var(--primary);
    color: #fff;
}
.live-preview-eye .stat {
    text-align: center;
    font-size: 8px;
}
.live-preview-eye .stat strong {
    font-size: 12px;
}
.live-preview-eye .lp-footer {
    background: #2e1065;
    color: #fff;
    padding: 8px;
    text-align: center;
    font-size: 8px;
}
</style>
