<?php
$pageTitle = 'About Us — Githunguri Dairy Management System';
$extraStyles = '<style>
.about-section{padding:90px 0;background:var(--page-bg);}
.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;margin-bottom:90px;}
.about-img-wrapper{position:relative;}
.about-img{width:100%;height:480px;object-fit:cover;border-radius:24px;border:1px solid rgba(46,204,113,0.15);}
.about-badge{position:absolute;bottom:-20px;left:-20px;background:var(--accent);color:#0a2200;border-radius:16px;padding:20px 24px;box-shadow:0 10px 30px rgba(240,165,0,0.4);}
.about-badge h4{font-family:"Playfair Display",serif;font-size:28px;font-weight:700;}
.about-badge p{font-size:13px;font-weight:600;}
.about-values{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:32px;}
.value-item{display:flex;gap:12px;align-items:flex-start;padding:16px;background:#ffffff;border:1px solid #dde5ed;border-radius:12px;transition:all .3s;box-shadow:0 1px 4px rgba(0,0,0,0.04);}
.value-item:hover{border-color:rgba(46,204,113,0.3);box-shadow:0 4px 20px rgba(46,204,113,0.08);}
.value-icon{width:36px;height:36px;border-radius:10px;background:rgba(46,204,113,0.12);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;}
.value-item h5{font-size:14px;font-weight:700;margin-bottom:4px;color:#0d1f2d;}
.value-item p{font-size:13px;color:var(--text-muted);}
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;margin-bottom:90px;}
.stat-box{background:#ffffff;border:1px solid #dde5ed;border-radius:16px;padding:28px;text-align:center;transition:all .3s;box-shadow:0 2px 10px rgba(0,0,0,0.05);}
.stat-box:hover{border-color:rgba(46,204,113,0.3);transform:translateY(-4px);}
.stat-box h3{font-family:"Playfair Display",serif;font-size:40px;font-weight:700;color:var(--accent);margin-bottom:8px;}
.stat-box p{font-size:14px;color:var(--text-muted);}
.team-section{padding:0 0 90px;background:var(--page-bg);}
.dev-card{background:#ffffff;border:1px solid #dde5ed;border-radius:20px;padding:40px;display:flex;gap:36px;align-items:center;max-width:780px;margin:0 auto;box-shadow:0 4px 20px rgba(0,0,0,0.07);}
.dev-avatar{width:100px;height:100px;border-radius:20px;background:linear-gradient(135deg,#1a6b3c,#2d9158);display:flex;align-items:center;justify-content:center;font-family:"Playfair Display",serif;font-size:36px;font-weight:700;color:var(--accent);flex-shrink:0;border:3px solid rgba(46,204,113,0.3);}
.dev-card h4{font-family:"Playfair Display",serif;font-size:22px;font-weight:700;color:#0d1f2d;margin-bottom:6px;}
.dev-card .role{font-size:13px;color:var(--primary);font-weight:600;margin-bottom:12px;}
.dev-card p{font-size:14px;color:var(--text-muted);line-height:1.7;}
@media(max-width:768px){.about-grid{grid-template-columns:1fr;}.stats-row{grid-template-columns:repeat(2,1fr);}.about-values{grid-template-columns:1fr;}.dev-card{flex-direction:column;align-items:center;text-align:center;}}
</style>';
include 'includes/nav.php';
?>

<div class="page-hero">
    <div class="section-container">
        <div class="section-label" style="justify-content:center;"><span>About Us</span></div>
        <h1 class="section-title fade-up">Githunguri Dairy Farmers Cooperative</h1>
        <p class="fade-up" style="transition-delay:.1s;">Empowering dairy farmers across Kiambu County with technology, transparency, and fairness.</p>
    </div>
</div>

<section class="about-section">
    <div class="section-container">
        <div class="stats-row">
            <div class="stat-box fade-up"><h3>60+</h3><p>Years of Operation</p></div>
            <div class="stat-box fade-up" style="transition-delay:.08s"><h3>500+</h3><p>Active Farmers</p></div>
            <div class="stat-box fade-up" style="transition-delay:.16s"><h3>1K+</h3><p>Daily Litres Collected</p></div>
            <div class="stat-box fade-up" style="transition-delay:.24s"><h3>99%</h3><p>Payment Accuracy</p></div>
        </div>

        <div class="about-grid">
            <div class="about-img-wrapper fade-up">
                <img src="https://images.unsplash.com/photo-1533779283484-8ad4a1b79990?w=600&h=480&fit=crop" alt="Dairy Farm" class="about-img" onerror="this.src='https://images.unsplash.com/photo-1500595046743-cd271d694d30?w=600&h=480&fit=crop'">
                <div class="about-badge">
                   
                    <p>Serving Kiambu County</p>
                </div>
            </div>
            <div class="fade-up" style="transition-delay:.2s;">
                <div class="section-label"><span>Our Story</span></div>
                <h2 class="section-title">A Legacy of Dairy Excellence</h2>
                <p style="color:var(--text-muted);line-height:1.8;margin-bottom:20px;font-size:16px;">One of Kenya's most successful dairy cooperatives, Githunguri DFCS has been empowering farmers in Kiambu County for over six decades. This digital management system represents our commitment to leveraging technology for better farmer outcomes.</p>
                <p style="color:var(--text-muted);line-height:1.8;margin-bottom:32px;font-size:15px;">Our system was developed as part of a research project by Meru University of Science and Technology to modernize dairy cooperative operations, replacing manual processes with an efficient, transparent, and intelligent digital platform.</p>
                <div class="about-values">
                    <div class="value-item"><div class="value-icon"><i class="fas fa-shield-alt"></i></div><div><h5>Transparency</h5><p>Full visibility into deliveries and payments for every farmer.</p></div></div>
                    <div class="value-item"><div class="value-icon"><i class="fas fa-balance-scale"></i></div><div><h5>Fairness</h5><p>Quality-based payment ensures farmers are rewarded fairly.</p></div></div>
                    <div class="value-item"><div class="value-icon"><i class="fas fa-bolt"></i></div><div><h5>Efficiency</h5><p>Automated processes save time and reduce human error.</p></div></div>
                    <div class="value-item"><div class="value-icon"><i class="fas fa-seedling"></i></div><div><h5>Growth</h5><p>Data-driven insights help farmers improve productivity.</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="team-section">
    <div class="section-container">
        <div class="fade-up" style="text-align:center;margin-bottom:48px;">
            <div class="section-label" style="justify-content:center;"><span>Developer</span></div>
            <h2 class="section-title">System Development</h2>
        </div>
        <div class="dev-card fade-up">
            <div class="dev-avatar">DK</div>
            <div>
                <h4>David Kimani</h4>
                <div class="role">Lead Developer — Meru University of Science &amp; Technology</div>
                <p>This system was developed as a research project to modernize the Githunguri Dairy Farmers Cooperative Society operations. The platform replaces manual paper-based processes with a fully digital, AI-enhanced management system covering farmer management, milk quality testing, payment processing, and analytics.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
