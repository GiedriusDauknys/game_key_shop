@extends('layouts.app')

@section('title', 'Submit Support Request')

@section('content')
    <div class="container py-4">
        <h2>Submit Support Request</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('support.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" name="subject" id="subject" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Send Request</button>
            <a href="{{ route('support.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
