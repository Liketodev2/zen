<section>
    @php
        $zoneOffset = [];
        if (isset($others->zone_overseas_forces_offset)) {
            // Ensure it's an array
            $zoneOffset = is_string($others->zone_overseas_forces_offset)
                ? json_decode($others->zone_overseas_forces_offset, true)
                : $others->zone_overseas_forces_offset;

            // Remove extra quotes from keys
            $zoneOffset = collect($zoneOffset)->mapWithKeys(function($value, $key) {
                $cleanKey = trim($key, "'\""); // removes both single & double quotes
                return [$cleanKey => $value];
            })->toArray();
        }
    @endphp

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Zone Or Overseas Forces</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

        <div class="grin_box_border mb-5">
    <div class="col-md-6 mb-3">
      <p class="choosing-business-type-text">Zone or overseas forces tax offset</p>
      <input type="number" step="0.01" name="zone_overseas_forces_offset['zone_offset']" class="form-control border-dark" placeholder="00.00$"
             value="{{ old('zone_overseas_forces_offset.zone_offset', $zoneOffset['zone_offset'] ?? '') }}">
    </div>

    <div class="mb-3">
      <p class="choosing-business-type-text">Did you live in this area for more than 182 days?</p>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio" type="radio" name="zone_overseas_forces_offset['zone_lived_182']" value="yes" id="zone_yes"
            {{ old('zone_overseas_forces_offset.zone_lived_182', $zoneOffset['zone_lived_182'] ?? '') === 'yes' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="zone_yes">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio" type="radio" name="zone_overseas_forces_offset['zone_lived_182']" value="no" id="zone_no"
         {{ (old('zone_overseas_forces_offset.zone_lived_182', $zoneOffset['zone_lived_182'] ?? '') == 'no') ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="zone_no">No</label>
      </div>
    </div>
</div>
</section>
