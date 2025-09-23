<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Australian Annuities</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <p class="choosing-business-type-text">
        Complete this section if you receive an Australian annuity from your super fund or life insurance company.
    </p>

    <div class="grin_box_border mb-4">
        <div class="row">
            <p class="choosing-business-type-text">
                “Where can I find the details?” Your super fund or life insurance company should provide a summary with the numbers below.
            </p>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Total tax withheld from Australian Annuities</label>
                <input type="text" name="annuities[annuity_tax_withheld]" class="form-control border-dark" placeholder="00.00$" value="{{ old('annuities.annuity_tax_withheld', $incomes->annuities['annuity_tax_withheld'] ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Total payments received from Australian Annuities</label>
                <input type="text" name="annuities[annuity_total_received]" class="form-control border-dark" placeholder="00.00$" value="{{ old('annuities.annuity_total_received', $incomes->annuities['annuity_total_received'] ?? '') }}">
            </div>
        </div>
    </div>
</section>
