<div class="row">
    @foreach($games as $game)
        <div class="col-md-4 mb-4">
            <div class="card h-100">


                <div class="position-relative">
                    <a href="{{ route('games.show', $game->id) }}" class="card-link text-decoration-none text-dark">
                        <img src="{{ $game->thumbnail ?? '/img/default.png' }}" class="card-img-top" alt="{{ $game->title }}">
                    </a>

                    @auth
                        <button class="btn btn-link p-0 border-0 position-absolute top-0 end-0 p-2"
                                style="z-index: 2;"
                                onclick="toggleFavorite({{ $game->id }}, this)">
    <span class="favorite-icon" style="font-size: 1.5rem; color: {{ auth()->user()->favorites->contains($game->id) ? 'red' : 'grey' }};">
        {{ auth()->user()->favorites->contains($game->id) ? '❤️' : '🤍' }}
    </span>
                        </button>
                    @endauth
                </div>


                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">{{ $game->title }}</h5>
                        <p class="card-text"><strong>Price:</strong> €{{ number_format($game->price, 2) }}</p>
                        <p class="card-text"><strong>Genres:</strong>
                            @foreach($game->genres as $genre)
                                <span class="badge bg-secondary">{{ $genre->name }}</span>
                            @endforeach
                        </p>
                    </div>

                    @php
                        $availableKeys = $game->keys()->where('used', false)->count();
                    @endphp

                    @auth
                        @if ($availableKeys > 0)
                            <form action="{{ route('cart.add', $game->id) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    Add to Basket
                                </button>
                            </form>
                        @else
                            <div class="alert alert-danger mt-3 text-center">
                                ❌ Out of Stock
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary mt-3 w-100">
                            Login to purchase
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    @endforeach

    <div class="d-flex justify-content-center mt-4">
        {{ $games->links() }}
    </div>
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
                const icon = button.querySelector('.favorite-icon');
                if (data.status === 'added') {
                    icon.textContent = '❤️';
                    icon.style.color = 'red';
                } else if (data.status === 'removed') {
                    icon.textContent = '🤍';
                    icon.style.color = 'grey';
                }
            })
            .catch(error => {
                console.error('Error toggling favorite:', error);
                alert('Something went wrong while updating favorites.');
            });
    }
</script>
