<section>
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="form_title">Tools and Equipment</h4>
    <img src="{{ asset('img/icons/help.png') }}" alt="Help" />
  </div>

  <p class="choosing-business-type-text">
    If you paid for tools and equipment used for your work, claim the costs here.
  </p>
  <p class="choosing-business-type-text">
    Please do not claim items that were paid for by an employer and don’t claim items if you were paid back for them later by an employer.
  </p>

  @php
    $tools = old('tools', $deductions->tools ?? []);
  @endphp

  <div class="grin_box_border p-3 mb-3">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          Add-up the items that cost $300 or LESS each
        </label>
        <input
          type="text"
          name="tools[under_300]"
          class="form-control border-dark"
          placeholder="00.00$"
          value="{{ $tools['under_300'] ?? '' }}"
        />
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          In a few words, describe the tools and how they relate to your work.
          <small class="text-muted">(eg. “Assorted hand tools and power tool bits required for my work”)</small>
        </label>
        <textarea
          name="tools[under_300_desc]"
          class="form-control border-dark"
          rows="3"
          placeholder="Text"
        >{{ $tools['under_300_desc'] ?? '' }}</textarea>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          Add-up the items that cost $301 or MORE each
        </label>
        <input
          type="text"
          name="tools[over_300]"
          class="form-control border-dark"
          placeholder="00.00$"
          value="{{ $tools['over_300'] ?? '' }}"
        />
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          For tools that cost more than $300 each, please list all items incl. description, cost and date of purchase
          <small class="text-muted">(eg. “air compressor $452.12, 18 Feb <?= date('Y') ?>, Bunnings”)</small>
        </label>
        <textarea
          name="tools[over_300_desc]"
          class="form-control border-dark"
          rows="3"
          placeholder="Text"
        >{{ $tools['over_300_desc'] ?? '' }}</textarea>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          What % of the time are the tools used for your work?
        </label>
        <input
          type="text"
          name="tools[percent_use]"
          class="form-control border-dark"
          placeholder="0%"
          value="{{ $tools['percent_use'] ?? '' }}"
        />
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="choosing-business-type-text">
          What sort of records or evidence do you have?
          <small class="text-muted">(eg. an invoice or receipt)</small>
        </label>
        <select name="tools[evidence]" class="form-select border-dark">
          <option value="">Choose</option>
          <option value="I: Invoice / Receipt" {{ ($tools['evidence'] ?? '') === 'I: Invoice / Receipt' ? 'selected' : '' }}>I: Invoice / Receipt</option>
          <option value="C: Actual recorded cost" {{ ($tools['evidence'] ?? '') === 'C: Actual recorded cost' ? 'selected' : '' }}>C: Actual recorded cost</option>
        </select>
      </div>
    </div>
  </div>
</section>
