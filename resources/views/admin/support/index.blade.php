@extends('layouts.admin')

@section('title', 'Support Requests')

@section('content')
    <div class="card">

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
                        <th>User</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->subject }}</td>
                            <td>{{ ucfirst($request->status) }}</td>
                            <td>
                                <a href="{{ url('admin/support/' . $request->id) }}" class="btn btn-success btn-sm">View</a>
                                <a href="{{ url('admin/support/' . $request->id . '/edit') }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ url('admin/support/' . $request->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this request?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-3">{{ $requests->links() }}</div>
            </div>
        </div>
    </div>
@endsection
