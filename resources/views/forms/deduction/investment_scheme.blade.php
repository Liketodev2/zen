<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Forestry Managed Investment Scheme Deduction</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <div class="grin_box_border">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="choosing-business-type-text" for="investment_scheme_amount">
                Forestry managed investment scheme deduction
            </label>
            <input
                type="number"
                step="0.01"
                class="form-control border-dark"
                id="investment_scheme_amount"
                name="investment_scheme[investment_scheme_amount]"
                placeholder="00.00$"
                value="{{ old('investment_scheme.investment_scheme_amount', $deductions->investment_scheme['investment_scheme_amount'] ?? '') }}"
            />
        </div>
    </div>
  </div>
</section>
