<section>
  <!-- Education-Related Car Expense -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Education-Related Car Expense</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <div class="grin_box_border mb-3">
    <label class="form-label d-block">Did you use your vehicle for travel between your home or work and your classes?</label>
    <div class="form-check form-check-inline">
      <input
        class="form-check-input custom-radio"
        type="radio"
        name="education[car_travel]"
        id="car_travel_yes"
        value="yes"
        {{ old('education.car_travel', $deductions->education['car_travel'] ?? '') === 'yes' ? 'checked' : '' }}
      >
      <label class="form-check-label custom-label" for="car_travel_yes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
      <input
        class="form-check-input custom-radio"
        type="radio"
        name="education[car_travel]"
        id="car_travel_no"
        value="no"
        {{ old('education.car_travel', $deductions->education['car_travel'] ?? '') === 'no' ? 'checked' : '' }}
      >
      <label class="form-check-label custom-label" for="car_travel_no">No</label>
    </div>

    <div id="car_travel_block" class="mt-3" style="display: {{ old('education.car_travel', $deductions->education['car_travel'] ?? '') === 'yes' ? 'block' : 'none' }};">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">In a few words, why do you use your car for Education?</label>
          <input
            type="text"
            class="form-control"
            name="education[car_education_reason]"
            placeholder="abc"
            value="{{ old('education.car_education_reason', $deductions->education['car_education_reason'] ?? '') }}"
          >
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Number of kilometres travelled for education?</label>
          <input
            type="text"
            class="form-control"
            name="education[car_kilometres]"
            placeholder="0 km"
            value="{{ old('education.car_kilometres', $deductions->education['car_kilometres'] ?? '') }}"
          >
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label d-block">Is the vehicle registered in your name?</label>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input custom-radio"
              type="radio"
              name="education[vehicle_owned]"
              id="vehicle_owned_yes"
              value="yes"
              {{ old('education.vehicle_owned', $deductions->education['vehicle_owned'] ?? '') === 'yes' ? 'checked' : '' }}
            >
            <label class="form-check-label custom-label" for="vehicle_owned_yes">Yes</label>
          </div>
          <div class="form-check form-check-inline">
            <input
              class="form-check-input custom-radio"
              type="radio"
              name="education[vehicle_owned]"
              id="vehicle_owned_no"
              value="no"
              {{ old('education.vehicle_owned', $deductions->education['vehicle_owned'] ?? '') === 'no' ? 'checked' : '' }}
            >
            <label class="form-check-label custom-label" for="vehicle_owned_no">No</label>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Self-Education Expense -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Work-Related Self-Education Expenses</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <div class="grin_box_border mb-3">
    <label class="form-label d-block">Did you pay for self-education expenses that are directly related to your current employment?</label>
    <div class="form-check form-check-inline">
      <input
        class="form-check-input custom-radio"
        type="radio"
        name="education[edu_expense]"
        id="edu_expense_yes"
        value="yes"
        {{ old('education.edu_expense', $deductions->education['edu_expense'] ?? '') === 'yes' ? 'checked' : '' }}
      >
      <label class="form-check-label custom-label" for="edu_expense_yes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
      <input
        class="form-check-input custom-radio"
        type="radio"
        name="education[edu_expense]"
        id="edu_expense_no"
        value="no"
        {{ old('education.edu_expense', $deductions->education['edu_expense'] ?? '') === 'no' ? 'checked' : '' }}
      >
      <label class="form-check-label custom-label" for="edu_expense_no">No</label>
    </div>

    <div id="edu_expense_block" class="mt-3" style="display: {{ old('education.edu_expense', $deductions->education['edu_expense'] ?? '') === 'yes' ? 'block' : 'none' }};">
      <div class="col-md-6 mb-3">
        <label class="form-label">Why did you do this education?</label>
        <select name="education[edu_reason]" class="form-select">
          <option value="">Choose</option>
          <option value="Maintains or improves a skill or specific knowledge" {{ old('education.edu_reason', $deductions->education['edu_reason'] ?? '') === 'Maintains or improves a skill or specific knowledge' ? 'selected' : '' }}>Maintains or improves a skill or specific knowledge</option>
          <option value="Leads to increased income" {{ old('education.edu_reason', $deductions->education['edu_reason'] ?? '') === 'Leads to increased income' ? 'selected' : '' }}>Leads to increased income</option>
          <option value="Other circumstances" {{ old('education.edu_reason', $deductions->education['edu_reason'] ?? '') === 'Other circumstances' ? 'selected' : '' }}>Other circumstances</option>
        </select>
      </div>

      <div id="edu_expense_items">
        @php
          $eduExpenses = old('education.expenses', isset($deductions->education['expenses']) ? $deductions->education['expenses'] : []);
          $eduCount = count($eduExpenses) > 0 ? count($eduExpenses) : 1;
        @endphp

        @for($i = 0; $i < $eduCount; $i++)
        <div class="grin_box_border p-3 mb-3 edu-expense-item">
          <div class="col-md-6 mb-3">
            <label class="form-label">Type of education expense</label>
            <select name="education[expenses][{{ $i }}][type]" class="form-select edu-expense-type">
              <option value="">Choose</option>
              <option value="Education Fees" {{ old("education.expenses.$i.type", $eduExpenses[$i]['type'] ?? '') === 'Education Fees' ? 'selected' : '' }}>Education Fees</option>
              <option value="Books stationery consumables" {{ old("education.expenses.$i.type", $eduExpenses[$i]['type'] ?? '') === 'Books stationery consumables' ? 'selected' : '' }}>Books stationery consumables</option>
              <option value="Laptop Computer" {{ old("education.expenses.$i.type", $eduExpenses[$i]['type'] ?? '') === 'Laptop Computer' ? 'selected' : '' }}>Laptop Computer</option>
              <option value="Desktop Computer" {{ old("education.expenses.$i.type", $eduExpenses[$i]['type'] ?? '') === 'Desktop Computer' ? 'selected' : '' }}>Desktop Computer</option>
              <option value="Tablet Computer" {{ old("education.expenses.$i.type", $eduExpenses[$i]['type'] ?? '') === 'Tablet Computer' ? 'selected' : '' }}>Tablet Computer</option>
              <option value="Other" {{ old("education.expenses.$i.type", $eduExpenses[$i]['type'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
              <option value="Repair expenses" {{ old("education.expenses.$i.type", $eduExpenses[$i]['type'] ?? '') === 'Repair expenses' ? 'selected' : '' }}>Repair expenses</option>
            </select>
          </div>

          <div class="col-md-6 mb-3 amount-paid-block" style="{{ old("education.expenses.$i.type", $eduExpenses[$i]['type'] ?? '') === 'laptop' ? 'display: none;' : '' }}">
            <label class="form-label">Amount you paid for this item</label>
            <input
              type="number"
              step="0.01"
              class="form-control edu-amount"
              name="education[expenses][{{ $i }}][amount]"
              placeholder="00.00$"
              value="{{ old("education.expenses.$i.amount", $eduExpenses[$i]['amount'] ?? '') }}"
            >
          </div>

          <div class="laptop-extra-fields" style="{{ old("education.expenses.$i.type", $eduExpenses[$i]['type'] ?? '') === 'laptop' ? 'display: block;' : 'display: none;' }}">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Total Amount you paid for this item</label>
                <input
                  type="number"
                  step="0.01"
                  class="form-control laptop-total-amount"
                  name="education[expenses][{{ $i }}][laptop_total_amount]"
                  placeholder="00.00$"
                  value="{{ old("education.expenses.$i.laptop_total_amount", $eduExpenses[$i]['laptop_total_amount'] ?? '') }}"
                >
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">What Percent of use was for education purposes?</label>
                <input
                  type="number"
                  step="0.01"
                  min="0"
                  max="100"
                  class="form-control laptop-percent-use"
                  name="education[expenses][{{ $i }}][laptop_percent_use]"
                  placeholder="0%"
                  value="{{ old("education.expenses.$i.laptop_percent_use", $eduExpenses[$i]['laptop_percent_use'] ?? '') }}"
                >
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="choosing-business-type-text">Day</label>
                <select name="education[expenses][{{ $i }}][laptop_purchase_day]" class="form-control border-dark">
                  <option value="">Day</option>
                  @for ($d = 1; $d <= 31; $d++)
                    <option value="{{ $d }}" {{ old("education.expenses.$i.laptop_purchase_day", $eduExpenses[$i]['laptop_purchase_day'] ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
                  @endfor
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="choosing-business-type-text">Month</label>
                <select name="education[expenses][{{ $i }}][laptop_purchase_month]" class="form-control border-dark">
                  <option value="">Month</option>
                  @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ old("education.expenses.$i.laptop_purchase_month", $eduExpenses[$i]['laptop_purchase_month'] ?? '') == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                  @endfor
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="choosing-business-type-text">Year</label>
                <select name="education[expenses][{{ $i }}][laptop_purchase_year]" class="form-control border-dark">
                  <option value="">Year</option>
                  @for ($y = date('Y'); $y >= 1990; $y--)
                    <option value="{{ $y }}" {{ old("education.expenses.$i.laptop_purchase_year", $eduExpenses[$i]['laptop_purchase_year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                  @endfor
                </select>
              </div>
            </div>
          </div>
        </div>
        @endfor
      </div>

      <div class="mb-3">
        <button type="button" class="btn btn_add" id="addEduExpense">
          <img src="{{ asset('img/icons/plus.png') }}" alt="plus">Add another education expense
        </button>
      </div>

      <!-- File upload -->
      <div class="row mb-3 align-items-end">
        <p class="choosing-business-type-text">
          Attach a simple breakdown of your expenses (optional)
        </p>
        <div class="col-md-6 mb-3">
          <input type="file" name="education[edu_file]" id="eduFileInput" class="d-none">
          <button type="button" class="btn btn_add" id="triggerEduFile">
            <img src="{{ asset('img/icons/plus.png') }}" alt="plus">
            Choose file
          </button>
            <p class="text-muted mt-1 mb-0">
                Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
            </p>
        </div>
        <div class="col-md-6 mb-3">
          <p id="eduFileName" class="choosing-business-type-text text-muted mb-0">
            @if(!empty($deductions->attach['education']['edu_file']))
              <a href="{{ Storage::disk('s3')->url($deductions->attach['education']['edu_file']) }}" target="_blank" class="btn btn-outline-success">
                <i class="fa-solid fa-file"></i>
                View file
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

<!-- JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Show/hide car travel block
  document.querySelectorAll('input[name="education[car_travel]"]').forEach(input => {
    input.addEventListener('change', function () {
      document.getElementById('car_travel_block').style.display = this.value === 'yes' ? 'block' : 'none';
    });
  });

  // Show/hide education block
  document.querySelectorAll('input[name="education[edu_expense]"]').forEach(input => {
    input.addEventListener('change', function () {
      document.getElementById('edu_expense_block').style.display = this.value === 'yes' ? 'block' : 'none';
    });
  });

  const eduContainer = document.getElementById('edu_expense_items');
  const addEduBtn = document.getElementById('addEduExpense');
  const totalField = document.getElementById('edu_total');

  function updateEduTotal() {
    let total = 0;
    document.querySelectorAll('.edu-amount').forEach(input => {
      const val = parseFloat(input.value);
      if (!isNaN(val)) total += val;
    });

    // Calculate laptop expenses
    document.querySelectorAll('.laptop-total-amount').forEach((input, index) => {
      const totalAmount = parseFloat(input.value) || 0;
      const percentUse = parseFloat(document.querySelectorAll('.laptop-percent-use')[index].value) || 0;
      if (!isNaN(totalAmount) && !isNaN(percentUse)) {
        total += totalAmount * (percentUse / 100);
      }
    });

    if(totalField) {
        totalField.textContent = total.toFixed(2) + "$";
    }
  }

  function toggleLaptopFields(block) {
    const typeSelect = block.querySelector('.edu-expense-type');
    const amountBlock = block.querySelector('.amount-paid-block');
    const laptopExtra = block.querySelector('.laptop-extra-fields');

    if (typeSelect.value === 'laptop') {
      amountBlock.style.display = 'none';
      laptopExtra.style.display = 'block';
    } else {
      amountBlock.style.display = 'block';
      laptopExtra.style.display = 'none';
    }
  }

  function initEduBlock(block) {
    const typeSelect = block.querySelector('.edu-expense-type');
    const amountInput = block.querySelector('.edu-amount');
    const laptopTotalInput = block.querySelector('.laptop-total-amount');
    const laptopPercentInput = block.querySelector('.laptop-percent-use');

    toggleLaptopFields(block);

    typeSelect.addEventListener('change', () => {
      toggleLaptopFields(block);
      updateEduTotal();
    });

    amountInput.addEventListener('input', updateEduTotal);
    if (laptopTotalInput) laptopTotalInput.addEventListener('input', updateEduTotal);
    if (laptopPercentInput) laptopPercentInput.addEventListener('input', updateEduTotal);
  }

  eduContainer.querySelectorAll('.edu-expense-item').forEach(initEduBlock);

  addEduBtn.addEventListener('click', function () {
    const blocks = eduContainer.querySelectorAll('.edu-expense-item');
    const newIndex = blocks.length;
    const first = blocks[0];
    const clone = first.cloneNode(true);

    // Clear all values
    clone.querySelectorAll('input').forEach(input => {
      input.value = '';
    });
    clone.querySelectorAll('select').forEach(select => {
      select.selectedIndex = 0;
    });

    // Update names with new index
    clone.querySelectorAll('[name]').forEach(el => {
      const name = el.getAttribute('name');
      el.setAttribute('name', name.replace(/education\[expenses\]\[\d+\]/, `education[expenses][${newIndex}]`));
    });

    // Reset display states
    clone.querySelector('.laptop-extra-fields').style.display = 'none';
    clone.querySelector('.amount-paid-block').style.display = 'block';

    eduContainer.appendChild(clone);
    initEduBlock(clone);
  });

  // File input handler
  const eduFileInput = document.getElementById("eduFileInput");
  const triggerEduFile = document.getElementById("triggerEduFile");
  const eduFileName = document.getElementById("eduFileName");

  triggerEduFile.addEventListener("click", () => eduFileInput.click());

  eduFileInput.addEventListener("change", () => {
    eduFileName.textContent = eduFileInput.files.length
      ? eduFileInput.files[0].name
      : "No file chosen";
  });

  // Initialize total on page load
  updateEduTotal();
});
</script>
