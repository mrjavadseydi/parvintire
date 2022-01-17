<?php

namespace LaraBase\Store\Models;

use LaraBase\CoreModel;
use Str;

class ShippingWorld extends CoreModel {

    protected $table = 'shipping_world';

    protected $guarded = [];

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function world()
    {
        return $this->belongsTo("LaraBase\World\models\\" . Str::ucfirst($this->relation), 'relation_id');
    }
}
