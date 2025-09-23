@extends('layouts.app')

@section('content')
<div class="container admin_controler">
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
        <i class="fa-solid fa-arrow-left "></i>
        Back
    </a>
    <h2>Site Information</h2>

    <form action="{{ route('site-info.update', 1) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $info->phone) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $info->email) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">ABN</label>
            <input type="text" name="abn" class="form-control" value="{{ old('abn', $info->abn) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Registered Tax Agent</label>
            <input type="text" name="tax_agent" class="form-control" value="{{ old('tax_agent', $info->tax_agent) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Facebook URL</label>
            <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $info->facebook) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Instagram URL</label>
            <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $info->instagram) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">X (Twitter) URL</label>
            <input type="url" name="x" class="form-control" value="{{ old('x', $info->x) }}">
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
