<section>
  <!-- Work-related Uniform, Occupation Specific or Protective Clothing -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Work-related Uniform, Occupation Specific or Protective Clothing</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <div id="uniformContainer">
    <p class="choosing-business-type-text">
      Includes the purchase of work uniforms with logos, or protective clothing. You cannot claim ordinary clothing, business suits, etc.
    </p>

    @php
      $uniformItems = old('uniforms.items', isset($deductions->uniforms['items']) ? $deductions->uniforms['items'] : []);
      $uniformCount = count($uniformItems) > 0 ? count($uniformItems) : 1;
    @endphp

    @for($i = 0; $i < $uniformCount; $i++)
    <div class="grin_box_border mb-3 uniform-block">
      <div class="mb-3">
        <label class="choosing-business-type-text">Do you have a receipt for every uniform item you are claiming below?</label><br>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input custom-radio"
            type="radio"
            name="uniforms[items][{{ $i }}][has_receipt]"
            id="has_receipt_{{ $i }}_yes"
            value="1"
            {{ old("uniforms.items.$i.has_receipt", $uniformItems[$i]['has_receipt'] ?? '') === '1' ? 'checked' : '' }}
          >
          <label class="form-check-label custom-label" for="has_receipt_{{ $i }}_yes">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            class="form-check-input custom-radio"
            type="radio"
            name="uniforms[items][{{ $i }}][has_receipt]"
            id="has_receipt_{{ $i }}_no"
            value="0"
            {{ old("uniforms.items.$i.has_receipt", $uniformItems[$i]['has_receipt'] ?? '') === '0' ? 'checked' : '' }}
          >
          <label class="form-check-label custom-label" for="has_receipt_{{ $i }}_no">No</label>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Type of clothing you bought</label>
          <select class="form-control border-dark uniform-type" name="uniforms[items][{{ $i }}][type]">
            <option value="" disabled {{ !isset($uniformItems[$i]['type']) ? 'selected' : '' }}>Choose</option>
            <option value="Shirts (with logo)" {{ old("uniforms.items.$i.type", $uniformItems[$i]['type'] ?? '') === 'Shirts (with logo)' ? 'selected' : '' }}>Shirts (with logo)</option>
            <option value="Shirts (protective/hi-vis)" {{ old("uniforms.items.$i.type", $uniformItems[$i]['type'] ?? '') === 'Shirts (protective/hi-vis)' ? 'selected' : '' }}>Shirts (protective/hi-vis)</option>
            <option value="Pants (with logo)" {{ old("uniforms.items.$i.type", $uniformItems[$i]['type'] ?? '') === 'Pants (with logo)' ? 'selected' : '' }}>Pants (with logo)</option>
            <option value="Pants (protective/hi-vis)" {{ old("uniforms.items.$i.type", $uniformItems[$i]['type'] ?? '') === 'Pants (protective/hi-vis)' ? 'selected' : '' }}>Pants (protective/hi-vis)</option>
            <option value="Protective/Safety boots" {{ old("uniforms.items.$i.type", $uniformItems[$i]['type'] ?? '') === 'Protective/Safety boots' ? 'selected' : '' }}>Protective/Safety boots</option>
            <option value="Non slip shoes" {{ old("uniforms.items.$i.type", $uniformItems[$i]['type'] ?? '') === 'Non slip shoes' ? 'selected' : '' }}>Non slip shoes</option>
            <option value="Checked Chef pants" {{ old("uniforms.items.$i.type", $uniformItems[$i]['type'] ?? '') === 'Checked Chef pants' ? 'selected' : '' }}>Checked Chef pants</option>
            <option value="Hi-Vis vest" {{ old("uniforms.items.$i.type", $uniformItems[$i]['type'] ?? '') === 'Hi-Vis vest' ? 'selected' : '' }}>Hi-Vis vest</option>
            <option value="Other" {{ old("uniforms.items.$i.type", $uniformItems[$i]['type'] ?? '') === 'Other' ? 'selected' : '' }}>Other (please specify)</option>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">If 'Other', please specify</label>
          <input
            type="text"
            class="form-control border-dark other-uniform"
            name="uniforms[items][{{ $i }}][other_type]"
            placeholder="Please enter the type of uniform you are claiming"
            value="{{ old("uniforms.items.$i.other_type", $uniformItems[$i]['other_type'] ?? '') }}"
            {{ old("uniforms.items.$i.type", $uniformItems[$i]['type'] ?? '') === 'Other' ? '' : 'disabled' }}
          >
        </div>
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Total amount you paid for this item</label>
          <input
            type="number"
            step="0.01"
            class="form-control border-dark uniform-amount"
            name="uniforms[items][{{ $i }}][amount]"
            placeholder="00.00$"
            value="{{ old("uniforms.items.$i.amount", $uniformItems[$i]['amount'] ?? '') }}"
          >
        </div>
      </div>
    </div>
    @endfor
  </div>

  <div class="mb-3">
    <button type="button" class="btn btn_add" id="addUniformBtn">
      <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another uniform item
    </button>
  </div>

  <div class="col-12 mt-4 mb-3">
    <label class="choosing-business-type-text">Attach a simple breakdown of your expenses (optional)</label>
    <input type="file" name="uniforms[uniform_receipt]" id="uniformFileInput" class="d-none" /><br>
    <button type="button" class="btn btn_add" id="triggerUniformFile">
      <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Choose file
    </button>
      <p class="text-muted mt-1 mb-0">
          Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
      </p>
    <p id="uniformSelectedFile" class="choosing-business-type-text text-muted mt-2 mb-0">
      @if(!empty($deductions->attach['uniforms']['uniform_receipt']))
        <a href="{{ Storage::disk('s3')->url($deductions->attach['uniforms']['uniform_receipt']) }}" target="_blank" class="btn btn-outline-success">
          <i class="fa-solid fa-file"></i>
          View file
        </a>
      @else
        No file chosen
      @endif
    </p>
  </div>

  <!-- Laundry Expenses -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Laundry Expenses</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <p class="choosing-business-type-text">
    If you entered any work uniform above that you regularly wash, you can also usually claim the cost of laundering those items up to $150 per year.
  </p>

  <div class="grin_box_border mt-4">
    <div class="row mb-3">
      <p class="choosing-business-type-text mb-3">
        Please complete the fields below and we will calculate your laundry claim for you.
      </p>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">Please select whether you wash your uniform separately or mixed with other non-work clothes</label>
        <select class="form-control border-dark" name="uniforms[laundry][laundry_type]">
          <option value="" disabled {{ !isset($deductions->uniforms['laundry']['laundry_type']) ? 'selected' : '' }}>Choose</option>
          <option value="Separate Wash" {{ old('uniforms.laundry.laundry_type', $deductions->uniforms['laundry']['laundry_type'] ?? '') === 'Separate Wash' ? 'selected' : '' }}>Separate Wash</option>
          <option value="Mixed Wash" {{ old('uniforms.laundry.laundry_type', $deductions->uniforms['laundry']['laundry_type'] ?? '') === 'Mixed Wash' ? 'selected' : '' }}>Mixed Wash</option>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text mt-0 mt-md-4">How many loads of laundry you do per week</label>
        <input
          type="number"
          step="1"
          class="form-control border-dark"
          name="uniforms[laundry][laundry_loads]"
          placeholder="00.00$"
          value="{{ old('uniforms.laundry.laundry_loads', $deductions->uniforms['laundry']['laundry_loads'] ?? '') }}"
        >
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">How many weeks of the year did you work?</label>
        <input
          type="number"
          step="1"
          class="form-control border-dark"
          name="uniforms[laundry][weeks_worked]"
          placeholder="00.00$"
          value="{{ old('uniforms.laundry.weeks_worked', $deductions->uniforms['laundry']['weeks_worked'] ?? '') }}"
        >
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("uniformContainer");
  const addBtn = document.getElementById("addUniformBtn");
  const uniformFileInput = document.getElementById("uniformFileInput");
  const uniformTrigger = document.getElementById("triggerUniformFile");
  const uniformFileDisplay = document.getElementById("uniformSelectedFile");

  function initUniformBlock(block) {
    const typeSelect = block.querySelector(".uniform-type");
    const otherInput = block.querySelector(".other-uniform");
    const amountInput = block.querySelector(".uniform-amount");

    if (typeSelect && otherInput) {
      // Set initial state
      otherInput.disabled = typeSelect.value !== "Other";

      typeSelect.addEventListener("change", () => {
        otherInput.disabled = typeSelect.value !== "Other";
        if (otherInput.disabled) otherInput.value = "";
      });
    }

    if (amountInput) {
      amountInput.addEventListener("input", updateTotal);
    }
  }

  function updateTotal() {
    let total = 0;
    container.querySelectorAll('.uniform-amount').forEach(input => {
      const val = parseFloat(input.value);
      if (!isNaN(val)) total += val;
    });
    // You can display the total somewhere if needed
  }

  // Initialize existing blocks
  document.querySelectorAll(".uniform-block").forEach(initUniformBlock);

  addBtn.addEventListener("click", () => {
    const blocks = container.querySelectorAll(".uniform-block");
    const newIndex = blocks.length;
    const firstBlock = blocks[0];
    const clone = firstBlock.cloneNode(true);

    // Clear all values
    clone.querySelectorAll("input").forEach(input => {
      input.value = "";
      if (input.classList.contains("other-uniform")) input.disabled = true;
    });

    clone.querySelectorAll("select").forEach(select => {
      select.selectedIndex = 0;
    });

    clone.querySelectorAll('input[type="radio"]').forEach(radio => {
      radio.checked = false;
    });

    // Update names with new index
    clone.querySelectorAll('[name]').forEach(el => {
      const name = el.getAttribute('name');
      el.setAttribute('name', name.replace(/uniforms\[items\]\[\d+\]/, `uniforms[items][${newIndex}]`));
    });

    // Update IDs with new index
    clone.querySelectorAll('[id]').forEach(el => {
      const id = el.getAttribute('id');
      if (id.includes('has_receipt')) {
        const newId = id.replace(/_(\d+)_/, `_${newIndex}_`);
        el.setAttribute('id', newId);

        // Update corresponding label's for attribute
        const label = el.nextElementSibling;
        if (label && label.tagName === 'LABEL') {
          label.setAttribute('for', newId);
        }
      }
    });

    container.appendChild(clone);
    initUniformBlock(clone);
  });

  uniformTrigger.addEventListener("click", () => uniformFileInput.click());
  uniformFileInput.addEventListener("change", () => {
    uniformFileDisplay.textContent = uniformFileInput.files.length > 0
      ? uniformFileInput.files[0].name
      : "No file chosen";
  });

  // Initialize total on page load
  updateTotal();
});
</script>
