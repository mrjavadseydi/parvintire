<?php

namespace LaraBase\Permissions\Models;

use LaraBase\Auth\Models\User;
use LaraBase\CoreModel;
use LaraBase\Roles\Models\Role;
use LaraBase\Roles\Models\RoleMeta;

class Permission extends CoreModel {
 
    protected $fillable = [
        'name',
        'label',
        'description',
        'parent',
        'created_at',
        'updated_at',
    ];
    
    public function roles($returnIds = false)
    {
        $roleMetas = RoleMeta::where(['key' => 'permission', 'value' => $this->id])->get();
        $roleIds = array_unique(array_unique($roleMetas->pluck('role_id')->toArray()));
        
        if ($returnIds)
            return $roleIds;
        
        return Role::whereIn('id', $roleIds)->get();
    }
    
    public function users($returnIds = false) {
        $roleIds = $this->roles(true);
        $users = RoleMeta::whereIn('role_id', $roleIds)->where('key', 'user')->get();
        $userIds = array_unique($users->pluck('value')->toArray());
    
        if ($returnIds)
            return $userIds;
        
        return User::whereIn('id', $userIds)->get();
    }
    
}
