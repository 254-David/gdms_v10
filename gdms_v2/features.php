<?php
$pageTitle = 'Features — Githunguri Dairy Management System';
$extraStyles = '<style>
.features-section{padding:80px 0;background:var(--page-bg);}
.features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}
.feature-card{background:#ffffff;border:1px solid #dde5ed;border-radius:16px;padding:28px;transition:all .3s;position:relative;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.05);}
.feature-card::before{content:"";position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#00875a,#006644,#f59e0b);transform:scaleX(0);transition:transform .3s;transform-origin:left;}
.feature-card:hover{transform:translateY(-6px);box-shadow:0 0 30px rgba(46,204,113,0.15);border-color:rgba(46,204,113,0.3);}
.feature-card:hover::before{transform:scaleX(1);}
.feature-icon{width:56px;height:56px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:26px;margin-bottom:20px;}
.feature-card h3{font-family:"Playfair Display",serif;font-size:18px;font-weight:700;margin-bottom:12px;color:#0d1f2d;}
.feature-card p{font-size:14px;color:var(--text-muted);line-height:1.7;}
.feature-tags{display:flex;flex-wrap:wrap;gap:8px;margin-top:20px;}
.feature-tag{background:#d1fae5;color:#065f46;font-size:12px;font-weight:600;padding:4px 12px;border-radius:50px;border:1px solid #a7f3d0;}
.ai-section{background:linear-gradient(135deg,#0d1f2d 0%,#0a3020 100%);padding:90px 0;border-top:none;border-bottom:none;}
.ai-grid{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;}
.ai-features{display:flex;flex-direction:column;gap:16px;margin-top:32px;}
.ai-feature{display:flex;align-items:flex-start;gap:16px;padding:20px;background:rgba(255,255,255,0.06);border-radius:14px;border:1px solid rgba(255,255,255,0.10);transition:all .3s;}
.ai-feature:hover{background:rgba(255,255,255,0.10);transform:translateX(4px);border-color:rgba(0,180,120,0.4);}
.ai-feature-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;background:rgba(251,191,36,0.18);color:#fbbf24;}
.ai-feature h5{font-size:14px;font-weight:700;margin-bottom:4px;color:#ffffff;}
.ai-feature p{font-size:13px;color:#94a3b8;margin:0;line-height:1.65;}
.ai-visual{background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:20px;padding:28px;}
.ai-header{display:flex;align-items:center;gap:10px;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid rgba(255,255,255,0.08);}
.ai-dot{width:12px;height:12px;border-radius:50%;}
.dot-red{background:#ff5f57;}.dot-yellow{background:#ffbd2e;}.dot-green{background:#28ca41;}
.ai-header span{margin-left:auto;font-size:12px;color:#94a3b8;}
.ai-metric{display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-radius:10px;margin-bottom:10px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);}
.ai-metric .name{font-size:13px;color:#cbd5e1;font-weight:500;}.ai-metric .val{font-size:15px;font-weight:700;}
.ai-metric .status{font-size:11px;padding:3px 10px;border-radius:50px;font-weight:700;}
.status-ok{background:rgba(40,202,65,0.2);color:#4ade80;}.status-warn{background:rgba(255,189,46,0.2);color:#fbbf24;}
.ai-conclusion{margin-top:20px;padding:16px;background:rgba(251,191,36,0.12);border-radius:12px;border-left:4px solid #fbbf24;}
.ai-conclusion p{font-size:13px;color:#fde68a;margin:0;line-height:1.7;}
.ai-conclusion strong{color:#fbbf24;}
@media(max-width:1024px){.features-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:768px){.features-grid{grid-template-columns:1fr;}.ai-grid{grid-template-columns:1fr;gap:40px;}}
</style>';
include 'includes/nav.php';
?>

<div class="page-hero">
    <div class="section-container">
        <div class="section-label" style="justify-content:center;"><span>What We Offer</span></div>
        <h1 class="section-title fade-up">Everything Your Cooperative Needs</h1>
        <p class="fade-up" style="transition-delay:.1s;">A fully integrated platform designed to streamline dairy operations from farm to payment.</p>
    </div>
</div>

<section class="features-section">
    <div class="section-container">
        <div class="features-grid">
            <div class="feature-card fade-up" style="transition-delay:.08s">
                <div class="feature-icon" style="background:rgba(46,204,113,0.12);color:var(--primary);"><i class="fas fa-users"></i></div>
                <h3>Farmer Management</h3>
                <p>Register and manage farmers with complete profiles including cow breeds, bank details, farm size, and full performance history.</p>
                <div class="feature-tags"><span class="feature-tag">Registration</span><span class="feature-tag">Profiles</span><span class="feature-tag">History</span></div>
            </div>
            <div class="feature-card fade-up" style="transition-delay:.13s">
                <div class="feature-icon" style="background:rgba(240,165,0,0.12);color:var(--accent);"><i class="fas fa-flask"></i></div>
                <h3>Milk Quality Monitor</h3>
                <p>Real-time quality testing with fat content, protein, pH, temperature, SNF, density, and antibiotic checks against predefined standards.</p>
                <div class="feature-tags"><span class="feature-tag">Testing</span><span class="feature-tag">Standards</span><span class="feature-tag">Grading</span></div>
            </div>
            <div class="feature-card fade-up" style="transition-delay:.18s">
                <div class="feature-icon" style="background:rgba(59,130,246,0.12);color:#93c5fd;"><i class="fas fa-robot"></i></div>
                <h3>AI Quality Advisor</h3>
                <p>Powered by AI — get real-time intelligent insights on milk status, spoilage risk predictions, and quality improvement recommendations.</p>
                <div class="feature-tags"><span class="feature-tag">AI Powered</span><span class="feature-tag">Real-time</span><span class="feature-tag">Predictions</span></div>
            </div>
            <div class="feature-card fade-up" style="transition-delay:.23s">
                <div class="feature-icon" style="background:rgba(167,139,250,0.12);color:#a78bfa;"><i class="fas fa-truck"></i></div>
                <h3>Delivery Tracking</h3>
                <p>Record daily morning and evening deliveries with complete traceability from farmer to storage tank with timestamped logs.</p>
                <div class="feature-tags"><span class="feature-tag">Delivery Logs</span><span class="feature-tag">Sessions</span><span class="feature-tag">Tracking</span></div>
            </div>
            <div class="feature-card fade-up" style="transition-delay:.28s">
                <div class="feature-icon" style="background:rgba(46,204,113,0.12);color:var(--primary);"><i class="fas fa-money-check-alt"></i></div>
                <h3>Payment Processing</h3>
                <p>Automated payment calculations based on quantity and quality grades with bonuses, deductions, and support for M-Pesa, bank transfer, and cash.</p>
                <div class="feature-tags"><span class="feature-tag">Auto-Calculate</span><span class="feature-tag">M-Pesa</span><span class="feature-tag">Bank</span></div>
            </div>
            <div class="feature-card fade-up" style="transition-delay:.33s">
                <div class="feature-icon" style="background:rgba(251,113,133,0.12);color:#fb7185;"><i class="fas fa-chart-bar"></i></div>
                <h3>Reports &amp; Analytics</h3>
                <p>Generate daily, weekly, monthly and annual reports on collections, quality trends, payments, and farmer performance with export options.</p>
                <div class="feature-tags"><span class="feature-tag">Analytics</span><span class="feature-tag">Export PDF</span><span class="feature-tag">Charts</span></div>
            </div>
            <div class="feature-card fade-up" style="transition-delay:.38s">
                <div class="feature-icon" style="background:rgba(240,165,0,0.12);color:var(--accent);"><i class="fas fa-database"></i></div>
                <h3>Storage Tank Monitor</h3>
                <p>Track milk volume, temperature, and fill levels across all storage tanks in real time, with automatic alerts for high-risk conditions.</p>
                <div class="feature-tags"><span class="feature-tag">Tanks</span><span class="feature-tag">Temperature</span><span class="feature-tag">Alerts</span></div>
            </div>
            <div class="feature-card fade-up" style="transition-delay:.43s">
                <div class="feature-icon" style="background:rgba(59,130,246,0.12);color:#93c5fd;"><i class="fas fa-bullhorn"></i></div>
                <h3>Announcements</h3>
                <p>Post and manage cooperative announcements targeted to all farmers, all staff, or specific groups. Farmers receive updates instantly in their portal.</p>
                <div class="feature-tags"><span class="feature-tag">Notices</span><span class="feature-tag">Targeted</span><span class="feature-tag">Instant</span></div>
            </div>
            <div class="feature-card fade-up" style="transition-delay:.48s">
                <div class="feature-icon" style="background:rgba(167,139,250,0.12);color:#a78bfa;"><i class="fas fa-user-cog"></i></div>
                <h3>Staff Management</h3>
                <p>Add and manage cooperative staff accounts with role-based access control. Full activity logging for accountability and audit trails.</p>
                <div class="feature-tags"><span class="feature-tag">Roles</span><span class="feature-tag">Access</span><span class="feature-tag">Audit Logs</span></div>
            </div>
        </div>
    </div>
</section>

<section class="ai-section">
    <div class="section-container">
        <div class="ai-grid">
            <div class="fade-up">
                <div class="section-label"><span>AI Powered</span></div>
                <h2 style="font-family:'Playfair Display',serif;font-size:38px;font-weight:700;color:#ffffff;line-height:1.15;margin-bottom:16px;">Intelligent Milk Quality Advisor</h2>
                <p style="font-size:15px;color:#94a3b8;line-height:1.8;margin-bottom:0;max-width:460px;">Our AI integration provides real-time intelligent analysis of every milk batch, detecting risks before they become losses.</p>
                <div class="ai-features">
                    <div class="ai-feature"><div class="ai-feature-icon"><i class="fas fa-thermometer"></i></div><div><h5>Spoilage Risk Detection</h5><p>Analyses temperature, storage time, and quality parameters to flag at-risk batches early.</p></div></div>
                    <div class="ai-feature"><div class="ai-feature-icon"><i class="fas fa-chart-bar"></i></div><div><h5>Quality Trend Analysis</h5><p>Tracks quality patterns per farmer over time and identifies seasonal or feed-related trends.</p></div></div>
                    <div class="ai-feature"><div class="ai-feature-icon"><i class="fas fa-lightbulb"></i></div><div><h5>Actionable Recommendations</h5><p>Provides specific guidance to improve milk quality, storage conditions, and handling practices.</p></div></div>
                </div>
            </div>
            <div class="ai-visual fade-up" style="transition-delay:.2s;">
                <div class="ai-header">
                    <div class="ai-dot dot-red"></div><div class="ai-dot dot-yellow"></div><div class="ai-dot dot-green"></div>
                    <span>AI Quality Analysis — Sample Report</span>
                </div>
                <div class="ai-metric"><span class="name">🌡️ Temperature</span><span class="val" style="color:#28ca41;">4.2°C</span><span class="status status-ok">Excellent</span></div>
                <div class="ai-metric"><span class="name">🥛 Fat Content</span><span class="val" style="color:#28ca41;">3.8%</span><span class="status status-ok">Grade A</span></div>
                <div class="ai-metric"><span class="name">🧪 Protein</span><span class="val" style="color:#28ca41;">3.2%</span><span class="status status-ok">Optimal</span></div>
                <div class="ai-metric"><span class="name">⚗️ pH Level</span><span class="val" style="color:#ffbd2e;">6.58</span><span class="status status-warn">Monitor</span></div>
                <div class="ai-metric"><span class="name">⏱️ Storage Time</span><span class="val" style="color:#28ca41;">2.5 hrs</span><span class="status status-ok">Safe</span></div>
                <div class="ai-metric"><span class="name">💧 Water Content</span><span class="val" style="color:#28ca41;">0%</span><span class="status status-ok">None</span></div>
                <div class="ai-conclusion"><p>🤖 <strong>AI Verdict:</strong> Milk batch is of excellent quality. Grade A certified. pH slightly below optimal — recommend monitoring cow feed. No spoilage risk. Estimated safe storage: 18–22 hours.</p></div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
