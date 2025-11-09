<header class="patient-header">
    <div class="header-container">
        <!-- Logo Section -->
        <a href="{{ route('patient-dashboard') }}" class="header-logo" style="text-decoration: none;">
            <img src="{{ asset('images/logo4.png') }}" alt="ToothTalk" class="logo-img">
            <div class="logo-text">
                <span class="clinic-name">Tooth<span class="clinic-name-talk">Talk</span></span>
            </div>
        </a>

        <!-- Navigation Menu -->
        <nav class="header-nav">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('patient-dashboard') }}" class="nav-link {{ request()->routeIs('patient-dashboard') || request()->routeIs('patient-home') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('patient-calendar') }}" class="nav-link {{ request()->routeIs('patient-calendar') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i>
                        <span>Calendar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('patient-announcement') }}" class="nav-link {{ request()->routeIs('patient-announcement') ? 'active' : '' }}">
                        <i class="bi bi-megaphone"></i>
                        <span>Announcements</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('patient-record') }}" class="nav-link {{ request()->routeIs('patient-record') ? 'active' : '' }}">
                        <i class="bi bi-file-medical"></i>
                        <span>My Records</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('patient-about') }}" class="nav-link {{ request()->routeIs('patient-about') ? 'active' : '' }}">
                        <i class="bi bi-info-circle"></i>
                        <span>About Us</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Right Side Container -->
        <div class="header-container-right">
            <!-- User Actions -->
            <div class="header-actions">
            <!-- Notifications -->
            <div class="dropdown">
                <button class="icon-btn" type="button" data-bs-toggle="dropdown" id="notificationDropdownBtn">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-dropdown" id="notificationDropdown">
                    <div class="dropdown-header">
                        <h6 class="mb-0">Notifications</h6>
                        <small class="text-muted" id="notificationSubtitle">Loading...</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div id="notificationsList">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('patient-notifications') }}" class="dropdown-item text-center text-primary">
                        <small>View All Notifications</small>
                    </a>
                </div>
            </div>

            <!-- User Profile -->
            <div class="dropdown">
                <button class="user-profile-btn" type="button" data-bs-toggle="dropdown">
                    <div class="profile-avatar">
                        @if(auth()->user()->info && auth()->user()->info->first_name && auth()->user()->info->last_name)
                            {{ strtoupper(substr(auth()->user()->info->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->info->last_name, 0, 1)) }}
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->name, 1, 1)) }}
                        @endif
                    </div>
                    <div class="user-info">
                        <span class="user-name">
                            @if(auth()->user()->info)
                                {{ trim(auth()->user()->info->first_name . ' ' . auth()->user()->info->last_name) }}
                            @else
                                {{ auth()->user()->name ?? 'User' }}
                            @endif
                        </span>
                        <small class="user-role">Patient</small>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                    <div class="dropdown-header text-center">
                        <div class="profile-avatar-lg">
                            @if(auth()->user()->info && auth()->user()->info->first_name && auth()->user()->info->last_name)
                                {{ strtoupper(substr(auth()->user()->info->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->info->last_name, 0, 1)) }}
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->name, 1, 1)) }}
                            @endif
                        </div>
                        <h6 class="mt-2 mb-0">
                            @if(auth()->user()->info)
                                {{ trim(auth()->user()->info->first_name . ' ' . auth()->user()->info->last_name) }}
                            @else
                                {{ auth()->user()->name ?? 'User' }}
                            @endif
                        </h6>
                        <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('patient-profile') }}">
                        <i class="bi bi-person me-2"></i>My Profile
                    </a>
                    <div class="dropdown-item d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center">
                            <i id="dmLabelIcon" class="bi bi-moon-stars me-2"></i>
                            <span id="dmLabelText">Light Mode</span>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="patientDarkModeSwitch">
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#" id="patientLogoutBtn">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('patient.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Open menu">
                <span class="burger-icon" id="mobileMenuToggleIcon">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Backdrop -->
    <div class="mobile-menu-backdrop" id="mobileMenuBackdrop" aria-hidden="true"></div>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" aria-hidden="true">
        <div class="mobile-menu-header">
            <a href="{{ route('patient-dashboard') }}" class="header-logo" style="text-decoration: none;">
                <img src="{{ asset('images/logo4.png') }}" alt="ToothTalk" class="logo-img">
                <div class="logo-text">
                    <span class="clinic-name">Tooth<span class="clinic-name-talk">Talk</span></span>
                </div>
            </a>
        </div>

        <!-- User Profile Info at Top -->
        <div class="mobile-user-info-section">
            <div class="profile-avatar-lg">
                @if(auth()->user()->info && auth()->user()->info->first_name && auth()->user()->info->last_name)
                    {{ strtoupper(substr(auth()->user()->info->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->info->last_name, 0, 1)) }}
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->name, 1, 1)) }}
                @endif
            </div>
            <div class="mobile-user-info-text">
                <h6 class="mb-0">
                    @if(auth()->user()->info)
                        {{ trim(auth()->user()->info->first_name . ' ' . auth()->user()->info->last_name) }}
                    @else
                        {{ auth()->user()->name ?? 'User' }}
                    @endif
                </h6>
                <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="mobile-nav">
            <ul class="mobile-nav-menu">
                <li class="mobile-nav-item">
                    <a href="{{ route('patient-dashboard') }}" class="mobile-nav-link {{ request()->routeIs('patient-dashboard') || request()->routeIs('patient-home') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('patient-calendar') }}" class="mobile-nav-link {{ request()->routeIs('patient-calendar') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i>
                        <span>Calendar</span>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('patient-announcement') }}" class="mobile-nav-link {{ request()->routeIs('patient-announcement') ? 'active' : '' }}">
                        <i class="bi bi-megaphone"></i>
                        <span>Announcements</span>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('patient-record') }}" class="mobile-nav-link {{ request()->routeIs('patient-record') ? 'active' : '' }}">
                        <i class="bi bi-file-medical"></i>
                        <span>My Records</span>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('patient-about') }}" class="mobile-nav-link {{ request()->routeIs('patient-about') ? 'active' : '' }}">
                        <i class="bi bi-info-circle"></i>
                        <span>About Us</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- User Actions at Bottom -->
        <div class="mobile-user-actions-section">
            <a class="mobile-action-item" href="{{ route('patient-profile') }}">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
            </a>
            <a class="mobile-action-item" href="{{ route('patient-notifications') }}">
                <i class="bi bi-bell"></i>
                <span>Notifications</span>
            </a>
            <div class="mobile-action-item mobile-action-dark-mode">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <i id="mobileDmLabelIcon" class="bi bi-moon-stars"></i>
                        <span id="mobileDmLabelText">Dark Mode</span>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" id="mobilePatientDarkModeSwitch">
                    </div>
                </div>
            </div>
            <a class="mobile-action-item mobile-action-logout" href="#" id="mobilePatientLogoutBtn">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</header>

