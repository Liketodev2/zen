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
          <input type="file" name="home_office[home_receipt]" id="homeFileInput" class="d-none" />
          <button type="button" class="btn btn_add" id="triggerHomeFile">
            <img src="{{ asset('img/icons/plus.png') }}" alt="plus" /> Choose file
          </button>
            <p class="text-muted mt-1 mb-0">
                Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
            </p>
          <p id="homeSelectedFile" class="choosing-business-type-text text-muted mb-0 mt-2">
            @if(!empty($deductions->attach['home_office']['home_receipt']))
              <a href="{{ Storage::disk('s3')->url($deductions->attach['home_office']['home_receipt']) }}" target="_blank" class="btn btn-outline-success">
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
  const $ = (id) => document.getElementById(id);

  // Initialize form state based on saved values
  function initializeForm() {
    // Set initial visibility based on saved values
    toggleHoursRecordBlock();
    toggleHoursDetailBlocks();
    toggleTypicalHoursYesBlock();
    // toggleOtherExpensesBlocks();
    // toggleOtherExpensesBlocks_YES();

    // Show furniture date fields if needed
    const expenseType = document.querySelector('.expense-type');
    if (expenseType && expenseType.value === 'furniture_over_300') {
      document.getElementById('furnitureDate_1').style.display = 'block';
    }

    const expenseTypeYes = document.querySelector('.expense-type-select-yes');
    if (expenseTypeYes && expenseTypeYes.value === 'furniture_over_300') {
      document.querySelector('.purchase-date-yes').style.display = 'block';
    }
  }

  const workedRadios = document.querySelectorAll('[name="home_office[worked_from_home]"]');
  const hoursRecordRadios = document.querySelectorAll('[name="home_office[have_hours_record]"]');
  const typicalHoursRadios = document.querySelectorAll('[name="home_office[typical_hours_record]"]');

  function toggleHoursRecordBlock() {
    const worked = document.querySelector('input[name="home_office[worked_from_home]"]:checked');
    if (worked && worked.value === "1") {
      $("hoursRecordBlock").style.display = "block";
    } else {
      $("hoursRecordBlock").style.display = "none";
      $("blockIfHoursRecordYes").style.display = "none";
      $("blockIfHoursRecordNo").style.display = "none";
      $("typicalHoursYesBlock").style.display = "none";
    }
  }

  function toggleHoursDetailBlocks() {
    const selected = document.querySelector('input[name="home_office[have_hours_record]"]:checked');
    if (selected?.value === "0") {
      $("blockIfHoursRecordYes").style.display = "block";
      $("blockIfHoursRecordNo").style.display = "none";
      $("typicalHoursYesBlock").style.display = "none";
    } else if (selected?.value === "0") {
      $("blockIfHoursRecordYes").style.display = "none";
      $("blockIfHoursRecordNo").style.display = "block";
    }
  }

  function toggleTypicalHoursYesBlock() {
    const selected = document.querySelector('input[name="home_office[typical_hours_record]"]:checked');
    $("typicalHoursYesBlock").style.display = selected?.value === "1" ? "block" : "none";
  }

  const fileInput = $("homeFileInput");
  const fileTrigger = $("triggerHomeFile");
  const fileDisplay = $("homeSelectedFile");

  fileTrigger?.addEventListener("click", () => fileInput.click());
  fileInput?.addEventListener("change", () => {
    fileDisplay.innerHTML = fileInput.files[0]?.name || "No file chosen";
  });

  workedRadios.forEach(r => r.addEventListener("change", toggleHoursRecordBlock));
  hoursRecordRadios.forEach(r => r.addEventListener("change", toggleHoursDetailBlocks));
  typicalHoursRadios.forEach(r => r.addEventListener("change", toggleTypicalHoursYesBlock));

  // Initialize the form on page load
  initializeForm();
});

