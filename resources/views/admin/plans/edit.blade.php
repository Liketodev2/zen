@extends('layouts.app')

@section('content')
<div class="container admin_controler">
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
        <i class="fa-solid fa-arrow-left "></i>
        Back
    </a>
    <h2>{{ isset($plan) ? 'Edit Plan' : 'Create Plan' }}</h2>

    <form action="{{ isset($plan) ? route('plans.update', $plan) : route('plans.store') }}" method="POST">
        @csrf
        @if(isset($plan)) @method('PUT') @endif

        <div class="mb-3">
            <label for="plan-name" class="form-label">Name</label>
            <input type="text" id="plan-name" name="name" class="form-control" value="{{ old('name', $plan->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="plan-description" class="form-label">Description</label>
            <textarea id="plan-description" name="description" class="form-control">{{ old('description', $plan->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="plan-price" class="form-label">Price</label>
            <input type="number" id="plan-price" name="price" step="0.01" class="form-control" value="{{ old('price', $plan->price ?? '') }}" required>
        </div>

        <div id="options-container">
            <h5>Options</h5>
            @if(isset($plan) && $plan->options->count())
                @foreach($plan->options as $i => $option)
                    <div class="d-flex gap-2 mb-2 option-row align-items-center">
                        <input type="text" name="options[{{ $i }}][name]" class="form-control" value="{{ $option->name }}" placeholder="Option name" required>
                        <button type="button" class="btn btn-outline-danger text-danger" onclick="removeOption(this)" title="Delete option">
                            <i class="fa-solid fa-trash fa-lg"></i>
                        </button>
                    </div>
                @endforeach
            @else
                <div class="d-flex gap-2 mb-2 option-row align-items-center">
                    <input type="text" name="options[0][name]" class="form-control" placeholder="Option name" required>
                    <button type="button" class="btn btn-outline-danger text-danger" onclick="removeOption(this)" title="Delete option" disabled>
                        <i class="fa-solid fa-trash fa-lg"></i>
                    </button>
                </div>
            @endif
        </div>

        <button type="button" class="btn btn-outline-secondary mb-3" onclick="addOption()">
            <i class="fa fa-plus me-1"></i>
            Add Option
        </button>

        <br>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<script>
    let optionIndex = {{ isset($plan) ? $plan->options->count() : 1 }};

    function addOption() {
        const container = document.getElementById('options-container');

        const row = document.createElement('div');
        row.className = 'row mb-2 option-row align-items-center';
        row.innerHTML = `
            <div class="d-flex gap-2 mb-2 option-row align-items-center">
                <input type="text" id="option-${optionIndex}" name="options[${optionIndex}][name]" class="form-control" placeholder="Option name" required>
            
                <button type="button" class="btn btn-outline-danger text-danger" onclick="removeOption(this)" title="Delete option">
                    <i class="fa-solid fa-trash fa-lg"></i>
                </button>
            </div
        `;

        container.appendChild(row);
        optionIndex++;

        updateDeleteButtons();
    }

    function removeOption(button) {
        const container = document.getElementById('options-container');
        const rows = container.querySelectorAll('.option-row');

        if (rows.length > 1) {
            const row = button.closest('.option-row');
            if (row) {
                row.remove();
                updateDeleteButtons();
            }
        } else {
            alert('At least one option is required.');
        }
    }

    // Enable/disable the delete button depending on the number of options
    function updateDeleteButtons() {
        const container = document.getElementById('options-container');
        const buttons = container.querySelectorAll('button[onclick^="removeOption"]');

        if (buttons.length === 1) {
            buttons[0].disabled = true;
        } else {
            buttons.forEach(btn => btn.disabled = false);
        }
    }

    // When loading the page, we immediately update the state of the buttons
    document.addEventListener('DOMContentLoaded', () => {
        updateDeleteButtons();
    });
</script>
@endsection
