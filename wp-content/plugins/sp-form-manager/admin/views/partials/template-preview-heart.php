<?php
/**
 * Template Preview: Cardiology - Heart Focused
 * Professional cardiac center with emergency emphasis
 */
?>
<div class="live-preview live-preview-cardiology">
    <div class="lp-emergency-alert">
        <span class="alert-icon">⚠️</span>
        <span class="alert-text">Heart Attack Signs: Chest pain → Call 911</span>
    </div>
    <div class="lp-header">
        <div class="lp-logo">❤️ <strong>HeartCare</strong></div>
        <div class="lp-nav">
            <span>Services</span>
            <span>Conditions</span>
            <span>Doctors</span>
        </div>
        <div class="lp-emergency-btn">🚨 Emergency</div>
    </div>
    <div class="lp-hero">
        <div class="lp-heartbeat">
            <div class="heart-icon">❤️</div>
            <div class="pulse-line">
                <svg viewBox="0 0 100 30" class="ecg-line">
                    <path d="M0,15 L20,15 L25,5 L30,25 L35,10 L40,20 L45,15 L100,15" 
                          stroke="white" stroke-width="2" fill="none"/>
                </svg>
            </div>
        </div>
        <h2>Your Heart in Expert Hands</h2>
        <p>Leading cardiac care from prevention to intervention</p>
        <div class="lp-hero-btns">
            <span class="btn-primary">Heart Screening</span>
            <span class="btn-secondary">Our Expertise</span>
        </div>
    </div>
    <div class="lp-services">
        <div class="service-item">
            <span class="icon">🩺</span>
            <span class="name">Diagnostics</span>
        </div>
        <div class="service-item">
            <span class="icon">💓</span>
            <span class="name">Cath Lab</span>
        </div>
        <div class="service-item">
            <span class="icon">🫀</span>
            <span class="name">Surgery</span>
        </div>
        <div class="service-item">
            <span class="icon">⚡</span>
            <span class="name">Pacemakers</span>
        </div>
    </div>
    <div class="lp-conditions">
        <div class="condition-tag">Heart Attack</div>
        <div class="condition-tag">Arrhythmia</div>
        <div class="condition-tag">Heart Failure</div>
        <div class="condition-tag">Coronary Disease</div>
    </div>
    <div class="lp-stats">
        <div class="stat"><strong>50K+</strong><br>Procedures</div>
        <div class="stat"><strong>98%</strong><br>Success</div>
        <div class="stat"><strong>25</strong><br>Cardiologists</div>
    </div>
    <div class="lp-footer">
        <span>© HeartCare Center • 24/7 Cardiac Emergency</span>
    </div>
</div>

<style>
.live-preview-cardiology {
    height: 100%;
    display: flex;
    flex-direction: column;
    font-size: 10px;
    background: var(--bg, #fef2f2);
}
.live-preview-cardiology .lp-emergency-alert {
    background: #dc2626;
    color: #fff;
    padding: 5px 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 8px;
    animation: alertPulse 2s infinite;
}
@keyframes alertPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
.live-preview-cardiology .lp-header {
    background: #fff;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.live-preview-cardiology .lp-logo {
    color: var(--primary);
    font-size: 11px;
}
.live-preview-cardiology .lp-nav {
    display: flex;
    gap: 10px;
    color: #64748b;
    font-size: 8px;
}
.live-preview-cardiology .lp-emergency-btn {
    background: #dc2626;
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 8px;
    font-weight: 600;
    animation: pulse 1.5s infinite;
}
.live-preview-cardiology .lp-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    padding: 20px 15px;
    text-align: center;
}
.live-preview-cardiology .lp-heartbeat {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 10px;
}
.live-preview-cardiology .heart-icon {
    font-size: 30px;
    animation: heartbeat 1s ease-in-out infinite;
}
@keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    25% { transform: scale(1.1); }
    50% { transform: scale(1); }
    75% { transform: scale(1.05); }
}
.live-preview-cardiology .pulse-line {
    width: 80px;
    height: 25px;
}
.live-preview-cardiology .ecg-line {
    width: 100%;
    height: 100%;
}
.live-preview-cardiology .ecg-line path {
    stroke-dasharray: 200;
    animation: ecgDraw 2s linear infinite;
}
@keyframes ecgDraw {
    to { stroke-dashoffset: -200; }
}
.live-preview-cardiology .lp-hero h2 {
    margin: 0 0 5px 0;
    font-size: 14px;
}
.live-preview-cardiology .lp-hero p {
    margin: 0 0 10px 0;
    font-size: 9px;
    opacity: 0.9;
}
.live-preview-cardiology .lp-hero-btns {
    display: flex;
    justify-content: center;
    gap: 8px;
}
.live-preview-cardiology .btn-primary {
    background: #fff;
    color: var(--primary);
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-cardiology .btn-secondary {
    border: 1px solid #fff;
    color: #fff;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 8px;
}
.live-preview-cardiology .lp-services {
    display: flex;
    justify-content: space-around;
    padding: 12px;
    background: #fff;
}
.live-preview-cardiology .service-item {
    text-align: center;
}
.live-preview-cardiology .service-item .icon {
    display: block;
    font-size: 18px;
    margin-bottom: 3px;
}
.live-preview-cardiology .service-item .name {
    font-size: 8px;
    color: var(--primary);
    font-weight: 600;
}
.live-preview-cardiology .lp-conditions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 5px;
    padding: 10px;
    background: var(--bg);
    flex: 1;
}
.live-preview-cardiology .condition-tag {
    background: #fff;
    border: 1px solid var(--primary);
    color: var(--primary);
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 8px;
}
.live-preview-cardiology .lp-stats {
    display: flex;
    justify-content: space-around;
    padding: 12px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
}
.live-preview-cardiology .stat {
    text-align: center;
    font-size: 8px;
}
.live-preview-cardiology .stat strong {
    font-size: 14px;
}
.live-preview-cardiology .lp-footer {
    background: #450a0a;
    color: #fff;
    padding: 8px;
    text-align: center;
    font-size: 8px;
}
</style>
