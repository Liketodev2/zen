@extends('layouts.app')

@section('content')
<div class="container admin_controler">
    @include('components.alerts')
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="text-center mb-5">
                <h1>Welcome, Admin-panel!</h1>
                <p class="lead text-muted">Here you can manage your website settings.</p>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Site Info</h5>
                        <p class="card-text text-muted d-none d-md-block">Update your contact and social media settings.</p>
                    </div>
                    <div class="d-flex gap-2">
                        @include('admin.site_info.show_modal')
                        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#siteInfoModal">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <a href="{{ route('site-info.edit', 1) }}" class="btn btn-primary">
                            Edit Info <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Users</h5>
                        <p class="card-text fs-4">{{ $usersCount ?? 0 }}</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="btn btn-primary">
                        View Users
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Plans</h5>
                        <p class="card-text fs-4">{{ $plansCount ?? 0 }}</p>
                    </div>
                    <a href="{{ route('plans.index') }}" class="btn btn-primary">
                        View Plans
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1">Transactions</h5>
                        <p class="card-text fs-4">{{ $transactionsCount ?? 0 }}</p>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="btn btn-primary">
                        View Transactions
                        <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
