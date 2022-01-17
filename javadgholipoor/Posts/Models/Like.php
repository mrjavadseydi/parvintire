<?php

namespace LaraBase\Posts\Models;

use LaraBase\CoreModel;

class Like extends CoreModel {

    public $timestamps = false;
    
    protected $table = 'likes';
    
    protected $fillable = [
        'id',
        'post_id',
        'user_id',
        'type',
        'ip',
        'created_at',
    ];
    
}
