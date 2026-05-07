<?php
$pageTitle = 'Help & FAQ — Githunguri Dairy Management System';
$extraStyles = '<style>
.help-section{padding:80px 0;background:var(--page-bg);}
.help-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-bottom:90px;}
.help-card{background:#ffffff;border:1px solid #dde5ed;border-radius:16px;padding:32px;text-align:center;transition:all .3s;box-shadow:0 2px 10px rgba(0,0,0,0.05);}
.help-card:hover{border-color:#00875a;box-shadow:0 6px 24px rgba(0,135,90,0.1);transform:translateY(-4px);}
.help-icon{width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,#00875a,#006644);display:flex;align-items:center;justify-content:center;font-size:28px;color:white;margin:0 auto 20px;}
.help-card h4{font-size:18px;font-weight:700;margin-bottom:12px;color:#0d1f2d;}
.help-card p{font-size:14px;color:#4a6070;line-height:1.6;margin-bottom:20px;}
.help-link{color:var(--primary);font-size:14px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;}
.help-link:hover{color:var(--primary-mid);}
.faq-section{max-width:800px;margin:0 auto;padding-bottom:90px;}
.faq-list{}
.faq-item{border:1px solid #dde5ed;border-radius:12px;margin-bottom:10px;overflow:hidden;background:#ffffff;box-shadow:0 1px 4px rgba(0,0,0,0.04);}
.faq-item.open{border-color:#00875a;}
.faq-question{padding:18px 22px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;font-weight:600;font-size:15px;color:#0d1f2d;transition:all .2s;}
.faq-question:hover{background:#f0f9f5;}
.faq-answer{padding:0 24px;max-height:0;overflow:hidden;transition:all .3s;}
.faq-answer.open{max-height:300px;padding:0 24px 20px;}
.faq-answer p{font-size:14px;color:#3d5166;line-height:1.7;}
.faq-icon{transition:transform .3s;color:var(--primary);}
.faq-item.open .faq-icon{transform:rotate(180deg);}
.guide-grid{display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-bottom:80px;}
.guide-card{background:#ffffff;border:1px solid #dde5ed;border-radius:14px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.05);}
.guide-header{padding:22px 24px;background:#eaf7f2;border-bottom:1px solid #c3e9d9;}
.guide-header h4{font-size:16px;font-weight:700;color:#0d1f2d;display:flex;align-items:center;gap:10px;}
.guide-steps{padding:20px 24px;}
.guide-step{display:flex;gap:14px;margin-bottom:18px;align-items:flex-start;}
.guide-step:last-child{margin-bottom:0;}
.step-dot{width:28px;height:28px;border-radius:50%;background:#d1fae5;border:2px solid #00875a;color:#00875a;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;margin-top:1px;}
.guide-step h5{font-size:14px;font-weight:700;color:#0d1f2d;margin-bottom:3px;}
.guide-step p{font-size:13px;color:#4a6070;}
@media(max-width:768px){.help-grid{grid-template-columns:1fr;}.guide-grid{grid-template-columns:1fr;}}
</style>';
include 'includes/nav.php';
?>

<div class="page-hero">
    <div class="section-container">
        <div class="section-label" style="justify-content:center;"><span>Support</span></div>
        <h1 class="section-title fade-up">How Can We Help?</h1>
        <p class="fade-up" style="transition-delay:.1s;">Guides, manuals, and answers to commonly asked questions to help you get started quickly.</p>
    </div>
</div>

<section class="help-section">
    <div class="section-container">
        <div class="help-grid">
            <div class="help-card fade-up">
                <div class="help-icon"><i class="fas fa-book-open"></i></div>
                <h4>Farmer Guide</h4>
                <p>Learn how to register, log in, view your deliveries, check quality feedback, and download payment statements.</p>
                <a href="#farmer-guide" class="help-link">Read Guide <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="help-card fade-up" style="transition-delay:.1s;">
                <div class="help-icon"><i class="fas fa-cogs"></i></div>
                <h4>Staff Manual</h4>
                <p>Complete reference for staff operations including data entry, quality assessment, payment processing and reports.</p>
                <a href="#staff-guide" class="help-link">Read Manual <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="help-card fade-up" style="transition-delay:.2s;">
                <div class="help-icon"><i class="fas fa-headset"></i></div>
                <h4>Live Support</h4>
                <p>Contact our technical team for assistance. Available Monday–Friday, 8am–5pm EAT. Emergency support for critical issues.</p>
                <a href="contact.php" class="help-link">Contact Us <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="guide-grid" id="farmer-guide">
            <div class="guide-card fade-up">
                <div class="guide-header"><h4>🌾 Farmer Quick Start</h4></div>
                <div class="guide-steps">
                    <div class="guide-step"><div class="step-dot">1</div><div><h5>Register Your Account</h5><p>Click "Register as Farmer" on the homepage. Provide your National ID, phone number, and email.</p></div></div>
                    <div class="guide-step"><div class="step-dot">2</div><div><h5>Log In</h5><p>Use your registered credentials to access the Farmer Portal at farmer/login.php.</p></div></div>
                    <div class="guide-step"><div class="step-dot">3</div><div><h5>View Your Deliveries</h5><p>Navigate to "My Deliveries" to see all your recorded milk submissions with dates and quantities.</p></div></div>
                    <div class="guide-step"><div class="step-dot">4</div><div><h5>Check Quality &amp; Payments</h5><p>Visit "Quality History" for test results, and "Payments" to track what you've been paid.</p></div></div>
                </div>
            </div>
            <div class="guide-card fade-up" style="transition-delay:.1s;" id="staff-guide">
                <div class="guide-header"><h4>👔 Staff Quick Start</h4></div>
                <div class="guide-steps">
                    <div class="guide-step"><div class="step-dot">1</div><div><h5>Log In with Credentials</h5><p>Go to staff/login.php and use your username, password, and cooperative code (GDFC2024).</p></div></div>
                    <div class="guide-step"><div class="step-dot">2</div><div><h5>Record a Milk Delivery</h5><p>Click the "+" button in the top bar. Fill in farmer, session, litres, and quality parameters.</p></div></div>
                    <div class="guide-step"><div class="step-dot">3</div><div><h5>Run AI Analysis</h5><p>From the AI Advisor tab, enter milk parameters to get spoilage risk analysis and recommendations.</p></div></div>
                    <div class="guide-step"><div class="step-dot">4</div><div><h5>Process a Payment</h5><p>Go to Payments, click "Process Payment", select farmer and period, then confirm.</p></div></div>
                </div>
            </div>
        </div>

        <div id="faq">
            <div class="fade-up" style="text-align:center;margin-bottom:40px;">
                <div class="section-label" style="justify-content:center;"><span>FAQ</span></div>
                <h2 class="section-title">Frequently Asked Questions</h2>
            </div>
            <div class="faq-section">
                <div class="faq-list">
                    <?php $faqs = [
                        ['How do I register as a farmer?', 'Click "Register as Farmer" on the homepage. You will need your National ID number, phone number, and email address. Fill in all required fields and submit. Your account will be activated immediately.'],
                        ['How are milk quality grades determined?', 'Quality is assessed based on: temperature (°C), fat content (%), protein (%), pH/acidity, SNF%, density, and water content (adulteration). Grade A requires optimal values across all parameters. The AI system also provides additional insights.'],
                        ['When and how are payments processed?', 'Payments are processed bi-monthly (1st–15th and 16th–31st). Grade A milk is paid at KES 55/litre plus a KES 5 quality bonus. Grade B is KES 45/litre, Grade C is KES 35/litre. Payments are sent via M-Pesa, bank transfer, or cash.'],
                        ['What happens if my milk is rejected?', 'If milk fails quality standards (extreme pH, very high temperature, excessive water content, positive antibiotic test), it will be marked as rejected with a reason provided. You can view this in your farmer dashboard and discuss with the quality inspector.'],
                        ['How do staff log into the system?', 'Staff accounts are pre-configured by the system administrator. Staff must provide their username, password, and the cooperative access code (GDFC2024) to log in. Contact your administrator if you need access credentials.'],
                        ['Can I update my bank details?', 'Yes. Log into your Farmer Portal, go to "My Profile", and update your M-Pesa number or bank account details. Changes take effect from the next payment cycle.'],
                        ['What is the AI Quality Advisor?', 'The AI Advisor analyses milk quality parameters (temperature, fat, pH, protein, etc.) and provides an intelligent verdict on the milk\'s grade, spoilage risk level, and specific recommendations for improving quality.'],
                    ]; foreach($faqs as $i => $f): ?>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(this)"><?= $f[0] ?><i class="fas fa-chevron-down faq-icon"></i></div>
                        <div class="faq-answer"><p><?= $f[1] ?></p></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function toggleFaq(el){
    const item=el.parentElement;
    const answer=item.querySelector('.faq-answer');
    document.querySelectorAll('.faq-item.open').forEach(i=>{if(i!==item){i.classList.remove('open');i.querySelector('.faq-answer').classList.remove('open');}});
    item.classList.toggle('open');
    answer.classList.toggle('open');
}
</script>
<?php include 'includes/footer.php'; ?>
