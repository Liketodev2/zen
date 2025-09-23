<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Personal Services Income</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <p class="choosing-business-type-text">
        Complete this section if you received any Personal Services Income (PSI) during the year.
    </p>

    <div class="grin_box_border mb-4">
        <div class="row">
            <p class="choosing-business-type-text">
                “Where can I find the details?” Your payer should provide a summary with the numbers below.
            </p>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">PSI tax withheld - voluntary agreement</label>
                <input type="number" step="0.01" name="personal_services[psi_voluntary_agreement]" class="form-control border-dark" placeholder="00.00$" value="{{ old('personal_services.psi_voluntary_agreement', $incomes->personal_services['psi_voluntary_agreement'] ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">PSI tax withheld where ABN not quoted</label>
                <input type="number" step="0.01" name="personal_services[psi_abn_not_quoted]" class="form-control border-dark" placeholder="00.00$" value="{{ old('personal_services.psi_abn_not_quoted', $incomes->personal_services['psi_abn_not_quoted'] ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">PSI tax withheld - labour hire or other specified payments</label>
                <input type="number" step="0.01" name="personal_services[psi_labour_hire]" class="form-control border-dark" placeholder="00.00$" value="{{ old('personal_services.psi_labour_hire', $incomes->personal_services['psi_labour_hire'] ?? '') }}">
            </div>
        </div>
    </div>
</section>
