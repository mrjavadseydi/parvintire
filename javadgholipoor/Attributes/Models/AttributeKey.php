<?php

namespace LaraBase\Attributes\Models;

use LaraBase\CoreModel;

class AttributeKey extends CoreModel
{

    protected $table = 'attribute_keys';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'lang',
        'parent',
        'created_at',
        'updated_at'
    ];

}
