<section id="travelExpensesForm">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Work-Related Travel Expenses</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <div id="travelExpenseContainer">
    <p class="choosing-business-type-text">
      For work-related travel costs other than using your car. Can include airfares, bus, train, taxi, meals and accommodation. Does not include travel between home and work. Only include items that you paid for yourself and were not repaid by your employer.
    </p>

    @php
      $expenses = old('travel_expenses.expenses', $deductions->travel_expenses['expenses'] ?? []);
      $expenseCount = count($expenses) > 0 ? count($expenses) : 1;
    @endphp

    @for($i = 0; $i < $expenseCount; $i++)
    <div class="grin_box_border mb-3 travel-expense-block" data-index="{{ $i }}">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Type of travel expense</label>
          <select class="form-control border-dark expense-type" name="travel_expenses[expenses][{{ $i }}][reason]">
            <option value="" disabled {{ empty($expenses[$i]['reason']) ? 'selected' : '' }}>Select</option>
            @foreach(['Airfares','Accommodation','Meals','Taxi','Bus','Train','Other'] as $option)
              <option value="{{ $option }}" {{ ($expenses[$i]['reason'] ?? '') === $option ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Specify if 'Other'</label>
          <input type="text" class="form-control border-dark other-description"
                 name="travel_expenses[expenses][{{ $i }}][other]"
                 placeholder="What do you do for a living?"
                 value="{{ $expenses[$i]['other'] ?? '' }}"
                 {{ ($expenses[$i]['reason'] ?? '') !== 'Other' ? 'disabled' : '' }}>
        </div>
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Total amount you paid for this item</label>
          <input type="number" step="0.01" class="form-control border-dark travel-amount"
                 name="travel_expenses[expenses][{{ $i }}][total]"
                 placeholder="00.00$"
                 value="{{ $expenses[$i]['total'] ?? '' }}">
        </div>
      </div>
      <div class="mb-2">
        <button type="button" class="btn btn_delete deleteTravelExpense">Delete</button>
      </div>
    </div>
    @endfor
  </div>

  <div class="mb-3">
    <button type="button" class="btn btn_add" id="addTravelExpense">
      <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another travel expense
    </button>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label class="choosing-business-type-text">Is any travel allowance listed on your PAYG Summary?</label><br>
      @php $paygValue = old('travel_expenses.payg_allowance', $deductions->travel_expenses['payg_allowance'] ?? ''); @endphp
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio" type="radio" name="travel_expenses[payg_allowance]" id="paygYes" value="1" {{ $paygValue == '1' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="paygYes">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio" type="radio" name="travel_expenses[payg_allowance]" id="paygNo" value="0" {{ $paygValue == '0' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="paygNo">No</label>
      </div>
    </div>
  </div>

  <div class="col-12 mt-4 mb-3">
    <p class="choosing-business-type-text">Attach receipts or a log of your travel expenses (optional)</p>
    <input type="file" name="travel_expenses[travel_file]" id="travelFileInput" class="d-none" />
    <button type="button" class="btn btn_add" id="triggerTravelFile">
      <img src="{{ asset('img/icons/plus.png') }}" alt="plus" /> Choose file
    </button>
      <p class="text-muted mt-1 mb-0">
          Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
      </p>
    <p id="travelSelectedFile" class="choosing-business-type-text text-muted mb-0 mt-2">
      @if(!empty($deductions->attach['travel_expenses']['travel_file']))
        <a href="{{ Storage::disk('s3')->url($deductions->attach['travel_expenses']['travel_file']) }}" target="_blank" class="btn btn-outline-success">
          <i class="fa-solid fa-file"></i> View file
        </a>
      @else
        {{ old('travel_expenses.travel_file', 'No file chosen') }}
      @endif
    </p>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const container = document.getElementById("travelExpenseContainer");
  const addBtn = document.getElementById("addTravelExpense");

  const blockTemplate = `
    <div class="grin_box_border mb-3 travel-expense-block" data-index="__INDEX__">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Type of travel expense</label>
          <select class="form-control border-dark expense-type" name="travel_expenses[expenses][__INDEX__][reason]">
            <option value="" disabled selected>Select</option>
            <option value="Airfares">Airfares</option>
            <option value="Accommodation">Accommodation</option>
            <option value="Meals">Meals</option>
            <option value="Taxi">Taxi</option>
            <option value="Bus">Bus</option>
            <option value="Train">Train</option>
            <option value="Other">Other (please specify)</option>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Specify if 'Other'</label>
          <input type="text" class="form-control border-dark other-description" name="travel_expenses[expenses][__INDEX__][other]" placeholder="What do you do for a living?" disabled>
        </div>
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Total amount you paid for this item</label>
          <input type="number" step="0.01" class="form-control border-dark travel-amount" name="travel_expenses[expenses][__INDEX__][total]" placeholder="00.00$">
        </div>
      </div>
      <div class="mb-2">
        <button type="button" class="btn btn_delete deleteTravelExpense">Delete</button>
      </div>
    </div>
  `;

  function refreshIndices() {
    const blocks = container.querySelectorAll(".travel-expense-block");
    blocks.forEach((block, index) => {
      block.dataset.index = index;
      block.querySelectorAll('input, select').forEach(input => {
        input.name = input.name.replace(/travel_expenses\[expenses\]\[\d+\]/, `travel_expenses[expenses][${index}]`);
      });
    });
  }

  function handleOtherInput(selectEl, inputEl) {
    inputEl.disabled = selectEl.value !== "Other";
    if(inputEl.disabled) inputEl.value = "";
  }

  function attachOtherHandlers() {
    container.querySelectorAll(".travel-expense-block").forEach(block => {
      const select = block.querySelector(".expense-type");
      const otherInput = block.querySelector(".other-description");
      handleOtherInput(select, otherInput);
      select.onchange = () => handleOtherInput(select, otherInput);
    });
  }

  function attachDeleteButtons() {
    container.querySelectorAll(".deleteTravelExpense").forEach(btn => {
      btn.onclick = () => {
        const blocks = container.querySelectorAll(".travel-expense-block");
        if (blocks.length > 1) {
          btn.closest(".travel-expense-block").remove();
          refreshIndices();
          attachOtherHandlers();
        } else {
          alert("You must have at least one travel expense.");
        }
      };
    });
  }

  addBtn.addEventListener("click", () => {
    const newIndex = container.querySelectorAll(".travel-expense-block").length;
    const newBlockHTML = blockTemplate.replace(/__INDEX__/g, newIndex);
    container.insertAdjacentHTML('beforeend', newBlockHTML);
    refreshIndices();
    attachDeleteButtons();
    attachOtherHandlers();
  });

  // Initialize handlers
  attachDeleteButtons();
  attachOtherHandlers();

  // File upload handling
  const travelFileInput = document.getElementById("travelFileInput");
  const travelTrigger = document.getElementById("triggerTravelFile");
  const travelFileDisplay = document.getElementById("travelSelectedFile");

  travelTrigger.addEventListener("click", () => travelFileInput.click());
  travelFileInput.addEventListener("change", () => {
    travelFileDisplay.textContent = travelFileInput.files.length > 0 ? travelFileInput.files[0].name : "No file chosen";
  });
});
</script>
