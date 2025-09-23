@extends('layouts.app')

@section('content')
<div class="container admin_controler">
    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
        <i class="fa-solid fa-arrow-left "></i>
        Back
    </a>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $plan->name }}</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Price: {{ $plan->price }} $</h5>
            <p class="card-text">{{ $plan->description }}</p>

            <h6 class="mt-3">Options:</h6>
            @if($plan->options->count())
                <ul class="list-group list-group-flush">
                    @foreach($plan->options as $option)
                        <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                            {{ $option->name }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mt-2">No options available.</p>
            @endif
        </div>
    </div>
</div>
@endsection