<style>
.patient-header {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0.75rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.header-container-right {
    display: flex;
    align-items: center;
    gap: 0.9rem;
}

.header-logo {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0.25rem;
    border-radius: 8px;
}

.header-logo:hover {
    transform: translateY(-2px);
}

/* Pulse animation for logo on page load */
@keyframes logoPulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.logo-img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    transition: transform 0.3s ease, filter 0.3s ease;
    cursor: pointer;
    animation: logoPulse 2s ease-in-out;
}

.logo-img:hover {
    transform: scale(1.1) rotate(5deg);
    filter: drop-shadow(0 4px 12px rgba(255, 255, 255, 0.3));
    animation: none;
}

.logo-text {
    display: flex;
    flex-direction: column;
    color: white;
    align-items: flex-start;
    justify-content: center;
    line-height: 1;
}

.clinic-name {
    font-size: 1.75rem;
    font-weight: 700;
    line-height: 1.2;
    color: white;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.clinic-name-talk {
    color: #00EAFF;
    transition: all 0.3s ease;
    font-weight: 700;
}

.header-logo:hover .clinic-name {
    text-shadow: 0 2px 8px rgba(255, 255, 255, 0.3);
}

.header-logo:hover .clinic-name-talk {
    color: #5CECFF;
    text-shadow: 0 2px 8px rgba(0, 234, 255, 0.6);
}

/* Dark Mode Logo Styles */
[data-theme="dark"] .clinic-name {
    color: #00EAFF !important;
    text-shadow: 0 2px 4px rgba(0, 234, 255, 0.3), 0 0 8px rgba(0, 234, 255, 0.2), 0 1px 2px rgba(255, 255, 255, 0.2) !important;
    transition: all 0.3s ease;
}

[data-theme="dark"] .clinic-name-talk {
    color: #ffffff !important;
    text-shadow: 0 2px 8px rgba(255, 255, 255, 0.3) !important;
    transition: all 0.3s ease;
}

[data-theme="dark"] .header-logo:hover .clinic-name {
    color: #5CECFF !important;
    text-shadow: 0 0 15px rgba(0, 234, 255, 0.7), 0 0 25px rgba(0, 234, 255, 0.4) !important;
}

[data-theme="dark"] .header-logo:hover .clinic-name-talk {
    color: #ffffff !important;
    text-shadow: 0 2px 12px rgba(255, 255, 255, 0.5) !important;
}

.clinic-subtitle {
    font-size: 0.75rem;
    opacity: 0.9;
}

.header-nav { flex: 1; }

.nav-menu {
    display: flex;
    list-style: none;
    gap: 0.5rem;
    margin: 0;
    padding: 0;
    justify-content: center;
}

.nav-item {
    position: relative;
}

.nav-item .nav-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.65rem 1.25rem;
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 0.95rem;
    white-space: nowrap;
    position: relative;
    font-weight: 500;
    overflow: hidden;
    backdrop-filter: blur(10px);
    box-shadow: none;
}

.nav-item .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
    opacity: 0;
    transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 12px;
}

.nav-item .nav-link i {
    font-size: 1.15rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    z-index: 1;
}

.nav-item .nav-link span {
    position: relative;
    z-index: 1;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-item .nav-link:hover {
    color: white;
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.25), 0 1px 3px rgba(0, 0, 0, 0.08);
}

.nav-item .nav-link:hover::before {
    opacity: 1;
}

.nav-item .nav-link:hover i {
    transform: scale(1.15) rotate(5deg);
    color: #00EAFF;
}

.nav-item .nav-link:hover span {
    font-weight: 600;
}

.nav-item .nav-link.active {
    background: linear-gradient(135deg, rgba(25, 118, 210, 0.8) 0%, rgba(21, 101, 192, 0.9) 100%);
    color: white;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2), 0 1px 3px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.nav-item .nav-link.active::before {
    opacity: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
}

.nav-item .nav-link.active i {
    color: #00EAFF;
    transform: scale(1.1);
    animation: iconPulse 2s ease-in-out infinite;
}

.nav-item .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    height: 3px;
    background: linear-gradient(90deg, transparent, #00EAFF, transparent);
    border-radius: 3px 3px 0 0;
    animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes iconPulse {
    0%, 100% {
        transform: scale(1.1);
        filter: drop-shadow(0 0 0 rgba(0, 234, 255, 0));
    }
    50% {
        transform: scale(1.15);
        filter: drop-shadow(0 0 8px rgba(0, 234, 255, 0.6));
    }
}

@keyframes slideIn {
    from {
        width: 0;
        opacity: 0;
    }
    to {
        width: 60%;
        opacity: 1;
    }
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 0.9rem;
}

.icon-btn {
    position: relative;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    border: none;
    color: white;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
}

.icon-btn:hover {
    background: rgba(255,255,255,0.25);
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.user-profile-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 1rem;
    background: rgba(255,255,255,0.15);
    border: none;
    border-radius: 50px;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    flex-shrink: 0;
}

.user-profile-btn:hover {
    background: rgba(255,255,255,0.25);
}

.user-profile-btn .bi-chevron-down {
    font-size: 0.875rem;
    opacity: 0.8;
    transition: transform 0.3s;
    flex-shrink: 0;
}

.user-profile-btn:hover .bi-chevron-down {
    opacity: 1;
    transform: translateY(1px);
}

.profile-avatar {
    width: 38px;
    height: 38px;
    min-width: 38px;
    min-height: 38px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.4);
    background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.15) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
    flex-shrink: 0;
    margin: 0;
    padding: 0;
}

