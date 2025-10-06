<div class="modal fade" id="editAnnouncementModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editAnnouncementForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" id="editAnnouncementId">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editAnnouncementModalLabel">Edit Announcement</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Title --}}
                    <div class="form-floating mb-3">
                        <input type="text" name="title" id="editAnnouncementTitle" class="form-control" required>
                        <label for="editAnnouncementTitle">Title</label>
                    </div>

                    {{-- Body --}}
                    <div class="form-floating mb-3">
                        <textarea name="body" id="editAnnouncementBody" class="form-control" style="height: 120px;" required></textarea>
                        <label for="editAnnouncementBody">Body</label>
                    </div>

                    {{-- Image --}}
                    <div class="mb-3">
                        <label for="editAnnouncementImage">Image (optional)</label>
                        <input type="file" name="image" id="editAnnouncementImage" class="form-control">
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
