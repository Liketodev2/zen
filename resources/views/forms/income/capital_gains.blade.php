<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Capital Gains or Losses</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    @php
        $capitalGains = old('capital_gains', isset($incomes) ? $incomes->capital_gains ?? [] : []);
    @endphp

    <div class="row mb-3">
        <p class="choosing-business-type-text">
            <strong>Includes selling or exchanging assets such as property, shares and cryptocurrency</strong>
        </p>
        <div class="grin_box_border mb-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <p class="choosing-business-type-text">Have you applied an exemption or rollover?</p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio" type="radio" name="capital_gains[exemption_applied]" id="exemptionYes" value="yes"
                            {{ isset($capitalGains['exemption_applied']) && $capitalGains['exemption_applied'] === 'yes' ? 'checked' : '' }}>
                        <label class="form-check-label custom-label" for="exemptionYes">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio" type="radio" name="capital_gains[exemption_applied]" id="exemptionNo" value="no"
                            {{ isset($capitalGains['exemption_applied']) && $capitalGains['exemption_applied'] === 'no' ? 'checked' : '' }}>
                        <label class="form-check-label custom-label" for="exemptionNo">No</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <p class="choosing-business-type-text">Exemption or rollover code</p>
                <div class="col-md-6 mb-3">
                    <select class="form-control border-dark" id="rolloverCode" name="capital_gains[rollover_code]">
                        <option value="">Select an option</option>
                        @php
                            $rolloverOptions = [
                                '152-C' => 'Small business active asset reduction (Subdivision 152-C)',
                                '152-D' => 'Small business retirement exemption (Subdivision 152-D)',
                                '152-E' => 'Small business roll-over (Subdivision 152-E)',
                                '152-B' => 'Small business 15 year exemption (Subdivision 152-B)',
                                '855' => 'Foreign resident CGT exemption (Division 855)',
                                '124-M' => 'Scrip for scrip roll-over (Subdivision 124-M)',
                                '118-B' => 'Main residence exemption (Subdivision 118-B)',
                                'pre-cgt' => 'Capital gains disregarded as a result of the sale of a pre-CGT asset',
                                '122' => 'Disposal or creation of assets in a wholly-owned company (Division 122)',
                                '124' => 'Replacement asset roll-overs (Division 124)',
                                '124-F' => 'Exchange of rights or options (Subdivision 124-F)',
                                '615-company' => 'Exchange of shares in one company for shares in another company (Division 615)',
                                '615-unit' => 'Exchange of units in a unit trust for shares in a company (Division 615)',
                                '125-B' => 'Demerger roll-over (Subdivision 125-B)',
                                '126' => 'Same asset roll-overs (Division 126)',
                                '328-G' => 'Small business restructure roll-over (Subdivision 328-G)',
                                '360-A' => 'Early stage investor (Subdivision 360-A)',
                                '118-F' => 'Venture capital investment (Subdivision 118-F)',
                                'other' => 'Other exemptions and rollovers',
                            ];
                        @endphp
                        @foreach($rolloverOptions as $value => $label)
                            <option value="{{ $value }}"
                                {{ isset($capitalGains['rollover_code']) && $capitalGains['rollover_code'] === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <input type="text" name="capital_gains[rollover_other_detail]" id="rolloverOtherInput"
                        class="form-control border-dark" placeholder="Specify other exemption"
                        value="{{ $capitalGains['rollover_other_detail'] ?? '' }}"
                        {{ (isset($capitalGains['rollover_code']) && $capitalGains['rollover_code'] === 'other') ? '' : 'disabled' }}>
                </div>
                <div class="col-md-6 mb-3">
                    <p class="choosing-business-type-text">Credit for foreign resident capital gains withholding amounts</p>
                    <input type="number" step="0.01" name="capital_gains[foreign_credit]"
                        class="form-control border-dark" placeholder="00.00$"
                        value="{{ $capitalGains['foreign_credit'] ?? '' }}">
                </div>
            </div>
        </div>

        <p class="choosing-business-type-text">
            <strong>Capital Gains or Losses Excluding Managed Funds</strong>
        </p>
        <div class="grin_box_border mb-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <p class="choosing-business-type-text">Total Capital Gain eligible for discount</p>
                    <input type="number" step="0.01" name="capital_gains[gain_discount_eligible]"
                        class="form-control border-dark" placeholder="00.00$"
                        value="{{ $capitalGains['gain_discount_eligible'] ?? '' }}">
                </div>

                <div class="col-md-6 mb-3">
                    <p class="choosing-business-type-text">Total Capital Gain not eligible for discount</p>
                    <input type="number" step="0.01" name="capital_gains[gain_not_discounted]"
                        class="form-control border-dark" placeholder="00.00$"
                        value="{{ $capitalGains['gain_not_discounted'] ?? '' }}">
                </div>

                <div class="col-md-6 mb-3">
                    <p class="choosing-business-type-text">Total current year Capital Losses</p>
                    <input type="number" step="0.01" name="capital_gains[current_year_losses]"
                        class="form-control border-dark" placeholder="00.00$"
                        value="{{ $capitalGains['current_year_losses'] ?? '' }}">
                </div>

                <div class="col-md-6 mb-3">
                    <p class="choosing-business-type-text">Total prior year Capital Losses</p>
                    <input type="number" step="0.01" name="capital_gains[prior_year_losses]"
                        class="form-control border-dark" placeholder="00.00$"
                        value="{{ $capitalGains['prior_year_losses'] ?? '' }}">
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Do you need to use a schedule?</p>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio" type="radio" name="capital_gains[use_schedule]" id="scheduleYes" value="yes"
                        {{ isset($capitalGains['use_schedule']) && $capitalGains['use_schedule'] === 'yes' ? 'checked' : '' }}>
                    <label class="form-check-label custom-label" for="scheduleYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio" type="radio" name="capital_gains[use_schedule]" id="scheduleNo" value="no"
                        {{ isset($capitalGains['use_schedule']) && $capitalGains['use_schedule'] === 'no' ? 'checked' : '' }}>
                    <label class="form-check-label custom-label" for="scheduleNo">No</label>
                </div>
            </div>

            <div class="col-12 mb-3 {{ isset($capitalGains['use_schedule']) && $capitalGains['use_schedule'] === 'yes' ? '' : 'd-none' }}" id="scheduleExtraBlock">
                <!-- Schedule fields here (abbreviated for brevity) -->
                <div class="row">
                    <p class="choosing-business-type-text">Shares in companies listed on an Australian securities exchange</p>
                    <div class="col-md-6 mb-3">
                        <input type="number" name="capital_gains[cg_listed_shares_gain]"
                            class="form-control border-dark" placeholder="Capital gain"
                            value="{{ $capitalGains['cg_listed_shares_gain'] ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="number" name="capital_gains[cg_listed_shares_loss]"
                            class="form-control border-dark" placeholder="Capital loss"
                            value="{{ $capitalGains['cg_listed_shares_loss'] ?? '' }}">
                    </div>
                </div>
                <!-- Other schedule fields would follow the same pattern -->
            </div>
        </div>
        <div class="row align-items-end">
            <p class="choosing-business-type-text">
                Attach Managed Fund statements here (optional)
            </p>
            <div class="col-md-6 mb-3">
                <input type="file" name="capital_gains[cgt_attachment]" id="capitalFundInput" class="d-none">

                <button type="button" class="btn btn_add" id="capitalFundTrigger">
                    <img src="{{ asset('img/icons/plus.png') }}" alt="plus">
                    Choose file
                </button>
                <p class="text-muted mt-1 mb-0">
                    Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
                </p>
            </div>
            <div class="col-md-6 mb-3">
                <p id="managedFundName" class="choosing-business-type-text text-muted mb-0">
                     @if(!empty($capitalGains['cgt_attachment']))
                            <a href="{{ Storage::disk('s3')->url($capitalGains['cgt_attachment']) }}" target="_blank" class="btn btn-outline-success">
                                <i class="fa-solid fa-file"></i> View file
                            </a>
                    @else
                        No file chosen
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Rollover code handling
    const select = document.getElementById("rolloverCode");
    const otherInput = document.getElementById("rolloverOtherInput");

    function updateRolloverInput() {
        if (select.value === "other") {
            otherInput.disabled = false;
            otherInput.focus();
        } else {
            otherInput.disabled = true;
            otherInput.value = "";
        }
    }

    select.addEventListener("change", updateRolloverInput);
    updateRolloverInput(); // Initialize

    // Schedule section handling
    const scheduleRadios = document.querySelectorAll("input[name='capital_gains[use_schedule]']");
    const scheduleBlock = document.getElementById("scheduleExtraBlock");

    function updateScheduleVisibility() {
        const scheduleYes = document.querySelector("input[name='capital_gains[use_schedule]'][value='yes']");
        scheduleBlock.classList.toggle("d-none", !scheduleYes.checked);
    }

    scheduleRadios.forEach(radio => {
        radio.addEventListener("change", updateScheduleVisibility);
    });
    updateScheduleVisibility(); // Initialize

    // File input handling
    const capitalFundInput = document.getElementById("capitalFundInput");
    const capitalFundTrigger = document.getElementById("capitalFundTrigger");
    const managedFundName = document.getElementById("managedFundName");

    // Trigger hidden input
    capitalFundTrigger.addEventListener("click", () => capitalFundInput.click());

    // Display selected file name
    capitalFundInput.addEventListener("change", () => {
        const files = Array.from(capitalFundInput.files).map(f => f.name).join(", ");
        managedFundName.textContent = files || "No file chosen";
    });
});
</script>
