<footer class="patient-footer reveal-element reveal-slide-up">
    <div class="footer-content">
        <div class="footer-grid">
            <!-- About Section -->
            <div class="footer-section reveal-element reveal-fade">
                <div class="footer-logo">
                    <h3 class="footer-brand">
                        <img src="{{ asset('images/logo7.png') }}" alt="ToothTalk" class="footer-logo-img">
                        ToothTalk
                    </h3>
                </div>
                <p class="footer-description">
                    We offer premium dental care services since 2024. Committed to providing utmost care and attention to your dental needs.
                </p>
                <div class="footer-social">
                    <a href="https://www.facebook.com/profile.php?id=61555389276989" class="social-link" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/JValeradentalclinic" class="social-link" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section reveal-element reveal-fade reveal-delay-1">
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
            <div class="footer-section reveal-element reveal-fade reveal-delay-2">
                <h4 class="footer-heading">Our Services</h4>
                <ul class="footer-services-list" style="list-style-type:disc;">
                    <li>General Dentistry</li>
                    <li>Cosmetic Dentistry</li>
                    <li>Orthodontics</li>
                    <li>Teeth Whitening</li>
                    <li>Emergency Care</li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section reveal-element reveal-fade reveal-delay-3">
                <h4 class="footer-heading">Contact Us</h4>
                <ul class="footer-contact">
                    <li>
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>0190 Policarpio St. Gen T. Deleon<br>Valenzuela City</span>
                    </li>
                    <li>
                        <i class="bi bi-telephone-fill"></i>
                        <span>(+63)915 622 9695</span>
                    </li>
                    <li>
                        <i class="bi bi-envelope-fill"></i>
                        <span>jvaleradentalclinic@gmail.com</span>
                    </li>
                    <li>
                        <i class="bi bi-clock-fill"></i>
                        <span>Tuesday-Saturday: 11:00 AM - 6:00 PM</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom reveal-element reveal-fade">
            <p class="copyright">
                &copy; {{ date('Y') }} Dental Clinic. All Rights Reserved.
            </p>
            <div class="footer-bottom-links">
                <a href="#" data-bs-toggle="modal" data-bs-target="#privacyPolicyModal">Privacy Policy</a>
                <span>•</span>
                <a href="#" data-bs-toggle="modal" data-bs-target="#termsOfServiceModal">Terms of Service</a>
                <span>•</span>
                <a href="{{ route('patient-development-team') }}">Development Team</a>
            </div>
        </div>
    </div>
</footer>

<style>
.patient-footer {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: rgba(255,255,255,0.8);
    margin-top: 2.5rem;
}

.footer-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1.25rem 1.25rem 0.5rem;
    padding-right: calc(1.25rem + 100px); /* Add space for chatbot button */
}

.footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 1.5rem;
    margin-bottom: 0.75rem;
}

.footer-section h3,
.footer-section h4 {
    color: white;
    margin-bottom: 0.5rem;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.footer-logo h3 {
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    color: white;
}

.footer-logo-img {
    width: 40px;
    height: 40px;
    object-fit: contain;
    display: inline-block;
}

.footer-tagline {
    font-size: 0.85rem;
    opacity: 0.8;
    margin: 0;
}

.footer-description {
    line-height: 1.4;
    margin-bottom: 0.75rem;
    font-size: 0.8rem;
    max-width: 280px;
    text-align: left;
}

.footer-social {
    display: flex;
    gap: 0.4rem;
}

.social-link {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s;
    font-size: 0.85rem;
}

.social-link:hover {
    background: #667eea;
    transform: translateY(-3px);
}

.footer-heading {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
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
    padding-left: 1rem;
}

.footer-services-list li {
    color: rgba(255,255,255,0.8);
    margin-bottom: 0.375rem;
    line-height: 1.4;
    font-size: 0.8rem;
}

.footer-links li {
    margin-bottom: 0.375rem;
}

.footer-links a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: all 0.2s;
    display: inline-block;
    font-size: 0.8rem;
}

.footer-links a:hover {
    color: white;
    padding-left: 0.5rem;
}

.footer-contact li {
    display: flex;
    align-items: start;
    gap: 0.4rem;
    margin-bottom: 0.5rem;
}

.footer-contact i {
    color: #667eea;
    margin-top: 0.2rem;
    font-size: 0.85rem;
    flex-shrink: 0;
}

.footer-contact span {
    font-size: 0.8rem;
    line-height: 1.4;
}

