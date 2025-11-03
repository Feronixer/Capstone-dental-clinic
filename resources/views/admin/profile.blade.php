@extends('layout.admin.app')
@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Success Message -->
    @if(session('success'))
        <x-toast-message type="success" :message="session('success')" />
    @endif

    <!-- Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #3498db 0%, #2574b8 100%);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="profile-initials-container me-4">
                            @php
                                $firstName = $userInfo && $userInfo->first_name
                                    ? $userInfo->first_name
                                    : (explode(' ', $user->name)[0] ?? 'U');
                                $lastName = $userInfo && $userInfo->last_name
                                    ? $userInfo->last_name
                                    : (explode(' ', $user->name)[1] ?? '');
                                $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                            @endphp
                            <div class="profile-initials-avatar">
                                {{ $initials }}
                            </div>
                        </div>
                        <div class="text-white">
                            <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                            <p class="mb-0 opacity-75">
                                <i class="bi bi-envelope me-2"></i>{{ $user->email }}
                            </p>
                            <p class="mb-0 opacity-75">
                                <i class="bi bi-shield-check me-2"></i>{{ $user->role ? $user->role->role : 'Administrator' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="row g-4">
        <!-- Personal Information -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-circle me-2" style="color: #3498db;"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="profileForm">
                        @csrf
                        <div class="row g-3">
                            <!-- Username -->
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                            </div>

                            <!-- First Name -->
                            <div class="col-md-4">
                                <label for="first_name" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $userInfo->first_name ?? '' }}" required>
                            </div>

                            <!-- Middle Name -->
                            <div class="col-md-4">
                                <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ $userInfo->middle_name ?? '' }}">
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-4">
                                <label for="last_name" class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $userInfo->last_name ?? '' }}" required>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-3">
                                <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $userInfo->phone ?? '' }}" placeholder="09XXXXXXXXX">
                            </div>

                            <!-- Birthday -->
                            <div class="col-md-3">
                                <label for="birthday" class="form-label fw-semibold">Birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" value="{{ $userInfo->birthday ?? '' }}" readonly style="background: #f5f5f5; cursor: not-allowed;">
                            </div>

                            <!-- Age -->
                            <div class="col-md-3">
                                <label for="age" class="form-label fw-semibold">Age</label>
                                <input type="number" class="form-control" id="age" name="age" value="{{ $userInfo->age ?? '' }}" min="0" readonly style="background: #f5f5f5; cursor: not-allowed;">
                            </div>

                            <!-- Gender -->
                            <div class="col-md-3">
                                <label for="gender" class="form-label fw-semibold">Gender</label>
                                <select class="form-select" id="gender" name="gender" disabled style="background: #f5f5f5; cursor: not-allowed;">
                                    <option value="">Select</option>
                                    <option value="Male" {{ ($userInfo->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ ($userInfo->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <!-- Hidden input to preserve value when disabled -->
                                <input type="hidden" name="gender" value="{{ $userInfo->gender ?? '' }}">
                            </div>

                            <!-- Address -->
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter your complete address">{{ $userInfo->address ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Save Changes
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-4" onclick="location.reload()">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="col-lg-4">
            <!-- Change Password -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-shield-lock me-2" style="color: #3498db;"></i>Change Password
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="passwordForm">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#current_password" aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#new_password" aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#new_password_confirmation" aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-key me-2"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 pb-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2" style="color: #3498db;"></i>Account Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted">Member Since</span>
                        <span class="fw-semibold">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted">Last Updated</span>
                        <span class="fw-semibold">{{ $user->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">User ID</span>
                        <span class="fw-semibold">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-initials-container {
    width: 120px;
    height: 120px;
    flex-shrink: 0;
}

.profile-initials-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    border: 4px solid white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    text-transform: uppercase;
    letter-spacing: 2px;
}

.form-control:focus, .form-select:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #3498db 0%, #2574b8 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2574b8 0%, #1a5a8e 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile Form Submit
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Saving...';

        fetch('{{ route("admin-profile.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Success!', data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast('Error!', data.message || 'Failed to update profile', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error!', 'Failed to update profile', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Password Form Submit
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Check if passwords match
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('new_password_confirmation').value;

        if (newPassword !== confirmPassword) {
            showToast('Error!', 'New passwords do not match', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Updating...';

        fetch('{{ route("admin-profile.update-password") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // If backend requests logout, redirect
                if (data.logout_redirect) {
                    window.location.href = data.logout_redirect;
                    return;
                }
                showToast('Success!', data.message, 'success');
                this.reset();
            } else {
                showToast('Error!', data.message || 'Failed to update password', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error!', 'Failed to update password', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Toggle password visibility buttons
    document.querySelectorAll('.toggle-password').forEach(function(btn){
        btn.addEventListener('click', function(){
            const input = document.querySelector(this.getAttribute('data-target'));
            if(!input) return;
            const icon = this.querySelector('i');
            const isPwd = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPwd ? 'text' : 'password');
            if(icon){
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            }
            this.setAttribute('aria-label', isPwd ? 'Hide password' : 'Show password');
        });
    });

    // Toast notification function
    function showToast(title, message, type) {
        // Create toast element
        const toastHTML = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        // Add to page
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = toastContainer.lastElementChild;
        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        // Remove after hide
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    // Calculate age from birthday (read-only, but calculate on load)
    function calculateAgeFromBirthday() {
        const birthdayInput = document.getElementById('birthday');
        const ageInput = document.getElementById('age');

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
    const birthdayInput = document.getElementById('birthday');
    if (birthdayInput && birthdayInput.value) {
        calculateAgeFromBirthday();
    }
});
</script>
@endsection
