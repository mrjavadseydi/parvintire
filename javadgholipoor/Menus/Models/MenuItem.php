<?php

namespace LaraBase\Menus\Models;

use LaraBase\CoreModel;

class MenuItem extends CoreModel {

    protected $table = 'menu_items';
    
    protected $fillable = [
        'id',
        'menu_id',
        'title',
        'parent',
        'sort',
        'link',
        'type',
        'data',
        'attributes',
        'icon',
        'image',
        'class',
        'active',
        'content',
        'created_at',
        'updated_at',
    ];

}
