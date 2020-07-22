<?php

namespace LaraBase\Payment\Controllers;

use Illuminate\Http\Request;
use LaraBase\Auth\Models\User;
use LaraBase\CoreController;
use LaraBase\Payment\Models\Transaction;

class TransactionController extends CoreController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'تراکنش ها';
        $records = Transaction::status()->with('user')->latest()->paginate(50);
        return adminView('transactions.all', compact('records', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        can('createTransaction');
        return adminView('transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        can('createTag');
        $request->validate(['tag' => 'required|max:191|unique:tags,tag']);
        $tag = Tag::create($request->all());
        session()->flash('success', 'با موفقیت درج شد');
        return redirect(route('admin.tags.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        can('showTag');
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        can('updateTag');
        $tag = Tag::find($id);
        return adminView('tags.edit', compact('tag'));
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
        can('updateTag');
        $tag = Tag::find($id);
        $request->validate(['tag' => 'required|max:191|unique:tags,tag,' . $tag->id]);
        $tag->update($request->all());
        session()->flash('success', 'بروزرسانی با موفقیت انجام شد');
        $this->clearCache($id);
        return redirect(route('admin.tags.index'));
    }

    public function destroyConfirm($id) {
        $record = Tag::find($id);
        return adminView('tags.destroy', compact('record'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        can('deleteTag');
        $record = Tag::find($id);
        $record->delete();
        $this->clearCache($id);

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.tags.index'));
    }

    public function search() {

        $tag = null;
        if (isset($_GET['term']))
            $tag = $_GET['term'];

        $tags = Tag::where('tag', 'like', "%{$tag}%")->limit(100)->get();

        $output = [
            'items' => []
        ];

        foreach ($tags as $tag) {
            $output['items'][] = [
                'id'  => $tag->tag,
                'text' => $tag->tag,
            ];
        }

        return response()->json($output);

    }

    public function clearCache($tagId) {
        $this->menusClearCache($tagId);
    }

    public function menusClearCache($tagId) {

        $cacheKeys = MenuMeta::where(['key' => 'cache', 'value' => "postsTags_" . $tagId])->pluck('more')->toArray();
        foreach (array_unique($cacheKeys) as $cacheKey) {
            if (Cache::has($cacheKey)) {
                Cache::delete($cacheKey);
            }
        }

    }

}
