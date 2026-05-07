<?php $base = isset($isSubdir) ? '../' : ''; ?>

<footer>
    <div class="footer-grid">
        <div class="footer-brand">
            <a href="<?= $base ?>index.php" class="nav-logo" style="text-decoration:none;">
                <div class="logo-icon">🥛</div>
                <div class="logo-text">
                    <h1 style="color:white;">GDMS</h1>
                    <span>Githunguri Dairy</span>
                </div>
            </a>
            <p>A comprehensive digital dairy management platform for Githunguri Dairy Farmers Cooperative Society, Kiambu County, Kenya.</p>
            <div class="footer-socials">
                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h5>Quick Links</h5>
            <ul>
                <li><a href="<?= $base ?>features.php">Features</a></li>
                <li><a href="<?= $base ?>portals.php">Portals</a></li>
                <li><a href="<?= $base ?>about.php">About Us</a></li>
                <li><a href="<?= $base ?>help.php">Help & FAQ</a></li>
                <li><a href="<?= $base ?>contact.php">Contact</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h5>Portals</h5>
            <ul>
                <li><a href="<?= $base ?>farmer/login.php">Farmer Login</a></li>
                <li><a href="<?= $base ?>farmer/register.php">Farmer Register</a></li>
                <li><a href="<?= $base ?>staff/login.php">Staff Login</a></li>
                <li><a href="<?= $base ?>staff/dashboard.php">Staff Dashboard</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h5>Contact</h5>
            <ul>
                <li><a href="tel:+254712345678"><i class="fas fa-phone" style="margin-right:6px;"></i> +254 712 345 678</a></li>
                <li><a href="mailto:info@githunguri.coop"><i class="fas fa-envelope" style="margin-right:6px;"></i> info@githunguri.coop</a></li>
                <li><a href="#"><i class="fas fa-map-marker-alt" style="margin-right:6px;"></i> Githunguri, Kiambu</a></li>
            </ul>
        </div>
    </div>
    <hr class="footer-divider">
    <div class="footer-bottom">
        <p>&copy; 2026 Githunguri Dairy Farmers Cooperative Society. All rights reserved.</p>
        <p>Developed by David Kimani Mburu | Meru University of Science &amp; Technology</p>
    </div>
</footer>

<button class="scroll-top" id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="fas fa-chevron-up"></i>
</button>

<script>
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar');
    const scrollTop = document.getElementById('scrollTop');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
        scrollTop.classList.add('show');
    } else {
        navbar.classList.remove('scrolled');
        scrollTop.classList.remove('show');
    }
});
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
}, { threshold: 0.08 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
function toggleMobileMenu() { alert('Please use the Login buttons above to access the system.'); }
</script>
</body>
</html>
