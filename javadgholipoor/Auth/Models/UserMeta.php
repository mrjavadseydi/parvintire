<?php

namespace LaraBase\Auth\Models;

use LaraBase\CoreModel;

class UserMeta extends CoreModel {
    
    protected $fillable = [
        'user_id',
        'key',
        'value',
        'more'
    ];
    
}
