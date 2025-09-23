<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Personal Superannuation Contributions</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <p class="choosing-business-type-text">
    Complete this section if you made personal contributions to your superannuation fund(s).
  </p>

  @php
    $superannuation = old('superannuation', $deductions->superannuation ?? []);
  @endphp

  <div class="grin_box_border p-3 mb-3">
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="choosing-business-type-text">Number of funds</label>
        <select name="superannuation[number_of_funds]" class="form-control border-dark">
          <option value="">Choose</option>
          <option value="1" {{ ($superannuation['number_of_funds'] ?? '') == 1 ? 'selected' : '' }}>1</option>
          <option value="2" {{ ($superannuation['number_of_funds'] ?? '') == 2 ? 'selected' : '' }}>2</option>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label class="choosing-business-type-text d-block mb-2">
        I confirm that I advised my fund(s) that I will claim this tax deduction, and I received confirmation from the fund(s):
      </label>
      <div>
        <div class="form-check form-check-inline">
          <input class="form-check-input custom-radio" type="radio" name="superannuation[confirmation]" id="confirmation_yes" value="yes" {{ ($superannuation['confirmation'] ?? '') === 'yes' ? 'checked' : '' }}>
          <label class="form-check-label custom-label" for="confirmation_yes">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input custom-radio" type="radio" name="superannuation[confirmation]" id="confirmation_no" value="no" {{ ($superannuation['confirmation'] ?? '') === 'no' ? 'checked' : '' }}>
          <label class="form-check-label custom-label" for="confirmation_no">No</label>
        </div>
      </div>
    </div>

    <div id="superannuationDetails" class="{{ ($superannuation['confirmation'] ?? '') === 'yes' ? '' : 'd-none' }}">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Full name of fund</label>
          <input type="text" name="superannuation[fund_name]" class="form-control border-dark" placeholder="Name" value="{{ $superannuation['fund_name'] ?? '' }}">
        </div>

        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Account Number</label>
          <input type="text" name="superannuation[account_number]" class="form-control border-dark" placeholder="0" value="{{ $superannuation['account_number'] ?? '' }}">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Amount of Deduction Claimed</label>
          <input type="text" name="superannuation[deduction_amount]" class="form-control border-dark" placeholder="00.00$" value="{{ $superannuation['deduction_amount'] ?? '' }}">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="choosing-business-type-text">Super Fund's ABN</label>
          <input type="text" name="superannuation[super_fund_abn]" class="form-control border-dark" placeholder="Name" value="{{ $superannuation['super_fund_abn'] ?? '' }}">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="choosing-business-type-text">Day</label>
          <select name="superannuation[day]" class="form-control border-dark">
            <option value="">Day</option>
            @for ($i = 1; $i <= 31; $i++)
              <option value="{{ $i }}" {{ ($superannuation['day'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
          </select>
        </div>

        <div class="col-md-4">
          <label class="choosing-business-type-text">Month</label>
          <select name="superannuation[month]" class="form-control border-dark">
            <option value="">Month</option>
            @for ($i = 1; $i <= 12; $i++)
              <option value="{{ $i }}" {{ ($superannuation['month'] ?? '') == $i ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
            @endfor
          </select>
        </div>

        <div class="col-md-4">
          <label class="choosing-business-type-text">Year</label>
          <select name="superannuation[year]" class="form-control border-dark">
            <option value="">Year</option>
            @for ($i = date('Y'); $i >= 1990; $i--)
              <option value="{{ $i }}" {{ ($superannuation['year'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
          </select>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const yes = document.getElementById("confirmation_yes");
    const no = document.getElementById("confirmation_no");
    const detailsBlock = document.getElementById("superannuationDetails");

    function toggleDetails() {
      if (yes.checked) {
        detailsBlock.classList.remove("d-none");
      } else {
        detailsBlock.classList.add("d-none");
      }
    }

    yes.addEventListener("change", toggleDetails);
    no.addEventListener("change", toggleDetails);
  });
</script>
