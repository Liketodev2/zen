
<form id="basic-info-form" action="{{ isset($basicInfo) ? route('basic-info.update', [$taxReturn->id,  $basicInfo->id]) : route('basic-info.store', $taxReturn->id) }}" method="POST">
    @csrf
    @if(isset($basicInfo))
        @method('PUT')
    @endif

    <section class="choosing-business-type_section">
        <h2 class="choosing-business-type-title">Please confirm your details</h2>
        <p class="choosing-business-type-text text-center">
            Enter the personal information we need to verify your identity and begin your tax refund application.
        </p>
        <div class="choosing-business-type-form-box">
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <input type="text" name="first_name" class="form-control border-dark" placeholder="First name"
                        value="{{ old('first_name', $basicInfo->first_name ?? '') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <input type="text" name="last_name" class="form-control border-dark" placeholder="Last name"
                        value="{{ old('last_name', $basicInfo->last_name ?? '') }}">
                </div>
            </div>

            <div class="row mb-3">
                <p class="choosing-business-type-text">Date of Birth</p>
                <!-- Day -->
                <div class="col-md-4 mb-3">
                    <select name="day" class="form-control border-dark">
                        <option value="">Day</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}" {{ old('day', $basicInfo->day ?? '') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <!-- Month -->
                <div class="col-md-4 mb-3">
                    <select name="month" class="form-control border-dark">
                        <option value="">Month</option>
                        @for ($i = 1; $i <= 12; $i++)
                            @php $monthName = DateTime::createFromFormat('!m', $i)->format('F'); @endphp
                            <option value="{{ $monthName  }}" {{ old('month', $basicInfo->month ?? '') == $monthName ? 'selected' : '' }}>
                                {{ $monthName }}
                            </option>
                        @endfor
                    </select>
                </div>
                <!-- Year -->
                <div class="col-md-4 mb-3">
                    <select name="year" class="form-control border-dark">
                        <option value="">Year</option>
                        @for ($i = date('Y'); $i >= date('Y') - 100; $i--)
                            <option value="{{ $i }}" {{ old('year', $basicInfo->year ?? '') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <input type="text" name="phone" class="form-control border-dark" placeholder="Phone Number"
                        value="{{ old('phone', $basicInfo->phone ?? '') }}">
                </div>
            </div>

            <div class="row mb-3">
                <p class="choosing-business-type-text">Gender</p>
                <div class="col-md-6 mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio" type="radio" name="gender" id="genderMale" value="male"
                            {{ old('gender', $basicInfo->gender ?? '') == 'male' ? 'checked' : '' }}>
                        <label class="form-check-label custom-label" for="genderMale">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio" type="radio" name="gender" id="genderFemale" value="female"
                            {{ old('gender', $basicInfo->gender ?? '') == 'female' ? 'checked' : '' }}>
                        <label class="form-check-label custom-label" for="genderFemale">Female</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <p class="choosing-business-type-text">Did you have a Spouse/De Facto during this financial year?</p>
                <div class="col-md-6 mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio" type="radio" name="has_spouse" id="spouseYes" value="1"
                            {{ old('has_spouse', $basicInfo->has_spouse ?? '') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label custom-label" for="spouseYes">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio" type="radio" name="has_spouse" id="spouseNo" value="0"
                            {{ old('has_spouse', $basicInfo->has_spouse ?? '') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label custom-label" for="spouseNo">No</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <p class="choosing-business-type-text">Will you be required to complete an Australian Tax Return in the future?</p>
                <div class="col-md-6 mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio" type="radio" name="future_tax_return" id="futureTaxYes" value="1"
                            {{ old('future_tax_return', $basicInfo->future_tax_return ?? '') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label custom-label" for="futureTaxYes">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio" type="radio" name="future_tax_return" id="futureTaxNo" value="0"
                            {{ old('future_tax_return', $basicInfo->future_tax_return ?? '') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label custom-label" for="futureTaxNo">No</label>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="choosing-business-type_section mb-4">
        <h2 class="choosing-business-type-title">Residency Information</h2>
        <div class="choosing-business-type-form-box container">
            <div class="row mb-5 grin_box_border text-center py-4">
                <p class="choosing-business-type-text">Do you have Australian Citizenship?</p>
                <div class="col mt-3 mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio" type="radio" name="australian_citizenship" id="citizenshipYes" value="1"
                            {{ old('australian_citizenship', $basicInfo->australian_citizenship ?? '') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label custom-label" for="citizenshipYes">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-radio" type="radio" name="australian_citizenship" id="citizenshipNo" value="0"
                            {{ old('australian_citizenship', $basicInfo->australian_citizenship ?? '') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label custom-label" for="citizenshipNo">No</label>
                    </div>
                </div>
            </div>

            <!-- Non-citizen section -->
            <div class="row mb-3" id="nonCitizenSection" style="display: {{ old('australian_citizenship', $basicInfo->australian_citizenship ?? '') == '0' ? 'block' : 'none' }};">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <select class="form-control" name="visa_type" id="visaSelect">
                            <option value="">What type of visa do you have (eg. 417, 457, NZ citizen)?</option>
                            <option value="417 - Working Holiday Visa" {{ old('visa_type', $basicInfo->visa_type ?? '') == '417 - Working Holiday Visa' ? 'selected' : '' }}>
                                417 - Working Holiday Visa
                            </option>
                            <option value="457 - Temporary Work Visa New Zealand citizen" {{ old('visa_type', $basicInfo->visa_type ?? '') == '457 - Temporary Work Visa New Zealand citizen' ? 'selected' : '' }}>
                                457 - Temporary Work Visa New Zealand citizen
                            </option>
                            <option value="462" {{ old('462 - Work and Holiday Visa', $basicInfo->visa_type ?? '') == '462 - Work and Holiday Visa' ? 'selected' : '' }}>
                                462 - Work and Holiday Visa
                            </option>
                            <option value="416 Special Program Visa" {{ old('visa_type', $basicInfo->visa_type ?? '') == '416 Special Program Visa' ? 'selected' : '' }}>
                                416 Special Program Visa
                            </option>
                            <option value="403 - Seasonal Work Visa" {{ old('visa_type', $basicInfo->visa_type ?? '') == '403 - Seasonal Work Visa' ? 'selected' : '' }}>
                                403 - Seasonal Work Visa
                            </option>
                            <option value="Other" {{ old('visa_type', $basicInfo->visa_type ?? '') == 'Other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="otherVisaInput" style="display: {{ old('visa_type', $basicInfo->visa_type ?? '') == 'other' ? 'block' : 'none' }};">
                        <input type="text" name="other_visa_type" class="form-control border-dark" placeholder="Specify visa type"
                            value="{{ old('other_visa_type', $basicInfo->other_visa_type ?? '') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <p class="choosing-business-type-text">
                            Did you live or stay in one place continuously for more than 183 days, during your stay in Australia?
                        </p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input custom-radio" type="radio" name="long_stay_183" id="stay183Yes" value="1"
                                {{ old('long_stay_183', $basicInfo->long_stay_183 ?? '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label custom-label" for="stay183Yes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input custom-radio" type="radio" name="long_stay_183" id="stay183No" value="0"
                                {{ old('long_stay_183', $basicInfo->long_stay_183 ?? '') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label custom-label" for="stay183No">No</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <p class="choosing-business-type-text">Date of arrival in Australia</p>
                    <div class="col-md-6 mb-3">
                        <select name="arrival_month" class="form-control border-dark">
                            <option value="">Month</option>
                            @for ($i = 1; $i <= 12; $i++)
                                @php $monthName = DateTime::createFromFormat('!m', $i)->format('F'); @endphp
                                <option value="{{ $monthName }}" {{ old('arrival_month', $basicInfo->arrival_month ?? '') == $monthName ? 'selected' : '' }}>
                                    {{ $monthName }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <select name="arrival_year" class="form-control border-dark">
                            <option value="">Year</option>
                            @for ($i = date('Y'); $i >= date('Y') - 100; $i--)
                                <option value="{{ $i }}" {{ old('arrival_year', $basicInfo->arrival_year ?? '') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <p class="choosing-business-type-text">Date of departure from Australia</p>
                    <div class="col-md-6 mb-3">
                        <select name="departure_month" class="form-control border-dark">
                            <option value="">Month</option>
                            @for ($i = 1; $i <= 12; $i++)
                                @php $monthName = DateTime::createFromFormat('!m', $i)->format('F'); @endphp
                                <option value="{{ $monthName }}" {{ old('departure_month', $basicInfo->departure_month ?? '') == $monthName ? 'selected' : '' }}>
                                    {{ $monthName }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <select name="departure_year" class="form-control border-dark">
                            <option value="">Year</option>
                            @for ($i = date('Y'); $i >= 1990; $i--)
                                @for ($i = date('Y'); $i >= date('Y') - 100; $i--)
                                    <option value="{{ $i }}" {{ old('departure_year', $basicInfo->departure_yearr ?? '') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <select name="stay_purpose" class="form-control border-dark">
                            <option value="">What was the primary purpose of your stay in Australia?</option>
                            <option value="Holiday" {{ old('stay_purpose', $basicInfo->stay_purpose ?? '') == 'Holiday' ? 'selected' : '' }}>Holiday</option>
                            <option value="Work" {{ old('stay_purpose', $basicInfo->stay_purpose ?? '') == 'Work' ? 'selected' : '' }}>Work</option>
                            <option value="Study" {{ old('stay_purpose', $basicInfo->stay_purpose ?? '') == 'Study' ? 'selected' : '' }}>Study</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <p class="choosing-business-type-text">
                        Did you live in Australia for the full tax year (1 July <?= date('Y') - 1 ?> – 30 June <?= date('Y') ?>)?
                    </p>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input custom-radio" type="radio" name="full_tax_year" id="taxYearYes" value="1"
                                {{ old('full_tax_year', $basicInfo->full_tax_year ?? '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label custom-label" for="taxYearYes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input custom-radio" type="radio" name="full_tax_year" id="taxYearNo" value="0"
                                {{ old('full_tax_year', $basicInfo->full_tax_year ?? '') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label custom-label" for="taxYearNo">No</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Home Address -->
        <div class="row mb-3">
            <p class="choosing-business-type-text">Home Address</p>
            <div class="grin_box_border">
                <div class="col-md-6">
                    <input type="text" name="home_address" class="form-control border-dark" placeholder="Address"
                        value="{{ old('home_address', $basicInfo->home_address ?? '') }}">
                </div>
            </div>
        </div>

        <!-- Postal Address -->
        <div class="row mb-3">
            <p class="choosing-business-type-text">Postal Address</p>
            <div class="grin_box_border">
                <div class="row">
                    <div class="col-md-6">
                        <p class="choosing-business-type-text">Is this the same as your home address?</p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input custom-radio" type="radio" name="same_as_home_address" id="sameAddressYes" value="1"
                                {{ old('same_as_home_address', $basicInfo->same_as_home_address ?? '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label custom-label" for="sameAddressYes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input custom-radio" type="radio" name="same_as_home_address" id="sameAddressNo" value="0"
                                {{ old('same_as_home_address', $basicInfo->same_as_home_address ?? '') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label custom-label" for="sameAddressNo">No</label>
                        </div>
                    </div>
                    <div class="col-md-6" id="postalAddressField" style="display: {{ old('same_as_home_address', $basicInfo->same_as_home_address ?? '') == '0' ? 'block' : 'none' }};">
                        <input type="text" name="postal_address" class="form-control border-dark" placeholder="Postal Address"
                            value="{{ old('postal_address', $basicInfo->postal_address ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Education and Other Debts -->
        <div class="row mb-3">
            <p class="choosing-business-type-text">Education and Other Debts</p>
            <div class="grin_box_border">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="choosing-business-type-text">Do you have any HELP, TSL (now known as AASL), VSL or SSL debt?</p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input custom-radio" type="radio" name="has_education_debt" id="eduDebtYes" value="1"
                                {{ old('has_education_debt', $basicInfo->has_education_debt ?? '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label custom-label" for="eduDebtYes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input custom-radio" type="radio" name="has_education_debt" id="eduDebtNo" value="0"
                                {{ old('has_education_debt', $basicInfo->has_education_debt ?? '') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label custom-label" for="eduDebtNo">No</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="choosing-business-type-text">Do you have any Student Financial Supplement Scheme (SFSS) debt?</p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input custom-radio" type="radio" name="has_sfss_debt" id="sfssDebtYes" value="1"
                                {{ old('has_sfss_debt', $basicInfo->has_sfss_debt ?? '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label custom-label" for="sfssDebtYes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input custom-radio" type="radio" name="has_sfss_debt" id="sfssDebtNo" value="0"
                                {{ old('has_sfss_debt', $basicInfo->has_sfss_debt ?? '') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label custom-label" for="sfssDebtNo">No</label>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <input type="text" name="other_tax_debts" class="form-control border-dark"
                            placeholder="Other tax-related debts?"
                            value="{{ old('other_tax_debts', $basicInfo->other_tax_debts ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Occupation -->
        <div class="row mb-3">
            <p class="choosing-business-type-text">My Occupation</p>
            <div class="grin_box_border py-4 px-3">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="choosing-business-type-text">
                            What do you do for a living?
                        </p>
                        <select class="form-control" name="occupation" id="occupationSelect">
                            <option value="">Choose</option>
                            @php
                                $occupations = [
                                    'Accountant', 'Administrative Assistant', 'Architect', 'Artist', 'Attorney', 'Baker', 'Barber',
                                    'Bartender', 'Biologist', 'Bookkeeper', 'Bus Driver', 'Business Analyst', 'Butcher', 'Carpenter',
                                    'Cashier', 'Chef', 'Civil Engineer', 'Cleaner', 'Clergy Member', 'Construction Worker',
                                    'Consultant', 'Customer Service Representative', 'Data Analyst', 'Data Scientist', 'Delivery Driver',
                                    'Dentist', 'Designer', 'Doctor', 'Driver', 'Editor', 'Electrician', 'Engineer', 'Entrepreneur',
                                    'Event Planner', 'Farmer', 'Fashion Designer', 'Firefighter', 'Flight Attendant', 'Graphic Designer',
                                    'Hairdresser', 'Healthcare Assistant', 'HR Manager', 'Insurance Agent', 'Interpreter', 'IT Support Specialist',
                                    'Journalist', 'Judge', 'Laborer', 'Lawyer', 'Librarian', 'Logistics Coordinator', 'Machine Operator',
                                    'Maintenance Worker', 'Manager', 'Marketing Specialist', 'Massage Therapist', 'Mechanic', 'Medical Assistant',
                                    'Musician', 'Nanny', 'Nurse', 'Nutritionist', 'Office Manager', 'Painter', 'Paramedic', 'Personal Trainer',
                                    'Pharmacist', 'Photographer', 'Physical Therapist', 'Pilot', 'Plumber', 'Police Officer', 'Professor',
                                    'Programmer', 'Project Manager', 'Psychologist', 'Real Estate Agent', 'Receptionist', 'Retail Clerk',
                                    'Sales Associate', 'Sales Manager', 'Scientist', 'Secretary', 'Security Guard', 'Server', 'Social Worker',
                                    'Software Developer', 'Software Engineer', 'Soldier', 'Speech Therapist', 'Student', 'Surgeon',
                                    'Tailor', 'Teacher', 'Technician', 'Therapist', 'Translator', 'Truck Driver', 'Veterinarian',
                                    'Video Editor', 'Waiter', 'Warehouse Worker', 'Web Developer', 'Welder', 'Writer', 'Other'
                                ];
                            @endphp
                            @foreach ($occupations as $job)
                                <option value="{{ $job }}" {{ old('occupation', $basicInfo->occupation ?? '') == $job ? 'selected' : '' }}>
                                    {{ $job }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="row" id="otherOccupationField" style="display: {{ old('occupation', $basicInfo->occupation ?? '') == 'other' ? 'block' : 'none' }};">
                    <div class="col-md-6">
                        <input type="text" name="other_occupation" class="form-control border-dark" placeholder="Specify occupation"
                            value="{{ old('other_occupation', $basicInfo->other_occupation ?? '') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="d-flex justify-content-end mb-5">
        <button type="submit" class="btn navbar_btn">
            {{ isset($basicInfo) ? 'Update' : 'Save' }}
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Citizenship toggle
        const citizenshipRadios = document.querySelectorAll('input[name="australian_citizenship"]');
        const nonCitizenSection = document.getElementById('nonCitizenSection');

        function toggleCitizenSection() {
            const selectedValue = document.querySelector('input[name="australian_citizenship"]:checked')?.value;
            nonCitizenSection.style.display = (selectedValue === '0') ? 'block' : 'none';
        }

        // Visa type toggle
        const visaSelect = document.getElementById('visaSelect');
        const otherVisaInput = document.getElementById('otherVisaInput');

        function toggleVisaInput() {
            otherVisaInput.style.display = (visaSelect.value === 'other') ? 'block' : 'none';
        }

        // Postal address toggle
        const addressRadios = document.querySelectorAll('input[name="same_as_home_address"]');
        const postalAddressField = document.getElementById('postalAddressField');

        function togglePostalAddress() {
            const selectedValue = document.querySelector('input[name="same_as_home_address"]:checked')?.value;
            postalAddressField.style.display = (selectedValue === '0') ? 'block' : 'none';
        }

        // Occupation toggle
        const occupationSelect = document.getElementById('occupationSelect');
        const otherOccupationField = document.getElementById('otherOccupationField');

        function toggleOccupationField() {
            otherOccupationField.style.display = (occupationSelect.value === 'other') ? 'block' : 'none';
        }

        // Set initial states
        toggleCitizenSection();
        toggleVisaInput();
        togglePostalAddress();
        toggleOccupationField();

        // Add event listeners
        citizenshipRadios.forEach(radio => radio.addEventListener('change', toggleCitizenSection));
        if(visaSelect) visaSelect.addEventListener('change', toggleVisaInput);
        addressRadios.forEach(radio => radio.addEventListener('change', togglePostalAddress));
        if(occupationSelect) occupationSelect.addEventListener('change', toggleOccupationField);

        // AJAX обработчик формы
        const form = document.getElementById('basic-info-form');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Removing old error messages
            document.querySelectorAll('.text-danger').forEach(el => el.remove());

            // Show loading indicator
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Processing...';
            submitBtn.disabled = true;

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Successful save
                    showToast('success', data.message);

                    // If this is a new record creation, update the ID in the form
                    if (data.basicInfoId && !form.action.includes('update')) {
                        const newAction = form.action.replace('basic-info.store', `basic-info.update/${data.basicInfoId}`);
                        form.action = newAction;
                        form.querySelector('button[type="submit"]').textContent = 'Update Information';
                    }
                } else {
                    // Handling validation errors
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
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        });

        function showToast(type, message) {
            // Implementation of toast notifications (can use any library)
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

            // Automatic hiding after 5 seconds
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
