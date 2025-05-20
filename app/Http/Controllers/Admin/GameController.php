<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Creator;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::paginate(25);
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        $creators = Creator::pluck('name', 'id');
        $genres = Genre::pluck('name', 'id');
        $users = User::selectRaw("CONCAT(COALESCE(name,'')) AS Name, id")
            ->orderBy('Name', 'asc')
            ->pluck('Name', 'id');
        $users->prepend('--- Please select ---', 0);
        $users->all();

        return view('admin.games.form', compact('creators', 'genres', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'release_date' => 'required|date',
            'creator_id' => 'required|exists:creators,id',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $game = Game::create($request->only(['title', 'description', 'price', 'release_date', 'creator_id']));


        $game->genres()->attach($request->genres);

        return redirect('admin/games')->with('success', 'Game added successfully.');
    }

    public function show($id)
    {
        $game = Game::with('genres')->findOrFail($id);
        return view('admin.games.show', compact('game'));
    }

    public function edit($id)
    {
        $game = Game::findOrFail($id);


        $creators = Creator::pluck('name', 'id');

        $genres = Genre::pluck('name', 'id');

        $selected_genres = $game->genres()->pluck('genres.id')->toArray();

        $selected_users = $game->users()->pluck('users.id')->toArray();

        $users = User::selectRaw("CONCAT(COALESCE(name,'')) AS Name, id")
            ->orderBy('Name', 'asc')
            ->pluck('Name', 'id')
            ->prepend('--- Please select ---', 0)
            ->all();

        return view('admin.games.form', compact('game', 'creators', 'genres', 'selected_genres', 'selected_users', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'release_date' => 'required|date',
            'creator_id' => 'required|exists:creators,id',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
        ]);
        $game = Game::findOrFail($id);
        $game->update($request->all());

        $game->genres()->sync($request->genres);

        return redirect('admin/games')->with('success', 'Game updated successfully.');
    }

    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->genres()->detach();
        $game->users()->detach();
        $game->delete();
        return redirect('admin/games')->with('success', 'Game deleted successfully.');
    }
}
