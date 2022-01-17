<?php

namespace LaraBase\Roles\Models;

use LaraBase\Auth\Models\User;
use LaraBase\CoreModel;
use LaraBase\Permissions\Models\Permission;
use LaraBase\Posts\Models\PostType;

class Role extends CoreModel {

    protected $table = 'roles';

    protected $guarded = [];

    protected $appends = [
        'count'
    ];

    public function getCountAttribute()
    {
        return $this->metas()->where('key', 'user')->count();
    }

    public function users() {
        $ids = $this->metas()->where('key', 'user')->pluck('value')->toArray();
        return User::whereIn('id', $ids)->get();
    }

    public function permissions() {
        $ids = $this->metas()->where('key', 'permission')->pluck('value')->toArray();
        return Permission::whereIn('id', $ids)->get();
    }

    public function levels() {
        return $this->metas()->where('key', 'level')->filter();
    }

    public function postTypes() {
        return $this->metas()->where('key', 'postType')->filter();
    }

    public function postBoxes() {
        return $this->metas()->where('key', 'box')->filter();
    }

    public function categories() {
        return $this->metas()->where('key', 'category')->filter();
    }

    public function countries() {
        return $this->metas()->where('key', 'world')->where('more', 'country')->filter();
    }

    public function provinces() {
        return $this->metas()->where('key', 'world')->where('more', 'province')->filter();
    }

    public function cities() {
        return $this->metas()->where('key', 'world')->where('more', 'city')->filter();
    }

    public function towns() {
        return $this->metas()->where('key', 'world')->where('more', 'town')->filter();
    }

    public function getPostTypes() {

        // اگر اینجا تغییر کرد باید متد canSetPostTypes در مدل User هم تغییر کند

        $postTypes = $this->postTypes();

        $whereIn = $postTypes->pluck('more')->toArray();
        $typesConfig = config('typesConfig.types');
        $permissions = $categories = [];

        foreach ($postTypes as $postType) {
            foreach ($typesConfig as $typeConfig) {
                $type = substr($postType->value, 0, strlen($typeConfig));
                if ($typeConfig == $type) {
                    $categories[str_replace('_', '', $type)][] = str_replace($type, '', $postType->value);
                    break;
                }
            }
            $permissions[$postType->value][] = $postType->more;
        }


        foreach ($permissions as $key => $value)
            $permissions[$key] = array_unique($value);

        foreach ($categories as $key => $value)
            $categories[$key] = array_unique($value);

        $output = [
            'categories'  => $categories,
            'permissions' => $permissions,
            'postTypes'   => PostType::whereIn('type', $whereIn)->get()
        ];

        return $output;
    }

    public function isCanSet($role) {
        if (RoleMeta::where([
            'role_id' => $this->id,
            'key'     => 'level',
            'value'   => $role->id
        ])->exists())
            return true;

        return false;
    }

    public function deleteMetas() {
        RoleMeta::where(['role_id' => $this->id])->delete();
    }

    public function deleteMeta($data) {
        $data['role_id'] = $this->id;
        RoleMeta::where($data)->delete();
    }

    public function addMeta($data) {
        $data['role_id'] = $this->id;
        RoleMeta::create($data);
    }

    function metas() {
        $hook = doAction('roles');
        $metas = null;
        $roleId = $this->id;
        if (isset($hook['metas'][$roleId])) {
            $metas = $hook['metas'][$roleId];
        } else {
            $metas = RoleMeta::where('role_id', $roleId)->get();
            addAction('roles', function ($hook) use ($metas, $roleId) {
                $hook['metas'][$roleId] = $metas;
                return $hook;
            });
        }

        return $metas;
    }

}
