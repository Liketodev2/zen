<section>
    <div class="mt-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="form_title">Dividends</h4>
            <img src="{{ asset('img/icons/help.png') }}" alt="Help">
        </div>
        <p class="choosing-business-type-text">
            Enter the dividends you received from all investments. Find this on your dividend statements or online share accounts. If you have joint (shared) ownership, ensure you adjust the number of account holders.
        </p>
        <div class="grin_box_border">
            <p class="choosing-business-type-text">
                Enter each investment separately. If you had more than one investment paying dividends, click the "add another dividend" button below.
            </p>
            <div id="dividendsContainer">
                @php
                    $dividends = old('dividends', isset($incomes) ? $incomes->dividends ?? [] : []);
                    $dividendCount = max(count($dividends), 1);
                @endphp

                @for($i = 0; $i < $dividendCount; $i++)
                <section class="dividends-block" data-index="{{ $i }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Number of Account Holders</p>
                            <input 
                                type="number" 
                                name="dividends[{{ $i }}][account_holders]" 
                                class="form-control border-dark" 
                                placeholder="1"
                                value="{{ old("dividends.$i.account_holders", $dividends[$i]['account_holders'] ?? '') }}"
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Total unfranked amount</p>
                            <input 
                                type="number" 
                                name="dividends[{{ $i }}][unfranked_amount]" 
                                class="form-control border-dark" 
                                placeholder="00.00$"
                                value="{{ old("dividends.$i.unfranked_amount", $dividends[$i]['unfranked_amount'] ?? '') }}"
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Total franked amount</p>
                            <input 
                                type="number" 
                                name="dividends[{{ $i }}][franked_amount]" 
                                class="form-control border-dark" 
                                placeholder="00.00$"
                                value="{{ old("dividends.$i.franked_amount", $dividends[$i]['franked_amount'] ?? '') }}"
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Total franking credits</p>
                            <input 
                                type="number" 
                                name="dividends[{{ $i }}][franking_credits]" 
                                class="form-control border-dark" 
                                placeholder="00.00$"
                                value="{{ old("dividends.$i.franking_credits", $dividends[$i]['franking_credits'] ?? '') }}"
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="choosing-business-type-text">Total tax amounts withheld</p>
                            <input 
                                type="number" 
                                name="dividends[{{ $i }}][tax_withheld]" 
                                class="form-control border-dark" 
                                placeholder="00.00$"
                                value="{{ old("dividends.$i.tax_withheld", $dividends[$i]['tax_withheld'] ?? '') }}"
                            >
                        </div>
                    </div>
                </section>
                @endfor
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <button type="button" class="btn btn_add btn_add_dividend">
                        <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Add another dividend
                    </button>
                    <button type="button" class="btn btn_delete btn_delete_dividend">
                        Delete dividend
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("dividendsContainer");
    const addBtn = document.querySelector(".btn_add_dividend");
    const deleteBtn = document.querySelector(".btn_delete_dividend");

    // Template for new dividend block
    const newBlockTemplate = `
    <section class="dividends-block" data-index="__INDEX__">
        <div class="row">
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Number of Account Holders</p>
                <input 
                    type="number" 
                    name="dividends[__INDEX__][account_holders]" 
                    class="form-control border-dark" 
                    placeholder="1"
                >
            </div>
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Total unfranked amount</p>
                <input 
                    type="number" 
                    name="dividends[__INDEX__][unfranked_amount]" 
                    class="form-control border-dark" 
                    placeholder="00.00$"
                >
            </div>
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Total franked amount</p>
                <input 
                    type="number" 
                    name="dividends[__INDEX__][franked_amount]" 
                    class="form-control border-dark" 
                    placeholder="00.00$"
                >
            </div>
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Total franking credits</p>
                <input 
                    type="number" 
                    name="dividends[__INDEX__][franking_credits]" 
                    class="form-control border-dark" 
                    placeholder="00.00$"
                >
            </div>
            <div class="col-md-6 mb-3">
                <p class="choosing-business-type-text">Total tax amounts withheld</p>
                <input 
                    type="number" 
                    name="dividends[__INDEX__][tax_withheld]" 
                    class="form-control border-dark" 
                    placeholder="00.00$"
                >
            </div>
        </div>
    </section>
    `;

    // Refresh input names and indices
    function refreshIndices() {
        const blocks = container.querySelectorAll(".dividends-block");
        blocks.forEach((block, index) => {
            block.dataset.index = index;
            block.querySelectorAll("input").forEach(input => {
                input.name = input.name.replace(/dividends\[\d+\]/, `dividends[${index}]`);
            });
        });
    }

    // Add new dividend block
    addBtn.addEventListener("click", () => {
        const newIndex = container.querySelectorAll(".dividends-block").length;
        const newBlockHTML = newBlockTemplate.replace(/__INDEX__/g, newIndex);
        container.insertAdjacentHTML("beforeend", newBlockHTML);
        refreshIndices();
    });

    // Delete last dividend block
    deleteBtn.addEventListener("click", () => {
        const blocks = container.querySelectorAll(".dividends-block");
        if (blocks.length > 1) {
            blocks[blocks.length - 1].remove();
            refreshIndices();
        }
    });
    
    refreshIndices();
});
</script>