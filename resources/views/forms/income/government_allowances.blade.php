<section>
    <div class="mt-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="form_title">Government Allowances</h4>
            <img src="{{ asset('img/icons/help.png') }}" alt="Help">
        </div>

        <p class="choosing-business-type-text">
            This can include Newstart/JobSeeker Allowance, Youth Allowance, Parenting Payment (Partnered) and any other Government allowance you received.
        </p>
        <p class="choosing-business-type-text">
            Where to find them? Check the income summary or letters you received from Centrelink or the Government Department that sent you money.
        </p>
        <p class="choosing-business-type-text">
            If you received more than one, add additional allowances below.
        </p>

        <div class="grin_box_border">
            <div id="allowancesContainer">
                @php
                    $allowances = old('government_allowances', isset($incomes) ? $incomes->government_allowances ?? [] : []);
                    $allowanceCount = max(count($allowances), 1);
                @endphp

                @for($i = 0; $i < $allowanceCount; $i++)
                <section class="allowance-block" data-index="{{ $i }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Please select the type of allowance you received</p>
                            @php
                                $selectedType = old("government_allowances.$i.type", $allowances[$i]['type'] ?? '');
                                $otherValue = old("government_allowances.$i.other", $allowances[$i]['other'] ?? '');
                            @endphp
                            <select name="government_allowances[{{ $i }}][type]" class="form-select border-dark allowance-type-select">
                                <option value="" disabled {{ !$selectedType ? 'selected' : '' }}>Choose</option>
                                <option value="Newstart" {{ $selectedType === 'Newstart' ? 'selected' : '' }}>Newstart</option>
                                <option value="JobSeeker Allowance" {{ $selectedType === 'JobSeeker Allowance' ? 'selected' : '' }}>JobSeeker Allowance</option>
                                <option value="Youth Allowance" {{ $selectedType === 'Youth Allowance' ? 'selected' : '' }}>Youth Allowance</option>
                                <option value="Parenting Payment (Partnered)" {{ $selectedType === 'Parenting Payment (Partnered)' ? 'selected' : '' }}>Parenting Payment (Partnered)</option>
                                <option value="Mature Age Allowance" {{ $selectedType === 'Mature Age Allowance' ? 'selected' : '' }}>Mature Age Allowance</option>
                                <option value="Partner Allowance" {{ $selectedType === 'Partner Allowance' ? 'selected' : '' }}>Partner Allowance</option>
                                <option value="Sickness Allowance" {{ $selectedType === 'Sickness Allowance' ? 'selected' : '' }}>Sickness Allowance</option>
                                <option value="Special Benefit" {{ $selectedType === 'Special Benefit' ? 'selected' : '' }}>Special Benefit</option>
                                <option value="Widow Allowance" {{ $selectedType === 'Widow Allowance' ? 'selected' : '' }}>Widow Allowance</option>
                                <option value="Austudy Payment" {{ $selectedType === 'Austudy Payment' ? 'selected' : '' }}>Austudy Payment</option>
                                <option value="Other" {{ $selectedType === 'Other' ? 'selected' : '' }}>Other (Please Specify)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Other Allowance (specify)</p>
                            <input
                                type="text"
                                name="government_allowances[{{ $i }}][other]"
                                class="form-control border-dark allowance-other-input"
                                placeholder="Specify other allowance"
                                value="{{ $otherValue }}"
                                {{ $selectedType === 'Other' ? '' : 'disabled' }}
                            >
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Tax withheld from this Government allowance</p>
                            <input
                                type="number"
                                name="government_allowances[{{ $i }}][tax_withheld]"
                                class="form-control border-dark"
                                placeholder="00.00$"
                                value="{{ old("government_allowances.$i.tax_withheld", $allowances[$i]['tax_withheld'] ?? '') }}"
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Total amount received from this government allowance</p>
                            <input
                                type="number"
                                name="government_allowances[{{ $i }}][total_received]"
                                class="form-control border-dark"
                                placeholder="00.00$"
                                value="{{ old("government_allowances.$i.total_received", $allowances[$i]['total_received'] ?? '') }}"
                            >
                        </div>
                    </div>
                </section>
                @endfor
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <button type="button" class="btn btn_add btn_add_allowance">
                        <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another Government Allowance
                    </button>
                    <button type="button" class="btn btn_delete btn_delete_allowance">
                        Delete allowance
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("allowancesContainer");
    const addBtn = document.querySelector(".btn_add_allowance");
    const deleteBtn = document.querySelector(".btn_delete_allowance");

    // Template for new allowance block
    const newBlockTemplate = `
    <section class="allowance-block" data-index="__INDEX__">
        <div class="row">
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Please select the type of allowance you received</p>
                <select name="government_allowances[__INDEX__][type]" class="form-select border-dark allowance-type-select">
                    <option value="" disabled selected>Choose</option>
                    <option value="Newstart">Newstart</option>
                    <option value="JobSeeker Allowance">JobSeeker Allowance</option>
                    <option value="Youth Allowance">Youth Allowance</option>
                    <option value="Parenting Payment (Partnered)">Parenting Payment (Partnered)</option>
                    <option value="Mature Age Allowance">Mature Age Allowance</option>
                    <option value="Partner Allowance">Partner Allowance</option>
                    <option value="Sickness Allowance">Sickness Allowance</option>
                    <option value="Special Benefit">Special Benefit</option>
                    <option value="Widow Allowance">Widow Allowance</option>
                    <option value="Austudy Payment">Austudy Payment</option>
                    <option value="Other">Other (Please Specify)</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Other Allowance (specify)</p>
                <input
                    type="text"
                    name="government_allowances[__INDEX__][other]"
                    class="form-control border-dark allowance-other-input"
                    placeholder="Specify other allowance"
                    disabled
                >
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Tax withheld from this Government allowance</p>
                <input
                    type="number"
                    name="government_allowances[__INDEX__][tax_withheld]"
                    class="form-control border-dark"
                    placeholder="00.00$"
                >
            </div>
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Total amount received from this government allowance</p>
                <input
                    type="number"
                    name="government_allowances[__INDEX__][total_received]"
                    class="form-control border-dark"
                    placeholder="00.00$"
                >
            </div>
        </div>
    </section>
    `;

    // Initialize allowance blocks
    function initAllowanceBlock(section) {
        const select = section.querySelector(".allowance-type-select");
        const input = section.querySelector(".allowance-other-input");

        function toggleInput() {
            input.disabled = select.value !== "Other";
            if (!input.disabled) input.focus();
        }

        select.addEventListener("change", toggleInput);
        toggleInput();
    }

    // Refresh indices and reinitialize blocks
    function refreshIndices() {
        const blocks = container.querySelectorAll(".allowance-block");
        blocks.forEach((block, index) => {
            block.dataset.index = index;
            
            // Update all inputs and selects
            block.querySelectorAll("input, select").forEach(el => {
                if (el.name) {
                    el.name = el.name.replace(/government_allowances\[\d+\]/, `government_allowances[${index}]`);
                }
            });
            
            initAllowanceBlock(block);
        });
    }

    // Add new allowance block
    addBtn.addEventListener("click", () => {
        const newIndex = container.querySelectorAll(".allowance-block").length;
        const newBlockHTML = newBlockTemplate.replace(/__INDEX__/g, newIndex);
        container.insertAdjacentHTML("beforeend", newBlockHTML);
        refreshIndices();
    });

    // Delete last allowance block
    deleteBtn.addEventListener("click", () => {
        const blocks = container.querySelectorAll(".allowance-block");
        if (blocks.length > 1) {
            blocks[blocks.length - 1].remove();
            refreshIndices();
        }
    });

    // Initialize existing blocks
    refreshIndices();
});
</script>