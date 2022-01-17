<?php

namespace LaraBase\World\models;

use LaraBase\CoreModel;

class Region extends CoreModel {

    protected $table = 'regions';

    protected $fillable = [
        'id',
        'town_id',
        'name',
        'description',
        'content',
        'keywords',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
    ];

}
