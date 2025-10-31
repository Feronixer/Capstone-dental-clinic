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
        <ul>
            <div>
                <li class="{{ request()->routeIs('admin-dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin-dashboard') }}">
                        <i class="bi bi-microsoft"></i>
                        Dashboard
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-account-management') ? 'active' : '' }}">
                    <a href="{{ route('admin-account-management') }}">
                        <i class="bi bi-person-fill-gear"></i>
                        Account Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-appointment') ? 'active' : '' }}">
                    <a href="{{ route('admin-appointment') }}">
                        <i class="bi bi-calendar4-week"></i>
                        Appointments
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-content-management') ? 'active' : '' }}">
                    <a href="{{ route('admin-content-management') }}">
                        <i class="bi bi-folder-fill"></i>
                        Content Management
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-post-procedural') ? 'active' : '' }}">
                    <a href="{{ route('admin-post-procedural') }}">
                        <i class="bi bi-file-earmark-post"></i>
                        Post-Procedural Form
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-toothtalk') ? 'active' : '' }}">
                    <a href="{{ route('admin-toothtalk') }}">
                        <i class="bi bi-chat-left-text"></i>
                        Chatbot
                    </a>
                </li>
            </div>
            <div>
                <li class="{{ request()->routeIs('admin-notification') ? 'active' : '' }}">
                    <a href="{{ route('admin-notification') }}">
                        <i class="bi bi-bell"></i>
                        <span style="position: relative; display: inline-flex; align-items: center; gap: 0.5rem;">
                            Notifications
                            @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                                <span class="notification-badge">{{ $pendingRequestsCount }}</span>
                            @endif
                        </span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-activity-logs*') ? 'active' : '' }}">
                    <a href="{{ route('admin-activity-logs') }}">
                        <i class="bi bi-clock-history"></i>
                        Activity Logs
                    </a>
                </li>
                <li>
                    <a href="#" class="dark-mode-toggle-btn" onclick="toggleDarkMode(); return false;" title="Toggle Dark Mode">
                        <i class="bi bi-moon-stars"></i>
                        <span>Dark Mode</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin-profile') ? 'active' : '' }}">
                    <a href="{{ route('admin-profile') }}">
                        <i class="bi bi-person"></i>
                        Profile
                    </a>
                </li>
                <li>
                    <button type="button" class="btn btn-link text-light text-decoration-none" id="adminLogoutBtn">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </div>
        </ul>
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

