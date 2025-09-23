@php
    $contributionsSpouse = $others->superannuation_contributions_spouse ?? [];
@endphp
<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Superannuation Contributions On Behalf Of Your Spouse</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <div class="grin_box_border p-3 mb-4">
        <!-- Contributions paid -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="choosing-business-type-text mb-2" for="spouse_contributions_paid">
                    Contributions paid
                </label>
                <input type="number" step="0.01" class="form-control border-dark" id="spouse_contributions_paid"
                       name="superannuation_contributions_spouse[spouse_contributions_paid]"
                       value="{{ $contributionsSpouse['spouse_contributions_paid'] ?? '' }}" placeholder="00.00$">
            </div>

            <!-- Tax offset -->
            <div class="col-md-6">
                <label class="choosing-business-type-text mb-2" for="spouse_tax_offset">
                    Superannuation contributions on behalf of your spouse tax offset
                </label>
                <input type="number" step="0.01" class="form-control border-dark"
                       name="superannuation_contributions_spouse[spouse_tax_offset]"
                       value="{{ $contributionsSpouse['spouse_tax_offset'] ?? '' }}"
                       id="spouse_tax_offset" placeholder="00.00$">
            </div>
        </div>

    </div>
 </section>
