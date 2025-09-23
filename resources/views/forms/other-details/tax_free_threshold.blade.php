@php
    // Existing data from DB (if any)
    $partYear = $others->part_year_tax_free_threshold ?? [];

    // Merge with old input so form keeps submitted values if validation fails
    $partYear = array_merge($partYear, old('part_year_tax_free_threshold', []));
@endphp

<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Part-year Tax-free Threshold</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>
    <div class="grin_box_border p-3 mt-3">
        <div class="row">
            <p class="choosing-business-type-text">Part-year tax-free threshold date</p>

            <div class="col-md-4 mb-3">
                <select name="part_year_tax_free_threshold[spouse_birth_day]" class="form-control border-dark">
                    <option value="">Day</option>
                    @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}" {{ (isset($partYear['spouse_birth_day']) && $partYear['spouse_birth_day'] == $i) ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <select name="part_year_tax_free_threshold[spouse_birth_month]" class="form-control border-dark">
                    <option value="">Month</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ (isset($partYear['spouse_birth_month']) && $partYear['spouse_birth_month'] == $i) ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <select name="part_year_tax_free_threshold[spouse_birth_year]" class="form-control border-dark">
                    <option value="">Year</option>
                    @for ($i = date('Y'); $i >= 1900; $i--)
                        <option value="{{ $i }}" {{ (isset($partYear['spouse_birth_year']) && $partYear['spouse_birth_year'] == $i) ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="part_year_months" class="choosing-business-type-text d-block mb-2">
                Number of months eligible for part-year tax-free threshold
            </label>
            <select class="form-select border-dark" name="part_year_tax_free_threshold[part_year_months]" id="part_year_months">
                <option value="">Choose</option>
                @for ($i = 1; $i <= 11; $i++)
                    <option value="{{ $i }}" {{ (isset($partYear['part_year_months']) && $partYear['part_year_months'] == $i) ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>
    </div>
</section>
