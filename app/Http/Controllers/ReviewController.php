<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $gameId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        $existing = \App\Models\Review::where('user_id', $user->id)->where('game_id', $gameId)->first();
        if ($existing) {
            return back()->with('error', 'You already reviewed this game.');
        }

        \App\Models\Review::create([
            'user_id' => $user->id,
            'game_id' => $gameId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted.');
    }

    // 1. ReviewController.php

    public function edit($gameId)
    {
        $review = \App\Models\Review::where('game_id', $gameId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('reviews.edit', [
            'review' => $review,
            'game' => \App\Models\Game::findOrFail($gameId)
        ]);
    }

    public function update(Request $request, $gameId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = \App\Models\Review::where('game_id', $gameId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('games.show', $gameId)->with('success', 'Review updated successfully.');
    }

    public function destroy($gameId)
    {
        $review = \App\Models\Review::where('game_id', $gameId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $review->delete();

        return redirect()->route('games.show', $gameId)->with('success', 'Review deleted. You can leave a new one if you wish.');
    }


}
