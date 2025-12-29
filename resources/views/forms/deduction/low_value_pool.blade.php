<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Low Value Pool Deduction</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <div class="grin_box_border p-3 mb-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">
                    Low value pool deductions relating to financial investments
                </label>
                <input type="text" class="form-control border-dark" name="low_value_pool[lvp_financial]" value="{{ old('low_value_pool.lvp_financial', $deductions->low_value_pool['lvp_financial'] ?? '') }}" placeholder="00.00$">
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">
                    Low value pool deductions relating to rental properties
                </label>
                <input type="text" class="form-control border-dark" name="low_value_pool[lvp_rental]" value="{{ old('low_value_pool.lvp_rental', $deductions->low_value_pool['lvp_rental'] ?? '') }}" placeholder="00.00$">
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">
                    Other low value pool deductions
                </label>
                <input type="text" class="form-control border-dark" name="low_value_pool[lvp_other]" value="{{ old('low_value_pool.lvp_other', $deductions->low_value_pool['lvp_other'] ?? '') }}"  placeholder="00.00$">
            </div>

            <!-- Attachments -->
            <div class="col-md-12 mb-3">
                <label class="choosing-business-type-text">Attach supporting documents (optional)</label>
                <input type="file" name="low_value_pool[files][]" id="lowValueFileInput" class="d-none" multiple>
                <button type="button" class="btn btn_add" id="triggerLowValueFile">
                    <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Choose file
                </button>
                <p class="text-muted mt-1 mb-0">Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.</p>
            </div>

            <div class="col-md-12 mb-3">
                <ul id="lowValueFileList" class="list-unstyled mb-0">
                    @if(!empty($deductions->attach['low_value_pool']['files']))
                        @foreach($deductions->attach['low_value_pool']['files'] as $file)
                            <li>
                                <a href="{{ Storage::disk('s3')->url($file) }}" target="_blank" class="btn btn-outline-success mb-1">
                                    <i class="fa-solid fa-file"></i> View File
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li class="text-muted">No file chosen</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const fileInput = document.getElementById("lowValueFileInput");
        const triggerButton = document.getElementById("triggerLowValueFile");
        const fileList = document.getElementById("lowValueFileList");

        triggerButton.addEventListener("click", () => fileInput.click());

        fileInput.addEventListener("change", () => {
            fileList.innerHTML = '';
            if (fileInput.files.length > 0) {
                Array.from(fileInput.files).forEach(file => {
                    const li = document.createElement('li');
                    li.innerHTML = `<span class="text-success">${file.name}</span>`;
                    fileList.appendChild(li);
                });
            } else {
                fileList.innerHTML = '<li class="text-muted">No file chosen</li>';
            }
        });
    });
</script>
