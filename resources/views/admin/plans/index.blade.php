@extends('layouts.app')

@section('content')
<div class="container admin_controler">
    @include('components.alerts')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Plans</h1>
        <a href="{{ route('plans.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i>
            New Plan
        </a>
    </div>
    <hr>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">
        <i class="fa-solid fa-arrow-left "></i>
        Dashboard
    </a>

    @if($plans->count())
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light" style="border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                        <tr>
                            <td>{{ $plan->name }}</td>
                            <td>{{ Str::limit($plan->description, 60) }}</td>
                            <td>{{ $plan->price }} $</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('plans.show', $plan) }}" class="btn btn-outline-success btn-sm" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('plans.edit', $plan) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">No plans available.</p>
    @endif
</div>
@endsection
