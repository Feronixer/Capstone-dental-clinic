<div class="toast position-fixed top-0 end-0 text-bg-{{ $type }} border-0 m-3 w-auto p-1 z-3" role="alert" aria-live="assertive" aria-atomic="true" id="toastMessage">
    <div class="d-flex">
        <div class="toast-body">
            {{ $message }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var toastMessage = document.getElementById('toastMessage');
        var toast = new bootstrap.Toast(toastMessage);
        toast.show();
    });
</script>
