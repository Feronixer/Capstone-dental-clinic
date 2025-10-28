<div class="modal fade" id="addUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/staff/account-management" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addUserModalLabel">Add User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Username --}}
                    <div class="form-floating mb-3">
                        <input name="username" type="text"
                               id="floatingUsername"
                               placeholder="Enter username"
                               value="{{ old('username') }}"
                               class="form-control form-control-sm @error('username') is-invalid @enderror">
                        <label for="floatingUsername">Username</label>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- First Name --}}
                    <div class="form-floating mb-3">
                        <input name="first_name" type="text"
                               id="floatingFirstName"
                               placeholder="Enter first name"
                               value="{{ old('first_name') }}"
                               class="form-control form-control-sm @error('first_name') is-invalid @enderror">
                        <label for="floatingFirstName">First Name</label>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Middle Name --}}
                    <div class="form-floating mb-3">
                        <input name="middle_name" type="text"
                               id="floatingMiddleName"
                               placeholder="Enter middle name"
                               value="{{ old('middle_name') }}"
                               class="form-control form-control-sm">
                        <label for="floatingMiddleName">Middle Name (Optional)</label>
                    </div>

                    {{-- Last Name --}}
                    <div class="form-floating mb-3">
                        <input name="last_name" type="text"
                               id="floatingLastName"
                               placeholder="Enter last name"
                               value="{{ old('last_name') }}"
                               class="form-control form-control-sm @error('last_name') is-invalid @enderror">
                        <label for="floatingLastName">Last Name</label>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-floating mb-3">
                        <input name="email" type="email"
                               id="floatingEmail"
                               placeholder="name@example.com"
                               value="{{ old('email') }}"
                               class="form-control form-control-sm @error('email') is-invalid @enderror">
                        <label for="floatingEmail">Email</label>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Phone --}}
                    <div class="form-floating mb-3">
                        <input name="phone" type="text"
                               id="floatingPhone"
                               placeholder="Enter phone number"
                               value="{{ old('phone') }}"
                               class="form-control form-control-sm @error('phone') is-invalid @enderror">
                        <label for="floatingPhone">Phone Number</label>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Role --}}
                    <div class="form-floating mb-3">
                        <select name="role_id"
                                id="floatingRole"
                                class="form-select form-select-sm @error('role_id') is-invalid @enderror">
                            <option disabled {{ old('role_id') ? '' : 'selected' }}>--</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->role }}
                                </option>
                            @endforeach
                        </select>
                        <label for="floatingRole">Role</label>
                        @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-floating mb-3">
                        <input name="password" type="password"
                               id="floatingPassword"
                               placeholder="Enter password"
                               class="form-control form-control-sm @error('password') is-invalid @enderror">
                        <label for="floatingPassword">Password</label>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-floating mb-3">
                        <input name="confirm_password" type="password"
                               id="floatingConfirmPassword"
                               placeholder="Confirm password"
                               class="form-control form-control-sm @error('confirm_password') is-invalid @enderror">
                        <label for="floatingConfirmPassword">Confirm Password</label>
                        @error('confirm_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

