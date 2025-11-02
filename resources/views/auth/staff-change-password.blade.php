@extends('layout.auth.app')
@section('content')
<section class="login-container">
    @if (session('error'))
        <x-toast-message type="danger" :message="session('error')" />
    @endif
    @if ($errors->any())
        <x-toast-message type="danger" message="Please fix the errors below" />
    @endif

    <div class="login-card">
        <div class="login-card-content">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-2.png') }}" alt="Logo" class="login-logo mb-3">
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-shield-exclamation me-2"></i>
                    <strong>Staff Password Change Required</strong>
                    <p class="mb-0 mt-2 small">For security reasons, you must change your password on first login.</p>
                </div>
                <h4 class="fw-bold">Change Staff Password</h4>
                <p class="text-secondary">
                    <i class="bi bi-shield-lock-fill me-1"></i>Staff Portal
                </p>
            </div>

            <form action="{{ route('staff.password.change.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                               name="current_password" id="current_password" placeholder="Enter current password" autocomplete="current-password" required>
                    </div>
                    @error('current_password')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-key-fill"></i>
                        </span>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                               name="new_password" id="new_password" placeholder="Enter new password (min. 8 characters)" autocomplete="new-password" required>
                    </div>
                    @error('new_password')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                    <small class="text-muted">Password must be at least 8 characters long</small>
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-check2-circle"></i>
                        </span>
                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror"
                               name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm new password" autocomplete="new-password" required>
                    </div>
                    @error('new_password_confirmation')
                        <p class="text-danger mt-1">* {{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-custom-primary">
                    <i class="bi bi-shield-lock me-2"></i>Change Password
                </button>

                <div class="text-center mt-3">
                    <button type="button" class="btn btn-link text-muted" id="logoutBtn">
                        <i class="bi bi-box-arrow-left me-1"></i>Logout
                    </button>
                    <form id="logout-form" action="{{ route('staff.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </form>
        </div>
        <div class="login-card-image">
            <img src="{{ asset('images/tooth.png') }}" alt="Tooth Image">
        </div>
    </div>
    <p class="text-center mt-4 text-secondary">
        &copy; {{ date('Y') }} JValera Dental Clinic. All rights reserved.
    </p>
</section>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content logout-modal-content">
            <div class="modal-body text-center p-4">
                <div class="logout-icon-wrapper mb-3">
                    <i class="bi bi-box-arrow-right text-white"></i>
                </div>
                <h5 class="logout-modal-title mb-2" id="logoutModalLabel">Logout</h5>
                <p class="logout-modal-message mb-4">Are you sure you want to logout?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel-logout" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-confirm-logout" id="confirmLogoutBtn">Logout</button>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure Bootstrap is available
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap is not loaded');
            return;
        }

        // Add event listener to logout button
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const modalElement = document.getElementById('logoutModal');
                if (!modalElement) {
                    console.error('Logout modal not found');
                    return;
                }
                const logoutModal = new bootstrap.Modal(modalElement);
                logoutModal.show();
            });
        }

        // Add event listener for confirm button
        const confirmBtn = document.getElementById('confirmLogoutBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                const logoutForm = document.getElementById('logout-form');
                if (logoutForm) {
                    logoutForm.submit();
                }
            });
        }
    });
</script>
@endsection

