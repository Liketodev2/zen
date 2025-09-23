<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Union Fees</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    @php
        $unionFees = old('union_fees', isset($deductions) ? $deductions->union_fees ?? [] : []);
    @endphp

    <div class="grin_box_border p-3 mb-3">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="choosing-business-type-text">Total union fees paid</label>
                <input type="text" name="union_fees[amount]" class="form-control border-dark" placeholder="00.00$"
                       value="{{ $unionFees['amount'] ?? '' }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="choosing-business-type-text">
                    What sort of records or evidence do you have for this expense? <br>
                    <small class="text-muted">(e.g. an invoice or receipt)</small>
                </label>
                <select name="union_fees[evidence_type]" class="form-control border-dark">
                    <option value="">Choose</option>
                    <option value="PAYG" {{ (isset($unionFees['evidence_type']) && $unionFees['evidence_type']=='PAYG') ? 'selected' : '' }}>PAYG</option>
                    <option value="Invoice / receipt" {{ (isset($unionFees['evidence_type']) && $unionFees['evidence_type']=='Invoice / receipt') ? 'selected' : '' }}>Invoice / receipt</option>
                    <option value="Actual recorded cost" {{ (isset($unionFees['evidence_type']) && $unionFees['evidence_type']=='Actual recorded cost') ? 'selected' : '' }}>Actual recorded cost</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row mb-3 align-items-end">
        <p class="choosing-business-type-text">
            Attach a simple breakdown of your expenses <span class="text-muted">(optional)</span>
        </p>

        <div class="col-md-6 mb-3">
            <input type="file" name="union_fees[file]" id="unionFileInput" class="d-none">
            <button type="button" class="btn btn_add" id="triggerUnionFile">
                <img src="{{ asset('img/icons/plus.png') }}" alt="plus">
                Choose file
            </button>
            <p class="text-muted mt-1 mb-0">
                Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
            </p>
        </div>

        <div class="col-md-6 mb-3">
            <p id="unionFileName" class="choosing-business-type-text text-muted mb-0">
                @if(!empty($deductions->attach['union_fees']['file']))
                    <a href="{{ Storage::disk('s3')->url($deductions->attach['union_fees']['file']) }}" target="_blank" class="btn btn-outline-success">
                        <i class="fa-solid fa-file"></i> View file
                    </a>
                @else
                    No file chosen
                @endif
            </p>
        </div>
    </div>
</section>

<script>
const unionFileInput = document.getElementById("unionFileInput");
const triggerUnionFile = document.getElementById("triggerUnionFile");
const unionFileName = document.getElementById("unionFileName");

if (unionFileInput && triggerUnionFile && unionFileName) {
    triggerUnionFile.addEventListener("click", () => unionFileInput.click());

    unionFileInput.addEventListener("change", () => {
        unionFileName.textContent = unionFileInput.files.length
            ? unionFileInput.files[0].name
            : "No file chosen";
    });
}
</script>
