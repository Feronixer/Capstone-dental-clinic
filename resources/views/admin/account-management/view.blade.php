@extends('layout.admin.app')
@section('content')
@if (session('success'))
    <x-toast-message type="success" :message="session('success')" />
@elseif (session('error'))
    <x-toast-message type="danger" :message="session('error')" />
@endif
<div id="toast-container" class="position-fixed top-0 end-0 px-3" style="z-index: 1055;"></div>
<section>
    <div class="admin-dashboard">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="card-body p-4" id="users-table">
                        <h5 class="m-0">User Management</h5>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <form id="per-page-form" class="d-inline">
                                    <span class="me-2">Show</span>
                                    <select name="per_page" class="form-select d-inline w-auto">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                    <span class="ms-2">entries</span>
                                </form>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <div class="filter-by">
                                    <span>Filter by Role:</span>
                                    <select id="filter-role" name="role" class="form-select d-inline w-auto">
                                        <option value="">--</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                                {{ $role->role }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="search-bar">
                                    <input type="text" id="search-input" name="search" placeholder="Search" value="{{ request('search') }}">
                                    <i class="bi bi-search"></i>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" style="min-height: 540px;">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <x-table.th column="id" label="ID" center="true" />
                                        <x-table.th column="username" label="Username" />
                                        <x-table.th column="name" label="Name" />
                                        <x-table.th column="email" label="Email" />
                                        <x-table.th column="role" label="Role" />
                                        <x-table.th column="created_at" label="Created At" />
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table-body">
                                    @include('admin.partials.users-table', ['users' => $users])
                                </tbody>
                                <tr id="loader" style="display: none;">
                                    <td colspan="7" class="align-middle text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="spinner-border text-secondary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Loading...
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center flex-wrap m-2" id="users-pagination">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="bi bi-person-plus-fill"></i>
                                <p class="d-inline ms-1">Add User</p>
                            </button>
                            <x-table.pagination :paginator="$users" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('admin.account-management.modal-add-user')
@include('admin.account-management.modal-edit-user')
@include('admin.account-management.delete-user')
{{-- @include('admin.account-management.change-password-modal') --}}

<script>
$(document).ready(function () {
    let isLoading = false;
    let debounceTimer;

    $(document).on('submit', '.sortable-form', function(e) {
        e.preventDefault();
        if (isLoading) return;

        let form = $(this);
        let sort = form.data('sort');
        let direction = form.data('direction');

        $('.sortable-form i')
            .removeClass('bi-arrow-up bi-arrow-down')
            .addClass('bi-arrow-down-up text-secondary');

        let icon = form.find('i');
        if (direction === 'asc') {
            icon.removeClass('bi-arrow-down-up text-secondary')
                .addClass('bi-arrow-up text-primary');
            form.data('direction', 'desc');
        } else {
            icon.removeClass('bi-arrow-down-up text-secondary')
                .addClass('bi-arrow-down text-primary');
            form.data('direction', 'asc');
        }

        let perPage = $('#per-page-form select[name="per_page"]').val();
        let role = $('#filter-role').val();
        let search = $('#search-input').val();

        let url = "{{ route('admin-account-management') }}";
        url += `?per_page=${perPage}&role=${role}&search=${search}&sort=${sort}&direction=${direction}`;
        fetchUsers(url);
    });

    // Pagination link click
    $(document).on('click', '#users-pagination .pagination a', function(e) {
        e.preventDefault();
        if (isLoading) return;

        let url = $(this).attr('href');
        if (url === '#') return;

        fetchUsers(url);
    });

    // Per-page dropdown change
    $(document).on('change', '#per-page-form select[name="per_page"]', function(e) {
        e.preventDefault();
        if (isLoading) return;

        let perPage = $(this).val();
        let role = $('#filter-role').val();
        let search = $('#search-input').val();
        let url = "{{ route('admin-account-management') }}?per_page=" + perPage + "&role=" + role + "&search=" + search;

        fetchUsers(url);
    });

    // Role filter
    $(document).on('change', '#filter-role', function() {
        if (isLoading) return;

        let perPage = $('#per-page-form select[name="per_page"]').val();
        let role = $('#filter-role').val();
        let search = $('#search-input').val();
        let url = "{{ route('admin-account-management') }}?per_page=" + perPage + "&role=" + role + "&search=" + search;

        fetchUsers(url);
    });

    // Search input typing
    $(document).on('keyup', '#search-input', function() {
        if (isLoading) return;
        clearTimeout(debounceTimer);

        $("#loader").show();
        $("#users-table-body").hide();

        debounceTimer = setTimeout(function() {
            let perPage = $('#per-page-form select[name="per_page"]').val();
            let role = $('#filter-role').val();
            let search = $('#search-input').val();
            let url = "{{ route('admin-account-management') }}?per_page=" + perPage + "&role=" + role + "&search=" + search;

            fetchUsers(url);
        }, 1500);
    });

    function fetchUsers(url) {
        isLoading = true;
        $("#users-pagination .pagination a").addClass("disabled").css("pointer-events", "none");

        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function () {
                $("#loader").show();
                $("#users-table-body").hide();
            },
            success: function (response) {
                setTimeout(function() {
                    $("#users-table-body").html(response.html).show();
                    $("#users-pagination").html(response.pagination_html);
                    $("#loader").hide();
                }, 500);
            },
            complete: function () {
                setTimeout(function() {
                    $("#users-pagination .pagination a").removeClass("disabled").css("pointer-events", "auto");
                    isLoading = false;
                }, 500);
            },
            error: function () {
                alert("Failed to load data.");
                $("#loader").hide();
                $("#users-table-body").show();
                $("#users-pagination .pagination a").removeClass("disabled").css("pointer-events", "auto");
                isLoading = false;
            }
        });
    }
    $(document).ready(function () {
        $('#editUserModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let userId = button.data('id');
            console.log(userId);
            let modal = $(this);

            // ilagay sa hidden input ang user id
            modal.find('#modalUserId').val(userId);

            // kuha ng user
            $.get('/admin/account-management/users/' + userId, function (data) {
                modal.find('input[name="username"]').val(data.username);
                modal.find('input[name="first_name"]').val(data.info.first_name);
                modal.find('input[name="middle_name"]').val(data.info.middle_name);
                modal.find('input[name="last_name"]').val(data.info.last_name);
                modal.find('input[name="email"]').val(data.email);
                modal.find('input[name="phone"]').val(data.info.phone);
                modal.find('select[name="role_id"]').val(data.role_id);
            });
        });
        //change password
        $('#changePasswordModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let userId = button.data('id');
            $(this).find('#modalChangePasswordUserId').val(userId);
        });
        // delete
        $('#deleteUserModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let userId = button.data('id');
            $(this).find('#modalDeleteUserId').val(userId);
        });
    });

    $('#editUserModal').on('hidden.bs.modal', function () {
        let form = $('#editUserForm');
        form.find('.invalid-feedback').text('').hide();
        form.find('.form-control, .form-select').removeClass('is-invalid');
    });

    $('#changePasswordModal').on('hidden.bs.modal', function () {
        let form = $('#editUserForm');
        form.find('.invalid-feedback').text('').hide();
        form.find('.form-control, .form-select').removeClass('is-invalid');
    });


    $('#editUserForm').on('submit', function (e) {
        e.preventDefault();

        let userId = $(this).find('input[name="user_id"]').val();
        let formData = $(this).serialize();

        $.ajax({
            url: '/admin/account-management/users/' + userId,
            method: 'PUT',
            data: formData,
            success: function (response) {
                $('#editUserModal').modal('hide');
                showToast('success', response.message);
                fetchUsers("{{ route('admin-account-management') }}");
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('#editUserForm .invalid-feedback').text('').hide();
                    $('#editUserForm .form-control, #editUserForm .form-select').removeClass('is-invalid');
                    $.each(errors, function (key, value) {
                        let input = $('#editUserForm').find(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        input.closest('.form-floating').find('.invalid-feedback')
                            .text(value[0])
                            .show();
                    });
                } else {
                    showToast('danger', 'Unexpected error occurred');
                }
            }
        });
    });

    $('#changePasswordForm').on('submit', function (e) {
        e.preventDefault();

        let userId = $(this).find('input[name="user_id"]').val();
        let formData = $(this).serialize();

        $.ajax({
            url: '/admin/account-management/users/change-password/' + userId,
            method: 'POST',
            data: formData,
            success: function (response) {
                $('#changePasswordModal').modal('hide');
                showToast('success', response.message);
                fetchUsers("{{ route('admin-account-management') }}");
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('#changePasswordForm .invalid-feedback').text('').hide();
                    $('#changePasswordForm .form-control, #editUserForm .form-select').removeClass('is-invalid');
                    $.each(errors, function (key, value) {
                        let input = $('#changePasswordForm').find(`[name="${key}"]`);
                        input.addClass('is-invalid');
                        input.closest('.form-floating').find('.invalid-feedback')
                            .text(value[0])
                            .show();
                    });
                } else {
                    showToast('danger', 'Unexpected error occurred');
                }
            }
        });
    });

    $('#deleteUserForm').on('submit', function (e) {
        e.preventDefault();
        let userId = $(this).find('input[name="user_id"]').val();

        $.ajax({
            url: '/admin/account-management/users/' + userId,
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('#deleteUserModal').modal('hide');
                showToast('success', response.message);
                fetchUsers("{{ route('admin-account-management') }}");
            },
        });
    });

    function showToast(type, message) {
        let toast = `
            <div class="toast align-items-center text-bg-${type} border-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
        $('#toast-container').append(toast);
        let bsToast = new bootstrap.Toast($('#toast-container .toast').last()[0]);
        bsToast.show();
    }
});
</script>
@endsection
