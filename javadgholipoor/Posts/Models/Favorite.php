<?php

namespace LaraBase\Posts\Models;

use LaraBase\CoreModel;

class Favorite extends CoreModel {

    public $timestamps = false;
    
    protected $table = 'favorites';
    
    protected $fillable = [
        'post_id',
        'user_id',
        'created_at',
    ];
    
}
