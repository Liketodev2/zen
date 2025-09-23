<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Foreign Source Income</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help" />
  </div>

  <section>
    <!-- Question 1 -->
    <p class="choosing-business-type-text">
      Foreign Income - Income from foreign assets or investments
    </p>
    <div class="grin_box_border mb-4">
      <div class="row">
        <div class="col-md-6">
          <p class="choosing-business-type-text">
            During the year did you own, or have an interest in, assets
            located outside Australia which had a total value of AUD $50,000
            or more?
          </p>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input custom-radio"
              type="radio"
              name="foreign_income[assets_interest]"
              id="assetsInterestYes"
              value="yes"
              {{ old('assets_interest', $incomes->foreign_income['assets_interest'] ?? '') == 'yes' ? 'checked' : '' }}
            />
            <label class="form-check-label custom-label" for="assetsInterestYes">Yes</label>
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input custom-radio"
              type="radio"
              name="foreign_income[assets_interest]"
              id="assetsInterestNo"
              value="no"
              {{ old('assets_interest', $incomes->foreign_income['assets_interest'] ?? '') == 'no' ? 'checked' : '' }}
            />
            <label class="form-check-label custom-label" for="assetsInterestNo">No</label>
          </div>
        </div>
      </div>

      <!-- Assets income -->
      <div class="row">
        <div class="col-md-6">
          <p class="choosing-business-type-text mt-3">
            Did you receive any income from foreign assets, investments,
            rental property, or any other source?
          </p>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input custom-radio foreign-yes"
              type="radio"
              name="foreign_income[income_foreign_sources]"
              id="incomeForeignYes"
              value="yes"
              {{ old('income_foreign_sources', $incomes->foreign_income['income_foreign_sources'] ?? '') == 'yes' ? 'checked' : '' }}
            />
            <label class="form-check-label custom-label" for="incomeForeignYes">Yes</label>
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input custom-radio foreign-no"
              type="radio"
              name="foreign_income[income_foreign_sources]"
              id="incomeForeignNo"
              value="no"
              {{ old('income_foreign_sources', $incomes->foreign_income['income_foreign_sources'] ?? '') == 'no' ? 'checked' : '' }}
            />
            <label class="form-check-label custom-label" for="incomeForeignNo">No</label>
          </div>

          <div
            class="foreign-details"
            style="display:none; margin-top: 15px;"
          >
            @php
              $foreignEntries = old('foreign_income[entries]', $incomes->foreign_income['entries'] ?? [[]]);
            @endphp

            @foreach($foreignEntries as $index => $entry)
            <div class="row asset-entry">
              <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text" for="foreignIncomeType">Type of foreign income</label>
                <select
                  class="form-select border-dark foreign-income-type"
                  name="foreign_income[entries][{{ $index }}][type]"
                >
                  <option value="">Choose</option>
                  <option value="rental" {{ ($entry['type'] ?? '') == 'rental' ? 'selected' : '' }}>Rental Property</option>
                  <option value="investment" {{ ($entry['type'] ?? '') == 'investment' ? 'selected' : '' }}>Financial Investment</option>
                  <option value="other" {{ ($entry['type'] ?? '') == 'other' ? 'selected' : '' }}>Other (please specify)</option>
                </select>
              </div>
              <div class="row">
              <div class="col-md-6 mb-3 foreign-income-other-block" style="{{ ($entry['type'] ?? '') == 'other' ? '' : 'display:none;' }}">
                <label class="choosing-business-type-text">Other (please specify)</label>
                <input
                  type="text"
                  class="form-control border-dark foreign-income-other"
                  name="foreign_income[entries][{{ $index }}][other]"
                  placeholder="Specify type..."
                  value="{{ $entry['other'] ?? '' }}"
                />
              </div>

              <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text" for="deductibleExpenses">Deductible expenses</label>
                <input
                  type="number"
                  step="0.01"
                  class="form-control border-dark"
                  name="foreign_income[entries][{{ $index }}][deductible_expenses]"
                  placeholder="00.00$"
                  value="{{ $entry['deductible_expenses'] ?? '' }}"
                />
              </div>

              <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text" for="grossAmount">Gross amount (income) received</label>
                <input
                  type="number"
                  step="0.01"
                  class="form-control border-dark"
                  name="foreign_income[entries][{{ $index }}][gross_amount]"
                  placeholder="00.00$"
                  value="{{ $entry['gross_amount'] ?? '' }}"
                />
              </div>

              <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text" for="foreignTaxPaid">Foreign tax paid</label>
                <input
                  type="number"
                  step="0.01"
                  class="form-control border-dark"
                  name="foreign_income[entries][{{ $index }}][foreign_tax_paid]"
                  placeholder="00.00$"
                  value="{{ $entry['foreign_tax_paid'] ?? '' }}"
                />
              </div>

              <div class="col-md-6 mb-3 nz-franking-credit-block" style="{{ isset($entry['nz_franking_credit']) ? '' : 'display:none;' }}">
                  <label class="choosing-business-type-text">Australian franking credits from a NZ franking company</label>
                  <input
                    type="number"
                    step="0.01"
                    class="form-control border-dark"
                    name="foreign_income[entries][{{ $index }}][nz_franking_credit]"
                    placeholder="00.00$"
                    value="{{ $entry['nz_franking_credit'] ?? '' }}"
                  />
              </div>
              </div>
            </div>
            @endforeach

            <span class="btn_add_asset"></span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
      <!-- Question 2 -->
      <p class="choosing-business-type-text">
        Foreign Income - Income from foreign pensions and annuities
      </p>
      <div class="grin_box_border mb-4">
        <div class="row">
          <div class="col-md-6">
            <p class="choosing-business-type-text">
              Did you receive any income from foreign pensions or annuities?
            </p>
            @php
                $pensionValue = old('foreign_income[foreign_pensions]', $incomes->foreign_income['foreign_pensions'] ?? '');
            @endphp
            <div class="form-check form-check-inline">
              <input
                class="form-check-input custom-radio pension-yes"
                type="radio"
                name="foreign_income[foreign_pensions]"
                id="incomePensionsYes"
                value="yes"
                {{ $pensionValue === 'yes' ? 'checked' : '' }}
              />
              <label
                class="form-check-label custom-label"
                for="incomePensionsYes"
                >Yes</label
              >
            </div>
            <div class="form-check form-check-inline">
              <input
                class="form-check-input custom-radio pension-no"
                type="radio"
                name="foreign_income[foreign_pensions]"
                id="incomePensionsNo"
                value="no"
                {{ $pensionValue === 'no' ? 'checked' : '' }}
              />
              <label
                class="form-check-label custom-label"
                for="incomePensionsNo"
                >No</label
              >
            </div>

            <div
              class="foreign-details foreign-pensions-details"
              style="display:none; margin-top: 15px;"
            >
              @php
                  $pensions = old('foreign_income[entries_pensions]', $incomes->foreign_income['entries_pensions'] ?? [[]]);
              @endphp

              @foreach($pensions as $index => $pension)
              <div class="row pension-entry">
                <div class="col-md-6 mb-3">
                  <label class="choosing-business-type-text" for="pensionType">Describe type of pension or annuity</label>
                  <input
                    type="text"
                    class="form-control border-dark"
                    name="foreign_income[entries_pensions][{{ $index }}][type]"
                    placeholder="Lifetime annuities"
                    value="{{ $pension['type'] ?? '' }}"
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="choosing-business-type-text" for="grossAmountPension">Gross amount (income) received</label>
                  <input
                    type="number"
                    step="0.01"
                    class="form-control border-dark"
                    name="foreign_income[entries_pensions][{{ $index }}][gross_amount]"
                    placeholder="00.00$"
                    value="{{ $pension['gross_amount'] ?? '' }}"
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="choosing-business-type-text" for="deductibleExpensesPension">Deductible expenses</label>
                  <input
                    type="number"
                    step="0.01"
                    class="form-control border-dark"
                    name="foreign_income[entries_pensions][{{ $index }}][deductible_expenses]"
                    placeholder="00.00$"
                    value="{{ $pension['deductible_expenses'] ?? '' }}"
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="choosing-business-type-text" for="undeductedPurchasePrice">Undeducted purchase price</label>
                  <input
                    type="number"
                    step="0.01"
                    class="form-control border-dark"
                    name="foreign_income[entries_pensions][{{ $index }}][undeducted_purchase_price]"
                    placeholder="00.00$"
                    value="{{ $pension['undeducted_purchase_price'] ?? '' }}"
                  />
                </div>
                <div class="col-md-6 mb-3">
                  <label class="choosing-business-type-text" for="foreignTaxPaidPension">Foreign tax paid</label>
                  <input
                    type="number"
                    step="0.01"
                    class="form-control border-dark"
                    name="foreign_income[entries_pensions][{{ $index }}][foreign_tax_paid]"
                    placeholder="00.00$"
                    value="{{ $pension['foreign_tax_paid'] ?? '' }}"
                  />
                </div>

                <div class="col-12 mb-3">
                  <button
                    type="button"
                    class="btn btn_delete_pension btn_delete"
                  >
                    Delete pension or annuity
                  </button>
                </div>
              </div>
              @endforeach

              <div class="col mb-3">
                <button type="button" class="btn btn_add_pension btn-add">
                  <img src="{{ asset('img/icons/plus.png') }}" alt="plus" /> Add
                  another pension or annuity
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>

  <section>
      <!-- Question 3 -->
      <p class="choosing-business-type-text">
        Foreign Income - Income from foreign employment
      </p>
      <div class="grin_box_border mb-4">
        <div class="row">
          <div class="col-md-6">
            <p class="choosing-business-type-text">
              Did you receive any income from employment in a foreign country?
            </p>
            <div class="form-check form-check-inline">
              <input
                class="form-check-input custom-radio foreign-yes"
                type="radio"
                name="foreign_income[entries_employment][received]"
                id="incomeEmployment1Yes"
                value="yes"
                {{ old('foreign_income.entries_employment.0.received', $incomes->foreign_income['entries_employment']['received'] ?? '') == 'yes' ? 'checked' : '' }}
              />
              <label
                class="form-check-label custom-label"
                for="incomeEmployment1Yes"
                >Yes</label
              >
            </div>
            <div class="form-check form-check-inline">
              <input
                class="form-check-input custom-radio foreign-no"
                type="radio"
                name="foreign_income[entries_employment][received]"
                id="incomeEmployment1No"
                value="no"
                {{ old('foreign_income.entries_employment.0.received', $incomes->foreign_income['entries_employment']['received'] ?? '') == 'no' ? 'checked' : '' }}
              />
              <label
                class="form-check-label custom-label"
                for="incomeEmployment1No"
                >No</label
              >
            </div>

            <div class="foreign-details" style="display:none;">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label
                    class="choosing-business-type-text"
                    for="employmentGrossAmount"
                    >Gross amount (income) received</label
                  >
                  <input
                    type="number"
                    step="0.01"
                    class="form-control border-dark"
                    name="foreign_income[entries_employment][gross_amount]"
                    placeholder="00.00$"
                    value="{{ old('foreign_income.entries_employment.0.gross_amount', $incomes->foreign_income['entries_employment']['gross_amount'] ?? '') }}"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label
                    class="choosing-business-type-text"
                    for="employmentDescription"
                  >
                    Please describe the type of income and how you earned it
                  </label>
                  <textarea
                    class="form-control border-dark"
                    name="foreign_income[entries_employment][description]"
                    rows="3"
                    placeholder="Description..."
                  >{{ old('foreign_income.entries_employment.0.description', $incomes->foreign_income['entries_employment']['description'] ?? '') }}</textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>


  <section>
    <!-- Question 4 -->
    <p class="choosing-business-type-text">
      Foreign Income - Non-resident foreign income
    </p>
    <div class="grin_box_border mb-4">
      <div class="row">
        <div class="col-md-6">
          <p class="choosing-business-type-text">
            Did you receive any income from employment in a foreign country?
          </p>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input custom-radio foreign-yes"
              type="radio"
              name="foreign_income[entries_non_resident][0][received]"
              id="incomeEmployment2Yes"
              value="yes"
              {{ old('foreign_income.entries_non_resident.0.received', $incomes->foreign_income['entries_non_resident'][0]['received'] ?? '') == 'yes' ? 'checked' : '' }}
            />
            <label
              class="form-check-label custom-label"
              for="incomeEmployment2Yes"
              >Yes</label
            >
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input custom-radio foreign-no"
              type="radio"
              name="foreign_income[entries_non_resident][0][received]"
              id="incomeEmployment2No"
              value="no"
              {{ old('foreign_income.entries_non_resident.0.received', $incomes->foreign_income['entries_non_resident'][0]['received'] ?? '') == 'no' ? 'checked' : '' }}
            />
            <label
              class="form-check-label custom-label"
              for="incomeEmployment2No"
              >No</label
            >
          </div>

          <div
            class="foreign-details employment-details"
            style="display:none; margin-top: 20px;"
          >
            <div class="row">
              <div class="col-12 mb-3">
                <label class="choosing-business-type-text">
                Were you non-resident for part or all of the tax year from 1 July <?= date('Y') - 1 ?> to 30 June <?= date('Y') ?>?
                </label
                ><br />
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input custom-radio non-resident-yes"
                    type="radio"
                    name="foreign_income[entries_non_resident][0][non_resident_status]"
                    id="nonResidentYes"
                    value="yes"
                    {{ old('foreign_income.entries_non_resident.0.non_resident_status', $incomes->foreign_income['entries_non_resident'][0]['non_resident_status'] ?? '') == 'yes' ? 'checked' : '' }}
                  />
                  <label
                    class="form-check-label custom-label"
                    for="nonResidentYes"
                    >Yes</label
                  >
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input custom-radio non-resident-no"
                    type="radio"
                    name="foreign_income[entries_non_resident][0][non_resident_status]"
                    id="nonResidentNo"
                    value="no"
                    {{ old('foreign_income.entries_non_resident.0.non_resident_status', $incomes->foreign_income['entries_non_resident'][0]['non_resident_status'] ?? '') == 'no' ? 'checked' : '' }}
                  />
                  <label
                    class="form-check-label custom-label"
                    for="nonResidentNo"
                    >No</label
                  >
                </div>
              </div>

              <div class="col-12 mb-3 help-debt-block" style="display:none;">
                <label class="choosing-business-type-text">
                Did you have a HELP or AASL (previously known as TSL) debt on 1 June <?= date('Y') ?>?  
                </label
                ><br />
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input custom-radio"
                    type="radio"
                    name="foreign_income[entries_non_resident][0][help_debt]"
                    id="helpDebtYes"
                    value="yes"
                    {{ old('foreign_income.entries_non_resident.0.help_debt', $incomes->foreign_income['entries_non_resident'][0]['help_debt'] ?? '') == 'yes' ? 'checked' : '' }}
                  />
                  <label
                    class="form-check-label custom-label"
                    for="helpDebtYes"
                    >Yes</label
                  >
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input custom-radio"
                    type="radio"
                    name="foreign_income[entries_non_resident][0][help_debt]"
                    id="helpDebtNo"
                    value="no"
                    {{ old('foreign_income.entries_non_resident.0.help_debt', $incomes->foreign_income['entries_non_resident'][0]['help_debt'] ?? '') == 'no' ? 'checked' : '' }}
                  />
                  <label
                    class="form-check-label custom-label"
                    for="helpDebtNo"
                    >No</label
                  >
                </div>
              </div>

              <div class="help-related-fields" style="display: none;">
                <div class="row">
                  <div class="col-md-6 mb-3 pt-0 pt-md-4">
                    <label class="choosing-business-type-text"
                      >Country you earned foreign income in</label
                    >
                    <input
                      type="text"
                      class="form-control border-dark"
                      name="foreign_income[entries_non_resident][0][country]"
                      placeholder="Country"
                      value="{{ old('foreign_income.entries_non_resident.0.country', $incomes->foreign_income['entries_non_resident'][0]['country'] ?? '') }}"
                    />
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="choosing-business-type-text"
                      >Occupation while earning foreign income</label
                    >
                    <input
                      type="text"
                      class="form-control border-dark"
                      name="foreign_income[entries_non_resident][0][occupation]"
                      placeholder="Occupation"
                      value="{{ old('foreign_income.entries_non_resident.0.occupation', $incomes->foreign_income['entries_non_resident'][0]['occupation'] ?? '') }}"
                    />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="choosing-business-type-text"
                      >Gross amount foreign income received</label
                    >
                    <input
                      type="number"
                      step="0.01"
                      class="form-control border-dark"
                      name="foreign_income[entries_non_resident][0][gross_amount]"
                      placeholder="00.00$"
                      value="{{ old('foreign_income.entries_non_resident.0.gross_amount', $incomes->foreign_income['entries_non_resident'][0]['gross_amount'] ?? '') }}"
                    />
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
  </section>

