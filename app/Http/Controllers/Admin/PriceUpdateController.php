<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;

class PriceUpdateController extends Controller
{
    public function updatePrices()
    {
        $games = Game::with('shops')->get();
        $updatedCount = 0;
        $checkedCount = 0;

        foreach ($games as $game) {
            $cheapest = $game->shops->sortBy(function ($shop) {
                return $shop->pivot->price;
            })->first();

            if (!$cheapest) continue;

            $checkedCount++;

            $currentPrice = $game->price;
            $newPrice = $cheapest->pivot->price;

            if ($newPrice < $currentPrice) {
                $game->update(['price' => $newPrice]);
                $updatedCount++;
            }
        }

        return redirect()->back()->with('success', "$updatedCount game prices updated out of $checkedCount checked.");
    }
}
