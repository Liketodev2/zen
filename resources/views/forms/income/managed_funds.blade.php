<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Managed Funds</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <p class="choosing-business-type-text mb-3">
        Managed Fund investments NEED to be included on your tax return.
    </p>

    <div class="grin_box_border">
        <div id="managedFundContainer">
            @php
                $managedFunds = old('managed_funds', isset($incomes) ? $incomes->managed_funds ?? [] : []);
                $numericItems = array_filter($managedFunds, function($key) {
                    return is_int($key);
                }, ARRAY_FILTER_USE_KEY);
                $fundCount = max(count($numericItems), 1);
            @endphp

            @for($i = 0; $i < $fundCount; $i++)
            <section class="managed-fund-block" data-index="{{ $i }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p>Fund's ABN</p>
                        <input
                            type="number"
                            name="managed_funds[{{ $i }}][fund_abn]"
                            class="form-control border-dark"
                            placeholder="33 051 775 556"
                            value="{{ old("managed_funds.$i.fund_abn", $incomes->managed_funds[$i]['fund_abn'] ?? '') }}"
                        >
                    </div>
                    <div class="col-md-6 mb-3">
                        <p>Total Income</p>
                        <input
                            type="number"
                            name="managed_funds[{{ $i }}][total_income]"
                            class="form-control border-dark"
                            placeholder="00.00$"
                            value="{{ old("managed_funds.$i.total_income", $incomes->managed_funds[$i]['total_income'] ?? '') }}"
                        >
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p>Total Tax Withheld</p>
                        <input
                            type="number"
                            name="managed_funds[{{ $i }}][total_tax_withheld]"
                            class="form-control border-dark"
                            placeholder="00.00$"
                            value="{{ old("managed_funds.$i.total_tax_withheld", $incomes->managed_funds[$i]['total_tax_withheld'] ?? '') }}"
                        >
                    </div>
                </div>
            </section>
            @endfor
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <button type="button" class="btn btn_add" id="addManagedFund">
                    <img src="{{ asset('img/icons/plus.png') }}" alt="plus">
                    Add another managed fund
                </button>
                <button type="button" class="btn btn_delete" id="removeManagedFund">
                    Delete managed fund
                </button>
            </div>
        </div>

        <div class="row mb-3 align-items-end">
            <p class="choosing-business-type-text">
                Attach Managed Fund statements here (optional)
            </p>
            <div class="col-md-6 mb-3">
                <input type="file" name="managed_fund_files[]" id="managedFundInput" class="d-none" multiple>
                <button type="button" class="btn btn_add" id="customFileTrigger">
                    <img src="{{ asset('img/icons/plus.png') }}" alt="plus">
                    Choose file
                </button>
                <p class="text-muted mt-1 mb-0">
                    Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
                </p>
            </div>
            <div class="col-md-6 mb-3">
                <p id="selectedFileName" class="choosing-business-type-text text-muted mb-0">
                    @if(!empty($incomes->attach['managed_fund_files']))
                        @foreach($incomes->attach['managed_fund_files'] as $file)
                            <a href="{{ Storage::disk('s3')->url($file) }}" target="_blank">
                                <i class="fa-solid fa-file"></i> View file
                            </a><br>
                        @endforeach
                    @else
                        No file chosen
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Managed Funds Blocks
    const container = document.getElementById("managedFundContainer");
    const addBtn = document.getElementById("addManagedFund");
    const removeBtn = document.getElementById("removeManagedFund");

    const template = `
    <section class="managed-fund-block" data-index="__INDEX__">
        <div class="row">
            <div class="col-md-6 mb-3">
                <p>Fund's ABN</p>
                <input
                    type="number"
                    name="managed_funds[__INDEX__][fund_abn]"
                    class="form-control border-dark"
                    placeholder="33 051 775 556"
                    value=""
                >
            </div>
            <div class="col-md-6 mb-3">
                <p>Total Income</p>
                <input
                    type="number"
                    name="managed_funds[__INDEX__][total_income]"
                    class="form-control border-dark"
                    placeholder="00.00$"
                    value=""
                >
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <p>Total Tax Withheld</p>
                <input
                    type="number"
                    name="managed_funds[__INDEX__][total_tax_withheld]"
                    class="form-control border-dark"
                    placeholder="00.00$"
                    value=""
                >
            </div>
        </div>
    </section>
    `;

    function refreshIndices() {
        const blocks = container.querySelectorAll(".managed-fund-block");
        blocks.forEach((block, index) => {
            block.dataset.index = index;
            block.querySelectorAll('input').forEach(el => {
                if (el.name) {
                    el.name = el.name.replace(/managed_funds\[\d+\]/, `managed_funds[${index}]`);
                }
            });
        });
    }

    addBtn.addEventListener("click", () => {
        const newIndex = container.querySelectorAll(".managed-fund-block").length;
        const newBlockHTML = template.replace(/__INDEX__/g, newIndex);
        container.insertAdjacentHTML('beforeend', newBlockHTML);
        refreshIndices();
    });

    removeBtn.addEventListener("click", () => {
        const blocks = container.querySelectorAll(".managed-fund-block");
        if (blocks.length > 1) {
            blocks[blocks.length - 1].remove();
            refreshIndices();
        }
    });

    // File Upload Handling with clickable link
    const fileInput = document.getElementById("managedFundInput");
    const fileTrigger = document.getElementById("customFileTrigger");
    const fileNameDisplay = document.getElementById("selectedFileName");

    fileTrigger.addEventListener("click", () => {
        fileInput.click();
    });

    fileInput.addEventListener("change", () => {
        if (fileInput.files.length > 0) {
            const files = Array.from(fileInput.files).map(file => {
                const url = URL.createObjectURL(file);
                return `<a href="${url}" target="_blank">${file.name}</a>`;
            }).join('<br>');
            fileNameDisplay.innerHTML = files;
        } else {
            fileNameDisplay.textContent = "No file chosen";
        }
    });
});
</script>