// Additional scripts for expense type handling
document.addEventListener("DOMContentLoaded", function () {
  const expenseTypes = document.querySelectorAll('.expense-type');

  expenseTypes.forEach(select => {
    select.addEventListener('change', function () {
      const dateBlock = document.getElementById('furnitureDate_1');

      if (this.value === 'furniture_over_300') {
        dateBlock.style.display = 'block';
      } else {
        dateBlock.style.display = 'none';
      }
    });
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const yesRadio = document.getElementById("otherExpensesYes");
  const noRadio = document.getElementById("otherExpensesNo");

  const noBlock = document.getElementById("otherExpensesNoBlock");
  const yesBlock = document.getElementById("otherExpensesDetailsBlock");

  function toggleOtherExpensesBlocks() {
    if (yesRadio && yesRadio.checked) {
      if (yesBlock) yesBlock.style.display = "block";
      if (noBlock) noBlock.style.display = "none";
    } else if (noRadio && noRadio.checked) {
      if (yesBlock) yesBlock.style.display = "none";
      if (noBlock) noBlock.style.display = "block";
    } else {
      if (yesBlock) yesBlock.style.display = "none";
      if (noBlock) noBlock.style.display = "none";
    }
  }

  if (yesRadio) yesRadio.addEventListener("change", toggleOtherExpensesBlocks);
  if (noRadio) noRadio.addEventListener("change", toggleOtherExpensesBlocks);

  toggleOtherExpensesBlocks();

  const fileInput = document.getElementById("hoursWorkedRecordFile");
  const fileTrigger = document.getElementById("triggerHoursWorkedFile");
  const fileNameDisplay = document.getElementById("hoursWorkedFileName");

  if (fileTrigger && fileInput) {
    fileTrigger.addEventListener("click", () => fileInput.click());
    fileInput.addEventListener("change", () => {
      if (fileNameDisplay) fileNameDisplay.textContent = fileInput.files[0]?.name || "No file chosen";
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const yesRadio_YES = document.getElementById("otherExpensesYes_YES");
  const noRadio_YES = document.getElementById("otherExpensesNo_YES");

  const noBlock_YES = document.getElementById("otherExpensesNoBlock_YES");
  const expenseBlock_YES = document.getElementById("expense_block_yes_1");

  function toggleOtherExpensesBlocks_YES() {
    if (yesRadio_YES && yesRadio_YES.checked) {
      if (expenseBlock_YES) expenseBlock_YES.style.display = "block";
      if (noBlock_YES) noBlock_YES.style.display = "none";
    } else if (noRadio_YES && noRadio_YES.checked) {
      if (expenseBlock_YES) expenseBlock_YES.style.display = "none";
      if (noBlock_YES) noBlock_YES.style.display = "block";
    } else {
      if (expenseBlock_YES) expenseBlock_YES.style.display = "none";
      if (noBlock_YES) noBlock_YES.style.display = "none";
    }
  }

  if (yesRadio_YES) yesRadio_YES.addEventListener("change", toggleOtherExpensesBlocks_YES);
  if (noRadio_YES) noRadio_YES.addEventListener("change", toggleOtherExpensesBlocks_YES);

  toggleOtherExpensesBlocks_YES();

  const fileInput_YES = document.getElementById("hoursWorkedRecordFile_YES");
  const fileTrigger_YES = document.getElementById("triggerHoursWorkedFile_YES");
  const fileNameDisplay_YES = document.getElementById("hoursWorkedFileName_YES");

  if (fileTrigger_YES && fileInput_YES) {
    fileTrigger_YES.addEventListener("click", () => fileInput_YES.click());
    fileInput_YES.addEventListener("change", () => {
      if (fileNameDisplay_YES) fileNameDisplay_YES.textContent = fileInput_YES.files[0]?.name || "No file chosen";
    });
  }

  const expenseTypeSelect_YES = document.querySelector('.expense-type-select-yes');
  const purchaseDateBlock_YES = document.querySelector('.purchase-date-yes');

  if (expenseTypeSelect_YES) {
    expenseTypeSelect_YES.addEventListener('change', () => {
      if (expenseTypeSelect_YES.value === 'furniture_over_300') {
        if (purchaseDateBlock_YES) purchaseDateBlock_YES.style.display = 'block';
      } else {
        if (purchaseDateBlock_YES) purchaseDateBlock_YES.style.display = 'none';
      }
    });
  }
});
</script>
