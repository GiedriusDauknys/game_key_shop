<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\Creator;
use Illuminate\Http\Request;

class GamesListController extends Controller
{
    public function listGames(Request $request)
    {
        $genres = Genre::all();
        $query = Game::with('genres');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Genre Filter
        if ($request->has('genres') && $request->genres !== '') {
            $genreIds = explode(',', $request->genres);
            $query->whereHas('genres', function($q) use ($genreIds) {
                $q->whereIn('genres.id', $genreIds);
            });
        }

        // Sorting
        $orderBy = $request->input('order_by');
        switch ($orderBy) {
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'release_newest':
                $query->orderBy('release_date', 'desc');
                break;
            case 'release_oldest':
                $query->orderBy('release_date', 'asc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }

        $games = $query->paginate(20);
        $games->appends($request->all());

        if ($request->ajax()) {
            return view('partials.games-list', compact('games'))->render();
        }

        return view('pages.games', compact('games', 'genres'));
    }

    public function getReviews($gameId)
    {
        $game = Game::with('reviews.user')->findOrFail($gameId);
        return view('partials.reviews', compact('game'))->render();
    }

    public function show($id)
    {
        $game = Game::with(['genres', 'creator', 'shops'])->findOrFail($id);

        $averageRating = $game->reviews()->avg('rating');


        $reviews = $game->reviews()->with('user')->latest()->paginate(5);

        return view('pages.game', compact('game', 'averageRating', 'reviews'));
    }
    public function getAverageRating($gameId)
    {
        $game = Game::findOrFail($gameId);
        $averageRating = round($game->reviews()->avg('rating'), 1);

        $starsHtml = view('partials.rating-stars', compact('averageRating'))->render();

        return response()->json([
            'average' => $averageRating,
            'html' => $starsHtml,
        ]);
    }
}
