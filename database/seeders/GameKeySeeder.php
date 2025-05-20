<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameKey;
use Illuminate\Database\Seeder;

class GameKeySeeder extends Seeder
{
    public function run(): void
    {
        $games = Game::all();

        foreach ($games as $game) {

            // Count only UNUSED keys
            $availableKeys = GameKey::where('game_id', $game->id)
                ->where('used', false)
                ->count();

            if ($availableKeys < 5) {
                $toGenerate = 5 - $availableKeys;

                for ($i = 0; $i < $toGenerate; $i++) {
                    GameKey::create([
                        'game_id' => $game->id,
                        'key' => self::generateRandomKey(),
                        'used' => false,
                        'user_id' => null
                    ]);
                }

                echo "✔️ Added $toGenerate key(s) to: " . $game->title . "\n";
            }
        }
    }

    private static function generateRandomKey(): string
    {
        $parts = [];

        for ($i = 0; $i < 4; $i++) {
            $parts[] = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        }

        return implode('-', $parts);
    }
}
