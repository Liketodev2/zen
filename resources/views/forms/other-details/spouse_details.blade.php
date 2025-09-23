<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Spouse Details</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <p class="choosing-business-type-text">
    If you had a spouse during <?= date('Y') - 1 ?>-<?= date('Y') ?>, the ATO requires these details.
    </p>
    <div class="grin_box_border mb-5">
        <p class="choosing-business-type-text mb-4">‘Spouse’ includes another person (same sex or opposite sex) who:</p>
        <p class="choosing-business-type-text">is married to you - OR -</p>
        <p class="choosing-business-type-text mb-3">lived with you on a genuine domestic basis in a relationship as a couple.</p>

        {{-- First & Middle Name --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <p class="choosing-business-type-text">Spouse's first name</p>
                <input type="text" name="spouse_details[spouse_first_name]" class="form-control border-dark" placeholder="Name"
                       value="{{ old('spouse_details.spouse_first_name', $others->spouse_details['spouse_first_name'] ?? '') }}">
            </div>
            <div class="col-md-6">
                <p class="choosing-business-type-text">Spouse's middle names</p>
                <input type="text" name="spouse_details[spouse_middle_names]" class="form-control border-dark" placeholder="Middle names"
                       value="{{ old('spouse_details.spouse_middle_names', $others->spouse_details['spouse_middle_names'] ?? '') }}">
            </div>
        </div>

        {{-- Family Name --}}
        <div class="col-md-6 mb-3">
            <p class="choosing-business-type-text">Spouse's family name</p>
            <input type="text" name="spouse_details[spouse_family_name]" class="form-control border-dark" placeholder="Family name"
                   value="{{ old('spouse_details.spouse_family_name', $others->spouse_details['spouse_family_name'] ?? '') }}">
        </div>

        {{-- Date of Birth --}}
        <div class="row">
            <p class="choosing-business-type-text">Spouse's date of birth</p>
            <div class="col-md-4 mb-3">
                <select name="spouse_details[spouse_birth_day]" class="form-control border-dark">
                    <option value="">Day</option>
                    @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}" {{ old('spouse_details.spouse_birth_day', $others->spouse_details['spouse_birth_day'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="spouse_details[spouse_birth_month]" class="form-control border-dark">
                    <option value="">Month</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('spouse_details.spouse_birth_month', $others->spouse_details['spouse_birth_month'] ?? '') == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="spouse_details[spouse_birth_year]" class="form-control border-dark">
                    <option value="">Year</option>
                    @for ($i = date('Y'); $i >= 1900; $i--)
                        <option value="{{ $i }}" {{ old('spouse_details.spouse_birth_year', $others->spouse_details['spouse_birth_year'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        {{-- Gender --}}
        <div class="mb-3">
            <p class="choosing-business-type-text">Spouse's gender</p>
            @php
                $gender = old('spouse_details.spouse_gender', $others->spouse_details['spouse_gender'] ?? '');
            @endphp
            <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" type="radio" name="spouse_details[spouse_gender]" value="male" id="gender_male" {{ $gender == 'male' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="gender_male">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" type="radio" name="spouse_details[spouse_gender]" value="female" id="gender_female" {{ $gender == 'female' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="gender_female">Female</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" type="radio" name="spouse_details[spouse_gender]" value="indeterminate" id="gender_other" {{ $gender == 'indeterminate' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="gender_other">Indeterminate</label>
            </div>
        </div>

        {{-- Had spouse full year --}}
        <div class="mb-3">
            <p class="choosing-business-type-text">
            Did you have a spouse for the full year 1 July <?= date('Y') - 1 ?> to 30 June <?= date('Y') ?>?
            </p>
            @php
                $fullYear = old('spouse_details.had_spouse_full_year', $others->spouse_details['had_spouse_full_year'] ?? '');
            @endphp
            <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" type="radio" name="spouse_details[had_spouse_full_year]" value="yes" id="spouse_yes" {{ $fullYear == 'yes' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="spouse_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" type="radio" name="spouse_details[had_spouse_full_year]" value="no" id="spouse_no" {{ $fullYear == 'no' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="spouse_no">No</label>
            </div>
        </div>

        {{-- Date ranges --}}
        <div id="spouse_dates_container" class="row mb-3 {{ $fullYear == 'no' ? '' : 'd-none' }}">
            {{-- From date --}}
            <p class="choosing-business-type-text">Date had a spouse from</p>
            <div class="col-md-4 mb-3">
                <select name="spouse_details[spouse_from_day]" class="form-control border-dark">
                    <option value="">Day</option>
                    @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}" {{ old('spouse_details.spouse_from_day', $others->spouse_details['spouse_from_day'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="spouse_details[spouse_from_month]" class="form-control border-dark">
                    <option value="">Month</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('spouse_details.spouse_from_month', $others->spouse_details['spouse_from_month'] ?? '') == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="spouse_details[spouse_from_year]" class="form-control border-dark">
                    <option value="">Year</option>
                    @for ($i = date('Y'); $i >= 1990; $i--)
                        <option value="{{ $i }}" {{ old('spouse_details.spouse_from_year', $others->spouse_details['spouse_from_year'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            {{-- To date --}}
            <p class="choosing-business-type-text">Date had a spouse to</p>
            <div class="col-md-4 mb-3">
                <select name="spouse_details[spouse_to_day]" class="form-control border-dark">
                    <option value="">Day</option>
                    @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}" {{ old('spouse_details.spouse_to_day', $others->spouse_details['spouse_to_day'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="spouse_details[spouse_to_month]" class="form-control border-dark">
                    <option value="">Month</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('spouse_details.spouse_to_month', $others->spouse_details['spouse_to_month'] ?? '') == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="spouse_details[spouse_to_year]" class="form-control border-dark">
                    <option value="">Year</option>
                    @for ($i = date('Y'); $i >= 1990; $i--)
                        <option value="{{ $i }}" {{ old('spouse_details.spouse_to_year', $others->spouse_details['spouse_to_year'] ?? '') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        {{-- Taxable Income --}}
        <div class="col-md-6 mb-3">
            <p class="choosing-business-type-text">Spouse's taxable income</p>
            <input type="text" name="spouse_details[spouse_taxable_income]" class="form-control border-dark" placeholder="00.00$"
                   value="{{ old('spouse_details.spouse_taxable_income', $others->spouse_details['spouse_taxable_income'] ?? '') }}">
            <small class="text-muted">If you're not sure of the exact figure, an estimate is fine</small>
        </div>

        {{-- Additional income --}}
        <div class="mb-3">
            <p class="choosing-business-type-text">Did your spouse receive any additional income during the financial year?</p>
            @php
                $addIncome = old('spouse_details.spouse_additional_income', $others->spouse_details['spouse_additional_income'] ?? '');
            @endphp
            <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" type="radio" name="spouse_details[spouse_additional_income]" value="yes" id="additional_income_yes" {{ $addIncome == 'yes' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="additional_income_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" type="radio" name="spouse_details[spouse_additional_income]" value="no" id="additional_income_no" {{ $addIncome == 'no' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="additional_income_no">No</label>
            </div>
        </div>

        {{-- Additional income fields --}}
        <div id="additional_income_fields" class="{{ $addIncome == 'no' ? '' : 'd-none' }}">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="choosing-business-type-text">Fringe benefit tax (FBT) paid from FBT exempt employers</p>
                    <input type="text" name="spouse_details[fbt_exempt]" class="form-control border-dark" placeholder="00.00$"
                           value="{{ old('spouse_details.fbt_exempt', $others->spouse_details['fbt_exempt'] ?? '') }}">
                </div>
                <div class="col-md-6">
                    <p class="choosing-business-type-text">Fringe benefit tax (FBT) paid from Non FBT exempt employers</p>
                    <input type="text" name="spouse_details[fbt_non_exempt]" class="form-control border-dark" placeholder="00.00$"
                           value="{{ old('spouse_details.fbt_non_exempt', $others->spouse_details['fbt_non_exempt'] ?? '') }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="choosing-business-type-text">Reportable superannuation contributions</p>
                    <input type="text" name="spouse_details[reportable_super]" class="form-control border-dark" placeholder="00.00$"
                           value="{{ old('spouse_details.reportable_super', $others->spouse_details['reportable_super'] ?? '') }}">
                </div>
                <div class="col-md-6">
                    <p class="choosing-business-type-text">Total Australian Government Pensions and Allowances (includes Centrelink)</p>
                    <input type="text" name="spouse_details[govt_pensions]" class="form-control border-dark" placeholder="00.00$"
                           value="{{ old('spouse_details.govt_pensions', $others->spouse_details['govt_pensions'] ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const spouseNo = document.getElementById("spouse_no");
        const spouseYes = document.getElementById("spouse_yes");
        const spouseDatesContainer = document.getElementById("spouse_dates_container");

        const incomeNo = document.getElementById("additional_income_no");
        const incomeYes = document.getElementById("additional_income_yes");
        const additionalIncomeFields = document.getElementById("additional_income_fields");

        if (spouseNo) spouseNo.addEventListener("change", () => spouseDatesContainer.classList.remove("d-none"));
        if (spouseYes) spouseYes.addEventListener("change", () => spouseDatesContainer.classList.add("d-none"));

        if (incomeNo) incomeNo.addEventListener("change", () => additionalIncomeFields.classList.remove("d-none"));
        if (incomeYes) incomeYes.addEventListener("change", () => additionalIncomeFields.classList.add("d-none"));
    });
</script>
