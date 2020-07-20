<?php

namespace LaraBase\Comments\Models;

use LaraBase\CoreModel;

class CommentMeta extends CoreModel {

    protected $table = 'comment_metas';
    
    protected $fillable = [
        'comment_id',
        'key',
        'value',
        'more',
        'created_at',
        'updated_at'
    ];

}
