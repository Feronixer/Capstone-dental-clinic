<div class="modal fade" id="editServiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editServiceForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editServiceId">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editServiceModalLabel">Edit Service</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Icon --}}
                    <div class="form-floating mb-3">
                        <input type="text" name="icon_fa" id="editServiceIcon" class="form-control" placeholder="FontAwesome Icon" required>
                        <label for="editServiceIcon">FontAwesome Icon</label>
                    </div>

                    {{-- Service Name --}}
                    <div class="form-floating mb-3">
                        <input type="text" name="service" id="editServiceName" class="form-control" placeholder="Service Name" required>
                        <label for="editServiceName">Service Name</label>
                    </div>

                    {{-- Price --}}
                    <div class="form-floating mb-3">
                        <input type="text" name="price" id="editServicePrice" class="form-control" placeholder="Price" required>
                        <label for="editServicePrice">Price</label>
                    </div>

                    {{-- Description --}}
                    <div class="form-floating mb-3">
                        <textarea name="description" id="editServiceDescription" class="form-control" placeholder="Description" required></textarea>
                        <label for="editServiceDescription">Description</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
