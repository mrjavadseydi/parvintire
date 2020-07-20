<?php

namespace LaraBase;

use Illuminate\Database\Eloquent\Model;

class CoreModel extends Model {
    
    public function scopeSearch($query, $word) {
    
    }
    
    public function scopeOrdering($query) {
        
        if (isset($_GET['orderBy'])) {
            $orderBy = $_GET['orderBy'];
            if ($orderBy == 'latest') {
                $query->latest();
            } else if ($orderBy == 'oldest') {
                $query->oldest();
            } else {
                $query->orderBy($orderBy, $_GET['sortOrder'] ?? 'asc');
            }
        } else {
            $query->latest();
        }
        
    }
    
}
