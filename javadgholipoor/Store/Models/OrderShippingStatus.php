<?php


namespace LaraBase\Store\Models;


use LaraBase\CoreModel;

class OrderShippingStatus extends CoreModel
{

    public $timestamps = false;

    protected $table = 'order_shipping_status';

    protected $fillable = [
        'id',
        'order_id',
        'order_shipping_id',
        'status',
        'created_at',
    ];

}
