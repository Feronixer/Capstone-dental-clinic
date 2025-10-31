<footer class="patient-footer">
    <div class="footer-content">
        <div class="footer-grid">
            <!-- About Section -->
            <div class="footer-section">
                <div class="footer-logo">
                    <img src="{{ asset('images/logo5.png') }}" alt="JValera Dental Clinic" class="footer-logo-img">
                    <div>
                        <h3 class="footer-brand">JValera Dental Clinic</h3>
                        <p class="footer-tagline">Your smile is our priority</p>
                    </div>
                </div>
                <p class="footer-description">
                    Providing exceptional dental care with state-of-the-art technology and compassionate service since 2010.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-link" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="social-link" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="social-link" title="Twitter">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="#" class="social-link" title="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h4 class="footer-heading">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('patient-home') }}">Home</a></li>
                    <li><a href="{{ route('patient-calendar') }}">Calendar</a></li>
                    <li><a href="{{ route('patient-announcement') }}">Announcements</a></li>
                    <li><a href="{{ route('patient-record') }}">My Records</a></li>
                    <li><a href="{{ route('patient-about') }}">About Us</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="footer-section">
                <h4 class="footer-heading">Our Services</h4>
                <ul class="footer-services-list">
                    <li>General Dentistry</li>
                    <li>Cosmetic Dentistry</li>
                    <li>Orthodontics</li>
                    <li>Teeth Whitening</li>
                    <li>Emergency Care</li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section">
                <h4 class="footer-heading">Contact Us</h4>
                <ul class="footer-contact">
                    <li>
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>0190 Policarpio St. Gen T. Deleon<br>Valenzuela City</span>
                    </li>
                    <li>
                        <i class="bi bi-telephone-fill"></i>
                        <span>+63 15 622 9695</span>
                    </li>
                    <li>
                        <i class="bi bi-envelope-fill"></i>
                        <span>jvaleradentalclinic@gmail.com</span>
                    </li>
                    <li>
                        <i class="bi bi-clock-fill"></i>
                        <span>Mon-Sat: 9:00 AM - 6:00 PM</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom">
            <p class="copyright">
                &copy; {{ date('Y') }} JValera Dental Clinic. All Rights Reserved.
            </p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <span>•</span>
                <a href="#">Terms of Service</a>
                <span>•</span>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<style>
.patient-footer {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: rgba(255,255,255,0.8);
    margin-top: 4rem;
}

.footer-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 3rem 2rem 1.5rem;
}

.footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

.footer-section h3,
.footer-section h4 {
    color: white;
    margin-bottom: 1rem;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.footer-logo-img {
    height: 50px;
    width: auto;
}

.footer-brand {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
}

.footer-tagline {
    font-size: 0.85rem;
    opacity: 0.8;
    margin: 0;
}

.footer-description {
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

.footer-social {
    display: flex;
    gap: 0.75rem;
}

.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s;
}

.social-link:hover {
    background: #667eea;
    transform: translateY(-3px);
}

.footer-heading {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.footer-links,
.footer-contact,
.footer-services-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-services-list {
    list-style-type: disc;
    padding-left: 1.5rem;
}

.footer-services-list li {
    color: rgba(255,255,255,0.8);
    margin-bottom: 0.75rem;
    line-height: 1.6;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-links a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: all 0.2s;
    display: inline-block;
}

.footer-links a:hover {
    color: white;
    padding-left: 0.5rem;
}

.footer-contact li {
    display: flex;
    align-items: start;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.footer-contact i {
    color: #667eea;
    margin-top: 0.25rem;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.footer-bottom {
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.copyright {
    margin: 0;
    font-size: 0.9rem;
}

.footer-bottom-links {
    display: flex;
    gap: 1rem;
    font-size: 0.9rem;
}

.footer-bottom-links a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: all 0.2s;
}

.footer-bottom-links a:hover {
    color: white;
}

@media (max-width: 1024px) {
    .footer-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .footer-grid {
        grid-template-columns: 1fr;
    }

    .footer-bottom {
        flex-direction: column;
        text-align: center;
    }
}
</style>
