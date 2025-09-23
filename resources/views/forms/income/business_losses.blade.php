<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Deferred Non-commercial Business Losses</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help" />
  </div>

  <div class="grin_box_border mb-4">
    <div class="row">
      <!-- Partnership: investing -->
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">
          Your share of deferred losses from partnership activities - from carrying on a business of investing
        </p>
        <input type="number" step="0.01" name="business_losses[partnership_investing]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_losses.partnership_investing', $incomes->business_losses['partnership_investing'] ?? '') }}" />
      </div>

      <!-- Partnership: rental property -->
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">
          Your share of deferred losses from partnership activities - from carrying on a rental property business
        </p>
        <input type="number" step="0.01" name="business_losses[partnership_rental]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_losses.partnership_rental', $incomes->business_losses['partnership_rental'] ?? '') }}" />
      </div>

      <!-- Partnership: other -->
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">
          Your share of deferred losses from partnership activities - other
        </p>
        <input type="number" step="0.01" name="business_losses[partnership_other]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_losses.partnership_other', $incomes->business_losses['partnership_other'] ?? '') }}" />
      </div>

      <!-- Partnership: total -->
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">
          Your share of deferred losses from partnership activities
        </p>
        <input type="number" step="0.01" name="business_losses[partnership_total]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_losses.partnership_total', $incomes->business_losses['partnership_total'] ?? '') }}" />
      </div>

      <!-- Sole trader: investing -->
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">
          Your share of deferred losses from sole trader activities - from carrying on a business of investing
        </p>
        <input type="number" step="0.01" name="business_losses[sole_investing]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_losses.sole_investing', $incomes->business_losses['sole_investing'] ?? '') }}" />
      </div>

      <!-- Sole trader: rental property -->
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">
          Your share of deferred losses from sole trader activities - from carrying on a rental property business
        </p>
        <input type="number" step="0.01" name="business_losses[sole_rental]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_losses.sole_rental', $incomes->business_losses['sole_rental'] ?? '') }}" />
      </div>

      <!-- Sole trader: other -->
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">
          Your share of deferred losses from sole trader activities - other
        </p>
        <input type="number" step="0.01" name="business_losses[sole_other]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_losses.sole_other', $incomes->business_losses['sole_other'] ?? '') }}" />
      </div>
    </div>

    <div class="row">
      <!-- Sole trader: total -->
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">
          Deferred losses from sole trader activities
        </p>
        <input type="number" step="0.01" name="business_losses[sole_total]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_losses.sole_total', $incomes->business_losses['sole_total'] ?? '') }}" />
      </div>
    </div>

    <div class="row">
      <!-- Primary production -->
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">Primary production deferred losses</p>
        <input type="number" step="0.01" name="business_losses[primary_production]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_losses.primary_production', $incomes->business_losses['primary_production'] ?? '') }}" />
      </div>

      <!-- Non-primary production -->
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">Non-primary production deferred losses</p>
        <input type="number" step="0.01" name="business_losses[non_primary_production]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_losses.non_primary_production', $incomes->business_losses['non_primary_production'] ?? '') }}" />
      </div>
    </div>
  </div>
</section>
