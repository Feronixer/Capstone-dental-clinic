<div class="modal fade" id="deleteServiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteServiceForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" id="deleteServiceId">

                <div class="modal-body">
                    <h5 class="py-4 text-center">Are you sure you want to delete this service?</h5>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
