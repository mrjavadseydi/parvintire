<?php


namespace LaraBase\Posts\Models;


use LaraBase\CoreModel;

class Language extends CoreModel
{

    protected $table = 'languages';

    protected $fillable = [
        'title',
        'lang',
        'body',
        'image',
        'active',
        'created_at',
        'updated_at',
    ];

}
