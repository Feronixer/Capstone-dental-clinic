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
                        <input name="username" type="text" class="form-control form-control-sm" id="floatingUsername" placeholder="Enter username">
                        <label for="floatingUsername">Username</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- First Name --}}
                    <div class="form-floating mb-3">
                        <input name="first_name" type="text" class="form-control form-control-sm" id="floatingFirstName" placeholder="Enter first name">
                        <label for="floatingFirstName">First Name</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Middle Name --}}
                    <div class="form-floating mb-3">
                        <input name="middle_name" type="text" class="form-control form-control-sm" id="floatingMiddleName" placeholder="Enter middle name">
                        <label for="floatingMiddleName">Middle Name (Optional)</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Last Name --}}
                    <div class="form-floating mb-3">
                        <input name="last_name" type="text" class="form-control form-control-sm" id="floatingLastName" placeholder="Enter last name">
                        <label for="floatingLastName">Last Name</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Gender --}}
                    <div class="form-floating mb-3">
                        <select name="gender" class="form-select form-select-sm" id="floatingGender">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <label for="floatingGender">Gender (Optional)</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Email --}}
                    <div class="form-floating mb-3">
                        <input name="email" type="email" class="form-control form-control-sm" id="floatingEmail" placeholder="name@example.com">
                        <label for="floatingEmail">Email</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Phone --}}
                    <div class="form-floating mb-3">
                        <input name="phone" type="text" class="form-control form-control-sm" id="floatingPhone" placeholder="Enter phone number">
                        <label for="floatingPhone">Phone Number</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Role --}}
                    <div class="form-floating mb-3">
                        <select name="role_id" class="form-select form-select-sm" id="floatingRole">
                            <option disabled>--</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->role }}
                                </option>
                            @endforeach
                        </select>
                        <label for="floatingRole">Role</label>
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

