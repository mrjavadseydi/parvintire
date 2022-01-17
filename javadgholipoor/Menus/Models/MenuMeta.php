<?php

namespace LaraBase\Menus\Models;

use LaraBase\CoreModel;

class MenuMeta extends CoreModel {

    protected $table = 'menu_metas';

    protected $fillable = [
        'menu_id', 'key', 'value', 'more', 'lang', 'created_at', 'updated_at'
    ];

}
