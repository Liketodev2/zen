@php
    $streamOffset = $others->superannuation_income_stream_offset ?? [];
@endphp
<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Australian Superannuation Income Stream</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>
    <div class="grin_box_border p-3 mb-5">

    <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text mb-2" for="superannuation_income">
            Australian superannuation income stream
        </label>
        <input type="number" step="0.01" class="form-control" id="superannuation_income"
               name="superannuation_income_stream_offset[superannuation_income]" value="{{ $streamOffset['superannuation_income'] ?? '' }}"
               placeholder="00.00$">
    </div>

</div>
</section>
