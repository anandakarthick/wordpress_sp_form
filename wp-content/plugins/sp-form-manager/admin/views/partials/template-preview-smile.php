<?php
/**
 * Template Preview: Dental Clinic - Smile Focused
 * Modern dental with smile gallery emphasis
 */
?>
<div class="live-preview live-preview-dental">
    <div class="lp-header">
        <div class="lp-logo">🦷 <strong>Bright Smile</strong></div>
        <div class="lp-phone">📞 (555) 234-5678</div>
        <div class="lp-cta-btn">Book Visit ✨</div>
    </div>
    <div class="lp-hero">
        <div class="lp-smile-graphic">😊</div>
        <h2>Your Perfect Smile Starts Here</h2>
        <p>Gentle, modern dentistry for the whole family</p>
        <div class="lp-promo-badge">✨ Free Whitening with Checkup!</div>
        <div class="lp-hero-btns">
            <span class="btn-primary">Schedule Visit</span>
            <span class="btn-secondary">Free Consultation</span>
        </div>
    </div>
    <div class="lp-services-pills">
        <span class="service-pill">✨ Whitening</span>
        <span class="service-pill">📐 Invisalign</span>
        <span class="service-pill">🔧 Implants</span>
        <span class="service-pill">👑 Crowns</span>
    </div>
    <div class="lp-gallery-section">
        <div class="gallery-title">😁 Smile Transformations</div>
        <div class="gallery-grid">
            <div class="gallery-item before-after">
                <div class="before">Before</div>
                <div class="after">After ✨</div>
            </div>
            <div class="gallery-item before-after">
                <div class="before">Before</div>
                <div class="after">After ✨</div>
            </div>
        </div>
    </div>
    <div class="lp-stats">
        <div class="stat">😊 <strong>15K+</strong><br>Smiles</div>
        <div class="stat">⭐ <strong>5.0</strong><br>Rating</div>
        <div class="stat">🎖️ <strong>25</strong><br>Years</div>
    </div>
    <div class="lp-footer">
        <span>© Bright Smile Dental • Creating Beautiful Smiles</span>
    </div>
</div>

<style>
.live-preview-dental {
    height: 100%;
    display: flex;
    flex-direction: column;
    font-size: 10px;
    background: var(--bg, #f0f9ff);
}
.live-preview-dental .lp-header {
    background: #fff;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.live-preview-dental .lp-logo {
    color: var(--primary);
    font-size: 12px;
}
.live-preview-dental .lp-phone {
    color: #64748b;
    font-size: 9px;
}
.live-preview-dental .lp-cta-btn {
    background: var(--primary);
    color: #fff;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 9px;
    font-weight: 600;
}
.live-preview-dental .lp-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    padding: 20px 15px;
    text-align: center;
    position: relative;
}
.live-preview-dental .lp-smile-graphic {
    font-size: 40px;
    margin-bottom: 8px;
    animation: bounce 2s infinite;
}
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
.live-preview-dental .lp-hero h2 {
    margin: 0 0 5px 0;
    font-size: 14px;
}
.live-preview-dental .lp-hero p {
    margin: 0 0 8px 0;
    opacity: 0.9;
    font-size: 9px;
}
.live-preview-dental .lp-promo-badge {
    background: rgba(255,255,255,0.2);
    display: inline-block;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 8px;
    margin-bottom: 10px;
}
.live-preview-dental .lp-hero-btns {
    display: flex;
    justify-content: center;
    gap: 8px;
}
.live-preview-dental .btn-primary {
    background: #fff;
    color: var(--primary);
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-dental .btn-secondary {
    border: 1px solid #fff;
    color: #fff;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
}
.live-preview-dental .lp-services-pills {
    display: flex;
    justify-content: center;
    gap: 6px;
    padding: 12px;
    background: #fff;
    flex-wrap: wrap;
}
.live-preview-dental .service-pill {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: #fff;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 8px;
}
.live-preview-dental .lp-gallery-section {
    padding: 12px;
    background: #fff;
    flex: 1;
}
.live-preview-dental .gallery-title {
    text-align: center;
    color: var(--primary);
    font-weight: 700;
    margin-bottom: 10px;
    font-size: 11px;
}
.live-preview-dental .gallery-grid {
    display: flex;
    gap: 8px;
    justify-content: center;
}
.live-preview-dental .before-after {
    display: flex;
    border-radius: 6px;
    overflow: hidden;
    font-size: 7px;
}
.live-preview-dental .before {
    background: #e2e8f0;
    padding: 15px 10px;
    color: #64748b;
}
.live-preview-dental .after {
    background: var(--accent);
    padding: 15px 10px;
    color: #fff;
}
.live-preview-dental .lp-stats {
    display: flex;
    justify-content: space-around;
    padding: 12px;
    background: var(--bg);
}
.live-preview-dental .stat {
    text-align: center;
    font-size: 8px;
    color: var(--primary);
}
.live-preview-dental .stat strong {
    font-size: 14px;
    display: block;
}
.live-preview-dental .lp-footer {
    background: #0c4a6e;
    color: #fff;
    padding: 8px;
    text-align: center;
    font-size: 8px;
    opacity: 0.9;
}
</style>
