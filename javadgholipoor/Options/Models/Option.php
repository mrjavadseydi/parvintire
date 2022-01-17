<?php

namespace LaraBase\Options\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model {

    protected $fillable = [
        'key',
        'value',
        'more',
        'lang',
        'created_at',
        'updated_at'
    ];

}
