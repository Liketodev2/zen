<section id="mobilePhoneForm">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Mobile Phone</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <div id="mobileExpenseContainer">
    @php
      $mobileExpenses = old('mobile_phone.expenses', $deductions->mobile_phone['expenses'] ?? []);
      $mobileCount = count($mobileExpenses) > 0 ? count($mobileExpenses) : 1;
    @endphp

    @for($i = 0; $i < $mobileCount; $i++)
    <div class="grin_box_border mb-3 mobile-expense-block" data-index="{{ $i }}">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Why do you use your mobile for work?</label>
          <input type="text" class="form-control border-dark reason" 
                 name="mobile_phone[expenses][{{ $i }}][reason]" 
                 value="{{ $mobileExpenses[$i]['reason'] ?? '' }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">What % of your mobile calls are related to your work?</label>
          <input type="text" class="form-control border-dark percentage" 
                 name="mobile_phone[expenses][{{ $i }}][percentage]" 
                 value="{{ $mobileExpenses[$i]['percentage'] ?? '' }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Total of your mobile bills for the year</label>
          <input type="number" step="0.01" class="form-control border-dark mobile-total" 
                 name="mobile_phone[expenses][{{ $i }}][total]" 
                 value="{{ $mobileExpenses[$i]['total'] ?? '' }}">
        </div>
      </div>
      <div class="mb-2">
        <button type="button" class="btn btn_delete deleteMobileExpense">Delete</button>
      </div>
    </div>
    @endfor
  </div>

  <div class="mb-3">
    <button type="button" class="btn btn_add" id="addMobileExpense">
      <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another mobile expense
    </button>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const container = document.getElementById("mobileExpenseContainer");
  const addBtn = document.getElementById("addMobileExpense");

  const blockTemplate = `
    <div class="grin_box_border mb-3 mobile-expense-block" data-index="__INDEX__">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Why do you use your mobile for work?</label>
          <input type="text" class="form-control border-dark reason" name="mobile_phone[expenses][__INDEX__][reason]" value="">
        </div>
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">What % of your mobile calls are related to your work?</label>
          <input type="text" class="form-control border-dark percentage" name="mobile_phone[expenses][__INDEX__][percentage]" value="">
        </div>
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Total of your mobile bills for the year</label>
          <input type="number" step="0.01" class="form-control border-dark mobile-total" name="mobile_phone[expenses][__INDEX__][total]" value="">
        </div>
      </div>
      <div class="mb-2">
        <button type="button" class="btn btn_delete deleteMobileExpense">Delete</button>
      </div>
    </div>
  `;

  function refreshIndices() {
    const blocks = container.querySelectorAll(".mobile-expense-block");
    blocks.forEach((block, index) => {
      block.dataset.index = index;
      block.querySelectorAll('input').forEach(input => {
        input.name = input.name.replace(/\d+/, index);
      });
    });
  }

  function attachDeleteButtons() {
    container.querySelectorAll(".deleteMobileExpense").forEach(btn => {
      btn.onclick = () => {
        const blocks = container.querySelectorAll(".mobile-expense-block");
        if (blocks.length > 1) {
          btn.closest(".mobile-expense-block").remove();
          refreshIndices();
        } else {
          alert("You must have at least one mobile expense.");
        }
      };
    });
  }

  addBtn.addEventListener("click", () => {
    const newIndex = container.querySelectorAll(".mobile-expense-block").length;
    const newBlockHTML = blockTemplate.replace(/__INDEX__/g, newIndex);
    container.insertAdjacentHTML('beforeend', newBlockHTML);
    refreshIndices();
    attachDeleteButtons();
  });

  attachDeleteButtons();
});
</script>
