<?php
/**
 * ONE-TIME Admin Password Reset Utility
 * Run this once, then DELETE this file immediately for security.
 * Access: localhost/gdms_v2/php/reset_admin.php
 */
require_once '../includes/config.php';

$newPassword = 'Admin@GDMS2026';   // Change this to your desired password
$newHash     = password_hash($newPassword, PASSWORD_DEFAULT);

$db = Database::getInstance();
$db->update(
    "UPDATE staff SET password=? WHERE username='admin'",
    [$newHash]
);
?>
<!DOCTYPE html>
<html>
<head><style>
body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f0fdf4;margin:0;}
.box{background:white;border:1px solid #bbf7d0;border-radius:16px;padding:40px;max-width:460px;text-align:center;box-shadow:0 4px 20px rgba(0,100,50,0.1);}
h2{color:#065f46;font-size:22px;margin-bottom:10px;}
.cred{background:#f0fdf4;border:1px solid #a7f3d0;border-radius:10px;padding:18px;margin:20px 0;text-align:left;}
.label{font-size:11px;font-weight:700;text-transform:uppercase;color:#6b7280;letter-spacing:.5px;margin-bottom:4px;}
.val{font-size:17px;font-weight:700;color:#0f2137;font-family:monospace;letter-spacing:1px;}
.warn{background:#fff1f2;border:1px solid #fecdd3;border-radius:10px;padding:14px;color:#9f1239;font-size:13px;margin-top:16px;}
</style></head>
<body>
<div class="box">
  <div style="font-size:40px;margin-bottom:12px;">🔐</div>
  <h2>Admin Password Reset</h2>
  <p style="color:#4a6070;font-size:14px;">Password has been updated successfully.</p>
  <div class="cred">
    <div class="label">Username</div>
    <div class="val">admin</div>
    <div class="label" style="margin-top:14px;">New Password</div>
    <div class="val"><?= htmlspecialchars($newPassword) ?></div>
    <div class="label" style="margin-top:14px;">Access Code</div>
    <div class="val"><?= ACCESS_CODE ?></div>
  </div>
  <div class="warn">
    ⚠️ <strong>Delete this file immediately</strong> after use.<br>
    <code>gdms_v2/php/reset_admin.php</code>
  </div>
  <a href="../staff/login.php" style="display:inline-block;margin-top:18px;padding:11px 26px;background:#009a55;color:white;border-radius:9px;text-decoration:none;font-weight:700;font-size:14px;">Go to Login →</a>
</div>
</body>
</html>
