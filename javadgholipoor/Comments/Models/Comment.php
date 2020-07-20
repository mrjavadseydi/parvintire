<?php

namespace LaraBase\Comments\Models;

use LaraBase\CoreModel;

class Comment extends CoreModel {
    
    protected $table = 'comments';
    
    protected $fillable = [
        'user_id',
        'post_id',
        'parent',
        'type',
        'name',
        'subject',
        'comment',
        'email',
        'mobile',
        'department',
        'priority',
        'ip',
        'status',
        'created_at',
        'updated_at',
    ];
    
    public function attachments() {
        
    }
    
    public function metas() {
        return CommentMeta::where('comment_id', $this->id)->get();
    }
    
    public function scopeComments($query) {
        $query->where('type', '1');
    }
    
    public function scopeTickets($query) {
        $query->where('type', '2');
    }
    
    public function scopePending($query) {
        $query->where('status', '1');
    }
    
    public function scopeStatus($query, $status = null) {
        if ($status == null) {
            $status = $_GET['status'] ?? null;
        }
        if (!empty($status))
            $query->where('status', $status);
    }
    
    public function scopeStatuses($query, $statuses) {
        $query->whereIn('status', $statuses);
    }
    
}
