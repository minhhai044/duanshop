<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOneTimePassword extends Model
{
    protected $fillable = [
        'user_id',
        'otp',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
