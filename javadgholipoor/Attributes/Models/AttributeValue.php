<?php

namespace LaraBase\Attributes\Models;

use LaraBase\CoreModel;

class AttributeValue extends CoreModel
{

    protected $table = 'attribute_values';

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
