@forelse ($users as $user)
    <tr>
        <td class="align-middle text-center">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
        <td class="align-middle text-center">{{ $user->username }}</td>
        <td class="align-middle text-center">{{ $user->name }}</td>
        <td class="align-middle text-center">
            <span class="masked-email" data-user-id="{{ $user->id }}" data-visible="0">••••••••</span>
            <button class="btn btn-outline-secondary btn-sm ms-2 view-email-btn" data-user-id="{{ $user->id }}" title="View email">
                <i class="bi bi-eye" data-icon="eye"></i>
            </button>
        </td>
        <td class="align-middle text-center">{{ $user->info->gender ?? 'N/A' }}</td>
        <td class="align-middle text-center">{{ $user->role->role ?? 'N/A' }}</td>
        <td class="align-middle text-center">{{ $user->created_at ?? 'N/A' }}</td>
        <td class="text-center">
            <div class="d-inline">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="{{ $user->id }}">
                    <i class="bi bi-pencil"></i>
                </button>
                {{-- <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal" data-id="{{ $user->id }}">
                    <i class="bi bi-key"></i>
                </button> --}}
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-id="{{ $user->id }}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="align-middle text-center">No users found.</td>
    </tr>
@endforelse
