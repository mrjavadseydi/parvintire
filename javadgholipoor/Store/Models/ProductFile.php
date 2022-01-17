<?php


namespace LaraBase\Store\Models;


use LaraBase\Attachments\Models\Attachment;
use LaraBase\CoreModel;

class ProductFile extends CoreModel {
    
    protected $table = 'product_files';
    
    protected $fillable = [
        'post_id', 'attachment_id', 'type', 'active', 'sort', 'views', 'downloads', 'created_at', 'updated_at'
    ];
    
    public function attachment() {
        return $this->belongsTo(Attachment::class);
    }
    
}
