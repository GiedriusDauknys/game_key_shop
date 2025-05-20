@extends('layouts.admin')

@section('title', 'Support Request Details')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ url('admin/support/' . $request->id . '/edit') }}" class="btn btn-primary">Edit Request</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th>ID</th><td>{{ $request->id }}</td></tr>
                <tr><th>User</th><td>{{ $request->user->name }}</td></tr>
                <tr><th>Subject</th><td>{{ $request->subject }}</td></tr>
                <tr><th>Message</th><td>{{ $request->message }}</td></tr>
                <tr><th>Response</th><td>{{ $request->response ?? 'No response yet' }}</td></tr>
                <tr><th>Status</th><td>{{ ucfirst($request->status) }}</td></tr>
                <tr><th>Created</th><td>{{ $request->created_at }}</td></tr>
                <tr><th>Updated</th><td>{{ $request->updated_at }}</td></tr>
            </table>
        </div>
    </div>
@endsection
