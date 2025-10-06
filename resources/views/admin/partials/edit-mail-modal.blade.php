<div class="modal fade" id="editMailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editMailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editMailForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" id="editMailId">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editMailModalLabel">Edit Mail Setting</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Structure --}}
                    <div class="form-floating mb-3">
                        <textarea name="structure" id="editMailStructure" class="form-control" style="height: 120px;" required></textarea>
                        <label for="editMailStructure">Mail Structure</label>
                    </div>

                    {{-- Preview --}}
                    <div class="form-floating mb-3">
                        <textarea name="preview" id="editMailPreview" class="form-control" style="height: 120px;" required></textarea>
                        <label for="editMailPreview">Preview</label>
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
