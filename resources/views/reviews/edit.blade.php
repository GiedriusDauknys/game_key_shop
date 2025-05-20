@extends('layouts.app')

@section('title', 'Edit Review')

@section('content')
    <div class="container py-4">
        <h2>Edit Your Review for "{{ $game->title }}"</h2>

        {{-- Update Form --}}
        <form action="{{ route('review.update', $game->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Rating:</label>
                <div class="star-rating d-flex flex-row-reverse justify-content-end">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                            {{ $review->rating == $i ? 'checked' : '' }} />
                        <label for="star{{ $i }}">★</label>
                    @endfor
                </div>
            </div>

            <div class="mb-3">
                <label for="comment" class="form-label">Comment:</label>
                <textarea name="comment" class="form-control" rows="4">{{ $review->comment }}</textarea>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Update Review</button>
                <a href="{{ route('games.show', $game->id) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        {{-- Delete Form (outside of update form) --}}
        <form action="{{ route('review.destroy', $game->id) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to delete your review?');"
              class="mt-3 text-end">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Review</button>
        </form>
    </div>

    <style>
        .star-rating {
            direction: ltr;
            font-size: 2rem;
            unicode-bidi: bidi-override;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star-rating input[type="radio"]:checked ~ label {
            color: #000000;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #000000;
        }
    </style>
@endsection
