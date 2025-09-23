<section>
    <div class="mt-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="form_title">Interest</h4>
            <img src="{{ asset('img/icons/help.png') }}" alt="Help">
        </div>
        <p class="choosing-business-type-text">
            Enter the interest you received from all bank accounts. Find this on your bank statements or online banking. If you have joint (shared) accounts, ensure you adjust the number of account holders.
        </p>
        <div class="grin_box_border">
            <div id="interestContainer">
                @php
                    $interests = old('interests', isset($incomes) ? $incomes->interests ?? [] : []);
                    $interestCount = max(count($interests), 1);
                @endphp

                @for($i = 0; $i < $interestCount; $i++)
                <section class="interest-block" data-index="{{ $i }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Number of Account Holders</p>
                            <input 
                                type="number" 
                                name="interests[{{ $i }}][account_holders]" 
                                class="form-control border-dark" 
                                placeholder="1"
                                value="{{ old("interests.$i.account_holders", $interests[$i]['account_holders'] ?? '') }}"
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Total Tax Withheld from interest (if any)</p>
                            <input 
                                type="number" 
                                name="interests[{{ $i }}][tax_withheld]" 
                                class="form-control border-dark" 
                                placeholder="00.00$"
                                value="{{ old("interests.$i.tax_withheld", $interests[$i]['tax_withheld'] ?? '') }}"
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Total Interest</p>
                            <input 
                                type="number" 
                                name="interests[{{ $i }}][total_interest]" 
                                class="form-control border-dark" 
                                placeholder="00.00$"
                                value="{{ old("interests.$i.total_interest", $interests[$i]['total_interest'] ?? '') }}"
                            >
                        </div>
                    </div>
                </section>
                @endfor
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <button type="button" class="btn btn_add btn_add_interest">
                        <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another account
                    </button>
                    <button type="button" class="btn btn_delete btn_delete_interest">
                        Delete account
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const interestContainer = document.getElementById("interestContainer");
    const addInterestBtn = document.querySelector(".btn_add_interest");
    const deleteInterestBtn = document.querySelector(".btn_delete_interest");

    const interestTemplate = `
    <section class="interest-block" data-index="__INDEX__">
        <div class="row">
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Number of Account Holders</p>
                <input 
                    type="number" 
                    name="interests[__INDEX__][account_holders]" 
                    class="form-control border-dark" 
                    placeholder="1"
                >
            </div>
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Total Tax Withheld from interest (if any)</p>
                <input 
                    type="number" 
                    name="interests[__INDEX__][tax_withheld]" 
                    class="form-control border-dark" 
                    placeholder="00.00$"
                >
            </div>
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Total Interest</p>
                <input 
                    type="number" 
                    name="interests[__INDEX__][total_interest]" 
                    class="form-control border-dark" 
                    placeholder="00.00$"
                >
            </div>
        </div>
    </section>
    `;

    function refreshInterestIndices() {
        const blocks = interestContainer.querySelectorAll(".interest-block");
        blocks.forEach((block, index) => {
            block.dataset.index = index;
            block.querySelectorAll("input").forEach(input => {
                input.name = input.name.replace(/interests\[\d+\]/, `interests[${index}]`);
            });
        });
    }

    addInterestBtn.addEventListener("click", () => {
        const newIndex = interestContainer.querySelectorAll(".interest-block").length;
        const newBlockHTML = interestTemplate.replace(/__INDEX__/g, newIndex);
        interestContainer.insertAdjacentHTML("beforeend", newBlockHTML);
        refreshInterestIndices();
    });

    deleteInterestBtn.addEventListener("click", () => {
        const blocks = interestContainer.querySelectorAll(".interest-block");
        if (blocks.length > 1) {
            blocks[blocks.length - 1].remove();
            refreshInterestIndices();
        }
    });
});
</script>