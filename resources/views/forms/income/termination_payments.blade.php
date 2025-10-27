<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Employment Termination Payments (ETP)</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>
    <p class="choosing-business-type-text">
        Please enter the details from your ETP Income Statement or PAYG statement.
    </p>
    <p class="choosing-business-type-text">
        If you can’t find the Income Statement or PAYG document for your termination or redundancy payment, please ring the employer who gave you the payment and ask for a copy. Or if that is a problem, please click the mail icon up top and add a note telling us that you received an ETP but you don’t have a statement; we will try to track down the details for you.
    </p>

    <div class="grin_box_border mb-4">
        @php
            $etps = old('termination_payments', isset($incomes) ? $incomes->termination_payments ?? [] : []);
            $numericItems = array_filter($etps, fn($key) => is_int($key), ARRAY_FILTER_USE_KEY);
            $etpCount = max(count($numericItems), 1);
        @endphp

        <div id="etpContainer">
            @for($i = 0; $i < $etpCount; $i++)
                <section class="etp-block mb-4" data-index="{{ $i }}">
                    <div class="row">
                        <p class="choosing-business-type-text">ETP Date of Payment</p>

                        <div class="col-md-4 mb-3">
                            <select name="termination_payments[{{ $i }}][day]" class="form-control border-dark">
                                <option value="">Day</option>
                                @for ($d = 1; $d <= 31; $d++)
                                    <option value="{{ $d }}" {{ old("termination_payments.$i.day", $etps[$i]['day'] ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <select name="termination_payments[{{ $i }}][month]" class="form-control border-dark">
                                <option value="">Month</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old("termination_payments.$i.month", $etps[$i]['month'] ?? '') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <select name="termination_payments[{{ $i }}][year]" class="form-control border-dark">
                                <option value="">Year</option>
                                @for ($y = date('Y'); $y >= 1990; $y--)
                                    <option value="{{ $y }}" {{ old("termination_payments.$i.year", $etps[$i]['year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Tax Withheld Amount</p>
                            <input type="number" name="termination_payments[{{ $i }}][tax_withheld]" class="form-control border-dark" placeholder="00.00$" value="{{ old("termination_payments.$i.tax_withheld", $etps[$i]['tax_withheld'] ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">ETP Taxable Component</p>
                            <input type="number" name="termination_payments[{{ $i }}][taxable_component]" class="form-control border-dark" placeholder="00.00$" value="{{ old("termination_payments.$i.taxable_component", $etps[$i]['taxable_component'] ?? '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">ETP Code</p>
                            <select name="termination_payments[{{ $i }}][code]" class="form-control border-dark">
                                <option value="">Choose</option>
                                @php
                                    $codes = [
                                        'R' => 'R: excluded life benefit termination payment',
                                        'S' => 'S: excluded life benefit termination payment part of earlier year',
                                        'O' => 'O: non-excluded life benefit',
                                        'P' => 'P: non-excluded life benefit part of earlier year',
                                        'D' => 'D: death benefit to dependant',
                                        'N' => 'N: death benefit to non-dependant',
                                        'B' => 'B: death benefit part of earlier year',
                                    ];
                                @endphp
                                @foreach($codes as $key => $label)
                                    <option value="{{ $key }}" {{ old("termination_payments.$i.code", $etps[$i]['code'] ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Payer's ABN</p>
                            <input type="text" name="termination_payments[{{ $i }}][abn]" class="form-control border-dark" placeholder="51 824 753 556" value="{{ old("termination_payments.$i.abn", $etps[$i]['abn'] ?? '') }}">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-end">
                        <div class="col-md-6 mb-3">
                            <input
                                type="file"
                                name="termination_payments[{{ $i }}][etp_files][]"
                                class="d-none etpFileInput"
                                multiple
                            >
                            <button type="button" class="btn btn_add triggerETPFile">
                                <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Choose files
                            </button>
                            <p class="text-muted mt-1 mb-0">
                                Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text text-muted mb-0 etpFileName">
                                @php
                                    $files = $incomes->attach['termination_payments'][$i]['etp_files'] ?? [];
                                @endphp
                                @if(!empty($files))
                                    @foreach($files as $file)
                                        <a href="{{ Storage::disk('s3')->url($file) }}" target="_blank">
                                            <i class="fa-solid fa-file"></i> View file
                                        </a><br>
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
            <button type="button" class="btn btn_add" id="btnAddETP">
                <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another ETP
            </button>
            <button type="button" class="btn btn_delete" id="btnDeleteETP">Delete ETP</button>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const etpContainer = document.getElementById("etpContainer");
        const btnAdd = document.getElementById("btnAddETP");
        const btnDelete = document.getElementById("btnDeleteETP");
        const template = etpContainer.querySelector(".etp-block").outerHTML;

        function refreshIndices() {
            etpContainer.querySelectorAll(".etp-block").forEach((block, index) => {
                block.dataset.index = index;
                block.querySelectorAll("input, select").forEach(el => {
                    if (el.name) {
                        el.name = el.name.replace(/termination_payments\[\d+\]/, `termination_payments[${index}]`);
                    }
                });
            });
        }

        function clearBlockValues(block) {
            block.querySelectorAll("input[type='number'], input[type='text']").forEach(input => input.value = '');
            block.querySelectorAll("select").forEach(select => select.selectedIndex = 0);
            block.querySelector(".etpFileName").textContent = 'No file chosen';
        }

        // ✅ Attach file handlers
        function attachFileTriggers(context = etpContainer) {
            context.querySelectorAll(".triggerETPFile").forEach(btn => {
                btn.onclick = () => {
                    const fileInput = btn.closest(".row").querySelector(".etpFileInput");
                    fileInput.click();
                };
            });

            context.querySelectorAll(".etpFileInput").forEach(input => {
                input.onchange = () => {
                    const display = input.closest(".row").querySelector(".etpFileName");
                    if (input.files.length > 0) {
                        const names = Array.from(input.files).map(f => f.name).join(", ");
                        display.innerHTML = `<i class="fa-solid fa-file"></i> ${names}`;
                    } else {
                        display.textContent = "No file chosen";
                    }
                };
            });
        }

        // ✅ Add new ETP
        btnAdd.addEventListener("click", () => {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = template.trim();
            const newBlock = tempDiv.firstElementChild;

            clearBlockValues(newBlock);
            etpContainer.appendChild(newBlock);
            refreshIndices();
            attachFileTriggers(newBlock);
        });

        // ✅ Delete last ETP
        btnDelete.addEventListener("click", () => {
            const blocks = etpContainer.querySelectorAll(".etp-block");
            if (blocks.length > 1) {
                blocks[blocks.length - 1].remove();
                refreshIndices();
            }
        });

        attachFileTriggers();
    });
</script>
