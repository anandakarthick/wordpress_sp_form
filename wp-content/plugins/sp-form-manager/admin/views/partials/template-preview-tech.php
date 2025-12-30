<?php
/**
 * Template Preview: Diagnostic Lab - Tech/Data Design
 * Modern laboratory with test catalog and packages
 */
?>
<div class="live-preview live-preview-diagnostic">
    <div class="lp-header">
        <div class="lp-logo">🔬 <strong>PrecisionLab</strong></div>
        <div class="lp-actions">
            <span class="action-btn report">📱 Reports</span>
            <span class="action-btn book">Book Test</span>
        </div>
    </div>
    <div class="lp-hero">
        <div class="lp-lab-graphic">
            <span class="test-tube">🧪</span>
            <span class="microscope">🔬</span>
        </div>
        <h2>Accurate Results, Better Health</h2>
        <p>500+ Tests • Online Reports • Home Collection</p>
        <div class="lp-hero-btns">
            <span class="btn-primary">Book a Test</span>
            <span class="btn-secondary">Test Catalog</span>
        </div>
    </div>
    <div class="lp-features">
        <div class="feature-badge">📱 Online Reports</div>
        <div class="feature-badge">🏠 Home Collection</div>
        <div class="feature-badge">⏱️ 24h Results</div>
        <div class="feature-badge">✅ NABL Certified</div>
    </div>
    <div class="lp-categories">
        <div class="cat-item">🩸 Blood</div>
        <div class="cat-item">💉 Diabetes</div>
        <div class="cat-item">🦋 Thyroid</div>
        <div class="cat-item">❤️ Cardiac</div>
        <div class="cat-item">🫁 Liver</div>
        <div class="cat-item">🧬 Allergy</div>
    </div>
    <div class="lp-packages">
        <div class="package-title">Health Packages</div>
        <div class="packages-grid">
            <div class="package-card">
                <div class="pkg-name">Basic</div>
                <div class="pkg-price">$99</div>
                <div class="pkg-tests">40+ Tests</div>
            </div>
            <div class="package-card featured">
                <div class="pkg-badge">Popular</div>
                <div class="pkg-name">Full Body</div>
                <div class="pkg-price">$199</div>
                <div class="pkg-tests">70+ Tests</div>
            </div>
            <div class="package-card">
                <div class="pkg-name">Executive</div>
                <div class="pkg-price">$349</div>
                <div class="pkg-tests">100+ Tests</div>
            </div>
        </div>
    </div>
    <div class="lp-stats">
        <span>📊 1M+ Tests</span>
        <span>✓ 99.9% Accuracy</span>
        <span>🏢 50+ Centers</span>
    </div>
    <div class="lp-footer">
        <span>© PrecisionLab Diagnostics • NABL Accredited</span>
    </div>
</div>

<style>
.live-preview-diagnostic {
    height: 100%;
    display: flex;
    flex-direction: column;
    font-size: 10px;
    background: var(--bg, #f0fdfa);
}
.live-preview-diagnostic .lp-header {
    background: #fff;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.live-preview-diagnostic .lp-logo {
    color: var(--primary);
    font-size: 11px;
}
.live-preview-diagnostic .lp-actions {
    display: flex;
    gap: 6px;
}
.live-preview-diagnostic .action-btn {
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 8px;
}
.live-preview-diagnostic .action-btn.report {
    background: var(--bg);
    color: var(--primary);
}
.live-preview-diagnostic .action-btn.book {
    background: var(--primary);
    color: #fff;
}
.live-preview-diagnostic .lp-hero {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    padding: 18px 15px;
    text-align: center;
}
.live-preview-diagnostic .lp-lab-graphic {
    font-size: 24px;
    margin-bottom: 8px;
    display: flex;
    justify-content: center;
    gap: 10px;
}
.live-preview-diagnostic .test-tube {
    animation: shake 0.5s ease-in-out infinite;
}
@keyframes shake {
    0%, 100% { transform: rotate(-5deg); }
    50% { transform: rotate(5deg); }
}
.live-preview-diagnostic .lp-hero h2 {
    margin: 0 0 4px 0;
    font-size: 13px;
}
.live-preview-diagnostic .lp-hero p {
    margin: 0 0 10px 0;
    font-size: 8px;
    opacity: 0.9;
}
.live-preview-diagnostic .lp-hero-btns {
    display: flex;
    justify-content: center;
    gap: 8px;
}
.live-preview-diagnostic .btn-primary {
    background: #fff;
    color: var(--primary);
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-diagnostic .btn-secondary {
    border: 1px solid #fff;
    color: #fff;
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 8px;
}
.live-preview-diagnostic .lp-features {
    display: flex;
    justify-content: center;
    gap: 6px;
    padding: 10px;
    background: #fff;
    flex-wrap: wrap;
}
.live-preview-diagnostic .feature-badge {
    background: linear-gradient(135deg, var(--bg), #fff);
    border: 1px solid var(--accent);
    color: var(--primary);
    padding: 4px 8px;
    border-radius: 10px;
    font-size: 7px;
}
.live-preview-diagnostic .lp-categories {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 6px;
    padding: 10px;
    background: var(--bg);
}
.live-preview-diagnostic .cat-item {
    background: #fff;
    padding: 8px 6px;
    text-align: center;
    border-radius: 6px;
    font-size: 8px;
    color: var(--primary);
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.live-preview-diagnostic .lp-packages {
    padding: 10px;
    background: #fff;
    flex: 1;
}
.live-preview-diagnostic .package-title {
    text-align: center;
    font-size: 10px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 8px;
}
.live-preview-diagnostic .packages-grid {
    display: flex;
    gap: 6px;
}
.live-preview-diagnostic .package-card {
    flex: 1;
    background: var(--bg);
    border-radius: 8px;
    padding: 10px 6px;
    text-align: center;
    position: relative;
}
.live-preview-diagnostic .package-card.featured {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
}
.live-preview-diagnostic .pkg-badge {
    position: absolute;
    top: -6px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--accent);
    color: #fff;
    font-size: 6px;
    padding: 2px 6px;
    border-radius: 8px;
}
.live-preview-diagnostic .pkg-name {
    font-size: 9px;
    font-weight: 700;
}
.live-preview-diagnostic .pkg-price {
    font-size: 14px;
    font-weight: 800;
    margin: 4px 0;
}
.live-preview-diagnostic .pkg-tests {
    font-size: 7px;
    opacity: 0.8;
}
.live-preview-diagnostic .lp-stats {
    display: flex;
    justify-content: space-around;
    padding: 10px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    font-size: 8px;
    font-weight: 600;
}
.live-preview-diagnostic .lp-footer {
    background: #134e4a;
    color: #fff;
    padding: 8px;
    text-align: center;
    font-size: 7px;
}
</style>
