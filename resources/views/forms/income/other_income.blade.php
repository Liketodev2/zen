<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Other Income</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help" />
  </div>

  <div class="grin_box_border mb-4">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          Assessable First Home Super Saver (FHSS) released amount - Category 3
        </label>
        <input type="number" step="0.01"
               name="other_income[fhss_amount]"
               class="form-control border-dark"
               placeholder="00.00$"
               value="{{ old('other_income.fhss_amount', $incomes->other_income['fhss_amount'] ?? '') }}" />
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          Tax withheld for First Home Super Saver (FHSS) - Category 3
        </label>
        <input type="number" step="0.01"
               name="other_income[fhss_tax_withheld]"
               class="form-control border-dark"
               placeholder="00.00$"
               value="{{ old('other_income.fhss_tax_withheld', $incomes->other_income['fhss_tax_withheld'] ?? '') }}" />
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">Taxable professional income</label>
        <input type="number" step="0.01"
               name="other_income[professional_income]"
               class="form-control border-dark"
               placeholder="00.00$"
               value="{{ old('other_income.professional_income', $incomes->other_income['professional_income'] ?? '') }}" />
      </div>
    </div>
  </div>

  <div class="grin_box_border mb-4" id="otherIncomeContainer">
    @php
      $otherIncomes = $incomes->other_income ?? [];
      $dynamicBlocks = array_filter($otherIncomes, fn($k) => is_numeric($k), ARRAY_FILTER_USE_KEY);
    @endphp

    @foreach($dynamicBlocks as $index => $block)
    <div class="row other-income-block" data-index="{{ $index }}">
      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">Other income type</label>
        <select name="other_income[{{ $index }}][other_income_type]" class="form-control border-dark">
          <option value="">Select</option>
          <option value="lottery" {{ old("other_income.$index.other_income_type", $block['other_income_type'] ?? '') == 'lottery' ? 'selected' : '' }}>Winnings from investment related lotteries and gambling</option>
          <option value="forex" {{ old("other_income.$index.other_income_type", $block['other_income_type'] ?? '') == 'forex' ? 'selected' : '' }}>Foreign exchange gains</option>
          <option value="securities" {{ old("other_income.$index.other_income_type", $block['other_income_type'] ?? '') == 'securities' ? 'selected' : '' }}>Gains on traditional securities</option>
          <option value="financial" {{ old("other_income.$index.other_income_type", $block['other_income_type'] ?? '') == 'financial' ? 'selected' : '' }}>Financial investments not shown elsewhere</option>
          <option value="asset_adjustment" {{ old("other_income.$index.other_income_type", $block['other_income_type'] ?? '') == 'asset_adjustment' ? 'selected' : '' }}>Any assessable balancing adjustment when you stop holding a depreciating asset</option>
          <option value="work_in_progress" {{ old("other_income.$index.other_income_type", $block['other_income_type'] ?? '') == 'work_in_progress' ? 'selected' : '' }}>Work-in-progress amounts</option>
          <option value="ato_interest" {{ old("other_income.$index.other_income_type", $block['other_income_type'] ?? '') == 'ato_interest' ? 'selected' : '' }}>ATO interest remitted</option>
          <option value="reimbursements" {{ old("other_income.$index.other_income_type", $block['other_income_type'] ?? '') == 'reimbursements' ? 'selected' : '' }}>Reimbursements of tax-related expenses or election expenses</option>
          <option value="other" {{ old("other_income.$index.other_income_type", $block['other_income_type'] ?? '') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">Amount you received</label>
        <input type="number" step="0.01"
               name="other_income[{{ $index }}][other_income_amount]"
               class="form-control border-dark"
               placeholder="00.00$"
               value="{{ old("other_income.$index.other_income_amount", $block['other_income_amount'] ?? '') }}" />
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          Assessable balancing adjustment from low value pool relating to financial investments
        </label>
        <input type="number" step="0.01"
               name="other_income[{{ $index }}][bal_adj_financial]"
               class="form-control border-dark"
               placeholder="00.00$"
               value="{{ old("other_income.$index.bal_adj_financial", $block['bal_adj_financial'] ?? '') }}" />
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          Assessable balancing adjustment from low value pool relating to rental property
        </label>
        <input type="number" step="0.01"
               name="other_income[{{ $index }}][bal_adj_rental]"
               class="form-control border-dark"
               placeholder="00.00$"
               value="{{ old("other_income.$index.bal_adj_rental", $block['bal_adj_rental'] ?? '') }}" />
      </div>

      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">Remaining assessable balancing adjustment</label>
        <input type="number" step="0.01"
               name="other_income[{{ $index }}][bal_adj_remaining]"
               class="form-control border-dark"
               placeholder="00.00$"
               value="{{ old("other_income.$index.bal_adj_remaining", $block['bal_adj_remaining'] ?? '') }}" />
      </div>
    </div>
    @endforeach
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <button type="button" class="btn btn_add btn_add_other_income">
        <img src="{{ asset('img/icons/plus.png') }}" alt="plus" />
        Add another item
      </button>
      <button type="button" class="btn btn_delete btn_delete_other_income">
        Delete item
      </button>
    </div>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("otherIncomeContainer");
    const addBtn = document.querySelector(".btn_add_other_income");
    const deleteBtn = document.querySelector(".btn_delete_other_income");

    function refreshIndices() {
      container.querySelectorAll(".other-income-block").forEach((block, index) => {
        block.dataset.index = index;
        block.querySelectorAll("[name]").forEach(el => {
          el.name = el.name.replace(/other_income\[\d+\]/, `other_income[${index}]`);
        });
      });
    }

    addBtn.addEventListener("click", () => {
      const lastBlock = container.querySelector(".other-income-block:last-child");
      const newBlock = lastBlock.cloneNode(true);

      newBlock.querySelectorAll("input").forEach(input => input.value = "");
      newBlock.querySelectorAll("select").forEach(select => select.selectedIndex = 0);

      container.appendChild(newBlock);
      refreshIndices();
    });

    deleteBtn.addEventListener("click", () => {
      const blocks = container.querySelectorAll(".other-income-block");
      if (blocks.length > 1) {
        blocks[blocks.length - 1].remove();
        refreshIndices();
      }
    });

    refreshIndices();
});
</script>
