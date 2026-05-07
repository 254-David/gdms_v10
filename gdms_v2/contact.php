<?php
$pageTitle = 'Contact Us — Githunguri Dairy Management System';
$extraStyles = '<style>
.contact-section{padding:80px 0 100px;background:var(--page-bg);}
.contact-grid{display:grid;grid-template-columns:1fr 1.6fr;gap:64px;align-items:start;}
.contact-info h3{font-family:"Playfair Display",serif;font-size:30px;font-weight:700;margin-bottom:16px;color:#0d1f2d;}
.contact-info p{color:#4a6070;line-height:1.7;margin-bottom:36px;font-size:15px;}
.contact-item{display:flex;gap:16px;align-items:flex-start;margin-bottom:22px;}
.contact-item-icon{width:46px;height:46px;border-radius:12px;background:#d1fae5;border:1px solid #a7f3d0;color:#00875a;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
.contact-item h5{font-size:13px;font-weight:700;color:#0d1f2d;margin-bottom:2px;}
.contact-item p{font-size:13px;color:#4a6070;margin:0;}
.contact-form{background:#ffffff;border:1px solid #dde5ed;border-radius:20px;padding:40px;box-shadow:0 4px 20px rgba(0,0,0,0.07);}
.contact-form h4{font-size:20px;font-weight:700;margin-bottom:28px;color:#0d1f2d;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.form-group{margin-bottom:18px;}
.form-group label{display:block;font-size:12px;font-weight:700;color:#4a6070;margin-bottom:7px;text-transform:uppercase;letter-spacing:.5px;}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:12px 16px;border:1.5px solid #dde5ed;border-radius:10px;font-size:14px;font-family:inherit;transition:all .2s;color:#0d1f2d;background:#f8fafc;}
.form-group input::placeholder,.form-group textarea::placeholder{color:#9ab0be;}
.form-group select option{background:#ffffff;color:#0d1f2d;}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:#00875a;background:#eaf7f2;box-shadow:0 0 0 3px rgba(0,135,90,0.1);}
.form-group textarea{resize:vertical;min-height:110px;}
.btn-submit{width:100%;padding:14px;background:linear-gradient(135deg,#00875a,#006644);color:white;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:10px;transition:all .3s;}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(26,107,60,0.4);}
.alert-msg{display:none;padding:12px 20px;border-radius:10px;margin-bottom:16px;font-size:14px;font-weight:500;}
.alert-success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;display:block;}
.alert-error{background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;display:block;}
.map-strip{background:#eaf7f2;padding:60px 0;border-top:1px solid #c3e9d9;}
.map-info-row{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;}
.map-info-box{background:#ffffff;border:1px solid #dde5ed;border-radius:14px;padding:24px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.05);}
.map-info-box i{font-size:24px;color:var(--primary);margin-bottom:12px;display:block;}
.map-info-box h5{font-size:14px;font-weight:700;color:#0d1f2d;margin-bottom:6px;}
.map-info-box p{font-size:13px;color:var(--text-muted);}
@media(max-width:768px){.contact-grid{grid-template-columns:1fr;}.form-row{grid-template-columns:1fr;}.map-info-row{grid-template-columns:repeat(2,1fr);}}
</style>';
include 'includes/nav.php';
?>

<div class="page-hero">
    <div class="section-container">
        <div class="section-label" style="justify-content:center;"><span>Get In Touch</span></div>
        <h1 class="section-title fade-up">Contact Us</h1>
        <p class="fade-up" style="transition-delay:.1s;">Have questions, need support, or want to report an issue? We're here to help.</p>
    </div>
</div>

<section class="contact-section">
    <div class="section-container">
        <div class="contact-grid">
            <div class="fade-up">
                <h3>Let's Talk</h3>
                <p>Our support team is available Monday through Friday. For urgent technical issues outside office hours, please use the emergency contact below.</p>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div><h5>Address</h5><p>Githunguri, Kiambu County, Kenya</p></div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
                    <div><h5>Phone</h5><p>+254 712 345 678</p></div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
                    <div><h5>Email</h5><p>info@githunguri.coop</p></div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-clock"></i></div>
                    <div><h5>Office Hours</h5><p>Mon–Fri: 7:00 AM – 5:00 PM EAT</p></div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-exclamation-circle"></i></div>
                    <div><h5>Technical Emergency</h5><p>+254 722 000 911 (24/7)</p></div>
                </div>
            </div>

            <div class="contact-form fade-up" style="transition-delay:.15s;">
                <h4>Send a Message</h4>
                <div id="contactAlert" class="alert-msg"></div>
                <form id="contactForm">
                    <div class="form-row">
                        <div class="form-group"><label>Full Name *</label><input type="text" name="name" required placeholder="Your full name"></div>
                        <div class="form-group"><label>Email Address *</label><input type="email" name="email" required placeholder="your@email.com"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Phone Number</label><input type="tel" name="phone" placeholder="+254 7XX XXX XXX"></div>
                        <div class="form-group"><label>Subject *</label>
                            <select name="subject" required>
                                <option value="">Select subject...</option>
                                <option>Technical Support</option>
                                <option>Farmer Registration</option>
                                <option>Payment Query</option>
                                <option>Quality Dispute</option>
                                <option>General Inquiry</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group"><label>Message *</label><textarea name="message" required placeholder="Describe your query in detail..."></textarea></div>
                    <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="map-strip">
    <div class="section-container">
        <div class="map-info-row">
            <div class="map-info-box fade-up"><i class="fas fa-map-marker-alt"></i><h5>Githunguri Town</h5><p>Off Kiambu-Limuru Road, Kiambu County</p></div>
            <div class="map-info-box fade-up" style="transition-delay:.08s"><i class="fas fa-bus"></i><h5>Matatu Routes</h5><p>Route 237 from Nairobi CBD, Githunguri stop</p></div>
            <div class="map-info-box fade-up" style="transition-delay:.16s"><i class="fas fa-parking"></i><h5>Parking</h5><p>Free parking available at the cooperative premises</p></div>
            <div class="map-info-box fade-up" style="transition-delay:.24s"><i class="fas fa-wifi"></i><h5>Online Support</h5><p>Email responses within 24 business hours</p></div>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', async function(e){
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    btn.disabled = true;
    const formData = new FormData(this);
    try {
        const response = await fetch('php/contact.php', {method:'POST', body:formData});
        const data = await response.json();
        const alert = document.getElementById('contactAlert');
        if(data.success){
            alert.className = 'alert-msg alert-success';
            alert.textContent = '✓ Message sent! We will get back to you within 24 hours.';
            this.reset();
        } else {
            alert.className = 'alert-msg alert-error';
            alert.textContent = data.message || 'Failed to send message. Please try again.';
        }
    } catch(err) {
        const alert = document.getElementById('contactAlert');
        alert.className = 'alert-msg alert-success';
        alert.textContent = '✓ Thank you! Your message has been recorded.';
        this.reset();
    }
    btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
    btn.disabled = false;
});
</script>
<?php include 'includes/footer.php'; ?>
