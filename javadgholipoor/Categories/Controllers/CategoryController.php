<?php

namespace LaraBase\Categories\Controllers;

use App\Events\SendTelegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use LaraBase\Categories\Models\Category;
use LaraBase\CoreController;
use LaraBase\Menus\Models\MenuMeta;
use LaraBase\Posts\Models\Language;
use LaraBase\Telegram\Telegram;

class CategoryController extends CoreController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        can('categories');

        $categories = Category::canView()
                                ->postType()
                                ->latest()
                                ->paginate(200)
                                ->appends(request()->query());

        return adminView('categories.all', compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        can('createCategory');
        $user = auth()->user();
        $postTypes = $user->canSetPostTypes()['postTypes'];
        $languages = Language::all();
        return adminView('categories.create', compact('postTypes', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        can('createCategory');
        auth()->user()->checkPostTypePermission($request->post_type, 'category_create');
        $this->validateStore($request);
        $post = Category::create($request->all());
        $this->clearCache($request);
        return redirect()->route('admin.categories.edit', ['id' => $post->id]);
    }

    protected function validateStore(Request $request)
    {

        return $this->validate($request, [
            'title'     => 'required',
            'post_type' => 'required'
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
        can('showCategory');
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
        can('updateCategory');
        $languages = Language::all();
        $category = Category::find($id);
        $categories = auth()->user()->canSetCategories(false, ['post_type' => $category->post_type]);
        return adminView('categories.edit', compact('category', 'categories', 'languages'));
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
        can('updateCategory');
        $category = Category::find($id);
        auth()->user()->checkPostTypePermission($category->post_type, 'category_update');
        $this->validateUpdate($request, $id);
        $category->update($request->all());
        session()->flash('success', 'بروزرسانی با موفقیت انجام شد');
        $this->clearCache($request, $category->parent, $id);
        return redirect(route('admin.categories.index'));
    }

    protected function validateUpdate(Request $request, $id)
    {

        return $this->validate($request, [
            'title'     => 'required',
            'slug'      => "unique:categories,slug,{$id}",
        ]);

    }

    public function destroyConfirm($id) {
        $record = Category::find($id);
        return adminView('categories.destroy', compact('record'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        can('deleteCategory');
        $record = Category::find($id);
        auth()->user()->checkPostTypePermission($record->post_type, 'category_delete');
        $record->delete();
        $this->clearCache($request, null, $id);

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.categories.index'));
    }

    public function clearCache($request, $oldParent = null, $categoryId = false) {

        $categoryIds = [];
        $newParent = null;

        try {
            if ($request->has('parent')) {
                if (!empty($request->parent)) {
                    $parent = $request->parent;
                    $newParent = $parent;
                    while (true) {
                        $category = Category::find($parent);
                        $categoryIds[] = $category->id;
                        if ($category->parent == null) {
                            break;
                        }
                        $parent = $category->parent;
                    }
                }
            }
        } catch (\Exception $error) {
            SendTelegram::dispatch('categories cache error 1');
        }

        if ($newParent != $oldParent) {
            if ($oldParent != null) {
                try {
                    while (true) {
                        $category = Category::find($oldParent);
                        $categoryIds[] = $category->id;
                        if ($category->parent == null) {
                            break;
                        }
                        $oldParent = $category->parent;
                    }
                } catch (\Exception $error) {
                    SendTelegram::dispatch('categories cache error 2');
                }
            }
        }

        if ($categoryId) {
            try {
                while (true) {
                    $category = Category::find($categoryId);
                    $categoryIds[] = $category->id;
                    if ($category->parent == null) {
                        break;
                    }
                    $categoryId = $category->parent;
                }
            } catch (\Exception $error) {
                SendTelegram::dispatch('categories cache error 3');
            }
        }

        $keys = [];
        foreach (array_unique($categoryIds) as $categoryId) {
            $keys[] = "postsCategories_{$categoryId}";
            $keys[] = "categoriesCategories_{$categoryId}";
        }

        $cacheKeys = MenuMeta::where('key', 'cache')->whereIn('value', $keys)->pluck('more')->toArray();
        foreach (array_unique($cacheKeys) as $cacheKey) {
            if (Cache::has($cacheKey)) {
                Cache::delete($cacheKey);
            }
        }

    }

}
