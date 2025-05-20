<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Shop;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RawgAPIController extends Controller
{
    public $rawgApiKey;
    public $rawgBaseUrl = 'https://api.rawg.io/api/';

    public function __construct()
    {
        $this->rawgApiKey = env('RAWG_API_KEY');
    }

    public function fetchDataFromRawgAPI()
    {
        $processedGames = [];
        $gamesPerYear = 1;
        $startYear = 2017;
        $endYear = 2018;

        for ($year = $startYear; $year <= $endYear; $year++) {
            $page = 1;
            $fetchedGames = 0;

            while ($fetchedGames < $gamesPerYear) {
                $response = Http::get($this->rawgBaseUrl . 'games', [
                    'key' => $this->rawgApiKey,
                    'dates' => "$year-01-01,$year-12-31",
                    'page_size' => 40,
                    'page' => $page,
                ]);

                if (!$response->successful()) break;
                $gamesData = $response->json()['results'] ?? [];
                if (empty($gamesData)) break;

                foreach ($gamesData as $gameData) {
                    if ($fetchedGames >= $gamesPerYear) break 2;
                    if (in_array($gameData['name'], $processedGames) || Game::where('title', $gameData['name'])->exists()) continue;

                    $shopEntries = $this->getGameShops($gameData['name']);
                    if (empty($shopEntries)) continue;

                    $gameResponse = Http::get($this->rawgBaseUrl . 'games/' . $gameData['id'], [
                        'key' => $this->rawgApiKey,
                    ]);

                    if (!$gameResponse->successful()) continue;
                    $gameDetail = $gameResponse->json();

                    $creatorName = $gameDetail['developers'][0]['name'] ?? 'Unknown';
                    $creator = Creator::firstOrCreate(['name' => $creatorName]);

                    $lowestPrice = collect($shopEntries)->min('price');


                    $game = Game::updateOrCreate(
                        ['title' => $gameData['name']],
                        [
                            'description' => strip_tags($gameDetail['description_raw'] ?? 'No description available'),
                            'release_date' => $gameDetail['released'] ?? null,
                            'creator_id' => $creator->id,
                            'thumbnail' => $gameDetail['background_image'] ?? null,
                            'price' => $lowestPrice, // <- įrašome pigiausią kainą
                        ]
                    );


                    if (!empty($gameDetail['genres'])) {
                        $genreIds = [];
                        foreach ($gameDetail['genres'] as $genreData) {
                            $genreResponse = Http::get($this->rawgBaseUrl . 'genres/' . $genreData['id'], [
                                'key' => $this->rawgApiKey,
                            ]);

                            $genreDescription = 'No description available';
                            if ($genreResponse->successful()) {
                                $genreDataFull = $genreResponse->json();
                                $genreDescription = strip_tags($genreDataFull['description'] ?? $genreDescription);
                            }

                            $genre = Genre::firstOrCreate(
                                ['name' => $genreData['name']],
                                ['description' => $genreDescription]
                            );
                            $genreIds[] = $genre->id;
                        }
                        $game->genres()->sync($genreIds);
                    }

                    foreach ($shopEntries as $entry) {
                        $shopMeta = $this->getStoreInfo($entry['shop_name']);


                        $shop = Shop::where('name', $shopMeta['name'])->first();


                        if (!$shop) {
                            $shop = Shop::updateOrCreate(
                                ['id' => $entry['shop_name']],
                                [
                                    'name' => $shopMeta['name'],
                                    'icon_url' => $shopMeta['icon_url'],
                                ]
                            );
                        }

                        $game->shops()->syncWithoutDetaching([
                            $shop->id => [
                                'price' => $entry['price'],
                                'url' => $entry['deal_link']
                            ]
                        ]);
                    }

                    $processedGames[] = $gameData['name'];
                    $fetchedGames++;
                }
                $page++;
            }
        }

        return redirect()->back()->with('success', 'Games and shop data fetched and saved.');
    }

    public function getGameShops($gameTitle)
    {
        try {
            sleep(1);
            $encodedTitle = urlencode($gameTitle);
            $url = "https://www.cheapshark.com/api/1.0/games?title={$encodedTitle}";
            $response = Http::get($url);

            $games = $response->json();

            if (!is_array($games)) {
                \Log::warning('CheapShark API returned unexpected format', [
                    'title' => $gameTitle,
                    'response' => $response->body(),
                ]);
                return [];
            }

            foreach ($games as $game) {
                if (is_array($game) && isset($game['external'])) {
                    similar_text($game['external'], $gameTitle, $percent);
                    if ($percent >= 90) {
                        $gameID = $game['gameID'];
                        $dealRes = Http::get("https://www.cheapshark.com/api/1.0/games", ['id' => $gameID]);
                        $dealData = $dealRes->json();

                        return collect($dealData['deals'] ?? [])->map(function ($deal) {
                            return [
                                'shop_name' => $deal['storeID'],
                                'price' => floatval($deal['price']),
                                'deal_link' => 'https://www.cheapshark.com/redirect?dealID=' . $deal['dealID']
                            ];
                        });
                    }
                }
            }

            \Log::info('No matching game found on CheapShark', ['title' => $gameTitle]);
        } catch (\Exception $e) {
            \Log::error('Error in getGameShops', ['error' => $e->getMessage()]);
        }

        return [];
    }

    public function getStoreInfo($storeId)
    {
        $stores = Cache::remember('cheapshark_stores', 86400, function () {
            $response = Http::get("https://www.cheapshark.com/api/1.0/stores");
            return $response->json();
        });

        foreach ($stores as $store) {
            if ((int) $store['storeID'] === (int) $storeId) {
                $iconIndex = max(0, (int)$store['storeID'] - 1); // sumažinam per 1, bet minimum 0
                return [
                    'name' => $store['storeName'],
                    'icon_url' => 'https://www.cheapshark.com/img/stores/icons/' . $iconIndex . '.png'
                ];
            }
        }

        return [
            'name' => 'Unknown',
            'icon_url' => null
        ];
    }
}
