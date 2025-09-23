<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Net Income Or Loss From Business</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <p class="choosing-business-type-text">
        Complete this section if you have net income or loss from your business activities.
    </p>

    <div class="grin_box_border mb-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Net Income Or Loss From Business</label>
                <input type="number" step="0.01" name="business_income[amount]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_income.amount', $incomes->business_income['amount'] ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Profit or Loss</label>
                <select name="business_income[type]" class="form-control border-dark">
                    <option value="">Choose</option>
                    <option value="profit" {{ old('business_income.type', $incomes->business_income['type'] ?? '') === 'profit' ? 'selected' : '' }}>Profit</option>
                    <option value="loss" {{ old('business_income.type', $incomes->business_income['type'] ?? '') === 'loss' ? 'selected' : '' }}>Loss</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Net income or loss from carrying on a rental property business</label>
                <input type="number" step="0.01" name="business_income[rental_property]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_income.rental_property', $incomes->business_income['rental_property'] ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Profit or Loss</label>
                <select name="business_income[rental_property_type]" class="form-control border-dark">
                    <option value="">Choose</option>
                    <option value="profit" {{ old('business_income.rental_property_type', $incomes->business_income['rental_property_type'] ?? '') === 'profit' ? 'selected' : '' }}>Profit</option>
                    <option value="loss" {{ old('business_income.rental_property_type', $incomes->business_income['rental_property_type'] ?? '') === 'loss' ? 'selected' : '' }}>Loss</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Other income or loss relating to item 15</label>
                <input type="number" step="0.01" name="business_income[other_15]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_income.other_15', $incomes->business_income['other_15'] ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Profit or Loss</label>
                <select name="business_income[other_15_type]" class="form-control border-dark">
                    <option value="">Choose</option>
                    <option value="profit" {{ old('business_income.other_15_type', $incomes->business_income['other_15_type'] ?? '') === 'profit' ? 'selected' : '' }}>Profit</option>
                    <option value="loss" {{ old('business_income.other_15_type', $incomes->business_income['other_15_type'] ?? '') === 'loss' ? 'selected' : '' }}>Loss</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Net small business income</label>
                <input type="number" step="0.01" name="business_income[small_business]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_income.small_business', $incomes->business_income['small_business'] ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Net income/loss from business tax withheld - voluntary agreement</label>
                <input type="number" step="0.01" name="business_income[tax_withheld_voluntary]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_income.tax_withheld_voluntary', $incomes->business_income['tax_withheld_voluntary'] ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Net income/loss from business tax withheld where ABN not quoted</label>
                <input type="number" step="0.01" name="business_income[tax_withheld_abn]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_income.tax_withheld_abn', $incomes->business_income['tax_withheld_abn'] ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Net income/loss from business tax withheld - foreign resident withholding</label>
                <input type="number" step="0.01" name="business_income[foreign_withholding]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_income.foreign_withholding', $incomes->business_income['foreign_withholding'] ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Net income/loss from business tax withheld - labour hire or other specified payments</label>
                <input type="number" step="0.01" name="business_income[labour_hire]" class="form-control border-dark" placeholder="00.00$" value="{{ old('business_income.labour_hire', $incomes->business_income['labour_hire'] ?? '') }}">
            </div>
        </div>
    </div>
</section>
