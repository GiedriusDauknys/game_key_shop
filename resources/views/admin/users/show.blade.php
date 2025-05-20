@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">User Details</h6>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Roles:</strong> {{ implode(', ', $user->roles->pluck('name')->toArray()) }}</p>

            <p><strong>Favorite Games:</strong>
                {{ $user->games->isNotEmpty() ? implode(', ', $user->games->pluck('title')->toArray()) : 'No favorite games' }}
            </p>

            <a href="{{ url('admin/users') }}" class="btn btn-secondary">Back</a>
            <a href="{{ url('admin/users/'.$user->id.'/edit') }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
@endsection
