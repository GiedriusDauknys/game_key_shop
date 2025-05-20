@extends('layouts.admin')

@section('title', 'Creators')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ url('admin/creators/create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Creator
            </a>
        </div>

        <div class="card-body">
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    @php Session::forget('success'); @endphp
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($creators as $creator)
                        <tr>
                            <td>{{ $creator->id }}</td>
                            <td>{{ $creator->name }}</td>
                            <td>
                                <a href="{{ url('admin/creators/'.$creator->id.'/edit') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <a href="{{ url('admin/creators/'.$creator->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <form action="{{ url('admin/creators/'.$creator->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this creator?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No creators found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $creators->links() }}


            </div>
        </div>
    </div>
@endsection
