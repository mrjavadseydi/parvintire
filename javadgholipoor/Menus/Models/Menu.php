<?php

namespace LaraBase\Menus\Models;

use LaraBase\CoreModel;

class Menu extends CoreModel {
 
    use \LaraBase\Menus\Actions\Menu;
    
    protected $table = 'menus';
    
    protected $fillable = [
        'title',
        'created_at',
        'updated_at'
    ];
    
}
