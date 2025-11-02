<aside class="navigation-bar-container">
    <!-- User Profile Section -->
    <div class="user-profile-section">
        <a href="{{ route('admin-profile') }}" class="user-profile-link">
            <div class="user-profile-avatar">
                <div class="user-initials-avatar">
                    @php
                        $firstName = Auth::user()->info && Auth::user()->info->first_name
                            ? Auth::user()->info->first_name
                            : (explode(' ', Auth::user()->name)[0] ?? 'U');
                        $lastName = Auth::user()->info && Auth::user()->info->last_name
                            ? Auth::user()->info->last_name
                            : (explode(' ', Auth::user()->name)[1] ?? '');
                        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                    @endphp
                    {{ $initials }}
                </div>
            </div>
            <div class="user-profile-info">
                <div class="user-profile-name">{{ Auth::user()->name }}</div>
                <div class="user-profile-role">
                    @if(Auth::user()->role)
                        {{ Auth::user()->role->role }}
                    @else
                        Administrator
                    @endif
                </div>
            </div>
        </a>
    </div>

    <nav class="navigation-bar">
        <!-- Main Navigation Section -->
        <div class="nav-section main-navigation">
            <div class="nav-section-label">
                <i class="bi bi-grid-3x3-gap"></i>
                <span>Main</span>
            </div>
            <ul class="nav-menu-list">
                <li class="{{ request()->routeIs('admin-dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin-dashboard') }}" class="nav-item-link">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-microsoft"></i>
                        </div>
                        <span class="nav-item-text">Dashboard</span>
                        @if(request()->routeIs('admin-dashboard'))
                            <div class="nav-active-indicator"></div>
                        @endif
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-appointment') ? 'active' : '' }}">
                    <a href="{{ route('admin-appointment') }}" class="nav-item-link">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-calendar4-week"></i>
                        </div>
                        <span class="nav-item-text">Appointments</span>
                        @if(request()->routeIs('admin-appointment'))
                            <div class="nav-active-indicator"></div>
                        @endif
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-account-management') ? 'active' : '' }}">
                    <a href="{{ route('admin-account-management') }}" class="nav-item-link">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-person-fill-gear"></i>
                        </div>
                        <span class="nav-item-text">User Management</span>
                        @if(request()->routeIs('admin-account-management'))
                            <div class="nav-active-indicator"></div>
                        @endif
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-content-management') ? 'active' : '' }}">
                    <a href="{{ route('admin-content-management') }}" class="nav-item-link">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-folder-fill"></i>
                        </div>
                        <span class="nav-item-text">Content Management</span>
                        @if(request()->routeIs('admin-content-management'))
                            <div class="nav-active-indicator"></div>
                        @endif
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-post-procedural') ? 'active' : '' }}">
                    <a href="{{ route('admin-post-procedural') }}" class="nav-item-link">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-file-earmark-post"></i>
                        </div>
                        <span class="nav-item-text">Post-Procedural Form</span>
                        @if(request()->routeIs('admin-post-procedural'))
                            <div class="nav-active-indicator"></div>
                        @endif
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-toothtalk') ? 'active' : '' }}">
                    <a href="{{ route('admin-toothtalk') }}" class="nav-item-link">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-chat-left-text"></i>
                        </div>
                        <span class="nav-item-text">Chatbot Helper</span>
                        @if(request()->routeIs('admin-toothtalk'))
                            <div class="nav-active-indicator"></div>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        <!-- Utilities & Settings Section -->
        <div class="nav-section utilities-section">
            <div class="nav-section-label">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </div>
            <ul class="nav-menu-list">
                <li class="{{ request()->routeIs('admin-notification') ? 'active' : '' }}">
                    <a href="{{ route('admin-notification') }}" class="nav-item-link">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-bell"></i>
                            @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                                <span class="notification-badge-nav">{{ $pendingRequestsCount }}</span>
                            @endif
                        </div>
                        <span class="nav-item-text">Notifications</span>
                        @if(request()->routeIs('admin-notification'))
                            <div class="nav-active-indicator"></div>
                        @endif
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-activity-logs*') ? 'active' : '' }}">
                    <a href="{{ route('admin-activity-logs') }}" class="nav-item-link">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <span class="nav-item-text">Activity Logs</span>
                        @if(request()->routeIs('admin-activity-logs*'))
                            <div class="nav-active-indicator"></div>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-item-link dark-mode-toggle-btn" onclick="toggleDarkMode(); return false;" title="Toggle Dark Mode">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-moon-stars"></i>
                        </div>
                        <span class="nav-item-text">Dark Mode</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-profile') ? 'active' : '' }}">
                    <a href="{{ route('admin-profile') }}" class="nav-item-link">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-person"></i>
                        </div>
                        <span class="nav-item-text">Profile</span>
                        @if(request()->routeIs('admin-profile'))
                            <div class="nav-active-indicator"></div>
                        @endif
                    </a>
                </li>
                <li>
                    <button type="button" class="nav-item-link nav-logout-btn" id="adminLogoutBtn">
                        <div class="nav-icon-wrapper">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <span class="nav-item-text">Logout</span>
                    </button>
                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </nav>