.footer-bottom {
    padding-top: 0.5rem;
    border-top: 1px solid rgba(255,255,255,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    max-width: 100%;
    padding-right: 0;
}

.footer-bottom .copyright {
    flex: 0 0 auto;
}

.copyright {
    margin: 0;
    font-size: 0.75rem;
}

.footer-bottom-links {
    display: flex;
    gap: 0.5rem;
    font-size: 0.75rem;
    flex: 0 0 auto;
    margin-right: 20px; /* Add space from chatbot button */
    padding-right: 0;
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
        gap: 1.25rem;
    }
    
    .footer-content {
        padding-right: 1.25rem;
    }
}

@media (max-width: 768px) {
    .footer-content {
        padding: 1rem 1rem 0.5rem;
        padding-right: 1rem;
    }
    
    .footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    /* About Section - full width */
    .footer-grid .footer-section:first-child {
        grid-column: 1 / -1;
    }
    
    /* Contact Section - full width */
    .footer-grid .footer-section:last-child {
        grid-column: 1 / -1;
    }
    
    .footer-section {
        margin-bottom: 0.5rem;
    }
    
    .footer-logo {
        justify-content: flex-start;
    }
    
    .footer-logo h3 {
        font-size: 1.1rem;
    }
    
    .footer-logo-img {
        width: 35px;
        height: 35px;
    }
    
    .footer-description {
        font-size: 0.75rem;
        max-width: 100%;
        text-align: left;
    }
    
    .footer-heading {
        font-size: 0.8rem;
        margin-bottom: 0.4rem;
        text-align: left;
    }
    
    .footer-links,
    .footer-services-list {
        text-align: left;
    }
    
    /* Quick Links section - move to the right */
    .footer-grid .footer-section:nth-child(2) {
        padding-left: 0.75rem;
    }
    
    .footer-links a,
    .footer-contact span,
    .footer-services-list li {
        font-size: 0.75rem;
    }
    
    .footer-contact {
        align-items: flex-start;
        text-align: left;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem 0.75rem;
    }
    
    .footer-contact li {
        align-items: flex-start;
        text-align: left;
        margin-bottom: 0;
    }
    
    .footer-contact i {
        font-size: 0.8rem;
    }
    
    .footer-social {
        justify-content: flex-start;
    }
    
    .footer-bottom {
        flex-direction: column;
        text-align: left;
        gap: 0.75rem;
        padding-top: 0.75rem;
    }
    
    .footer-bottom-links {
        flex-direction: row;
        gap: 0.5rem;
        margin-right: 0;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .footer-bottom-links span {
        display: inline;
        color: rgba(255,255,255,0.6);
    }
    
    .copyright {
        font-size: 0.7rem;
    }
    
    .footer-bottom-links a {
        font-size: 0.7rem;
    }
}

@media (max-width: 640px) {
    .footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    /* About Section - full width */
    .footer-grid .footer-section:first-child {
        grid-column: 1 / -1;
    }
    
    /* Contact Section - full width */
    .footer-grid .footer-section:last-child {
        grid-column: 1 / -1;
    }
    
    .footer-content {
        padding: 1rem 0.75rem 0.5rem;
    }
    
    .footer-section {
        margin-bottom: 1rem;
    }
    
    .footer-logo {
        justify-content: flex-start;
        margin-bottom: 0.75rem;
    }
    
    .footer-description {
        text-align: left;
        margin-bottom: 1rem;
    }
    
    .footer-social {
        justify-content: flex-start;
        margin-top: 0.5rem;
    }
    
    .footer-heading {
        text-align: left;
        margin-bottom: 0.5rem;
    }
    
    .footer-links {
        text-align: left;
    }
    
    /* Quick Links section - move to the right */
    .footer-grid .footer-section:nth-child(2) {
        padding-left: 0.75rem;
    }
    
    .footer-services-list {
        text-align: left;
        padding-left: 1rem;
        list-style-position: outside;
    }
    
    .footer-contact {
        align-items: flex-start;
        text-align: left;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem 0.75rem;
    }
    
    .footer-contact li {
        flex-direction: row;
        align-items: flex-start;
        text-align: left;
        gap: 0.4rem;
        margin-bottom: 0;
    }
    
    .footer-contact i {
        margin-top: 0.2rem;
    }
    
    .footer-bottom {
        flex-direction: column;
        text-align: left;
        gap: 0.75rem;
    }
    
    .footer-bottom-links {
        flex-direction: row;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .footer-bottom-links span {
        display: inline;
        color: rgba(255,255,255,0.6);
    }
}

@media (max-width: 480px) {
    .footer-content {
        padding: 0.75rem 0.5rem 0.5rem;
    }
    
    .footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    
    /* About Section - full width */
    .footer-grid .footer-section:first-child {
        grid-column: 1 / -1;
    }
    
    /* Contact Section - full width */
    .footer-grid .footer-section:last-child {
        grid-column: 1 / -1;
    }
    
    .footer-logo h3 {
        font-size: 1rem;
    }
    
    .footer-logo-img {
        width: 30px;
        height: 30px;
    }
    
    .footer-description {
        font-size: 0.7rem;
        line-height: 1.5;
    }
    
    .footer-heading {
        font-size: 0.75rem;
    }
    
    .footer-links a,
    .footer-contact span,
    .footer-services-list li {
        font-size: 0.7rem;
    }
    
    /* Quick Links section - move to the right */
    .footer-grid .footer-section:nth-child(2) {
        padding-left: 0.5rem;
    }
    
    .footer-contact {
        grid-template-columns: 1fr 1fr;
        gap: 0.4rem 0.5rem;
    }
    
    .social-link {
        width: 28px;
        height: 28px;
        font-size: 0.8rem;
    }
    
    .copyright,
    .footer-bottom-links a {
        font-size: 0.65rem;
    }
    
    .footer-bottom {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .footer-bottom-links {
        flex-direction: row;
        gap: 0.4rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .footer-bottom-links span {
        display: inline;
        color: rgba(255,255,255,0.6);
    }
}
</style>

<!-- Privacy Policy Modal -->
<div class="modal fade policy-modal" id="privacyPolicyModal" tabindex="-1" aria-labelledby="privacyPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content policy-modal-content">
            <div class="modal-header policy-modal-header">
                <h5 class="modal-title policy-modal-title" id="privacyPolicyModalLabel">
                    <i class="bi bi-shield-lock-fill me-2"></i>Privacy Policy
                </h5>
                <button type="button" class="btn-close policy-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body policy-modal-body">
                <div class="policy-content">
                    <p class="policy-intro">Last Updated: {{ date('F d, Y') }}</p>
                    
                    <p>At JValera Dental Clinic, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our ToothTalk dental clinic management system.</p>

                    <h3 class="policy-section-title">1. Information We Collect</h3>
                    <p>We collect information that you provide directly to us, including:</p>
                    <ul class="policy-list">
                        <li><strong>Personal Information:</strong> Name, date of birth, contact information (phone, email, address), gender, and other demographic information</li>
                        <li><strong>Medical Information:</strong> Dental health records, treatment history, medical conditions, medications, and allergies</li>
                        <li><strong>Appointment Information:</strong> Appointment dates, times, services requested, and appointment status</li>
                        <li><strong>Account Information:</strong> Username, password, and account preferences</li>
                    </ul>

                    <h3 class="policy-section-title">2. How We Use Your Information</h3>
                    <p>We use the information we collect to:</p>
                    <ul class="policy-list">
                        <li>Provide, maintain, and improve our dental services</li>
                        <li>Schedule and manage your appointments</li>
                        <li>Maintain accurate dental and medical records</li>
                        <li>Send appointment reminders, notifications, and important updates</li>
                        <li>Respond to your inquiries and provide customer support</li>
                        <li>Comply with legal obligations and healthcare regulations</li>
                        <li>Ensure the security and integrity of our system</li>
                    </ul>

                    <h3 class="policy-section-title">3. Information Sharing and Disclosure</h3>
                    <p>We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                    <ul class="policy-list">
                        <li><strong>Healthcare Providers:</strong> With authorized dental and medical professionals involved in your care</li>
                        <li><strong>Service Providers:</strong> With trusted third-party service providers who assist us in operating our system (e.g., cloud hosting)</li>
                        <li><strong>Legal Requirements:</strong> When required by law, court order, or government regulation</li>
                        <li><strong>Emergency Situations:</strong> To protect your health and safety or that of others</li>
                        <li><strong>With Your Consent:</strong> When you explicitly authorize us to share your information</li>
                    </ul>

                    <h3 class="policy-section-title">4. Data Security</h3>
                    <p>We implement industry-standard security measures to protect your information, including:</p>
                    <ul class="policy-list">
                        <li>Encryption of sensitive data in transit and at rest</li>
                        <li>Secure password requirements and authentication</li>
                        <li>Regular security audits and updates</li>
                        <li>Access controls and user authentication</li>
                        <li>Secure backup and disaster recovery procedures</li>
                    </ul>
                    <p>However, no method of transmission over the internet or electronic storage is 100% secure, and we cannot guarantee absolute security.</p>

                    <h3 class="policy-section-title">5. Your Rights and Choices</h3>
                    <p>You have the right to:</p>
                    <ul class="policy-list">
                        <li>Access and review your personal and medical information</li>
                        <li>Request corrections to inaccurate or incomplete information</li>
                        <li>Request deletion of your account and information (subject to legal retention requirements)</li>
                        <li>Opt-out of non-essential communications</li>
                        <li>Request a copy of your medical records</li>
                    </ul>
                    <p>To exercise these rights, please contact us using the information provided in the "Contact Us" section below.</p>

                    <h3 class="policy-section-title">6. Data Retention</h3>
                    <p>We retain your information for as long as necessary to provide our services and comply with legal obligations. Medical records are retained in accordance with applicable healthcare regulations and may be kept for extended periods as required by law.</p>

                    <h3 class="policy-section-title">7. Children's Privacy</h3>
                    <p>Our services are not intended for children under the age of 18 without parental consent. We do not knowingly collect personal information from children without appropriate parental authorization.</p>

                    <h3 class="policy-section-title">8. Changes to This Privacy Policy</h3>
                    <p>We may update this Privacy Policy from time to time. We will notify you of any material changes by posting the new Privacy Policy on this page and updating the "Last Updated" date. We encourage you to review this Privacy Policy periodically.</p>

                    <h3 class="policy-section-title">9. Contact Us</h3>
                    <p>If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:</p>
                    <ul class="policy-list">
                        <li><strong>Email:</strong> jvaleradentalclinic@gmail.com</li>
                        <li><strong>Phone:</strong> (+63)915 622 9695</li>
                        <li><strong>Address:</strong> 0190 Policarpio St. Gen T. Deleon, Valenzuela City</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms of Service Modal -->
<div class="modal fade policy-modal" id="termsOfServiceModal" tabindex="-1" aria-labelledby="termsOfServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content policy-modal-content">
            <div class="modal-header policy-modal-header">
                <h5 class="modal-title policy-modal-title" id="termsOfServiceModalLabel">
                    <i class="bi bi-file-text-fill me-2"></i>Terms of Service
                </h5>
                <button type="button" class="btn-close policy-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body policy-modal-body">
                <div class="policy-content">
                    <p class="policy-intro">Last Updated: {{ date('F d, Y') }}</p>
                    
                    <p>Welcome to ToothTalk, the dental clinic management system operated by JValera Dental Clinic. By accessing or using our system, you agree to be bound by these Terms of Service. Please read them carefully.</p>

                    <h3 class="policy-section-title">1. Acceptance of Terms</h3>
                    <p>By creating an account, accessing, or using the ToothTalk system, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service and our Privacy Policy. If you do not agree to these terms, please do not use our system.</p>

                    <h3 class="policy-section-title">2. Description of Service</h3>
                    <p>ToothTalk is a web-based dental clinic management system that provides patients with the ability to:</p>
                    <ul class="policy-list">
                        <li>Schedule and manage dental appointments</li>
                        <li>View and access personal dental records and treatment history</li>
                        <li>Receive appointment reminders and notifications</li>
                        <li>Communicate with the dental clinic</li>
                        <li>View announcements and clinic information</li>
                    </ul>

                    <h3 class="policy-section-title">3. User Accounts</h3>
                    <p>To use certain features of our system, you must create an account. You agree to:</p>
                    <ul class="policy-list">
                        <li>Provide accurate, current, and complete information during registration</li>
                        <li>Maintain and update your account information to keep it accurate</li>
                        <li>Maintain the security of your password and account</li>
                        <li>Accept responsibility for all activities that occur under your account</li>
                        <li>Notify us immediately of any unauthorized use of your account</li>
                        <li>Not share your account credentials with others</li>
                    </ul>

                    <h3 class="policy-section-title">4. Appointment Scheduling and Cancellation</h3>
                    <p>When scheduling appointments through our system:</p>
                    <ul class="policy-list">
                        <li>Appointments are subject to availability and clinic approval</li>
                        <li>You must provide accurate information when scheduling</li>
                        <li>Cancellations should be made at least 24 hours in advance when possible</li>
                        <li>Repeated no-shows or late cancellations may result in restrictions on future bookings</li>
                        <li>The clinic reserves the right to reschedule or cancel appointments when necessary</li>
                    </ul>

                    <h3 class="policy-section-title">5. Medical Information and Records</h3>
                    <p>You understand and agree that:</p>
                    <ul class="policy-list">
                        <li>All medical and dental information provided is accurate and complete</li>
                        <li>You are responsible for updating your medical information as needed</li>
                        <li>Medical records are maintained in accordance with healthcare regulations</li>
                        <li>Access to your records is provided for informational purposes and does not replace professional medical advice</li>
                        <li>The clinic maintains ownership of medical records as required by law</li>
                    </ul>

                    <h3 class="policy-section-title">6. Payment Terms</h3>
                    <p>Payment terms and conditions:</p>
                    <ul class="policy-list">
                        <li>Payment is due at the time of service unless other arrangements have been made</li>
                        <li>Accepted payment methods will be communicated by the clinic</li>
                        <li>All fees and charges are non-refundable unless otherwise stated</li>
                        <li>You are responsible for all charges incurred under your account</li>
                        <li>Billing disputes must be reported within 30 days of the billing date</li>
                    </ul>

                    <h3 class="policy-section-title">7. Prohibited Uses</h3>
                    <p>You agree not to:</p>
                    <ul class="policy-list">
                        <li>Use the system for any illegal or unauthorized purpose</li>
                        <li>Attempt to gain unauthorized access to the system or other users' accounts</li>
                        <li>Interfere with or disrupt the system's operation or security</li>
                        <li>Transmit any viruses, malware, or harmful code</li>
                        <li>Use automated systems to access the system without permission</li>
                        <li>Impersonate any person or entity</li>
                        <li>Violate any applicable laws or regulations</li>
                    </ul>

                    <h3 class="policy-section-title">8. Intellectual Property</h3>
                    <p>The ToothTalk system, including its design, features, and content, is the property of JValera Dental Clinic and is protected by copyright, trademark, and other intellectual property laws. You may not copy, modify, distribute, or create derivative works without our express written permission.</p>

                    <h3 class="policy-section-title">9. System Availability and Modifications</h3>
                    <p>We strive to maintain system availability but do not guarantee uninterrupted access. We reserve the right to:</p>
                    <ul class="policy-list">
                        <li>Modify, suspend, or discontinue any part of the system at any time</li>
                        <li>Perform maintenance that may temporarily affect system availability</li>
                        <li>Update features and functionality as needed</li>
                    </ul>

                    <h3 class="policy-section-title">10. Limitation of Liability</h3>
                    <p>To the maximum extent permitted by law, JValera Dental Clinic shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including loss of data, profits, or business opportunities, arising from your use of the system.</p>

                    <h3 class="policy-section-title">11. Indemnification</h3>
                    <p>You agree to indemnify and hold harmless JValera Dental Clinic, its employees, and agents from any claims, damages, losses, or expenses arising from your use of the system or violation of these Terms of Service.</p>

                    <h3 class="policy-section-title">12. Termination</h3>
                    <p>We reserve the right to suspend or terminate your account at any time, with or without notice, for violation of these Terms of Service or for any other reason we deem necessary. You may also terminate your account at any time by contacting us.</p>

                    <h3 class="policy-section-title">13. Changes to Terms</h3>
                    <p>We may modify these Terms of Service at any time. We will notify you of material changes by posting the updated terms on this page and updating the "Last Updated" date. Your continued use of the system after changes are posted constitutes acceptance of the modified terms.</p>

                    <h3 class="policy-section-title">14. Governing Law</h3>
                    <p>These Terms of Service shall be governed by and construed in accordance with the laws of the Philippines, without regard to its conflict of law provisions.</p>

                    <h3 class="policy-section-title">15. Contact Information</h3>
                    <p>If you have any questions about these Terms of Service, please contact us:</p>
                    <ul class="policy-list">
                        <li><strong>Email:</strong> jvaleradentalclinic@gmail.com</li>
                        <li><strong>Phone:</strong> (+63)915 622 9695</li>
                        <li><strong>Address:</strong> 0190 Policarpio St. Gen T. Deleon, Valenzuela City</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Policy Modal Styles */
.policy-modal .modal-dialog {
    max-width: 800px;
    margin: 1.75rem auto;
    display: flex;
    align-items: center;
    min-height: calc(100% - 3.5rem);
}

.policy-modal-content {
    border-radius: 16px;
    border: none;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.policy-modal-header {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    padding: 1.5rem;
    border-bottom: none;
}

.policy-modal-title {
    color: white;
    font-weight: 700;
    font-size: 1.5rem;
    margin: 0;
    display: flex;
    align-items: center;
}

.policy-modal-title i {
    font-size: 1.5rem;
}

.policy-modal-close {
    background: rgba(255, 255, 255, 0.2) !important;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1 !important;
    transition: all 0.3s ease;
    border: none;
    padding: 0;
    margin: 0;
    position: relative;
    z-index: 10;
}

.policy-modal-close .btn-close::before,
.policy-modal-close::after {
    display: none !important;
}

.policy-modal-close::before {
    content: '×';
    color: white !important;
    font-size: 1.75rem;
    font-weight: 300;
    line-height: 1;
    display: block;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 11;
}

.policy-modal-close:hover {
    background: rgba(255, 255, 255, 0.3) !important;
    transform: rotate(90deg);
}

.policy-modal-close:hover::before {
    color: white !important;
}

.policy-modal-body {
    padding: 2rem;
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

.policy-content {
    color: #1e293b;
    line-height: 1.8;
}

.policy-intro {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    font-style: italic;
}

.policy-content p {
    margin-bottom: 1.25rem;
    font-size: 1rem;
}

.policy-section-title {
    color: #1e293b;
    font-size: 1.25rem;
    font-weight: 700;
    margin-top: 2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.policy-list {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.policy-list li {
    margin-bottom: 0.75rem;
    line-height: 1.7;
    font-size: 0.95rem;
}

.policy-list li strong {
    color: #3b82f6;
    font-weight: 600;
}


/* Dark Mode Styles for Policy Modal */
[data-theme="dark"] .policy-modal-content {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .policy-modal-header {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
}

[data-theme="dark"] .policy-modal-body {
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .policy-content {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .policy-intro {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .policy-section-title {
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .policy-list li {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .policy-list li strong {
    color: #60a5fa !important;
}


[data-theme="dark"] .policy-modal-close {
    background: rgba(255, 255, 255, 0.2) !important;
}

[data-theme="dark"] .policy-modal-close::before {
    color: white !important;
}

[data-theme="dark"] .policy-modal-close:hover {
    background: rgba(255, 255, 255, 0.3) !important;
}

[data-theme="dark"] .policy-modal-close:hover::before {
    color: white !important;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .policy-modal .modal-dialog {
        max-width: 95%;
        margin: 1rem auto;
        min-height: calc(100% - 2rem);
    }

    .policy-modal-header {
        padding: 1.25rem;
    }

    .policy-modal-title {
        font-size: 1.25rem;
    }

    .policy-modal-body {
        padding: 1.5rem;
        max-height: calc(100vh - 180px);
    }

    .policy-section-title {
        font-size: 1.1rem;
        margin-top: 1.5rem;
    }

    .policy-content p {
        font-size: 0.95rem;
    }

    .policy-list {
        padding-left: 1.25rem;
    }

    .policy-list li {
        font-size: 0.9rem;
    }

}

@media (max-width: 576px) {
    .policy-modal .modal-dialog {
        max-width: 100%;
        margin: 0;
        height: 100vh;
        max-height: 100vh;
    }

    .policy-modal-content {
        border-radius: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .policy-modal-body {
        flex: 1;
        overflow-y: auto;
        max-height: none;
        padding: 1.25rem;
    }

    .policy-modal-header {
        padding: 1rem;
    }

    .policy-modal-title {
        font-size: 1.1rem;
    }

    .policy-section-title {
        font-size: 1rem;
    }

    .policy-content p {
        font-size: 0.9rem;
    }

    .policy-list li {
        font-size: 0.85rem;
    }
}

/* Scrollbar Styling for Modal Body */
.policy-modal-body::-webkit-scrollbar {
    width: 8px;
}

.policy-modal-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.policy-modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.policy-modal-body::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

[data-theme="dark"] .policy-modal-body::-webkit-scrollbar-track {
    background: var(--dm-bg-secondary, #0f172a) !important;
}

[data-theme="dark"] .policy-modal-body::-webkit-scrollbar-thumb {
    background: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .policy-modal-body::-webkit-scrollbar-thumb:hover {
    background: #475569 !important;
}
</style>
