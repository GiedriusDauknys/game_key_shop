<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class GameKey extends Model
{
    protected $fillable = ['game_id', 'key', 'used', 'user_id', 'viewed_at'];


    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = Crypt::encrypt($value);
    }

    public function getKeyAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
