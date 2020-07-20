<?php

namespace LaraBase\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{

    protected $table = 'user_verification';

    protected $fillable = [
        'type',
        'user_id',
        'token'
    ];

    protected $hidden = [
        'token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
