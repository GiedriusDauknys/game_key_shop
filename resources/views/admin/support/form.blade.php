@extends('layouts.admin')

@section('title', isset($request) ? 'Edit Request' : 'Create Request')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($request) ? 'Edit Support Request' : 'New Support Request' }}
            </h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ isset($request) ? url('admin/support/' . $request->id) : url('admin/support') }}" method="POST">
                @csrf
                @isset($request) @method('PATCH') @endisset

                <div class="form-group">
                    <label for="user_id">User:</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        @foreach($users as $id => $name)
                            <option value="{{ $id }}" {{ old('user_id', $request->user_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject', $request->subject ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea name="message" class="form-control" rows="4" required>{{ old('message', $request->message ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="response">Response:</label>
                    <textarea name="response" class="form-control" rows="4">{{ old('response', $request->response ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="open" {{ old('status', $request->status ?? '') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="resolved" {{ old('status', $request->status ?? '') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
