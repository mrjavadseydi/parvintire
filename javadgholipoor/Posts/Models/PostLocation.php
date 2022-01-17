<?php


namespace LaraBase\Posts\Models;


use LaraBase\CoreModel;

class PostLocation extends CoreModel {
    
    protected $table = 'post_locations';
    
    public $timestamps = false;
    
    protected $fillable = [
        'post_id',
        'post_type',
        'latitude',
        'longitude',
        'address',
        'country_id',
        'province_id',
        'city_id',
        'town_id'
    ];
    
}
