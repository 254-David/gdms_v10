<?php $currentPage = basename($_SERVER['PHP_SELF'], '.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Githunguri Dairy Management System' ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root{
  /* ═══════════════════════════════════════════
     GDMS COLOUR PALETTE — edit these to restyle
     ═══════════════════════════════════════════ */
  --green:  #00875a;   /* Primary green — buttons, active nav, badges  */
  --green2: #006644;   /* Darker green  — hover states                 */
  --green3: #e3f9f0;   /* Pale green    — light backgrounds, chips     */
  --green4: #00b37d;   /* Bright green  — highlights, icons             */

  --navy:   #0d1f2d;   /* Headings & dark text                          */
  --navy2:  #1e3448;   /* Section dark backgrounds                      */

  --amber:  #f59e0b;   /* Amber accent — badges, tags                   */
  --amber2: #fbbf24;   /* Lighter amber                                 */

  --blue:   #2563eb;   /* Blue — info, links                            */
  --red:    #dc2626;   /* Red  — errors, rejected                       */

  --bg:     #ffffff;   /* Page background — pure white                  */
  --bg2:    #f5f7fa;   /* Light grey sections                           */
  --bg3:    #eaf7f2;   /* Pale green sections                           */

  --tx:     #0d1f2d;   /* Primary text — very dark navy                 */
  --tx2:    #3d5166;   /* Secondary text — readable grey                */
  --tx3:    #7a93a6;   /* Muted labels                                  */

  --border: #dde5ed;   /* Card/input borders                            */
  --shadow: 0 2px 12px rgba(0,40,20,0.07);
  --shadow2:0 6px 32px rgba(0,40,20,0.12);

  /* Legacy aliases — keep these so other pages don't break */
  --primary:#00875a; --primary-mid:#006644; --primary-dark:#004d29;
  --page-bg:#f5f7fa; --section-alt:#eaf7f2; --card-bg:#ffffff;
  --card-border:#dde5ed; --accent:#f59e0b; --accent-light:#fbbf24;
  --white:#ffffff; --text:#0d1f2d; --text-muted:#3d5166;
  --gradient:linear-gradient(135deg,#00875a,#006644);
  --glow:0 0 24px rgba(0,135,90,0.15);
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{font-family:'DM Sans',sans-serif;color:var(--tx);background:var(--bg);overflow-x:hidden;}

/* ── NAVBAR ── */
.navbar{position:fixed;top:0;left:0;right:0;z-index:1000;padding:16px 0;transition:all .3s;background:rgba(255,255,255,0.95);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);box-shadow:0 1px 0 var(--border);}
.navbar.scrolled{padding:10px 0;box-shadow:0 2px 20px rgba(0,50,30,0.1);}
.nav-container{max-width:1240px;margin:0 auto;padding:0 40px;display:flex;align-items:center;justify-content:space-between;}
.nav-logo{display:flex;align-items:center;gap:11px;text-decoration:none;}
.logo-icon{width:44px;height:44px;background:var(--amber);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow:0 3px 10px rgba(245,158,11,0.35);}
.logo-text h1{font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--navy);line-height:1.1;}
.logo-text span{font-size:10px;color:var(--tx3);font-weight:400;}
.nav-links{display:flex;align-items:center;gap:2px;list-style:none;}
.nav-links a{color:#004d29;text-decoration:none;font-size:14px;font-weight:500;padding:7px 14px;border-radius:8px;transition:all .2s;}
.nav-links a:hover,.nav-links a.active{color:var(--green);background:var(--green3);}
.nav-btns{display:flex;gap:10px;align-items:center;}
.btn-nav{padding:8px 18px;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;transition:all .25s;cursor:pointer;border:none;font-family:inherit;}
.btn-outline{border:1.5px solid var(--green);color:var(--green);background:transparent;}
.btn-outline:hover{background:var(--green3);}
.btn-accent{background:var(--green);color:white;box-shadow:0 3px 12px rgba(0,154,85,0.3);}
.btn-accent:hover{background:var(--green2);transform:translateY(-1px);box-shadow:0 5px 18px rgba(0,154,85,0.4);}
.hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none;padding:4px;}
.hamburger span{display:block;width:24px;height:2px;background:var(--navy);border-radius:2px;transition:all .3s;}

/* ── SHARED SECTIONS ── */
.section-container{max-width:1240px;margin:0 auto;padding:0 40px;}
.section-label{display:inline-flex;align-items:center;gap:8px;color:var(--green);font-size:12px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:10px;}
.section-label::before{content:'';width:28px;height:2px;background:var(--amber);}
.section-title{font-family:'Playfair Display',serif;font-size:40px;font-weight:700;color:var(--navy);line-height:1.15;margin-bottom:16px;}
.section-subtitle{font-size:16px;color:var(--tx2);line-height:1.75;max-width:580px;margin-bottom:56px;}

/* ── PAGE HERO BANNER (inner pages) ── */
.page-hero{padding:120px 0 70px;background:linear-gradient(135deg,#0f2137 0%,#1e4d30 60%,#0f2137 100%);position:relative;overflow:hidden;text-align:center;}
.page-hero::before{content:'';position:absolute;top:-30%;left:50%;transform:translateX(-50%);width:600px;height:600px;background:radial-gradient(circle,rgba(0,154,85,0.15) 0%,transparent 70%);border-radius:50%;pointer-events:none;}
.page-hero .section-label{justify-content:center;color:#34d399;}
.page-hero .section-label::before{background:#f59e0b;}
.page-hero .section-title{font-size:50px;color:white;}
.page-hero p{color:rgba(255,255,255,0.7);font-size:17px;line-height:1.7;max-width:580px;margin:0 auto;}

/* ── FOOTER ── */
footer{background:var(--navy);color:rgba(255,255,255,0.65);padding:60px 0 28px;border-top:none;}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:48px;max-width:1240px;margin:0 auto;padding:0 40px;}
.footer-brand p{font-size:13px;line-height:1.75;margin-top:14px;color:rgba(255,255,255,0.55);}
.footer-socials{display:flex;gap:10px;margin-top:20px;}
.social-link{width:38px;height:38px;border-radius:9px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);text-decoration:none;transition:all .2s;}
.social-link:hover{background:var(--green);color:white;}
.footer-col h5{font-size:13px;font-weight:700;color:white;margin-bottom:18px;letter-spacing:.5px;}
.footer-col ul{list-style:none;}
.footer-col ul li{margin-bottom:10px;}
.footer-col ul li a{color:rgba(255,255,255,0.55);text-decoration:none;font-size:13px;transition:color .2s;}
.footer-col ul li a:hover{color:var(--amber2);}
.footer-divider{border:none;border-top:1px solid rgba(255,255,255,0.08);margin:40px 40px 24px;}
.footer-bottom{max-width:1240px;margin:0 auto;padding:0 40px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;}
.footer-bottom p{font-size:12px;color:rgba(255,255,255,0.4);}

/* ── ANIMATIONS ── */
.fade-up{opacity:0;transform:translateY(28px);transition:all .55s ease;}
.fade-up.visible{opacity:1;transform:translateY(0);}

/* ── SCROLL TOP ── */
.scroll-top{position:fixed;bottom:28px;right:28px;width:44px;height:44px;background:var(--green);color:white;border:none;border-radius:11px;cursor:pointer;display:none;align-items:center;justify-content:center;font-size:16px;box-shadow:0 4px 16px rgba(0,154,85,0.35);transition:all .3s;z-index:999;}
.scroll-top:hover{background:var(--green2);transform:translateY(-2px);}
.scroll-top.show{display:flex;}

@media(max-width:1024px){.section-title{font-size:34px;}}
@media(max-width:768px){
  .nav-links,.nav-btns{display:none;}
  .hamburger{display:flex;}
  .nav-container{padding:0 20px;}
  .section-container{padding:0 20px;}
  .section-title{font-size:28px;}
  .page-hero .section-title{font-size:34px;}
  .footer-grid{grid-template-columns:1fr 1fr;gap:28px;}
  .footer-bottom{flex-direction:column;text-align:center;}
}
</style>
<?= $extraStyles ?? '' ?>
</head>
<body>

<nav class="navbar" id="navbar">
  <div class="nav-container">
    <a href="<?= isset($isSubdir)?'../':'' ?>index.php" class="nav-logo">
      <div class="logo-icon">🥛</div>
      <div class="logo-text"><h1>GDMS</h1><span>Githunguri Dairy</span></div>
    </a>
    <ul class="nav-links">
      <?php $base = isset($isSubdir)?'../':''; ?>
      <li><a href="<?= $base ?>features.php" class="<?= $currentPage==='features'?'active':'' ?>">Features</a></li>
      <li><a href="<?= $base ?>portals.php" class="<?= $currentPage==='portals'?'active':'' ?>">Portals</a></li>
      <li><a href="<?= $base ?>about.php" class="<?= $currentPage==='about'?'active':'' ?>">About</a></li>
      <li><a href="<?= $base ?>help.php" class="<?= $currentPage==='help'?'active':'' ?>">Help</a></li>
      <li><a href="<?= $base ?>contact.php" class="<?= $currentPage==='contact'?'active':'' ?>">Contact</a></li>
    </ul>
    <div class="nav-btns">
      <a href="<?= $base ?>farmer/login.php" class="btn-nav btn-outline">Farmer Login</a>
      <a href="<?= $base ?>staff/login.php" class="btn-nav btn-accent">Staff Portal</a>
    </div>
    <button class="hamburger" onclick="toggleMobileMenu()">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>
