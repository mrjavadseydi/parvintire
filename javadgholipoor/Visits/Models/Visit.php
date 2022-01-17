<?php

namespace LaraBase\Visits\Models;

use LaraBase\CoreModel;

class Visit extends CoreModel {
    
    protected $table = 'visits';
    
    protected $fillable = [
        'user_id',
        'url',
        'ip',
        'count',
        'relation',
        'relation_id',
        'created_at',
        'updated_at',
    ];
    
}
