<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'genres';
    protected $fillable = ['name', 'description'];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_genre', 'genre_id', 'game_id');
    }
}
