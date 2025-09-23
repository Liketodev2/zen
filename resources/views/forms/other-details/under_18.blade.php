@php
    $under18 = $others->under_18 ?? [];
    $under18Code = $under18['under_18_code'] ?? '';
@endphp

<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Under 18</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>
    <div class="grin_box_border p-3 mb-4">

    <h5 class="choosing-business-type-text mb-3">Under 18</h5>

    <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text mb-2" for="under_18_code">
            Under 18 action code
        </label>
        <select name="under_18[under_18_code]"  id="under_18_code" class="form-control">
            <option value="">Choose</option>
            <option value="A: Excepted person under 18 years" {{ $under18Code ==='A: Excepted person under 18 years' ? 'selected' : '' }}>
                A: Excepted person under 18 years
            </option>
            <option value="M: Person under 18 years at 30 June <?= date('Y') ?>" 
    {{ $under18Code === 'M: Person under 18 years at 30 June ' . date('Y') ? 'selected' : '' }}>
    M: Person under 18 years at 30 June <?= date('Y') ?>
</option>

        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text mb-2" for="under_18_amount">
            Under 18
        </label>
        <input type="number" step="0.01" class="form-control" id="under_18_amount" placeholder="00.00$"
               name="under_18[under_18_amount]" value="{{ $under18['under_18_amount'] ?? '' }}">
    </div>

</div>

</section>
