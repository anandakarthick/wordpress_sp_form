<?php
/**
 * Template Preview: Mental Health - Calming Design
 * Peaceful, supportive mental wellness layout
 */
?>
<div class="live-preview live-preview-mental">
    <div class="lp-floating-nature">
        <span class="nature-icon" style="left:10%;animation-delay:0s">🍃</span>
        <span class="nature-icon" style="left:30%;animation-delay:1s">🦋</span>
        <span class="nature-icon" style="left:50%;animation-delay:2s">🌸</span>
        <span class="nature-icon" style="left:70%;animation-delay:0.5s">🌿</span>
        <span class="nature-icon" style="left:90%;animation-delay:1.5s">🍃</span>
    </div>
    <div class="lp-crisis-banner">
        <span>💚 In crisis? You matter. Help is available 24/7</span>
        <span class="crisis-btn">Call 988</span>
    </div>
    <div class="lp-header">
        <div class="lp-logo">🧘 <strong>Serenity</strong></div>
        <div class="lp-nav">
            <span>Services</span>
            <span>Resources</span>
            <span>Team</span>
        </div>
        <div class="lp-cta-btn">Get Support</div>
    </div>
    <div class="lp-hero">
        <div class="lp-peace-icon">🌱</div>
        <h2>Your Journey to Wellness</h2>
        <p>Compassionate care in a safe, supportive space</p>
        <div class="lp-hero-btns">
            <span class="btn-primary">Start Your Journey</span>
        </div>
    </div>
    <div class="lp-services">
        <div class="service-card">
            <span class="icon">💬</span>
            <span class="name">Individual Therapy</span>
        </div>
        <div class="service-card">
            <span class="icon">👥</span>
            <span class="name">Group Sessions</span>
        </div>
        <div class="service-card">
            <span class="icon">🧘</span>
            <span class="name">Mindfulness</span>
        </div>
        <div class="service-card">
            <span class="icon">💊</span>
            <span class="name">Psychiatry</span>
        </div>
    </div>
    <div class="lp-approach">
        <div class="approach-title">Our Approach</div>
        <div class="approach-items">
            <span class="approach-tag">✓ Judgment-free</span>
            <span class="approach-tag">✓ Evidence-based</span>
            <span class="approach-tag">✓ Personalized</span>
        </div>
    </div>
    <div class="lp-quote">
        <span>"You don't have to face this alone."</span>
    </div>
    <div class="lp-footer">
        <span>© Serenity Wellness • 988 Crisis Line Available 24/7</span>
    </div>
</div>

<style>
.live-preview-mental {
    height: 100%;
    display: flex;
    flex-direction: column;
    font-size: 10px;
    background: var(--bg, #ecfdf5);
    position: relative;
    overflow: hidden;
}
.live-preview-mental .lp-floating-nature {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}
.live-preview-mental .nature-icon {
    position: absolute;
    font-size: 14px;
    animation: floatGentle 5s ease-in-out infinite;
    opacity: 0.3;
}
@keyframes floatGentle {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-15px) rotate(10deg); }
}
.live-preview-mental .lp-crisis-banner {
    background: var(--primary);
    color: #fff;
    padding: 6px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 8px;
    z-index: 5;
    position: relative;
}
.live-preview-mental .crisis-btn {
    background: #fff;
    color: var(--primary);
    padding: 3px 10px;
    border-radius: 10px;
    font-weight: 600;
}
.live-preview-mental .lp-header {
    background: rgba(255,255,255,0.95);
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 5;
    position: relative;
}
.live-preview-mental .lp-logo {
    color: var(--primary);
    font-size: 11px;
}
.live-preview-mental .lp-nav {
    display: flex;
    gap: 10px;
    color: #64748b;
    font-size: 8px;
}
.live-preview-mental .lp-cta-btn {
    background: var(--primary);
    color: #fff;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
}
.live-preview-mental .lp-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    padding: 25px 15px;
    text-align: center;
    z-index: 2;
    position: relative;
}
.live-preview-mental .lp-peace-icon {
    font-size: 30px;
    margin-bottom: 8px;
    animation: grow 3s ease-in-out infinite;
}
@keyframes grow {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}
.live-preview-mental .lp-hero h2 {
    margin: 0 0 5px 0;
    font-size: 14px;
}
.live-preview-mental .lp-hero p {
    margin: 0 0 12px 0;
    font-size: 9px;
    opacity: 0.9;
}
.live-preview-mental .btn-primary {
    background: #fff;
    color: var(--primary);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 600;
}
.live-preview-mental .lp-services {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    padding: 12px;
    background: #fff;
    flex: 1;
    z-index: 2;
    position: relative;
}
.live-preview-mental .service-card {
    background: var(--bg);
    border-radius: 10px;
    padding: 12px 8px;
    text-align: center;
}
.live-preview-mental .service-card .icon {
    display: block;
    font-size: 18px;
    margin-bottom: 4px;
}
.live-preview-mental .service-card .name {
    font-size: 8px;
    color: var(--primary);
    font-weight: 600;
}
.live-preview-mental .lp-approach {
    padding: 10px;
    background: #fff;
    text-align: center;
    z-index: 2;
    position: relative;
}
.live-preview-mental .approach-title {
    font-size: 10px;
    color: var(--primary);
    font-weight: 700;
    margin-bottom: 8px;
}
.live-preview-mental .approach-items {
    display: flex;
    justify-content: center;
    gap: 8px;
}
.live-preview-mental .approach-tag {
    background: var(--bg);
    color: var(--secondary);
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 7px;
}
.live-preview-mental .lp-quote {
    background: var(--primary);
    color: #fff;
    padding: 12px;
    text-align: center;
    font-style: italic;
    font-size: 9px;
    z-index: 2;
    position: relative;
}
.live-preview-mental .lp-footer {
    background: #064e3b;
    color: #fff;
    padding: 8px;
    text-align: center;
    font-size: 7px;
    z-index: 2;
    position: relative;
}
</style>
