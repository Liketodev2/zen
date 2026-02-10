@extends('layouts.app')

@section('content')
<div class="container admin_controler">
    @include('components.alerts')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>FAQs</h1>
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">Create FAQ</a>
    </div>
    <hr>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">
        <i class="fa-solid fa-arrow-left "></i>
        Dashboard
    </a>

    @if($faqs->count())
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Order</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faqs as $faq)
                        <tr>
                            <td>{{ $faq->order }}</td>
                            <td>{{ $faq->question }}</td>
                            <td>{{ Str::limit($faq->answer, 100) }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this FAQ?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">No FAQs yet.</p>
    @endif
</div>
@endsection
