<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Home Office Occupancy Costs (If you ONLY work from your home office)</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <p class="choosing-business-type-text">
    Complete this section if you ONLY work from a home office and your employer does not provide any office for you.
  </p>

  @php
    $office = old('office_occupancy', $deductions->office_occupancy ?? []);
  @endphp

  <div class="grin_box_border p-3 mb-3">
    <div class="mb-3">
      <label class="choosing-business-type-text">Does your employer provide an office or work area for you?</label>
      <div>
        <div class="form-check form-check-inline">
          <input class="form-check-input custom-radio" type="radio" name="office_occupancy[employer_office]" id="employerOfficeYes" value="yes" {{ ($office['employer_office'] ?? '') === 'yes' ? 'checked' : '' }}>
          <label class="form-check-label custom-label" for="employerOfficeYes">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input custom-radio" type="radio" name="office_occupancy[employer_office]" id="employerOfficeNo" value="no" {{ ($office['employer_office'] ?? '') === 'no' ? 'checked' : '' }}>
          <label class="form-check-label custom-label" for="employerOfficeNo">No</label>
        </div>
      </div>
    </div>

    <div id="secondQuestion" class="mb-3 {{ ($office['employer_office'] ?? '') === 'yes' ? '' : 'd-none' }}">
      <label class="choosing-business-type-text">Do you have a dedicated home office space that is considered to be your “place of business”?</label>
      <div>
        <div class="form-check form-check-inline">
          <input class="form-check-input custom-radio" type="radio" name="office_occupancy[dedicated_office]" id="dedicatedOfficeYes" value="yes" {{ ($office['dedicated_office'] ?? '') === 'yes' ? 'checked' : '' }}>
          <label class="form-check-label custom-label" for="dedicatedOfficeYes">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input custom-radio" type="radio" name="office_occupancy[dedicated_office]" id="dedicatedOfficeNo" value="no" {{ ($office['dedicated_office'] ?? '') === 'no' ? 'checked' : '' }}>
          <label class="form-check-label custom-label" for="dedicatedOfficeNo">No</label>
        </div>
      </div>
    </div>

    <div id="dedicatedDetails" class="{{ ($office['dedicated_office'] ?? '') === 'yes' ? '' : 'd-none' }}">
      <div class="mb-3">
        <label class="choosing-business-type-text">
          Please list the details and amounts of any of these expenses for the tax year: <br>
          <small class="text-muted">(Rent Paid or Mortgage interest, Council rates, Land tax, House insurance)</small><br>
          Adjust the total amount to reflect your share of the expense only.
        </label>
        <textarea class="form-control border-dark" name="office_occupancy[office_expenses]" rows="4" placeholder="Describe the expenses...">{{ $office['office_expenses'] ?? '' }}</textarea>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="choosing-business-type-text">What is the total area of the inside of your home? (in square metres)</label>
          <input type="number" class="form-control border-dark" name="office_occupancy[total_home_area]" placeholder="..." value="{{ $office['total_home_area'] ?? '' }}">
        </div>
        <div class="col-md-6">
          <label class="choosing-business-type-text">What is the total area of your home office or work area? (in square metres)</label>
          <input type="number" class="form-control border-dark" name="office_occupancy[office_area]" placeholder="..." value="{{ $office['office_area'] ?? '' }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="choosing-business-type-text">Did your employer already pay or reimburse you for any of the items above?</label>
        <div>
          <div class="form-check form-check-inline">
            <input class="form-check-input custom-radio" type="radio" name="office_occupancy[employer_reimbursed]" id="employerReimbursedYes" value="yes" {{ ($office['employer_reimbursed'] ?? '') === 'yes' ? 'checked' : '' }}>
            <label class="form-check-label custom-label" for="employerReimbursedYes">Yes</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input custom-radio" type="radio" name="office_occupancy[employer_reimbursed]" id="employerReimbursedNo" value="no" {{ ($office['employer_reimbursed'] ?? '') === 'no' ? 'checked' : '' }}>
            <label class="form-check-label custom-label" for="employerReimbursedNo">No</label>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const firstYes = document.getElementById("employerOfficeYes");
    const firstNo = document.getElementById("employerOfficeNo");
    const secondBlock = document.getElementById("secondQuestion");

    const secondYes = document.getElementById("dedicatedOfficeYes");
    const secondNo = document.getElementById("dedicatedOfficeNo");
    const detailsBlock = document.getElementById("dedicatedDetails");

    function handleFirstQuestion() {
      if (firstYes.checked) {
        secondBlock.classList.remove("d-none");
      } else {
        secondBlock.classList.add("d-none");
        detailsBlock.classList.add("d-none");
      }
    }

    function handleSecondQuestion() {
      if (secondYes.checked) {
        detailsBlock.classList.remove("d-none");
      } else {
        detailsBlock.classList.add("d-none");
      }
    }

    firstYes.addEventListener("change", handleFirstQuestion);
    firstNo.addEventListener("change", handleFirstQuestion);
    secondYes.addEventListener("change", handleSecondQuestion);
    secondNo.addEventListener("change", handleSecondQuestion);
  });
</script>
