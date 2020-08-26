<?php

namespace LaraBase\Roles\Controllers;;

use function Composer\Autoload\includeFile;
use Illuminate\Http\Request;
use LaraBase\CoreController;
use LaraBase\Permissions\Models\Permission;
use LaraBase\Permissions\Permissions;
use LaraBase\Roles\Models\Role;
use LaraBase\Roles\Models\RoleCategory;
use LaraBase\Roles\Models\RoleMeta;
use LaraBase\Roles\Models\RolePostBox;
use LaraBase\Roles\Models\RolePostType;
use LaraBase\Roles\Roles;
use LaraBase\World\models\Country;

class RoleController extends CoreController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->apiSecurity('roles');

        // TODO optimize

        if ($request->ajax()) {
            $roles = Role::all();
            $output = [];
            foreach ($roles as $role) {
                $output[] = [
                    'id' => $role->id,
                    'value' => $role->id,
                    'name' => $role->name,
                    'text' => $role->label,
                    'title' => $role->label,
                    'count' => $role->count
                ];
            }
            return $output;
        }

        $roles = Role::paginate(30);
        return adminView('roles.all', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return adminView('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->apiSecurity('roles');
        $this->storeValidator($request);
        $request->request->add(['operator_id' => auth()->id()]);
        $role = Role::create([
            'name' => $request->name,
            'label' => $request->label
        ]);
        if ($request->ajax()) {
            return [
                'status' => 'success',
                'role' => $role
            ];
        }
        return redirect(route('admin.roles.edit', $role));
    }

    public function storeValidator($request) {
        return $request->validate([
            'name'  => 'required|unique:roles,name',
            'label' => 'required'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->apiSecurity('roles');
        $role = Role::find($id);
        $user = auth()->user();

        $userRoles = $user->roles();
        $roles = $user->canSetRoles($userRoles);
        $roleLevels = $role->levels()->pluck('value')->toArray();

        $canSet = [];
        $canSetMe = [];

        $canSetSelfRole = false;
        if ($user->can('canSetSelfRole'))
            $canSetSelfRole = true;

        foreach ($roles as $roleItem) {
            if (in_array($roleItem->id, $roleLevels)) {
                $canSet[] = $roleItem->id;
            }
            if ($roleItem->isCanSet($role)) {
                $canSetMe[] = $roleItem->id;
            }
        }

        $permissions = $user->canSetPermissions($userRoles);
        $treeViewPermissions = treeView($permissions, ['title' => 'label']);
        $rolePermissions = $role->permissions()->pluck('id')->toArray();

        $canSetPostTypes = $user->canSetPostTypes($userRoles);
        $postTypes = $canSetPostTypes['postTypes'];
        $postTypesPermissions = $canSetPostTypes['permissions'];
        $allPostTypesPermissions = config('typesConfig.permissions');
        $getPostTypes = $role->getPostTypes();
        $rolePostTypes = $getPostTypes['postTypes'];
        $rolePostTypesPermissions = $getPostTypes['permissions'];

        $categories = $user->canSetCategories($userRoles);
        $roleCategories = $role->categories()->pluck('value')->toArray();

        $postBoxes = $user->canSetPostBoxes($userRoles);
        $rolePostBoxes = $role->postBoxes()->pluck('value')->toArray();

        $countries = $provinces = $cities = $towns = null;
        $roleCountries = $roleProvinces = $roleCities = $roleTowns = null;
        $countries = $user->canSetCountries();
        $roleCountries = $role->countries()->pluck('value')->toArray();
        $provinces = $user->canSetProvinces();
        $roleProvinces = $role->provinces()->pluck('value')->toArray();
        $cities    = $user->canSetCities();
        $roleCities = $role->cities()->pluck('value')->toArray();
        $towns     = $user->canSetTowns();
        $roleTowns = $role->towns()->pluck('value')->toArray();

        $ticketDepartment = RoleMeta::where(['role_id' => $id, 'key' => 'ticketDepartment'])->exists();

        return [
            'role' => $role,
            'roles' => $roles,
            'roleLevels' => $roleLevels,
            'canSet' => $canSet,
            'canSetMe' => $canSetMe,
            'permissions' => $permissions,
            'treeViewPermissions' => $treeViewPermissions,
            'rolePermissions' => $rolePermissions,
            'postTypes' => $postTypes,
            'postTypesPermissions' => $postTypesPermissions,
            'allPostTypesPermissions' => $allPostTypesPermissions,
            'rolePostTypes' => $rolePostTypes,
            'rolePostTypesPermissions' => $rolePostTypesPermissions,
            'postBoxes' => $postBoxes,
            'rolePostBoxes' => $rolePostBoxes,
            'categories' => $categories,
            'roleCategories' => $roleCategories,
            'countries' => $countries,
            'roleCountries' => $roleCountries,
            'provinces' => $provinces,
            'roleProvinces' => $roleProvinces,
            'cities' => $cities,
            'roleCities' => $roleCities,
            'towns' => $towns,
            'roleTowns' => $roleTowns,
            'ticketDepartment' => $ticketDepartment,
        ];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $this->apiSecurity('roles');
        $role = Role::find($id);
        $user = auth()->user();

        $userRoles = $user->roles();

        $roles = $user->canSetRoles($userRoles);
        $roleLevels = $role->levels()->pluck('value')->toArray();

        $permissions = $user->canSetPermissions($userRoles);
        $treeViewPermissions = treeView($permissions, ['title' => 'label']);
        $rolePermissions = $role->permissions()->pluck('id')->toArray();

        $canSetPostTypes = $user->canSetPostTypes($userRoles);
        $postTypes = $canSetPostTypes['postTypes'];
        $postTypesPermissions = $canSetPostTypes['permissions'];
        $allPostTypesPermissions = config('typesConfig.permissions');
        $getPostTypes = $role->getPostTypes();
        $rolePostTypes = $getPostTypes['postTypes'];
        $rolePostTypesPermissions = $getPostTypes['permissions'];

        $categories = $user->canSetCategories($userRoles);
        $roleCategories = $role->categories()->pluck('value')->toArray();

        $postBoxes = $user->canSetPostBoxes($userRoles);
        $rolePostBoxes = $role->postBoxes()->pluck('value')->toArray();

        $countries = $provinces = $cities = $towns = null;
        $roleCountries = $roleProvinces = $roleCities = $roleTowns = null;
        if (isActiveWorld()) {
            try {
                $countries = $user->canSetCountries();
                $roleCountries = $role->countries()->pluck('value')->toArray();
                $provinces = $user->canSetProvinces();
                $roleProvinces = $role->provinces()->pluck('value')->toArray();
                $cities    = $user->canSetCities();
                $roleCities = $role->cities()->pluck('value')->toArray();
                $towns     = $user->canSetTowns();
                $roleTowns = $role->towns()->pluck('value')->toArray();
            } catch (\Exception $err) {

            }
        }

        $ticketDepartment = RoleMeta::where(['role_id' => $id, 'key' => 'ticketDepartment'])->exists();

        return adminView('roles.edit', compact(
            'role',
            'roles',
            'roleLevels',
            'treeViewPermissions',
            'permissions',
            'rolePermissions',
            'postTypes',
            'postTypesPermissions',
            'allPostTypesPermissions',
            'rolePostTypes',
            'rolePostTypesPermissions',
            'postBoxes',
            'rolePostBoxes',
            'categories',
            'roleCategories',
            'countries',
            'roleCountries',
            'provinces',
            'roleProvinces',
            'cities',
            'roleCities',
            'towns',
            'roleTowns',
            'ticketDepartment'
        ));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->apiSecurity('roles');
        $role = Role::find($id);
        $this->updateValidator($request, $role);

        $role->update(['label' => $request->label, 'name' => $request->name]);

        RoleMeta::where('role_id', $id)->where('key', '!=', 'user')->delete();
        RoleMeta::where(['key' => 'level', 'value' => $id])->where('key', '!=', 'user')->delete();

        if ($request->has('canSet')) {
            foreach ($request->canSet as $assignedId) {
                $role->addMeta([
                    'key' => 'level',
                    'value' => $assignedId
                ]);
            }
        }

        if ($request->has('canSetMe')) {
            foreach ($request->canSetMe as $assignedId) {
                RoleMeta::create([
                    'role_id'  => $assignedId,
                    'key'      => 'level',
                    'value'    => $id,
                ]);
            }
        }

        if ($request->has('postTypes')) {
            foreach ($request->postTypes as $postType => $permissions) {
                foreach ($permissions as $permission) {
                    $role->addMeta([
                        'key' => 'postType',
                        'value' => $permission,
                        'more'  => $postType
                    ]);
                }
            }
        }

        if ($request->has('postBoxes')) {
            foreach ($request->postBoxes as $postBox) {
                $role->addMeta([
                    'key' => 'box',
                    'value' => $postBox,
                ]);
            }
        }

        if ($request->has('categories')) {
            foreach ($request->categories as $postType => $categories) {
                foreach ($categories as $category) {
                    $role->addMeta([
                        'key' => 'category',
                        'value' => $category,
                        'more'  => $postType
                    ]);
                }
            }
        }

        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                $role->addMeta([
                    'key'   => 'permission',
                    'value' => $permission,
                ]);
            }
        }

        if ($request->has('countries')) {
            foreach ($request->countries as $country) {
                $role->addMeta([
                    'key'   => 'world',
                    'value' => $country,
                    'more'  => 'country',
                ]);
            }
        }

        if ($request->has('provinces')) {
            foreach ($request->provinces as $province) {
                $role->addMeta([
                    'key'   => 'world',
                    'value' => $province,
                    'more'  => 'province',
                ]);
            }
        }

        if ($request->has('cities')) {
            foreach ($request->cities as $city) {
                $role->addMeta([
                    'key'   => 'world',
                    'value' => $city,
                    'more'  => 'city',
                ]);
            }
        }

        if ($request->has('towns')) {
            foreach ($request->towns as $town) {
                $role->addMeta([
                    'key'   => 'world',
                    'value' => $town,
                    'more'  => 'town',
                ]);
            }
        }

        if ($request->has('ticketDepartment')) {
            $where = [
                'role_id' => $role->id,
                'key' => 'ticketDepartment'
            ];
            if (RoleMeta::where($where)->exists()) {
                if ($request->ticketDepartment != '1') {
                    RoleMeta::where($where)->delete();
                }
            } else {
                if ($request->ticketDepartment == '1') {
                    RoleMeta::create(array_merge($where, ['value' => 'true']));
                }
            }
        }

        if ($request->ajax()) {
            return [
                'status' => 'success',
                'message' => 'بروزرسانی با موفقیت انجام شد'
            ];
        }

        session()->flash('success', 'با موفقیت انجام شد');
        return redirect()->back();

    }

    public function updateValidator($request, $role) {

        $rules = [
            'name'  => "required|unique:roles,name,{$role->id}",
            'label' => 'required'
        ];
        $messages = [];

        $can = true;

        $user = auth()->user();
        $userRoles = $user->roles;

        if (!$user->can('canSetAllRoles')) {

            $canSetSelfRole = $user->can('canSetSelfRole');
            $roles = $user->canSetRoles($userRoles)->pluck('id')->toArray();

            if ($request->has('canSet')) {

                if (!$canSetSelfRole) {
                    if (in_array($role->id, $request->canSet)) {
                        $can = false;
                    }
                }

                if ($can) {
                    foreach ($request->canSet as $id) {
                        if (!in_array($id, $roles)) {
                            $can = false;
                            break;
                        }
                    }
                }

            }

            if ($request->has('canSetMe')) {

                if (!$canSetSelfRole) {
                    if (in_array($role->id, $request->canSetMe)) {
                        $can = false;
                    }
                }

                if ($can) {
                    foreach ($request->canSetMe as $id) {
                        if (!in_array($id, $roles)) {
                            $can = false;
                            break;
                        }
                    }
                }

            }

        }

        if (!$user->can('canSetAllPermissions')) {

            if ($request->has('permissions')) {

                if ($can) {

                    $permissions = $user->canSetPermissions($userRoles)->pluck('id')->toArray();

                    foreach ($request->permissions as $id) {
                        if (!in_array($id, $permissions)) {
                            $can = false;
                            break;
                        }
                    }

                }

            }

        }

        if (!$user->can('canSetAllPost')) {

            if ($request->has('postTypes')) {

                if ($can) {

                    $canSetPostTypes = $user->canSetPostTypes($userRoles);
                    $postTypes = $canSetPostTypes['postTypes'];
                    $postTypesPermissions = $canSetPostTypes['permissions'];

                    foreach ($request->postTypes as $postType => $permissions) {
                        if (!in_array($postType, $postTypes->pluck('type')->toArray())) {
                            $can = false;
                            break;
                        } else {

                            $break = false;
                            foreach ($permissions as $permission) {
                                if (isset($postTypesPermissions[$permission])) {
                                    if (!in_array($postType, $postTypesPermissions[$permission])) {
                                        $break = true;
                                        break;
                                    }
                                } else {
                                    $break = true;
                                    break;
                                }
                            }

                            if ($break) {
                                $can = false;
                                break;
                            }

                        }
                    }

                }

            }

        }

        if (!$user->can('canSetAllPostBoxes')) {

            if ($request->has('postBoxes')) {

                if ($can) {

                    $postBoxes = array_keys($user->canSetPostBoxes($userRoles));

                    foreach ($request->postBoxes as $postBox) {
                        if (!in_array($postBox, $postBoxes)) {
                            $can = false;
                            break;
                        }
                    }

                }

            }

        }

        if (!$user->can('canSetAllCategories')) {

            if ($request->has('categories')) {

                if ($can) {

                    $break = false;
                    $categories = $user->canSetCategories($userRoles)->pluck('id')->toArray();
                    foreach ($request->categories as $postType => $cats) {

                        foreach ($cats as $category) {
                            if (!in_array($category, $categories)) {
                                $break = true;
                                break;
                            }
                        }

                        if ($break) {
                            $can = false;
                            break;
                        }

                    }

                }

            }

        }

        if (!$can) {
            $rules = [
                'name' => 'false'
            ];
            $messages = [
                'name.false' => 'لطفا پارامترهای استاندارد را تغییر ندهید.',
            ];
        }

        return $request->validate($rules, $messages);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->apiSecurity('roles');
        $role = Role::find($id);
        if ($role != null) {
            $role->delete();
            RoleMeta::where('role_id', $id)->delete();
        }
        return [
            'status' => 'success',
            'message' => 'با موفقیت حذف شد'
        ];
    }

}
