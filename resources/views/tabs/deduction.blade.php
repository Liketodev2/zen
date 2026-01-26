<section class="choosing-business-type_section">
    <h2 class="choosing-business-type-title">My Deduction Finder</h2>
    <p class="choosing-business-type-text text-center mb-5">
        Now let’s find some tax deductions to help improve your refund.
    </p>
    <h4 class="form_title">These are common tax deductions for a Manager - retail store.</h4>
    <div class="select_deduction_container mt-0">
        @php
            $deductionsItems = [
                'car_expenses' => 'Car expenses + parking + tolls',
                'travel_expenses' => 'Travel Expenses',
                'mobile_phone' => 'Mobile Phone',
                'internet_access' => 'Internet Access',
                'computer' => 'Computer / Laptop',
                'gifts' => 'Gifts / Donations',
                'home_office' => 'Home Office Expenses',
                'books' => 'Books & Other Work-Related Expenses',
                'tax_affairs' => 'Cost of Tax Affairs',
                'uniforms' => 'Uniforms',
                'education' => 'Education Expenses',
                'tools' => 'Tools and Equipment',
                'superannuation' => 'Superannuation Contributions',
                'office_occupancy' => 'Home Office Occupancy',
                'union_fees' => 'Union Fees',
                'sun_protection' => 'Sun Protection',
                'low_value_pool' => 'Low Value Pool Deduction',
                'interest_deduction' => 'Interest Deductions',
                'dividend_deduction' => 'Dividend Deductions',
                'upp' => 'Deductible Amount Of UPP',
                'project_pool' => 'Deduction For Project Pool',
                'investment_scheme' => 'Investment Scheme Deduction',
                'other' => 'Other Deductions',
            ];
        @endphp

        @foreach(array_slice($deductionsItems, 0, 9, true) as $key => $label)
            <div class="deduction-item @if(isset($deductions) && !empty($deductions->$key)) active @endif"
     data-index="{{ $loop->index }}" data-key="{{ $key }}">
                <div class="deduction-label">
                    <p>{{ $label }}</p>
                    <img src="{{ asset('img/icons/hr.png') }}" class="img-fluid" alt="hr">
                </div>
                <div class="deduction-icon">
                    <img src="{{ asset('img/icons/deduction/' . $key . '.png') }}" class="img-fluid" alt="{{ $key }}">
                </div>
            </div>
        @endforeach
    </div>

    <div id="more-deductions-section" class="d-none">
        <h4 class="form_title mt-4">Is there anything else you can claim?</h4>
        <div class="select_deduction_container select_deduction_container1 mt-0">
            @foreach(array_slice($deductionsItems, 9, null, true) as $key => $label)
                <div class="deduction-item @if(isset($deductions) && !empty($deductions->$key)) active @endif"
     data-index="{{ $loop->index + 9 }}" data-key="{{ $key }}">
                    <div class="deduction-label">
                        <p>{{ $label }}</p>
                        <img src="{{ asset('img/icons/hr.png') }}" class="img-fluid" alt="hr">
                    </div>
                    <div class="deduction-icon">
                        <img src="{{ asset('img/icons/deduction/' . $key . '.png') }}" class="img-fluid" alt="{{ $key }}">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="text-center">
        <button class="btn toggle-btn" id="toggleDeductionBtn">Show More</button>
    </div>
</section>

<section class="choosing-business-type_section">
    <h2 class="choosing-business-type-title" id="deduction-forms_title">Let’s add the details</h2>

    <form id="deduction-form" action="{{ isset($deductions) ? route('deduction.update', [$taxReturn->id, $deductions->id]) : route('deduction.store', $taxReturn->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($deductions))
            @method('PUT')
        @endif
        <input type="hidden" name="active_sections" id="activeSections" value="[]">
        <div class="deduction-form-container">
            @foreach(array_keys($deductionsItems) as $i => $key)
                <div class="d-none" id="form-deduction-{{ $i }}">
                    @include('forms.deduction.' . $key, ['deductions' => $deductions ?? null])
                </div>
            @endforeach

            <div class="d-flex justify-content-end mb-5 mt-3">
                <button type="submit" class="btn navbar_btn">
                    {{ isset($deductions) ? 'Update' : 'Save' }}
                </button>
            </div>
        </div>
    </form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll(".deduction-item");
    const activeSectionsInput = document.getElementById('activeSections');

    function updateActiveSections() {
        const activeKeys = Array.from(document.querySelectorAll('.deduction-item.active'))
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
        const formToShow = document.getElementById(`form-deduction-${index}`);

        if (item.classList.contains("active") && formToShow) {
            formToShow.classList.remove("d-none");
            setInputsEnabled(formToShow, true);
        } else if (formToShow) {
            formToShow.classList.add("d-none");
            setInputsEnabled(formToShow, false);
        }

        item.addEventListener("click", () => {
            if (!formToShow) return;

            const willActivate = !item.classList.contains('active');
            item.classList.toggle('active', willActivate);

            if (willActivate) {
                formToShow.classList.remove('d-none');
                setInputsEnabled(formToShow, true);
                formToShow.scrollIntoView({ behavior: "smooth", block: "start" });
            } else {
                formToShow.classList.add('d-none');
                setInputsEnabled(formToShow, false);
            }

            updateActiveSections();
        });
    });

    updateActiveSections();

    const moreSection = document.getElementById('more-deductions-section');
    const toggleBtn = document.getElementById('toggleDeductionBtn');

    if (moreSection && toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            if (moreSection.classList.contains('d-none')) {
                moreSection.classList.remove('d-none');
                this.textContent = 'Show Less';
            } else {
                moreSection.classList.add('d-none');
                this.textContent = 'Show More';
            }
        });
    }

    const form = document.getElementById('deduction-form');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            document.querySelectorAll('.text-danger').forEach(el => el.remove());

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

                    if (data.deductionId && !form.action.includes('update')) {
                        form.action = form.action.replace('deduction.store', `deduction.update/${data.deductionId}`);
                        submitBtn.textContent = 'Update';
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
    }

    function showToast(type, message) {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
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
