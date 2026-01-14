<section>
    @php
        $phi = $others->private_health_insurance ?? [];
        $attach = $others->attach['private_health_insurance'] ?? [];

        // Merge file path info into $phi for easier access
        if (!empty($attach['statement_file'])) {
            $phi['statement_file'] = $attach['statement_file'];
        }
        if (!empty($attach['private_health_statement'])) {
            $phi['private_health_statement'] = $attach['private_health_statement'];
        }

        $statements = $phi['statements'] ?? [[]];
        $familyStatusVal = $phi['family_status'] ?? '';
        $dependentChildrenVal = $phi['dependent_children'] ?? '';
        $inputOptionVal = $phi['input_option'] ?? '';
    @endphp

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Private Health Insurance Policy Details</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <div class="grin_box_border mb-4">

        {{-- Family status --}}
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <label for="family_status" class="choosing-business-type-text">
                What was your family status on 30 June <?= date('Y') ?>?
                </label>
                <select id="family_status" name="private_health_insurance[family_status]" class="form-select border-dark">
                    <option value="">Choose</option>
                    <option value="Single" {{ $familyStatusVal==='Single' ? 'selected' : '' }}>Single</option>
                    <option value="Had a spouse" {{ $familyStatusVal==='Had a spouse' ? 'selected' : '' }}>Had a spouse</option>
                </select>
            </div>
        </div>

        {{-- Dependent children (keeps your original behavior: shown when "Single") --}}
        <div class="row">
            <div class="col-md-6 mb-3 {{ $familyStatusVal==='Single' ? '' : 'd-none' }}" id="dependent_children_block">
                <label class="choosing-business-type-text">At that time, did you have any dependent children?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio" type="radio"
                           name="private_health_insurance[dependent_children]"
                           id="children_yes" value="yes" {{ $dependentChildrenVal==='yes' ? 'checked' : '' }}>
                    <label class="form-check-label custom-label" for="children_yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input custom-radio" type="radio"
                           name="private_health_insurance[dependent_children]"
                           id="children_no" value="no" {{ $dependentChildrenVal==='no' ? 'checked' : '' }}>
                    <label class="form-check-label custom-label" for="children_no">No</label>
                </div>
            </div>
        </div>

        {{-- Input option --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="input_option" class="choosing-business-type-text">Please choose one of the 3 options below</label>
                <select id="input_option" name="private_health_insurance[input_option]" class="form-select border-dark">
                    <option value="">Choose</option>
                    <option value="Let Etax collect my details" {{ $inputOptionVal==='Let Etax collect my details' ? 'selected' : '' }}>Let Etax collect my details</option>
                    <option value="Attach my statement" {{ $inputOptionVal==='Attach my statement' ? 'selected' : '' }}>Attach my statement</option>
                    <option value="Enter my details myself" {{ $inputOptionVal==='Enter my details myself' ? 'selected' : '' }}>Enter my details myself</option>
                </select>
            </div>
        </div>

        {{-- Etax message --}}
        <div class="text-success mb-3 {{ $inputOptionVal==='Let Etax collect my details' ? '' : 'd-none' }}" id="etax_success_text">
            <strong>Great!</strong> Please continue to the next section. <br>
            We will add your Private Health cover to your tax return for you!
        </div>

        {{-- Attach my statement --}}
        <div class="{{ $inputOptionVal==='Attach my statement' ? '' : 'd-none' }}" id="upload_statement_block">
            <div class="row mb-3 align-items-end">
                <p class="choosing-business-type-text">Attach a copy of your annual private health cover statement</p>
                <div class="col-md-6 mb-3">
                    <input type="file" name="private_health_insurance[statement_file]" id="statementFileInput" class="d-none">
                    <button type="button" class="btn btn_add" id="statementFileTrigger">
                        <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Choose file
                    </button>
                    <p class="text-muted mt-1 mb-0">
                        Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <p id="statementFileName" class="choosing-business-type-text text-muted mb-0">
                        @if(!empty($phi['statement_file']))
                            @if(is_array($phi['statement_file']))
                                @foreach($phi['statement_file'] as $file)
                                    <a href="{{ Storage::disk('s3')->url($file) }}" target="_blank" class="btn btn-outline-success">
                                        <i class="fa-solid fa-file"></i> View file
                                    </a><br>
                                @endforeach
                            @else
                                <a href="{{ Storage::disk('s3')->url($phi['statement_file']) }}" target="_blank" class="btn btn-outline-success">
                                    <i class="fa-solid fa-file"></i> View file
                                </a>
                            @endif
                        @else
                            No file chosen
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Manual input --}}
        <div class="manual_input_block {{ $inputOptionVal==='Enter my details myself' ? '' : 'd-none' }}">
            @foreach($statements as $idx => $statement)
                @php
                    $s = is_array($statement) ? $statement : [];
                @endphp
                <div class="grin_box_border mb-3 health-statement-block" data-index="{{ $idx }}" style="display:block;">
                    <p class="choosing-business-type-text mb-3 line-title">
                        Private Health Cover - Your Summary (line {{ $idx + 1 }})
                    </p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="choosing-business-type-text">Health insurer ID</label>
                            <select name="private_health_insurance[statements][{{ $idx }}][health_insurer_id]" class="form-select border-dark">
                                <option value="">Choose</option>
                                <option value="ACA - ACA Health Benefits Fund" {{ ($s['health_insurer_id'] ?? '') ==='ACA - ACA Health Benefits Fund' ? 'selected' : '' }}>ACA - ACA Health Benefits Fund</option>
                                <option value="MYO - AIA Health Insurance" {{ ($s['health_insurer_id'] ?? '')==='MYO - AIA Health Insurance' ? 'selected' : '' }}>MYO - AIA Health Insurance</option>
                                <option value="AHM - Australian Health Management Group" {{ ($s['health_insurer_id'] ?? '')==='AHM - Australian Health Management Group' ? 'selected' : '' }}>AHM - Australian Health Management Group</option>
                                <option value="AUF - Australian Unity Health Limited" {{ ($s['health_insurer_id'] ?? '')==='AUF - Australian Unity Health Limited' ? 'selected' : '' }}>AUF - Australian Unity Health Limited</option>
                                <option value="BUP - BUPA" {{ ($s['health_insurer_id'] ?? '')==='BUP - BUPA' ? 'selected' : '' }}>BUP - BUPA</option>
                                <option value="CBC - CBHS Corporate Health Pty Ltd" {{ ($s['health_insurer_id'] ?? '')==='CBC - CBHS Corporate Health Pty Ltd' ? 'selected' : '' }}>CBC - CBHS Corporate Health Pty Ltd</option>
                                <option value="CDH - Cessnock District Health Benefits Fund Limited" {{ ($s['health_insurer_id'] ?? '')==='CDH - Cessnock District Health Benefits Fund Limited' ? 'selected' : '' }}>CDH - Cessnock District Health Benefits Fund Limited</option>
                                <option value="AHB - Defence Health Ltd" {{ ($s['health_insurer_id'] ?? '')==='AHB - Defence Health Ltd' ? 'selected' : '' }}>AHB - Defence Health Ltd</option>
                                <option value="GMH-GMHBA Limited" {{ ($s['health_insurer_id'] ?? '')==='GMH-GMHBA Limited' ? 'selected' : '' }}>GMH-GMHBA Limited</option>
                                <option value="FAI-GU Health" {{ ($s['health_insurer_id'] ?? '')==='FAI-GU Health' ? 'selected' : '' }}>FAI-GU Health</option>
                                <option value="HBF - HBF Health Limited" {{ ($s['health_insurer_id'] ?? '')==='HBF - HBF Health Limited' ? 'selected' : '' }}>HBF - HBF Health Limited</option>
                                <option value="HCI - Health Care Insurance Ltd" {{ ($s['health_insurer_id'] ?? '')==='HCI - Health Care Insurance Ltd' ? 'selected' : '' }}>HCI - Health Care Insurance Ltd</option>
                                <option value="HIF - Health Insurance Fund of Australia Ltd" {{ ($s['health_insurer_id'] ?? '')==='HIF - Health Insurance Fund of Australia Ltd' ? 'selected' : '' }}>HIF - Health Insurance Fund of Australia Ltd</option>
                                <option value="SPS - Health Partners" {{ ($s['health_insurer_id'] ?? '')==='SPS - Health Partners' ? 'selected' : '' }}>SPS - Health Partners</option>
                                <option value="LHS - Latrobe Health Services" {{ ($s['health_insurer_id'] ?? '')==='LHS - Latrobe Health Services' ? 'selected' : '' }}>LHS - Latrobe Health Services</option>
                                <option value="MBP - Medibank Private Limited" {{ ($s['health_insurer_id'] ?? '')==='MBP - Medibank Private Limited' ? 'selected' : '' }}>MBP - Medibank Private Limited</option>
                                <option value="MDH - Mildura District Hospital Fund" {{ ($s['health_insurer_id'] ?? '')==='MDH - Mildura District Hospital Fund' ? 'selected' : '' }}>MDH - Mildura District Hospital Fund</option>
                                <option value="NHB - Navy Health" {{ ($s['health_insurer_id'] ?? '')==='NHB - Navy Health' ? 'selected' : '' }}>NHB - Navy Health</option>
                                <option value="NIB - NIB Health Funds Ltd" {{ ($s['health_insurer_id'] ?? '')==='NIB - NIB Health Funds Ltd' ? 'selected' : '' }}>NIB - NIB Health Funds Ltd</option>
                                <option value="OMF - onemedifund" {{ ($s['health_insurer_id'] ?? '')==='OMF - onemedifund' ? 'selected' : '' }}>OMF - onemedifund</option>
                                <option value="LHM - Peoplecare" {{ ($s['health_insurer_id'] ?? '')==='LHM - Peoplecare' ? 'selected' : '' }}>LHM - Peoplecare</option>
                                <option value="PWA - Phoenix Health Fund Limited" {{ ($s['health_insurer_id'] ?? '')==='PWA - Phoenix Health Fund Limited' ? 'selected' : '' }}>PWA - Phoenix Health Fund Limited</option>
                                <option value="SPE - Police Health Limited" {{ ($s['health_insurer_id'] ?? '')==='SPE - Police Health Limited' ? 'selected' : '' }}>SPE - Police Health Limited</option>
                                <option value="QCH - Queensland Country Health Fund" {{ ($s['health_insurer_id'] ?? '')==='QCH - Queensland Country Health Fund' ? 'selected' : '' }}>QCH - Queensland Country Health Fund</option>
                                <option value="RBH - Reserve Bank Health Society" {{ ($s['health_insurer_id'] ?? '')==='RBH - Reserve Bank Health Society' ? 'selected' : '' }}>RBH - Reserve Bank Health Society</option>
                                <option value="RTE - rt health fund" {{ ($s['health_insurer_id'] ?? '')==='RTE - rt health fund' ? 'selected' : '' }}>RTE - rt health fund</option>
                                <option value="CPS - see-u by HBF" {{ ($s['health_insurer_id'] ?? '')==='CPS - see-u by HBF' ? 'selected' : '' }}>CPS - see-u by HBF</option>
                                <option value="SLM - St Lukes Medical and Hospital Benefits Assoc" {{ ($s['health_insurer_id'] ?? '')==='SLM - St Lukes Medical and Hospital Benefits Assoc' ? 'selected' : '' }}>SLM - St Lukes Medical and Hospital Benefits Assoc</option>
                                <option value="NTF - Teachers Health Fund" {{ ($s['health_insurer_id'] ?? '')==='NTF - Teachers Health Fund' ? 'selected' : '' }}>NTF - Teachers Health Fund</option>
                                <option value="QTU - Teachers' Union Health" {{ ($s['health_insurer_id'] ?? '')==="QTU - Teachers' Union Health" ? 'selected' : '' }}>QTU - Teachers' Union Health</option>
                                <option value="AMA - The Doctors' Health Fund Pty Ltd" {{ ($s['health_insurer_id'] ?? '')==='AMA - The Doctors\' Health Fund Pty Ltd' ? 'selected' : '' }}>AMA - The Doctors' Health Fund Pty Ltd</option>
                                <option value="HCF - The Hospitals Contribution Fund of Australia Limited" {{ ($s['health_insurer_id'] ?? '')==='HCF - The Hospitals Contribution Fund of Australia Limited' ? 'selected' : '' }}>HCF - The Hospitals Contribution Fund of Australia Limited</option>
                                <option value="TFS - Transport Health Pty Ltd" {{ ($s['health_insurer_id'] ?? '')==='TFS - Transport Health Pty Ltd' ? 'selected' : '' }}>TFS - Transport Health Pty Ltd</option>
                                <option value="WFD - Westfund Limited" {{ ($s['health_insurer_id'] ?? '')==='WFD - Westfund Limited' ? 'selected' : '' }}>WFD - Westfund Limited</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="choosing-business-type-text">Membership number</label>
                            <input type="text" name="private_health_insurance[statements][{{ $idx }}][membership_number]"
                                   class="form-control border-dark" value="{{ $s['membership_number'] ?? '' }}" placeholder="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="choosing-business-type-text">Your premiums eligible for Australian Government rebate (A)</label>
                            <input type="text" name="private_health_insurance[statements][{{ $idx }}][premiums_eligible]"
                                   class="form-control border-dark" value="{{ $s['premiums_eligible'] ?? '' }}" placeholder="00.00$">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="choosing-business-type-text">Your Australian Government rebate received (B)</label>
                            <input type="text" name="private_health_insurance[statements][{{ $idx }}][rebate_received]"
                                   class="form-control border-dark" value="{{ $s['rebate_received'] ?? '' }}" placeholder="00.00$">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="choosing-business-type-text">Benefit code</label>
                            <select name="private_health_insurance[statements][{{ $idx }}][benefit_code]" class="form-select border-dark">
                                <option value="">Choose</option>
                                @foreach(['30','31','35','36','40','41'] as $bc)
                                    <option value="{{ $bc }}" {{ ($s['benefit_code'] ?? '')==$bc ? 'selected' : '' }}>{{ $bc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-2">
                        <button type="button" class="btn btn_add btn-sm removeStatementBtn">
                            Remove this statement
                        </button>
                    </div>
                </div>
            @endforeach

            <div class="row">
                <div class="col-md-6 mb-3">
                    <button type="button" class="btn btn_add" id="addStatementBtn">
                        <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another statement
                    </button>
                    <button type="button" class="btn btn_add" id="addSpouseStatementBtn">
                        <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add spouse's statement
                    </button>
                </div>
            </div>

            <div class="row mb-3 align-items-end">
                <p class="choosing-business-type-text">
                    Confused? Just attach a copy of your annual private health cover statement, then we can complete this section for you.
                </p>
                <div class="col-md-6 mb-3">
                    <input type="file" name="private_health_insurance[private_health_statement]" id="privateHealthInput" class="d-none">
                    <button type="button" class="btn btn_add" id="privateHealthTrigger">
                        <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Choose file
                    </button>
                    <p class="text-muted mt-1 mb-0">
                        Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <p id="privateHealthFileName" class="choosing-business-type-text text-muted mb-0">
                        @if(!empty($phi['private_health_statement']))
                            @if(is_array($phi['private_health_statement']))
                                @foreach($phi['private_health_statement'] as $file)
                                    <a href="{{ Storage::disk('s3')->url($file) }}" target="_blank" class="btn btn-outline-success">
                                        <i class="fa-solid fa-file"></i> View file
                                    </a><br>
                                @endforeach
                            @else
                                <a href="{{ Storage::disk('s3')->url($phi['private_health_statement']) }}" target="_blank" class="btn btn-outline-success">
                                    <i class="fa-solid fa-file"></i> View file
                                </a>
                            @endif
                        @else
                            No file chosen
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Hidden template for new statement blocks --}}
<template id="statement-template">
    <div class="grin_box_border mb-3 health-statement-block" data-index="__INDEX__" style="display:block;">
        <p class="choosing-business-type-text mb-3 line-title">
            Private Health Cover - Your Summary (line __LINENO__)
        </p>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Health insurer ID</label>
                <select name="private_health_insurance[statements][__INDEX__][health_insurer_id]" class="form-select border-dark">
                    <option value="">Choose</option>
                    <option value="ACA - ACA Health Benefits Fund">ACA - ACA Health Benefits Fund</option>
                    <option value="MYO - AIA Health Insurance">MYO - AIA Health Insurance</option>
                    <option value="AHM - Australian Health Management Group">AHM - Australian Health Management Group</option>
                    <option value="AUF - Australian Unity Health Limited">AUF - Australian Unity Health Limited</option>
                    <option value="BUP - BUPA">BUP - BUPA</option>
                    <option value="CBC - CBHS Corporate Health Pty Ltd">CBC - CBHS Corporate Health Pty Ltd</option>
                    <option value="CDH - Cessnock District Health Benefits Fund Limited">CDH - Cessnock District Health Benefits Fund Limited</option>
                    <option value="AHB - Defence Health Ltd">AHB - Defence Health Ltd</option>
                    <option value="GMH-GMHBA Limited">GMH-GMHBA Limited</option>
                    <option value="FAI-GU Health">FAI-GU Health</option>
                    <option value="HBF - HBF Health Limited">HBF - HBF Health Limited</option>
                    <option value="HCI - Health Care Insurance Ltd">HCI - Health Care Insurance Ltd</option>
                    <option value="HIF - Health Insurance Fund of Australia Ltd">HIF - Health Insurance Fund of Australia Ltd</option>
                    <option value="SPS - Health Partners">SPS - Health Partners</option>
                    <option value="LHS - Latrobe Health Services">LHS - Latrobe Health Services</option>
                    <option value="MBP - Medibank Private Limited">MBP - Medibank Private Limited</option>
                    <option value="MDH - Mildura District Hospital Fund">MDH - Mildura District Hospital Fund</option>
                    <option value="NHB - Navy Health">NHB - Navy Health</option>
                    <option value="NIB - NIB Health Funds Ltd">NIB - NIB Health Funds Ltd</option>
                    <option value="OMF - onemedifund">OMF - onemedifund</option>
                    <option value="LHM - Peoplecare">LHM - Peoplecare</option>
                    <option value="PWA - Phoenix Health Fund Limited">PWA - Phoenix Health Fund Limited</option>
                    <option value="SPE - Police Health Limited">SPE - Police Health Limited</option>
                    <option value="QCH - Queensland Country Health Fund">QCH - Queensland Country Health Fund</option>
                    <option value="RBH - Reserve Bank Health Society">RBH - Reserve Bank Health Society</option>
                    <option value="RTE - rt health fund">RTE - rt health fund</option>
                    <option value="CPS - see-u by HBF">CPS - see-u by HBF</option>
                    <option value="SLM - St Lukes Medical and Hospital Benefits Assoc">SLM - St Lukes Medical and Hospital Benefits Assoc</option>
                    <option value="NTF - Teachers Health Fund">NTF - Teachers Health Fund</option>
                    <option value="QTU - Teachers' Union Health">QTU - Teachers' Union Health</option>
                    <option value="AMA - The Doctors' Health Fund Pty Ltd">AMA - The Doctors' Health Fund Pty Ltd</option>
                    <option value="HCF - The Hospitals Contribution Fund of Australia Limited">HCF - The Hospitals Contribution Fund of Australia Limited</option>
                    <option value="TFS - Transport Health Pty Ltd">TFS - Transport Health Pty Ltd</option>
                    <option value="WFD - Westfund Limited">WFD - Westfund Limited</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Membership number</label>
                <input type="text" name="private_health_insurance[statements][__INDEX__][membership_number]" class="form-control border-dark" placeholder="0">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Your premiums eligible for Australian Government rebate (A)</label>
                <input type="text" name="private_health_insurance[statements][__INDEX__][premiums_eligible]" class="form-control border-dark" placeholder="00.00$">
            </div>
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Your Australian Government rebate received (B)</label>
                <input type="text" name="private_health_insurance[statements][__INDEX__][rebate_received]" class="form-control border-dark" placeholder="00.00$">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Benefit code</label>
                <select name="private_health_insurance[statements][__INDEX__][benefit_code]" class="form-select border-dark">
                    <option value="">Choose</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    <option value="35">35</option>
                    <option value="36">36</option>
                    <option value="40">40</option>
                    <option value="41">41</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="private_health_insurance[statements][__INDEX__][owner]" value="self">

        <div class="d-flex gap-2 mb-2">
            <button type="button" class="btn btn_add btn-sm removeStatementBtn">Remove this statement</button>
        </div>
    </div>
