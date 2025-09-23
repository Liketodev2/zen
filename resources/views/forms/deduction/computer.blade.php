<section id="computerExpensesForm">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Computer / Laptop</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <p class="choosing-business-type-text mb-4">
    Are you required to do some of your work on your personal computer? Did you pay for the computer yourself?
    If you answered “yes”, then you might be able to claim part of the cost as a deduction.
    Includes desktop, laptop, tablet, iPad or printer that cost more than $300.
  </p>

  <div class="col-md-6 form-group mb-3">
    <label class="choosing-business-type-text" for="computer_expense_count">
      Number of expenses you need to claim (choose)
    </label>
    <select name="computer[expense_count]" id="computer_expense_count" class="form-control border-dark">
      <option value="">Choose</option>
      @for ($i = 1; $i <= 5; $i++)
        <option value="{{ $i }}"
          {{ old('computer.expense_count', $deductions->computer['expense_count'] ?? '') == $i ? 'selected' : '' }}>
          {{ $i }}
        </option>
      @endfor
    </select>
  </div>

  @php
    $computerExpenses = old('computer.expenses', $deductions->computer['expenses'] ?? []);
    $computerCount = count($computerExpenses) > 0 ? count($computerExpenses) : 1;
  @endphp

  @for ($i = 0; $i < $computerCount; $i++)
  <div class="grin_box_border p-3 mb-3 computer-expense-block" id="computer_expense_block_{{ $i }}">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label>Describe the computer (eg. Apple MacBook Air)</label>
        <input type="text"
               name="computer[expenses][{{ $i }}][description]"
               class="form-control border-dark"
               placeholder="..."
               value="{{ $computerExpenses[$i]['description'] ?? '' }}">
      </div>

      <div class="col-md-6 mb-3">
        <label>In a few words, WHY do you use this computer for work?</label>
        <input type="text"
               name="computer[expenses][{{ $i }}][reason]"
               class="form-control border-dark"
               placeholder="..."
               value="{{ $computerExpenses[$i]['reason'] ?? '' }}">
      </div>

      <!-- Date of purchase -->
      <div class="col-md-4 mb-3">
        <label>Day</label>
        <select name="computer[expenses][{{ $i }}][day]" class="form-control border-dark">
          <option value="">Day</option>
          @for ($d = 1; $d <= 31; $d++)
            <option value="{{ $d }}" {{ ($computerExpenses[$i]['day'] ?? '') == $d ? 'selected' : '' }}>
              {{ $d }}
            </option>
          @endfor
        </select>
      </div>
      <div class="col-md-4 mb-3">
        <label>Month</label>
        <select name="computer[expenses][{{ $i }}][month]" class="form-control border-dark">
          <option value="">Month</option>
          @for ($m = 1; $m <= 12; $m++)
            @php $monthName = DateTime::createFromFormat('!m', $m)->format('F'); @endphp
            <option value="{{ $m }}" {{ ($computerExpenses[$i]['month'] ?? '') == $m ? 'selected' : '' }}>
              {{ $monthName }}
            </option>
          @endfor
        </select>
      </div>
      <div class="col-md-4 mb-3">
        <label>Year</label>
        <select name="computer[expenses][{{ $i }}][year]" class="form-control border-dark">
          <option value="">Year</option>
          @for ($y = date('Y'); $y >= 1990; $y--)
            <option value="{{ $y }}" {{ ($computerExpenses[$i]['year'] ?? '') == $y ? 'selected' : '' }}>
              {{ $y }}
            </option>
          @endfor
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label>What % of this computer’s usage is for your work?</label>
        <input type="text"
               name="computer[expenses][{{ $i }}][percentage]"
               class="form-control border-dark"
               placeholder="0%"
               value="{{ $computerExpenses[$i]['percentage'] ?? '' }}">
      </div>

      <div class="col-md-6 mb-3">
        <label>What sort of records do you have for this item?</label>
        <select name="computer[expenses][{{ $i }}][record]" class="form-control border-dark">
          <option value="">Choose</option>
          <option value="Invoice / receipt" {{ ($computerExpenses[$i]['record'] ?? '') === 'Invoice / receipt' ? 'selected' : '' }}>Invoice / receipt</option>
          <option value="Actual recorded cost" {{ ($computerExpenses[$i]['record'] ?? '') === 'Actual recorded cost' ? 'selected' : '' }}>Actual recorded cost</option>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label>Cost of this item</label>
        <input type="number"
               name="computer[expenses][{{ $i }}][cost]"
               step="0.01"
               class="form-control border-dark"
               placeholder="00.00$"
               value="{{ $computerExpenses[$i]['cost'] ?? '' }}">
      </div>
    </div>
  </div>
  @endfor

  <!-- File upload -->
  <div class="mt-3">
    <label class="choosing-business-type-text d-block mb-2">
      Attach a copy of your receipts or invoices (optional)
    </label>
    <input type="file" name="computer[computer_file]" id="computerFileInput" class="d-none" />
    <button type="button" class="btn btn_add" id="triggerComputerFile">
      <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Choose file
    </button>
      <p class="text-muted mt-1 mb-0">
          Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
      </p>
      <p id="computerFileName" class="choosing-business-type-text text-muted mt-2 mb-0">
          @if(!empty($deductions->attach['computer']['computer_file']))
              <a href="{{ asset('storage/'.$deductions->attach['computer']['computer_file']) }}" target="_blank" class="btn btn-outline-success">
                  <i class="fa-solid fa-file"></i>
                  View file
              </a>
          @else
              No file chosen
          @endif
      </p>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const computerFileInput = document.getElementById("computerFileInput");
  const triggerComputerFile = document.getElementById("triggerComputerFile");
  const computerFileName = document.getElementById("computerFileName");

  triggerComputerFile.addEventListener("click", () => computerFileInput.click());

  computerFileInput.addEventListener("change", () => {
    computerFileName.textContent = computerFileInput.files.length
      ? computerFileInput.files[0].name
      : "No file chosen";
  });
});
</script>
