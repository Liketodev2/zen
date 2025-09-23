<section>
    <div class="mt-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="form_title">Australian Government Pensions</h4>
            <img src="{{ asset('img/icons/help.png') }}" alt="Help">
        </div>

        <p class="choosing-business-type-text">
            This can include Parenting Payment (Single), Aged Pension, Disability Support Pension, Carer Payments and any other Government pension you received.
        </p>
        <p class="choosing-business-type-text">
            If you received more than one, add additional pensions below.
        </p>

        <div class="grin_box_border">
            <div id="pensionsContainer">
                @php
                    $pensions = old('government_pensions', $incomes->government_pensions ?? []);
                    
                    if (empty($pensions)) {
                        $pensions = [['type' => '', 'other' => '', 'tax_withheld' => '', 'total_received' => '']];
                    }
                @endphp
                
                @foreach($pensions as $index => $pension)
                @php
                    $pensionType = $pension['type'] ?? '';
                    $isOtherSelected = ($pensionType === 'Other');
                @endphp
                <section class="pension-block" data-index="{{ $index }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Please select the type of pension you received</p>
                            <select name="government_pensions[{{ $index }}][type]" class="form-select border-dark pension-type-select">
                                <option value="" disabled {{ empty($pensionType) ? 'selected' : '' }}>Choose</option>
                                <option value="Parenting Payment (Single)" {{ $pensionType === 'Parenting Payment (Single)' ? 'selected' : '' }}>Parenting Payment (Single)</option>
                                <option value="Aged Pension" {{ $pensionType === 'Aged Pension' ? 'selected' : '' }}>Aged Pension</option>
                                <option value="Disability Support Pension" {{ $pensionType === 'Disability Support Pension' ? 'selected' : '' }}>Disability Support Pension</option>
                                <option value="Carer Payments" {{ $pensionType === 'Carer Payments' ? 'selected' : '' }}>Carer Payments</option>
                                <option value="Newstart" {{ $pensionType === 'Newstart' ? 'selected' : '' }}>Newstart</option>
                                <option value="JobSeeker Allowance" {{ $pensionType === 'JobSeeker Allowance' ? 'selected' : '' }}>JobSeeker Allowance</option>
                                <option value="Youth Allowance" {{ $pensionType === 'Youth Allowance' ? 'selected' : '' }}>Youth Allowance</option>
                                <option value="Parenting Payment (Partnered)" {{ $pensionType === 'Parenting Payment (Partnered)' ? 'selected' : '' }}>Parenting Payment (Partnered)</option>
                                <option value="Mature Age Allowance" {{ $pensionType === 'Mature Age Allowance' ? 'selected' : '' }}>Mature Age Allowance</option>
                                <option value="Partner Allowance" {{ $pensionType === 'Partner Allowance' ? 'selected' : '' }}>Partner Allowance</option>
                                <option value="Sickness Allowance" {{ $pensionType === 'Sickness Allowance' ? 'selected' : '' }}>Sickness Allowance</option>
                                <option value="Special Benefit" {{ $pensionType === 'Special Benefit' ? 'selected' : '' }}>Special Benefit</option>
                                <option value="Widow Allowance" {{ $pensionType === 'Widow Allowance' ? 'selected' : '' }}>Widow Allowance</option>
                                <option value="Austudy Payment" {{ $pensionType === 'Austudy Payment' ? 'selected' : '' }}>Austudy Payment</option>
                                <option value="Other" {{ $isOtherSelected ? 'selected' : '' }}>Other (Please Specify)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Other Pension (specify)</p>
                            <input
                                type="text"
                                name="government_pensions[{{ $index }}][other]"
                                class="form-control border-dark pension-other-input"
                                placeholder="Specify other pension"
                                value="{{ $isOtherSelected ? ($pension['other'] ?? '') : '' }}"
                                {{ $isOtherSelected ? '' : 'disabled' }}
                            >
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Tax withheld from this Government pension</p>
                            <input
                                type="number"
                                name="government_pensions[{{ $index }}][tax_withheld]"
                                class="form-control border-dark"
                                placeholder="00.00$"
                                value="{{ $pension['tax_withheld'] ?? '' }}"
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Total amount received from this Government pension</p>
                            <input
                                type="number"
                                name="government_pensions[{{ $index }}][total_received]"
                                class="form-control border-dark"
                                placeholder="00.00$"
                                value="{{ $pension['total_received'] ?? '' }}"
                            >
                        </div>
                    </div>
                </section>
                @endforeach
            </div>

            <div class="row">
                <div class="col mb-3">
                    <button type="button" class="btn btn_add btn_add_pension mb-2">
                        <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another government pension
                    </button>
                    <button type="button" class="btn btn_delete btn_delete_pension mb-2">
                        Delete another government pension
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("pensionsContainer");
    const addBtn = document.querySelector(".btn_add_pension");
    const deleteBtn = document.querySelector(".btn_delete_pension");

    const newBlockTemplate = `
    <section class="pension-block" data-index="__INDEX__">
        <div class="row">
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Please select the type of pension you received</p>
                <select name="government_pensions[__INDEX__][type]" class="form-select border-dark pension-type-select">
                    <option value="" disabled selected>Choose</option>
                    <option value="Parenting Payment (Single)">Parenting Payment (Single)</option>
                    <option value="Aged Pension">Aged Pension</option>
                    <option value="Disability Support Pension">Disability Support Pension</option>
                    <option value="Carer Payments">Carer Payments</option>
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
                <p class="choosing-business-type-text">Other Pension (specify)</p>
                <input
                    type="text"
                    name="government_pensions[__INDEX__][other]"
                    class="form-control border-dark pension-other-input"
                    placeholder="Specify other pension"
                    disabled
                >
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Tax withheld from this Government pension</p>
                <input
                    type="number"
                    name="government_pensions[__INDEX__][tax_withheld]"
                    class="form-control border-dark"
                    placeholder="00.00$"
                >
            </div>
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Total amount received from this Government pension</p>
                <input
                    type="number"
                    name="government_pensions[__INDEX__][total_received]"
                    class="form-control border-dark"
                    placeholder="00.00$"
                >
            </div>
        </div>
    </section>
    `;

    function initPensionBlock(section) {
        const select = section.querySelector(".pension-type-select");
        const input = section.querySelector(".pension-other-input");

        function toggleInput() {
            if (select.value === "Other") {
                input.disabled = false;
            } else {
                input.disabled = true;
                input.value = "";
            }
        }

        select.addEventListener("change", toggleInput);
        toggleInput();
    }

    function initAllPensionBlocks() {
        container.querySelectorAll(".pension-block").forEach(initPensionBlock);
    }

    addBtn.addEventListener("click", () => {
        const newIndex = container.querySelectorAll(".pension-block").length;
        const newBlockHTML = newBlockTemplate.replace(/__INDEX__/g, newIndex);
        
        container.insertAdjacentHTML('beforeend', newBlockHTML);
        initPensionBlock(container.lastElementChild);
    });

    deleteBtn.addEventListener("click", () => {
        const blocks = container.querySelectorAll(".pension-block");
        if (blocks.length > 1) {
            blocks[blocks.length - 1].remove();
        }
    });

    initAllPensionBlocks();
});
</script>
