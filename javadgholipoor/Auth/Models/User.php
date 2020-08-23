<?php

namespace LaraBase\Auth\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use LaraBase\Auth\Actions\Authorizable;
use LaraBase\Categories\Models\Category;
use LaraBase\Permissions\Permissions;
use LaraBase\Posts\Models\PostType;
use LaraBase\Posts\Posts;
use LaraBase\Roles\Models\Role;
use LaraBase\Roles\Models\RoleMeta;
use LaraBase\Roles\Roles;
use LaraBase\World\models\City;
use LaraBase\World\models\Country;
use LaraBase\World\models\Province;
use LaraBase\World\models\Town;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use \LaraBase\Auth\Actions\User;
    use Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'fullname'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return $this->name();
    }

    public function getAvatarAttribute()
    {
        return $this->avatar();
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function scopeRoles()
    {
        $hookKey = "user_{$this->id}";
        $hook = doAction($hookKey);
        if (isset($hook['roles'])) {
            $roles = $hook['roles'];
        } else {
            $roleIds = RoleMeta::where([
                'key' => 'user',
                'value' => $this->id
            ])->pluck('role_id')->toArray();
            $roles = Role::whereIn('id', $roleIds)->get();
            addAction($hookKey, function ($hook) use ($roles) {
                $hook['roles'] = $roles;
                return $hook;
            });
        }
        return $roles;
    }

    public function scopePermissions() {

        $hookKey = "user_{$this->id}";
        $hook = doAction($hookKey);
        if (isset($hook['permissions'])) {
            $permissions = $hook['permissions'];
        } else {

            $permissions = [];
            $roles = $this->roles();
            foreach ($roles as $role) {
                foreach ($role->permissions() as $permission) {
                    $permissions[] = $permission->name;
                }
            }

            addAction($hookKey, function ($hook) use ($permissions) {
                $hook['permissions'] = $permissions;
                return $hook;
            });

        }

        return $permissions;

    }

    public function verifications()
    {
        return $this->hasMany(UserVerification::class);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    public function postsFavorite() {
        return $this->belongsToMany(Post::class);
    }

    // Methods

    public function paginationCount() {
        return config('usersConfig.pagination.count');
    }

    //Scopes

    public function scopeIsId($query, $id) {
        $query->whereId($id);
    }

    public function canSetRoles($roles = false) {

        $all = false;
        if ($this->can('canSetAllRoles')) {
            $all = true;
        } else {
            if (isDev()) {
                $all = true;
            }
        }

        if ($all)
            return Roles::all();

        if (!$roles)
            $roles = $this->roles();

        $levels = [];
        foreach ($roles as $role) {
            foreach ($role->levels() as $level) {
                $levels[] = $level->value;
            }
        }

        $levels = array_unique($levels);

        return Roles::whereIn('id', $levels)->get();

    }

    public function canSetPermissions($roles = false) {

        $all = false;
        if ($this->can('canSetAllPermissions')) {
            $all = true;
        } else {
            if (isDev()) {
                $all = true;
            }
        }

        if ($all)
            return Permissions::all();

        if (!$roles)
            $roles = $this->roles();

        $permissions = [];
        foreach ($roles as $role) {
            foreach ($role->permissions() as $permission) {
                $permissions[] = $permission->id;
            }
        }

        $permissions = array_unique($permissions);

        return Permissions::whereIn('id', $permissions)->get();

    }

    public function canSetPostTypes($roles = false) {

        // اگر اینجا تغییر کرد باید متد getPostTypes در مدل Role هم تغییر کند

        $all = false;
        if ($this->can('canSetAllPost')) {
            $all = true;
        } else {
            if (isDev()) {
                $all = true;
            }
        }

        $typesConfig = config('typesConfig.types');

        $permissions = $categories = [];

        if ($all) {
            $postTypes = getPostTypes();
            $types = $postTypes->pluck('type')->toArray();
            foreach (config('typesConfig.permissions') as $key => $value) {
                foreach ($typesConfig as $typeConfig) {
                    $type = substr($key, 0, strlen($typeConfig));
                    if ($typeConfig == $type) {
                        $categories[str_replace('_', '', $type)][] = str_replace($type, '', $key);
                        break;
                    }
                }

                $permissions[$key] = $types;
            }

            return [
                'groups'  => $categories,
                'permissions' => $permissions,
                'postTypes'   => $postTypes
            ];
        }

        if (!$roles)
            $roles = $this->roles();

        $postTypes = [];
        foreach ($roles as $role) {
            foreach ($role->postTypes() as $postType) {
                foreach ($typesConfig as $typeConfig) {
                    $type = substr($postType->value, 0, strlen($typeConfig));
                    if ($typeConfig == $type) {
                        $categories[str_replace('_', '', $type)][] = str_replace($type, '', $postType->value);
                        break;
                    }
                }

                $postTypes[$postType->value][] = $postType->more;
            }
        }

        $whereIn = [];

        foreach ($postTypes as $key => $value) {
            $postTypes[$key] = array_unique($value);
            $whereIn = array_merge($whereIn, $value);
        }

        foreach ($categories as $key => $value) {
            $categories[$key] = array_unique($value);
        }

        $whereIn = array_unique($whereIn);

        $output = [
            'groups'  => $categories,
            'permissions' => $postTypes,
            'postTypes'   => PostType::whereIn('type', $whereIn)->get()
        ];

        return $output;

    }

    public function checkPostTypePermission($postType, $permission, $returnBoolean = false) {

        $get = $this->canSetPostTypes();
        $permissions = $get['permissions'];
        if (isset($permissions[$permission])) {
            if (in_array($postType, $permissions[$permission])) {
                return true;
            }
        }

        if ($returnBoolean) {
            return false;
        }

        return abort(401);

    }

    public function canSetCategories($roles = false, $where = []) {

        $all = false;
        if ($this->can('canSetAllCategories')) {
            $all = true;
        } else {
            if (isDev()) {
                $all = true;
            }
        }

        $allCategories = Category::where($where)->get();

        if ($all) {
            return $allCategories;
        }

        if (!$roles)
            $roles = $this->roles();

        $categories = [];
        foreach ($roles as $role) {
            foreach ($role->categories() as $category) {
                $categories[] = $category->value;
            }
        }

        $categories = array_unique($categories);

        return Category::whereIn('id', $categories)->where($where)->get();

    }

    public function canSetPostBoxes($roles = false) {

        $all = false;
        if ($this->can('canSetAllPostBoxes')) {
            $all = true;
        } else {
            if (isDev()) {
                $all = true;
            }
        }

        $allBoxes = array_merge(config('boxes'), getProjectBoxes() ?? []);

        if ($all) {
            return $allBoxes;
        }

        if (!$roles)
            $roles = $this->roles();

        $postBoxes = [];
        foreach ($roles as $role) {
            foreach ($role->postBoxes() as $postBox) {
                $postBoxes[] = $postBox->value;
            }
        }

        $postBoxes = array_unique($postBoxes);

        $output = [];

        foreach ($allBoxes as $name => $value) {
            if (in_array($name, $postBoxes)) {
                $output[$name] = $value;
            }
        }

        return $output;

    }

    public function canSetCountries($roles = false) {

        $all = false;
        if ($this->can('canSetAllCountries')) {
            $all = true;
        } else {
            if (isDev()) {
                $all = true;
            }
        }

        if ($all) {
            return Country::all();
        }

        if (!$roles)
            $roles = $this->roles();

        $countries = [];
        foreach ($roles as $role) {
            foreach ($role->countries() as $country) {
                $countries[] = $country->value;
            }
        }

        $countries = array_unique($countries);

        return Country::whereIn('id', $countries)->get();

    }

    public function canSetProvinces($roles = false) {

        $all = false;
        if ($this->can('canSetAllProvinces')) {
            $all = true;
        } else {
            if (isDev()) {
                $all = true;
            }
        }

        if ($all) {
            return Province::all();
        }

        if (!$roles)
            $roles = $this->roles();

        $provinces = [];
        foreach ($roles as $role) {
            foreach ($role->provinces() as $province) {
                $provinces[] = $province->value;
            }
        }

        $provinces = array_unique($provinces);

        return Province::whereIn('id', $provinces)->get();

    }

    public function canSetCities($roles = false) {

        $all = false;
        if ($this->can('canSetAllCities')) {
            $all = true;
        } else {
            if (isDev()) {
                $all = true;
            }
        }

        if ($all) {
            return City::all();
        }

        if (!$roles)
            $roles = $this->roles();

        $cities = [];
        foreach ($roles as $role) {
            foreach ($role->cities() as $city) {
                $cities[] = $city->value;
            }
        }

        $cities = array_unique($cities);

        return City::whereIn('id', $cities)->get();

    }

    public function canSetTowns($roles = false) {

        $all = false;
        if ($this->can('canSetAllTowns')) {
            $all = true;
        } else {
            if (isDev()) {
                $all = true;
            }
        }

        if ($all) {
            return Town::all();
        }

        if (!$roles)
            $roles = $this->roles();

        $towns = [];
        foreach ($roles as $role) {
            foreach ($role->towns() as $town) {
                $towns[] = $town->value;
            }
        }

        $towns = array_unique($towns);

        return Town::whereIn('id', $towns)->get();

    }

    function scopeCanView($query) {

//        $user = auth()->user();

    }

    function scopeSearch($query, $string = null) {
        if (empty($string))
            if (isset($_GET['search']))
                $string = $_GET['search'];
        if (empty($string))
            if (isset($_GET['q']))
                $string = $_GET['q'];

        if (!empty($string)) {
            $query->whereRaw("(users.id LIKE '%{$string}%' OR users.name LIKE '%{$string}%' OR users.family LIKE '%{$string}%' OR users.username LIKE '%{$string}%' OR users.email LIKE '%{$string}%' OR users.mobile LIKE '%{$string}%')");
        }
    }

    function scopeSort($query, $sort = null) {
        if (empty($sort))
            if (isset($_GET['sort']))
                $sort = $_GET['sort'];

        $ascDesk = 'desc';

        if (!empty($sort)) {
            if ($sort == 'oldest')
                $ascDesk = 'asc';
        }

        $query->orderBy('created_at', $ascDesk);

    }

}
