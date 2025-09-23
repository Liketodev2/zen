<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Car expenses + parking + tolls</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <p class="choosing-business-type-text">
    Enter car-related expenses for work. This excludes travel to and from work unless carrying heavy tools.
  </p>

  @php
    $carExpenses = old('car_expenses', isset($deductions) ? $deductions->car_expenses ?? [] : []);
    $vehicles = $carExpenses['vehicles'] ?? [];
    $vehicleCount = count($vehicles) > 0 ? count($vehicles) : 1;

    $parkingCost = $carExpenses['parking_cost'] ?? '';
    $tollsCost = $carExpenses['tolls_cost'] ?? '';
    $hasReceipts = $carExpenses['has_receipts'] ?? '';
    $vehicleUse = $carExpenses['vehicle_use'] ?? '';
  @endphp

  <div class="grin_box_border">
    <p class="choosing-business-type-text">
      If you use multiple vehicles, enter each one separately. Use the buttons below to add or remove vehicles.
    </p>

    <div>
      <p class="choosing-business-type-text">Do you use your motor vehicle(s) for work-related travel?</p>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio vehicle-use" type="radio" name="car_expenses[vehicle_use]" id="vehicleUseYes" value="yes" {{ $vehicleUse === 'yes' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="vehicleUseYes">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input custom-radio vehicle-use" type="radio" name="car_expenses[vehicle_use]" id="vehicleUseNo" value="no" {{ $vehicleUse === 'no' ? 'checked' : '' }}>
        <label class="form-check-label custom-label" for="vehicleUseNo">No</label>
      </div>
    </div>

    <div class="vehicle-extra-details mt-3" style="{{ $vehicleUse === 'yes' ? 'block' : 'none' }};">
      <div id="vehicleContainer">
        <!-- Dynamic vehicle blocks inserted here -->
        @for($i = 0; $i < $vehicleCount; $i++)
        <section class="vehicle-block" data-index="{{ $i }}">
          <hr>
          <div class="row">
            <div class="col-md-12 mb-3">
              <p class="choosing-business-type-text">Is the vehicle registered in your name (do you own the vehicle)?</p>
              @php
                $registeredOwner = $vehicles[$i]['registered_owner'] ?? '';
              @endphp
              <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" id="registeredOwnerYes_{{ $i }}" type="radio" name="car_expenses[vehicles][{{ $i }}][registered_owner]" value="yes" {{ $registeredOwner === 'yes' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="registeredOwnerYes_{{ $i }}">Yes</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" id="registeredOwnerNo_{{ $i }}" type="radio" name="car_expenses[vehicles][{{ $i }}][registered_owner]" value="no" {{ $registeredOwner === 'no' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="registeredOwnerNo_{{ $i }}">No</label>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <p class="choosing-business-type-text">In a few words, why do you use your car for work?</p>
              <input type="text" name="car_expenses[vehicles][{{ $i }}][car_usage_reason]" class="form-control border-dark" placeholder="..." value="{{ $vehicles[$i]['car_usage_reason'] ?? '' }}">
            </div>

            <div class="col-md-6 mb-3">
              <p class="choosing-business-type-text">Number of kilometres travelled for work</p>
              <input type="text" name="car_expenses[vehicles][{{ $i }}][work_kilometers]" class="form-control border-dark" placeholder="..." value="{{ $vehicles[$i]['work_kilometers'] ?? '' }}">
            </div>

            <div class="col-md-12 mb-3">
              <p class="choosing-business-type-text">Did you record all your trips in a car logbook for 12 continuous weeks (1 time during the past 5 years)?</p>
              @php
                $hasLogbook = $vehicles[$i]['has_logbook'] ?? '';
              @endphp
              <div class="form-check form-check-inline">
                <input class="form-check-input logbook-radio custom-radio" id="logbookYes_{{ $i }}" type="radio" name="car_expenses[vehicles][{{ $i }}][has_logbook]" value="yes" {{ $hasLogbook === 'yes' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="logbookYes_{{ $i }}">Yes</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input logbook-radio custom-radio" id="logbookNo_{{ $i }}" type="radio" name="car_expenses[vehicles][{{ $i }}][has_logbook]" value="no" {{ $hasLogbook === 'no' ? 'checked' : '' }}>
                <label class="form-check-label custom-label" for="logbookNo_{{ $i }}">No</label>
              </div>
            </div>

            <div class="col-md-12 mb-3 logbook-extra" style="{{ $hasLogbook === 'yes' ? 'block' : 'none' }};">
              <div class="row gx-3 gy-2">
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="startOdometer_{{ $i }}">Start of logbook period odometer reading</label>
                  <input type="number" id="startOdometer_{{ $i }}" name="car_expenses[vehicles][{{ $i }}][start_odometer]" class="form-control border-dark" placeholder="..." value="{{ $vehicles[$i]['start_odometer'] ?? '' }}">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="endOdometer_{{ $i }}">End of logbook period odometer reading</label>
                  <input type="number" id="endOdometer_{{ $i }}" name="car_expenses[vehicles][{{ $i }}][end_odometer]" class="form-control border-dark" placeholder="..." value="{{ $vehicles[$i]['end_odometer'] ?? '' }}">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="totalWorkKilometers_{{ $i }}">Total number of work related kilometres in logbook</label>
                  <input type="number" id="totalWorkKilometers_{{ $i }}" name="car_expenses[vehicles][{{ $i }}][total_work_kilometers]" class="form-control border-dark" placeholder="..." value="{{ $vehicles[$i]['total_work_kilometers'] ?? '' }}">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="fuelReceipts_{{ $i }}">Total of fuel receipts expense for the year</label>
                  <input type="number" step="0.01" id="fuelReceipts_{{ $i }}" name="car_expenses[vehicles][{{ $i }}][fuel_receipts]" class="form-control border-dark" placeholder="00.00$" value="{{ $vehicles[$i]['fuel_receipts'] ?? '' }}">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="registrationExpense_{{ $i }}">Registration expense for the year</label>
                  <input type="number" step="0.01" id="registrationExpense_{{ $i }}" name="car_expenses[vehicles][{{ $i }}][registration_expense]" class="form-control border-dark" placeholder="00.00$" value="{{ $vehicles[$i]['registration_expense'] ?? '' }}">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="serviceExpenses_{{ $i }}">Service expenses for the year</label>
                  <input type="number" step="0.01" id="serviceExpenses_{{ $i }}" name="car_expenses[vehicles][{{ $i }}][service_expenses]" class="form-control border-dark" placeholder="00.00$" value="{{ $vehicles[$i]['service_expenses'] ?? '' }}">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="insuranceExpenses_{{ $i }}">Insurance expenses for the year</label>
                  <input type="number" step="0.01" id="insuranceExpenses_{{ $i }}" name="car_expenses[vehicles][{{ $i }}][insurance_expenses]" class="form-control border-dark" placeholder="00.00$" value="{{ $vehicles[$i]['insurance_expenses'] ?? '' }}">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="otherVehicleExpenses_{{ $i }}">Other vehicle expenses for the year</label>
                  <input type="number" step="0.01" id="otherVehicleExpenses_{{ $i }}" name="car_expenses[vehicles][{{ $i }}][other_vehicle_expenses]" class="form-control border-dark" placeholder="00.00$" value="{{ $vehicles[$i]['other_vehicle_expenses'] ?? '' }}">
                </div>

                <div class="col-md-6 mt-3">
                  <label class="choosing-business-type-text" for="purchaseType_{{ $i }}">Did you buy the vehicle outright, borrow money to buy it, take out a hire purchase or is it under a lease agreement?</label>
                  @php
                    $purchaseType = $vehicles[$i]['purchase_type'] ?? '';
                  @endphp
                  <select id="purchaseType_{{ $i }}" name="car_expenses[vehicles][{{ $i }}][purchase_type]" class="form-select border-dark">
                    <option value="">Choose</option>
                    <option value="Purchased outright" {{ $purchaseType === 'Purchased outright' ? 'selected' : '' }}>Purchased outright</option>
                    <option value="Borrowed money or hire purchase" {{ $purchaseType === 'Borrowed money or hire purchase' ? 'selected' : '' }}>Borrowed money or hire purchase</option>
                    <option value="Lease agreement" {{ $purchaseType === 'Lease agreement' ? 'selected' : '' }}>Lease agreement</option>
                  </select>
                </div>

                @php
                  $purchaseDateOutright = $vehicles[$i]['purchase_date_outright'] ?? [];
                  $purchaseDateBorrowed = $vehicles[$i]['purchase_date_borrowed'] ?? [];
                  $purchaseDateLease = $vehicles[$i]['purchase_date_lease'] ?? [];
                  $purchasePrice = $vehicles[$i]['purchase_price'] ?? '';
                  $totalYearlyInterest = $vehicles[$i]['total_yearly_interest'] ?? '';
                @endphp

                <div class="purchase-details purchase-purchased_outright mt-3" style="{{ $purchaseType === 'purchased_outright' ? 'block' : 'none' }};">
                  <label class="choosing-business-type-text" for="purchaseDate_{{ $i }}_outright">Purchase date of vehicle</label>
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label class="choosing-business-type-text">Day</label>
                      <select name="car_expenses[vehicles][{{ $i }}][purchase_date_outright][day]" class="form-control border-dark">
                        <option value="">Day</option>
                        @for ($d = 1; $d <= 31; $d++)
                          <option value="{{ $d }}" {{ ($purchaseDateOutright['day'] ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label class="choosing-business-type-text">Month</label>
                      <select name="car_expenses[vehicles][{{ $i }}][purchase_date_outright][month]" class="form-control border-dark">
                        <option value="">Month</option>
                        @for ($m = 1; $m <= 12; $m++)
                          @php $monthName = DateTime::createFromFormat('!m', $m)->format('F'); @endphp
                          <option value="{{ $monthName }}" {{ ($purchaseDateOutright['month'] ?? '') == $monthName ? 'selected' : '' }}>{{ $monthName }}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label class="choosing-business-type-text">Year</label>
                      <select name="car_expenses[vehicles][{{ $i }}][purchase_date_outright][year]" class="form-control border-dark">
                        <option value="">Year</option>
                        @for ($y = date('Y'); $y >= 1990; $y--)
                          <option value="{{ $y }}" {{ ($purchaseDateOutright['year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                      </select>
                    </div>
                  </div>
                  <label class="choosing-business-type-text mt-2" for="purchasePrice_{{ $i }}_outright">Purchase price of vehicle</label>
                  <input type="number" step="0.01" id="purchasePrice_{{ $i }}_outright" name="car_expenses[vehicles][{{ $i }}][purchase_price]" class="form-control border-dark" placeholder="00.00$" value="{{ $purchasePrice }}">
                </div>

                <div class="purchase-details purchase-borrowed_or_hire_purchase mt-3" style="{{ $purchaseType === 'borrowed_or_hire_purchase' ? 'block' : 'none' }};">
                  <label class="choosing-business-type-text" for="purchaseDate_{{ $i }}_borrowed">Purchase date of vehicle</label>
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label class="choosing-business-type-text">Day</label>
                      <select name="car_expenses[vehicles][{{ $i }}][purchase_date_borrowed][day]" class="form-control border-dark">
                        <option value="">Day</option>
                        @for ($d = 1; $d <= 31; $d++)
                          <option value="{{ $d }}" {{ ($purchaseDateBorrowed['day'] ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label class="choosing-business-type-text">Month</label>
                      <select name="car_expenses[vehicles][{{ $i }}][purchase_date_borrowed][month]" class="form-control border-dark">
                        <option value="">Month</option>
                        @for ($m = 1; $m <= 12; $m++)
                          @php $monthName = DateTime::createFromFormat('!m', $m)->format('F'); @endphp
                          <option value="{{ $monthName }}" {{ ($purchaseDateBorrowed['month'] ?? '') == $monthName ? 'selected' : '' }}>{{ $monthName }}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label class="choosing-business-type-text">Year</label>
                      <select name="car_expenses[vehicles][{{ $i }}][purchase_date_borrowed][year]" class="form-control border-dark">
                        <option value="">Year</option>
                        @for ($y = date('Y'); $y >= 1990; $y--)
                          <option value="{{ $y }}" {{ ($purchaseDateBorrowed['year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                      </select>
                    </div>
                  </div>
                  <div class="row gx-2">
                    <div class="col-md-6">
                      <label class="choosing-business-type-text mt-2" for="purchasePrice_{{ $i }}_borrowed">Purchase price of vehicle</label>
                      <input type="number" step="0.01" id="purchasePrice_{{ $i }}_borrowed" name="car_expenses[vehicles][{{ $i }}][purchase_price]" class="form-control border-dark" placeholder="00.00$" value="{{ $purchasePrice }}">
                    </div>
                    <div class="col-md-6">
                      <label class="choosing-business-type-text mt-2" for="totalYearlyInterest_{{ $i }}">Total yearly interest paid</label>
                      <input type="number" step="0.01" id="totalYearlyInterest_{{ $i }}" name="car_expenses[vehicles][{{ $i }}][total_yearly_interest]" class="form-control border-dark" placeholder="00.00$" value="{{ $totalYearlyInterest }}">
                    </div>
                  </div>
                </div>

                <div class="purchase-details purchase-lease_agreement mt-3" style="{{ $purchaseType === 'lease_agreement' ? 'block' : 'none' }};">
                  <label class="choosing-business-type-text" for="purchaseDate_{{ $i }}_lease">Purchase date of vehicle</label>
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label class="choosing-business-type-text">Day</label>
                      <select name="car_expenses[vehicles][{{ $i }}][purchase_date_lease][day]" class="form-control border-dark">
                        <option value="">Day</option>
                        @for ($d = 1; $d <= 31; $d++)
                          <option value="{{ $d }}" {{ ($purchaseDateLease['day'] ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label class="choosing-business-type-text">Month</label>
                      <select name="car_expenses[vehicles][{{ $i }}][purchase_date_lease][month]" class="form-control border-dark">
                        <option value="">Month</option>
                        @for ($m = 1; $m <= 12; $m++)
                          @php $monthName = DateTime::createFromFormat('!m', $m)->format('F'); @endphp
                          <option value="{{ $monthName }}" {{ ($purchaseDateLease['month'] ?? '') == $monthName ? 'selected' : '' }}>{{ $monthName }}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label class="choosing-business-type-text">Year</label>
                      <select name="car_expenses[vehicles][{{ $i }}][purchase_date_lease][year]" class="form-control border-dark">
                        <option value="">Year</option>
                        @for ($y = date('Y'); $y >= 1990; $y--)
                          <option value="{{ $y }}" {{ ($purchaseDateLease['year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                      </select>
                    </div>
                  </div>
                  <label class="choosing-business-type-text mt-2" for="totalYearlyInterest_{{ $i }}_lease">Total yearly interest paid</label>
                  <input type="number" step="0.01" id="totalYearlyInterest_{{ $i }}_lease" name="car_expenses[vehicles][{{ $i }}][total_yearly_interest]" class="form-control border-dark" placeholder="00.00$" value="{{ $totalYearlyInterest }}">
                </div>
              </div>
            </div>
          </div>
        </section>
        @endfor
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <button type="button" class="btn btn_add btn_add_vehicle">
            <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another vehicle
          </button>
          <button type="button" class="btn btn_delete btn_delete_vehicle">
            Delete vehicle
          </button>
        </div>
      </div>
    </div>
  </div>

  <p class="choosing-business-type-text mt-4">Parking & Tolls</p>

  <div class="grin_box_border">
    <div class="row">
      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">Total cost of parking</p>
        <input type="number" name="car_expenses[parking_cost]" class="form-control border-dark" placeholder="00.00$" value="{{ $parkingCost }}" />
      </div>

      <div class="col-md-6 mb-3">
        <p class="choosing-business-type-text">Total cost of tolls</p>
        <input type="number" name="car_expenses[tolls_cost]" class="form-control border-dark" placeholder="00.00$" value="{{ $tollsCost }}" />
      </div>

      <div class="col-md-12 mb-3">
        <p class="choosing-business-type-text">Do you have receipts or logs for all parking and tolls claimed?</p>
        <div class="form-check form-check-inline">
          <input class="form-check-input custom-radio" type="radio" name="car_expenses[has_receipts]" id="hasReceiptsYes" value="yes" {{ $hasReceipts === 'yes' ? 'checked' : '' }}>
          <label class="form-check-label custom-label" for="hasReceiptsYes">Yes</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input custom-radio" type="radio" name="car_expenses[has_receipts]" id="hasReceiptsNo" value="no" {{ $hasReceipts === 'no' ? 'checked' : '' }}>
          <label class="form-check-label custom-label" for="hasReceiptsNo">No</label>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const vehicleRadios = document.querySelectorAll('input[name="car_expenses[vehicle_use]"]');
    const extraDetails = document.querySelector(".vehicle-extra-details");
    const vehicleContainer = document.getElementById("vehicleContainer");
    const addVehicleBtn = document.querySelector(".btn_add_vehicle");
    const deleteVehicleBtn = document.querySelector(".btn_delete_vehicle");

    function toggleVehicleBlock() {
      const selected = document.querySelector('input[name="car_expenses[vehicle_use]"]:checked');
      extraDetails.style.display = (selected && selected.value === "yes") ? "block" : "none";
    }

    vehicleRadios.forEach(radio => radio.addEventListener("change", toggleVehicleBlock));
    toggleVehicleBlock();

    function createDateSelects(namePrefix, index) {
      const daysOptions = Array.from({length:31}, (_,i) => `<option value="${i+1}">${i+1}</option>`).join('');
      const monthsNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      const monthsOptions = monthsNames.map((m,i) => `<option value="${i+1}">${m}</option>`).join('');
      const currentYear = new Date().getFullYear();
      const yearsOptions = [];
      for(let y = currentYear; y >= 1990; y--) {
        yearsOptions.push(`<option value="${y}">${y}</option>`);
      }

      return `
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="choosing-business-type-text">Day</label>
            <select name="${namePrefix}_day_${index}" class="form-control border-dark">
              <option value="">Day</option>
              ${daysOptions}
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="choosing-business-type-text">Month</label>
            <select name="${namePrefix}_month_${index}" class="form-control border-dark">
              <option value="">Month</option>
              ${monthsOptions}
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="choosing-business-type-text">Year</label>
            <select name="${namePrefix}_year_${index}" class="form-control border-dark">
              <option value="">Year</option>
              ${yearsOptions.join('')}
            </select>
          </div>
        </div>
      `;
    }

    function createVehicleBlock(index) {
      return `
        <section class="vehicle-block" data-index="${index}">
          <hr>
          <div class="row">
            <div class="col-md-12 mb-3">
              <p class="choosing-business-type-text">Is the vehicle registered in your name (do you own the vehicle)?</p>
              <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" id="registeredOwnerYes_${index}" type="radio" name="car_expenses[vehicles][${index}][registered_owner]" value="yes">
                <label class="form-check-label custom-label" for="registeredOwnerYes_${index}">Yes</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input custom-radio" id="registeredOwnerNo_${index}" type="radio" name="car_expenses[vehicles][${index}][registered_owner]" value="no">
                <label class="form-check-label custom-label" for="registeredOwnerNo_${index}">No</label>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <p class="choosing-business-type-text">In a few words, why do you use your car for work?</p>
              <input type="text" name="car_expenses[vehicles][${index}][car_usage_reason]" class="form-control border-dark" placeholder="...">
            </div>

            <div class="col-md-6 mb-3">
              <p class="choosing-business-type-text">Number of kilometres travelled for work</p>
              <input type="text" name="car_expenses[vehicles][${index}][work_kilometers]" class="form-control border-dark" placeholder="...">
            </div>

            <div class="col-md-12 mb-3">
              <p class="choosing-business-type-text">Did you record all your trips in a car logbook for 12 continuous weeks (1 time during the past 5 years)?</p>
              <div class="form-check form-check-inline">
                <input class="form-check-input logbook-radio custom-radio" id="logbookYes_${index}" type="radio" name="car_expenses[vehicles][${index}][has_logbook]" value="yes">
                <label class="form-check-label custom-label" for="logbookYes_${index}">Yes</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input logbook-radio custom-radio" id="logbookNo_${index}" type="radio" name="car_expenses[vehicles][${index}][has_logbook]" value="no">
                <label class="form-check-label custom-label" for="logbookNo_${index}">No</label>
              </div>
            </div>

            <div class="col-md-12 mb-3 logbook-extra" style="display: none;">
              <div class="row gx-3 gy-2">
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="startOdometer_${index}">Start of logbook period odometer reading</label>
                  <input type="number" id="startOdometer_${index}" name="car_expenses[vehicles][${index}][start_odometer]" class="form-control border-dark" placeholder="...">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="endOdometer_${index}">End of logbook period odometer reading</label>
                  <input type="number" id="endOdometer_${index}" name="car_expenses[vehicles][${index}][end_odometer]" class="form-control border-dark" placeholder="...">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="totalWorkKilometers_${index}">Total number of work related kilometres in logbook</label>
                  <input type="number" id="totalWorkKilometers_${index}" name="car_expenses[vehicles][${index}][total_work_kilometers]" class="form-control border-dark" placeholder="...">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="fuelReceipts_${index}">Total of fuel receipts expense for the year</label>
                  <input type="number" step="0.01" id="fuelReceipts_${index}" name="car_expenses[vehicles][${index}][fuel_receipts]" class="form-control border-dark" placeholder="00.00$">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="registrationExpense_${index}">Registration expense for the year</label>
                  <input type="number" step="0.01" id="registrationExpense_${index}" name="car_expenses[vehicles][${index}][registration_expense]" class="form-control border-dark" placeholder="00.00$">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="serviceExpenses_${index}">Service expenses for the year</label>
                  <input type="number" step="0.01" id="serviceExpenses_${index}" name="car_expenses[vehicles][${index}][service_expenses]" class="form-control border-dark" placeholder="00.00$">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="insuranceExpenses_${index}">Insurance expenses for the year</label>
                  <input type="number" step="0.01" id="insuranceExpenses_${index}" name="car_expenses[vehicles][${index}][insurance_expenses]" class="form-control border-dark" placeholder="00.00$">
                </div>
                <div class="col-md-6">
                  <label class="choosing-business-type-text" for="otherVehicleExpenses_${index}">Other vehicle expenses for the year</label>
                  <input type="number" step="0.01" id="otherVehicleExpenses_${index}" name="car_expenses[vehicles][${index}][other_vehicle_expenses]" class="form-control border-dark" placeholder="00.00$">
                </div>

                <div class="col-md-6 mt-3">
                  <label class="choosing-business-type-text" for="purchaseType_${index}">Did you buy the vehicle outright, borrow money to buy it, take out a hire purchase or is it under a lease agreement?</label>
                  <select id="purchaseType_${index}" name="car_expenses[vehicles][${index}][purchase_type]" class="form-select border-dark">
                    <option value="">Choose</option>
                    <option value="purchased_outright">Purchased outright</option>
                    <option value="borrowed_or_hire_purchase">Borrowed money or hire purchase</option>
                    <option value="lease_agreement">Lease agreement</option>
                  </select>
                </div>

                <div class="purchase-details purchase-purchased_outright mt-3" style="display:none;">
                  <label class="choosing-business-type-text" for="purchaseDate_${index}_outright">Purchase date of vehicle</label>
                  ${createDateSelects('car_expenses[vehicles][' + index + '][purchase_date_outright]', index)}
                  <label class="choosing-business-type-text mt-2" for="purchasePrice_${index}_outright">Purchase price of vehicle</label>
                  <input type="number" step="0.01" id="purchasePrice_${index}_outright" name="car_expenses[vehicles][${index}][purchase_price]" class="form-control border-dark" placeholder="00.00$">
                </div>

                <div class="purchase-details purchase-borrowed_or_hire_purchase mt-3" style="display:none;">
                  <label class="choosing-business-type-text" for="purchaseDate_${index}_borrowed">Purchase date of vehicle</label>
                  ${createDateSelects('car_expenses[vehicles][' + index + '][purchase_date_borrowed]', index)}
                  <div class="row gx-2">
                    <div class="col-md-6">
                      <label class="choosing-business-type-text mt-2" for="purchasePrice_${index}_borrowed">Purchase price of vehicle</label>
                      <input type="number" step="0.01" id="purchasePrice_${index}_borrowed" name="car_expenses[vehicles][${index}][purchase_price]" class="form-control border-dark" placeholder="00.00$">
                    </div>
                    <div class="col-md-6">
                      <label class="choosing-business-type-text mt-2" for="totalYearlyInterest_${index}">Total yearly interest paid</label>
                      <input type="number" step="0.01" id="totalYearlyInterest_${index}" name="car_expenses[vehicles][${index}][total_yearly_interest]" class="form-control border-dark" placeholder="00.00$">
                    </div>
                  </div>
                </div>

                <div class="purchase-details purchase-lease_agreement mt-3" style="display:none;">
                  <label class="choosing-business-type-text" for="purchaseDate_${index}_lease">Purchase date of vehicle</label>
                  ${createDateSelects('car_expenses[vehicles][' + index + '][purchase_date_lease]', index)}
                  <label class="choosing-business-type-text mt-2" for="totalYearlyInterest_${index}_lease">Total yearly interest paid</label>
                  <input type="number" step="0.01" id="totalYearlyInterest_${index}_lease" name="car_expenses[vehicles][${index}][total_yearly_interest]" class="form-control border-dark" placeholder="00.00$">
                </div>
              </div>
            </div>
          </div>
        </section>
      `;
    }

    function initPurchaseTypeListeners() {
      const blocks = vehicleContainer.querySelectorAll(".vehicle-block");

      blocks.forEach(block => {
        const select = block.querySelector('select[name^="car_expenses[vehicles]"]');
        if (!select) return;

        const showRelevantDetails = () => {
          const value = select.value;
          const detailsBlocks = block.querySelectorAll(".purchase-details");
          detailsBlocks.forEach(div => div.style.display = "none");
          if (!value) return;
          const toShow = block.querySelector(`.purchase-${value}`);
          if (toShow) toShow.style.display = "block";
        };

        select.addEventListener("change", showRelevantDetails);

        showRelevantDetails();
      });
    }

    function initLogbookListeners() {
      const blocks = vehicleContainer.querySelectorAll(".vehicle-block");
      blocks.forEach(block => {
        const radios = block.querySelectorAll(".logbook-radio");
        const logbookExtra = block.querySelector(".logbook-extra");
        if (!logbookExtra) return;

        const toggleLogbookExtra = () => {
          const selected = [...radios].find(r => r.checked);
          logbookExtra.style.display = selected && selected.value === "yes" ? "block" : "none";
        };

        radios.forEach(r => r.addEventListener("change", toggleLogbookExtra));
        toggleLogbookExtra();
      });
    }

    addVehicleBtn.addEventListener("click", () => {
      const index = vehicleContainer.querySelectorAll(".vehicle-block").length;
      vehicleContainer.insertAdjacentHTML("beforeend", createVehicleBlock(index));
      initLogbookListeners();
      initPurchaseTypeListeners();
    });

    deleteVehicleBtn.addEventListener("click", () => {
      const blocks = vehicleContainer.querySelectorAll(".vehicle-block");
      if (blocks.length > 1) blocks[blocks.length - 1].remove();
    });

    // Initialize listeners for existing blocks
    initLogbookListeners();
    initPurchaseTypeListeners();
  });
</script>
