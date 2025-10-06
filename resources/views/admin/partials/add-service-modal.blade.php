<div class="modal fade" id="addServiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('services.store') }}">
        @csrf
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addServiceModalLabel">Add Service</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="form-floating mb-3">
            <input type="text" name="icon_fa" class="form-control" placeholder="FontAwesome Icon" required>
            <label>FontAwesome Icon</label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="service" class="form-control" placeholder="Service Name" required>
            <label>Service Name</label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" name="price" class="form-control" placeholder="Price" required>
            <label>Price</label>
          </div>

          <div class="form-floating mb-3">
            <textarea name="description" class="form-control" placeholder="Description" required></textarea>
            <label>Description</label>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>
