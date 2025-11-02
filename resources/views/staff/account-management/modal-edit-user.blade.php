<div class="modal fade" id="editUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editUserForm">
                @csrf
                <input type="hidden" name="user_id" id="modalUserId">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editUserModalLabel">Edit User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- Username --}}
                    <div class="form-floating mb-3">
                        <input name="username" type="text" class="form-control form-control-sm" id="editFloatingUsername" placeholder="Enter username">
                        <label for="editFloatingUsername">Username</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- First Name --}}
                    <div class="form-floating mb-3">
                        <input name="first_name" type="text" class="form-control form-control-sm" id="editFloatingFirstName" placeholder="Enter first name">
                        <label for="editFloatingFirstName">First Name</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Middle Name --}}
                    <div class="form-floating mb-3">
                        <input name="middle_name" type="text" class="form-control form-control-sm" id="editFloatingMiddleName" placeholder="Enter middle name">
                        <label for="editFloatingMiddleName">Middle Name (Optional)</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Last Name --}}
                    <div class="form-floating mb-3">
                        <input name="last_name" type="text" class="form-control form-control-sm" id="editFloatingLastName" placeholder="Enter last name">
                        <label for="editFloatingLastName">Last Name</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Gender --}}
                    <div class="form-floating mb-3">
                        <select name="gender" class="form-select form-select-sm" id="editFloatingGender" required>
                            <option value="" class="text-muted small">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <label for="editFloatingGender">Gender <span class="text-danger">*</span></label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Birthday --}}
                    <div class="form-floating mb-3">
                        <input name="birthday" type="date" class="form-control form-control-sm" id="editFloatingBirthday" placeholder="Enter birthday" required>
                        <label for="editFloatingBirthday">Birthday <span class="text-danger">*</span></label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Age --}}
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control form-control-sm" id="editFloatingAge" placeholder="Age" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        <label for="editFloatingAge">Age <small class="text-muted">(auto-calculated)</small></label>
                    </div>

                    {{-- Email --}}
                    <div class="form-floating mb-3">
                        <input name="email" type="email" class="form-control form-control-sm" id="editFloatingEmail" placeholder="name@example.com">
                        <label for="editFloatingEmail">Email</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Phone --}}
                    <div class="form-floating mb-3">
                        <input name="phone" type="text" class="form-control form-control-sm" id="editFloatingPhone" placeholder="Enter phone number">
                        <label for="editFloatingPhone">Phone Number</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Role --}}
                    <div class="form-floating mb-3">
                        <select name="role_id" class="form-select form-select-sm" id="editFloatingRole">
                            <option disabled>--</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->role }}
                                </option>
                            @endforeach
                        </select>
                        <label for="editFloatingRole">Role</label>
                        <div class="invalid-feedback"></div>
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

