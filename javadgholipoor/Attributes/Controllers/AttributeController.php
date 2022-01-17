<?php

namespace LaraBase\Attributes\Controllers;

use Illuminate\Http\Request;
use LaraBase\Attributes\Models\Attribute;
use LaraBase\Attributes\Models\AttributeKey;
use LaraBase\Attributes\Models\AttributeRelation;
use LaraBase\Attributes\Models\AttributeValue;
use LaraBase\CoreController;
use LaraBase\Posts\Models\Language;

class AttributeController extends CoreController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    can('attributes');
        $records = Attribute::latest()->paginate(30);
        return adminView('attributes.all', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    can('createAttribute');
        $types = attributeTypes();
        $user = auth()->user();
        $canSetPostTypes = $user->canSetPostTypes();
        $postTypes = $canSetPostTypes['postTypes'];
        return adminView('attributes.create', compact('types', 'postTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        can('createAttribute');
        $this->validateStore($request);
        $record = Attribute::create($request->all());

        if ($request->has('keys')) {
            foreach ($request->input('keys') as $keyTitle) {
                $key = AttributeKey::where('title', $keyTitle)->first();
                if ($key == null) {
                    $key = AttributeKey::create(['title' => $keyTitle]);
                }
                AttributeRelation::create([
                    'key'   => 'attribute_key',
                    'value' => $record->id,
                    'more'  => $key->id
                ]);
            }
        }

        if ($request->has('post_types')) {
            foreach ($request->input('post_types') as $postType) {
                AttributeRelation::create([
                    'key'   => 'attribute_postType',
                    'value' => $record->id,
                    'more'  => $postType
                ]);
            }
        }

        if ($request->has('plan')) {
            if ($request->plan == '1') {
                AttributeRelation::create([
                    'key'   => 'attribute_plan',
                    'value' => $record->id,
                    'more'  => '1'
                ]);
            }
        }

        session()->flash('success', 'با موفقیت درج شد');
        return redirect(route('admin.attributes.index'));
    }

    public function validateStore($request)
    {
        return $request->validate([
            'title' => 'required|max:191',
            'type'  => 'required',
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
	    can('updateAttribute');
        $record = Attribute::where('id', $id)->first();
        $types = attributeTypes();
        $user = auth()->user();
        $canSetPostTypes = $user->canSetPostTypes();
        $postTypes = $canSetPostTypes['postTypes'];
        $attributePostTypes = AttributeRelation::where(['key' => 'attribute_postType', 'value' => $record->id])->pluck('more')->toArray();
        $attributeKeys = AttributeKey::whereIn('id', AttributeRelation::where(['key' => 'attribute_key', 'value' => $record->id])->pluck('more')->toArray())->get();
        $attributePlan = 0;
        if (AttributeRelation::where(['key' => 'attribute_plan', 'value' => $record->id])->exists()) {
            $attributePlan = 1;
        }
        return adminView('attributes.edit', compact('record', 'types', 'postTypes', 'attributePostTypes', 'attributeKeys', 'attributePlan'));
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

	    can('updateAttribute');
        $record = Attribute::where('id', $id)->first();
        $this->validateUpdate($request, $record);
        $record->update($request->all());

        if ($record->parent == null) {
            Attribute::where('parent', $id)->update([
                'type' => $request->type,
                'icon' => $request->icon,
            ]);
        }

        AttributeRelation::where('value', $record->id)->whereIn('key', ['attribute_key', 'attribute_postType', 'attribute_plan'])->delete();

        if ($request->has('keys')) {
            foreach ($request->input('keys') as $keyTitle) {
                $key = AttributeKey::where('title', $keyTitle)->first();
                if ($key == null) {
                    $key = AttributeKey::create(['title' => $keyTitle]);
                }
                AttributeRelation::create([
                    'key'   => 'attribute_key',
                    'value' => $record->id,
                    'more'  => $key->id
                ]);
            }
        }

        if ($request->has('post_types')) {
            foreach ($request->input('post_types') as $postType) {
                AttributeRelation::create([
                    'key'   => 'attribute_postType',
                    'value' => $record->id,
                    'more'  => $postType
                ]);
            }
        }

        if ($request->has('plan')) {
            if ($request->plan == '1') {
                AttributeRelation::create([
                    'key'   => 'attribute_plan',
                    'value' => $record->id,
                    'more'  => '1'
                ]);
            }
        }

        session()->flash('success', 'بروزرسانی با موفقیت انجام شد');
        return redirect(route('admin.attributes.index'));
    }

    protected function validateUpdate(Request $request,$record)
    {

        if ($record->parent == null) {
            return $request->validate([
                'title' => 'required|max:191',
                'type'  => 'required',
            ]);
        } else {
            return $request->validate([
                'title' => 'required|max:191'
            ]);
        }

    }

    public function destroyConfirm($id) {
        can('deleteAttribute');
        $record = Attribute::find($id);
        return adminView('attributes.destroy', compact('record'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
	    can('deleteAttribute');
        $record = Attribute::find($id);
        $record->delete();

        $attributeKeys = [
            'attribute_key', 'attribute_value', 'attribute_postType', 'attribute_plan'
        ];

        AttributeRelation::where('value', $id)->whereIn('key', $attributeKeys)->delete();

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.attributes.index'));

    }

    public function keys($attributeId) {
        $output = [
            'status'  => "error",
            'message' => 'خطایی پیش آمده لطفا مجددا امتحان کنید.'
        ];

        $attribute = Attribute::where('id', $attributeId)->first();
        $attributeKeys = AttributeKey::where('attribute_id', $attributeId)->get();
        $output['status'] = "success";
        $output['message'] = "";
        $output['attribute'] = $attribute;
        $output['attributeKeys'] = $attributeKeys;

        return response()->json($output);
    }

    public function values($attributeKeyId)
    {
        $output = [
            'status'    => "error",
            'message'   => 'خطایی پیش آمده لطفا مجددا امتحان کنید.'
        ];

        $attributeKey = AttributeKey::where('id', $attributeKeyId)->first();
        $attributeKeyValue = AttributeValue::where('attribute_key_id', $attributeKeyId)->get();
        $output['status'] = "success";
        $output['message'] = "";
        $output['attributeKey']     = $attributeKey;
        $output['attributeKeyValues'] = $attributeKeyValue;

        return response()->json($output);

    }

    public function searchKeys()
    {

        $term = null;
        if (isset($_GET['term']))
            $term = $_GET['term'];

        $records = AttributeKey::where('title', 'like', "%{$term}%")->limit(100)->get();

        $output = [
            'items' => []
        ];

        foreach ($records as $record) {
            $output['items'][] = [
                'id'  => $record->title,
                'text' => $record->title,
            ];
        }

        return response()->json($output);

    }

    public function searchValues()
    {

        $term = null;
        if (isset($_GET['term']))
            $term = $_GET['term'];

        $records = AttributeValue::where('title', 'like', "%{$term}%")->limit(100)->get();

        $output = [
            'items' => []
        ];

        foreach ($records as $record) {
            $output['items'][] = [
                'id'  => $record->title,
                'text' => $record->title,
            ];
        }

        return response()->json($output);

    }

    public function survey()
    {

        $term = null;
        if (isset($_GET['term']))
            $term = $_GET['term'];

        $records = Attribute::where([
            ['title', 'like', "%{$term}%"],
            'type' => 'comment'
        ])->limit(100)->get();

        $output = [
            'items' => []
        ];

        foreach ($records as $record) {
            $output['items'][] = [
                'id'  => $record->title,
                'text' => $record->title,
            ];
        }

        return response()->json($output);

    }

    public function search()
    {

        can('postSearchAttribute');

        $relations       = AttributeRelation::whereIn('key', ['attribute_postType', 'attribute_key', 'key_value'])->get();
        $attributes      = Attribute::where('type', 'post')->get();
        $attributeKeys   = AttributeKey::all();
        $attributeValues = AttributeValue::all();

        $data = [];
        foreach (array_unique($relations->where('key', 'attribute_postType')->pluck('more')->toArray()) as $postType) {

            $attrIds = $relations->where('key', 'attribute_postType')->where('more', $postType)->pluck('value')->toArray();
            foreach ($attributes->whereIn('id', $attrIds)->filter() as $attr) {

                $data[$postType][$attr->id] = [
                    'id' => $attr->id,
                    'title' => $attr->title
                ];

                $keyIds = $relations->where('key', 'attribute_key')->where('value', $attr->id)->pluck('more')->toArray();
                foreach ($attributeKeys->whereIn('id', $keyIds)->filter() as $key) {

                    $data[$postType][$attr->id]['keys'][$key->id] = [
                        'id' => $key->id,
                        'title' => $key->title
                    ];

                    $valueIds = $relations->where('key', 'key_value')->where('value', $key->id)->pluck('more')->toArray();
                    foreach ($attributeValues->whereIn('id', $valueIds)->filter() as $value) {

                        $data[$postType][$attr->id]['keys'][$key->id]['values'][$value->id] = [
                            'id' => $value->id,
                            'title' => $value->title
                        ];

                    }

                }

            }

        }

        $old =AttributeRelation::where('key', 'search_postType_akv')->pluck('more')->toArray();
        return adminView('attributes.search', compact('data', 'old'));

    }

    public function searchUpdate(Request $request)
    {

        can('postSearchAttribute');
        AttributeRelation::where('key', 'search_postType_akv')->delete();
        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $postType => $akv_s) {
                foreach ($akv_s as $akv) {
                    AttributeRelation::create([
                        'key' => 'search_postType_akv',
                        'value' => $postType,
                        'more' => $akv
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'با موفقیت بروزرسانی شد');

    }

    public function translate()
    {
        can('translateAttributes');
        $lang = Language::where('lang', $_GET['lang'])->first();
        $records = Attribute::leftJoin('attributes as a', function ($join) use ($lang) {
            $join->on('attributes.id', '=', 'a.parent')->where('a.lang', $lang->lang);
        })->whereNull('attributes.parent')->whereNull('a.id')->selectRaw('attributes.*')->paginate(10);
        return adminView('attributes.language', compact('records', 'lang'));
    }

    public function storeTranslate(Request $request)
    {

        can('translateAttributes');
        $request->validate(['lang' => 'required']);
        $lang = $request->lang;

        $attrs = Attribute::whereIn('id', $request->ids)->get();

        if ($request->has('data')) {
            if (!empty($request->data)) {
                foreach ($request->data as $id => $item) {
                    if (!empty($item['title'])) {

                        $attr = $attrs->where('id', $id)->first();
                        $add = Attribute::create([
                            'title' => $item['title'],
                            'description' => $item['description'] ?? null,
                            'icon' => $attr->icon,
                            'type' => $attr->type,
                            'lang' => $lang,
                            'parent' => $id
                        ]);

                    }
                }
            }
        }

        session()->flash('success', 'با موفقیت انجام شد');
        return redirect()->back();

    }

}
