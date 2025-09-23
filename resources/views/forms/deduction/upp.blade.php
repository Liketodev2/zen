<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Deductible Amount Of Undeducted Purchase Price Of Foreign Pension Or Annuity</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <div class="grin_box_border mb-3">
    <div class="col-md-6">
        <label class="choosing-business-type-text" for="upp_amount">Deductible amount of UPP of foreign pension or annuity</label>
        <input
        type="number"
        step="0.01"
        class="form-control border-dark"
        id="upp_amount"
        name="upp[upp_amount]"
        value="{{ old('upp.upp_amount', $deductions->upp['upp_amount'] ?? '') }}"
        placeholder="00.00$"
        />
    </div>
  </div>
</section>
