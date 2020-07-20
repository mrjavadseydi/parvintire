<?php

namespace LaraBase\Attributes\Models;

use LaraBase\CoreModel;

class AttributeKeyPostType extends CoreModel
{
    
    public $timestamps = false;
    
    protected $table = 'attribute_key_post_types';
    
    protected $fillable = [
        'attribute_key_id', 'post_type'
    ];
    
}
