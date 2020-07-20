<?php

namespace LaraBase\Store\Models;

use LaraBase\CoreModel;

class Currency extends CoreModel {
    
    protected $table = 'currency';
    
    protected $fillable = [
        'title',
        'code',
        'created_at',
        'updated_at',
    ];
    
}
