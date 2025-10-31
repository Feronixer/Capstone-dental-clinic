<header class="patient-header">
    <div class="header-container">
        <!-- Logo Section -->
        <div class="header-logo">
            <img src="{{ asset('images/logo4.png') }}" alt="JValera Dental Clinic" class="logo-img">
            <div class="logo-text">
                <span class="clinic-name">TOOTHTALK</span>
            </div>
        </div>

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
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay">
        <div class="mobile-menu-header">
            <div class="header-logo">
                <img src="{{ asset('images/logo.png') }}" alt="JValera Dental Clinic" class="logo-img">
                <div class="logo-text">
                    <span class="clinic-name">JValera</span>
                    <span class="clinic-subtitle">Dental Clinic</span>
                </div>
            </div>
            <button class="mobile-menu-close" id="mobileMenuClose">
                <i class="bi bi-x-lg"></i>
            </button>
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
            <h6 class="mt-2 mb-0">
                @if(auth()->user()->info)
                    {{ trim(auth()->user()->info->first_name . ' ' . auth()->user()->info->last_name) }}
                @else
                    {{ auth()->user()->name ?? 'User' }}
                @endif
            </h6>
            <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
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
    z-index: 9999;
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

.header-logo {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo-img {
    height: 45px;
    width: auto;
}

.logo-text {
    display: flex;
    flex-direction: column;
    color: white;
}

.clinic-name {
    font-size: 1.15rem;
    font-weight: 700;
    line-height: 1.2;
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

.nav-item .nav-link {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 1rem;
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s;
    font-size: 0.9rem;
    white-space: nowrap;
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
    gap: 0.65rem;
    padding: 0.4rem 1.1rem;
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
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.5);
    background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
}

.profile-avatar-lg {
    width: 60px;
    height: 60px;
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
}

.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    line-height: 1.2;
}

.user-name {
    font-size: 0.9rem;
    font-weight: 600;
}

.user-role {
    font-size: 0.7rem;
    opacity: 0.85;
}

.notification-dropdown,
.profile-dropdown {
    min-width: 320px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    border: none;
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
}

