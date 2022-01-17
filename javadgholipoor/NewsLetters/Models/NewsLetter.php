<?php

namespace LaraBase\NewsLetters\Models;

use LaraBase\CoreModel;

class NewsLetter extends CoreModel {
    
    protected $table = 'newsletters';
    
    protected $fillable = [
        'user_id',
        'name',
        'group',
        'type',
        'value',
        'active',
        'created_at',
        'updated_at'
    ];
    
}
