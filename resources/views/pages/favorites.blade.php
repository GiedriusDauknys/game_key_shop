@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="height: 20px;"></div>
        <h2 class="mb-4">Your Favorite Games</h2>

        <p id="no-favorites-message" style="display: none;">No games in your wishlist yet. Click the heart icon on any game or open a game page and press “Add to Favorites” to save it here.</p>

        @if($favoriteGames->isEmpty())
            <p>No games in your wishlist yet. Click the heart icon on any game or open a game page and press “Add to Favorites” to save it here.</p>
        @else
            <div class="row" id="favorites-grid">
                @foreach($favoriteGames as $game)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="position-relative">
                                <a href="{{ route('games.show', $game->id) }}" class="text-decoration-none text-dark">
                                    <img src="{{ $game->thumbnail ?? '/img/default.png' }}" class="card-img-top" alt="{{ $game->title }}">
                                </a>

                                <button class="btn btn-link p-0 border-0 position-absolute top-0 end-0 p-2"
                                        style="z-index: 2;"
                                        onclick="toggleFavorite({{ $game->id }}, this)">
                                    <span class="favorite-icon" style="font-size: 1.5rem; color: red;">❤️</span>
                                </button>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">{{ $game->title }}</h5>
                                <p><strong>Price:</strong> €{{ number_format($game->price, 2) }}</p>

                                <p><strong>Genres:</strong>
                                    @foreach($game->genres as $genre)
                                        <span class="badge bg-secondary">{{ $genre->name }}</span>
                                    @endforeach
                                </p>

                                <a href="{{ route('games.show', $game->id) }}" class="btn btn-primary w-100 mt-2">
                                    View Game
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $favoriteGames->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleFavorite(gameId, button) {
            fetch(`/favorites/toggle/${gameId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Request failed');
                    }
                    return response.json();
                })
                .then(data => {
                    const card = button.closest('.col-md-4');
                    if (data.status === 'removed') {
                        card.remove();

                        // Check if there are any favorites left
                        const remainingCards = document.querySelectorAll('#favorites-grid .col-md-4');
                        if (remainingCards.length === 0) {
                            document.getElementById('no-favorites-message').style.display = 'block';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error toggling favorite:', error);
                    alert('Something went wrong while updating favorites.');
                });
        }
    </script>
@endsection
