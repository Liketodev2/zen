<section id="booksExpensesForm">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Books & Other Work-Related Expenses</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help">
  </div>

  <div class="col-md-6 form-group mb-3">
    <label class="choosing-business-type-text" for="books_expense_count">
      Number of expenses you need to claim (choose)
    </label>
    <select name="books[expense_count]" id="books_expense_count" class="form-control border-dark">
      <option value="">Choose</option>
      @for ($i = 1; $i <= 4; $i++)
        <option value="{{ $i }}"
          {{ old('books.expense_count', $deductions->books['expense_count'] ?? '') == $i ? 'selected' : '' }}>
          {{ $i }}
        </option>
      @endfor
    </select>
  </div>

  @php
    $booksExpenses = old('books.expenses', $deductions->books['expenses'] ?? []);
    $booksCount = count($booksExpenses) > 0 ? count($booksExpenses) : 1;
  @endphp

  @for ($i = 0; $i < $booksCount; $i++)
  <div class="grin_box_border p-3 mb-3 books-expense-block" id="books_expense_block_{{ $i }}">
    <div class="col-md-6">
      <div class="form-group mb-2">
        <label>Type of expense</label>
        <select name="books[expenses][{{ $i }}][type]" class="form-control border-dark">
          <option value="">Choose</option>
          <option value="Books and journals" {{ ($booksExpenses[$i]['type'] ?? '') === 'Books and journals' ? 'selected' : '' }}>Books and journals</option>
          <option value="Seminars" {{ ($booksExpenses[$i]['type'] ?? '') === 'Seminars' ? 'selected' : '' }}>Seminars</option>
          <option value="Newspapers" {{ ($booksExpenses[$i]['type'] ?? '') === 'Newspapers' ? 'selected' : '' }}>Newspapers</option>
          <option value="Subscriptions" {{ ($booksExpenses[$i]['type'] ?? '') === 'Subscriptions' ? 'selected' : '' }}>Subscriptions</option>
          <option value="Award overtime meal allowance" {{ ($booksExpenses[$i]['type'] ?? '') === 'Award overtime meal allowance' ? 'selected' : '' }}>Award overtime meal allowance</option>
          <option value="Other" {{ ($booksExpenses[$i]['type'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
        </select>
      </div>

      <div class="form-group mb-2">
        <label>Describe this item in a few words</label>
        <input type="text"
               name="books[expenses][{{ $i }}][description]"
               class="form-control border-dark"
               placeholder="..."
               value="{{ $booksExpenses[$i]['description'] ?? '' }}">
      </div>

      <div class="form-group mb-2">
        <label>What sort of records do you have for this expense?</label>
        <select name="books[expenses][{{ $i }}][record_type]" class="form-control border-dark">
          <option value="">Choose</option>
          <option value="Invoice / receipt" {{ ($booksExpenses[$i]['record_type'] ?? '') === 'Invoice / receipt' ? 'selected' : '' }}>Invoice / receipt</option>
          <option value="PAYG summary" {{ ($booksExpenses[$i]['record_type'] ?? '') === 'PAYG summary' ? 'selected' : '' }}>PAYG summary</option>
          <option value="Allowance received" {{ ($booksExpenses[$i]['record_type'] ?? '') === 'Allowance received' ? 'selected' : '' }}>Allowance received</option>
          <option value="Actual recorded cost" {{ ($booksExpenses[$i]['record_type'] ?? '') === 'Actual recorded cost' ? 'selected' : '' }}>Actual recorded cost</option>
        </select>
      </div>

      <div class="form-group mb-2">
        <label>Cost of this item</label>
        <input type="text"
               name="books[expenses][{{ $i }}][cost]"
               class="form-control border-dark"
               placeholder="00.00$"
               value="{{ $booksExpenses[$i]['cost'] ?? '' }}">
      </div>
    </div>
  </div>
  @endfor

  <!-- File upload -->
  <div class="mt-3">
    <label class="choosing-business-type-text d-block mb-2">
      Attach a simple breakdown of your expenses (optional)
    </label>
    <input type="file" name="books[books_file]" id="booksFileInput" class="d-none" />
    <button type="button" class="btn btn_add" id="triggerBooksFile">
      <img src="{{ asset('img/icons/plus.png') }}" alt="plus"> Choose file
    </button>
      <p class="text-muted mt-1 mb-0">
          Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
      </p>
    <p id="booksFileName" class="choosing-business-type-text text-muted mt-2 mb-0">
      @if(!empty($deductions->attach['books']['books_file']))
        <a href="{{ asset('storage/'.$deductions->attach['books']['books_file']) }}" target="_blank" class="btn btn-outline-success">
          <i class="fa-solid fa-file"></i>
          View file
        </a>
      @else
        No file chosen
      @endif
    </p>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const booksFileInput = document.getElementById("booksFileInput");
  const triggerBooksFile = document.getElementById("triggerBooksFile");
  const booksFileName = document.getElementById("booksFileName");

  triggerBooksFile.addEventListener("click", () => booksFileInput.click());

  booksFileInput.addEventListener("change", () => {
    booksFileName.textContent = booksFileInput.files.length
      ? booksFileInput.files[0].name
      : "No file chosen";
  });
});
</script>
