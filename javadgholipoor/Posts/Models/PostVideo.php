<?php

namespace LaraBase\Posts\Models;

use LaraBase\CoreModel;

class PostVideo extends CoreModel {
    
    protected $table = 'videos';
    
    protected $fillable = [
        'post_id',
        'title',
        'description',
        'poster',
        'poster_attachment_id',
        'video',
        'video_attachment_id',
        'size',
        'duration',
        'type',
        'downloads',
        'views',
        'sort',
        'active',
        'created_at',
        'updated_at',
    ];
    
}
