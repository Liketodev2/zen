<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Superannuation Income Stream</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help" />
  </div>

  <p class="choosing-business-type-text">
    Complete this section if you receive money from your super fund on a regular basis (not just a one-time lump sum payment).
  </p>

  <div class="grin_box_border mb-4">
    <p class="choosing-business-type-text">
      “Where can I find the details?” Your super fund should provide a summary with the numbers below.
    </p>

    <div id="superStreamContainer">
      @php
        $superItems = old('superannuation', isset($incomes) ? $incomes->superannuation ?? [] : []);
        $count = max(count($superItems), 1);
      @endphp

      @for ($i = 0; $i < $count; $i++)
      <section class="super-block" data-index="{{ $i }}">
        <!-- Payment period start date -->
        <p class="choosing-business-type-text">Payment period start date</p>
        <div class="row">
          <div class="col-md-4 mb-3">
            <select name="superannuation[{{ $i }}][payment_start_day]" class="form-control border-dark">
              <option value="">Day</option>
              @for ($d = 1; $d <= 31; $d++)
                <option value="{{ $d }}" {{ old("superannuation.$i.payment_start_day", $superItems[$i]['payment_start_day'] ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
              @endfor
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <select name="superannuation[{{ $i }}][payment_start_month]" class="form-control border-dark">
              <option value="">Month</option>
              @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ old("superannuation.$i.payment_start_month", $superItems[$i]['payment_start_month'] ?? '') == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
              @endfor
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <select name="superannuation[{{ $i }}][payment_start_year]" class="form-control border-dark">
              <option value="">Year</option>
              @for ($y = date('Y'); $y >= 1990; $y--)
                <option value="{{ $y }}" {{ old("superannuation.$i.payment_start_year", $superItems[$i]['payment_start_year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
              @endfor
            </select>
          </div>
        </div>

        <!-- Payment period end date -->
        <p class="choosing-business-type-text">Payment period end date</p>
        <div class="row">
          <div class="col-md-4 mb-3">
            <select name="superannuation[{{ $i }}][payment_end_day]" class="form-control border-dark">
              <option value="">Day</option>
              @for ($d = 1; $d <= 31; $d++)
                <option value="{{ $d }}" {{ old("superannuation.$i.payment_end_day", $superItems[$i]['payment_end_day'] ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
              @endfor
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <select name="superannuation[{{ $i }}][payment_end_month]" class="form-control border-dark">
              <option value="">Month</option>
              @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ old("superannuation.$i.payment_end_month", $superItems[$i]['payment_end_month'] ?? '') == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
              @endfor
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <select name="superannuation[{{ $i }}][payment_end_year]" class="form-control border-dark">
              <option value="">Year</option>
              @for ($y = date('Y'); $y >= 1990; $y--)
                <option value="{{ $y }}" {{ old("superannuation.$i.payment_end_year", $superItems[$i]['payment_end_year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
              @endfor
            </select>
          </div>
        </div>

        <!-- Tax fields -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="choosing-business-type-text">Tax withheld</label>
            <input type="text" name="superannuation[{{ $i }}][tax_withheld]" class="form-control border-dark" placeholder="00.00$" value="{{ old("superannuation.$i.tax_withheld", $superItems[$i]['tax_withheld'] ?? '') }}" />
          </div>
          <div class="col-md-6 mb-3">
            <label class="choosing-business-type-text">Taxable component - Taxed element</label>
            <input type="text" name="superannuation[{{ $i }}][taxable_taxed]" class="form-control border-dark" placeholder="00.00$" value="{{ old("superannuation.$i.taxable_taxed", $superItems[$i]['taxable_taxed'] ?? '') }}" />
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="choosing-business-type-text">Taxable component - Untaxed element</label>
            <input type="text" name="superannuation[{{ $i }}][taxable_untaxed]" class="form-control border-dark" placeholder="00.00$" value="{{ old("superannuation.$i.taxable_untaxed", $superItems[$i]['taxable_untaxed'] ?? '') }}" />
          </div>
          <div class="col-md-6 mb-3">
            <label class="choosing-business-type-text">Tax offset</label>
            <input type="text" name="superannuation[{{ $i }}][tax_offset]" class="form-control border-dark" placeholder="00.00$" value="{{ old("superannuation.$i.tax_offset", $superItems[$i]['tax_offset'] ?? '') }}" />
          </div>
        </div>

        <!-- Under 60 question -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <p class="choosing-business-type-text">
              Are you under 60 years of age and a death benefits dependent, where the deceased died at 60 years or over?
            </p>

            @php
              $under60 = old("superannuation.$i.under60_dependent", $superItems[$i]['under60_dependent'] ?? '');
            @endphp

            <div class="form-check form-check-inline">
              <input class="form-check-input custom-radio under60-radio" type="radio" name="superannuation[{{ $i }}][under60_dependent]" id="under60Yes_{{ $i }}" value="yes" {{ $under60 === 'yes' ? 'checked' : '' }}>
              <label class="form-check-label custom-label" for="under60Yes_{{ $i }}">Yes</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input custom-radio under60-radio" type="radio" name="superannuation[{{ $i }}][under60_dependent]" id="under60No_{{ $i }}" value="no" {{ $under60 === 'no' ? 'checked' : '' }}>
              <label class="form-check-label custom-label" for="under60No_{{ $i }}">No</label>
            </div>
          </div>
        </div>
      </section>
      @endfor
    </div>
  </div>

  <!-- Add/Delete buttons -->
  <div class="row mb-4">
    <div class="col-md-6 mb-3">
      <button type="button" class="btn btn_add" id="btnAddSuper">
        <img src="{{ asset('img/icons/plus.png') }}" alt="plus" />
        Add another payment
      </button>
      <button type="button" class="btn btn_delete" id="btnDeleteSuper">
        Delete last payment
      </button>
    </div>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const container = document.getElementById("superStreamContainer");
  const addBtn = document.getElementById("btnAddSuper");
  const delBtn = document.getElementById("btnDeleteSuper");

  function initUnder60(section, index) {
    const yes = section.querySelector(`#under60Yes_${index}`);
    const no = section.querySelector(`#under60No_${index}`);
    const details = section.querySelector(`#under60Details_${index}`); // если есть

    if (!yes || !no) return;

    function toggle() {
      if(details) details.style.display = yes.checked ? 'block' : 'none';
    }

    yes.addEventListener('change', toggle);
    no.addEventListener('change', toggle);
    toggle();
  }

  function refreshIndices() {
    const blocks = container.querySelectorAll(".super-block");
    blocks.forEach((block, index) => {
      block.dataset.index = index;

      block.querySelectorAll('input, select').forEach(el => {
        if(el.name) el.name = el.name.replace(/superannuation\[\d+\]/, `superannuation[${index}]`);
        if(el.id && el.classList.contains('under60-radio')) el.id = el.id.replace(/\d+$/, index);

        // Обновляем label для радио
        if(el.classList.contains('under60-radio')){
          const label = block.querySelector(`label[for^="${el.id.split('_')[0]}"]`);
          if(label) label.setAttribute('for', el.id);
        }
      });

      initUnder60(block, index);
    });
  }

  addBtn.addEventListener("click", function () {
    const blocks = container.querySelectorAll(".super-block");
    const last = blocks[blocks.length - 1];
    const clone = last.cloneNode(true);

    clone.querySelectorAll("input").forEach(i => {
      if(i.type === "radio" || i.type === "checkbox") i.checked = false;
      else i.value = '';
    });
    clone.querySelectorAll("select").forEach(s => s.selectedIndex = 0);
    clone.querySelectorAll(".under60-details").forEach(d => d.style.display = 'none');

    container.appendChild(clone);
    refreshIndices();
  });

  delBtn.addEventListener("click", function () {
    const blocks = container.querySelectorAll(".super-block");
    if (blocks.length > 1) {
      blocks[blocks.length - 1].remove();
      refreshIndices();
    }
  });

  refreshIndices();
});

</script>
