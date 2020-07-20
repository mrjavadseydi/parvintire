<?php


namespace LaraBase\Store\Models;


use LaraBase\CoreModel;

class Product extends CoreModel {

    use \LaraBase\Store\Actions\Product;

    protected $table = 'products';

    protected $fillable = [
        'post_id',
        'title',
        'type',
        'shipping_id',
        'tax_id',
        'purchase_price',
        'price',
        'special_price',
        'start_date',
        'end_date',
        'unit_id',
        'stock',
        'created_at',
        'updated_at',
    ];

}
