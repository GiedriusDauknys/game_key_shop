@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ url('admin/users/create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Add User
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Games</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                            <td>
                                {{ $user->games->isNotEmpty() ? implode(', ', $user->games->pluck('title')->toArray()) : 'No games' }}
                            </td>
                            <td>

                                <a href="{{ url('admin/users/'.$user->id.'/edit') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <a href="{{ url('admin/users/'.$user->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <form method="POST" action="{{ url('admin/users/'.$user->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>
@endsection
