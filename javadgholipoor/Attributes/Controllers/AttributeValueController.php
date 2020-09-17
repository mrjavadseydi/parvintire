<?php

namespace LaraBase\Attributes\Controllers;

use Illuminate\Http\Request;
use LaraBase\Attributes\Models\AttributeKey;
use LaraBase\Attributes\Models\AttributeRelation;
use LaraBase\Attributes\Models\AttributeValue;
use LaraBase\CoreController;
use LaraBase\Posts\Models\Language;

class AttributeValueController extends CoreController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    can('listAttributeValue');
        $records = AttributeValue::latest()->paginate(30);
        return adminView('attributes.values.all', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    can('createAttributeValue');
	    $keys = AttributeKey::all();
        return adminView('attributes.values.create', compact('keys'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        can('createAttributeValue');

        $this->validateStore($request);
        $this->validateProjectValueMetas($request);
        $record = AttributeValue::create($request->all());

        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $key => $value) {
                AttributeRelation::create([
                    'key' => "value_{$key}",
                    'value' => $record->id,
                    'more'  => toEnglish($value)
                ]);
            }
        }

        if ($request->has('keys')) {
            foreach ($request->keys as $keyId) {
                AttributeRelation::create([
                    'key'   => 'key_value',
                    'value' => $keyId,
                    'more'  => $record->id
                ]);
            }
        }

        session()->flash('success', 'با موفقیت درج شد');
        return redirect(route('admin.attribute-values.index'));
    }

    public function validateStore($request)
    {
        return $request->validate([
            'title' => 'required|max:191|unique:attribute_values,title',
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
	    can('updateAttributeValue');
        $record = AttributeValue::where('id', $id)->first();
        $keys = AttributeKey::all();
        $valueKeys = AttributeRelation::where(['key' => 'key_value', 'more' => $record->id])->pluck('value')->toArray();
        $valueAttributes = [];
        foreach (AttributeRelation::where('key', 'like', '%value_%')->where('value', $record->id)->get() as $item) {
            $valueAttributes[str_replace('value_', '', $item->key)] = $item->more;
        }
        return adminView('attributes.values.edit', compact('record', 'keys', 'valueKeys', 'valueAttributes'));
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
	    can('updateAttributeValue');
        $this->validateUpdate($request, $id);
        $this->validateProjectValueMetas($request);
        $record = AttributeValue::where('id', $id)->first();
        $record->update($request->all());

        AttributeRelation::where('value', $record->id)->where('key', 'like', '%value_%')->delete();
        AttributeRelation::where('more', $record->id)->whereIn('key', ['key_value'])->delete();

        if ($request->has('keys')) {
            foreach ($request->keys as $keyId) {
                AttributeRelation::create([
                    'key'   => 'key_value',
                    'value' => $keyId,
                    'more'  => $record->id
                ]);
            }
        }

        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $key => $value) {
                AttributeRelation::create([
                    'key' => "value_{$key}",
                    'value' => $record->id,
                    'more'  => toEnglish($value)
                ]);
            }
        }

        if ($record->parent == null) {

            AttributeValue::where('parent', $id)->update([
                'icon' => $request->icon,
            ]);

            $parents = AttributeValue::where('parent', $record->id)->get();

            foreach ($parents as $parent) {

                AttributeRelation::where('value', $parent->id)->where('key', 'like', '%value_%')->delete();
                AttributeRelation::where('more', $parent->id)->whereIn('key', ['key_value'])->delete();

                if ($request->has('keys')) {
                    foreach ($request->keys as $keyId) {
                        AttributeRelation::create([
                            'key'   => 'key_value',
                            'value' => $keyId,
                            'more'  => $parent->id
                        ]);
                    }
                }

                if ($request->has('attributes')) {
                    foreach ($request->input('attributes') as $key => $value) {
                        AttributeRelation::create([
                            'key' => "value_{$key}",
                            'value' => $parent->id,
                            'more'  => toEnglish($value)
                        ]);
                    }
                }

            }



        }

        session()->flash('success', 'بروزرسانی با موفقیت انجام شد');
        return redirect(route('admin.attribute-values.index'));

    }

    protected function validateUpdate(Request $request, $id)
    {
        return $request->validate([
            'title' => 'required|max:191|unique:attribute_values,title,' . $id,
        ]);

    }

    public function destroyConfirm($id) {
        can('deleteAttributeValue');
        $record = AttributeValue::find($id);
        return adminView('attributes.values.destroy', compact('record'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        can('deleteAttributeValue');
        $record = AttributeValue::find($id);

        $record->delete();

        AttributeRelation::where('value', $id)->where('key', 'like', '%value_%')->delete();

        AttributeRelation::where('more', $id)->whereIn('key', [
            'key_value'
        ])->delete();

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.attributes.values.index'));
    }

    public function validateProjectValueMetas($request) {
        $projectValueMetas = getProjectValueMetas();
        $projectValueMetasRules = $projectValueMetasMessages = [];
        foreach ($projectValueMetas as $key => $value) {
            foreach ($value['validations'] as $rule => $message) {

                $addToValidations = true;

                if ($request->has("attributes.{$key}")) {
                    if ($value['nullable']) {
                        if (empty($request->input("attributes.{$key}"))) {
                            $addToValidations = false;
                        }
                    }
                }

                if ($addToValidations) {
                    $projectValueMetasRules["attributes.{$key}"] = $rule;
                    $projectValueMetasMessages["attributes.{$key}.{$rule}"] = $message;
                }

            }
        }

        $request->validate($projectValueMetasRules, $projectValueMetasMessages);
    }

    public function translate()
    {
        can('translateAttributeValues');
        $lang = Language::where('lang', $_GET['lang'])->first();
        $records = AttributeValue::leftJoin('attribute_values as a', function ($join) use ($lang) {
            $join->on('attribute_values.id', '=', 'a.parent')->where('a.lang', $lang->lang);
        })->whereNull('attribute_values.parent')->whereNull('a.id')->selectRaw('attribute_values.*')->paginate(10);
        return adminView('attributes.values.language', compact('records', 'lang'));
    }

    public function storeTranslate(Request $request)
    {

        can('translateAttributeValues');
        $request->validate(['lang' => 'required']);
        $lang = $request->lang;

        $attrs = AttributeValue::whereIn('id', $request->ids)->get();

        if ($request->has('data')) {
            if (!empty($request->data)) {
                foreach ($request->data as $id => $item) {
                    if (!empty($item['title'])) {
                        $attr = $attrs->where('id', $id)->first();
                        if (!AttributeValue::where('title', $item['title'])->exists()) {
                            $add = AttributeValue::create([
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
