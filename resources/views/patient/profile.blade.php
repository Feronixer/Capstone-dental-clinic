@extends('layout.patient.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/patient-calendar.css') }}">

<style>
.profile-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

.profile-page-padding {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(33, 150, 243, 0.1);
    padding: 2.5rem;
    border: 1px solid #e3f2fd;
}

.account-container {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 2.5rem;
}

/* Sidebar */
.profile-sidebar {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    padding: 2.5rem 2rem;
    background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(25, 118, 210, 0.25);
}

.sidebar-avatar {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    background: white;
    border: 5px solid rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    font-weight: 700;
    color: #1976D2;
    text-transform: uppercase;
    letter-spacing: 2px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
}

.sidebar-avatar:hover {
    transform: scale(1.05);
}

.profile-name {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    text-align: center;
    margin: 0.5rem 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.profile-action-btn {
    width: 100%;
    padding: 0.875rem 1.5rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.15);
    color: white;
    backdrop-filter: blur(10px);
}

.profile-action-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.profile-action-btn.logout {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
}

.profile-action-btn.logout:hover {
    background: rgba(255, 87, 87, 0.9);
    border-color: rgba(255, 87, 87, 1);
}

/* Profile Details */
.profile-details-area {
    padding: 1.5rem;
    background: linear-gradient(to bottom, #ffffff 0%, #f8fbff 100%);
    border-radius: 12px;
    border: 1px solid #e3f2fd;
}

.details-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.75rem;
}

.detail-block {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    border: 2px solid #1976D2;
    transition: all 0.3s ease;
}

.detail-block:hover {
    border-color: #1565C0;
    box-shadow: 0 2px 8px rgba(25, 118, 210, 0.15);
}

