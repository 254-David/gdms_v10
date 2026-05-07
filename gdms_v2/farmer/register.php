<?php
session_start();
require_once '../includes/config.php';
if(isFarmerLoggedIn()){ header('Location: dashboard.php'); exit; }
$error=''; $success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        $db=Database::getInstance();
        $fullName=sanitize($_POST['full_name']??'');
        $email=sanitize($_POST['email']??'');
        $phone=sanitize($_POST['phone']??'');
        $idNum=sanitize($_POST['id_number']??'');
        $password=$_POST['password']??'';
        $confirm=$_POST['confirm_password']??'';
        $location=sanitize($_POST['location']??'');
        $cows=intval($_POST['number_of_cows']??0);
        $mpesa=sanitize($_POST['mpesa_number']??$_POST['phone']??'');
        if(empty($fullName)||empty($email)||empty($phone)||empty($idNum)||empty($password)){$error='All required fields must be filled.';}
        elseif($password!==$confirm){$error='Passwords do not match.';}
        elseif(strlen($password)<8){$error='Password must be at least 8 characters.';}
        elseif(!preg_match('/[A-Z]/',$password)){$error='Password must contain at least one uppercase letter.';}
        elseif(!preg_match('/[0-9]/',$password)){$error='Password must contain at least one number.';}
        elseif(!preg_match('/[^a-zA-Z0-9]/',$password)){$error='Password must contain at least one symbol (@, #, !, etc).';}
        else{
            $existing=$db->fetchOne("SELECT id FROM farmers WHERE email=? OR id_number=? OR phone=?",[$email,$idNum,$phone]);
            if($existing){$error='A farmer with this email, ID number, or phone already exists.';}
            else{
                $farmerId=generateFarmerId();
                $hash=hashPassword($password);
                $db->insert("INSERT INTO farmers(farmer_id,full_name,email,phone,id_number,password,location) VALUES(?,?,?,?,?,?,?)",
                    [$farmerId,$fullName,$email,$phone,$idNum,$hash,$location]);
                $success='Registration successful! Your Farmer ID is <strong>'.$farmerId.'</strong>. You can now login.';
            }
        }
    }catch(Exception $e){$error='Registration failed: '.$e->getMessage();}
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Farmer Registration — GDMS</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:linear-gradient(135deg,#0f4027,#009e72,#00c48c);min-height:100vh;padding:40px 20px;}
.wrap{max-width:700px;margin:0 auto;background:white;border-radius:24px;overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,.3);}
.hdr{background:linear-gradient(135deg,#009e72,#0f4027);padding:36px 40px;text-align:center;}
.hdr .logo{display:inline-flex;align-items:center;gap:12px;text-decoration:none;margin-bottom:20px;}
.logo-icon{width:48px;height:48px;background:#f0a500;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:24px;}
.hdr h1{font-family:'Playfair Display',serif;font-size:26px;color:white;margin-bottom:6px;}
.hdr p{font-size:14px;color:rgba(255,255,255,.7);}
.body{padding:36px 40px;}
.err{padding:12px 16px;background:#fee2e2;color:#991b1b;border-radius:10px;margin-bottom:20px;font-size:14px;display:flex;align-items:center;gap:8px;}
.suc{padding:14px 16px;background:#d1fae5;color:#065f46;border-radius:10px;margin-bottom:20px;font-size:14px;display:flex;align-items:flex-start;gap:8px;}
.sec-title{font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:1px;margin:20px 0 12px;padding-bottom:7px;border-bottom:1px solid #e5e7eb;}
.fg2{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.fg{margin-bottom:16px;}
.fg label{display:block;font-size:12px;font-weight:700;color:#111827;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;}
.fg input,.fg select{width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;font-family:inherit;transition:all .2s;background:#fafafa;color:#111;}
.fg input:focus,.fg select:focus{outline:none;border-color:#009e72;background:white;box-shadow:0 0 0 3px rgba(26,107,60,.09);}
.req{color:#dc2626;}
.btn{width:100%;padding:14px;background:linear-gradient(135deg,#059669,#047857);color:white;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;transition:all .3s;margin-top:8px;}
.btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(26,107,60,.35);}
.ftr{text-align:center;padding:20px;font-size:14px;color:#6b7280;border-top:1px solid #e5e7eb;}
.ftr a{color:#009e72;font-weight:600;text-decoration:none;}
@media(max-width:600px){.fg2{grid-template-columns:1fr;}.body{padding:28px 24px;}}
</style></head><body>
<div class="wrap">
<div class="hdr">
  <a href="../index.php" class="logo"><div class="logo-icon">🥛</div></a>
  <h1>Farmer Registration</h1>
  <p>Githunguri Dairy Farmers Cooperative Society</p>
</div>
<div class="body">
  <?php if($error):?><div class="err"><i class="fas fa-exclamation-circle"></i><?= $error ?></div><?php endif;?>
  <?php if($success):?><div class="suc"><i class="fas fa-check-circle" style="margin-top:2px;"></i><div><?= $success ?> <a href="login.php" style="color:#065f46;font-weight:700;">Login here →</a></div></div><?php endif;?>
  <?php if(!$success):?>
  <form method="POST">
    <div class="sec-title">Personal Information</div>
    <div class="fg2">
      <div class="fg"><label>Full Name <span class="req">*</span></label><input type="text" name="full_name" required placeholder="Your full name" value="<?= htmlspecialchars($_POST['full_name']??'') ?>"></div>
      <div class="fg"><label>National ID <span class="req">*</span></label><input type="text" name="id_number" required placeholder="ID number" value="<?= htmlspecialchars($_POST['id_number']??'') ?>"></div>
      <div class="fg"><label>Phone Number <span class="req">*</span></label><input type="tel" name="phone" required placeholder="0712345678" value="<?= htmlspecialchars($_POST['phone']??'') ?>"></div>
      <div class="fg"><label>Email Address <span class="req">*</span></label><input type="email" name="email" required placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email']??'') ?>"></div>
    </div>
    <div class="sec-title">Farm Information</div>
    <div class="fg2">
      <div class="fg"><label>Location / Village</label><input type="text" name="location" placeholder="Your village/area" value="<?= htmlspecialchars($_POST['location']??'') ?>"></div>
    </div>
    <div class="sec-title">Account Security</div>
    <div class="fg2">
      <div class="fg"><label>Password <span class="req">*</span></label><input type="password" name="password" required placeholder="Min. 8 chars, uppercase, number, symbol" id="reg-pw"></div>
      <div class="fg"><label>Confirm Password <span class="req">*</span></label><input type="password" name="confirm_password" required placeholder="Repeat password" id="reg-pw2"></div>
      <div id="pw-strength" style="margin-top:-8px;margin-bottom:10px;padding:10px 14px;border-radius:8px;background:#f8fafc;border:1px solid #e2e8f0;display:none;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
          <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;">Password Strength</span>
          <span id="pw-label" style="font-size:11px;font-weight:700;"></span>
        </div>
        <div style="height:5px;border-radius:50px;background:#e2e8f0;overflow:hidden;">
          <div id="pw-bar" style="height:100%;border-radius:50px;transition:all .3s;width:0%;"></div>
        </div>
        <ul id="pw-rules" style="margin-top:8px;padding-left:0;list-style:none;display:grid;grid-template-columns:1fr 1fr;gap:3px 10px;"></ul>
      </div>
      <script>
      (function(){
        const pw=document.getElementById('reg-pw');
        const pw2=document.getElementById('reg-pw2');
        const bar=document.getElementById('pw-bar');
        const lbl=document.getElementById('pw-label');
        const box=document.getElementById('pw-strength');
        const rules=document.getElementById('pw-rules');
        const checks=[
          {re:/[A-Z]/,label:'Uppercase letter'},
          {re:/[a-z]/,label:'Lowercase letter'},
          {re:/[0-9]/,label:'Number'},
          {re:/[^a-zA-Z0-9]/,label:'Symbol (@,#,!)'},
          {re:/.{8,}/,label:'8+ characters'},
          {re:/.{12,}/,label:'12+ characters (strong)'},
        ];
        const levels=[
          {min:0,label:'Very weak',color:'#f43f5e'},
          {min:2,label:'Weak',color:'#fb923c'},
          {min:3,label:'Fair',color:'#f59e0b'},
          {min:4,label:'Good',color:'#10d97e'},
          {min:6,label:'Strong ✓',color:'#10d97e'},
        ];
        pw.addEventListener('input',function(){
          const v=pw.value;
          if(!v){box.style.display='none';return;}
          box.style.display='block';
          const passed=checks.filter(c=>c.re.test(v)).length;
          const lvl=levels.filter(l=>passed>=l.min).pop();
          const pct=Math.min(100,Math.round((passed/checks.length)*100));
          bar.style.width=pct+'%';
          bar.style.background=lvl.color;
          lbl.textContent=lvl.label;
          lbl.style.color=lvl.color;
          rules.innerHTML=checks.map(c=>`<li style="font-size:11px;color:${c.re.test(v)?'#10d97e':'rgba(240,244,255,0.35)'};">${c.re.test(v)?'✓':'○'} ${c.label}</li>`).join('');
        });
      })();
      </script>
    </div>
    <button type="submit" class="btn"><i class="fas fa-user-plus"></i> Create Farmer Account</button>
  </form>
  <?php endif;?>
</div>
<div class="ftr">Already registered? <a href="login.php">Login here</a> &nbsp;|&nbsp; <a href="../index.php">← Back to Home</a></div>
</div>
</body></html>
