<div class="modal fade" id="{{ $id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ url($action) }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}">
                @csrf
                @if(in_array($method, ['PUT', 'PATCH', 'DELETE']))
                    @method($method)
                @endif
                <input type="hidden" name="modal_id" value="{{ $id }}">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="{{ $id }}Label">{{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- Username --}}
                    <div class="form-floating mb-3">
                        <input name="username" type="text" class="form-control form-control-sm" id="floatingUsername" placeholder="Enter username" value="{{ old('username', $user->username ?? '') }}">
                        <label for="floatingUsername">Username</label>
                    </div>
                    @error('username')<p class="text-danger">* {{ $message }}</p>@enderror

                    {{-- First Name --}}
                    <div class="form-floating mb-3">
                        <input name="first_name" type="text" class="form-control form-control-sm" id="floatingFirstName" placeholder="Enter first name" value="{{ old('first_name', $user->info->first_name ?? '') }}">
                        <label for="floatingFirstName">First Name</label>
                    </div>
                    @error('first_name')<p class="text-danger">* {{ $message }}</p>@enderror

                    {{-- Middle Name --}}
                    <div class="form-floating mb-3">
                        <input name="middle_name" type="text" class="form-control form-control-sm" id="floatingMiddleName" placeholder="Enter middle name" value="{{ old('middle_name', $user->info->middle_name ?? '') }}">
                        <label for="floatingMiddleName">Middle Name (Optional)</label>
                    </div>

                    {{-- Last Name --}}
                    <div class="form-floating mb-3">
                        <input name="last_name" type="text" class="form-control form-control-sm" id="floatingLastName" placeholder="Enter last name" value="{{ old('last_name', $user->info->last_name ?? '') }}">
                        <label for="floatingLastName">Last Name</label>
                    </div>
                    @error('last_name')<p class="text-danger">* {{ $message }}</p>@enderror

                    {{-- Email --}}
                    <div class="form-floating mb-3">
                        <input name="email" type="email" class="form-control form-control-sm" id="floatingEmail" placeholder="name@example.com" value="{{ old('email', $user->email ?? '') }}">
                        <label for="floatingEmail">Email</label>
                    </div>
                    @error('email')<p class="text-danger">* {{ $message }}</p>@enderror

                    {{-- Phone --}}
                    <div class="form-floating mb-3">
                        <input name="phone" type="text" class="form-control form-control-sm" id="floatingPhone" placeholder="Enter phone number" value="{{ old('phone', $user->info->phone ?? '') }}">
                        <label for="floatingPhone">Phone Number</label>
                    </div>
                    @error('phone')<p class="text-danger">* {{ $message }}</p>@enderror

                    {{-- Role --}}
                    <div class="form-floating mb-3">
                        <select name="role_id" class="form-select form-select-sm" id="floatingRole">
                            <option disabled {{ old('role_id', $user->role_id ?? '') ? '' : 'selected' }}>--</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                    {{ $role->role }}
                                </option>
                            @endforeach
                        </select>
                        <label for="floatingRole">Role</label>
                    </div>
                    @error('role_id')<p class="text-danger">* {{ $message }}</p>@enderror

                    <div class="form-floating mb-3">
                        <input
                            name="password"
                            type="password"
                            class="form-control form-control-sm"
                            id="floatingPassword"
                            placeholder="Enter password"
                            @if(!$user) required @endif>
                        <label for="floatingPassword">{{ $user ? 'New Password (Optional)' : 'Password' }}</label>
                    </div>
                    @error('password')<p class="text-danger">* {{ $message }}</p>@enderror

                    <div class="form-floating mb-3">
                        <input
                            name="confirm_password"
                            type="password"
                            class="form-control form-control-sm"
                            id="floatingConfirmPassword"
                            placeholder="Confirm password"
                            @if(!$user) required @endif>
                        <label for="floatingConfirmPassword">{{ $user ? 'Confirm New Password (Optional)' : 'Confirm Password' }}</label>
                    </div>
                    @error('confirm_password')<p class="text-danger">* {{ $message }}</p>@enderror
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">{{ $user ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

