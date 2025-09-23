<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Rent Received</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <p class="choosing-business-type-text">
        Complete this section if you earned income from a rental property, or it was available to rent during the year. If you own more than one property, please enter each one separately.
    </p>
    <p class="choosing-business-type-text">
        Please enter the FULL amount of the income and expenses related to each property below. If your ownership is 50%, please still enter the total income and expenses for the property. We will automatically calculate your share for you.
    </p>

    <div class="grin_box_border mb-4">
        @php
            $rents = old('rent', isset($incomes) ? $incomes->rent ?? [] : []);
            $numericItems = array_filter($rents, fn($key) => is_int($key), ARRAY_FILTER_USE_KEY);
            $rentCount = max(count($numericItems), 1);
        @endphp

        <div id="rentContainer">
            @for($i = 0; $i < $rentCount; $i++)
            <section class="rent-block" data-index="{{ $i }}">
                <p class="choosing-business-type-text"><strong>Rental Property {{ $i + 1 }}</strong></p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="choosing-business-type-text">Your percentage ownership of the property</label>
                        <input type="number" name="rent[{{ $i }}][ownership_percentage]" class="form-control border-dark" placeholder="50" value="{{ old("rent.$i.ownership_percentage", $rents[$i]['ownership_percentage'] ?? '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="choosing-business-type-text">Number of weeks property was rented this tax year</label>
                        <input type="number" name="rent[{{ $i }}][weeks_rented]" class="form-control border-dark" placeholder="52" value="{{ old("rent.$i.weeks_rented", $rents[$i]['weeks_rented'] ?? '') }}">
                    </div>
                </div>

                <div class="row">
                    <p class="choosing-business-type-text"><strong>Property details</strong></p>
                    <div class="col-md-6 mb-3">
                        <label class="choosing-business-type-text">Street name and number</label>
                        <input type="text" name="rent[{{ $i }}][street]" class="form-control border-dark" placeholder="123 Example Street" value="{{ old("rent.$i.street", $rents[$i]['street'] ?? '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="choosing-business-type-text">Suburb</label>
                        <input type="text" name="rent[{{ $i }}][suburb]" class="form-control border-dark" placeholder="ANYTOWN" value="{{ old("rent.$i.suburb", $rents[$i]['suburb'] ?? '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="choosing-business-type-text">State</label>
                        <input type="text" name="rent[{{ $i }}][state]" class="form-control border-dark" placeholder="NSW" value="{{ old("rent.$i.state", $rents[$i]['state'] ?? '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="choosing-business-type-text">Postcode</label>
                        <input type="text" name="rent[{{ $i }}][postcode]" class="form-control border-dark" placeholder="2000" value="{{ old("rent.$i.postcode", $rents[$i]['postcode'] ?? '') }}">
                    </div>

                    <div class="col-md-4">
                        <select name="rent[{{ $i }}][start_day]" class="form-control border-dark">
                            <option value="">Day</option>
                            @for ($d = 1; $d <= 31; $d++)
                                <option value="{{ $d }}" {{ old("rent.$i.start_day", $rents[$i]['start_day'] ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="rent[{{ $i }}][start_month]" class="form-control border-dark">
                            <option value="">Month</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old("rent.$i.start_month", $rents[$i]['start_month'] ?? '') == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="rent[{{ $i }}][start_year]" class="form-control border-dark">
                            <option value="">Year</option>
                            @for ($y = date('Y'); $y >= 1990; $y--)
                                <option value="{{ $y }}" {{ old("rent.$i.start_year", $rents[$i]['start_year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="row">
                    <p class="choosing-business-type-text"><strong>Income details</strong></p>
                    <div class="col-md-6 mb-3">
                        <label class="choosing-business-type-text">Rental income</label>
                        <input type="number" step="0.01" name="rent[{{ $i }}][rental_income]" class="form-control border-dark" placeholder="00.00$" value="{{ old("rent.$i.rental_income", $rents[$i]['rental_income'] ?? '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="choosing-business-type-text">Other rental related income</label>
                        <input type="number" step="0.01" name="rent[{{ $i }}][other_income]" class="form-control border-dark" placeholder="00.00$" value="{{ old("rent.$i.other_income", $rents[$i]['other_income'] ?? '') }}">
                    </div>
                </div>

                <div class="row">
                    <p class="choosing-business-type-text"><strong>Expense details</strong></p>
                    @php
                        $expenseFields = ['council_rates','interest_loans','agent_fees','repairs','body_corp','water_charges','capital_allowance','capital_works','advertising','gardening','insurance','land_tax','legal_fees','pest_control','stationery','travel_expenses','sundry'];
                    @endphp
                    @foreach($expenseFields as $field)
                        <div class="col-md-6 mb-3">
                            <label class="choosing-business-type-text">{{ ucwords(str_replace('_',' ',$field)) }}</label>
                            <input type="text" name="rent[{{ $i }}][{{ $field }}]" class="form-control border-dark" placeholder="00.00$" value="{{ old("rent.$i.$field", $rents[$i][$field] ?? '') }}">
                        </div>
                    @endforeach
                </div>

                <div class="row mb-3 align-items-end">
                    <div class="col-md-6 mb-3">
                        <input type="file" name="rent[{{ $i }}][rent_files][]" class="d-none rentFileInput">
                        <button type="button" class="btn btn_add triggerRentFile">
                            <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Choose file
                        </button>
                        <p class="text-muted mt-1 mb-0">
                            Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="choosing-business-type-text text-muted mb-0 rentFileName">
                            @php
                                $files = $incomes->attach['rent'][$i]['rent_files'] ?? [];
                            @endphp
                            @if(!empty($files))
                                @foreach($files as $file)
                                    <a href="{{ Storage::disk('s3')->url($file) }}" target="_blank">
                                        <i class="fa-solid fa-file"></i> View file
                                    </a>
                                @endforeach
                            @else
                                No file chosen
                            @endif
                        </p>
                    </div>
                </div>
            </section>
            @endfor
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <button type="button" class="btn btn_add" id="btnAddRent">
                <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another property
            </button>
            <button type="button" class="btn btn_delete" id="btnDeleteRent">Delete property</button>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const rentContainer = document.getElementById("rentContainer");
    const btnAdd = document.getElementById("btnAddRent");
    const btnDelete = document.getElementById("btnDeleteRent");

    const template = rentContainer.querySelector(".rent-block").outerHTML;

    function refreshIndices() {
        rentContainer.querySelectorAll(".rent-block").forEach((block, index) => {
            block.dataset.index = index;
            block.querySelectorAll("input, select").forEach(el => {
                if (el.name) el.name = el.name.replace(/rent\[\d+\]/, `rent[${index}]`);
            });
            block.querySelectorAll(".rentFileInput").forEach(input => input.value = '');
            block.querySelectorAll(".rentFileName").forEach(p => p.textContent = 'No file chosen');
        });
    }

    btnAdd.addEventListener("click", () => {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = template;
        const newBlock = tempDiv.firstElementChild;

        newBlock.querySelectorAll("input").forEach(input => {
            if (input.type !== 'file') input.value = '';
        });
        newBlock.querySelectorAll("select").forEach(select => select.selectedIndex = 0);
        newBlock.querySelectorAll(".rentFileInput").forEach(input => input.value = '');
        newBlock.querySelectorAll(".rentFileName").forEach(p => p.textContent = 'No file chosen');

        rentContainer.appendChild(newBlock);
        refreshIndices();
        attachFileTriggers();
    });

    function refreshIndices() {
        rentContainer.querySelectorAll(".rent-block").forEach((block, index) => {
            block.dataset.index = index;
            block.querySelectorAll("input, select").forEach(el => {
                if (el.name) el.name = el.name.replace(/rent\[\d+\]/, `rent[${index}]`);
            });
        });
    }

    btnDelete.addEventListener("click", () => {
        const blocks = rentContainer.querySelectorAll(".rent-block");
        if (blocks.length > 1) {
            blocks[blocks.length - 1].remove();
            refreshIndices();
        }
    });

    function attachFileTriggers() {
        rentContainer.querySelectorAll(".triggerRentFile").forEach(btn => {
            btn.onclick = () => btn.previousElementSibling.click();
        });

        rentContainer.querySelectorAll(".rentFileInput").forEach(input => {
            input.onchange = () => {
                const display = input.closest(".row").querySelector(".rentFileName");
                display.textContent = input.files.length ? input.files[0].name : "No file chosen";
            };
        });
    }

    attachFileTriggers();
});
</script>
