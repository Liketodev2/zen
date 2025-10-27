<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Salary or Wages</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>
    <div class="row mb-3">
        <p class="choosing-business-type-text">
            Enter all your income you received from your employer/s below.
        </p>
        <p class="choosing-business-type-text">*if you don’t have these details, don’t worry. We can access these from our pre-fill application. Should we have any issues we will contact you.</p>

        <div class="grin_box_border">
            <div id="employerContainer">
                @php
                    $salary = old('salary', isset($incomes) ? $incomes->salary ?? [] : []);
                    $salaryInfo = $salary['info'] ?? [];
                    $salaryCount = max(count($salaryInfo), 1);
                @endphp

                @for($i = 0; $i < $salaryCount; $i++)
                    <section class="employer-block" data-index="{{ $i }}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p>Employer’s ABN</p>
                                <input
                                    type="number"
                                    name="salary[info][{{ $i }}][employer_abn]"
                                    class="form-control border-dark"
                                    placeholder="33 051 775 556"
                                    value="{{ old("salary.info.$i.employer_abn", $salaryInfo[$i]['employer_abn'] ?? '') }}"
                                >
                            </div>
                            <div class="col-md-6 mb-3">
                                <p>Total Tax Withheld</p>
                                <input
                                    type="number"
                                    name="salary[info][{{ $i }}][total_tax_withheld]"
                                    class="form-control border-dark"
                                    placeholder="00.00$"
                                    value="{{ old("salary.info.$i.total_tax_withheld", $salaryInfo[$i]['total_tax_withheld'] ?? '') }}"
                                >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p>Gross Payments</p>
                                <input
                                    type="number"
                                    name="salary[info][{{ $i }}][gross_payments]"
                                    class="form-control border-dark"
                                    placeholder="00.00$"
                                    value="{{ old("salary.info.$i.gross_payments", $salaryInfo[$i]['gross_payments'] ?? '') }}"
                                >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p>Are there any more items on your Income Statement? (Includes allowances, fringe benefits, reportable super & lump sum payments)</p>
                                @php
                                    $incomeItemValue = old("salary.info.$i.income_items", $salaryInfo[$i]['income_items'] ?? '');
                                @endphp
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input custom-radio income-yes"
                                        type="radio"
                                        name="salary[info][{{ $i }}][income_items]"
                                        id="incomeYes_{{ $i }}"
                                        value="1"
                                        {{ $incomeItemValue === '1' ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label custom-label" for="incomeYes_{{ $i }}">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input custom-radio income-no"
                                        type="radio"
                                        name="salary[info][{{ $i }}][income_items]"
                                        id="incomeNo_{{ $i }}"
                                        value="0"
                                        {{ $incomeItemValue === '0' ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label custom-label" for="incomeNo_{{ $i }}">No</label>
                                </div>
                            </div>

                            <div class="row mb-3 income-details" style="display: {{ $incomeItemValue === '1' ? 'block' : 'none' }};">
                                <div class="col-md-6 mb-3">
                                    @php
                                        $allowanceChecked = old("salary.info.$i.allowances", $salaryInfo[$i]['allowances'] ?? '0') === '1';
                                        $fringeChecked = old("salary.info.$i.fringe_benefits", $salaryInfo[$i]['fringe_benefits'] ?? '0') === '1';
                                        $superChecked = old("salary.info.$i.reportable_super", $salaryInfo[$i]['reportable_super'] ?? '0') === '1';
                                    @endphp

                                    <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                                        <input type="hidden" name="salary[info][{{ $i }}][allowances]" value="0">
                                        <input
                                            class="form-check-input mt-0"
                                            type="checkbox"
                                            name="salary[info][{{ $i }}][allowances]"
                                            value="1"
                                            {{ $allowanceChecked ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label">Allowances</label>
                                    </div>

                                    <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                                        <input type="hidden" name="salary[info][{{ $i }}][fringe_benefits]" value="0">
                                        <input
                                            class="form-check-input mt-0"
                                            type="checkbox"
                                            name="salary[info][{{ $i }}][fringe_benefits]"
                                            value="1"
                                            {{ $fringeChecked ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label">Fringe benefits</label>
                                    </div>

                                    <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                                        <input type="hidden" name="salary[info][{{ $i }}][reportable_super]" value="0">
                                        <input
                                            class="form-check-input mt-0"
                                            type="checkbox"
                                            name="salary[info][{{ $i }}][reportable_super]"
                                            value="1"
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
                        $onlyIncomeValue = old('salary.only_income', $salary['only_income'] ?? '');
                        $showIncomeDetails = $onlyIncomeValue === '0';
                    @endphp

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input custom-radio"
                            type="radio"
                            name="salary[only_income]"
                            id="onlyIncomeYes"
                            value="1"
                            {{ $onlyIncomeValue === '1' ? 'checked' : '' }}
                        >
                        <label class="form-check-label custom-label" for="onlyIncomeYes">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input custom-radio"
                            type="radio"
                            name="salary[only_income]"
                            id="onlyIncomeNo"
                            value="0"
                            {{ $onlyIncomeValue === '0' ? 'checked' : '' }}
                        >
                        <label class="form-check-label custom-label" for="onlyIncomeNo">No</label>
                    </div>

                    <input
                        type="text"
                        name="salary[only_income_details]"
                        class="form-control border-dark mt-2"
                        id="onlyIncomeDetails"
                        placeholder="Add more details here about any other income"
                        value="{{ old('salary.only_income_details', $salary['only_income_details'] ?? '') }}"
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
                <input type="number" name="salary[info][__INDEX__][employer_abn]" class="form-control border-dark" placeholder="33 051 775 556">
            </div>
            <div class="col-md-6 mb-3">
                <p>Total Tax Withheld</p>
                <input type="number" name="salary[info][__INDEX__][total_tax_withheld]" class="form-control border-dark" placeholder="00.00$">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <p>Gross Payments</p>
                <input type="number" name="salary[info][__INDEX__][gross_payments]" class="form-control border-dark" placeholder="00.00$">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <p>Are there any more items on your Income Statement?</p>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio income-yes" type="radio" name="salary[info][__INDEX__][income_items]" id="incomeYes___INDEX__" value="1">
                    <label class="form-check-label custom-label" for="incomeYes___INDEX__">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio income-no" type="radio" name="salary[info][__INDEX__][income_items]" id="incomeNo___INDEX__" value="0" checked>
                    <label class="form-check-label custom-label" for="incomeNo___INDEX__">No</label>
                </div>
            </div>
            <div class="row mb-3 income-details" style="display:none;">
                <div class="col-md-6 mb-3">
                    <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                        <input type="hidden" name="salary[info][__INDEX__][allowances]" value="0">
                        <input class="form-check-input mt-0" type="checkbox" name="salary[info][__INDEX__][allowances]" value="1">
                        <label class="form-check-label">Allowances</label>
                    </div>
                    <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                        <input type="hidden" name="salary[info][__INDEX__][fringe_benefits]" value="0">
                        <input class="form-check-input mt-0" type="checkbox" name="salary[info][__INDEX__][fringe_benefits]" value="1">
                        <label class="form-check-label">Fringe benefits</label>
                    </div>
                    <div class="form-check form-switch d-flex align-items-center gap-3 mb-3">
                        <input type="hidden" name="salary[info][__INDEX__][reportable_super]" value="0">
                        <input class="form-check-input mt-0" type="checkbox" name="salary[info][__INDEX__][reportable_super]" value="1">
                        <label class="form-check-label">Reportable super & lump sum payments</label>
                    </div>
                </div>
            </div>
        </div>
    </section>`;

        function initIncomeRadio(section) {
            const yes = section.querySelector(".income-yes");
            const no = section.querySelector(".income-no");
            const details = section.querySelector(".income-details");
            if (!yes || !no || !details) return;
            const toggle = () => details.style.display = yes.checked ? "block" : "none";
            yes.addEventListener("change", toggle);
            no.addEventListener("change", toggle);
            toggle();
        }

        function refreshIndices() {
            const blocks = employerContainer.querySelectorAll(".employer-block");
            blocks.forEach((block, index) => {
                block.dataset.index = index;
                block.querySelectorAll("input").forEach(el => {
                    el.name = el.name.replace(/salary\[info]\[\d+]/, `salary[info][${index}]`);
                    if (el.id?.startsWith("incomeYes_")) el.id = `incomeYes_${index}`;
                    if (el.id?.startsWith("incomeNo_")) el.id = `incomeNo_${index}`;
                });
                block.querySelectorAll("label[for]").forEach(label => {
                    if (label.getAttribute("for")?.startsWith("incomeYes_")) label.setAttribute("for", `incomeYes_${index}`);
                    if (label.getAttribute("for")?.startsWith("incomeNo_")) label.setAttribute("for", `incomeNo_${index}`);
                });
                initIncomeRadio(block);
            });
        }

        addBtn.addEventListener("click", () => {
            const newIndex = employerContainer.querySelectorAll(".employer-block").length;
            employerContainer.insertAdjacentHTML("beforeend", newBlockTemplate.replace(/__INDEX__/g, newIndex));
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

        const onlyIncomeYes = document.getElementById("onlyIncomeYes");
        const onlyIncomeNo = document.getElementById("onlyIncomeNo");
        const onlyIncomeDetails = document.getElementById("onlyIncomeDetails");

        function toggleOnlyIncomeDetails() {
            if (onlyIncomeNo.checked) onlyIncomeDetails.style.display = "block";
            else {
                onlyIncomeDetails.style.display = "none";
                onlyIncomeDetails.value = '';
            }
        }

        if (onlyIncomeYes && onlyIncomeNo && onlyIncomeDetails) {
            onlyIncomeYes.addEventListener("change", toggleOnlyIncomeDetails);
            onlyIncomeNo.addEventListener("change", toggleOnlyIncomeDetails);
            toggleOnlyIncomeDetails();
        }
    });
</script>
