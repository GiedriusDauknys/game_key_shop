<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{

    protected $table = 'creators';

    protected $fillable = ['name'];

    public function games()
    {
        return $this->hasMany(Game::class, 'creator_id');
    }
}
