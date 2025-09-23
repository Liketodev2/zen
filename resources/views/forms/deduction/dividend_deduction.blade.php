<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Dividend Deductions</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>
<p class="choosing-business-type-text">
    You need to complete this section if you have any deductions related to dividend income that you have earned.
</p>
  <div class="grin_box_border p-3 mb-3">
    <p class="choosing-business-type-text mb-3">E.g. Account keeping or managing fees</p>
    <div class="col-md-6 mb-3">
      <label class="choosing-business-type-text">Dividend deduction description</label>
      <input type="text" class="form-control border-dark" name="dividend_deduction[dividend_description]" value="{{ old('dividend_deduction.dividend_description', $deductions->dividend_deduction['dividend_description'] ?? '') }}" placeholder="...">

    </div>

    <div class="col-md-6 mb-3">
      <label class="choosing-business-type-text">Total amount you paid</label>
      <input type="text" class="form-control border-dark" name="dividend_deduction[dividend_amount]" value="{{ old('dividend_deduction.dividend_amount', $deductions->dividend_deduction['dividend_amount'] ?? '') }}" placeholder="00.00$">

    </div>
  </div>
</section>