.detail-block-label {
    font-weight: 700;
    color: #000000;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.detail-block input,
.detail-block select {
    padding: 0.875rem 1rem;
    border: 2px solid #1976D2;
    border-radius: 8px;
    font-size: 1rem;
    background: #f8fbff;
    transition: all 0.3s ease;
    color: #000000;
}

.detail-block input:hover,
.detail-block select:hover {
    border-color: #1565C0;
    background: white;
}

/* Force dark mode override for all inputs - comprehensive */
[data-theme="dark"] .detail-block input,
[data-theme="dark"] .detail-block input[type="text"],
[data-theme="dark"] .detail-block input[type="email"],
[data-theme="dark"] .detail-block input[type="date"],
[data-theme="dark"] .detail-block input[type="number"],
[data-theme="dark"] .detail-block input[type="tel"],
[data-theme="dark"] .detail-block select {
    background: var(--dm-input-bg, #0f172a) !important;
    background-color: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

.detail-block input:focus,
.detail-block select:focus {
    outline: none;
    border-color: #1565C0;
    background: white;
    box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.2);
    color: #000000;
}

.edit-button-container {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 2px solid #e3f2fd;
}

.btn-update {
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    border: none;
    padding: 0.875rem 2.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

.btn-update:hover {
    background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(33, 150, 243, 0.4);
}

.btn-cancel {
    background: white;
    color: #1976D2;
    border: 2px solid #1976D2;
    padding: 0.875rem 2.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #e3f2fd;
    border-color: #1565C0;
    color: #1565C0;
    transform: translateY(-2px);
}

/* Validation Messages */
.alert {
    padding: 1rem 1.25rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border-left: 4px solid;
}

.alert-success {
    background: #e8f5e9;
    color: #1b5e20;
    border-left-color: #4caf50;
    border: 2px solid #c8e6c9;
}

.alert-danger {
    background: #ffebee;
    color: #b71c1c;
    border-left-color: #ef5350;
    border: 2px solid #ffcdd2;
}

.is-invalid {
    border-color: #ef5350 !important;
}

.invalid-feedback {
    color: #ef5350;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .profile-container {
        padding: 1rem;
    }

    .account-container {
        grid-template-columns: 1fr;
    }

    .details-row {
        grid-template-columns: 1fr;
    }
}

/* ============================================
   DARK MODE STYLES FOR PATIENT PROFILE PAGE
   ============================================ */

/* Profile Container Dark Mode */
[data-theme="dark"] .profile-container {
    background: transparent !important;
}

/* Profile Page Padding Dark Mode - Force override white background */
[data-theme="dark"] .profile-page-padding,
[data-theme="dark"] div.profile-page-padding,
[data-theme="dark"] .profile-page-padding[class] {
    background: var(--dm-card-bg, #1e293b) !important;
    background-color: var(--dm-card-bg, #1e293b) !important;
    border: 1px solid var(--dm-border-color, #334155) !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
}

/* Additional override for any white background inheritance */
[data-theme="dark"] .profile-container .profile-page-padding,
[data-theme="dark"] .profile-page-padding,
[data-theme="dark"] div.profile-page-padding {
    background: var(--dm-card-bg, #1e293b) !important;
    background-color: var(--dm-card-bg, #1e293b) !important;
    background-image: none !important;
}

/* Profile Sidebar Dark Mode */
[data-theme="dark"] .profile-sidebar {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
    border: 1px solid var(--dm-border-color, #334155) !important;
}

/* Sidebar Avatar Dark Mode */
[data-theme="dark"] .sidebar-avatar {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    border-color: rgba(59, 130, 246, 0.3) !important;
    color: white !important;
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .sidebar-avatar:hover {
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.5) !important;
}

/* Profile Name Dark Mode */
[data-theme="dark"] .profile-name {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Profile Action Buttons Dark Mode */
[data-theme="dark"] .profile-action-btn {
    background: var(--dm-bg-tertiary, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
    border: 1px solid var(--dm-border-color, #475569) !important;
}

[data-theme="dark"] .profile-action-btn:hover {
    background: var(--dm-bg-primary, #0f172a) !important;
    border-color: #3b82f6 !important;
}

[data-theme="dark"] .profile-action-btn.logout {
    background: #ef4444 !important;
    border-color: #ef4444 !important;
}

[data-theme="dark"] .profile-action-btn.logout:hover {
    background: #dc2626 !important;
    border-color: #dc2626 !important;
}

/* Profile Details Area Dark Mode */
[data-theme="dark"] .profile-details-area {
    background: linear-gradient(to bottom, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-secondary, #1e293b) 100%) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Details Row Dark Mode */
[data-theme="dark"] .details-row {
    background: transparent !important;
}

/* Detail Block Dark Mode */
[data-theme="dark"] .detail-block {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #475569) !important;
}

[data-theme="dark"] .detail-block:hover {
    border-color: #3b82f6 !important;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15) !important;
}

/* Detail Block Label Dark Mode - Ensure labels are light colored */
[data-theme="dark"] .detail-block-label {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Form Inputs Dark Mode - All input types - Remove duplicate and ensure comprehensive */
[data-theme="dark"] .profile-details-area .detail-block input,
[data-theme="dark"] .profile-details-area .detail-block input[type="text"],
[data-theme="dark"] .profile-details-area .detail-block input[type="email"],
[data-theme="dark"] .profile-details-area .detail-block input[type="date"],
[data-theme="dark"] .profile-details-area .detail-block input[type="number"],
[data-theme="dark"] .profile-details-area .detail-block input[type="tel"],
[data-theme="dark"] .profile-details-area .detail-block select {
    background: var(--dm-input-bg, #0f172a) !important;
    background-color: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .detail-block input:focus,
[data-theme="dark"] .detail-block select:focus {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .detail-block input::placeholder {
    color: var(--dm-text-muted, #64748b) !important;
    opacity: 0.7 !important;
}

/* Read-only Age Input Dark Mode */
[data-theme="dark"] input[readonly],
[data-theme="dark"] input[readonly][style*="background: #f5f5f5"] {
    background: var(--dm-bg-tertiary, #334155) !important;
    border-color: var(--dm-border-color, #475569) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
    cursor: not-allowed !important;
}

/* Select dropdown options dark mode */
[data-theme="dark"] .detail-block select option {
    background: var(--dm-input-bg, #0f172a) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Account Container Dark Mode */
[data-theme="dark"] .account-container {
    background: transparent !important;
}

/* Update Button Dark Mode */
[data-theme="dark"] .btn-update {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
}

[data-theme="dark"] .btn-update:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4) !important;
}

/* Cancel Button Dark Mode */
[data-theme="dark"] .btn-cancel {
    background: var(--dm-bg-tertiary, #334155) !important;
    border: 1px solid var(--dm-border-color, #475569) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .btn-cancel:hover {
    background: var(--dm-bg-primary, #0f172a) !important;
    border-color: #3b82f6 !important;
}

/* Alert Dark Mode */
[data-theme="dark"] .alert {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .alert-success {
    background: rgba(34, 197, 94, 0.15) !important;
    color: #86efac !important;
    border-color: #22c55e !important;
}

[data-theme="dark"] .alert-danger {
    background: rgba(239, 68, 68, 0.15) !important;
    color: #fca5a5 !important;
    border-color: #ef4444 !important;
}

/* Invalid Input Dark Mode */
[data-theme="dark"] .is-invalid {
    border-color: #ef4444 !important;
}

[data-theme="dark"] .invalid-feedback {
    color: #fca5a5 !important;
}

/* ============================================
   DATE PICKER / CALENDAR DARK MODE STYLES
   ============================================ */

/* Date Input Dark Mode - Base Styles */
[data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
    opacity: 0.8;
    cursor: pointer;
}

[data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator:hover {
    opacity: 1;
}

/* Date Picker Dropdown Dark Mode */
[data-theme="dark"] input[type="date"]::-webkit-datetime-edit-text,
[data-theme="dark"] input[type="date"]::-webkit-datetime-edit-month-field,
[data-theme="dark"] input[type="date"]::-webkit-datetime-edit-day-field,
[data-theme="dark"] input[type="date"]::-webkit-datetime-edit-year-field {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Date Picker Popup Dark Mode Styles */
[data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23f1f5f9' viewBox='0 0 16 16'%3E%3Cpath d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z'/%3E%3C/svg%3E");
}

/* For browsers that support pseudo-elements on date inputs */
[data-theme="dark"] input[type="date"]::after {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Shadow DOM styles for date picker (requires JavaScript or user agent stylesheet) */
/* These will be applied via JavaScript since we can't directly style shadow DOM */
</style>

<div class="profile-container">
    <!-- Header Section -->
    <div class="calendar-header">
        <div class="header-content">
            <div>
                <h1 class="page-title">My Profile</h1>
                <p class="page-subtitle">Manage your personal information and account settings</p>
            </div>
        </div>
    </div>

    <div class="profile-page-padding" id="profileMainCard">
        <!-- Validation Messages -->
        <div id="validation-messages" style="display: none;"></div>

        <div class="account-container">
            <aside class="profile-sidebar">
                <div class="sidebar-avatar">
                    @if($userInfo && $userInfo->first_name && $userInfo->last_name)
                        {{ strtoupper(substr($userInfo->first_name, 0, 1)) }}{{ strtoupper(substr($userInfo->last_name, 0, 1)) }}
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->name, 1, 1)) }}
                    @endif
                </div>
                <h2 class="profile-name">
                    {{ $userInfo ? trim($userInfo->first_name . ' ' . $userInfo->last_name) : $user->name }}
                </h2>

                <button class="profile-action-btn" onclick="window.location.href='{{ route('password.change') }}'">Change Password</button>
                <form method="POST" action="{{ route('patient.logout') }}">
                    @csrf
                    <button type="submit" class="profile-action-btn logout">Log Out</button>
                </form>
            </aside>

            <section class="profile-details-area">
                <form id="profileForm">
                    @csrf
                    <div class="details-row">
                        <div class="detail-block">
                            <span class="detail-block-label">First Name *</span>
                            <input type="text" name="first_name" id="first_name" value="{{ $userInfo->first_name ?? '' }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="detail-block">
                            <span class="detail-block-label">Middle Name</span>
                            <input type="text" name="middle_name" id="middle_name" value="{{ $userInfo->middle_name ?? '' }}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="detail-block">
                            <span class="detail-block-label">Last Name *</span>
                            <input type="text" name="last_name" id="last_name" value="{{ $userInfo->last_name ?? '' }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="details-row">
                        <div class="detail-block">
                            <span class="detail-block-label">Phone Number *</span>
                            <input type="text" name="phone" id="phone" value="{{ $userInfo->phone ?? '' }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="detail-block">
                            <span class="detail-block-label">Birthday *</span>
                            <input type="date" name="birthday" id="birthday" value="{{ $userInfo->birthday ?? '' }}" readonly style="background: #e3f2fd; border-color: #1976D2; color: #000000; cursor: not-allowed;">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="detail-block">
                            <span class="detail-block-label">Age</span>
                            <input type="number" id="age" value="{{ $userInfo->age ?? '' }}" readonly style="background: #e3f2fd; border-color: #1976D2; color: #000000; cursor: not-allowed;">
                        </div>
                        <div class="detail-block">
                            <span class="detail-block-label">Sex *</span>
                            <select name="gender" id="gender" disabled style="background: #e3f2fd; border-color: #1976D2; color: #000000; cursor: not-allowed;">
                                <option value="">Select...</option>
                                <option value="Male" {{ ($userInfo->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ ($userInfo->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <!-- Hidden input to preserve value when disabled -->
                            <input type="hidden" name="gender" value="{{ $userInfo->gender ?? '' }}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="detail-block">
                        <span class="detail-block-label">Email *</span>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="edit-button-container">
                        <button type="button" class="btn-cancel" onclick="window.location.reload()">Cancel</button>
                        <button type="submit" class="btn-update">Update Profile</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Force dark mode background for profile card
    function applyDarkModeStyles() {
        const theme = document.documentElement.getAttribute('data-theme');
        const profileCard = document.getElementById('profileMainCard');

        if (theme === 'dark' && profileCard) {
            profileCard.style.setProperty('background', '#1e293b', 'important');
            profileCard.style.setProperty('background-color', '#1e293b', 'important');
            profileCard.style.setProperty('border', '1px solid #334155', 'important');
        } else if (profileCard) {
            profileCard.style.removeProperty('background');
            profileCard.style.removeProperty('background-color');
            profileCard.style.removeProperty('border');
        }
    }

    // Apply on load
    applyDarkModeStyles();

    // Watch for theme changes
    const observer = new MutationObserver(applyDarkModeStyles);
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-theme']
    });

    const profileForm = document.getElementById('profileForm');
    const birthdayInput = document.getElementById('birthday');
    const ageInput = document.getElementById('age');

    // Calculate age from birthday (read-only, but calculate on load)
    function calculateAgeFromBirthday() {
        if (birthdayInput && ageInput && birthdayInput.value) {
            const birthday = new Date(birthdayInput.value);
            const today = new Date();
            let age = today.getFullYear() - birthday.getFullYear();
            const monthDiff = today.getMonth() - birthday.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
                age--;
            }

            ageInput.value = age;
        }
    }

    // Calculate initial age if birthday exists (birthday is read-only, so no change listener needed)
    if (birthdayInput && birthdayInput.value) {
        calculateAgeFromBirthday();
    }

    // Handle form submission
    profileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });

    function updateProfile() {
        // Clear previous errors
        clearValidationErrors();

        const formData = new FormData(profileForm);
        const submitBtn = profileForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Updating...';

        fetch('{{ route("patient-profile.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Profile updated successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                if (data.errors) {
                    showValidationErrors(data.errors);
                }
                showMessage(data.message || 'Error updating profile', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error updating profile. Please try again.', 'danger');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    }

    function showMessage(message, type) {
        const messagesDiv = document.getElementById('validation-messages');
        messagesDiv.innerHTML = `
            <div class="alert alert-${type}">
                ${message}
            </div>
        `;
        messagesDiv.style.display = 'block';

        // Scroll to top to show message
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Auto-hide success messages
        if (type === 'success') {
            setTimeout(() => {
                messagesDiv.style.display = 'none';
            }, 3000);
        }
    }

    function showValidationErrors(errors) {
        for (const [field, messages] of Object.entries(errors)) {
            const input = document.getElementById(field);
            if (input) {
                input.classList.add('is-invalid');
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = Array.isArray(messages) ? messages[0] : messages;
                }
            }
        }
    }

    function clearValidationErrors() {
        document.querySelectorAll('.is-invalid').forEach(input => {
            input.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.textContent = '';
        });
        document.getElementById('validation-messages').style.display = 'none';
    }

    // Apply dark mode styles to date picker calendar widget
    function applyDatePickerDarkMode() {
        const theme = document.documentElement.getAttribute('data-theme');

        if (theme === 'dark') {
            // Create and inject dark mode styles for date picker
            const styleId = 'date-picker-dark-mode';
            if (!document.getElementById(styleId)) {
                const style = document.createElement('style');
                style.id = styleId;
                style.textContent = `
                    /* Date Picker Dark Mode - Override browser defaults */
                    input[type="date"]::-webkit-calendar-picker-indicator {
                        filter: invert(1) brightness(1.2);
                        opacity: 0.9;
                        cursor: pointer;
                    }

                    input[type="date"]::-webkit-calendar-picker-indicator:hover {
                        opacity: 1;
                        filter: invert(1) brightness(1.5);
                    }

                    /* Style the date input text fields in dark mode */
                    input[type="date"]::-webkit-datetime-edit-text,
                    input[type="date"]::-webkit-datetime-edit-month-field,
                    input[type="date"]::-webkit-datetime-edit-day-field,
                    input[type="date"]::-webkit-datetime-edit-year-field {
                        color: #f1f5f9 !important;
                        background: transparent !important;
                    }

                    input[type="date"]::-webkit-datetime-edit-text:hover,
                    input[type="date"]::-webkit-datetime-edit-month-field:hover,
                    input[type="date"]::-webkit-datetime-edit-day-field:hover,
                    input[type="date"]::-webkit-datetime-edit-year-field:hover {
                        background: rgba(59, 130, 246, 0.1) !important;
                    }
                `;
                document.head.appendChild(style);
            }
        } else {
            // Remove dark mode styles
            const styleElement = document.getElementById('date-picker-dark-mode');
            if (styleElement) {
                styleElement.remove();
            }
        }
    }

    // Apply on load
    applyDatePickerDarkMode();

    // Watch for theme changes
    const datePickerObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                applyDatePickerDarkMode();
            }
        });
    });

    datePickerObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-theme']
    });
});
</script>

@endsection
