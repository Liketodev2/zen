<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Low Value Pool Deduction</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <div class="grin_box_border p-3 mb-3">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          Low value pool deductions relating to financial investments
        </label>
        <input type="text" class="form-control border-dark" name="low_value_pool[lvp_financial]" value="{{ old('low_value_pool.lvp_financial', $deductions->low_value_pool['lvp_financial'] ?? '') }}" placeholder="00.00$">
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          Low value pool deductions relating to rental properties
        </label>
        <input type="text" class="form-control border-dark" name="low_value_pool[lvp_rental]" value="{{ old('low_value_pool.lvp_rental', $deductions->low_value_pool['lvp_rental'] ?? '') }}" placeholder="00.00$">
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          Other low value pool deductions
        </label>
        <input type="text" class="form-control border-dark" name="low_value_pool[lvp_other]"value="{{ old('low_value_pool.lvp_other', $deductions->low_value_pool['lvp_other'] ?? '') }}"  placeholder="00.00$">
      </div>
    </div>
  </div>
</section>
