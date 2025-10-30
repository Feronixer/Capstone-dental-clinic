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
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 2rem;
}

.account-container {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
}

/* Sidebar */
.profile-sidebar {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 12px;
}

.sidebar-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    border: 4px solid #2196F3;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    font-weight: 700;
    color: white;
    text-transform: uppercase;
    letter-spacing: 2px;
    box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
}

.profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2C3E50;
    text-align: center;
    margin: 1rem 0;
}

.profile-action-btn {
    width: 100%;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    background: #546e7a;
    color: white;
}

.profile-action-btn:hover {
    background: #455a64;
    transform: translateY(-2px);
}

.profile-action-btn.logout {
    background: #ef5350;
}

.profile-action-btn.logout:hover {
    background: #e53935;
}

/* Profile Details */
.profile-details-area {
    padding: 1rem;
}

.details-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.detail-block {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-block-label {
    font-weight: 700;
    color: #546e7a;
    font-size: 0.9rem;
    text-transform: uppercase;
}

.detail-block input,
.detail-block select {
    padding: 0.75rem;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    font-size: 1rem;
    background: white;
    transition: all 0.3s;
}

.detail-block input:focus,
.detail-block select:focus {
    outline: none;
    border-color: #2196F3;
}

.edit-button-container {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-update {
    background: #4caf50;
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-update:hover {
    background: #45a049;
    transform: translateY(-2px);
}

.btn-cancel {
    background: #757575;
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-cancel:hover {
    background: #616161;
}

/* Validation Messages */
.alert {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
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

    <div class="profile-page-padding">
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
                            <span class="detail-block-label">Birthday *</span>
                            <input type="date" name="birthday" id="birthday" value="{{ $userInfo->birthday ?? '' }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="detail-block">
                            <span class="detail-block-label">Age</span>
                            <input type="number" id="age" value="{{ $userInfo->age ?? '' }}" readonly style="background: #f5f5f5;">
                        </div>
                        <div class="detail-block">
                            <span class="detail-block-label">Sex *</span>
                            <select name="gender" id="gender" required>
                                <option value="">Select...</option>
                                <option value="Male" {{ ($userInfo->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ ($userInfo->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ ($userInfo->gender ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="detail-block">
                        <span class="detail-block-label">Email *</span>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="detail-block">
                        <span class="detail-block-label">Contact Number *</span>
                        <input type="text" name="phone" id="phone" value="{{ $userInfo->phone ?? '' }}" required>
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
    const profileForm = document.getElementById('profileForm');
    const birthdayInput = document.getElementById('birthday');
    const ageInput = document.getElementById('age');

    // Calculate age when birthday changes
    birthdayInput.addEventListener('change', function() {
        if (this.value) {
            const birthday = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthday.getFullYear();
            const monthDiff = today.getMonth() - birthday.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
                age--;
            }

            ageInput.value = age;
        }
    });

    // Calculate initial age if birthday exists
    if (birthdayInput.value) {
        birthdayInput.dispatchEvent(new Event('change'));
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
});
</script>

@endsection
