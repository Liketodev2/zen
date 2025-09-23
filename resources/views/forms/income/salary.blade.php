<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Salary or Wages</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>
    <div class="row mb-3">
        <p class="choosing-business-type-text">
            Enter all your income you received from your employer/s below
        </p>
        <div class="grin_box_border">
            <div id="employerContainer">
                @php
                    $salary = old('salary', isset($incomes) ? $incomes->salary ?? [] : []);
                    $numericItems = array_filter($salary, function($key) {
                        return is_int($key);
                    }, ARRAY_FILTER_USE_KEY);
                    $salaryCount = max(count($numericItems), 1);
                @endphp

                @for($i = 0; $i < $salaryCount; $i++)
                <section class="employer-block" data-index="{{ $i }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p>Employer’s ABN</p>
                            <input
                                type="number"
                                name="salary[{{ $i }}][employer_abn]"
                                class="form-control border-dark"
                                placeholder="33 051 775 556"
                                value="{{ old("salary.$i.employer_abn", $incomes->salary[$i]['employer_abn'] ?? '') }}"
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <p>Total Tax Withheld</p>
                            <input
                                type="number"
                                name="salary[{{ $i }}][total_tax_withheld]"
                                class="form-control border-dark"
                                placeholder="00.00$"
                                value="{{ old("salary.$i.total_tax_withheld", $incomes->salary[$i]['total_tax_withheld'] ?? '') }}"
                            >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p>Gross Payments</p>
                            <input
                                type="number"
                                name="salary[{{ $i }}][gross_payments]"
                                class="form-control border-dark"
                                placeholder="00.00$"
                                value="{{ old("salary.$i.gross_payments", $incomes->salary[$i]['gross_payments'] ?? '') }}"
                            >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p>Are there any more items on your Income Statement? (Includes allowances, fringe benefits, reportable super & lump sum payments)</p>

                            @php
                                $incomeItemValue = old("salary.$i.income_items", $incomes->salary[$i]['income_items'] ?? '');
                            @endphp
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input custom-radio income-yes"
                                    type="radio"
                                    name="salary[{{ $i }}][income_items]"
                                    id="incomeYes_{{ $i }}"
                                    value="yes"
                                    {{ $incomeItemValue === 'yes' ? 'checked' : '' }}
                                >
                                <label class="form-check-label custom-label" for="incomeYes_{{ $i }}">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input custom-radio income-no"
                                    type="radio"
                                    name="salary[{{ $i }}][income_items]"
                                    id="incomeNo_{{ $i }}"
                                    value="no"
                                    {{ $incomeItemValue === 'no' ? 'checked' : '' }}
                                >
                                <label class="form-check-label custom-label" for="incomeNo_{{ $i }}">No</label>
                            </div>
                        </div>
                        <div class="row mb-3 income-details" style="display: {{ $incomeItemValue === 'yes' ? 'block' : 'none' }};">
                            <div class="col-md-6 mb-3">
                                @php
                                    $allowanceChecked = old("salary.$i.allowances", $incomes->salary[$i]['allowances'] ?? 'off') === 'on';
                                    $fringeChecked = old("salary.$i.fringe_benefits", $incomes->salary[$i]['fringe_benefits'] ?? 'off') === 'on';
                                    $superChecked = old("salary.$i.reportable_super", $incomes->salary[$i]['reportable_super'] ?? 'off') === 'on';
                                @endphp

                                <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                                    <input type="hidden" name="salary[{{ $i }}][allowances]" value="off">
                                    <input
                                        class="form-check-input mt-0"
                                        type="checkbox"
                                        name="salary[{{ $i }}][allowances]"
                                        value="on"
                                        {{ $allowanceChecked ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label">Allowances</label>
                                </div>

                                <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                                    <input type="hidden" name="salary[{{ $i }}][fringe_benefits]" value="off">
                                    <input
                                        class="form-check-input mt-0"
                                        type="checkbox"
                                        name="salary[{{ $i }}][fringe_benefits]"
                                        value="on"
                                        {{ $fringeChecked ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label">Fringe benefits</label>
                                </div>

                                <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                                    <input type="hidden" name="salary[{{ $i }}][reportable_super]" value="off">
                                    <input
                                        class="form-check-input mt-0"
                                        type="checkbox"
                                        name="salary[{{ $i }}][reportable_super]"
                                        value="on"
                                        {{ $superChecked ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label">Reportable super & lump sum payments</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @endfor
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <button type="button" class="btn btn_add btn_add_employer">
                        <img src="{{ asset('img/icons/plus.png') }}" alt="plus">
                        Add another employer
                    </button>
                    <button type="button" class="btn btn_delete btn_delete_employer">
                        Delete employer
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <p>Was this your only salary or income during the past year?</p>

                    @php
                        $onlyIncomeValue = old('salary.only_income', $incomes->salary['only_income'] ?? '');
                        $showIncomeDetails = $onlyIncomeValue === 'no';
                    @endphp

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input custom-radio"
                            type="radio"
                            name="salary[only_income]"
                            id="onlyIncomeYes"
                            value="yes"
                            {{ $onlyIncomeValue === 'yes' ? 'checked' : '' }}
                        >
                        <label class="form-check-label custom-label" for="onlyIncomeYes">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input custom-radio"
                            type="radio"
                            name="salary[only_income]"
                            id="onlyIncomeNo"
                            value="no"
                            {{ $onlyIncomeValue === 'no' ? 'checked' : '' }}
                        >
                        <label class="form-check-label custom-label" for="onlyIncomeNo">No</label>
                    </div>

                    <input
                        type="text"
                        name="salary[only_income_details]"
                        class="form-control border-dark mt-2"
                        id="onlyIncomeDetails"
                        placeholder="Add more details here about any other income"
                        value="{{ old('salary.only_income_details', $incomes->salary['only_income_details'] ?? '') }}"
                        style="display: {{ $showIncomeDetails ? 'block' : 'none' }};"
                    >
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const employerContainer = document.getElementById("employerContainer");
    const addBtn = document.querySelector(".btn_add_employer");
    const deleteBtn = document.querySelector(".btn_delete_employer");

    const newBlockTemplate = `
    <section class="employer-block" data-index="__INDEX__">
        <div class="row">
            <div class="col-md-6 mb-3">
                <p>Employer’s ABN</p>
                <input
                    type="number"
                    name="salary[__INDEX__][employer_abn]"
                    class="form-control border-dark"
                    placeholder="33 051 775 556"
                    value=""
                >
            </div>
            <div class="col-md-6 mb-3">
                <p>Total Tax Withheld</p>
                <input
                    type="number"
                    name="salary[__INDEX__][total_tax_withheld]"
                    class="form-control border-dark"
                    placeholder="00.00$"
                    value=""
                >
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <p>Gross Payments</p>
                <input
                    type="number"
                    name="salary[__INDEX__][gross_payments]"
                    class="form-control border-dark"
                    placeholder="00.00$"
                    value=""
                >
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <p>Are there any more items on your Income Statement? (Includes allowances, fringe benefits, reportable super & lump sum payments)</p>
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input custom-radio income-yes"
                        type="radio"
                        name="salary[__INDEX__][income_items]"
                        id="incomeYes___INDEX__"
                        value="yes"
                    >
                    <label class="form-check-label custom-label" for="incomeYes___INDEX__">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input custom-radio income-no"
                        type="radio"
                        name="salary[__INDEX__][income_items]"
                        id="incomeNo___INDEX__"
                        value="no"
                        checked
                    >
                    <label class="form-check-label custom-label" for="incomeNo___INDEX__">No</label>
                </div>
            </div>
            <div class="row mb-3 income-details" style="display:none;">
                <div class="col-md-6 mb-3">
                    <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                        <input type="hidden" name="salary[__INDEX__][allowances]" value="off">
                        <input
                            class="form-check-input mt-0"
                            type="checkbox"
                            name="salary[__INDEX__][allowances]"
                            value="on"
                        >
                        <label class="form-check-label">Allowances</label>
                    </div>
                    <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                        <input type="hidden" name="salary[__INDEX__][fringe_benefits]" value="off">
                        <input
                            class="form-check-input mt-0"
                            type="checkbox"
                            name="salary[__INDEX__][fringe_benefits]"
                            value="on"
                        >
                        <label class="form-check-label">Fringe benefits</label>
                    </div>
                    <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                        <input type="hidden" name="salary[__INDEX__][reportable_super]" value="off">
                        <input
                            class="form-check-input mt-0"
                            type="checkbox"
                            name="salary[__INDEX__][reportable_super]"
                            value="on"
                        >
                        <label class="form-check-label">Reportable super & lump sum payments</label>
                    </div>
                </div>
            </div>
        </div>
    </section>
    `;

    function initIncomeRadio(section) {
        const yesRadio = section.querySelector(".income-yes");
        const noRadio = section.querySelector(".income-no");
        const detailsBlock = section.querySelector(".income-details");

        if (!yesRadio || !noRadio || !detailsBlock) return;

        function toggleDetails() {
            detailsBlock.style.display = yesRadio.checked ? "block" : "none";
        }

        yesRadio.addEventListener("change", toggleDetails);
        noRadio.addEventListener("change", toggleDetails);

        toggleDetails();
    }

    function refreshIndices() {
        const blocks = employerContainer.querySelectorAll(".employer-block");
        blocks.forEach((block, index) => {
            block.dataset.index = index;

            block.querySelectorAll('input, select').forEach(el => {
                if (el.name) {
                    el.name = el.name.replace(/salary\[\d+\]/, `salary[${index}]`);
                }
                if (el.classList.contains('income-yes')) {
                    el.id = `incomeYes_${index}`;
                    const label = block.querySelector(`label[for^="incomeYes"]`);
                    if(label) label.setAttribute('for', el.id);
                }
                if (el.classList.contains('income-no')) {
                    el.id = `incomeNo_${index}`;
                    const label = block.querySelector(`label[for^="incomeNo"]`);
                    if(label) label.setAttribute('for', el.id);
                }
            });

            initIncomeRadio(block);
        });
    }

    addBtn.addEventListener("click", () => {
        const newIndex = employerContainer.querySelectorAll(".employer-block").length;
        const newBlockHTML = newBlockTemplate.replace(/__INDEX__/g, newIndex);
        employerContainer.insertAdjacentHTML('beforeend', newBlockHTML);
        refreshIndices();
    });

    deleteBtn.addEventListener("click", () => {
        const blocks = employerContainer.querySelectorAll(".employer-block");
        if (blocks.length > 1) {
            blocks[blocks.length - 1].remove();
            refreshIndices();
        }
    });

    refreshIndices();

});
    const onlyIncomeYes = document.getElementById("onlyIncomeYes");
    const onlyIncomeNo = document.getElementById("onlyIncomeNo");
    const onlyIncomeDetails = document.getElementById("onlyIncomeDetails");

    function toggleOnlyIncomeDetails() {
        if (onlyIncomeNo.checked) {
            onlyIncomeDetails.style.display = "block";
        } else {
            onlyIncomeDetails.style.display = "none";
            onlyIncomeDetails.value = ''; 
        }
    }

    if (onlyIncomeYes && onlyIncomeNo && onlyIncomeDetails) {
        onlyIncomeYes.addEventListener("change", toggleOnlyIncomeDetails);
        onlyIncomeNo.addEventListener("change", toggleOnlyIncomeDetails);
        toggleOnlyIncomeDetails();
    }
</script>