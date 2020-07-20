<?php

namespace LaraBase\Attributes\Controllers;

use Illuminate\Http\Request;
use LaraBase\Attributes\Models\Attribute;
use LaraBase\Attributes\Models\AttributeKey;
use LaraBase\Attributes\Models\AttributeKeyPostType;
use LaraBase\Attributes\Models\AttributeRelation;
use LaraBase\Attributes\Models\AttributeValue;
use LaraBase\CoreController;
use LaraBase\Posts\Models\Language;

class AttributeKeyController extends CoreController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    can('listAttributeKey');
        $records = AttributeKey::orderBy('updated_at', 'asc')->paginate(30);
        return adminView('attributes.keys.all', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    can('createAttributeKey');
	    $attributes = Attribute::all();
        return adminView('attributes.keys.create', compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        can('createAttributeKey');
        $this->validateStore($request);
        $record = AttributeKey::create($request->all());

        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $attributeId) {
                AttributeRelation::create([
                    'key'   => 'attribute_key',
                    'value' => $attributeId,
                    'more'  => $record->id
                ]);
            }
        }

        if ($request->has('values')) {
            foreach ($request->input('values') as $valueTitle) {
                $value = AttributeValue::where('title', $valueTitle)->first();
                if ($value == null) {
                    $value = AttributeValue::create(['title' => $valueTitle]);
                }
                AttributeRelation::create([
                    'key'   => 'key_value',
                    'value' => $record->id,
                    'more'  => $value->id
                ]);
            }
        }

        session()->flash('success', 'با موفقیت درج شد');
        return redirect(route('admin.attribute-keys.index'));
    }

    public function validateStore($request)
    {
        return $request->validate([
            'title' => 'required|max:191|unique:attribute_keys,title',
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
	    can('updateAttributeKey');
        $record = AttributeKey::find($id);
        $attributes = Attribute::all();
        $keyAttributes = AttributeRelation::where(['key' => 'attribute_key', 'more' => $record->id])->pluck('value')->toArray();
        $keyValues = AttributeValue::whereIn('id', AttributeRelation::where(['key' => 'key_value', 'value' => $record->id])->pluck('more')->toArray())->get();
        return adminView('attributes.keys.edit', compact('record', 'attributes', 'keyAttributes', 'keyValues'));
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
	    can('updateAttributeKey');
        $record = AttributeKey::where('id', $id)->first();
        $this->validateUpdate($request, $id);
        $record->update($request->all());

        if ($record->parent == null) {
            AttributeKey::where('parent', $id)->update([
                'icon' => $request->icon,
            ]);
        }

        AttributeRelation::where('more', $record->id)->whereIn('key', ['attribute_key'])->delete();
        AttributeRelation::where('value', $record->id)->whereIn('key', ['key_value'])->delete();

        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $attributeId) {
                AttributeRelation::create([
                    'key'   => 'attribute_key',
                    'value' => $attributeId,
                    'more'  => $record->id
                ]);
            }
        }

        if ($request->has('values')) {
            foreach ($request->input('values') as $valueTitle) {
                $value = AttributeValue::where('title', $valueTitle)->first();
                if ($value == null) {
                    $value = AttributeValue::create(['title' => $valueTitle]);
                }
                AttributeRelation::create([
                    'key'   => 'key_value',
                    'value' => $record->id,
                    'more'  => $value->id
                ]);
            }
        }

        session()->flash('success', 'بروزرسانی با موفقیت انجام شد');
        return redirect(route('admin.attribute-keys.index', ['id' => $record->attribute_id]));
    }

    protected function validateUpdate(Request $request, $id)
    {

        return $request->validate([
            'title' => 'required|max:191|unique:attribute_keys,title,' . $id,
        ]);

    }

    public function destroyConfirm($id) {
        can('deleteAttributeKey');
        $record = AttributeKey::find($id);
        return adminView('attributes.keys.destroy', compact('record'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        can('deleteAttributeKey');
        $record = AttributeKey::find($id);
        $record->delete();

        AttributeRelation::where('more', $id)->whereIn('key', [
            'attribute_key'
        ])->delete();

        AttributeRelation::where('value', $id)->whereIn('key', [
            'key_value'
        ])->delete();

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.attributes.keys.index'));
    }

    public function translate()
    {
        can('translateAttributeKeys');
        $lang = Language::where('lang', $_GET['lang'])->first();
        $records = AttributeKey::leftJoin('attribute_keys as a', function ($join) use ($lang) {
            $join->on('attribute_keys.id', '=', 'a.parent')->where('a.lang', $lang->lang);
        })->whereNull('attribute_keys.parent')->whereNull('a.id')->selectRaw('attribute_keys.*')->paginate(10);
        return adminView('attributes.keys.language', compact('records', 'lang'));
    }

    public function storeTranslate(Request $request)
    {

        can('translateAttributeKeys');
        $request->validate(['lang' => 'required']);
        $lang = $request->lang;

        $attrs = AttributeKey::whereIn('id', $request->ids)->get();

        if ($request->has('data')) {
            if (!empty($request->data)) {
                foreach ($request->data as $id => $item) {
                    if (!empty($item['title'])) {

                        $attr = $attrs->where('id', $id)->first();
                        if (!AttributeKey::where('title', $item['title'])->exists()) {
                            $add = AttributeKey::create([
                                'title' => $item['title'],
                                'description' => $item['description'] ?? null,
                                'icon' => $attr->icon,
                                'lang' => $lang,
                                'parent' => $id
                            ]);
                        }

                    }
                };
            }
        }

        session()->flash('success', 'با موفقیت انجام شد');
        return redirect()->back();

    }

}
