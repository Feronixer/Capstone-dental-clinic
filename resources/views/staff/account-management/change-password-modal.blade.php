<div class="modal fade" id="changePasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="changePasswordForm">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="changePasswordModalLabel">Change Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="modalChangePasswordUserId">
                    {{-- Password --}}
                    <div class="form-floating mb-3">
                        <div class="input-group">
                            <input name="password" type="password"
                                   id="floatingPassword"
                                   placeholder="Enter password"
                                   autocomplete="new-password"
                                   class="form-control form-control-sm">
                            <button class="btn btn-outline-secondary" type="button" id="toggleChangePasswordStaff" aria-label="Show password">
                                <i class="bi bi-eye" id="toggleChangePasswordStaffIcon"></i>
                            </button>
                        </div>
                        <label for="floatingPassword">New Password</label>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-floating">
                        <div class="input-group">
                            <input name="confirm_password" type="password"
                                   id="floatingConfirmPassword"
                                   placeholder="Confirm password"
                                   autocomplete="new-password"
                                   class="form-control form-control-sm">
                            <button class="btn btn-outline-secondary" type="button" id="toggleChangeConfirmPasswordStaff" aria-label="Show password">
                                <i class="bi bi-eye" id="toggleChangeConfirmPasswordStaffIcon"></i>
                            </button>
                        </div>
                        <label for="floatingConfirmPassword">Confirm Password</label>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

