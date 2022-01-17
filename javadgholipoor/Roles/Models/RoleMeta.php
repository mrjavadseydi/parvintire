<?php


namespace LaraBase\Roles\Models;


use LaraBase\CoreModel;

class RoleMeta extends CoreModel {
    
    public $timestamps = false;
    protected $table = 'role_metas';
    
    protected $fillable = [
        'role_id',
        'key',
        'value',
        'more'
    ];
    
}
