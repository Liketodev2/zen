<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Deduction For Project Pool</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <div class="grin_box_border mb-3">
    <div class="col-md-6">
    <label class="choosing-business-type-text" for="project_pool_deduction">Individual - Deduction for project pool</label>
    <input
      type="number"
      step="0.01"
      class="form-control border-dark"
      id="project_pool_deduction"
      name="project_pool[project_pool_deduction]"
      value="{{ old('project_pool.project_pool_deduction', $deductions->project_pool['project_pool_deduction'] ?? '') }}"
      placeholder="00.00$"
    />
    </div>
  </div>
</section>
