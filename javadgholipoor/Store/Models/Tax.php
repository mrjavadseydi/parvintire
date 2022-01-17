<?php


namespace LaraBase\Store\Models;


use LaraBase\CoreModel;

class Tax  extends CoreModel {
    
    protected $table = 'taxes';
    
    protected $fillable = [
        'title', 'percent', 'created_at', 'updated_at'
    ];
    
}
