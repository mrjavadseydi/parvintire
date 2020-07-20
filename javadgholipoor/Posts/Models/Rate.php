<?php


namespace LaraBase\Posts\Models;


use LaraBase\CoreModel;

class Rate extends CoreModel {
    
    public $timestamps = false;
    
    protected $table = 'rating';
    
    protected $fillable = [
        'id',
        'post_id',
        'user_id',
        'rate',
        'ip',
        'created_at',
    ];
    
}
