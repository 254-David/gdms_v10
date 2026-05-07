<?php
session_start();

// If already logged in as a farmer, redirect to dashboard
if(isset($_SESSION['farmer_id']) && isset($_SESSION['farmer_logged_in']) && $_SESSION['farmer_logged_in'] === true){
    header('Location: dashboard.php');
    exit;
}

// Clear any stale staff session so farmer login is clean
if(isset($_SESSION['staff_id'])){
    // Clear all session data
    $_SESSION = array();
    
    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
    
    // Start a fresh session
    session_start();
    session_regenerate_id(true);
}

require_once '../includes/config.php';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $phone    = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if(empty($phone) || empty($password)){
        $error = 'Phone/email and password are required.';
    } else {
        try {
            $db = Database::getInstance();
            $farmer = $db->fetchOne(
                "SELECT * FROM farmers WHERE (phone = ? OR email = ?) AND status = 'active'",
                [$phone, $phone]
            );
            
            if($farmer && verifyPassword($password, $farmer['password'])){
                // Set farmer session variables
                $_SESSION['farmer_id'] = $farmer['farmer_id'];
                $_SESSION['farmer_name'] = $farmer['full_name'];
                $_SESSION['farmer_email'] = $farmer['email'];
                $_SESSION['farmer_phone'] = $farmer['phone'];
                $_SESSION['farmer_logged_in'] = true;
                $_SESSION['login_time'] = time();
                
                // Update last login timestamp
                $db->update("UPDATE farmers SET last_login = NOW() WHERE farmer_id = ?", [$farmer['farmer_id']]);
                
                // Log the activity
                logActivity('farmer', $farmer['farmer_id'], 'Login', 'Farmer logged in successfully');
                
                // Redirect to farmer dashboard - use absolute path to be safe
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid phone/email or password.';
            }
        } catch(Exception $e){
            // Log the error for debugging
            error_log("Farmer login error: " . $e->getMessage());
            $error = 'System error. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Login — Githunguri Dairy Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DM Sans', sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0,135,90, 0.1), transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 900px;
            width: 95%;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.13);
            position: relative;
            z-index: 2;
        }
        
        .left {
            background: linear-gradient(160deg, #004d29 0%, #00875a 55%, #006644 100%);
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        
        .left::before {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 260px;
            height: 260px;
            background: rgba(240, 165, 0, 0.08);
            border-radius: 50%;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            position: relative;
            z-index: 2;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
            background: #f0a500;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .logo h2 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: white;
        }
        
        .logo p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .hero {
            margin-top: 44px;
            position: relative;
            z-index: 1;
        }
        
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 30px;
            color: white;
            line-height: 1.2;
            margin-bottom: 14px;
        }
        
        .hero h1 span {
            color: #fcd34d;
        }
        
        .hero p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.7;
            margin-bottom: 26px;
        }
        
        .feat {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 10px;
        }
        
        .feat i {
            color: #fcd34d;
            width: 16px;
        }
        
        .right {
            background: #ffffff;
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .right h3 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #0d1f2d;
        }
        
        .sub {
            font-size: 14px;
            color: #5a7080;
            margin-bottom: 32px;
        }
        
        .err {
            padding: 12px 16px;
            background: #fff1f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .fg {
            margin-bottom: 18px;
        }
        
        .fg label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #4a6070;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        
        .iw {
            position: relative;
        }
        
        .iw i.ico {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ab0be;
            font-size: 14px;
        }
        
        .fg input {
            width: 100%;
            padding: 12px 13px 12px 40px;
            border: 1.5px solid #dde5ed;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            transition: all .2s;
            background: #f8fafc;
            color: #0d1f2d;
        }
        
        .fg input::placeholder {
            color: #9ab0be;
        }
        
        .fg input:focus {
            outline: none;
            border-color: #00875a;
            background: #eaf7f2;
            box-shadow: 0 0 0 3px rgba(0,135,90, 0.1);
        }
        
        .eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ab0be;
            font-size: 13px;
        }
        
        .eye:hover {
            color: #00875a;
        }
        
        .btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #00875a, #006644);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all .3s;
            margin-top: 8px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 135, 90, 0.35);
        }
        
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .lnks {
            text-align: center;
            margin-top: 22px;
            font-size: 14px;
            color: #7a93a6;
        }
        
        .lnks a {
            color: #00875a;
            font-weight: 600;
            text-decoration: none;
            transition: color .2s;
        }
        
        .lnks a:hover {
            color: #f59e0b;
        }
        
        .forgot-password {
            text-align: right;
            margin-top: 5px;
        }
        
        .forgot-password a {
            font-size: 12px;
            color: #7a93a6;
            text-decoration: none;
        }
        
        .forgot-password a:hover {
            color: #00875a;
        }
        
        @media(max-width: 700px) {
            .wrap {
                grid-template-columns: 1fr;
            }
            .left {
                display: none;
            }
            .right {
                padding: 38px 28px;
            }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <!-- Left Panel - Info Section -->
        <div class="left">
            <a href="../index.php" class="logo">
                <div class="logo-icon">🥛</div>
                <div>
                    <h2>GDMS</h2>
                    <p>Githunguri Dairy</p>
                </div>
            </a>
            
            <div class="hero">
                <h1>Your <span>Dairy Farm</span> Dashboard</h1>
                <p>Access your personal dairy dashboard to track deliveries, quality results, payments and cooperative news.</p>
                
                <div class="feat">
                    <i class="fas fa-truck"></i> View all your milk deliveries
                </div>
                <div class="feat">
                    <i class="fas fa-flask"></i> Check quality grades & feedback
                </div>
                <div class="feat">
                    <i class="fas fa-money-bill-wave"></i> View payment history
                </div>
                <div class="feat">
                    <i class="fas fa-file-download"></i> Download payment statements
                </div>
                <div class="feat">
                    <i class="fas fa-bell"></i> Cooperative announcements
                </div>
            </div>
            
            <p style="font-size: 12px; color: rgba(230, 222, 222, 0.67);">
                Secure farmer portal — your data is private.
            </p>
        </div>
        
        <!-- Right Panel - Login Form -->
        <div class="right">
            <h3>Farmer Login</h3>
            <p class="sub">Sign in with your phone number and password</p>
            
            <?php if($error): ?>
                <div class="err">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="loginForm" autocomplete="off">
                <div class="fg">
                    <label>Phone Number or Email</label>
                    <div class="iw">
                        <i class="fas fa-phone ico"></i>
                        <input type="text" name="phone" placeholder="0712345678 or email" 
                               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" 
                               required autocomplete="off">
                    </div>
                </div>
                
                <div class="fg">
                    <label>Password</label>
                    <div class="iw">
                        <i class="fas fa-lock ico"></i>
                        <input type="password" name="password" id="password" 
                               placeholder="Your password" required autocomplete="off">
                        <button type="button" class="eye" onclick="togglePassword()" tabindex="-1">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div class="forgot-password">
                    <a href="forgot-password.php">Forgot password?</a>
                </div>
                
                <button type="submit" class="btn" id="submitBtn">
                    <i class="fas fa-sign-in-alt"></i> Login to My Dashboard
                </button>
            </form>
            
            <div class="lnks">
                <a href="../index.php"><i class="fas fa-arrow-left"></i> Homepage</a>
                &nbsp;|&nbsp;
                <a href="register.php">Register as Farmer</a>
                &nbsp;|&nbsp;
                <a href="../staff/login.php">Staff Login</a>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'fas fa-eye';
            }
        }
        
        // Form submission handling
        document.getElementById('loginForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
        });
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>