@extends('layouts.app')

@section('title', $game->title)

@section('content')
    <div class="container py-4">

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="font-size: 1.3rem;">
            <div class="average-rating-wrapper">
            @if($averageRating)
                <div class="d-flex align-items-center gap-2">
                    <strong class="me-2" style="font-size: 1.4rem;">Average Rating:</strong>
                    <span style="font-size: 2rem; color: #000;">
                @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($averageRating))
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
            </span>
                    <span class="text-muted ms-2" style="font-weight: bold;">({{ number_format($averageRating, 1) }}/5)</span>
                </div>
            @endif
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                ← Back to Games
            </a>

        </div>





        <div class="row">




            <!-- Žaidimo nuotrauka + parduotuvės -->
            <div class="col-md-6">
                <img src="{{ $game->thumbnail ?? '/img/default.png' }}" class="img-fluid rounded mb-4" alt="{{ $game->title }}">

                <!-- Žaidimo pavadinimas ir mygtukas atskirai -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="mb-0">{{ $game->title }}</h2>

                    @auth
                        <button class="btn btn-outline-primary"
                                onclick="toggleFavorite({{ $game->id }}, this)">
                            {{ auth()->user()->favorites->contains($game->id) ? 'Remove from Favorites' : 'Add to Favorites' }}
                        </button>
                    @endauth
                </div>

                <!-- Mygtukas į krepšelį -->
                @php
                    $availableKeys = $game->keys()->where('used', false)->count();
                @endphp

                @if ($availableKeys > 0)
                    <form action="{{ route('cart.add', $game->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            Add to basket
                        </button>
                    </form>
                @else
                    <div class="alert alert-danger mt-3">
                        ❌ Out of Stock
                    </div>
                @endif

                <div style="height: 50px;"></div>
                <div class="card">
                    <div class="card-header">
                        <strong>Compare Prices</strong>
                    </div>
                    <ul class="list-group list-group-flush small">
                        @forelse ($game->shops->sortBy(function($shop) { return $shop->pivot->price; }) as $shop)
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    @if ($shop->icon_url)
                                        <img src="{{ $shop->icon_url }}" alt="{{ $shop->name }}" width="24" height="24">
                                    @endif
                                    <small>{{ $shop->name }}</small>
                                </div>
                                <div>
                                    <a href="{{ $shop->pivot->url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        €{{ number_format($shop->pivot->price, 2) }}
                                    </a>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">This game is not available in any store.</li>
                        @endforelse
                    </ul>
                </div>
                <div style="height: 50px;"></div>

            </div>

            <!-- Žaidimo informacija -->
            <div class="col-md-6">
                <h2>{{ $game->title }}</h2>
                <p><strong>Price:</strong> €{{ number_format($game->price, 2) }}</p>
                <p><strong>Release Date:</strong> {{ $game->release_date ?? 'Unknown' }}</p>
                <p><strong>Developer:</strong> {{ $game->creator->name ?? 'Unknown' }}</p>
                <p><strong>Genres:</strong>
                    @foreach($game->genres as $genre)
                        <span class="badge bg-secondary">{{ $genre->name }}</span>
                    @endforeach
                </p>
                <p><strong>Description:</strong></p>
                <p>{{ $game->description ?? 'No description available.' }}</p>


            </div>
        </div>

        <!-- palikti atsiliepima-->
        <div id="review-form-wrapper">
            @auth
                @php
                    $hasReviewed = $game->reviews->contains('user_id', auth()->id());
                @endphp

                @if (!$hasReviewed)
                    <hr>
                    <h5>Leave a Review</h5>
                    <form id="review-form" action="{{ route('reviews.store', $game->id) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Rating:</label>
                            <div class="star-rating d-flex flex-row-reverse justify-content-end">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required />
                                    <label for="star{{ $i }}">★</label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Comment:</label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Optional"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>

                    <div id="review-success-message" class="alert alert-success mt-3 d-none">
                        Review submitted!
                    </div>
                @endif
            @endauth
        </div>

        <!-- esantys atsiliepimai -->
        <hr>
        <h4>User Reviews</h4>
        <div id="reviews-section">
            @forelse($reviews as $review)
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
                <div class="mt-3">
                    {{ $reviews->links() }}
                </div>
        </div>




    </div>




    <script>
        // === Toggle favorite ===
        function toggleFavorite(gameId, button) {
            fetch(`/favorites/toggle/${gameId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'added') {
                        button.textContent = 'Remove from Favorites';
                        button.classList.remove('btn-outline-primary');
                        button.classList.add('btn-outline-danger');
                    } else {
                        button.textContent = 'Add to Favorites';
                        button.classList.remove('btn-outline-danger');
                        button.classList.add('btn-outline-primary');
                    }
                })
                .catch(() => alert('Could not update favorites.'));
        }

        // === Review form submission ===
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('review-form');
            const wrapper = document.getElementById('review-form-wrapper');
            const successMsg = document.getElementById('review-success-message');
            const reviewSection = document.getElementById('reviews-section');

            if (!form || !wrapper) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                const formData = new FormData(form);
                const scrollPos = window.scrollY;

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                })
                    .then(res => res.ok ? res.text() : Promise.reject(res))
                    .then(() => {
                        form.reset();
                        wrapper.classList.add('d-none'); // hide entire form after submission
                        successMsg.classList.remove('d-none');

                        fetch(`/games/{{ $game->id }}/reviews`)
                            .then(res => res.text())
                            .then(html => {
                                reviewSection.innerHTML = html;
                                window.scrollTo({ top: scrollPos, behavior: 'auto' });

                                // FETCH updated average rating
                                fetch(`/games/{{ $game->id }}/average-rating`)
                                    .then(res => res.json())
                                    .then(data => {
                                        document.querySelector('.average-rating-wrapper').innerHTML = data.html;
                                    });
                            });
                    })
                    .catch(() => alert('There was a problem submitting your review.'));
            });
        });
    </script>





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
