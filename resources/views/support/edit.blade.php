@extends('layouts.app')

@section('title', 'Edit Support Request')

@section('content')
    <div class="container py-4">
        <h2>Edit Support Request</h2>

        <form action="{{ route('support.update', $request->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="subject" class="form-label">Subject:</label>
                <input type="text" name="subject" id="subject" class="form-control"
                       value="{{ old('subject', $request->subject) }}" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <textarea name="message" id="message" class="form-control" rows="4" required>{{ old('message', $request->message) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Request</button>
            <a href="{{ route('support.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
