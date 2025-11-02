@forelse ($users as $user)
    <tr>
        <td class="align-middle text-center">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
        <td class="align-middle text-center">{{ $user->username }}</td>
        <td class="align-middle text-center">{{ $user->name }}</td>
        <td class="align-middle text-center">{{ $user->email }}</td>
        <td class="align-middle text-center">{{ $user->info->gender ?? 'N/A' }}</td>
        <td class="align-middle text-center">{{ $user->role->role ?? 'N/A' }}</td>
        <td class="align-middle text-center">{{ $user->created_at ?? 'N/A' }}</td>
        <td class="text-center">
            <div class="d-inline">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="{{ $user->id }}">
                    <i class="bi bi-pencil"></i>
                </button>
                {{-- Staff cannot delete users --}}
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="align-middle text-center">No users found.</td>
    </tr>
@endforelse

