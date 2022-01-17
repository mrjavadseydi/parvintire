<?php

namespace LaraBase\Posts\Models;

use LaraBase\CoreModel;

class PostCommentAttributes extends CoreModel
{
    
    protected $table = 'post_comment_attributes';
    
    protected $fillable = [
        'post_id',
        'attribute_id',
        'active',
        'created_at',
        'updated_at',
    ];
    
}