</section>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const toggleBlockDisplay = (condition, block) => {
        if (block) block.style.display = condition ? "block" : "none";
    };

    const clearInputs = (container) => {
        if (!container) return;
        container.querySelectorAll("input, select, textarea").forEach(el => {
            if (el.tagName === "SELECT") el.selectedIndex = 0;
            else if (el.type === "radio" || el.type === "checkbox") el.checked = false;
            else el.value = "";
        });
    };

    const updateNonResidentBlocks = (foreignDetails) => {
        if (!foreignDetails) return;

        const nonResidentYes = foreignDetails.querySelector(".non-resident-yes");
        const nonResidentNo = foreignDetails.querySelector(".non-resident-no");
        const helpDebtBlock = foreignDetails.querySelector(".help-debt-block");
        const helpDebtYes = foreignDetails.querySelector("#helpDebtYes");
        const helpDebtNo = foreignDetails.querySelector("#helpDebtNo");
        const helpFields = foreignDetails.querySelector(".help-related-fields");

        // Показываем help-debt-block только если nonResidentYes выбран
        toggleBlockDisplay(nonResidentYes?.checked, helpDebtBlock);

        // Очистка и скрытие help-debt и help-fields если nonResidentNo выбран
        if (nonResidentNo?.checked) {
            clearInputs(helpDebtBlock);
            toggleBlockDisplay(false, helpFields);
        }

        // Показываем help-related-fields только если helpDebtYes выбран
        toggleBlockDisplay(nonResidentYes?.checked && helpDebtYes?.checked, helpFields);

        // Очистка help-related-fields если helpDebtNo выбран
        if (helpDebtNo?.checked) clearInputs(helpFields);
    };

    document.querySelectorAll(".grin_box_border").forEach(block => {
        const foreignYes = block.querySelector(".foreign-yes");
        const foreignNo = block.querySelector(".foreign-no");
        const foreignDetails = block.querySelector(".foreign-details");

        if (!foreignYes || !foreignNo || !foreignDetails) return;

        const toggleForeignDetails = () => {
            toggleBlockDisplay(foreignYes.checked, foreignDetails);

            if (!foreignYes.checked) {
                clearInputs(foreignDetails);
            }

            // Обновляем все вложенные блоки
            updateNonResidentBlocks(foreignDetails);
        };

        // Навешиваем события на верхние radio
        foreignYes.addEventListener("change", toggleForeignDetails);
        foreignNo.addEventListener("change", toggleForeignDetails);

        // Инициализация при загрузке страницы
        toggleForeignDetails();
    });

    // Навешиваем события на non-resident и help-debt радио внутри foreign-details
    document.querySelectorAll(".foreign-details").forEach(details => {
        const radios = details.querySelectorAll(".non-resident-yes, .non-resident-no, #helpDebtYes, #helpDebtNo");
        radios.forEach(radio => {
            radio.addEventListener("change", () => {
                updateNonResidentBlocks(details);
            });
        });

        // Инициализация при загрузке страницы
        updateNonResidentBlocks(details);
    });

});
</script>






