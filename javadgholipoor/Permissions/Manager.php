<?php

namespace LaraBase\Permissions;

use LaraBase\Permissions\Models\Permission;

class Manager extends Permission {
    
    protected $table = 'permissions';
    
    public function scopeTreeView() {
    
    }
    
    public function scopeAuth() {
    
    }

}
