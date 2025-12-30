<?php
/**
 * Template Preview: Pediatric Hospital - Playful Design
 * Child-friendly colorful layout
 */
?>
<div class="live-preview live-preview-pediatric">
    <div class="lp-floating-icons">
        <span class="float-icon" style="left:5%;animation-delay:0s">⭐</span>
        <span class="float-icon" style="left:25%;animation-delay:0.5s">🎈</span>
        <span class="float-icon" style="left:45%;animation-delay:1s">🌈</span>
        <span class="float-icon" style="left:65%;animation-delay:1.5s">🦋</span>
        <span class="float-icon" style="left:85%;animation-delay:2s">🎀</span>
    </div>
    <div class="lp-header">
        <div class="lp-logo">🧸 <strong>Happy Kids</strong></div>
        <div class="lp-emoji-nav">
            <span>🏠</span>
            <span>👨‍⚕️</span>
            <span>💉</span>
            <span>📞</span>
        </div>
        <div class="lp-cta-btn">Book Visit 🎈</div>
    </div>
    <div class="lp-hero">
        <div class="lp-hero-icons">🦸‍♀️ 🦸‍♂️</div>
        <h2>Where Little Heroes Get Big Care!</h2>
        <p>Making hospital visits less scary ❤️</p>
        <div class="lp-hero-btns">
            <span class="btn-primary">🎮 Virtual Tour</span>
            <span class="btn-secondary">📅 Book Visit</span>
        </div>
    </div>
    <div class="lp-features">
        <div class="feature-pill">🎨 Colorful Rooms</div>
        <div class="feature-pill">🎮 Play Areas</div>
        <div class="feature-pill">👨‍👩‍👧 Family Suites</div>
    </div>
    <div class="lp-services">
        <div class="service-card">
            <span class="service-emoji">👶</span>
            <span class="service-name">Newborn Care</span>
        </div>
        <div class="service-card">
            <span class="service-emoji">💉</span>
            <span class="service-name">Vaccinations</span>
        </div>
        <div class="service-card">
            <span class="service-emoji">🏥</span>
            <span class="service-name">Pediatric Surgery</span>
        </div>
        <div class="service-card">
            <span class="service-emoji">🧠</span>
            <span class="service-name">Development</span>
        </div>
    </div>
    <div class="lp-stats">
        <span>🌟 100K+ Kids</span>
        <span>👨‍⚕️ 150 Specialists</span>
        <span>🏥 24/7 Peds ER</span>
    </div>
    <div class="lp-footer">
        <span>© Happy Kids Hospital 🎈 Where healing is fun!</span>
    </div>
</div>

<style>
.live-preview-pediatric {
    height: 100%;
    display: flex;
    flex-direction: column;
    font-size: 10px;
    background: var(--bg, #fff7ed);
    position: relative;
    overflow: hidden;
}
.live-preview-pediatric .lp-floating-icons {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}
.live-preview-pediatric .float-icon {
    position: absolute;
    font-size: 14px;
    animation: floatUp 4s ease-in-out infinite;
    opacity: 0.4;
}
@keyframes floatUp {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}
.live-preview-pediatric .lp-header {
    background: #fff;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    z-index: 5;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.live-preview-pediatric .lp-logo {
    color: var(--primary);
    font-size: 11px;
}
.live-preview-pediatric .lp-emoji-nav {
    display: flex;
    gap: 8px;
    font-size: 12px;
}
.live-preview-pediatric .lp-cta-btn {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: #fff;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-pediatric .lp-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    padding: 20px 15px;
    text-align: center;
    position: relative;
    z-index: 2;
}
.live-preview-pediatric .lp-hero-icons {
    font-size: 28px;
    margin-bottom: 8px;
}
.live-preview-pediatric .lp-hero h2 {
    margin: 0 0 5px 0;
    font-size: 13px;
}
.live-preview-pediatric .lp-hero p {
    margin: 0 0 10px 0;
    font-size: 9px;
    opacity: 0.9;
}
.live-preview-pediatric .lp-hero-btns {
    display: flex;
    justify-content: center;
    gap: 8px;
}
.live-preview-pediatric .btn-primary {
    background: #fff;
    color: var(--primary);
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-pediatric .btn-secondary {
    border: 1px solid #fff;
    color: #fff;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
}
.live-preview-pediatric .lp-features {
    display: flex;
    justify-content: center;
    gap: 6px;
    padding: 10px;
    background: #fff;
    z-index: 2;
    position: relative;
}
.live-preview-pediatric .feature-pill {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-pediatric .lp-services {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    padding: 12px;
    flex: 1;
    background: #fff;
    z-index: 2;
    position: relative;
}
.live-preview-pediatric .service-card {
    background: linear-gradient(135deg, var(--bg), #fff);
    border: 2px solid var(--accent);
    border-radius: 10px;
    padding: 10px;
    text-align: center;
}
.live-preview-pediatric .service-emoji {
    font-size: 18px;
    display: block;
    margin-bottom: 4px;
}
.live-preview-pediatric .service-name {
    font-size: 8px;
    color: var(--primary);
    font-weight: 600;
}
.live-preview-pediatric .lp-stats {
    display: flex;
    justify-content: space-around;
    padding: 10px;
    background: linear-gradient(135deg, var(--accent), var(--primary));
    color: #fff;
    font-size: 8px;
    font-weight: 600;
    z-index: 2;
    position: relative;
}
.live-preview-pediatric .lp-footer {
    background: #7c2d12;
    color: #fff;
    padding: 8px;
    text-align: center;
    font-size: 8px;
    z-index: 2;
    position: relative;
}
</style>