</aside>

<!-- Admin Logout Confirmation Modal -->
<div class="modal fade" id="adminLogoutModal" tabindex="-1" aria-labelledby="adminLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content logout-modal-content">
            <div class="modal-body text-center p-4">
                <div class="logout-icon-wrapper mb-3">
                    <i class="bi bi-box-arrow-right text-white"></i>
                </div>
                <h5 class="logout-modal-title mb-2" id="adminLogoutModalLabel">Logout</h5>
                <p class="logout-modal-message mb-4">Are you sure you want to logout?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel-logout" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-confirm-logout" id="confirmAdminLogoutBtn">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded');
        return;
    }

    // Admin logout button
    const adminLogoutBtn = document.getElementById('adminLogoutBtn');
    if (adminLogoutBtn) {
        adminLogoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const modalElement = document.getElementById('adminLogoutModal');
            if (modalElement) {
                const logoutModal = new bootstrap.Modal(modalElement);
                logoutModal.show();
            }
        });
    }

    // Confirm logout button
    const confirmLogoutBtn = document.getElementById('confirmAdminLogoutBtn');
    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', function() {
            const logoutForm = document.getElementById('admin-logout-form');
            if (logoutForm) {
                logoutForm.submit();
            }
        });
    }
});
</script>

<style>
    /* User Profile Section - Compact */
    .user-profile-section {
        padding: 0.75rem;
        flex-shrink: 0;
    }

    .user-profile-link {
        padding: 0.5rem;
        gap: 0.625rem;
    }

    .user-initials-avatar {
        width: 40px !important;
        height: 40px !important;
        font-size: 0.95rem !important;
    }

    .user-profile-name {
        font-size: 0.875rem !important;
        margin-bottom: 0.1rem;
    }

    .user-profile-role {
        font-size: 0.7rem !important;
    }

    /* Enhanced Navigation Styles - Compact No-Scroll Version */
    .navigation-bar {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding: 0.5rem 0;
        overflow: hidden;
        justify-content: space-between;
        min-height: 0;
    }

    .nav-section {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        flex-shrink: 0;
    }

    .nav-section-label {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 1rem;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 0.125rem;
    }

    .nav-section-label i {
        font-size: 0.7rem;
        opacity: 0.8;
    }

    .nav-menu-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }

    .nav-menu-list li {
        position: relative;
        margin: 0;
    }

    .nav-item-link,
    .nav-logout-btn {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.5rem 0.875rem;
        margin: 0 0.5rem;
        border-radius: 8px;
        text-decoration: none;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
        font-size: 0.8rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border: none;
        background: transparent;
        width: calc(100% - 1rem);
        text-align: left;
        cursor: pointer;
    }

    .nav-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.08);
        transition: all 0.3s ease;
        position: relative;
        flex-shrink: 0;
    }

    .nav-icon-wrapper i {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
    }

    .nav-item-text {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .nav-active-indicator {
        position: absolute;
        right: 0.625rem;
        width: 3px;
        height: 50%;
        background: linear-gradient(180deg, #60a5fa 0%, #3b82f6 100%);
        border-radius: 2px;
        opacity: 1;
        transition: all 0.3s ease;
    }

    /* Hover States */
    .nav-item-link:hover,
    .nav-logout-btn:hover {
        background: rgba(255, 255, 255, 0.12);
        color: white;
        transform: translateX(3px);
    }

    .nav-item-link:hover .nav-icon-wrapper,
    .nav-logout-btn:hover .nav-icon-wrapper {
        background: rgba(255, 255, 255, 0.15);
        transform: scale(1.05);
    }

    .nav-item-link:hover .nav-icon-wrapper i,
    .nav-logout-btn:hover .nav-icon-wrapper i {
        color: white;
        transform: scale(1.1);
    }

    /* Active States */
    .nav-menu-list li.active .nav-item-link,
    .nav-menu-list li.active .nav-logout-btn {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.15) 100%);
        color: white;
        border-left: 3px solid #60a5fa;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .nav-menu-list li.active .nav-icon-wrapper {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .nav-menu-list li.active .nav-icon-wrapper i {
        color: white;
        transform: scale(1.1);
    }

    /* Notification Badge */
    .notification-badge-nav {
        position: absolute;
        top: -3px;
        right: -3px;
        min-width: 16px;
        height: 16px;
        padding: 0 4px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--navigation-bar-color, #1a202c);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        z-index: 10;
    }

    /* Logout Button Special Styling */
    .nav-logout-btn {
        color: rgba(239, 68, 68, 0.9) !important;
    }

    .nav-logout-btn:hover {
        background: rgba(239, 68, 68, 0.15) !important;
        color: #ef4444 !important;
    }

    .nav-logout-btn .nav-icon-wrapper {
        background: rgba(239, 68, 68, 0.1);
    }

    .nav-logout-btn:hover .nav-icon-wrapper {
        background: rgba(239, 68, 68, 0.2);
    }

    .nav-logout-btn:hover .nav-icon-wrapper i {
        color: #ef4444;
    }

    /* Dark Mode Toggle Button */
    .dark-mode-toggle-btn {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .navigation-bar-container {
            width: 220px !important;
            min-width: 220px !important;
            max-width: 220px !important;
        }

        .nav-item-link,
        .nav-logout-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
        }

        .nav-icon-wrapper {
            width: 26px;
            height: 26px;
        }

        .nav-icon-wrapper i {
            font-size: 0.85rem;
        }

        .nav-section-label {
            padding: 0.375rem 0.75rem;
            font-size: 0.6rem;
        }

        .navigation-bar {
            gap: 0.75rem;
            padding: 0.5rem 0;
        }
    }

    /* Dark Mode Support */
    [data-theme="dark"] .nav-section-label {
        color: rgba(255, 255, 255, 0.5);
    }

    [data-theme="dark"] .nav-item-link,
    [data-theme="dark"] .nav-logout-btn {
        color: rgba(255, 255, 255, 0.8);
    }

    [data-theme="dark"] .nav-item-link:hover,
    [data-theme="dark"] .nav-logout-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    [data-theme="dark"] .nav-menu-list li.active .nav-item-link,
    [data-theme="dark"] .nav-menu-list li.active .nav-logout-btn {
        background: linear-gradient(135deg, rgba(96, 165, 250, 0.2) 0%, rgba(59, 130, 246, 0.15) 100%);
        border-left-color: #60a5fa;
    }

    [data-theme="dark"] .nav-menu-list li.active .nav-icon-wrapper {
        background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    }

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

