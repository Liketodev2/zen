<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Medicare Levy Surcharge (MLS) - Compulsory Question</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <div class="grin_box_border mb-5">
        {{-- Step 1: Private Hospital Cover --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <p class="choosing-business-type-text">
                For the whole period from 1 July <?= date('Y') - 1 ?> to 30 June <?= date('Y') ?>, did you and all your dependants (including your spouse if you had one) have private hospital cover?
                </p>

                @php
                    $privateCover = old('mls.private_hospital_cover', $others->mls['private_hospital_cover'] ?? null);
                @endphp

                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio" type="radio" name="mls[private_hospital_cover]" id="privateHospitalYes" value="yes" {{ $privateCover === 'yes' ? 'checked' : '' }}>
                    <label class="form-check-label custom-label" for="privateHospitalYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio" type="radio" name="mls[private_hospital_cover]" id="privateHospitalNo" value="no" {{ $privateCover === 'no' ? 'checked' : '' }}>
                    <label class="form-check-label custom-label" for="privateHospitalNo">No</label>
                </div>
            </div>
        </div>

        {{-- Step 2: Low Income --}}
        @php
            $lowIncome = old('mls.low_income', $others->mls['low_income'] ?? null);
        @endphp
        <div id="step2" class="row mb-3 {{ $privateCover === 'no' ? '' : 'd-none' }}">
            <div class="col-md-6">
                <p class="choosing-business-type-text">
                    Was your income below $97,000.00 OR your family income below $194,000.00 (plus $1,500 per child excluding the first)?
                </p>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio" type="radio" name="mls[low_income]" id="lowIncomeYes" value="yes" {{ $lowIncome === 'yes' ? 'checked' : '' }}>
                    <label class="form-check-label custom-label" for="lowIncomeYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio" type="radio" name="mls[low_income]" id="lowIncomeNo" value="no" {{ $lowIncome === 'no' ? 'checked' : '' }}>
                    <label class="form-check-label custom-label" for="lowIncomeNo">No</label>
                </div>
            </div>
        </div>

        {{-- Step 3: Partial Cover --}}
        @php
            $partialCover = old('mls.partial_cover', $others->mls['partial_cover'] ?? null);
        @endphp
        <div id="step3" class="row mb-3 {{ $lowIncome === 'no' ? '' : 'd-none' }}">
            <div class="col-md-6">
                <p class="choosing-business-type-text">
                    Did you and your dependants have private hospital cover for part of the year?
                </p>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio" type="radio" name="mls[partial_cover]" id="partialCoverYes" value="yes" {{ $partialCover === 'yes' ? 'checked' : '' }}>
                    <label class="form-check-label custom-label" for="partialCoverYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio" type="radio" name="mls[partial_cover]" id="partialCoverNo" value="no" {{ $partialCover === 'no' ? 'checked' : '' }}>
                    <label class="form-check-label custom-label" for="partialCoverNo">No</label>
                </div>
            </div>
        </div>

        {{-- Non-Liable Days --}}
        @php
            $nonLiableDays = old('mls.non_liable_days', $others->mls['non_liable_days'] ?? 0);
        @endphp
        <div id="notLiableDays" class="row mb-3 {{ $partialCover === 'yes' ? '' : 'd-none' }}">
            <div class="col-md-6">
                <label for="nonLiableDays" class="form-label">Number of days not liable for surcharge</label>
                <input type="number" class="form-control border-dark" id="nonLiableDays" name="mls[non_liable_days]" min="0" value="{{ $nonLiableDays }}">
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const coverRadios = document.getElementsByName("mls[private_hospital_cover]");
        const incomeRadios = document.getElementsByName("mls[low_income]");
        const partialRadios = document.getElementsByName("mls[partial_cover]");

        const step2 = document.getElementById("step2");
        const step3 = document.getElementById("step3");
        const notLiableDays = document.getElementById("notLiableDays");

        coverRadios.forEach(radio => {
            radio.addEventListener("change", () => {
                if (radio.value === "no") {
                    step2.classList.remove("d-none");
                } else {
                    step2.classList.add("d-none");
                    step3.classList.add("d-none");
                    notLiableDays.classList.add("d-none");
                }
            });
        });

        incomeRadios.forEach(radio => {
            radio.addEventListener("change", () => {
                if (radio.value === "no") {
                    step3.classList.remove("d-none");
                } else {
                    step3.classList.add("d-none");
                    notLiableDays.classList.add("d-none");
                }
            });
        });

        partialRadios.forEach(radio => {
            radio.addEventListener("change", () => {
                if (radio.value === "yes") {
                    notLiableDays.classList.remove("d-none");
                } else {
                    notLiableDays.classList.add("d-none");
                }
            });
        });
    });
</script>
