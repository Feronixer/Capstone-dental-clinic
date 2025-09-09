<div class="modal fade" id="deleteUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteUserForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <input type="hidden" name="user_id" id="modalDeleteUserId">
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="py-5">Are you sure you want to delete this user ?</h5>
                    </div>
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
