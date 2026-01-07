@php
    $medicalExpenses = $others->medical_expenses_offset ?? [];
@endphp

<section id="medicalExpensesForm">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Net Medical Expenses Tax Offset</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <p class="choosing-business-type-text mb-3">
        Important update: This section is ONLY for disability aids, attendant care or aged care.
    </p>

    <div class="grin_box_border p-3 mb-4">
        <p class="choosing-business-type-text mb-3">
            The ATO no longer accepts claims for any other types of medical expenses.
        </p>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="choosing-business-type-text mb-2">
                    T5(a): Total Allowable Medical Expenses Paid
                </label>
                <input type="number" step="0.01" class="form-control"
                       name="medical_expenses_offset[total_allowable_expenses]"
                       value="{{ $medicalExpenses['total_allowable_expenses'] ?? '' }}">
            </div>

            <div class="col-md-6">
                <label class="choosing-business-type-text mb-2">
                    T5(b): Total refunds received
                </label>
                <input type="number" step="0.01" class="form-control"
                       name="medical_expenses_offset[total_refunds]"
                       value="{{ $medicalExpenses['total_refunds'] ?? '' }}">
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="choosing-business-type-text mb-2">
                Total net medical expenses
            </label>
            <input type="number" step="0.01" class="form-control"
                   name="medical_expenses_offset[net_medical_expenses]"
                   value="{{ $medicalExpenses['net_medical_expenses'] ?? '' }}">
        </div>

        <!-- FILE UPLOAD -->
        <div class="mt-3">
            <label class="choosing-business-type-text d-block mb-2">
                Attach a simple breakdown of your medical expenses (optional)
            </label>

            <input
                type="file"
                name="medical_expenses_offset[medical_expense_file]"
                id="medicalExpenseFileInput"
                class="d-none"
            >

            <button type="button" class="btn btn_add" id="triggerMedicalExpenseFile">
                <img src="{{ asset('img/icons/plus.png') }}" alt="plus">
                Choose file
            </button>

            <p class="text-muted mt-1 mb-0">
                Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
            </p>

            <p id="medicalExpenseFileName" class="choosing-business-type-text text-muted mt-2 mb-0">
                @if(!empty($others->attach['medical_expenses_offset']['medical_expense_file']))
                    <a href="{{ Storage::disk('s3')->url($others->attach['medical_expenses_offset']['medical_expense_file']) }}"
                       target="_blank" class="btn btn-outline-success">
                        <i class="fa-solid fa-file"></i> View file
                    </a>
                @else
                    No file chosen
                @endif
            </p>
        </div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('medicalExpenseFileInput');
        const triggerBtn = document.getElementById('triggerMedicalExpenseFile');
        const fileNameBox = document.getElementById('medicalExpenseFileName');

        triggerBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                fileNameBox.innerHTML =
                    `<span class="text-success">
                    <i class="fa-solid fa-file"></i>
                    ${fileInput.files[0].name}
                </span>`;
            } else {
                fileNameBox.textContent = 'No file chosen';
            }
        });
    });
</script>

