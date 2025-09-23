<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="form_title">Sun Protection</h4>
        <img src="{{ asset('img/icons/help.png') }}" alt="Help">
    </div>

    <p class="choosing-business-type-text mb-3">
        If you are required to do your work outdoors, then you can claim the cost of sunscreen, sunglasses and sun-protection clothing like hats.
    </p>

    @php
        $sunProtection = old('sun_protection', isset($deductions) ? $deductions->sun_protection ?? [] : []);
    @endphp

    <div class="grin_box_border p-3 mb-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">
                    Describe this item (eg. “sunnies and wide rimmed hat for paving work”)
                </label>
                <input type="text" class="form-control border-dark" name="sun_protection[description]" placeholder="..."
                    value="{{ $sunProtection['description'] ?? '' }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="choosing-business-type-text">Total cost of sun protection items</label>
                <input type="text" class="form-control border-dark" name="sun_protection[cost]" placeholder="00.00$"
                    value="{{ $sunProtection['cost'] ?? '' }}">
            </div>
        </div>
    </div>

    <div class="row mb-3 align-items-end">
        <p class="choosing-business-type-text">
            Attach a simple breakdown of your expenses (optional)
        </p>

        <div class="col-md-6 mb-3">
            <input type="file" name="sun_protection[sun_file]" id="sunFileInput" class="d-none">
            <button type="button" class="btn btn_add" id="triggerSunFile">
                <img src="{{ asset('img/icons/plus.png') }}" alt="plus">
                Choose file
            </button>
            <p class="text-muted mt-1 mb-0">
                Allowed file types: PDF, JPG, PNG. Maximum file size: 5 MB.
            </p>
        </div>

        <div class="col-md-6 mb-3">
            <p id="sunFileName" class="choosing-business-type-text text-muted mb-0">
                @if(!empty($deductions->attach['sun_protection']['sun_file']))
                    <a href="{{ Storage::disk('s3')->url($deductions->attach['sun_protection']['sun_file'])}}" target="_blank" class="btn btn-outline-success">
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
document.addEventListener("DOMContentLoaded", () => {
    const sunFileInput = document.getElementById("sunFileInput");
    const triggerSunFile = document.getElementById("triggerSunFile");
    const sunFileName = document.getElementById("sunFileName");

    triggerSunFile.addEventListener("click", () => sunFileInput.click());

    sunFileInput.addEventListener("change", () => {
        if (sunFileInput.files.length > 0) {
            const file = sunFileInput.files[0];
            sunFileName.innerHTML = `<span class="text-success">${file.name}</span>`;
        } else {
            sunFileName.textContent = "No file chosen";
        }
    });
});
</script>
