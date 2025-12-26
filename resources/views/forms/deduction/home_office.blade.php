<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Home Office Expenses</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <p class="choosing-business-type-text">Please read this section carefully:</p>
  <div class="grin_box_border">
    <p class="choosing-business-type-text mb-3">
      If you worked at home this year and want to claim your expenses, you need to have a detailed record that shows the days and hours you worked at home, plus any expenses (related to your home office) that you'd like to claim as tax deductions.
    </p>

    <p class="choosing-business-type-text">Did you regularly work from home this year?</p>
    <div class="form-check form-check-inline">
      <input class="form-check-input custom-radio" type="radio" name="home_office[worked_from_home]" id="workedFromHomeYes" value="1"
        {{ isset($deductions->home_office['worked_from_home']) && $deductions->home_office['worked_from_home'] == '1' ? 'checked' : '' }}>
      <label class="form-check-label custom-label" for="workedFromHomeYes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input custom-radio" type="radio" name="home_office[worked_from_home]" id="workedFromHomeNo" value="0"
        {{ isset($deductions->home_office['worked_from_home']) && $deductions->home_office['worked_from_home'] == '0' ? 'checked' : '' }}>
      <label class="form-check-label custom-label" for="workedFromHomeNo">No</label>
    </div>

    <!-- Block: Have hours record -->
    <div id="hoursRecordBlock" style="display:none; margin-top: 1rem;">
      <p class="choosing-business-type-text">
        Do you have a record of the number of hours that you worked at home each day? (E.g. diary, timesheets, logbook.)
      </p>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio" type="radio" name="home_office[have_hours_record]" id="hoursRecordYes" value="1"
          {{ isset($deductions->home_office['have_hours_record']) && $deductions->home_office['have_hours_record'] == '1' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="hoursRecordYes">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio" type="radio" name="home_office[have_hours_record]" id="hoursRecordNo" value="0"
          {{ isset($deductions->home_office['have_hours_record']) && $deductions->home_office['have_hours_record'] == '0' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="hoursRecordNo">No</label>
      </div>
    </div>

    <!-- Block: YES -> Provide details -->
    <div id="blockIfHoursRecordYes" class="col-md-6" style="display:none; margin-top: 1rem;">
      <div class="form-group mb-3">
        <label class="choosing-business-type-text" for="home_office_total_hours_worked_yes">
          How many hours in total did you work from home during the year?
        </label>
        <input type="number" min="0" id="home_office_total_hours_worked_yes" name="home_office[total_hours_worked_yes]" class="form-control"
          value="{{ $deductions->home_office['total_hours_worked_yes'] ?? 0 }}" />
      </div>

      <p class="choosing-business-type-text">
        Do you have other home office expenses to claim, such as home telephone, office furniture or stationery?
      </p>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio" type="radio" name="home_office[other_home_expenses_yes]" id="otherExpensesYes_YES" value="1"
          {{ isset($deductions->home_office['other_home_expenses_yes']) && $deductions->home_office['other_home_expenses_yes'] == '1' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="otherExpensesYes_YES">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio" type="radio" name="home_office[other_home_expenses_yes]" id="otherExpensesNo_YES" value="0"
          {{ isset($deductions->home_office['other_home_expenses_yes']) && $deductions->home_office['other_home_expenses_yes'] == '0' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="otherExpensesNo_YES">No</label>
      </div>

      <!-- NO block -->
      <div id="otherExpensesNoBlock_YES" style="display:none; margin-top:1rem;">
        <p class="choosing-business-type-text">
          Attach a record for the hours you worked from home (optional)
        </p>
        <input type="file" name="home_office[hours_worked_record_file_yes]" id="hoursWorkedRecordFile_YES" class="d-none" />
        <button type="button" class="btn btn_add" id="triggerHoursWorkedFile_YES">
          <img src="{{ asset('img/icons/plus.png') }}" alt="plus" /> Choose file
        </button>
          <p class="text-muted mt-1 mb-0">
              Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
          </p>
        <p id="hoursWorkedFileName_YES" class="choosing-business-type-text text-muted mb-0 mt-2">
          @if(!empty($deductions->attach['home_office']['hours_worked_record_file_yes']))
            <a href="{{ Storage::disk('s3')->url($deductions->attach['home_office']['hours_worked_record_file_yes']) }}" target="_blank" class="btn btn-outline-success">
              <i class="fa-solid fa-file"></i> View file
            </a>
          @else
            No file chosen
          @endif
        </p>
      </div>

      <!-- YES block expense -->
      <div class="expense-block" id="expense_block_yes_1" style="display:none; border:1px solid #ccc; padding:10px; margin-bottom:15px;">
        <label>Type of expense</label>
        <select name="home_office[expense_type_yes_1]" class="form-control expense-type-select-yes">
          <option value="">Choose</option>
          <option value="Home Telephone Bills" {{ isset($deductions->home_office['expense_type_yes_1']) && $deductions->home_office['expense_type_yes_1'] == 'Home Telephone Bills' ? 'selected' : '' }}>Home Telephone Bills</option>
          <option value="Office furniture over $300" {{ isset($deductions->home_office['expense_type_yes_1']) && $deductions->home_office['expense_type_yes_1'] == 'Office furniture over $300' ? 'selected' : '' }}>Office furniture over $300</option>
          <option value="Office furniture under $300 (e.g. chair)" {{ isset($deductions->home_office['expense_type_yes_1']) && $deductions->home_office['expense_type_yes_1'] == 'Office furniture under $300 (e.g. chair)' ? 'selected' : '' }}>Office furniture under $300 (e.g. chair)</option>
          <option value="Office equipment under $300 (e.g. mouse)" {{ isset($deductions->home_office['expense_type_yes_1']) && $deductions->home_office['expense_type_yes_1'] == 'Office equipment under $300 (e.g. mouse)' ? 'selected' : '' }}>Office equipment under $300 (e.g. mouse)</option>
          <option value="Repairs to office equipment & furniture" {{ isset($deductions->home_office['expense_type_yes_1']) && $deductions->home_office['expense_type_yes_1'] == 'repairs' ? 'selected' : '' }}>Repairs to office equipment & furniture</option>
          <option value="Printing and Stationery" {{ isset($deductions->home_office['expense_type_yes_1']) && $deductions->home_office['expense_type_yes_1'] == 'stationery' ? 'selected' : '' }}>Printing and Stationery</option>
        </select>

        <div class="purchase-date-yes" style="display:none; margin-top:10px;">
          <label>Purchase Date</label>
          <div class="row">
            <div class="col-4">
              <select name="home_office[expense_day_yes_1]" class="form-control border-dark">
                <option value="">Day</option>
                @for ($i = 1; $i <= 31; $i++)
                  <option value="{{ $i }}" {{ isset($deductions->home_office['expense_day_yes_1']) && $deductions->home_office['expense_day_yes_1'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
              </select>
            </div>
            <div class="col-4">
              <select name="home_office[expense_month_yes_1]" class="form-control border-dark">
                <option value="">Month</option>
                @for ($i = 1; $i <= 12; $i++)
                  <option value="{{ $i }}" {{ isset($deductions->home_office['expense_month_yes_1']) && $deductions->home_office['expense_month_yes_1'] == $i ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                @endfor
              </select>
            </div>
            <div class="col-4">
              <select name="home_office[expense_year_yes_1]" class="form-control border-dark">
                <option value="">Year</option>
                @for ($i = date('Y'); $i >= 1990; $i--)
                  <option value="{{ $i }}" {{ isset($deductions->home_office['expense_year_yes_1']) && $deductions->home_office['expense_year_yes_1'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
              </select>
            </div>
          </div>
        </div>

        <label style="margin-top:10px;">What % of this expense is related to your work?</label>
        <input type="text" name="home_office[expense_percentage_yes_1]" placeholder="0%" class="form-control"
          value="{{ $deductions->home_office['expense_percentage_yes_1'] ?? '' }}">

        <label style="margin-top:10px;">What sort of records do you have for this expense?</label>
        <select name="home_office[expense_record_type_yes_1]" class="form-control">
          <option value="">Choose</option>
          <option value="I: Invoice / Receipt" {{ isset($deductions->home_office['expense_record_type_yes_1']) && $deductions->home_office['expense_record_type_yes_1'] == 'I: Invoice / Receipt' ? 'selected' : '' }}>I: Invoice / Receipt</option>
          <option value="L: Log book" {{ isset($deductions->home_office['expense_record_type_yes_1']) && $deductions->home_office['expense_record_type_yes_1'] == 'L: Log book' ? 'selected' : '' }}>L: Log book</option>
          <option value="A: Allowance received" {{ isset($deductions->home_office['expense_record_type_yes_1']) && $deductions->home_office['expense_record_type_yes_1'] == 'A: Allowance received' ? 'selected' : '' }}>A: Allowance received</option>
          <option value="C: Actual recorded cost" {{ isset($deductions->home_office['expense_record_type_yes_1']) && $deductions->home_office['expense_record_type_yes_1'] == 'C: Actual recorded cost' ? 'selected' : '' }}>C: Actual recorded cost</option>
        </select>

        <label style="margin-top:10px;">Total cost of this item</label>
        <input type="text" name="home_office[expense_cost_yes_1]" placeholder="00.00$" class="form-control"
          value="{{ $deductions->home_office['expense_cost_yes_1'] ?? '' }}">
      </div>
    </div>

    <!-- Block: NO -> Ask about typical 4-week record -->
    <div id="blockIfHoursRecordNo" style="display:none; margin-top: 1rem;">
      <div class="grin_box_border">
        <p class="choosing-business-type-text">
          <strong>TIP:</strong> If you haven't kept a record personally, check to see if your employer is able to provide you with a record of your work from home hours.
        </p>
      </div>

      <p class="choosing-business-type-text" style="margin-top: 1.5rem;">
        Do you have a record that represents the typical hours you worked at home for a continuous 4-week period (e.g. diary entries)?
      </p>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio" type="radio" name="home_office[typical_hours_record]" id="typicalHoursYes" value="1"
          {{ isset($deductions->home_office['typical_hours_record']) && $deductions->home_office['typical_hours_record'] == '1' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="typicalHoursYes">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio" type="radio" name="home_office[typical_hours_record]" id="typicalHoursNo" value="0"
          {{ isset($deductions->home_office['typical_hours_record']) && $deductions->home_office['typical_hours_record'] == '0' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="typicalHoursNo">No</label>
      </div>

      <!-- If YES for typical_hours_record -->
      <div id="typicalHoursYesBlock" style="display: none; margin-top: 1rem;">
        <p class="choosing-business-type-text">
          Do you have other home office expenses to claim, such as home telephone, office furniture or stationery?
        </p>
        <div class="form-check form-check-inline">
          <input class="form-check-input custom-radio" type="radio" name="home_office[other_home_expenses]" id="otherExpensesYes" value="1"
            {{ isset($deductions->home_office['other_home_expenses']) && $deductions->home_office['other_home_expenses'] == '1' ? 'checked' : '' }}>
          <label class="form-check-label custom-label" for="otherExpensesYes">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input custom-radio" type="radio" name="home_office[other_home_expenses]" id="otherExpensesNo" value="0"
            {{ isset($deductions->home_office['other_home_expenses']) && $deductions->home_office['other_home_expenses'] == '0' ? 'checked' : '' }}>
          <label class="form-check-label custom-label" for="otherExpensesNo">No</label>
        </div>

        <div id="otherExpensesDetailsBlock" style="display: block; margin-top: 1rem;">
          <div class="form-group col-md-6 mb-3">
            <label for="number_of_expenses" class="choosing-business-type-text">
              Number of expenses you need to claim (choose)
            </label>
            <select name="home_office[number_of_expenses]" id="number_of_expenses" class="form-control border-dark">
              <option value="">Choose</option>
              @for ($i = 1; $i <= 7; $i++)
                <option value="{{ $i }}" {{ isset($deductions->home_office['number_of_expenses']) && $deductions->home_office['number_of_expenses'] == $i ? 'selected' : '' }}>{{ $i }}</option>
              @endfor
            </select>
          </div>

          <div class="grin_box_border p-3 mb-3">
            <div class="col-md-6">
              <div class="form-group mb-2">
                <label class="choosing-business-type-text">Type of expense</label>
                <select name="home_office[expense_type_1]" class="form-control border-dark expense-type">
                  <option value="">Choose</option>
                  <option value="Home Telephone Bills" {{ isset($deductions->home_office['expense_type_1']) && $deductions->home_office['expense_type_1'] == 'Home Telephone Bills' ? 'selected' : '' }}>Home Telephone Bills</option>
                  <option value="Office furniture over $300" {{ isset($deductions->home_office['expense_type_1']) && $deductions->home_office['expense_type_1'] == 'Office furniture over $300' ? 'selected' : '' }}>Office furniture over $300</option>
                  <option value="Office furniture under $300 (e.g. chair)" {{ isset($deductions->home_office['expense_type_1']) && $deductions->home_office['expense_type_1'] == 'Office furniture under $300 (e.g. chair)' ? 'selected' : '' }}>Office furniture under $300 (e.g. chair)</option>
                  <option value="Office equipment under $300 (e.g. mouse)" {{ isset($deductions->home_office['expense_type_1']) && $deductions->home_office['expense_type_1'] == 'Office equipment under $300 (e.g. mouse)' ? 'selected' : '' }}>Office equipment under $300 (e.g. mouse)</option>
                  <option value="Repairs to office equipment & furniture" {{ isset($deductions->home_office['expense_type_1']) && $deductions->home_office['expense_type_1'] == 'Repairs to office equipment & furniture' ? 'selected' : '' }}>Repairs to office equipment & furniture</option>
                  <option value="Printing and Stationery" {{ isset($deductions->home_office['expense_type_1']) && $deductions->home_office['expense_type_1'] == 'Printing and Stationery' ? 'selected' : '' }}>Printing and Stationery</option>
                </select>
              </div>

              <div class="form-group mb-2 furniture-date" id="furnitureDate_1" style="display: none;">
                <label class="choosing-business-type-text">Purchase Date</label>
                <div class="row">
                  <div class="col-4">
                    <select name="home_office[expense_day_1]" class="form-control border-dark">
                      <option value="">Day</option>
                      @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}" {{ isset($deductions->home_office['expense_day_1']) && $deductions->home_office['expense_day_1'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-4">
                    <select name="home_office[expense_month_1]" class="form-control border-dark">
                      <option value="">Month</option>
                      @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ isset($deductions->home_office['expense_month_1']) && $deductions->home_office['expense_month_1'] == $i ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-4">
                    <select name="home_office[expense_year_1]" class="form-control border-dark">
                      <option value="">Year</option>
                      @for ($i = date('Y'); $i >= 1990; $i--)
                        <option value="{{ $i }}" {{ isset($deductions->home_office['expense_year_1']) && $deductions->home_office['expense_year_1'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                      @endfor
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group mb-2">
                <label class="choosing-business-type-text">What % of this expense is related to your work?</label>
                <input type="text" name="home_office[expense_percentage_1]" class="form-control border-dark" placeholder="0%"
                  value="{{ $deductions->home_office['expense_percentage_1'] ?? '' }}">
              </div>

              <div class="form-group mb-2">
                <label class="choosing-business-type-text">What sort of records do you have for this expense?</label>
                <select name="home_office[expense_record_type_1]" class="form-control border-dark">
                  <option value="">Choose</option>
                  <option value="I: Invoice / Receipt" {{ isset($deductions->home_office['expense_record_type_1']) && $deductions->home_office['expense_record_type_1'] == 'I: Invoice / Receipt' ? 'selected' : '' }}>I: Invoice / Receipt</option>
                  <option value="L: Log book" {{ isset($deductions->home_office['expense_record_type_1']) && $deductions->home_office['expense_record_type_1'] == 'L: Log book' ? 'selected' : '' }}>L: Log book</option>
                  <option value="A: Allowance received" {{ isset($deductions->home_office['expense_record_type_1']) && $deductions->home_office['expense_record_type_1'] == 'A: Allowance received' ? 'selected' : '' }}>A: Allowance received</option>
                  <option value="C: Actual recorded cost" {{ isset($deductions->home_office['expense_record_type_1']) && $deductions->home_office['expense_record_type_1'] == 'C: Actual recorded cost' ? 'selected' : '' }}>C: Actual recorded cost</option>
                </select>
              </div>

              <div class="form-group mb-2">
                <label class="choosing-business-type-text">Total cost of this item</label>
                <input type="text" name="home_office[expense_cost_1]" class="form-control border-dark" placeholder="00.00$"
                  value="{{ $deductions->home_office['expense_cost_1'] ?? '' }}">
              </div>
            </div>
          </div>
        </div>

        <!-- File Upload -->
        <div class="col-12 mt-4 mb-3">
          <p class="choosing-business-type-text">
            Attach receipts or a log of your travel expenses (optional)
          </p>
          <input type="file" name="home_office[home_receipt_file]" id="homeFileInput" class="d-none" />
          <button type="button" class="btn btn_add" id="triggerHomeFile">
            <img src="{{ asset('img/icons/plus.png') }}" alt="plus" /> Choose file
          </button>
            <p class="text-muted mt-1 mb-0">
                Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
            </p>
          <p id="homeSelectedFile" class="choosing-business-type-text text-muted mb-0 mt-2">
            @if(!empty($deductions->attach['home_office']['home_receipt_file']))
              <a href="{{ Storage::disk('s3')->url($deductions->attach['home_office']['home_receipt_file']) }}" target="_blank" class="btn btn-outline-success">
                <i class="fa-solid fa-file"></i> View file
              </a>
            @else
              No file chosen
            @endif
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const $ = id => document.getElementById(id);

        /* ===============================
           WORK FROM HOME FLOW
        =============================== */

        function toggleWorkedFromHome() {
            const worked = document.querySelector('input[name="home_office[worked_from_home]"]:checked');
            const show = worked && worked.value === "1";

            $("hoursRecordBlock").style.display = show ? "block" : "none";
            if (!show) {
                $("blockIfHoursRecordYes").style.display = "none";
                $("blockIfHoursRecordNo").style.display = "none";
                $("typicalHoursYesBlock").style.display = "none";
            }
        }

        function toggleHoursRecord() {
            const record = document.querySelector('input[name="home_office[have_hours_record]"]:checked');

            if (!record) return;

            if (record.value === "1") {
                $("blockIfHoursRecordYes").style.display = "block";
                $("blockIfHoursRecordNo").style.display = "none";
                $("typicalHoursYesBlock").style.display = "none";
            } else {
                $("blockIfHoursRecordYes").style.display = "none";
                $("blockIfHoursRecordNo").style.display = "block";
            }
        }

        function toggleTypicalHours() {
            const typical = document.querySelector('input[name="home_office[typical_hours_record]"]:checked');
            $("typicalHoursYesBlock").style.display = typical?.value === "1" ? "block" : "none";
        }

        /* ===============================
           OTHER EXPENSES (YES FLOW)
        =============================== */

        function toggleOtherExpensesYES() {
            const yes = $("otherExpensesYes_YES")?.checked;
            $("expense_block_yes_1").style.display = yes ? "block" : "none";
            $("otherExpensesNoBlock_YES").style.display = yes ? "none" : "block";
        }

        /* ===============================
           EXPENSE TYPE → PURCHASE DATE
        =============================== */

        function togglePurchaseDate(select, dateBlock) {
            if (!select || !dateBlock) return;
            dateBlock.style.display =
                select.value === "Office furniture over $300" ? "block" : "none";
        }

        /* ===============================
           FILE INPUT HELPERS
        =============================== */

        function bindFile(triggerId, inputId, labelId) {
            const trigger = $(triggerId);
            const input = $(inputId);
            const label = $(labelId);

            if (!trigger || !input) return;

            trigger.addEventListener("click", () => input.click());
            input.addEventListener("change", () => {
                label.textContent = input.files[0]?.name || "No file chosen";
            });
        }

        /* ===============================
           EVENT BINDINGS
        =============================== */

        document.querySelectorAll('[name="home_office[worked_from_home]"]')
            .forEach(r => r.addEventListener("change", toggleWorkedFromHome));

        document.querySelectorAll('[name="home_office[have_hours_record]"]')
            .forEach(r => r.addEventListener("change", toggleHoursRecord));

        document.querySelectorAll('[name="home_office[typical_hours_record]"]')
            .forEach(r => r.addEventListener("change", toggleTypicalHours));

        $("otherExpensesYes_YES")?.addEventListener("change", toggleOtherExpensesYES);
        $("otherExpensesNo_YES")?.addEventListener("change", toggleOtherExpensesYES);

        document.querySelectorAll('.expense-type, .expense-type-select-yes')
            .forEach(select => {
                select.addEventListener("change", () => {
                    togglePurchaseDate(
                        select,
                        select.closest(".expense-block")?.querySelector(".purchase-date-yes")
                        || $("furnitureDate_1")
                    );
                });
            });

        bindFile("triggerHomeFile", "homeFileInput", "homeSelectedFile");
        bindFile("triggerHoursWorkedFile_YES", "hoursWorkedRecordFile_YES", "hoursWorkedFileName_YES");

        /* ===============================
           INITIALIZE STATE
        =============================== */

        toggleWorkedFromHome();
        toggleHoursRecord();
        toggleTypicalHours();
        toggleOtherExpensesYES();

        document.querySelectorAll('.expense-type, .expense-type-select-yes')
            .forEach(select =>
                togglePurchaseDate(
                    select,
                    select.closest(".expense-block")?.querySelector(".purchase-date-yes")
                    || $("furnitureDate_1")
                )
            );
    });
</script>

