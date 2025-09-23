@php
    $medicalExpenses = $others->medical_expenses_offset ?? [];
@endphp

<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Net Medical Expenses Tax Offset</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

        <p class="choosing-business-type-text mb-3">
            Important update: This section is ONLY for disability aids, attendant care or aged care.
        </p>
        <div class="grin_box_border p-3 mb-4">

        <p class="choosing-business-type-text mb-3">The ATO no longer accepts claims for any other types of medical expenses. Only claim items that you have receipts for.</p>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="choosing-business-type-text mb-2" for="total_allowable_expenses">
                    T5(a): Total Allowable Medical Expenses Paid
                </label>
                <input type="number" step="0.01" class="form-control" id="total_allowable_expenses"
                       name="medical_expenses_offset[total_allowable_expenses]" placeholder="00.00$"
                       value="{{ $medicalExpenses['total_allowable_expenses'] ?? ''}}">
            </div>

            <div class="col-md-6">
                <label class="choosing-business-type-text mb-2" for="total_refunds">
                    T5(b): Total refunds received/receivable from Medicare or your private health fund
                </label>
                <input type="number" step="0.01" class="form-control" id="total_refunds"
                       name="medical_expenses_offset[total_refunds]" placeholder="00.00$"
                       value="{{ $medicalExpenses['total_refunds'] ?? ''}}">
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="choosing-business-type-text mb-2" for="net_medical_expenses">
                Total net medical expenses for disability aids, attendant care or aged care
            </label>
            <input type="number" step="0.01" class="form-control" id="net_medical_expenses"
                   name="medical_expenses_offset[net_medical_expenses]" placeholder="00.00$"
                   value="{{ $medicalExpenses['net_medical_expenses'] ?? ''}}">
        </div>

        <div class="mt-3">
            <label class="choosing-business-type-text d-block mb-2">
                Attach a simple breakdown of your medical expenses (optional)
            </label>
            <input type="file" name="medical_expense_file" id="medical_expense_file" class="d-none" />
            <button type="button" class="btn btn_add" id="medical_expense_file_trigger">
                <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Choose file
            </button>
            <p class="text-muted mt-1 mb-0">
                Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
            </p>
            <p id="medical_expense_file_name" class="choosing-business-type-text text-muted mb-0 mt-2">
                @if(!empty($others->attach['medical_expense_file']))
                    <a href="{{ Storage::disk('s3')->url($others->attach['medical_expense_file']) }}" target="_blank">
                        <i class="fa-solid fa-file"></i> View file
                    </a>
                @else
                    No file chosen
                @endif
            </p>
        </div>
    </div>
<section>

