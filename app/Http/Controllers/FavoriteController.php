<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $favoriteGames = $user->favorites()->with('genres', 'keys')->paginate(10);
        return view('pages.favorites', compact('favoriteGames'));
    }

    public function toggle(Game $game)
    {
        $user = Auth::user();

        if ($user->favorites()->where('game_id', $game->id)->exists()) {
            $user->favorites()->detach($game->id);
            return response()->json(['status' => 'removed']);
        } else {
            $user->favorites()->attach($game->id);
            return response()->json(['status' => 'added']);
        }
    }
}
