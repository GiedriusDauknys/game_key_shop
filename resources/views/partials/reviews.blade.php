@forelse($game->reviews as $review)
    <div class="mb-3 border p-3 rounded">
        <strong>{{ $review->user->name }}</strong>
        <div>
            @for ($i = 1; $i <= 5; $i++)
                {{ $i <= $review->rating ? '★' : '☆' }}
            @endfor
        </div>
        <p>{{ $review->comment }}</p>
        <small class="text-muted">
            {{ $review->created_at->diffForHumans() }}
            @if ($review->updated_at && $review->updated_at->gt($review->created_at))
                (edited)
            @endif
        </small>
        @if (auth()->id() === $review->user_id)
            <a href="{{ route('review.edit', $review->game_id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
        @endif
    </div>
@empty
    <p>No reviews yet.</p>
@endforelse
