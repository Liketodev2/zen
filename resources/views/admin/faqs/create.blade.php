@extends('layouts.app')

@section('content')
<div class="container admin_controler">
    @include('components.alerts')
    <h1>Create FAQ</h1>
    <hr>
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary mb-3">Back to FAQs</a>

    <form action="{{ route('admin.faqs.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Question</label>
            <input type="text" name="question" class="form-control" value="{{ old('question') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Answer</label>
            <textarea name="answer" class="form-control" rows="6" required>{{ old('answer') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Order</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
        </div>
        <button class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
