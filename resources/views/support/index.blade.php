@extends('layouts.app')

@section('title', 'My Support Requests')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">My Support Requests</h2>

        <a href="{{ route('support.create') }}" class="btn btn-primary mb-4">
            <i class="fas fa-plus-circle"></i> Submit New Request
        </a>

        @if ($requests->isEmpty())
            <div class="alert alert-info">You haven't submitted any support requests yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Response</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($requests as $req)
                        <tr>
                            <td><strong>{{ $req->subject }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $req->status === 'resolved' ? 'success' : 'warning' }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                {{ $req->response ? Str::limit($req->response, 50) : '—' }}
                            </td>

                            <td>
                                @if ($req->status === 'open')
                                    <a href="{{ route('support.edit', $req->id) }}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>

                                    <form action="{{ route('support.destroy', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                @else
                                    <a href="{{ route('support.show', $req->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                @endif
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
