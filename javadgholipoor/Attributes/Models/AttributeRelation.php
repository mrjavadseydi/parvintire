<?php

namespace LaraBase\Attributes\Models;

use LaraBase\CoreModel;

class AttributeRelation extends CoreModel {

    public $timestamps = false;

    protected $table = 'attribute_relations';

    protected $fillable = [
        'key', 'value', 'more'
    ];

}
