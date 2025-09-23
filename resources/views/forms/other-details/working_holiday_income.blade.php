@php
    $workingIncome = $others->working_holiday_maker_net_income ?? [];
@endphp

<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Working Holiday Maker Net Income</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>
    <div class="grin_box_border p-3 mb-5">

    <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text mb-2" for="working_holiday_income">
            Working holiday maker net income
        </label>
        <input type="number" step="0.01" class="form-control" id="working_holiday_income"
               name="working_holiday_maker_net_income[working_holiday_income]" value="{{ $workingIncome['working_holiday_income'] ?? ''  }}"
              placeholder="00.00$">
    </div>

</div>
</section>
