<?php

namespace LaraBase\Permissions\Controllers;;

use Illuminate\Http\Request;
use LaraBase\CoreController;
use LaraBase\Permissions\Models\Permission;
use LaraBase\Permissions\Permissions;

class PermissionController extends CoreController
{

    public $permissions = [];

    public function __construct() {
        $this->permissions = config('permissions');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->apiSecurity('permissions');

        // TODO optimize

        if ($request->ajax()) {
            return Permission::all();
        }

        $permissions = Permission::paginate(30);
        return adminView('permissions.all', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return "add permission to Permissions/config/permissions.php and run sync route";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return "update Permissions/config/permissions.php and run sync route";
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sync(Request $request) {

        $this->apiSecurity('permissions');

        $appName = env('APP_NAME');
        $appNameToLower = strtolower($appName);

        if (file_exists(base_path("projects/{$appName}/config/{$appNameToLower}_permissions.php"))) {
            $this->permissions = array_merge(config("{$appNameToLower}_permissions"), $this->permissions);
        }

        foreach ($this->permissions as $name => $value) {

            $permission = $this->permission([
                'name'  => $name,
                'label' => $value['title']
            ]);

            foreach ($value['permissions'] as $n => $v) {

                $this->permission([
                    'name'   => $n,
                    'label'  => $v['title'],
                    'parent' => $permission->id
                ]);

            }

        }

        deleteCache('permissions');

        if ($request->ajax()) {
            return Permission::all();
        }

        return redirect(route('admin.roles.index'));

    }

    public function permission($data) {

        $where = $data;

        if (isset($where['label']))
            unset($where['label']);

        if (Permission::where($where)->exists()) {
            Permission::where($where)->update($data);
            return Permission::where($where)->first();
        } else {
            return Permission::create($data);
        }

    }

}
