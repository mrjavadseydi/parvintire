<?php


namespace LaraBase\Posts\Models;


use LaraBase\CoreModel;

class PostMeta extends CoreModel {
    
    protected $table = 'post_metas';
    
    protected $fillable = [
        'post_id', 'key', 'value', 'more'
    ];
    
}
