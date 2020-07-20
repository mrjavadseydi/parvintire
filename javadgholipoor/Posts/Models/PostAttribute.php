<?php

namespace LaraBase\Posts\Models;

use LaraBase\CoreModel;

class PostAttribute extends CoreModel
{
    
    public $timestamps = false;
    
    protected $table = 'post_attributes';
    
	protected $fillable = [
		'type', 'post_id', 'attribute_id', 'key_id', 'value_id', 'active'
	];
	
}
