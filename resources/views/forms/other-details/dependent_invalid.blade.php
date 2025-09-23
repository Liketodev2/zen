@php
    $dependentInvalid = $others->dependent_invalid_and_carer ?? [];
@endphp

<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Dependent (invalid and carer)</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>
    <div class="grin_box_border p-3 mb-4">
        <div class="col-md-6 mb-3">
        <label for="invalid_carer_offset" class="choosing-business-type-text mb-2">
            Invalid and invalid carer tax offset
        </label>
        <input type="number" step="0.01" class="form-control border-dark" id="invalid_carer_offset" placeholder="00.00$"
               name="dependent_invalid_and_carer[invalid_carer_offset]" value="{{ $dependentInvalid['invalid_carer_offset'] ?? '' }}">
    </div>
    </div>
</section>
