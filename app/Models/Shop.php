<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops';
    protected $fillable = ['name', 'icon_url'];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_shop')->withPivot('price')->withTimestamps();
    }



}
