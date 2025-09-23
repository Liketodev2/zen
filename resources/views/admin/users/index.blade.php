@extends('layouts.app')

@section('content')
<div class="container admin_controler">
    <h1 class="mb-4">Users List</h1>
    <hr>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">
        <i class="fa-solid fa-arrow-left "></i>
        Dashboard
    </a>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Registered At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ ++$loop->index }}</td>
                        <td>{{ $user->name }}</td>
                        <td class="text-capitalize">{{ $user->role }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center align-items-center _pagination">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
