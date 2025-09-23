<section id="internetAccessForm">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Internet Access</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <p class="choosing-business-type-text">
    If you use your internet connection for work purposes, then you can claim part of the cost as a tax deduction.
  </p>

  <div class="grin_box_border mb-3">
    <div class="row">
      <p class="choosing-business-type-text">
        <strong>Important:</strong> To claim internet, you must have:
      </p>
      <p class="choosing-business-type-text ms-3">
        A copy of your internet bills AND a record that represents your typical work-related internet use for a continuous 4-week period (e.g. diary entries, timesheets, logbook)
      </p>
      <p class="choosing-business-type-text">
        <strong>OR</strong>
      </p>
      <p class="choosing-business-type-text ms-3">
        A record of the number of hours that you worked at home each day during the year (e.g. diary entries, timesheets, logbook).
      </p>

      @php
        $internet = old('internet_access', $deductions->internet_access ?? []);
      @endphp

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">Why do you use internet for work?</label>
        <input type="text" 
               class="form-control border-dark" 
               name="internet_access[reason]" 
               placeholder="..." 
               value="{{ $internet['reason'] ?? '' }}">
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">What % of your internet use is directly related to your work?</label>
        <input type="text" 
               class="form-control border-dark" 
               name="internet_access[percentage]" 
               placeholder="0%" 
               value="{{ $internet['percentage'] ?? '' }}">
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">Total of your internet charges for the year</label>
        <input type="number" 
               step="0.01" 
               class="form-control border-dark" 
               name="internet_access[total]" 
               placeholder="00.00$" 
               value="{{ $internet['total'] ?? '' }}">
      </div>

    </div>
  </div>
</section>
