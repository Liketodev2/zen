$(document).ready(function() {
    function showToastFun(type, message) {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        const toastContainer = document.getElementById('toast-container') || createToastContainerFun();
        toastContainer.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Auto-hide after 5s
        setTimeout(() => bsToast.hide(), 5000);
    }

    function createToastContainerFun() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1100';
        document.body.appendChild(container);
        return container;
    }

    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    const maxSize = 5 * 1024 * 1024; // 5 MB

    // 🔎 Validate ALL file inputs
    $(document).on('change', 'input[type="file"]', function () {
        const files = this.files;
        let valid = true;
        let errorMessage = '';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            if (!allowedTypes.includes(file.type)) {
                valid = false;
                errorMessage = `File "${file.name}" is not allowed. Allowed types: PDF, JPG, PNG.`;
                break;
            }

            if (file.size > maxSize) {
                valid = false;
                errorMessage = `File "${file.name}" exceeds the maximum size of 5 MB.`;
                break;
            }
        }

        const inputId = $(this).attr('id');
        const preview = $('#' + inputId + '_name');

        if (!valid) {
            showToastFun('error', errorMessage);

            // Reset input + preview
            $(this).val('');
            if (preview.length) {
                preview.html('No file chosen');
            }
            return;
        }

        // ✅ Display selected filenames
        if (preview.length) {
            if (files.length > 1) {
                let fileNames = Array.from(files).map(f => f.name).join('<br>');
                preview.html(fileNames);
            } else {
                preview.html(files[0].name);
            }
        }
    });

    // 🔘 Trigger hidden file inputs
    $(document).on('click', 'button[id$="_trigger"]', function () {
        const inputId = $(this).attr('id').replace('_trigger', '');
        const input = $('#' + inputId);

        // reset value BEFORE opening dialog so same file can be reselected
        input.val('');
        input.trigger('click');
    });
});
