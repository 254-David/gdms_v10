<?php
$pageTitle = 'Access Portals — Githunguri Dairy Management System';
$extraStyles = '<style>
.portals-section{padding:80px 0;background:var(--page-bg);}
.portals-grid{display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-bottom:60px;}
.portal-card{border-radius:24px;padding:48px;position:relative;overflow:hidden;transition:all .3s;}
.portal-card:hover{transform:translateY(-6px);box-shadow:0 20px 50px rgba(0,0,0,0.4);}
.portal-farmer{background:linear-gradient(135deg,#0f4027 0%,#1a6b3c 60%,#2d9158 100%);border:1px solid rgba(46,204,113,0.2);}
.portal-staff{background:linear-gradient(135deg,#0a1e3d 0%,#1e3a5f 60%,#2563eb 100%);border:1px solid rgba(37,99,235,0.2);}
.portal-card::after{content:"";position:absolute;top:-40%;right:-20%;width:300px;height:300px;background:rgba(255,255,255,0.04);border-radius:50%;}
.portal-icon{width:72px;height:72px;border-radius:20px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:36px;margin-bottom:24px;}
.portal-card h3{font-family:"Playfair Display",serif;font-size:28px;font-weight:700;margin-bottom:12px;color:white;}
.portal-card p{font-size:15px;color:rgba(255,255,255,0.8);line-height:1.7;margin-bottom:32px;}
.portal-features{list-style:none;margin-bottom:36px;}
.portal-features li{display:flex;align-items:center;gap:10px;font-size:14px;margin-bottom:10px;color:rgba(255,255,255,0.85);}
.portal-features li i{color:var(--accent-light);width:18px;}
.btn-portal{display:inline-flex;align-items:center;gap:10px;padding:14px 28px;background:white;border-radius:12px;font-size:14px;font-weight:700;text-decoration:none;transition:all .3s;}
.portal-farmer .btn-portal{color:#0f4027;}.portal-staff .btn-portal{color:#0a1e3d;}
.btn-portal:hover{transform:translateX(4px);box-shadow:0 8px 25px rgba(0,0,0,0.25);}
.comparison-section{padding:0 0 80px;background:var(--page-bg);}
.comp-table{background:#ffffff;border:1px solid #dde5ed;border-radius:16px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.05);}
.comp-header{display:grid;grid-template-columns:2fr 1fr 1fr;background:rgba(46,204,113,0.08);padding:20px 28px;border-bottom:1px solid rgba(46,204,113,0.12);}
.comp-header span{font-size:13px;font-weight:700;color:#0d1f2d;text-align:center;}
.comp-header span:first-child{text-align:left;}
.comp-row{display:grid;grid-template-columns:2fr 1fr 1fr;padding:16px 28px;border-bottom:1px solid rgba(255,255,255,0.04);align-items:center;transition:background .2s;}
.comp-row:last-child{border-bottom:none;}
.comp-row:hover{background:rgba(46,204,113,0.04);}
.comp-row span:first-child{font-size:14px;color:var(--text-muted);}
.comp-row span{text-align:center;font-size:18px;}
@media(max-width:768px){.portals-grid{grid-template-columns:1fr;}.comp-table{overflow-x:auto;}.comp-header,.comp-row{min-width:480px;}}
</style>';
include 'includes/nav.php';
?>

<div class="page-hero">
    <div class="section-container">
        <div class="section-label" style="justify-content:center;"><span>Access Portals</span></div>
        <h1 class="section-title fade-up">Choose Your Dashboard</h1>
        <p class="fade-up" style="transition-delay:.1s;">Two dedicated portals designed for cooperative staff and farmers, each with tailored functionality and role-based access.</p>
    </div>
</div>

<section class="portals-section">
    <div class="section-container">
        <div class="portals-grid">
            <div class="portal-card portal-farmer fade-up">
                <div class="portal-icon">🌾</div>
                <h3>Farmer Portal</h3>
                <p>Self-service dashboard for milk-supplying farmers to track contributions, quality results, and payment history.</p>
                <ul class="portal-features">
                    <li><i class="fas fa-check-circle"></i> View milk delivery history</li>
                    <li><i class="fas fa-check-circle"></i> Track quality feedback &amp; grades</li>
                    <li><i class="fas fa-check-circle"></i> View payment summaries</li>
                    <li><i class="fas fa-check-circle"></i> Download monthly statements</li>
                    <li><i class="fas fa-check-circle"></i> Receive cooperative announcements</li>
                    <li><i class="fas fa-check-circle"></i> Update profile &amp; bank details</li>
                </ul>
                <a href="farmer/login.php" class="btn-portal">Login as Farmer <i class="fas fa-arrow-right"></i></a>
                <a href="farmer/register.php" style="display:block;margin-top:14px;color:rgba(255,255,255,0.7);font-size:14px;text-decoration:none;">New farmer? <strong style="color:var(--accent-light);">Register here →</strong></a>
            </div>
            <div class="portal-card portal-staff fade-up" style="transition-delay:.1s;">
                <div class="portal-icon">👔</div>
                <h3>Staff Portal</h3>
                <p>Full administrative dashboard for cooperative staff with complete management capabilities and AI-powered analytics.</p>
                <ul class="portal-features">
                    <li><i class="fas fa-check-circle"></i> Register &amp; manage farmers</li>
                    <li><i class="fas fa-check-circle"></i> Record milk deliveries &amp; quality</li>
                    <li><i class="fas fa-check-circle"></i> AI-powered milk analysis</li>
                    <li><i class="fas fa-check-circle"></i> Process &amp; track payments</li>
                    <li><i class="fas fa-check-circle"></i> Manage storage tanks</li>
                    <li><i class="fas fa-check-circle"></i> Generate detailed reports</li>
                </ul>
                <a href="staff/login.php" class="btn-portal">Staff Login <i class="fas fa-arrow-right"></i></a>
                <p style="margin-top:14px;color:rgba(255,255,255,0.55);font-size:13px;">Staff accounts are pre-configured by system admin</p>
            </div>
        </div>

        <section class="comparison-section">
            <div class="fade-up" style="text-align:center;margin-bottom:40px;">
                <div class="section-label" style="justify-content:center;"><span>Feature Comparison</span></div>
                <h2 class="section-title">Portal Capabilities</h2>
            </div>
            <div class="comp-table">
                <div class="comp-header">
                    <span>Feature</span>
                    <span>🌾 Farmer</span>
                    <span>👔 Staff</span>
                </div>
                <?php $rows = [
                    ['View my delivery history','✅','✅'],
                    ['View quality test results','✅','✅'],
                    ['View payment history','✅','✅'],
                    ['Download statements','✅','✅'],
                    ['Update profile & bank details','✅','—'],
                    ['Receive announcements','✅','✅'],
                    ['Record milk deliveries','—','✅'],
                    ['Perform quality testing','—','✅'],
                    ['AI milk analysis','—','✅'],
                    ['Process farmer payments','—','✅'],
                    ['Register new farmers','—','✅'],
                    ['Manage storage tanks','—','✅'],
                    ['Generate system reports','—','✅'],
                    ['Manage staff accounts','—','✅'],
                    ['Post announcements','—','✅'],
                ]; foreach($rows as $r): ?>
                <div class="comp-row">
                    <span><?= $r[0] ?></span>
                    <span><?= $r[1] ?></span>
                    <span><?= $r[2] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
