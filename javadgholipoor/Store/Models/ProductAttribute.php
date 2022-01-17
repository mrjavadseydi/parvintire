<?php


namespace LaraBase\Store\Models;


use LaraBase\CoreModel;

class ProductAttribute extends CoreModel
{

    protected $table = 'product_attributes';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'key_id',
    ];

}
