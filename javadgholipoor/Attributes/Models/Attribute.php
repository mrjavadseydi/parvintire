<?php

namespace LaraBase\Attributes\Models;

use LaraBase\CoreModel;

class Attribute extends CoreModel
{

    protected $table = 'attributes';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'type',
        'lang',
        'parent',
        'created_at',
        'updated_at'
    ];

}
