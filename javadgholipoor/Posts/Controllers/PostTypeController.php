<?php

namespace LaraBase\Posts\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use LaraBase\CoreController;
use LaraBase\Menus\Models\MenuMeta;
use LaraBase\Posts\Models\PostType;

class PostTypeController extends CoreController
{

    public function __construct() {
        if(!isDev()) {
            abort(404);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $records = PostType::paginate(30);
        return adminView('postTypes.all', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $getPostTypes = $user->canSetPostTypes();
        $postTypes = $getPostTypes['postTypes'];
        return adminView('postTypes.create', compact('postTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $this->storeValidator($request);
        $postType = PostType::create([
            'user_id'   => auth()->id(),
            'label'     => $request->label,
            'type'      => $request->type
        ]);
        $this->clearCache();
        return redirect()->route('admin.post-types.edit', $postType);

    }

    public function storeValidator($request) {
        return $request->validate([
            'label' => 'required',
            'type'  => 'required|regex:/^[a-z]+$/u|unique:post_types,type',
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
        $user = auth()->user();
        $postType = PostType::find($id);
        if (!isDev()) {
            $user->checkPostTypePermission($postType->type, 'postType_update');
        }

        $userRoles = $user->roles();
        $postBoxes = $user->canSetPostBoxes($userRoles);
        $selectedPostboxes = json_decode($postType->boxes);
        $sizes = json_decode($postType->sizes, true);

        $validations = [];
        if (!empty($postType->validations)) {
            $validations = json_decode($postType->validations);
        }

        $metas = [];
        if (!empty($postType->metas)) {
            $metas = json_decode($postType->metas, true);
        }

        return adminView('postTypes.edit', compact(
        'postType',
            'postBoxes',
            'selectedPostboxes',
            'validations',
            'sizes',
            'metas'
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
        $user = auth()->user();
        $postType = PostType::find($id);
        $user->checkPostTypePermission($postType->type, 'postType_update');
        $this->updateValidator($request, $id);

        $metas = null;
        if ($request->has('metas')) {
            if (!empty($request->metas)) {
                $metas = toEnglish(json_encode($request->metas));
            }
        }

        $data = [
            'label' => $request->label,
            'total_label' => $request->total_label,
            'type' => $request->type,
            'boxes' => json_encode($request->boxes),
            'validations' => json_encode($request->validations),
            'icon' => $request->icon,
            'sitemap' => $request->sitemap ?? '0',
            'search' => $request->search ?? '0',
            'metas' => $metas
        ];

        PostType::where('id', $id)->update($data);
        $this->clearCache($id);

        session()->flash('success', 'بروزرسانی با موفقیت انجام شد');
        return redirect()->back();
    }

    public function updateValidator($request, $id) {
        return $request->validate([
            'label'           => 'required',
            'total_label'     => 'required',
            'type'            => 'required|regex:/^[a-z]+$/u|unique:post_types,type,' . $id,
            'boxes'           => 'required',
            'validations'     => 'required',
        ]);
    }

    public function destroyConfirm($id) {
        $record = PostType::find($id);
        return adminView('postTypes.destroy', compact('record'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $record = PostType::find($id);
        $record->delete();
        $this->clearCache($id);

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.tags.index'));
    }

    public function clearCache($postTypeId = false) {
        $appName = env('APP_NAME');
        $postType = getPostTypeId($postTypeId);

        if ($postType != null) {
            $postTypeIdKey = "{$appName}postType_{$postTypeId}";
            if (Cache::has($postTypeIdKey))
                Cache::delete($postTypeIdKey);

            $postTypeKey = "{$appName}postTypeId_{$postType->type}";
            if (Cache::has($postTypeKey))
                Cache::delete($postTypeKey);
        }

        $postTypesKey = "{$appName}postTypes";
        if (Cache::has($postTypesKey))
            Cache::delete($postTypesKey);

        $this->menusClearCache($postTypeId);
    }

    public function menusClearCache($postTypeId) {

        $cacheKeys = MenuMeta::where(['key' => 'cache', 'value' => 'postTypes'])->pluck('more')->toArray();

        if ($postTypeId) {
            $cacheKeys = array_merge(MenuMeta::where('key', 'cache')->whereIn('value', [
                "categoriesPostTypes_" . $postTypeId,
                "postsPostTypes_" . $postTypeId
            ])->pluck('more')->toArray(), $cacheKeys);
        }

        foreach (array_unique($cacheKeys) as $cacheKey) {
            if (Cache::has($cacheKey)) {
                Cache::delete($cacheKey);
            }
        }

    }

}
