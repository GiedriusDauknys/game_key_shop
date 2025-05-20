@extends('layouts.app')

@section('title', 'Support Request Details')

@section('content')
    <div class="container py-4">
        <h2>Request Details</h2>

        <div class="card">
            <div class="card-header">
                <strong>Subject:</strong> {{ $request->subject }}
            </div>
            <div class="card-body">
                <p><strong>Message:</strong></p>
                <p>{{ $request->message }}</p>

                <p><strong>Status:</strong>
                    <span class="badge bg-{{ $request->status === 'resolved' ? 'success' : 'warning' }}">
                    {{ ucfirst($request->status) }}
                </span>
                </p>

                <p><strong>Response:</strong></p>
                <p>{{ $request->response ?? 'No response yet.' }}</p>

                <p><strong>Submitted:</strong> {{ $request->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <a href="{{ route('support.index') }}" class="btn btn-secondary mt-3">← Back to Support</a>
    </div>
@endsection
