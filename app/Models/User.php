<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function games(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'favorites', 'user_id', 'game_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function supportRequests()
    {
        return $this->hasMany(SupportRequest::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if (!$user->hasAnyRole(['admin', 'member'])) {
                $user->assignRole('member');
            }
        });
    }

    public function gameKeys()
    {
        return $this->hasMany(GameKey::class);
    }

    public function purchasedGames()
    {
        return $this->belongsToMany(Game::class, 'game_keys', 'user_id', 'game_id')
            ->withTimestamps()
            ->withPivot('used');
    }

    public function favorites()
    {
        return $this->belongsToMany(Game::class, 'favorites');
    }
}
