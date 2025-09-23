<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Interest Deductions</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

    <p class="choosing-business-type-text">
        You need to complete this section if you have any deductions related to interest income that you have earned.
    </p>
  <div class="grin_box_border p-3 mb-3">
    <p class="choosing-business-type-text">
        E.g. Account keeping fees or interest charged on money borrowed for purchase of shares.​
    </p>
    <div class="col-md-6 mb-3">
      <label class="choosing-business-type-text">Interest deduction description</label>
      <input type="text" class="form-control border-dark" name="interest_deduction[interest_description]" value="{{ old('interest_deduction.interest_description', $deductions->interest_deduction['interest_description'] ?? '') }}" placeholder="...">

    </div>

    <div class="col-md-6 mb-3">
      <label class="choosing-business-type-text">Total amount you paid</label>
      <input type="text" class="form-control border-dark" name="interest_deduction[interest_amount]" value="{{ old('interest_deduction.interest_amount', $deductions->interest_deduction['interest_amount'] ?? '') }}" placeholder="00.00$">

    </div>
  </div>
</section>
