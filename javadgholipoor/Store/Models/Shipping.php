<?php

namespace LaraBase\Store\Models;

use LaraBase\CoreModel;

class Shipping extends CoreModel {

    protected $table = 'shippings';

    protected $fillable = [
        'title',
        'description',
        'currency',
        'free_postage',
        'created_at',
        'updated_at',
    ];

}
