@if(session('success') || session('error'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
        <div id="toastMessage" class="toast align-items-center text-white {{ session('success') ? 'bg-success' : 'bg-danger' }} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') ?? session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        const toastEl = document.getElementById('toastMessage');
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000  // 3 секунды
        });
        toast.show();
    });
</script>