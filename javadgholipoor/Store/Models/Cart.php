<?php

namespace LaraBase\Store\Models;

use LaraBase\CoreModel;

class Cart extends CoreModel
{

    protected $table = 'carts';

    protected $fillable = [
        'id',
        'order_id',
        'order_shipping_id',
        'product_id',
        'price',
        'currency',
        'count',
        'total_price',
        'discount',
        'created_at',
        'updated_at',
    ];

}
