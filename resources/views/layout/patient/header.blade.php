<header class="patient-header">
    <div class="header-container">
        <!-- Logo Section -->
        <div class="header-logo">
            <img src="{{ asset('images/logo.png') }}" alt="JValera Dental Clinic" class="logo-img">
            <div class="logo-text">
                <span class="clinic-name">JValera</span>
                <span class="clinic-subtitle">Dental Clinic</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="header-nav">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('patient-home') }}" class="nav-link {{ request()->routeIs('patient-home') ? 'active' : '' }}">
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
                    <a href="#" class="nav-link">
                        <i class="bi bi-info-circle"></i>
                        <span>About Us</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- User Actions -->
        <div class="header-actions">
            <!-- Notifications -->
            <div class="dropdown">
                <button class="icon-btn" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                    <div class="dropdown-header">
                        <h6 class="mb-0">Notifications</h6>
                        <small class="text-muted">You have 3 unread messages</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item notification-item">
                        <div class="notification-icon bg-primary">
                            <i class="bi bi-calendar-check text-white"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Appointment Confirmed</div>
                            <small class="text-muted">Your appointment on April 15 has been confirmed</small>
                        </div>
                        <span class="notification-time">2m ago</span>
                    </a>
                    <a href="#" class="dropdown-item notification-item">
                        <div class="notification-icon bg-success">
                            <i class="bi bi-file-medical text-white"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Record Updated</div>
                            <small class="text-muted">Your dental record has been updated</small>
                        </div>
                        <span class="notification-time">1h ago</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-center text-primary">
                        <small>View All Notifications</small>
                    </a>
                </div>
            </div>

            <!-- User Profile -->
            <div class="dropdown">
                <button class="user-profile-btn" type="button" data-bs-toggle="dropdown">
                    <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('images/avatar.jpg') }}" alt="Profile" class="profile-avatar">
                    <div class="user-info">
                        <span class="user-name">{{ auth()->user()->name ?? 'User' }}</span>
                        <small class="user-role">Patient</small>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                    <div class="dropdown-header text-center">
                        <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('images/avatar.jpg') }}" alt="Profile" class="profile-avatar-lg">
                        <h6 class="mt-2 mb-0">{{ auth()->user()->name ?? 'User' }}</h6>
                        <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('patient-profile') }}">
                        <i class="bi bi-person me-2"></i>My Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('patient-calendar') }}">
                        <i class="bi bi-calendar-check me-2"></i>My Appointments
                    </a>
                    <a class="dropdown-item" href="{{ route('patient-record') }}">
                        <i class="bi bi-file-medical me-2"></i>Medical Records
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-gear me-2"></i>Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('patient.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <i class="bi bi-list"></i>
        </button>
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
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    gap: 2rem;
}

.header-logo {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo-img {
    height: 50px;
    width: auto;
}

.logo-text {
    display: flex;
    flex-direction: column;
    color: white;
}

.clinic-name {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.2;
}

.clinic-subtitle {
    font-size: 0.75rem;
    opacity: 0.9;
}

.header-nav {
    flex: 1;
}

.nav-menu {
    display: flex;
    list-style: none;
    gap: 0.5rem;
    margin: 0;
    padding: 0;
}

.nav-item .nav-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s;
    font-size: 0.95rem;
}

.nav-item .nav-link i {
    font-size: 1.1rem;
}

.nav-item .nav-link:hover {
    background: rgba(255,255,255,0.15);
    color: white;
}

.nav-item .nav-link.active {
    background: rgba(255,255,255,0.2);
    color: white;
    font-weight: 600;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.icon-btn {
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    border: none;
    color: white;
    font-size: 1.25rem;
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
}

.user-profile-btn:hover {
    background: rgba(255,255,255,0.25);
}

.profile-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.5);
}

.profile-avatar-lg {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid #2196F3;
}

.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    line-height: 1.2;
}

.user-name {
    font-size: 0.95rem;
    font-weight: 600;
}

.user-role {
    font-size: 0.75rem;
    opacity: 0.9;
}

.notification-dropdown,
.profile-dropdown {
    min-width: 320px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    border: none;
}

.notification-item {
    display: flex;
    align-items: start;
    gap: 0.75rem;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
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
}

.notification-title {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.notification-time {
    font-size: 0.75rem;
    color: #999;
}

.mobile-menu-toggle {
    display: none;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(255,255,255,0.15);
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
}

@media (max-width: 992px) {
    .header-nav {
        display: none;
    }

    .mobile-menu-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-info {
        display: none;
    }
}
</style>
