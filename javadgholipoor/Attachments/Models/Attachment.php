<?php


namespace LaraBase\Attachments\Models;


use LaraBase\CoreModel;

class Attachment extends CoreModel {
    
    use \LaraBase\Attachments\Actions\Attachment;
    
    protected $fillable = [
        'user_id',
        'relation_type',
        'relation_id',
        'title',
        'description',
        'poster',
        'mime',
        'type',
        'extension',
        'size',
        'duration',
        'path',
        'parent',
        'in',
        'created_at',
        'updated_at',
    ];
    
}
