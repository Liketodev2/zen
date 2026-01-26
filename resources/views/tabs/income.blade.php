<section class="choosing-business-type_section">
    <h2 class="choosing-business-type-title">My Income</h2>
    <p class="choosing-business-type-text text-center mb-5">
        Enter the personal information we need to verify your identity and begin your tax refund application.
    </p>
    <div class="select_income_container mt-0">
        @php

$incomeItems = [
    'salary' => 'Salary / Wages',
    'interests' => 'Interest',
    'dividends' => 'Dividends',
    'government_allowances' => 'Government Allowances',
    'government_pensions' => 'Government Pension',
    'capital_gains' => 'Capital Gains or Losses',
    'managed_funds' => 'Managed Funds',
    'termination_payments' => 'Termination Payments',
    'rent' => 'Rent Received',
    'partnerships' => 'Partnerships and Trusts',
    'annuities' => 'Australian Annuities',
    'superannuation' => 'Superannuation Income Stream',
    'super_lump_sums' => 'Super Lump Sums',
    'ess' => 'Employee Share Schemes',
    'personal_services' => 'Personal Services Income',
    'business_income' => 'Income / Loss From Business',
    'business_losses' => 'Deferred Business Losses',
    'foreign_income' => 'Foreign Source Income',
    'other_income' => 'Other Income'
];
        @endphp

        @foreach($incomeItems as $key => $label)
            <div class="income-item
    @if(isset($incomes) && data_get($incomes, $key)) active @endif"
    data-index="{{ $loop->index }}" data-key="{{ $key }}">
                <div class="other-details-label">
                    <p>{{ $label }}</p>
                    <img src="{{ asset('img/icons/hr.png') }}" class="img-fluid" alt="hr">
                </div>
                <div class="other-details-icon">
                    <img src="{{ asset('img/icons/income/' . $key . '.png') }}" class="img-fluid" alt="{{ $label }}">
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center">
        <button class="btn toggle-btn" id="toggleBtnIncome">Show More</button>
    </div>
</section>

<section class="choosing-business-type_section">
    <h2 class="choosing-business-type-title" id="income-forms_title">Let’s add the details</h2>
    <form id="income-form" action="{{ isset($incomes) ? route('income.update', [$taxReturn->id, $incomes->id]) : route('income.store', $taxReturn->id) }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="active_sections" id="activeSections" value="[]">
        <div class="form-container">
            @foreach(array_keys($incomeItems) as $i => $key)
                <div class="d-none" id="income-form-{{ $i }}">
                    @include('forms.income.' . $key, ['incomes' => $incomes ?? null])
                </div>
            @endforeach

            <div class="d-flex justify-content-end mb-5 mt-3">
                <button type="submit" class="btn navbar_btn">
                    {{ isset($incomes) ? 'Update' : 'Save' }}
                </button>
            </div>
        </div>
    </form>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll(".income-item");
    const activeSectionsInput = document.getElementById('activeSections');

    function updateActiveSections() {
        const activeKeys = Array.from(document.querySelectorAll('.income-item.active'))
            .map(el => el.getAttribute('data-key'))
            .filter(Boolean);
        activeSectionsInput.value = JSON.stringify(activeKeys);
    }

    function setInputsEnabled(sectionEl, enabled) {
        if (!sectionEl) return;
        const inputs = sectionEl.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.disabled = !enabled;
            if (!enabled && input.type === 'file') {
                try { input.value = ''; } catch (e) {}
            }
        });
    }

    items.forEach((item) => {
        const index = item.getAttribute("data-index");
        const formEl = document.getElementById(`income-form-${index}`);

        if (item.classList.contains("active") && formEl) {
            formEl.classList.remove("d-none");
            setInputsEnabled(formEl, true);
        } else if (formEl) {
            formEl.classList.add("d-none");
            setInputsEnabled(formEl, false);
        }

        item.addEventListener("click", () => {
            if (!formEl) return;

            const willActivate = !item.classList.contains('active');
            item.classList.toggle('active', willActivate);

            if (willActivate) {
                formEl.classList.remove('d-none');
                setInputsEnabled(formEl, true);
                formEl.scrollIntoView({ behavior: "smooth", block: "start" });
            } else {
                formEl.classList.add('d-none');
                setInputsEnabled(formEl, false);
            }

            updateActiveSections();
        });
    });

    updateActiveSections();
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('income-form');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Clear old errors
        document.querySelectorAll('.text-danger').forEach(el => el.remove());

        // Disable inputs in hidden sections
        const hiddenInputs = [];
        document.querySelectorAll('.d-none input, .d-none select, .d-none textarea').forEach(el => {
            if (!el.disabled) {
                el.disabled = true;
                hiddenInputs.push(el);
            }
        });

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Processing...';
        submitBtn.disabled = true;

        try {
            const formData = new FormData(form);
            const url = form.action;

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showToast('success', data.message);

                if (data.incomeId && !form.action.includes('update')) {
                    form.action = form.action.replace('income.store', `income.update/${data.incomeId}`);
                    form.querySelector('button[type="submit"]').textContent = 'Update Income';
                }
            } else {
                if (data.errors) {
                    for (const [field, errors] of Object.entries(data.errors)) {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'text-danger mt-1';
                            errorDiv.textContent = errors[0];
                            input.closest('.mb-3').appendChild(errorDiv);
                        }
                    }
                }
                showToast('error', data.message || 'An error occurred');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('error', 'Network error. Please try again.');
        } finally {
            hiddenInputs.forEach(el => el.disabled = false);
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    });

    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        toastContainer.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        setTimeout(() => bsToast.hide(), 5000);
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        container.style.zIndex = '1100';
        document.body.appendChild(container);
        return container;
    }
});
</script>
