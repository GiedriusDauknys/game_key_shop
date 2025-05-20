@extends('layouts.app')

@section('title', 'Browse Games')

@section('content')
    <div class="container py-4">
        <h2>Browse Games</h2>

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

        <!-- Search Bar -->
        <form method="GET" action="{{ route('games.list') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search for a game..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <label for="order_by"><strong>Sort By:</strong></label>
        <select name="order_by" id="order_by" class="form-select">
            <option value="">-- Default --</option>
            <option value="title" {{ request('order_by') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
            <option value="price_asc" {{ request('order_by') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
            <option value="price_desc" {{ request('order_by') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
            <option value="release_newest" {{ request('order_by') == 'release_newest' ? 'selected' : '' }}>Release Date (Newest)</option>
            <option value="release_oldest" {{ request('order_by') == 'release_oldest' ? 'selected' : '' }}>Release Date (Oldest)</option>
        </select>

        <!-- Genre Filter -->
        <form id="genreFilterForm" class="mb-4">
            <label for="genres"><strong>Filter by Genres:</strong></label>
            <div class="genre-box">
                @php
                    $selectedGenres = is_array(request('genres')) ? request('genres') : explode(',', request('genres', ''));
                @endphp

                @foreach($genres as $genre)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input genre-checkbox" type="checkbox"
                               id="genre{{ $genre->id }}"
                               name="genres[]"
                               value="{{ $genre->id }}"
                               @if(in_array($genre->id, $selectedGenres)) checked @endif>
                        <label class="form-check-label" for="genre{{ $genre->id }}">
                            {{ $genre->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="d-flex mt-2">
                <button type="button" id="clearFilter" class="btn btn-danger">Clear Filter</button>
            </div>
        </form>

        <!-- Game List (Updated via AJAX) -->
        <div id="games-list">
            @include('partials.games-list', ['games' => $games])
        </div>


        <style>
            .genre-box {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                border: 2px solid #ccc;
                padding: 10px;
                border-radius: 5px;
                background: #f9f9f9;
                max-width: 900px;
            }

            .form-check-inline {
                margin-right: 10px;
                white-space: nowrap;
            }
        </style>
        <!-- JavaScript for Auto-Update -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const checkboxes = document.querySelectorAll(".genre-checkbox");
                const clearFilterButton = document.getElementById("clearFilter");
                const searchInput = document.querySelector('input[name="search"]');
                const orderSelect = document.getElementById("order_by");

                //  Listen for genre checkbox changes
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener("change", updateGames);
                });

                //  Clear filters
                clearFilterButton.addEventListener("click", function() {
                    checkboxes.forEach(checkbox => checkbox.checked = false);
                    searchInput.value = '';
                    orderSelect.value = '';
                    updateGames();
                });

                //  Live search (key press)
                let searchTimeout;
                searchInput.addEventListener("keyup", function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        updateGames();
                    }, 300);
                });

                // Ordering change
                orderSelect.addEventListener("change", updateGames);

                function updateGames() {
                    const selectedGenres = Array.from(checkboxes)
                        .filter(checkbox => checkbox.checked)
                        .map(checkbox => checkbox.value);

                    const search = searchInput.value;
                    const order = orderSelect.value;

                    let url = "{{ route('games.list') }}?";
                    if (search) url += `search=${encodeURIComponent(search)}&`;
                    if (selectedGenres.length) url += `genres=${selectedGenres.join(',')}&`;
                    if (order) url += `order_by=${order}`;

                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById("games-list").innerHTML = html;
                        });
                    window.history.replaceState({}, '', url);
                }
            });
        </script>

    </div>
@endsection


