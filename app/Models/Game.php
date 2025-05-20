<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';
    protected $fillable = ['title', 'description', 'price', 'release_date', 'thumbnail', 'creator_id']; // Removed genre_id

    public function creator()
    {
        return $this->belongsTo(Creator::class, 'creator_id');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'game_genre', 'game_id', 'genre_id'); // Many-to-Many
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'game_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(OrderItem::class, 'game_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'game_id');
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'game_shop')->withPivot('price')->withTimestamps();
    }
    public function keys()
    {
        return $this->hasMany(GameKey::class);
    }
}