</template>

<script>
    (function () {
        const familyStatus = document.getElementById('family_status');
        const dependentChildrenBlock = document.getElementById('dependent_children_block');
        const inputOption = document.getElementById('input_option');
        const etaxSuccessText = document.getElementById('etax_success_text');
        const uploadStatementBlock = document.getElementById('upload_statement_block');
        const manualInputBlock = document.querySelector('.manual_input_block');
        const addStatementBtn = document.getElementById('addStatementBtn');
        const addSpouseStatementBtn = document.getElementById('addSpouseStatementBtn');
        const privateHealthTrigger = document.getElementById('privateHealthTrigger');
        const privateHealthInput = document.getElementById('privateHealthInput');
        const privateHealthFileName = document.getElementById('privateHealthFileName');
        
        // File trigger elements (must be declared before functions use them)
        const statementFileTrigger = document.getElementById('statementFileTrigger');
        const statementFileInput = document.getElementById('statementFileInput');
        const statementFileName = document.getElementById('statementFileName');

        // Toggle dependent children (keeps your original "Single => show" behavior)
        function toggleChildren() {
            if (familyStatus.value === 'Single') {
                dependentChildrenBlock.classList.remove('d-none');
            } else {
                dependentChildrenBlock.classList.add('d-none');
            }
        }

        // Clear attachment file input when switching away from "Attach my statement"
        function clearAttachmentSection() {
            if (statementFileInput) {
                statementFileInput.value = '';
                // Only reset the display if no file was previously uploaded
                if (statementFileName && !statementFileName.querySelector('a')) {
                    statementFileName.textContent = 'No file chosen';
                }
            }
        }

        // Clear manual input statements when switching away from "Enter my details myself"
        function clearManualSection() {
            // Remove all dynamically added statement blocks
            const blocks = manualInputBlock.querySelectorAll('.health-statement-block');
            blocks.forEach((block, index) => {
                // Clear all inputs in each block
                block.querySelectorAll('input, select').forEach(input => {
                    if (input.type === 'text') {
                        input.value = '';
                    } else if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    }
                });
            });

            // Also clear the file input for private health statement
            if (privateHealthInput) {
                privateHealthInput.value = '';
                // Only reset the display if no file was previously uploaded
                if (privateHealthFileName && !privateHealthFileName.querySelector('a')) {
                    privateHealthFileName.textContent = 'No file chosen';
                }
            }
        }

        // Toggle input option sections
        function toggleOption() {
            etaxSuccessText.classList.add('d-none');
            uploadStatementBlock.classList.add('d-none');
            manualInputBlock.classList.add('d-none');

            if (inputOption.value === 'Let Etax collect my details') {
                etaxSuccessText.classList.remove('d-none');
                // Clear other sections
                clearAttachmentSection();
                clearManualSection();
            } else if (inputOption.value === 'Attach my statement') {
                uploadStatementBlock.classList.remove('d-none');
                // Clear other sections
                clearManualSection();
            } else if (inputOption.value === 'Enter my details myself') {
                manualInputBlock.classList.remove('d-none');
                // Clear attachment section
                clearAttachmentSection();
            }
        }

        // Init on load for pre-filled values
        toggleChildren();
        toggleOption();

        familyStatus.addEventListener('change', toggleChildren);
        inputOption.addEventListener('change', toggleOption);

        if (statementFileTrigger && statementFileInput && statementFileName) {
            statementFileTrigger.addEventListener('click', () => statementFileInput.click());
            statementFileInput.addEventListener('change', function () {
                const fileName = this.files[0]?.name || 'No file chosen';
                statementFileName.textContent = fileName;
            });
        }

        privateHealthTrigger.addEventListener('click', () => privateHealthInput.click());
        privateHealthInput.addEventListener('change', () => {
            const fileName = privateHealthInput.files[0]?.name || 'No file chosen';
            privateHealthFileName.textContent = fileName;
        });

        // Dynamic statements
        function getNextIndex() {
            const blocks = manualInputBlock.querySelectorAll('.health-statement-block');
            let max = -1;
            blocks.forEach(b => {
                const i = parseInt(b.getAttribute('data-index'), 10);
                if (!isNaN(i)) max = Math.max(max, i);
            });
            return max + 1;
        }

        function addStatement(owner = 'self') {
            const tpl = document.getElementById('statement-template').innerHTML;
            const index = getNextIndex();
            const lineNo = manualInputBlock.querySelectorAll('.health-statement-block').length + 1;

            const html = tpl
                .replaceAll('__INDEX__', index)
                .replaceAll('__LINENO__', lineNo);

            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            const block = wrapper.firstElementChild;

            // set owner (self/spouse)
            block.querySelector('input[name^="private_health_insurance[statements]"][name$="[owner]"]').value = owner;

            manualInputBlock.appendChild(block);
            attachRemoveHandler(block);
        }

        function attachRemoveHandler(block) {
            const btn = block.querySelector('.removeStatementBtn');
            if (!btn) return;
            btn.addEventListener('click', () => {
                block.remove();
                // re-number titles
                manualInputBlock.querySelectorAll('.health-statement-block').forEach((b, idx) => {
                    const title = b.querySelector('.line-title');
                    if (title) title.textContent = `Private Health Cover - Your Summary (line ${idx + 1})`;
                });
            });
        }

        // Attach remove handlers to existing blocks
        manualInputBlock.querySelectorAll('.health-statement-block').forEach(attachRemoveHandler);

        addStatementBtn.addEventListener('click', () => addStatement('self'));
        addSpouseStatementBtn.addEventListener('click', () => addStatement('spouse'));
    })();
</script>
