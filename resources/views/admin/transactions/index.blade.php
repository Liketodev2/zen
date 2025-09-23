@extends('layouts.app')

@section('content')
<div class="container admin_controler">
    <h1 class="mb-4">Transactions List</h1>
    <hr>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">
        <i class="fa-solid fa-arrow-left "></i>
        Dashboard
    </a>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>Tax Return ID</th>
                <th>Stripe Charge ID</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Status</th>
                <th>Description</th>
                <th>Registered At</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->user->email ?? 'N/A' }}</td>
                    <td>{{ $transaction->taxReturn->id ?? 'N/A' }}</td>
                    <td>{{ $transaction->stripe_charge_id ?? '—' }}</td>
                    <td>${{ number_format($transaction->amount / 100, 2) }}</td>
                    <td>{{ strtoupper($transaction->currency) }}</td>
                    <td>{{ ucfirst($transaction->status) }}</td>
                    <td>{{ $transaction->description ?? '—' }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
</div>
@endsection
