<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ToothTalk - @yield('title', 'Premium Dental Care')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            min-height: 100vh;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 3rem;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2196F3;
            text-decoration: none;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: #2196F3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            font-weight: 800;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .nav-btn.primary {
            background: #2196F3;
            color: white;
        }

        .nav-btn.primary:hover {
            background: #1976D2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
        }

        .nav-btn.secondary {
            background: white;
            color: #2196F3;
            border: 2px solid #2196F3;
        }

        .nav-btn.secondary:hover {
            background: #e3f2fd;
            transform: translateY(-2px);
        }

        /* Footer Section */
        .footer {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            color: white;
            padding: 4rem 3rem 2rem;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-about h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-about .footer-logo {
            width: 35px;
            height: 35px;
            background: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #2196F3;
            font-size: 1rem;
            font-weight: 800;
        }

        .footer-about p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .footer-social a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .footer-social a:hover {
            background: white;
            color: #2196F3;
            transform: translateY(-3px);
        }

        .footer-section h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 0.8rem;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-section ul li a:hover {
            color: white;
            padding-left: 0.5rem;
        }

        .footer-section ul li a i {
            font-size: 0.9rem;
        }

        .footer-contact p {
            color: rgba(255, 255, 255, 1);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            line-height: 1.5;
            font-size: 0.95rem;
        }

        .footer-contact p i {
            font-size: 1.2rem;
            flex-shrink: 0;
            width: 24px;
            text-align: center;
            color: #FFA726;
        }

        .footer-contact p span {
            flex: 1;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
        }

        .footer-bottom p {
            margin: 0;
        }

        @media (max-width: 1024px) {
            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .footer {
                padding: 3rem 1.5rem 1.5rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="{{ url('/') }}" class="logo">
            <div class="logo-icon">TT</div>
            <span>ToothTalk</span>
        </a>
        <div class="nav-links">
            <a href="{{ url('/') }}" class="nav-btn {{ request()->is('/') ? 'primary' : 'secondary' }}">
                <i class="bi bi-house-door-fill"></i>
                Home
            </a>
            <a href="{{ route('announcements') }}" class="nav-btn {{ request()->is('announcements') ? 'primary' : 'secondary' }}">
                <i class="bi bi-megaphone-fill"></i>
                Announcements
            </a>
            <a href="{{ route('about-us') }}" class="nav-btn {{ request()->is('about-us') ? 'primary' : 'secondary' }}">
                <i class="bi bi-info-circle-fill"></i>
                About Us
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <!-- About Section -->
                <div class="footer-about">
                    <h3>
                        <span class="footer-logo">TT</span>
                        ToothTalk
                    </h3>
                    <p>
                        Premium dental care services since 2005. We're committed to providing world-class dental treatments with cutting-edge technology and compassionate care.
                    </p>
                    <div class="footer-social">
                        <a href="#" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" aria-label="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ url('/') }}"><i class="bi bi-chevron-right"></i> Home</a></li>
                        <li><a href="{{ route('about-us') }}"><i class="bi bi-chevron-right"></i> About Us</a></li>
                        <li><a href="{{ route('announcements') }}"><i class="bi bi-chevron-right"></i> Announcements</a></li>
                        <li><a href="{{ route('login') }}"><i class="bi bi-chevron-right"></i> Patient Portal</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="footer-section">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Cosmetic Dentistry</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Laser Dentistry</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Oral Surgery</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Periodontics</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Dental Crowns</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-section footer-contact">
                    <h4>Contact Info</h4>
                    <p>
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Policarpio St. Gen. T. de Leon Valenzuela City</span>
                    </p>
                    <p>
                        <i class="bi bi-telephone-fill"></i>
                        <span>(555) 123-4567</span>
                    </p>
                    <p>
                        <i class="bi bi-envelope-fill"></i>
                        <span>info@toothtalk.com</span>
                    </p>
                    <p>
                        <i class="bi bi-clock-fill"></i>
                        <span>Mon-Fri: 8am-6pm, Sat: 9am-2pm</span>
                    </p>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} ToothTalk Dental Clinic. All rights reserved. | Designed with <i class="bi bi-heart-fill" style="color: #ff5252;"></i> for healthy smiles</p>
            </div>
        </div>
    </footer>
</body>
</html>

