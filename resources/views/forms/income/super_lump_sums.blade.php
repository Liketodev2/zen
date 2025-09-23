<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Super Lump Sums</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">Number of Lump Sum Payments you received?</label>
        <input type="number" name="super_lump_sums[lump_sum_count]" class="form-control border-dark"
               value="{{ old('super_lump_sums.lump_sum_count', $incomes->super_lump_sums['lump_sum_count'] ?? '') }}"
               placeholder="1">
    </div>

    @php
        $superLumpSums = old('super_lump_sums', $incomes->super_lump_sums ?? [ [] ]);
        $numericItems = array_filter($superLumpSums, fn($key) => is_int($key), ARRAY_FILTER_USE_KEY);
        $count = max(count($numericItems), 1);
    @endphp

    <div id="super-lump-container">
        @foreach(array_values($numericItems) as $i => $lump)
        <div class="grin_box_border mb-4 super-lump-block" data-index="{{ $i }}">
            <div class="row">
                <div class="col-12 mb-2">
                    <p class="choosing-business-type-text">Date of payment</p>
                </div>
                <div class="col-md-4 mb-3">
                    <select name="super_lump_sums[{{ $i }}][lump_day]" class="form-control border-dark">
                        <option value="">Day</option>
                        @for ($d = 1; $d <= 31; $d++)
                            <option value="{{ $d }}" @selected(($lump['lump_day'] ?? '') == $d)>{{ $d }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <select name="super_lump_sums[{{ $i }}][lump_month]" class="form-control border-dark">
                        <option value="">Month</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected(($lump['lump_month'] ?? '') == $m)>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <select name="super_lump_sums[{{ $i }}][lump_year]" class="form-control border-dark">
                        <option value="">Year</option>
                        @for ($y = date('Y'); $y >= 1990; $y--)
                            <option value="{{ $y }}" @selected(($lump['lump_year'] ?? '') == $y)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="choosing-business-type-text">Tax withheld</label>
                    <input type="text" name="super_lump_sums[{{ $i }}][tax_withheld]" class="form-control border-dark"
                           value="{{ $lump['tax_withheld'] ?? '' }}" placeholder="00.00$">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="choosing-business-type-text">Taxable Component - Taxed element</label>
                    <input type="text" name="super_lump_sums[{{ $i }}][taxed_component]" class="form-control border-dark"
                           value="{{ $lump['taxed_component'] ?? '' }}" placeholder="00.00$">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="choosing-business-type-text">Taxable Component - Untaxed element</label>
                    <input type="text" name="super_lump_sums[{{ $i }}][untaxed_component]" class="form-control border-dark"
                           value="{{ $lump['untaxed_component'] ?? '' }}" placeholder="00.00$">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="choosing-business-type-text">Australian Superannuation lump sum payments tax-free component</label>
                    <input type="text" name="super_lump_sums[{{ $i }}][tax_free_component]" class="form-control border-dark"
                           value="{{ $lump['tax_free_component'] ?? '' }}" placeholder="00.00$">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <p class="choosing-business-type-text">Is this a death benefit payment?</p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio"
                               type="radio"
                               name="super_lump_sums[{{ $i }}][is_death_benefit]"
                               value="yes"
                               id="deathYes{{ $i }}"
                               @checked(($lump['is_death_benefit'] ?? '') === 'yes')>
                        <label class="form-check-label custom-label" for="deathYes{{ $i }}">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio"
                               type="radio"
                               name="super_lump_sums[{{ $i }}][is_death_benefit]"
                               value="no"
                               id="deathNo{{ $i }}"
                               @checked(($lump['is_death_benefit'] ?? '') === 'no')>
                        <label class="form-check-label custom-label" for="deathNo{{ $i }}">No</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="choosing-business-type-text">Payer's ABN</label>
                    <input type="text" name="super_lump_sums[{{ $i }}][payer_abn]" class="form-control border-dark"
                           value="{{ $lump['payer_abn'] ?? '' }}" placeholder="11 685 404 406">
                </div>
            </div>

            <button type="button" class="btn btn_delete remove-super-lump">Delete</button>
        </div>
        @endforeach
    </div>

    <button type="button" id="add-super-lump" class="btn btn_add btn_add_employer">Add another payment</button>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('super-lump-container');
    const addBtn = document.getElementById('add-super-lump');

    function updateIndexes() {
        container.querySelectorAll('.super-lump-block').forEach((block, i) => {
            block.dataset.index = i;
            block.querySelectorAll('input, select').forEach(el => {
                if (el.name) el.name = el.name.replace(/\[\d+\]/, `[${i}]`);
                if (el.id) el.id = el.id.replace(/\d+$/, i);
            });
        });
    }

    addBtn.addEventListener('click', function () {
        const blocks = container.querySelectorAll('.super-lump-block');
        const last = blocks[blocks.length - 1];
        const clone = last.cloneNode(true);

        clone.querySelectorAll('input, select').forEach(el => {
            if (el.type === 'radio') el.checked = false;
            else el.value = '';
        });

        container.appendChild(clone);
        updateIndexes();
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-super-lump')) {
            const blocks = container.querySelectorAll('.super-lump-block');
            if (blocks.length > 1) {
                e.target.closest('.super-lump-block').remove();
                updateIndexes();
            } else {
                alert('You must have at least one payment block.');
            }
        }
    });
});
</script>
