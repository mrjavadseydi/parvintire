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
        can('transactions');
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
        can('createTransaction');
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
        can('showTransaction');
        $transaction = Transaction::find($id);
        return adminView('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        can('updateTransaction');
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
        can('updateTransaction');
        $tag = Tag::find($id);
        $request->validate(['tag' => 'required|max:191|unique:tags,tag,' . $tag->id]);
        $tag->update($request->all());
        session()->flash('success', 'بروزرسانی با موفقیت انجام شد');
        $this->clearCache($id);
        return redirect(route('admin.tags.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        can('deleteTransaction');
        $record = Tag::find($id);
        $record->delete();
        $this->clearCache($id);

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.tags.index'));
    }

}
