<?php
session_start();

// Prevent browser caching the login page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// If already logged in, go to dashboard
if(isset($_SESSION['staff_id']) && !empty($_SESSION['staff_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
    header('Location: dashboard.php');
    exit;
}

// Clear any existing session (farmer or stale staff)
session_unset();
session_destroy();
session_write_close();
session_start();
session_regenerate_id(true);

require_once '../includes/config.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username    = sanitize($_POST['username']??'');
    $password    = $_POST['password']??'';
    $access_code = sanitize($_POST['access_code']??'');

    if(empty($username)||empty($password)||empty($access_code)){
        $error = 'All fields are required.';
    } else {
        try {
            $db = Database::getInstance();
            $staff = $db->fetchOne("SELECT * FROM staff WHERE username=? AND status='active'", [$username]);

            if($staff && verifyPassword($password, $staff['password']) && $staff['access_code'] === $access_code){
                $_SESSION['staff_id']    = $staff['staff_id'];
                $_SESSION['staff_name']  = $staff['full_name'];
                $_SESSION['staff_role']  = $staff['role'];
                $_SESSION['staff_email'] = $staff['email'];
                $_SESSION['logged_in']   = true;
                $db->update("UPDATE staff SET last_login=NOW() WHERE staff_id=?", [$staff['staff_id']]);
                logActivity('staff', $staff['staff_id'], 'Login', 'Staff logged in');
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid username, password, or access code.';
            }
        } catch(Exception $e){
            $error = 'System error. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Login — GDMS</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:#f0f4f8;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
body::before{content:'';position:absolute;top:-20%;right:-10%;width:600px;height:600px;background:radial-gradient(circle,rgba(0,196,140,0.1),transparent 70%);border-radius:50%;pointer-events:none;}
.wrap{display:grid;grid-template-columns:1fr 1fr;max-width:920px;width:95%;border-radius:24px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.14);position:relative;z-index:2;}
.left{background:linear-gradient(160deg,#004d29 0%,#00875a 60%,#006644 100%);padding:52px 44px;display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden;}
.left::before{content:'';position:absolute;top:-30%;right:-20%;width:280px;height:280px;background:rgba(240,165,0,0.08);border-radius:50%;}
.logo{display:flex;align-items:center;gap:12px;text-decoration:none;}
.logo-icon{width:50px;height:50px;background:#f0a500;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:24px;}
.logo h2{font-family:'Playfair Display',serif;font-size:20px;color:white;}
.logo p{font-size:11px;color:rgba(255,255,255,0.5);}
.hero{position:relative;z-index:1;margin-top:40px;}
.hero h1{font-family:'Playfair Display',serif;font-size:32px;color:white;line-height:1.2;margin-bottom:14px;}
.hero h1 span{color:#d97706;}
.hero p{font-size:14px;color:rgba(255,255,255,0.7);line-height:1.7;margin-bottom:28px;}
.feat{display:flex;align-items:center;gap:10px;font-size:13px;color:rgba(255,255,255,0.85);margin-bottom:10px;}
.feat i{color:#d97706;width:16px;}
/* Right panel */
.right{background:#ffffff;padding:52px 44px;display:flex;flex-direction:column;justify-content:center;}
.right h3{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;margin-bottom:6px;color:#0f2137;}
.sub{font-size:14px;color:#64748b;margin-bottom:32px;}
.err{padding:12px 16px;background:#fee2e2;color:#dc2626;border:1px solid #fecaca;border-radius:10px;margin-bottom:20px;font-size:14px;display:flex;align-items:center;gap:8px;}
.hint{background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px 16px;margin-bottom:22px;font-size:13px;color:#374151;}
.hint strong{color:#d97706;}
.fg{margin-bottom:18px;}
.fg label{display:block;font-size:12px;font-weight:700;color:#64748b;margin-bottom:7px;text-transform:uppercase;letter-spacing:.5px;}
.iw{position:relative;}
.iw i.ico{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:14px;}
.fg input{width:100%;padding:12px 13px 12px 40px;border:1.5px solid #cbd5e1;border-radius:10px;font-size:14px;font-family:inherit;transition:all .2s;background:#f8fafc;color:#0f172a;}
.fg input::placeholder{color:#94a3b8;}
.fg input:focus{outline:none;border-color:#00875a;background:#eaf7f2;box-shadow:0 0 0 3px rgba(0,135,90,0.12);}
.eye{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:13px;}
.btn{width:100%;padding:13px;background:linear-gradient(135deg,#00875a,#006644);color:white;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .3s;margin-top:8px;}
.btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(0,135,90,0.4);}
.back{text-align:center;margin-top:22px;font-size:14px;color:#94a3b8;}
.back a{color:#00875a;font-weight:600;text-decoration:none;}
@media(max-width:700px){.wrap{grid-template-columns:1fr;}.left{display:none;}.right{padding:36px 28px;}}
</style>
</head>
<body>
<div class="wrap">
  <div class="left">
    <a href="../index.php" class="logo">
      <div class="logo-icon">🥛</div>
      <div><h2>GDMS</h2><p>Githunguri Dairy</p></div>
    </a>
    <div class="hero">
      <h1>Staff <span>Management</span> Portal</h1>
      <p>Access your full administrative dashboard — manage farmers, milk records, quality analysis, AI insights and payments.</p>
      <div class="feat"><i class="fas fa-users"></i> Manage all registered farmers</div>
      <div class="feat"><i class="fas fa-flask"></i> Record &amp; analyze milk quality</div>
      <div class="feat"><i class="fas fa-robot"></i> AI-powered quality insights</div>
      <div class="feat"><i class="fas fa-money-check-alt"></i> Process farmer payments</div>
      <div class="feat"><i class="fas fa-chart-bar"></i> Generate detailed reports</div>
    </div>
    <p style="font-size:12px;color:rgba(223, 215, 215, 1);">All actions are logged for accountability.</p>
  </div>
  <div class="right">
    <h3>Welcome Back</h3>
    <p class="sub">Sign in with your staff credentials.</p>
    <?php if($error): ?>
    <div class="err"><i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
   <!--<div class="hint"><i class="fas fa-info-circle" style="color:#f0a500;margin-right:6px;"></i>
      <strong>Default:</strong> Username: <code style="background:rgba(255,255,255,0.1);padding:1px 6px;border-radius:4px;">admin</code> &nbsp;
      Password: <code style="background:rgba(255,255,255,0.1);padding:1px 6px;border-radius:4px;">password</code> &nbsp;
      Code: <code style="background:rgba(255,255,255,0.1);padding:1px 6px;border-radius:4px;">GDFC2024</code>
    </div>-->
    <form method="POST" id="lf" autocomplete="off">
      <div class="fg">
        <label>Username</label>
        <div class="iw">
          <i class="fas fa-user ico"></i>
          <input type="text" name="username" placeholder="Your username" required autocomplete="off">
        </div>
      </div>
      <div class="fg">
        <label>Password</label>
        <div class="iw">
          <i class="fas fa-lock ico"></i>
          <input type="password" name="password" id="pw" placeholder="Your password" required autocomplete="new-password">
          <button type="button" class="eye" onclick="togglePw()"><i class="fas fa-eye" id="eyeicon"></i></button>
        </div>
      </div>
      <div class="fg">
        <label>Cooperative Access Code</label>
        <div class="iw">
          <i class="fas fa-shield-alt ico"></i>
          <input type="text" name="access_code" placeholder="e.g. GDFC2024" required autocomplete="off">
        </div>
      </div>
      <div id="pw-hint" style="display:none;padding:8px 12px;border-radius:8px;font-size:12px;margin-bottom:8px;"></div>
      <button type="submit" class="btn" id="sb">
        <i class="fas fa-sign-in-alt"></i> Sign In to Dashboard
      </button>
    </form>
    <div class="back">
      <a href="../index.php"><i class="fas fa-arrow-left"></i> Homepage</a>
      &nbsp;|&nbsp;
      <a href="../farmer/login.php">Farmer Login</a>
    </div>
  </div>
</div>
<script>
document.getElementById('pw').addEventListener('input',function(){
    const v=this.value, hint=document.getElementById('pw-hint');
    if(!v){hint.style.display='none';return;}
    const strong=/[A-Z]/.test(v)&&/[0-9]/.test(v)&&/[^a-zA-Z0-9]/.test(v)&&v.length>=8;
    const medium=v.length>=6&&(/[A-Z]/.test(v)||/[0-9]/.test(v));
    hint.style.display='block';
    if(strong){hint.style.background='#f0fdf4';hint.style.color='#065f46';hint.style.border='1px solid #bbf7d0';hint.innerHTML='<i class="fas fa-check-circle"></i> Strong password';}
    else if(medium){hint.style.background='#fffbeb';hint.style.color='#92400e';hint.style.border='1px solid #fde68a';hint.innerHTML='<i class="fas fa-exclamation-circle"></i> Medium — add uppercase, numbers & symbols';}
    else{hint.style.background='#fff1f2';hint.style.color='#9f1239';hint.style.border='1px solid #fecdd3';hint.innerHTML='<i class="fas fa-times-circle"></i> Weak password';}
});
function togglePw(){
    const pw=document.getElementById('pw');
    const ic=document.getElementById('eyeicon');
    if(pw.type==='password'){pw.type='text';ic.className='fas fa-eye-slash';}
    else{pw.type='password';ic.className='fas fa-eye';}
}
document.getElementById('lf').addEventListener('submit',function(){
    const b=document.getElementById('sb');
    b.disabled=true;
    b.innerHTML='<i class="fas fa-spinner fa-spin"></i> Signing in...';
});
</script>
</body>
</html>