.profile-avatar-lg {
    width: 60px;
    height: 60px;
    min-width: 60px;
    min-height: 60px;
    border-radius: 50%;
    border: 3px solid #2196F3;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
    margin: 0 auto;
    flex-shrink: 0;
}

.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    line-height: 1.3;
    min-width: 0;
    flex: 1;
}

.user-name {
    font-size: 0.9rem;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

.user-role {
    font-size: 0.75rem;
    opacity: 0.9;
    white-space: nowrap;
}

.notification-dropdown,
.profile-dropdown {
    min-width: 320px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    border: none;
    z-index: 1001;
}

.profile-dropdown .form-check-input {
    cursor: pointer;
}
.profile-dropdown .form-check-input:focus { box-shadow: none; }

.notification-item {
    display: flex;
    align-items: start;
    gap: 0.75rem;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s;
}

.notification-item:hover {
    background: #f8fafc;
}

.notification-item.unread {
    background: linear-gradient(to right, #f0f9ff 0%, #ffffff 100%);
    border-left: 3px solid #2196F3;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #1e293b;
}

.notification-content small {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #64748b;
}

.notification-time {
    font-size: 0.75rem;
    color: #64748b;
    white-space: nowrap;
}

/* Dark Mode Notification Dropdown Styles */
[data-theme="dark"] .notification-dropdown {
    background: #1e293b !important;
    border: 1px solid #334155 !important;
}

[data-theme="dark"] .notification-dropdown .dropdown-header {
    background: #1e293b !important;
    border-bottom: 1px solid #334155 !important;
}

[data-theme="dark"] .notification-dropdown .dropdown-header h6 {
    color: #f1f5f9 !important;
}

[data-theme="dark"] .notification-dropdown .dropdown-header small,
[data-theme="dark"] .notification-dropdown .dropdown-header .text-muted {
    color: #94a3b8 !important;
}

[data-theme="dark"] .notification-dropdown .dropdown-divider {
    border-top-color: #334155 !important;
}

[data-theme="dark"] .notification-item {
    background: #1e293b !important;
    border-bottom-color: #334155 !important;
    color: #f1f5f9 !important;
}

[data-theme="dark"] .notification-item:hover {
    background: #334155 !important;
}

[data-theme="dark"] .notification-item.unread {
    background: linear-gradient(to right, #1e3a5f 0%, #1e293b 100%) !important;
    border-left-color: #3b82f6 !important;
}

[data-theme="dark"] .notification-item.unread:hover {
    background: linear-gradient(to right, #1e40af 0%, #334155 100%) !important;
}

[data-theme="dark"] .notification-title {
    color: #f1f5f9 !important;
    font-weight: 600 !important;
}

[data-theme="dark"] .notification-content small,
[data-theme="dark"] .notification-content .text-muted {
    color: #cbd5e1 !important;
}

[data-theme="dark"] .notification-time {
    color: #94a3b8 !important;
}

[data-theme="dark"] .notification-dropdown .dropdown-item.text-center {
    background: #1e293b !important;
    border-top: 1px solid #334155 !important;
}

[data-theme="dark"] .notification-dropdown .dropdown-item.text-center:hover {
    background: #334155 !important;
}

[data-theme="dark"] .notification-dropdown .dropdown-item.text-center.text-primary,
[data-theme="dark"] .notification-dropdown .dropdown-item.text-center small {
    color: #60a5fa !important;
}

[data-theme="dark"] .notification-dropdown .dropdown-item.text-center.text-primary:hover {
    color: #93c5fd !important;
}

[data-theme="dark"] .notification-dropdown .text-center .text-muted,
[data-theme="dark"] .notification-dropdown .text-center i.text-muted {
    color: #94a3b8 !important;
}

[data-theme="dark"] .notification-dropdown .text-center p.text-muted {
    color: #cbd5e1 !important;
}

.mobile-menu-toggle {
    display: none;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: rgba(255,255,255,0.15);
    border: none;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    align-items: center;
    justify-content: center;
    z-index: 1001;
    position: relative;
    pointer-events: auto;
    -webkit-tap-highlight-color: transparent;
    user-select: none;
}


.mobile-menu-toggle:hover {
    background: rgba(255,255,255,0.25);
}

/* When menu is open, pin the toggle above everything */
.mobile-menu-toggle.fixed-open {
    position: fixed !important;
    /* Align with mobile menu header: header height 64px, padding 14px, button height 40px */
    /* Perfect center: (64 - 40) / 2 = 12px */
    top: 12px !important;
    right: 16px !important;
    background: rgba(255,255,255,0.25) !important;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
    z-index: 1041 !important;
    pointer-events: auto !important;
}

@media (max-width: 576px) {
    .mobile-menu-toggle.fixed-open {
        /* Align with mobile menu header: header height 60px, button height 36px */
        /* Perfect center: (60 - 36) / 2 = 12px */
        top: 12px !important;
        right: 14px !important;
    }
}

@media (max-width: 480px) {
    .mobile-menu-toggle.fixed-open {
        /* Align with mobile menu header: header height 56px, button height 32px */
        /* Perfect center: (56 - 32) / 2 = 12px */
        top: 12px !important;
        right: 12px !important;
    }
}

/* Burger icon (hamburger -> X) */
.burger-icon {
    position: relative;
    width: 22px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.burger-icon .bar {
    position: absolute;
    left: 0;
    width: 100%;
    height: 2px;
    display: block;
    background: currentColor;
    border-radius: 2px;
    transition: transform 0.25s ease, top 0.25s ease, opacity 0.2s ease;
    margin: auto;
}

.burger-icon .bar:nth-child(1) { top: 0; }
.burger-icon .bar:nth-child(2) { top: 50%; transform: translateY(-50%); }
.burger-icon .bar:nth-child(3) { bottom: 0; }

.burger-icon.open .bar:nth-child(1) { 
    top: 50%; 
    transform: translateY(-50%) rotate(45deg); 
}
.burger-icon.open .bar:nth-child(2) { opacity: 0; }
.burger-icon.open .bar:nth-child(3) { 
    bottom: auto;
    top: 50%; 
    transform: translateY(-50%) rotate(-45deg); 
}


/* Mobile Menu Backdrop */
.mobile-menu-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.5);
    backdrop-filter: blur(1px);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.25s ease, visibility 0.25s ease;
    z-index: 1037;
}

.mobile-menu-backdrop.active {
    opacity: 1;
    visibility: visible;
}

/* Mobile Menu Overlay */
.mobile-menu-overlay {
    position: fixed;
    top: 0;
    right: -100%;
    width: 320px;
    max-width: 85vw;
    height: 100vh;
    background: white;
    box-shadow: -4px 0 30px rgba(0,0,0,0.2);
    z-index: 1038 !important;
    transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
}

.mobile-menu-overlay.active {
    right: 0;
}

.mobile-menu-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    min-height: 64px;
    height: 64px;
    max-height: 64px;
    box-sizing: border-box;
    position: relative;
    flex-shrink: 0;
}

.mobile-menu-header .header-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
    margin: 0;
    padding: 0;
    max-width: calc(100% - 50px);
}

.mobile-menu-header .logo-img {
    width: 48px;
    height: 48px;
    min-width: 48px;
    min-height: 48px;
    object-fit: contain;
    flex-shrink: 0;
    display: block;
    margin: 0;
    padding: 0;
}

.mobile-menu-header .logo-text {
    display: flex;
    align-items: center;
    flex-shrink: 0;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

.mobile-menu-header .clinic-name {
    font-size: 20px;
    line-height: 1.2;
    display: flex;
    align-items: center;
    margin: 0;
    padding: 0;
    vertical-align: middle;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.mobile-menu-close {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    flex-shrink: 0;
    margin: 0;
    padding: 0;
}

.mobile-menu-close:hover {
    background: rgba(255,255,255,0.3);
}

.mobile-menu-close .burger-icon {
    width: 22px;
    height: 16px;
}

.mobile-nav {
    padding: 0;
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    min-height: 0;
}

.mobile-nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.mobile-nav-item {
    border-bottom: 1px solid #e8e8e8;
}

.mobile-nav-item:last-child {
    border-bottom: none;
}

.mobile-nav-link {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 18px;
    color: #37474f;
    text-decoration: none;
    transition: all 0.25s ease;
    font-size: 14px;
    position: relative;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.mobile-nav-link i {
    font-size: 18px;
    color: #2196F3;
    width: 22px;
    min-width: 22px;
    text-align: center;
    transition: transform 0.25s ease;
    flex-shrink: 0;
}

.mobile-nav-link span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.mobile-nav-link:hover {
    background: #f5f8fa;
    padding-left: 24px;
}

.mobile-nav-link:hover i {
    transform: scale(1.1);
}

.mobile-nav-link.active {
    background: linear-gradient(to right, #e3f2fd 0%, #ffffff 100%);
    border-left: 4px solid #2196F3;
    font-weight: 600;
    color: #2196F3;
    padding-left: 16px;
}

.mobile-nav-link.active i {
    color: #2196F3;
}

/* Dark Mode Styles for Mobile Navigation */
[data-theme="dark"] .mobile-menu-overlay {
    background: var(--dm-card-bg, #1e293b) !important;
    box-shadow: -4px 0 30px rgba(0, 0, 0, 0.5) !important;
}

[data-theme="dark"] .mobile-nav-item {
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .mobile-nav-link {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .mobile-nav-link i {
    color: #60a5fa !important;
}

[data-theme="dark"] .mobile-nav-link:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .mobile-nav-link.active {
    background: linear-gradient(to right, rgba(59, 130, 246, 0.2) 0%, var(--dm-bg-tertiary, #334155) 100%) !important;
    border-left-color: #60a5fa !important;
    color: #60a5fa !important;
}

[data-theme="dark"] .mobile-nav-link.active i {
    color: #60a5fa !important;
}

[data-theme="dark"] .mobile-user-info-section {
    background: var(--dm-bg-secondary, #0f172a) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .mobile-user-info-section h6 {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .mobile-user-info-section small {
    color: var(--dm-text-muted, #94a3b8) !important;
}

[data-theme="dark"] .mobile-user-actions-section {
    background: var(--dm-bg-secondary, #0f172a) !important;
    border-top-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .mobile-action-item {
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .mobile-action-item i {
    color: #60a5fa !important;
}

[data-theme="dark"] .mobile-action-item:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

[data-theme="dark"] .mobile-action-logout {
    color: #f87171 !important;
}

[data-theme="dark"] .mobile-action-logout i {
    color: #f87171 !important;
}

[data-theme="dark"] .mobile-action-logout:hover {
    background: rgba(239, 68, 68, 0.15) !important;
}

[data-theme="dark"] .mobile-action-dark-mode {
    color: var(--dm-text-primary, #f1f5f9) !important;
    border-bottom-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .mobile-action-dark-mode i {
    color: #60a5fa !important;
}

[data-theme="dark"] .mobile-action-dark-mode:hover {
    background: var(--dm-bg-tertiary, #334155) !important;
}

/* User Info Section at Top */
.mobile-user-info-section {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    background: #fafbfc;
    border-bottom: 2px solid #e8e8e8;
    flex-shrink: 0;
    text-align: left;
}

.mobile-user-info-section .profile-avatar-lg {
    width: 48px;
    height: 48px;
    min-width: 48px;
    min-height: 48px;
    font-size: 20px;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
    border-radius: 50%;
    border: 3px solid #2196F3;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    flex-shrink: 0;
}

.mobile-user-info-text {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
}

.mobile-user-info-section h6 {
    color: #263238;
    font-weight: 600;
    font-size: 15px;
    margin: 0 0 2px 0;
    padding: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 100%;
    text-align: left;
}

.mobile-user-info-section small {
    color: #78909c;
    font-size: 12px;
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 100%;
    text-align: left;
}

/* User Actions Section at Bottom */
.mobile-user-actions-section {
    padding: 8px 0;
    background: #fafbfc;
    border-top: 2px solid #e8e8e8;
    margin-top: auto;
    margin-bottom: 0;
    flex-shrink: 0;
}

.mobile-action-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 11px 18px;
    color: #37474f;
    text-decoration: none;
    transition: all 0.25s ease;
    font-size: 14px;
    border-bottom: 1px solid #e8e8e8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.mobile-action-item:last-child {
    border-bottom: none;
}

.mobile-action-dark-mode {
    cursor: pointer;
    padding: 11px 18px;
}

.mobile-action-dark-mode:hover {
    background: #f5f8fa;
    padding-left: 24px;
}

.mobile-action-dark-mode i {
    font-size: 20px;
    color: #2196F3;
    width: 24px;
    min-width: 24px;
    text-align: center;
    flex-shrink: 0;
}

.mobile-action-dark-mode .form-check-input {
    cursor: pointer;
    width: 48px;
    height: 24px;
}

.mobile-action-dark-mode .form-check-input:focus {
    box-shadow: none;
    border-color: #2196F3;
}

.mobile-action-item i {
    font-size: 18px;
    color: #2196F3;
    width: 22px;
    min-width: 22px;
    text-align: center;
    flex-shrink: 0;
}

.mobile-action-item span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.mobile-action-item:hover {
    background: #f5f8fa;
    padding-left: 24px;
}

.mobile-action-logout {
    color: #ef4444 !important;
}

.mobile-action-logout i {
    color: #ef4444 !important;
}

.mobile-action-logout:hover {
    background: #fee2e2;
}

@media (max-width: 992px) {
    .header-container {
        padding: 10px 14px;
        min-height: 56px;
        align-items: center;
    }

    .header-nav {
        display: none;
    }

    .header-container-right {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: auto;
    }

    .header-logo {
        gap: 10px;
        align-items: center;
    }

    .logo-img {
        width: 48px;
        height: 48px;
        min-width: 48px;
        min-height: 48px;
    }

    .logo-text {
        display: flex;
        align-items: center;
        line-height: 1;
    }

    .clinic-name {
        font-size: 1.4rem;
        line-height: 1.2;
        display: flex;
        align-items: center;
    }

    .mobile-menu-toggle {
        display: flex !important;
        align-items: center;
        justify-content: center;
        visibility: visible !important;
        opacity: 1 !important;
        width: 40px;
        height: 40px;
        min-width: 40px;
        min-height: 40px;
        align-self: center;
    }

    .burger-icon {
        width: 20px;
        height: 14px;
    }

    .burger-icon .bar {
        height: 2px;
    }

    .user-info {
        display: none;
    }

    .user-profile-btn {
        display: none !important;
    }

    .user-profile-btn .bi-chevron-down {
        display: none;
    }

    .profile-avatar {
        width: 32px;
        height: 32px;
        min-width: 32px;
        min-height: 32px;
    }

    .header-actions {
        gap: 0.5rem;
    }
    
    /* Hide notifications button on mobile - it's in hamburger menu */
    .header-actions .dropdown:first-child {
        display: none;
    }

    .icon-btn {
        width: 32px;
        height: 32px;
        min-width: 32px;
        min-height: 32px;
        font-size: 0.95rem;
    }
}

@media (max-width: 576px) {
    .header-container {
        padding: 8px 12px;
        min-height: 52px;
        align-items: center;
    }

    .header-logo {
        align-items: center;
    }

    .logo-img {
        width: 44px;
        height: 44px;
        min-width: 44px;
        min-height: 44px;
    }

    .logo-text {
        align-items: center;
        line-height: 1;
    }

    .clinic-name {
        line-height: 1.2;
        display: flex;
        align-items: center;
    }

    .mobile-menu-toggle {
        width: 36px;
        height: 36px;
        min-width: 36px;
        min-height: 36px;
        align-self: center;
    }

    .burger-icon {
        width: 18px;
        height: 12px;
    }

    .burger-icon .bar {
        height: 2px;
    }

    .logo-text {
        display: none;
    }

    .mobile-menu-overlay {
        width: 100%;
        max-width: 300px;
    }


    .mobile-menu-header {
        padding: 12px 14px;
        min-height: 60px;
        height: 60px;
        max-height: 60px;
    }

    .mobile-menu-header .logo-img {
        width: 44px;
        height: 44px;
        min-width: 44px;
        min-height: 44px;
    }

    .mobile-menu-header .clinic-name {
        font-size: 18px;
    }

    .mobile-user-info-section {
        padding: 12px 14px;
        gap: 12px;
    }

    .mobile-user-info-section .profile-avatar-lg {
        width: 44px;
        height: 44px;
        min-width: 44px;
        min-height: 44px;
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .mobile-user-info-section h6 {
        font-size: 14px;
        margin: 0 0 2px 0;
    }

    .mobile-user-info-section small {
        font-size: 11px;
    }

    .mobile-nav-link {
        padding: 10px 16px;
        font-size: 13px;
        gap: 12px;
    }

    .mobile-nav-link i {
        font-size: 18px;
        width: 22px;
        min-width: 22px;
    }

    .mobile-nav-link:hover {
        padding-left: 22px;
    }

    .mobile-nav-link.active {
        padding-left: 14px;
    }

    .mobile-action-item {
        padding: 9px 16px;
        font-size: 13px;
        gap: 12px;
    }

    .mobile-action-item i {
        font-size: 16px;
        width: 20px;
        min-width: 20px;
    }

    .mobile-action-item:hover {
        padding-left: 20px;
    }

    .mobile-action-dark-mode {
        padding: 9px 16px;
        gap: 12px;
    }

    .mobile-action-dark-mode i {
        font-size: 16px;
        width: 20px;
        min-width: 20px;
    }

    .mobile-action-dark-mode:hover {
        padding-left: 20px;
    }

    .mobile-user-actions-section {
        padding: 6px 0;
    }

    .icon-btn {
        width: 32px;
        height: 32px;
        font-size: 1rem;
    }

    .profile-avatar {
        width: 32px;
        height: 32px;
        min-width: 32px;
        min-height: 32px;
        font-size: 0.75rem;
    }

    .profile-avatar-lg {
        width: 52px;
        height: 52px;
        min-width: 52px;
        min-height: 52px;
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    .header-container {
        padding: 6px 10px;
        min-height: 48px;
        align-items: center;
    }

    .header-logo {
        align-items: center;
    }

    .logo-img {
        width: 40px;
        height: 40px;
        min-width: 40px;
        min-height: 40px;
    }

    .logo-text {
        align-items: center;
        line-height: 1;
    }

    .clinic-name {
        line-height: 1.2;
        display: flex;
        align-items: center;
    }

    .mobile-menu-toggle {
        width: 32px;
        height: 32px;
        min-width: 32px;
        min-height: 32px;
        align-self: center;
    }

    .burger-icon {
        width: 16px;
        height: 11px;
    }

    .burger-icon .bar {
        height: 1.5px;
    }

    .mobile-menu-overlay {
        max-width: 280px;
    }


    .mobile-menu-header {
        padding: 10px 12px;
        min-height: 56px;
        height: 56px;
        max-height: 56px;
    }

    .mobile-menu-header .logo-img {
        width: 40px;
        height: 40px;
        min-width: 40px;
        min-height: 40px;
    }

    .mobile-menu-header .clinic-name {
        font-size: 16px;
    }

    .mobile-user-info-section {
        padding: 10px 12px;
        gap: 10px;
    }

    .mobile-user-info-section .profile-avatar-lg {
        width: 40px;
        height: 40px;
        min-width: 40px;
        min-height: 40px;
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .mobile-user-info-section h6 {
        font-size: 13px;
        margin: 0 0 2px 0;
    }

    .mobile-user-info-section small {
        font-size: 10px;
    }

    .mobile-nav-link {
        padding: 9px 14px;
        font-size: 12px;
        gap: 10px;
    }

    .mobile-nav-link i {
        font-size: 16px;
        width: 20px;
        min-width: 20px;
    }

    .mobile-nav-link:hover {
        padding-left: 20px;
    }

    .mobile-nav-link.active {
        padding-left: 12px;
    }

    .mobile-action-item {
        padding: 8px 14px;
        font-size: 12px;
        gap: 10px;
    }

    .mobile-action-item i {
        font-size: 14px;
        width: 18px;
        min-width: 18px;
    }

    .mobile-action-item:hover {
        padding-left: 18px;
    }

    .mobile-action-dark-mode {
        padding: 8px 14px;
        gap: 10px;
    }

    .mobile-action-dark-mode i {
        font-size: 14px;
        width: 18px;
        min-width: 18px;
    }

    .mobile-action-dark-mode:hover {
        padding-left: 18px;
    }

    .mobile-user-actions-section {
        padding: 4px 0;
    }
}
</style>

<script>
// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function(){
    const toggle = document.getElementById('mobileMenuToggle');
    const menu = document.getElementById('mobileMenuOverlay');
    const backdrop = document.getElementById('mobileMenuBackdrop');
    const toggleIcon = document.getElementById('mobileMenuToggleIcon');

    if (!toggle || !menu || !backdrop) {
        console.error('Mobile menu elements not found', {
            toggle: !!toggle,
            menu: !!menu,
            backdrop: !!backdrop
        });
        return;
    }

    console.log('Mobile menu elements found', {
        toggle: toggle,
        menu: menu,
        backdrop: backdrop
    });

    function openMenu(){
        console.log('Opening menu');
        menu.classList.add('active');
        menu.setAttribute('aria-hidden','false');
        backdrop.classList.add('active');
        backdrop.setAttribute('aria-hidden','false');
        if (toggleIcon) toggleIcon.classList.add('open');
        document.body.style.overflow = 'hidden'; // lock page scroll under menu
        // pin toggle on top
        toggle.classList.add('fixed-open');
        toggle.setAttribute('aria-label','Close menu');
    }

    function closeMenu(){
        console.log('Closing menu');
        menu.classList.remove('active');
        menu.setAttribute('aria-hidden','true');
        backdrop.classList.remove('active');
        backdrop.setAttribute('aria-hidden','true');
        if (toggleIcon) toggleIcon.classList.remove('open');
        document.body.style.overflow = ''; // restore scroll
        toggle.classList.remove('fixed-open');
        toggle.setAttribute('aria-label','Open menu');
    }

    // Add both click and touchstart for better mobile support
    toggle.addEventListener('click', function(e){
        console.log('Toggle button clicked');
        e.preventDefault();
        e.stopPropagation();
        if (menu.classList.contains('active')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    toggle.addEventListener('touchstart', function(e){
        console.log('Toggle button touched');
        e.preventDefault();
        e.stopPropagation();
        if (menu.classList.contains('active')) {
            closeMenu();
        } else {
            openMenu();
        }
    });


    document.addEventListener('click', function(e){
        if (menu.classList.contains('active') && !menu.contains(e.target) && e.target !== toggle && !toggle.contains(e.target)) { 
            closeMenu(); 
        }
    });

    if (backdrop) {
        backdrop.addEventListener('click', function(e){
            e.preventDefault();
            closeMenu();
        });
    }

    // Close menu when clicking on navigation links
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            closeMenu();
        });
    });

    // Close menu for non-logout action items
    const actionItems = document.querySelectorAll('.mobile-action-item:not(.mobile-action-logout)');
    actionItems.forEach(item => {
        item.addEventListener('click', function() {
            closeMenu();
        });
    });
});

// Notification system
let previousUnreadCount = 0;

document.addEventListener('DOMContentLoaded', function(){
    loadNotifications();

    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);

    // Load when dropdown is opened
    document.getElementById('notificationDropdownBtn')?.addEventListener('click', loadNotifications);
});

// Subtle notification sound function
function playNotificationSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        // Subtle, pleasant notification sound
        oscillator.frequency.value = 800; // Starting frequency
        oscillator.type = 'sine'; // Soft sine wave

        // Fade in and out for subtlety
        gainNode.gain.setValueAtTime(0, audioContext.currentTime);
        gainNode.gain.linearRampToValueAtTime(0.15, audioContext.currentTime + 0.01); // Volume at 15%
        gainNode.gain.linearRampToValueAtTime(0, audioContext.currentTime + 0.2); // Fade out

        // Play two soft beeps
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);

        // Second beep after a short pause
        setTimeout(() => {
            const oscillator2 = audioContext.createOscillator();
            const gainNode2 = audioContext.createGain();

            oscillator2.connect(gainNode2);
            gainNode2.connect(audioContext.destination);

            oscillator2.frequency.value = 1000; // Slightly higher frequency
            oscillator2.type = 'sine';

            gainNode2.gain.setValueAtTime(0, audioContext.currentTime);
            gainNode2.gain.linearRampToValueAtTime(0.15, audioContext.currentTime + 0.01);
            gainNode2.gain.linearRampToValueAtTime(0, audioContext.currentTime + 0.15);

            oscillator2.start(audioContext.currentTime);
            oscillator2.stop(audioContext.currentTime + 0.15);
        }, 150);
    } catch (error) {
        // Fallback: Silent if audio context is not supported or user interaction is required
        console.log('Notification sound unavailable');
    }
}

let lastNotificationCheck = null;
let notificationPollInterval = null;

async function loadNotifications() {
    try {
        const url = lastNotificationCheck 
            ? `/patient/notifications/poll?last_check=${encodeURIComponent(lastNotificationCheck)}`
            : '/patient/notifications/recent';
        
        const response = await fetch(url);
        const data = await response.json();

        // Check if unread count increased (new notification)
        const currentUnreadCount = data.unread_count || 0;
        if (typeof previousUnreadCount !== 'undefined' && currentUnreadCount > previousUnreadCount) {
            // New notification received - play sound
            playNotificationSound();
            
            // Show browser notification if permission granted
            if (Notification.permission === 'granted' && data.has_new) {
                const newNotif = data.notifications && data.notifications[0];
                if (newNotif) {
                    new Notification(newNotif.title, {
                        body: newNotif.message,
                        icon: '/images/logo4.png',
                        tag: 'notification-' + newNotif.id
                    });
                }
            }
        }
        previousUnreadCount = currentUnreadCount;

        // Update last check timestamp
        if (data.timestamp) {
            lastNotificationCheck = data.timestamp;
        } else if (data.notifications && data.notifications.length > 0) {
            lastNotificationCheck = data.notifications[0].created_at;
        }

        updateNotificationBadge(data.unread_count);
        updateNotificationSubtitle(data.unread_count);
        
        // Only update notifications list if dropdown is open or we have new notifications
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown && (dropdown.classList.contains('show') || data.has_new)) {
            if (data.has_new && data.notifications) {
                // Prepend new notifications to the list
                const existingNotifications = getCurrentNotifications();
                const allNotifications = [...data.notifications, ...existingNotifications]
                    .filter((v, i, a) => a.findIndex(t => t.id === v.id) === i)
                    .slice(0, 5);
                renderNotifications(allNotifications);
            } else if (!lastNotificationCheck) {
                // Initial load
                renderNotifications(data.notifications || []);
            }
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
        if (!lastNotificationCheck) {
            document.getElementById('notificationsList').innerHTML = `
                <div class="text-center py-3 text-muted">
                    <small>Failed to load notifications</small>
                </div>
            `;
        }
    }
}

function getCurrentNotifications() {
    const container = document.getElementById('notificationsList');
    const items = container.querySelectorAll('.notification-item');
    const notifications = [];
    items.forEach(item => {
        const title = item.querySelector('.notification-title')?.textContent;
        const message = item.querySelector('small')?.textContent;
        const timeAgo = item.querySelector('.notification-time')?.textContent;
        const isRead = !item.classList.contains('unread');
        const iconClass = item.querySelector('.bi')?.className.match(/bi-[\w-]+/)?.[0];
        const iconColor = item.querySelector('.notification-icon')?.className.match(/bg-\w+/)?.[0];
        
        if (title) {
            notifications.push({
                title,
                message,
                time_ago: timeAgo,
                is_read: isRead,
                icon_class: iconClass,
                icon_color: iconColor
            });
        }
    });
    return notifications;
}

// Start polling for notifications
function startNotificationPolling() {
    // Load initial notifications
    loadNotifications();
    
    // Poll every 5 seconds for new notifications
    if (notificationPollInterval) {
        clearInterval(notificationPollInterval);
    }
    notificationPollInterval = setInterval(loadNotifications, 5000);
}

// Request notification permission on page load
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

// Start polling when page loads
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startNotificationPolling);
} else {
    startNotificationPolling();
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationBadge');
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}

function updateNotificationSubtitle(count) {
    const subtitle = document.getElementById('notificationSubtitle');
    if (count === 0) {
        subtitle.textContent = 'No unread notifications';
    } else if (count === 1) {
        subtitle.textContent = 'You have 1 unread notification';
    } else {
        subtitle.textContent = `You have ${count} unread notifications`;
    }
}

function renderNotifications(notifications) {
    const container = document.getElementById('notificationsList');

    if (notifications.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="bi bi-bell-slash text-muted" style="font-size: 2rem;"></i>
                <p class="text-muted mb-0 mt-2">No notifications</p>
            </div>
        `;
        return;
    }

    container.innerHTML = notifications.map(notification => `
        <a href="{{ route('patient-notifications') }}" class="dropdown-item notification-item ${!notification.is_read ? 'unread' : ''}" onclick="markNotificationAsRead(${notification.id}, event)">
            <div class="notification-icon ${notification.icon_color}">
                <i class="bi ${notification.icon_class} text-white"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${notification.title}</div>
                <small class="text-muted">${notification.message}</small>
            </div>
            <span class="notification-time">${notification.time_ago}</span>
        </a>
    `).join('');
}

async function markNotificationAsRead(id, event) {
    // Don't prevent default, let the link work
    try {
        await fetch(`/patient/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        });
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

// Logout Confirmation Modal
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded');
        return;
    }

    // Desktop logout button
    const patientLogoutBtn = document.getElementById('patientLogoutBtn');
    if (patientLogoutBtn) {
        patientLogoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const modalElement = document.getElementById('patientLogoutModal');
            if (modalElement) {
                const logoutModal = new bootstrap.Modal(modalElement);
                logoutModal.show();
            }
        });
    }

    // Mobile logout button
    const mobilePatientLogoutBtn = document.getElementById('mobilePatientLogoutBtn');
    if (mobilePatientLogoutBtn) {
        mobilePatientLogoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            // Close mobile menu first
            const menu = document.getElementById('mobileMenuOverlay');
            const backdrop = document.getElementById('mobileMenuBackdrop');
            const toggle = document.getElementById('mobileMenuToggle');
            const toggleIcon = document.getElementById('mobileMenuToggleIcon');
            
            if (menu) {
                menu.classList.remove('active');
                menu.setAttribute('aria-hidden', 'true');
            }
            if (backdrop) {
                backdrop.classList.remove('active');
                backdrop.setAttribute('aria-hidden', 'true');
            }
            if (toggleIcon) toggleIcon.classList.remove('open');
            if (toggle) {
                toggle.classList.remove('fixed-open');
                toggle.setAttribute('aria-label', 'Open menu');
            }
            document.body.style.overflow = '';
            
            // Then show logout modal
            const modalElement = document.getElementById('patientLogoutModal');
            if (modalElement) {
                const logoutModal = new bootstrap.Modal(modalElement);
                logoutModal.show();
            }
        });
    }

    // Confirm logout button
    const confirmLogoutBtn = document.getElementById('confirmPatientLogoutBtn');
    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', function() {
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.submit();
            }
        });
    }

    // Function to update dark mode UI elements
    function updateDarkModeUI() {
        const theme = document.documentElement.getAttribute('data-theme');
        const isDark = theme === 'dark';
        
        // Desktop switch
        const dmSwitch = document.getElementById('patientDarkModeSwitch');
        if (dmSwitch) {
            dmSwitch.checked = isDark;
        }
        
        // Desktop labels
        const dmIcon = document.getElementById('dmLabelIcon');
        const dmText = document.getElementById('dmLabelText');
        if (dmIcon && dmText) {
            if (isDark) {
                dmIcon.className = 'bi bi-sun me-2';
                dmText.textContent = 'Light Mode';
            } else {
                dmIcon.className = 'bi bi-moon-stars me-2';
                dmText.textContent = 'Dark Mode';
            }
        }
        
        // Mobile switch
        const mobileDmSwitch = document.getElementById('mobilePatientDarkModeSwitch');
        if (mobileDmSwitch) {
            mobileDmSwitch.checked = isDark;
        }
        
        // Mobile labels
        const mobileDmIcon = document.getElementById('mobileDmLabelIcon');
        const mobileDmText = document.getElementById('mobileDmLabelText');
        if (mobileDmIcon && mobileDmText) {
            if (isDark) {
                mobileDmIcon.className = 'bi bi-sun';
                mobileDmText.textContent = 'Light Mode';
            } else {
                mobileDmIcon.className = 'bi bi-moon-stars';
                mobileDmText.textContent = 'Dark Mode';
            }
        }
    }
    
    // Dark mode switch in profile dropdown (desktop)
    const dmSwitch = document.getElementById('patientDarkModeSwitch');
    if (dmSwitch) {
        updateDarkModeUI(); // Initialize on load
        dmSwitch.addEventListener('change', function() {
            toggleDarkMode();
            updateDarkModeUI(); // Update both switches
        });
    }
    
    // Dark mode switch in mobile menu
    const mobileDmSwitch = document.getElementById('mobilePatientDarkModeSwitch');
    if (mobileDmSwitch) {
        updateDarkModeUI(); // Initialize on load
        mobileDmSwitch.addEventListener('change', function() {
            toggleDarkMode();
            updateDarkModeUI(); // Update both switches
        });
    }
});
</script>

