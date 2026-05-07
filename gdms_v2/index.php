<?php
$pageTitle = 'Githunguri Dairy Management System';
$extraStyles = '<style>
/* ── HERO ── */
.hero{padding:130px 0 90px;background:#ffffff;position:relative;overflow:hidden;}
.hero::before{content:"";position:absolute;top:0;right:0;width:55%;height:100%;background:linear-gradient(145deg,#00875a 0%,#005c3d 60%,#004d33 100%);clip-path:polygon(8% 0,100% 0,100% 100%,0% 100%);z-index:0;}
.hero::after{content:"";position:absolute;top:10%;right:5%;width:420px;height:420px;background:radial-gradient(circle,rgba(255,255,255,0.08) 0%,transparent 70%);border-radius:50%;pointer-events:none;z-index:1;}

/* dot pattern overlay on green panel */
.hero-dots{position:absolute;top:0;right:0;width:55%;height:100%;z-index:1;background-image:radial-gradient(rgba(255,255,255,0.07) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;}

.hero-container{max-width:1240px;margin:0 auto;padding:0 40px;display:grid;grid-template-columns:1fr 1fr;gap:70px;align-items:center;position:relative;z-index:2;}

/* ── BADGE ── */
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#fffbeb,#fef9c3);border:1px solid #fde68a;color:#92400e;padding:8px 16px;border-radius:50px;font-size:12px;font-weight:700;margin-bottom:24px;box-shadow:0 2px 8px rgba(245,158,11,0.15);}
.hero-badge i{color:#f59e0b;}

/* ── HEADING ── */
.hero h1{font-family:"Playfair Display",serif;font-size:54px;font-weight:700;color:#0a1628;line-height:1.08;margin-bottom:22px;}
.hero h1 span{color:#00a86b;position:relative;}
.hero h1 span::after{content:"";position:absolute;left:0;bottom:-4px;width:100%;height:3px;background:linear-gradient(90deg,#00a86b,#00d68f);border-radius:2px;}

.hero-desc{font-size:16.5px;color:#4a6070;line-height:1.8;margin-bottom:28px;max-width:460px;}

/* ── BUTTONS ── */
.hero-actions{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:48px;}
.btn-hero-primary{padding:14px 28px;background:linear-gradient(135deg,#00875a,#006644);color:white;border-radius:12px;font-size:15px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:9px;transition:all .3s;box-shadow:0 6px 22px rgba(0,135,90,0.35);}
.btn-hero-primary:hover{background:linear-gradient(135deg,#009a68,#007a52);transform:translateY(-2px);box-shadow:0 10px 30px rgba(0,135,90,0.4);}
.btn-hero-secondary{padding:14px 28px;border:2px solid #00875a;color:#00875a;background:rgba(0,135,90,0.06);border-radius:12px;font-size:15px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:9px;transition:all .3s;}
.btn-hero-secondary:hover{background:#00875a;color:white;transform:translateY(-2px);box-shadow:0 6px 22px rgba(0,135,90,0.3);}

/* ── BOTTOM STATS ── */
.hero-stats{display:flex;gap:36px;padding-top:32px;border-top:1px solid #175a39ff;}
.hero-stat h3{font-family:"Playfair Display",serif;font-size:30px;font-weight:700;color:#00875a;line-height:1;}
.hero-stat p{font-size:12px;color:#8fa4b0;margin-top:3px;}

/* ── HERO CARDS ── */
.hero-visual{display:flex;flex-direction:column;gap:14px;position:relative;z-index:2;}
.h-card{background:white;border:1px solid rgba(255,255,255,0.9);border-radius:18px;padding:20px 22px;box-shadow:0 8px 32px rgba(0,0,0,0.13);transition:all .3s;}
.h-card:hover{box-shadow:0 16px 48px rgba(0,0,0,0.18);transform:translateY(-3px);}
.h-card-hdr{display:flex;align-items:center;gap:12px;margin-bottom:16px;}
.h-card-ico{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;}
.ico-green{background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#065f46;}
.ico-amber{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#92400e;}
.ico-blue{background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#1e40af;}
.h-card h4{font-size:14px;font-weight:700;color:#0f2137;}
.h-card-sub{font-size:11px;color:#8fa4b0;margin-top:2px;font-weight:500;}
.hero-desc{font-size:17px;color:#4a6070;line-height:1.5;margin-bottom:28px;max-width:460px;}
/* ── STAT BOXES inside cards ── */
.h-card-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;}
.h-data{background:linear-gradient(135deg,#f0fdf8,#e6ffef);border:1px solid #075c25ff;border-radius:11px;padding:12px 8px;text-align:center;transition:all .3s;}
.h-data:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,135,90,0.12);}
.h-data .val{font-family:"Playfair Display",serif;font-size:20px;font-weight:700;color:#065f46;line-height:1.1;}
.h-data .lbl{font-size:9.5px;color:#6b7280;margin-top:4px;text-transform:uppercase;letter-spacing:.8px;font-weight:600;}

/* Grade A special color */
.val-grade{color:#1d4ed8 !important;}

/* ── AI LIST ── */
.h-list{font-size:13px;color:#374151;line-height:2;background:#f9fafb;border-radius:10px;padding:10px 14px;}
.h-list .ok{color:#059652;font-weight:700;margin-right:5px;}
.h-list .warn{color:#d97706;font-weight:700;margin-right:5px;}

/* ── TRUST BAR ── */
.trust-bar{background:linear-gradient(135deg,#f0fdf8,#f7fafc);padding:28px 0;border-top:1px solid #d1fae5;border-bottom:1px solid #d1fae5;}
.trust-inner{max-width:1240px;margin:0 auto;padding:0 40px;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;}
.trust-item{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600;color:#374151;}
.trust-item i{font-size:18px;color:#00875a;}

/* ── FEATURES ── */
.features-sec{padding:90px 0;background:#ffffff;}
.feat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}
.feat-card{background:#f7fafc;border:1px solid #e2ede7ff;border-radius:18px;padding:32px 26px;transition:all .3s;position:relative;overflow:hidden;}
.feat-card::before{content:"";position:absolute;bottom:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#00875a,#00d68f);transform:scaleX(0);transform-origin:left;transition:transform .3s;}
.feat-card:hover{background:white;box-shadow:0 12px 40px rgba(0,135,90,0.12);border-color:#bbf7d0;transform:translateY(-5px);}
.feat-card:hover::before{transform:scaleX(1);}
.feat-ico{width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);display:flex;align-items:center;justify-content:center;font-size:24px;color:#065f46;margin-bottom:20px;box-shadow:0 4px 14px rgba(0,135,90,0.15);}
.feat-card h4{font-size:16px;font-weight:700;color:#0f2137;margin-bottom:10px;}
.feat-card p{font-size:13px;color:#4a6070;line-height:1.75;}

/* ── HOW IT WORKS ── */
.how-sec{padding:90px 0;background:linear-gradient(180deg,#f0fdf8 0%,#eef6f2 100%);}
.steps-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;position:relative;}
.steps-grid::before{content:"";position:absolute;top:44px;left:13%;right:13%;height:2px;background:linear-gradient(to right,#00875a,#f59e0b);}
.step-card{text-align:center;position:relative;}
.step-num{width:88px;height:88px;border-radius:50%;background:linear-gradient(135deg,#00875a,#006644);border:3px solid white;display:flex;align-items:center;justify-content:center;font-family:"Playfair Display",serif;font-size:28px;font-weight:700;color:white;margin:0 auto 22px;box-shadow:0 8px 24px rgba(0,135,90,0.3);position:relative;z-index:1;}
.step-card h4{font-size:15px;font-weight:700;margin-bottom:9px;color:#0f2137;}
.step-card p{font-size:13px;color:#4a6070;line-height:1.65;}

/* ── STATS BAND ── */
.stats-band{padding:70px 0;background:linear-gradient(135deg,#0a1628 0%,#0f2d1e 50%,#0a1628 100%);}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0;}
.stat-box{text-align:center;padding:24px 20px;border-right:1px solid rgba(255,255,255,0.08);}
.stat-box:last-child{border-right:none;}
.stat-box h3{font-family:"Playfair Display",serif;font-size:48px;font-weight:700;color:#4ade80;line-height:1;}
.stat-box p{font-size:13px;color:rgba(255,255,255,0.6);margin-top:8px;font-weight:500;}

/* ── CTA BAND ── */
.cta-band{padding:90px 0;background:linear-gradient(135deg,#00875a 0%,#005c3d 100%);position:relative;overflow:hidden;}
.cta-band::before{content:"";position:absolute;top:-40%;left:50%;transform:translateX(-50%);width:700px;height:700px;background:radial-gradient(circle,rgba(255,255,255,0.06) 0%,transparent 65%);border-radius:50%;}
.cta-inner{max-width:700px;margin:0 auto;text-align:center;padding:0 40px;position:relative;z-index:1;}
.cta-inner h2{font-family:"Playfair Display",serif;font-size:40px;color:white;margin-bottom:16px;}
.cta-inner p{font-size:16px;color:rgba(255,255,255,0.82);line-height:1.75;margin-bottom:36px;}
.cta-btns{display:flex;gap:14px;justify-content:center;flex-wrap:wrap;}
.btn-cta-w{padding:15px 32px;background:white;color:#00875a;border-radius:12px;font-size:15px;font-weight:700;text-decoration:none;transition:all .3s;box-shadow:0 6px 20px rgba(0,0,0,0.15);display:inline-flex;align-items:center;gap:8px;}
.btn-cta-w:hover{background:#f0fdf4;transform:translateY(-2px);box-shadow:0 10px 28px rgba(0,0,0,0.2);}
.btn-cta-o{padding:15px 32px;border:2px solid rgba(255,255,255,0.6);color:white;border-radius:12px;font-size:15px;font-weight:700;text-decoration:none;transition:all .3s;display:inline-flex;align-items:center;gap:8px;}
.btn-cta-o:hover{background:rgba(255,255,255,0.12);border-color:white;transform:translateY(-2px);}

@media(max-width:768px){
  .hero::before,.hero-dots{display:none;}
  .hero-container{grid-template-columns:1fr;gap:40px;padding-top:20px;}
  .hero h1{font-size:36px;}
  .feat-grid{grid-template-columns:1fr;}
  .steps-grid{grid-template-columns:1fr 1fr;}
  .steps-grid::before{display:none;}
  .stats-grid{grid-template-columns:1fr 1fr;}
  .stat-box{border-right:none;border-bottom:1px solid rgba(255,255,255,0.08);}
  .hero-visual{display:none;}
}
</style>';
include 'includes/nav.php';
?>

<!-- ── HERO ── -->
<section class="hero" id="home">
  <div class="hero-dots"></div>
  <div class="hero-container">

    <!-- LEFT CONTENT -->
    <div class="hero-content fade-up">
      <div class="hero-badge"><i class="fas fa-star"></i> Kiambu County's Leading Dairy Platform</div>
      <h1>Smart <span>Dairy</span><br>Management for<br>Cooperatives</h1>
      <p class="hero-desc">A comprehensive digital platform for Githunguri Dairy Farmers Cooperative Society — manage deliveries, monitor quality, track payments and gain AI-powered insights.</p>
      <div class="hero-actions">
        <a href="farmer/register.php" class="btn-hero-primary"><i class="fas fa-user-plus"></i> Register as Farmer</a>
        <a href="features.php" class="btn-hero-secondary"><i class="fas fa-play-circle"></i> Explore Features</a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat"><h3>20+</h3><p>Registered Farmers</p></div>
        <div class="hero-stat"><h3>100+</h3><p>Daily Litres</p></div>
        <div class="hero-stat"><h3>99%</h3><p>Payment Accuracy</p></div>
      </div>
    </div>

    <!-- RIGHT CARDS -->
    <div class="hero-visual fade-up" style="transition-delay:.15s;">

      <!-- Today's Collection -->
      <div class="h-card">
        <div class="h-card-hdr">
          <div class="h-card-ico ico-green"><i class="fas fa-chart-line"></i></div>
          <div><h4>Today's Collection</h4><div class="h-card-sub">Morning Session</div></div>
        </div>
        <div class="h-card-grid">
          <div class="h-data">
            <div class="val">340</div>
            <div class="lbl">Litres</div>
          </div>
          <div class="h-data">
            <div class="val">20</div>
            <div class="lbl">Farmers</div>
          </div>
          <div class="h-data">
            <div class="val val-grade">Grade A</div>
            <div class="lbl">Avg Quality</div>
          </div>
        </div>
      </div>

      <!-- AI Quality Analysis -->
      <div class="h-card">
        <div class="h-card-hdr">
          <div class="h-card-ico ico-amber"><i class="fas fa-robot"></i></div>
          <div><h4>AI Quality Analysis</h4><div class="h-card-sub">Real-time monitoring</div></div>
        </div>
        <div class="h-list">
          <span class="ok">✓</span> Fat Content: 3.8% (Optimal)<br>
          <span class="ok">✓</span> Temperature: 4.2°C (Excellent)<br>
          <span class="warn">⚠</span> Tank B: 8hrs stored — Monitor
        </div>
      </div>

      <!-- Monthly Payments -->
      <div class="h-card">
        <div class="h-card-hdr">
          <div class="h-card-ico ico-blue"><i class="fas fa-money-bill-wave"></i></div>
          <div><h4>Monthly Payments</h4><div class="h-card-sub">Processing via M-Pesa</div></div>
        </div>
        <div class="h-card-grid" style="grid-template-columns:1fr 1fr;">
          <div class="h-data">
            <div class="val">KES 15k</div>
            <div class="lbl">Total Paid</div>
          </div>
          <div class="h-data">
            <div class="val">15</div>
            <div class="lbl">Farmers Paid</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ── TRUST BAR ── -->
<div class="trust-bar">
  <div class="trust-inner">
    <div class="trust-item"><i class="fas fa-shield-alt"></i> Secure & Encrypted</div>
    <div class="trust-item"><i class="fas fa-robot"></i> AI-Powered Analysis</div>
    <div class="trust-item"><i class="fas fa-mobile-alt"></i> M-Pesa Payments</div>
    <div class="trust-item"><i class="fas fa-chart-bar"></i> Real-time Reports</div>
    <div class="trust-item"><i class="fas fa-users"></i> Multi-role Access</div>
  </div>
</div>

<!-- ── FEATURES ── -->
<section class="features-sec">
  <div class="section-container">
    <div class="fade-up" style="text-align:center;">
      <div class="section-label" style="justify-content:center;"><span>What We Offer</span></div>
      <h2 class="section-title">Everything Your Cooperative Needs</h2>
      <p class="section-subtitle" style="margin:0 auto 50px;">From milk delivery to payment — one platform handles it all.</p>
    </div>
    <div class="feat-grid">
      <div class="feat-card fade-up">
        <div class="feat-ico"><i class="fas fa-truck"></i></div>
        <h4>Milk Delivery Tracking</h4>
        <p>Record every delivery with quantity, session, temperature and quality parameters. Full delivery history per farmer.</p>
      </div>
      <div class="feat-card fade-up" style="transition-delay:.07s;">
        <div class="feat-ico"><i class="fas fa-flask"></i></div>
        <h4>Quality Monitoring</h4>
        <p>Track fat content, protein, pH, SNF, water content and antibiotic tests. Auto-grade each delivery A, B, C or Rejected.</p>
      </div>
      <div class="feat-card fade-up" style="transition-delay:.14s;">
        <div class="feat-ico"><i class="fas fa-robot"></i></div>
        <h4>AI Quality Advisor</h4>
        <p>AI analyzes milk parameters and gives grade verdict, spoilage risk score, key issues and actionable recommendations.</p>
      </div>
      <div class="feat-card fade-up" style="transition-delay:.21s;">
        <div class="feat-ico"><i class="fas fa-thermometer-half"></i></div>
        <h4>Spoilage Detection</h4>
        <p>Monitor storage temperatures and detect spoilage risk early — Low, Medium, High or Critical alerts in real time.</p>
      </div>
      <div class="feat-card fade-up" style="transition-delay:.28s;">
        <div class="feat-ico"><i class="fas fa-mobile-alt"></i></div>
        <h4>M-Pesa Payments</h4>
        <p>Process farmer payments directly via M-Pesa STK Push. Payments calculated by quantity and quality grade automatically.</p>
      </div>
      <div class="feat-card fade-up" style="transition-delay:.35s;">
        <div class="feat-ico"><i class="fas fa-chart-bar"></i></div>
        <h4>Reports & Analytics</h4>
        <p>Generate delivery, quality, spoilage and payment reports. Filter by farmer, date range and export or print.</p>
      </div>
    </div>
  </div>
</section>

<!-- ── HOW IT WORKS ── -->
<section class="how-sec">
  <div class="section-container">
    <div class="fade-up" style="text-align:center;">
      <div class="section-label" style="justify-content:center;"><span>Process</span></div>
      <h2 class="section-title">How It Works</h2>
      <p class="section-subtitle" style="margin:0 auto 56px;">From milk delivery to payment — a seamless digital process.</p>
    </div>
    <div class="steps-grid">
      <div class="step-card fade-up">
        <div class="step-num">1</div>
        <h4>Farmer Delivers Milk</h4>
        <p>Farmer brings milk to the collection centre during morning or evening session.</p>
      </div>
      <div class="step-card fade-up" style="transition-delay:.1s;">
        <div class="step-num">2</div>
        <h4>Quality Testing</h4>
        <p>Staff tests milk for fat, protein, temperature, pH and other parameters.</p>
      </div>
      <div class="step-card fade-up" style="transition-delay:.2s;">
        <div class="step-num">3</div>
        <h4>AI Analysis</h4>
        <p>AI grades the milk, detects spoilage risk and gives specific recommendations.</p>
      </div>
      <div class="step-card fade-up" style="transition-delay:.3s;">
        <div class="step-num">4</div>
        <h4>M-Pesa Payment</h4>
        <p>Payment calculated by quantity and quality, sent directly to farmer's phone via M-Pesa.</p>
      </div>
    </div>
  </div>
</section>

<!-- ── STATS ── -->
<section class="stats-band">
  <div class="section-container">
    <div class="stats-grid">
      <div class="stat-box fade-up"><h3>20+</h3><p>Registered Farmers</p></div>
      <div class="stat-box fade-up" style="transition-delay:.08s;"><h3>100+</h3><p>Daily Litres Collected</p></div>
      <div class="stat-box fade-up" style="transition-delay:.16s;"><h3>99%</h3><p>Payment Accuracy</p></div>
      <div class="stat-box fade-up" style="transition-delay:.24s;"><h3>24/7</h3><p>System Availability</p></div>
    </div>
  </div>
</section>

<!-- ── CTA ── -->
<section class="cta-band">
  <div class="cta-inner fade-up">
    <h2>Ready to Modernize Your Cooperative?</h2>
    <p>Join Githunguri Dairy Farmers Cooperative Society's digital platform. Register as a farmer or access the staff portal today.</p>
    <div class="cta-btns">
      <a href="farmer/register.php" class="btn-cta-w"><i class="fas fa-user-plus"></i> Register as Farmer</a>
      <a href="staff/login.php" class="btn-cta-o"><i class="fas fa-lock"></i> Staff Portal</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
