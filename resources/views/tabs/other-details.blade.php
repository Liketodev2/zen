<section class="choosing-business-type_section">
    <h2 class="choosing-business-type-title">My Deduction Finder</h2>
    <p class="choosing-business-type-text text-center mb-5">
        Now let’s find some tax deductions to help improve your refund.
    </p>
    <h4 class="form_title">These are common tax deductions for a Manager - retail store.</h4>
    <div class="select_deduction_container select_deduction_container2 mt-0">
    @php
        $items = [
            'spouse_details' => 'Spouse Details',
            'private_health_insurance' => 'Private Health Insurance',
            'zone_overseas_forces_offset' => 'Zone / Overseas Forces Offset',
            'seniors_offset' => 'Seniors Offset',
            'medicare_reduction_exemption' => 'Medicare Reduction / Exemption',
            'part_year_tax_free_threshold' => 'Part-year Tax-free Threshold',
            'medical_expenses_offset' => 'Medical Expenses Offset',
            'under_18' => 'Under 18',
            'working_holiday_maker_net_income' => 'Working Holiday Maker Net Income',
            'superannuation_income_stream_offset' => 'Superannuation Income Stream Offset',
            'superannuation_contributions_spouse' => 'Superannuation Contributions on Behalf of Your Spouse',
            'tax_losses_earlier_income_years' => 'Tax Losses of Earlier Income Years',
            'dependent_invalid_and_carer' => 'Dependent (invalid and carer)',
            'superannuation_co_contribution' => 'Superannuation Co-Contribution',
            'other_tax_offsets_refundable' => 'Other Tax Offsets (Refundable)',
        ];
    @endphp

    @foreach($items as $key => $label)
        <div class="other-details-item  @if(isset($others) && $others->$key !== null) active @endif" data-index="{{ $loop->index }}">
            <div class="other-details-label">
                <p>{{ $label }}</p>
                <img src="{{ asset('img/icons/hr.png') }}" class="img-fluid" alt="hr">
            </div>
            <div class="other-details-icon">
                <img src="{{ asset('img/icons/other-details/' . $key . '.png') }}" class="img-fluid" alt="{{ $label }}">
            </div>
        </div>
    @endforeach
    </div>

    <div class="text-center">
        <button class="btn toggle-btn" id="toggleBtnOther">Show More</button>
    </div>
</section>

<section class="choosing-business-type_section">
    <h2 class="choosing-business-type-title" id="other-details-forms_title">Let’s add the details</h2>
    <form id="other-form" action="{{ isset($others) ? route('other.update', [$taxReturn->id, $others->id]) : route('other.store', $taxReturn->id) }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        @if(isset($others))
            @method('PUT')
        @endif
        <div class="form-container">
            @include('forms.other-details.form.dependent_children', ['others' => $others ?? null])
            <div class="@if(!isset($others) ||  (isset($others) && $others->private_health_insurance == null)) d-none @endif" id="other-details-form-1">
                @include('forms.other-details.private_health_insurance', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->zone_overseas_forces_offset == null)) d-none @endif" id="other-details-form-2">
                @include('forms.other-details.zone_offset', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->seniors_offset == null)) d-none @endif" id="other-details-form-3">
                @include('forms.other-details.seniors_offset', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->medicare_reduction_exemption == null)) d-none @endif" id="other-details-form-4">
                @include('forms.other-details.medicare_reduction', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->medical_expenses_offset == null)) d-none @endif" id="other-details-form-6">
                @include('forms.other-details.medical_expenses', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->superannuation_income_stream_offset == null)) d-none @endif" id="other-details-form-9">
                @include('forms.other-details.super_income_stream', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->superannuation_contributions_spouse == null)) d-none @endif" id="other-details-form-10">
                @include('forms.other-details.super_contributions_spouse', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->tax_losses_earlier_income_years == null)) d-none @endif" id="other-details-form-11">
                @include('forms.other-details.tax_losses_earlier', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->dependent_invalid_and_carer == null)) d-none @endif" id="other-details-form-12">
                @include('forms.other-details.dependent_invalid', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->other_tax_offsets_refundable == null)) d-none @endif" id="other-details-form-14">
                @include('forms.other-details.other_refundable_offsets', ['others' => $others ?? null])
            </div>
            @include('forms.other-details.form.mls', ['others' => $others ?? null])
            <div class="@if(!isset($others) ||  (isset($others) && $others->part_year_tax_free_threshold == null)) d-none @endif" id="other-details-form-5">
                @include('forms.other-details.tax_free_threshold', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->under_18 == null)) d-none @endif" id="other-details-form-7">
                @include('forms.other-details.under_18', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->working_holiday_maker_net_income == null)) d-none @endif" id="other-details-form-8">
                @include('forms.other-details.working_holiday_income', ['others' => $others ?? null])
            </div>
            <div class="@if(!isset($others) ||  (isset($others) && $others->superannuation_co_contribution == null)) d-none @endif" id="other-details-form-13">
                @include('forms.other-details.super_co_contribution', ['others' => $others ?? null])
            </div>
            @include('forms.other-details.form.income_tests', ['others' => $others ?? null])
            <div class="@if(!isset($others) ||  (isset($others) && $others->spouse_details == null)) d-none @endif" id="other-details-form-0">
                @include('forms.other-details.spouse_details', ['others' => $others ?? null])
            </div>
            @include('forms.other-details.form.attach', ['others' => $others ?? null])

            <div class="d-flex justify-content-end mb-5 mt-3">
                <button type="submit" class="btn navbar_btn">
                    {{ isset($others) ? 'Update' : 'Save' }}
                </button>
            </div>
        </div>
    </form>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const items = document.querySelectorAll(".other-details-item");

        items.forEach((item) => {
            item.addEventListener("click", () => {
                const index = item.getAttribute("data-index");
                const formToShow = document.getElementById(`other-details-form-${index}`);

                if (formToShow && formToShow.classList.contains("d-none")) {
                    formToShow.classList.remove("d-none");
                    item.classList.add("active");

                    const target = document.getElementById("other-details-forms_title");
                    if (target) {
                        target.scrollIntoView({ behavior: "smooth", block: "start" });
                    }
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('other-form');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Clear old errors
            document.querySelectorAll('.text-danger').forEach(el => el.remove());

            // Disable all inputs in hidden sections
            const hiddenInputs = [];
            document.querySelectorAll('.d-none input, .d-none select, .d-none textarea').forEach(el => {
                if (!el.disabled) {
                    el.disabled = true;
                    hiddenInputs.push(el); // store to re-enable later
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

                    // Update form action after creating a new record
                    if (data.incomeId && !form.action.includes('update')) {
                        form.action = form.action.replace('other.store', `other.update/${data.incomeId}`);
                        form.querySelector('button[type="submit"]').textContent = 'Update Other';
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
                // Re-enable previously disabled hidden inputs
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