<!-- Patient Logout Confirmation Modal -->
<div class="modal fade" id="patientLogoutModal" tabindex="-1" aria-labelledby="patientLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content logout-modal-content">
            <div class="modal-body text-center p-4">
                <div class="logout-icon-wrapper mb-3">
                    <i class="bi bi-box-arrow-right text-white"></i>
                </div>
                <h5 class="logout-modal-title mb-2" id="patientLogoutModalLabel">Logout</h5>
                <p class="logout-modal-message mb-4">Are you sure you want to logout?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel-logout" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-confirm-logout" id="confirmPatientLogoutBtn">Logout</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Logout Modal Styles */
    .logout-modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(37, 99, 235, 0.3);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }

    .logout-icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(100, 116, 139, 0.4);
    }

    .logout-icon-wrapper i {
        font-size: 2.5rem;
        color: white;
    }

    .logout-modal-title {
        font-size: 1.375rem !important;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .logout-modal-message {
        font-size: 1rem;
        color: #64748b;
        margin-bottom: 1.5rem;
    }

    .btn-cancel-logout {
        background: #ffffff;
        color: #64748b;
        border: 2px solid #e2e8f0;
        padding: 0.625rem 1.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-cancel-logout:hover {
        background: #f8fafc;
        color: #2563eb;
        border-color: #2563eb;
        transform: translateY(-2px);
    }

    .btn-confirm-logout {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-confirm-logout:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(100, 116, 139, 0.4);
    }

    /* Dark Mode Styles for Logout Modal */
    [data-theme="dark"] .logout-modal-content {
        background: linear-gradient(135deg, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5) !important;
    }

    [data-theme="dark"] .logout-modal-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .logout-modal-message {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .btn-cancel-logout {
        background: var(--dm-bg-tertiary, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .btn-cancel-logout:hover {
        background: var(--dm-bg-secondary, #1e293b) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border-color: #3b82f6 !important;
    }

    [data-theme="dark"] .btn-confirm-logout {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%) !important;
        color: white !important;
    }

    [data-theme="dark"] .btn-confirm-logout:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%) !important;
        color: white !important;
    }
</style>

