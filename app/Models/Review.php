<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $table = 'reviews';

    protected $fillable = ['user_id', 'game_id', 'comment', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