.notification-content small {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.notification-time {
    font-size: 0.75rem;
    color: #999;
    white-space: nowrap;
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
    transition: all 0.3s;
}

.mobile-menu-toggle:hover {
    background: rgba(255,255,255,0.25);
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
    z-index: 10001;
    transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
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
    padding: 1rem;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
}

.mobile-menu-close {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.mobile-menu-close:hover {
    background: rgba(255,255,255,0.3);
}

.mobile-nav {
    padding: 0;
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
    gap: 1rem;
    padding: 1.1rem 1.5rem;
    color: #37474f;
    text-decoration: none;
    transition: all 0.25s ease;
    font-size: 0.95rem;
    position: relative;
}

.mobile-nav-link i {
    font-size: 1.4rem;
    color: #2196F3;
    width: 24px;
    text-align: center;
    transition: transform 0.25s ease;
}

.mobile-nav-link:hover {
    background: #f5f8fa;
    padding-left: 1.75rem;
}

.mobile-nav-link:hover i {
    transform: scale(1.1);
}

.mobile-nav-link.active {
    background: linear-gradient(to right, #e3f2fd 0%, #ffffff 100%);
    border-left: 4px solid #2196F3;
    font-weight: 600;
    color: #2196F3;
    padding-left: calc(1.5rem - 4px);
}

.mobile-nav-link.active i {
    color: #2196F3;
}

/* User Info Section at Top */
.mobile-user-info-section {
    text-align: center;
    padding: 1.5rem;
    background: #fafbfc;
    border-bottom: 2px solid #e8e8e8;
}

.mobile-user-info-section h6 {
    color: #263238;
    font-weight: 600;
    font-size: 1rem;
}

.mobile-user-info-section small {
    color: #78909c;
    font-size: 0.85rem;
}

/* User Actions Section at Bottom */
.mobile-user-actions-section {
    padding: 1rem 0;
    background: #fafbfc;
    border-top: 2px solid #e8e8e8;
    margin-top: 1rem;
}

.mobile-action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    color: #37474f;
    text-decoration: none;
    transition: all 0.25s ease;
    font-size: 0.95rem;
    border-bottom: 1px solid #e8e8e8;
}

.mobile-action-item:last-child {
    border-bottom: none;
}

.mobile-action-item i {
    font-size: 1.3rem;
    color: #2196F3;
    width: 24px;
    text-align: center;
}

.mobile-action-item:hover {
    background: #f5f8fa;
    padding-left: 1.75rem;
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
        padding: 0.75rem 1rem;
    }

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

    .user-profile-btn {
        padding: 0.4rem 0.6rem;
    }

    .header-actions {
        gap: 0.5rem;
    }
}

@media (max-width: 576px) {
    .logo-text {
        display: none;
    }

    .mobile-menu-overlay {
        width: 100%;
        max-width: 320px;
    }

    .mobile-menu-header {
        padding: 0.875rem 1rem;
    }

    .mobile-user-info-section {
        padding: 1.25rem;
    }

    .mobile-nav-link {
        padding: 1rem 1.25rem;
        font-size: 0.9rem;
    }

    .mobile-nav-link i {
        font-size: 1.3rem;
    }

    .mobile-nav-link:hover {
        padding-left: 1.5rem;
    }

    .mobile-nav-link.active {
        padding-left: calc(1.25rem - 4px);
    }

    .mobile-action-item {
        padding: 0.9rem 1.25rem;
        font-size: 0.9rem;
    }

    .mobile-action-item i {
        font-size: 1.2rem;
    }

    .mobile-action-item:hover {
        padding-left: 1.5rem;
    }

    .icon-btn {
        width: 32px;
        height: 32px;
        font-size: 1rem;
    }

    .profile-avatar {
        width: 32px;
        height: 32px;
        font-size: 0.75rem;
    }

    .profile-avatar-lg {
        width: 55px;
        height: 55px;
        font-size: 1.3rem;
    }
}
</style>

<script>
// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenuClose = document.getElementById('mobileMenuClose');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

    // Open mobile menu
    mobileMenuToggle?.addEventListener('click', function(e) {
        e.stopPropagation();
        mobileMenuOverlay.classList.add('active');
    });

    // Close mobile menu
    mobileMenuClose?.addEventListener('click', function() {
        mobileMenuOverlay.classList.remove('active');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileMenuOverlay.classList.contains('active')) {
            // Check if click is outside the menu
            if (!mobileMenuOverlay.contains(e.target) && e.target !== mobileMenuToggle) {
                mobileMenuOverlay.classList.remove('active');
            }
        }
    });

    // Prevent clicks inside menu from closing it
    mobileMenuOverlay?.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Close menu when clicking on navigation links
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenuOverlay.classList.remove('active');
        });
    });

    // Close menu for non-logout action items
    const actionItems = document.querySelectorAll('.mobile-action-item:not(.mobile-action-logout)');
    actionItems.forEach(item => {
        item.addEventListener('click', function() {
            mobileMenuOverlay.classList.remove('active');
        });
    });

    // Notification system
    loadNotifications();

    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);

    // Load when dropdown is opened
    document.getElementById('notificationDropdownBtn')?.addEventListener('click', loadNotifications);
});

async function loadNotifications() {
    try {
        const response = await fetch('/patient/notifications/recent');
        const data = await response.json();

        updateNotificationBadge(data.unread_count);
        updateNotificationSubtitle(data.unread_count);
        renderNotifications(data.notifications);
    } catch (error) {
        console.error('Error loading notifications:', error);
        document.getElementById('notificationsList').innerHTML = `
            <div class="text-center py-3 text-muted">
                <small>Failed to load notifications</small>
            </div>
        `;
    }
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

    // Dark mode switch in profile dropdown
    const dmSwitch = document.getElementById('patientDarkModeSwitch');
    if (dmSwitch) {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        dmSwitch.checked = currentTheme === 'dark';
        const dmIcon = document.getElementById('dmLabelIcon');
        const dmText = document.getElementById('dmLabelText');
        if (dmIcon && dmText) {
            if (currentTheme === 'dark') { dmIcon.className = 'bi bi-moon-stars me-2'; dmText.textContent = 'Dark Mode'; }
            else { dmIcon.className = 'bi bi-sun me-2'; dmText.textContent = 'Light Mode'; }
        }
        dmSwitch.addEventListener('change', function() {
            toggleDarkMode();
            // sync state in case toggled elsewhere
            const theme = document.documentElement.getAttribute('data-theme');
            dmSwitch.checked = theme === 'dark';
            const icon = document.getElementById('dmLabelIcon');
            const label = document.getElementById('dmLabelText');
            if (icon && label) {
                if (theme === 'dark') { icon.className = 'bi bi-moon-stars me-2'; label.textContent = 'Dark Mode'; }
                else { icon.className = 'bi bi-sun me-2'; label.textContent = 'Light Mode'; }
            }
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
