<?php

namespace LaraBase\Tags\Models;

use LaraBase\CoreModel;
use LaraBase\Posts\Models\Post;

class Tag extends CoreModel {
    
    protected $fillable = [
        'tag',
    ];
    
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
    
}
