<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Gifts / Donations</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <div id="donationContainer">
        <p class="choosing-business-type-text">
            To be eligible, donations must be made to a registered charity and you should have a tax deductible gift receipt for each donation.
        </p>

        @php
            $gifts = old('gifts', isset($deductions) ? $deductions->gifts ?? [] : []);
            $numericItems = array_filter($gifts, fn($key) => is_int($key), ARRAY_FILTER_USE_KEY);
            $giftCount = max(count($numericItems), 1);
        @endphp

        @for($i = 0; $i < $giftCount; $i++)
        <section class="grin_box_border mb-3 donation-block" data-index="{{ $i }}">
            <div class="row mb-2">
                <div class="col-md-12">
                    <p class="choosing-business-type-text">Do you have a receipt for all donations that you are claiming?</p>
                    @php
                        $receiptValue = old("gifts.$i.has_receipt", $gifts[$i]['has_receipt'] ?? '');
                    @endphp
                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input custom-radio"
                            type="radio"
                            name="gifts[{{ $i }}][has_receipt]"
                            id="has_receipt_yes_{{ $i }}"
                            value="yes"
                            {{ $receiptValue === 'yes' ? 'checked' : '' }}
                        >
                        <label class="form-check-label custom-label" for="has_receipt_yes_{{ $i }}">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input custom-radio"
                            type="radio"
                            name="gifts[{{ $i }}][has_receipt]"
                            id="has_receipt_no_{{ $i }}"
                            value="no"
                            {{ $receiptValue === 'no' ? 'checked' : '' }}
                        >
                        <label class="form-check-label custom-label" for="has_receipt_no_{{ $i }}">No</label>
                    </div>
                </div>
            </div>

            <div class="row mb-2 align-items-end">
                <div class="col-md-6 mb-2">
                    <label class="choosing-business-type-text">Charity name or organisation</label>
                    <input
                        type="text"
                        name="gifts[{{ $i }}][charity_name]"
                        class="form-control border-dark"
                        placeholder="..."
                        value="{{ old("gifts.$i.charity_name", $gifts[$i]['charity_name'] ?? '') }}"
                    >
                </div>
                <div class="col-md-6 mb-2">
                    <label class="choosing-business-type-text">Total donation to this organisation</label>
                    <input
                        type="number"
                        name="gifts[{{ $i }}][donation_amount]"
                        class="form-control border-dark donation-input"
                        placeholder="00.00$"
                        step="0.01"
                        value="{{ old("gifts.$i.donation_amount", $gifts[$i]['donation_amount'] ?? '') }}"
                    >
                </div>
            </div>
        </section>
        @endfor
    </div>

    <div class="mb-3">
        <button type="button" class="btn btn_add btn_add_donation">
            <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another donation
        </button>
        <button type="button" class="btn btn_delete btn_delete_donation">
            Delete donation
        </button>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const donationContainer = document.getElementById("donationContainer");
    const addBtn = document.querySelector(".btn_add_donation");
    const deleteBtn = document.querySelector(".btn_delete_donation");

    const newBlockTemplate = `
    <section class="grin_box_border mb-3 donation-block" data-index="__INDEX__">
        <div class="row mb-2">
            <div class="col-md-12">
                <p class="choosing-business-type-text">Do you have a receipt for all donations that you are claiming?</p>
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input custom-radio"
                        type="radio"
                        name="gifts[__INDEX__][has_receipt]"
                        id="has_receipt_yes___INDEX__"
                        value="yes"
                    >
                    <label class="form-check-label custom-label" for="has_receipt_yes___INDEX__">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input custom-radio"
                        type="radio"
                        name="gifts[__INDEX__][has_receipt]"
                        id="has_receipt_no___INDEX__"
                        value="no"
                    >
                    <label class="form-check-label custom-label" for="has_receipt_no___INDEX__">No</label>
                </div>
            </div>
        </div>
        <div class="row mb-2 align-items-end">
            <div class="col-md-6 mb-2">
                <label class="choosing-business-type-text">Charity name or organisation</label>
                <input
                    type="text"
                    name="gifts[__INDEX__][charity_name]"
                    class="form-control border-dark"
                    placeholder="..."
                    value=""
                >
            </div>
            <div class="col-md-6 mb-2">
                <label class="choosing-business-type-text">Total donation to this organisation</label>
                <input
                    type="number"
                    name="gifts[__INDEX__][donation_amount]"
                    class="form-control border-dark donation-input"
                    placeholder="00.00$"
                    step="0.01"
                    value=""
                >
            </div>
        </div>
    </section>
    `;

    function refreshIndices() {
        const blocks = donationContainer.querySelectorAll(".donation-block");
        blocks.forEach((block, index) => {
            block.dataset.index = index;
            block.querySelectorAll('input').forEach(el => {
                if (el.name) {
                    el.name = el.name.replace(/gifts\[\d+\]/, `gifts[${index}]`);
                }
                if (el.id && el.id.includes("has_receipt_yes")) {
                    el.id = `has_receipt_yes_${index}`;
                    const label = block.querySelector(`label[for^="has_receipt_yes"]`);
                    if(label) label.setAttribute('for', el.id);
                }
                if (el.id && el.id.includes("has_receipt_no")) {
                    el.id = `has_receipt_no_${index}`;
                    const label = block.querySelector(`label[for^="has_receipt_no"]`);
                    if(label) label.setAttribute('for', el.id);
                }
            });
        });
    }

    addBtn.addEventListener("click", () => {
        const newIndex = donationContainer.querySelectorAll(".donation-block").length;
        const newBlockHTML = newBlockTemplate.replace(/__INDEX__/g, newIndex);
        donationContainer.insertAdjacentHTML('beforeend', newBlockHTML);
        refreshIndices();
    });

    deleteBtn.addEventListener("click", () => {
        const blocks = donationContainer.querySelectorAll(".donation-block");
        if (blocks.length > 1) {
            blocks[blocks.length - 1].remove();
            refreshIndices();
        }
    });

    refreshIndices();
});
</script>
