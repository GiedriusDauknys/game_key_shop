<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{

    protected $table = 'support_requests';

    protected $fillable = ['user_id', 'subject', 'message', 'response', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
