<?php
/**
 * Template Preview: Orthopedic - Motion/Active Design
 * Dynamic sports medicine and bone care layout
 */
?>
<div class="live-preview live-preview-orthopedic">
    <div class="lp-header">
        <div class="lp-logo">🦴 <strong>SpineFirst</strong></div>
        <div class="lp-nav">
            <span>Services</span>
            <span>Conditions</span>
            <span>Surgeons</span>
        </div>
        <div class="lp-cta-btn">Book Consult</div>
    </div>
    <div class="lp-hero">
        <div class="lp-motion-graphic">
            <span class="runner">🏃</span>
            <span class="motion-trail">→→→</span>
        </div>
        <h2>Get Moving Again</h2>
        <p>Expert bone, joint & spine care</p>
        <div class="lp-hero-btns">
            <span class="btn-primary">Schedule Consultation</span>
            <span class="btn-secondary">Our Treatments</span>
        </div>
    </div>
    <div class="lp-specialties">
        <div class="specialty-card featured">
            <div class="specialty-icon">🦴</div>
            <div class="specialty-name">Joint Replacement</div>
            <div class="specialty-desc">Hip • Knee • Shoulder</div>
        </div>
        <div class="specialty-card">
            <div class="specialty-icon">🏃</div>
            <div class="specialty-name">Sports Medicine</div>
            <div class="specialty-desc">ACL • Rotator Cuff</div>
        </div>
        <div class="specialty-card">
            <div class="specialty-icon">🧠</div>
            <div class="specialty-name">Spine Surgery</div>
            <div class="specialty-desc">Minimally Invasive</div>
        </div>
    </div>
    <div class="lp-procedures">
        <span class="proc-tag">Hip Replacement</span>
        <span class="proc-tag">Knee Surgery</span>
        <span class="proc-tag">ACL Repair</span>
        <span class="proc-tag">Disc Treatment</span>
    </div>
    <div class="lp-rehab-cta">
        <span class="rehab-icon">💪</span>
        <span class="rehab-text">Full Rehabilitation Programs</span>
        <span class="rehab-link">Learn More →</span>
    </div>
    <div class="lp-stats">
        <div class="stat"><strong>25K+</strong><br>Surgeries</div>
        <div class="stat"><strong>99%</strong><br>Success</div>
        <div class="stat"><strong>20</strong><br>Surgeons</div>
    </div>
    <div class="lp-footer">
        <span>© SpineFirst Orthopedics • Move Better, Live Better</span>
    </div>
</div>

<style>
.live-preview-orthopedic {
    height: 100%;
    display: flex;
    flex-direction: column;
    font-size: 10px;
    background: var(--bg, #eff6ff);
}
.live-preview-orthopedic .lp-header {
    background: #fff;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.live-preview-orthopedic .lp-logo {
    color: var(--primary);
    font-size: 11px;
}
.live-preview-orthopedic .lp-nav {
    display: flex;
    gap: 10px;
    color: #64748b;
    font-size: 8px;
}
.live-preview-orthopedic .lp-cta-btn {
    background: var(--primary);
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 8px;
}
.live-preview-orthopedic .lp-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    padding: 20px 15px;
    text-align: center;
}
.live-preview-orthopedic .lp-motion-graphic {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin-bottom: 10px;
}
.live-preview-orthopedic .runner {
    font-size: 28px;
    animation: run 0.5s steps(2) infinite;
}
@keyframes run {
    0% { transform: translateX(0); }
    100% { transform: translateX(5px); }
}
.live-preview-orthopedic .motion-trail {
    font-size: 14px;
    opacity: 0.6;
    animation: trail 1s ease-in-out infinite;
}
@keyframes trail {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.8; }
}
.live-preview-orthopedic .lp-hero h2 {
    margin: 0 0 5px 0;
    font-size: 15px;
    font-weight: 800;
}
.live-preview-orthopedic .lp-hero p {
    margin: 0 0 10px 0;
    font-size: 9px;
    opacity: 0.9;
}
.live-preview-orthopedic .lp-hero-btns {
    display: flex;
    justify-content: center;
    gap: 8px;
}
.live-preview-orthopedic .btn-primary {
    background: #fff;
    color: var(--primary);
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-orthopedic .btn-secondary {
    border: 1px solid #fff;
    color: #fff;
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 8px;
}
.live-preview-orthopedic .lp-specialties {
    display: flex;
    gap: 8px;
    padding: 12px;
    background: #fff;
}
.live-preview-orthopedic .specialty-card {
    flex: 1;
    background: var(--bg);
    border-radius: 8px;
    padding: 10px 6px;
    text-align: center;
    border: 2px solid transparent;
}
.live-preview-orthopedic .specialty-card.featured {
    border-color: var(--primary);
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
}
.live-preview-orthopedic .specialty-icon {
    font-size: 18px;
    margin-bottom: 4px;
}
.live-preview-orthopedic .specialty-name {
    font-size: 8px;
    font-weight: 700;
    color: var(--primary);
}
.live-preview-orthopedic .specialty-desc {
    font-size: 6px;
    color: #64748b;
    margin-top: 2px;
}
.live-preview-orthopedic .lp-procedures {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 5px;
    padding: 10px;
    background: var(--bg);
}
.live-preview-orthopedic .proc-tag {
    background: #fff;
    border: 1px solid var(--accent);
    color: var(--primary);
    padding: 4px 8px;
    border-radius: 10px;
    font-size: 7px;
}
.live-preview-orthopedic .lp-rehab-cta {
    background: linear-gradient(90deg, var(--accent), var(--primary));
    color: #fff;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 9px;
}
.live-preview-orthopedic .rehab-icon {
    font-size: 18px;
}
.live-preview-orthopedic .rehab-text {
    flex: 1;
    font-weight: 600;
}
.live-preview-orthopedic .rehab-link {
    background: rgba(255,255,255,0.2);
    padding: 3px 10px;
    border-radius: 10px;
    font-size: 8px;
}
.live-preview-orthopedic .lp-stats {
    display: flex;
    justify-content: space-around;
    padding: 12px;
    background: var(--primary);
    color: #fff;
    flex: 1;
    align-items: center;
}
.live-preview-orthopedic .stat {
    text-align: center;
    font-size: 8px;
}
.live-preview-orthopedic .stat strong {
    font-size: 14px;
}
.live-preview-orthopedic .lp-footer {
    background: #1e3a8a;
    color: #fff;
    padding: 8px;
    text-align: center;
    font-size: 7px;
}
</style>
