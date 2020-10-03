<?php

namespace LaraBase\Posts\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use LaraBase\Attributes\Models\Attribute;
use LaraBase\Attributes\Models\AttributeKey;
use LaraBase\Attributes\Models\AttributeRelation;
use LaraBase\Attributes\Models\AttributeValue;
use LaraBase\Auth\Models\User;
use LaraBase\Categories\Models\Category;
use LaraBase\CoreController;
use LaraBase\Helpers\MobileDetect;
use LaraBase\Menus\Models\MenuMeta;
use LaraBase\Posts\Models\Favorite;
use LaraBase\Posts\Models\Language;
use LaraBase\Posts\Models\Like;
use LaraBase\Posts\Models\Post;
use LaraBase\Posts\Models\PostCategory;
use LaraBase\Posts\Models\PostMeta;
use LaraBase\Posts\Models\PostAttribute;
use LaraBase\Posts\Models\PostTag;
use LaraBase\Posts\Models\Rate;
use LaraBase\Posts\Models\Search;
use LaraBase\Store\Models\Product;
use LaraBase\Store\Models\ProductAttribute;
use LaraBase\Store\Models\ProductFile;
use LaraBase\Tags\Models\Tag;
use LaraBase\World\Controllers\DistanceController;
use LaraBase\World\models\City;
use LaraBase\World\models\Province;
use LaraBase\World\models\Town;

class PostController extends CoreController
{

    public $menusCacheTags = [];
    public $menusCacheCategories = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = auth()->user();
        $postType = 'all';
        $postTypes = $user->canSetPostTypes()['postTypes'];

        $posts = Post::canView()
            ->search()
            ->users()
            ->postTypes()
            ->world()
            ->categories()
            ->statuses()
            ->finalStatuses()
            ->orderBy('id', 'desc')
            ->paginate(30)
            ->appends(request()->query());

        return adminView('posts.all', compact('postType', 'postTypes', 'posts'));
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
        return adminView('posts.create', compact('postTypes'));

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
        $user->checkPostTypePermission($request->type, 'post_create');
        $post = Post::create([
            'user_id'   => auth()->id(),
            'title'     => $request->title,
            'post_type' => $request->type
        ]);
        $this->clearCache($request, $post->post_type);
        return redirect()->route('admin.posts.edit', $post);

    }

    public function storeValidator($request) {
        return $request->validate([
            'type'  => 'required',
            'title' => 'required'
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

        can('updatePost');

        $post = Post::find($id);
        $metas = $post->metas();
        $thumbnail = $post->originalThumbnail();
        $gallery = $post->gallery();

        $parent = null;
        if (!empty($post->parent)) {
            $parent = Post::find($post->parent);

            if (empty($post->thumbnail))
                $thumbnail = $parent->originalThumbnail();

            if ($gallery->count() == 0)
                $gallery = $parent->gallery();
        }

        $postType = $post->type();
        $postTypeMetas =  $postType->metas();
        $postMenus = $post->menus($postTypeMetas);

        $postBoxes = $post->boxes();
        $user = auth()->user();
        $userRoles = $user->roles();
        $canSetPostTypes = $user->canSetPostTypes($userRoles);
        $post->canEdit($user, $canSetPostTypes);
        $sounds = $post->sounds();
        $duration = $post->duration();

        $statuses = [];
        $statusConfig = config('statusConfig');
        foreach ($canSetPostTypes['groups']['status'] as $status) {
            if (isset($statusConfig[$status]))
                $statuses[$status] = $statusConfig[$status];
        }

        $boxes = $user->canSetPostBoxes($userRoles);
        $projectBoxes = getProjectBoxes();
        $categoryList = $user->canSetCategories($userRoles, ['post_type' => $post->post_type, 'lang' => $post->lang]);
        $postCategories = $post->categories->pluck('id')->toArray();
        $attributes = $post->attributes(false, $post->lang);

        return adminView('posts.edit', compact(
            'user',
            'post',
            'parent',
            'postType',
            'postTypeMetas',
            'statuses',
            'boxes',
            'postBoxes',
            'projectBoxes',
            'categoryList',
            'postCategories',
            'gallery',
            'sounds',
            'duration',
            'postMenus',
            'thumbnail',
            'attributes',
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
        can('updatePost');
        $post = Post::find($id);
        $postBoxes = $post->boxes();
        $user = auth()->user();
        $canSetPostTypes = $user->canSetPostTypes();
        $canSetPostBoxes = $user->canSetPostBoxes();
        $this->updateValidator($request, $post, $user, $canSetPostTypes, $canSetPostBoxes, $postBoxes);

        $projectBox = [];

        foreach ($canSetPostBoxes as $method => $box) {
            if (in_array($method, $postBoxes)) {
                if (method_exists($this, $method)) {
                    call_user_func_array([$this, $method], [$request, $post]);
                } else {
                    $projectBox[$method] = $box;
                }
            }
        }

        $projectBoxes = getProjectBoxes();
        foreach ($projectBox as $method => $projectBox) {
            if (in_array($projectBox, $projectBoxes)) {
                $appName = env('APP_NAME');
                $class = "\\Project\\{$appName}\\Controllers\\PostController";
                if (class_exists($class)) {
                    $class::$method($request, $post);
                }
            }
        }

        $postData = [];

        $publishedStatuses = [
            'publish',
            'publishTime'
        ];

        if ($user->can('finalStatus')) {
            $postData['final_status'] = $request->status;
            if (!in_array($post->status, $publishedStatuses)) {
                $postData['status'] = $request->status;
            } else {
                $postData['status'] = $post->status;
            }
            if ($postData['final_status'] == 'needChange') {
                telegram()->message([
                    "🚫 در مطلب شما مواردی دیده شده که نیازمند تغییر می باشد",
                    "<a href='".url("admin/posts/{$post->id}/edit")."'>{$post->title}</a>"
                ])->tags(['needChange', 'post_'.$post->id])->sendToGroup();
                $post->updateMeta('needChange', $request->needChange);
            }
            if (in_array($request->status, $publishedStatuses)) {
                $post->deleteMeta(false, 'needChange');
            }
        } else {
            $postData['status'] = $request->status;
            if (in_array($postData['status'], $publishedStatuses)) {
                $postData['final_status'] = 'pending';
            }
        }

        foreach ([
            'title',
            'slug',
            'excerpt',
            'content',
        ] as $field) {

            if (in_array($field, $postBoxes)) {
                if (isset($canSetPostBoxes[$field])) {
                    $postData[$field] = $request->$field;
                }
            }

        }

        if (empty($post->published_at)) {
            if (in_array($postData['status'], $publishedStatuses)) {
                $postData['published_at'] = Carbon::now()->toDateTimeString();
            }
        }

        if ($postData['status'] == 'publishTime') {
            $publishedDateTime = explode(' ', toEnglish($request->publishTime));
            $publishedDateParts = explode('/', $publishedDateTime[0]);
            $publishTime = jalali_to_gregorian($publishedDateParts[0], $publishedDateParts[1], $publishedDateParts[2], '-') . ' ' . $publishedDateTime[1];
            $postData['published_at'] = $publishTime;
        }

        if ($request->has('postTypeMetas')) {
            // TODO optimize query
            foreach ($request->postTypeMetas as $type => $values) {
                foreach ($values as $k => $v) {
                    $old = PostMeta::where(['post_id' => $post->id, 'key' => $type, 'more' => $k])->first();
                    if ($old == null) {
                        $post->addMeta($type, $v, $k);
                    } else {
                        $old->update(['value' => $v]);
                    }
                }
            }
        }

        $post->update($postData);
        $this->clearCache($request, $post->post_type);

        session()->flash('success', 'بروزرسانی با موفقیت انجام شد');
        return redirect()->back();
    }

    public function updateValidator($request, $post, $user, $canSetPostTypes, $canSetPostBoxes, $postBoxes) {

        if ($post->canEdit($user, $canSetPostTypes, true)) {

            $rules = [];
            $messages = [];

            if (in_array($request->status, ['publish', 'finalPublish', 'publishTime'])) {

                $rules = [
                    'title' => 'required'
                ];

                if (in_array('slug', $canSetPostBoxes)) {
                    if (in_array('slug', $postBoxes)) {
                        $rules['slug'] = 'required|max:100';
                    }
                }

                foreach (['excerpt', 'content'] as $field) {
                    if (in_array($field, $canSetPostBoxes)) {
                        if (in_array($field, $postBoxes)) {
                            $rules[$field] = 'required';
                        }
                    }
                }

            }

            if ($request->status == 'publishTime') {
                $rules['publishTime'] = 'required';
            }

        } else {
            $rules = ['title' => 'false'];
            $messages = ['title.false' => 'لطفا پارامترهای استاندارد را تغییر ندهید'];
        }

        $request->validate($rules, $messages);

    }

    public function log($request, $post) {
        $user = auth()->user();
        $metas = PostMeta::where('post_id', $post->id)->where('key', '!=', 'log')->get()->toArray();
        $post->addMeta('log', $user->id, json_encode([
            'post' => $post->toArray(),
            'metas' => $metas
        ]));
    }

    public function telegram($request, $post) {
        if ($request->has('telegram_send_to')) {
            if (!empty($request->telegram_message)) {
                if ($request->telegram_send_to == 'group') {
                    telegram()->photo(url($post->thumbnail))->message($request->telegram_message)->sendToGroup();
                } else if ($request->telegram_send_to == 'channel') {
                    telegram()->photo(url($post->thumbnail))->message($request->telegram_message)->sendToChannel();
                }
            }
        }
    }

    public function tags($request, $post) {
        $tags = [];
        $oldTags = $post->tags->pluck('id')->toArray();
        if ($request->has('tags')) {
            foreach ($request->tags as $tag) {
                $old = Tag::where('tag', $tag)->first();
                if ($old != null) {
                    $tags[] = $old->id;
                } else {
                    $get = Tag::create(['tag' => $tag]);
                    $tags[] = $get->id;
                }
            }
            $post->tags()->sync($tags);
        } else {
            PostTag::where('post_id', $post->id)->delete();
        }
        $this->menusCacheTags = array_merge($oldTags, $tags);
    }

    public function categories($request, $post) {
        $this->menusCacheCategories = $post->categories->pluck('id')->toArray();
        if ($request->has('categories')) {
            $categories = [];
            foreach ($request->categories as $categoryId) {
                try {
                    $catId = $categoryId;
                    while (true) {
                        $category = Category::find($catId);
                        $categories[] = $category->id;
                        if ($category->parent == null) {
                            break;
                        }
                        $catId = $category->parent;
                    }
                } catch (\Exception $error) {
                    die('categories cache error');
                }
            }
            $categories = array_unique($categories);
            $this->menusCacheCategories = array_merge($this->menusCacheCategories, $categories);
            $post->categories()->sync($categories);
        } else {
            PostCategory::where('post_id', $post->id)->delete();
        }
    }

    public function thumbnail($request, $post) {
        if ($request->has('thumbnailChanged')) {
            if ($request->has('thumbnail')) {
                if ($request->has('thumbnailId')) {
                    $post->update(['thumbnail' => $request->thumbnail]);
                    $data = [
                        'post_id' => $post->id,
                        'key' => 'thumbnail',
                        'value' => $request->thumbnail,
                        'more' => $request->thumbnailId
                    ];
                    if (!PostMeta::where($data)->exists()) {
                        PostMeta::create($data);
                    }
                }
            }
        }
    }

    public function gallery($request, $post) {
        if ($request->has('galleryChanged')) {
            PostMeta::where(['post_id' => $post->id, 'key' => 'gallery'])->delete();
            if ($request->has('gallery')) {
                foreach ($request->gallery as $item) {
                    $post->addMeta('gallery', $item['path'], $item['id']);
                }
            }
        }
    }

    public function sounds($request, $post) {
        if ($request->has('soundsChanged')) {
            if ($request->has('sounds')) {
                PostMeta::where(['post_id' => $post->id, 'key' => 'sounds'])->delete();
                foreach ($request->sounds as $item) {
                    $post->addMeta('sounds', $item['path'], $item['id']);
                }
            }
        }
    }

    public function preview($request, $post) {
        if ($request->has('preview')) {
            $post->updateMeta('preview', $request->preview, $request->previewId);
        }
    }

    public function sourceZip($request, $post) {
        if ($request->has('sourceZip')) {
            $post->updateMeta('sourceZip', $request->sourceZip, $request->sourceZipId);
        }
    }

    public function scriptJs($request, $post) {
        if ($request->has('scriptJs')) {
            $post->updateMeta('scriptJs', $request->scriptJs, $request->scriptJsId);
        }
    }

    public function attributes(Request $request, Post $post)
    {

        PostAttribute::where(['type' => 'post', 'post_id' => $post->id])->update(['active' => '0']);

        if ($request->has('attributes')) {
            $postId = $post->id;
            foreach ($request->input('attributes') as $attributeId => $keys) {
                foreach ($keys as $keyId => $values) {
                    foreach ($values as $valueId) {

                        $where = [
                            'type'         => 'post',
                            'post_id'      => $postId,
                            'attribute_id' => $attributeId,
                            'key_id'       => $keyId,
                            'value_id'     => $valueId
                        ];

                        $old = PostAttribute::where($where)->first();

                        if ($old == null) {
                            PostAttribute::create($where);
                        } else {
                            $old->update(['active' => '1']);
                        }

                    }
                }
            }
        }

    }

    public function plan(Request $request, Post $post) {
        PostAttribute::where(['type' => 'plan', 'post_id' => $post->id])->delete();
        if ($request->has('plans')) {
            foreach ($request->plans as $attributeId => $values) {
                foreach ($values as $key => $value) {
                    if ($value['active'] == '1') {
                        PostAttribute::create([
                            'type'         => 'plan',
                            'post_id'      => $post->id,
                            'attribute_id' => $attributeId,
                            'key_id'       => $key,
                            'value_id'     => $value['id']
                        ]);
                    }
                }
            }
        }
    }

    public function survey(Request $request, Post $post)
    {

        PostAttribute::where(['post_id' => $post->id, 'type' => 'comment'])->update(['active' => '0']);

        if ($request->has('survey')) {

            $surveys = [];
            foreach ($request->survey as $surveyTitle) {
                $survey = Attribute::where(['title' => $surveyTitle, 'type' => 'comment'])->first();
                if ($survey == null) {
                    $survey = Attribute::create(['title' => $surveyTitle, 'type' => 'comment']);
                    AttributeRelation::create([
                        'key' => 'attribute_postType', 'value' => $survey->id, 'more' => $post->post_type
                    ]);
                }
                $surveys[] = $survey->id;
            }

            foreach ($surveys as $survey) {

                $data = [
                    'type' => 'comment',
                    'post_id' => $post->id,
                    'attribute_id' => $survey,
                ];

                if (PostAttribute::where($data)->exists()) {
                    PostAttribute::where($data)->update(['active' => '1']);
                } else {
                    PostAttribute::create($data);
                }

            }

        }

    }

    public function location(Request $request, Post $post) {
        if ($request->has('province_id')) {
            $post->updateLocation([
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'country_id'  => ($request->has('country_id') ? $request->country_id : 244), // 244 is iran
                'province_id' => $request->province_id,
                'city_id'     => $request->city_id,
                'town_id'     => $request->town_id,
                'address'     => $request->address,
            ]);
            $this->cacheDistance($request, $post);
        }
    }

    public function cacheDistance(Request $request, Post $post) {

        if ($request->has('cacheDistance')) {
            if ($request->has('latitude') && $request->has('longitude')) {
                if (!empty($request->latitude) && !empty($request->longitude)) {

                    if ($request->has('province_id')) {
                        if (!empty($request->province_id)) {
                            $data = [
                                'from_type' => '1',
                                'to_type'   => '4',
                                'from'      => $request->province_id,
                                'to'        => $post->id
                            ];
                            $world = Province::where('id', $request->province_id)->first();
                            $distanceController = new DistanceController();
                            $distanceController->cache($data, [
                                'latitude'  => $world->latitude,
                                'longitude' => $world->longitude
                            ], [
                                'latitude'  => $request->latitude,
                                'longitude' => $request->longitude
                            ]);
                        }
                    }

                    if ($request->has('city_id')) {
                        if (!empty($request->city_id)) {
                            $data = [
                                'from_type' => '2',
                                'to_type'   => '4',
                                'from'      => $request->city_id,
                                'to'        => $post->id
                            ];
                            $world = City::where('id', $request->city_id)->first();
                            $distanceController = new DistanceController();
                            $distanceController->cache($data, [
                                'latitude'  => $world->latitude,
                                'longitude' => $world->longitude
                            ], [
                                'latitude'  => $request->latitude,
                                'longitude' => $request->longitude
                            ]);
                        }
                    }

                    if ($request->has('town_id')) {
                        if (!empty($request->town_id)) {
                            $data = [
                                'from_type' => '3',
                                'to_type'   => '4',
                                'from'      => $request->town_id,
                                'to'        => $post->id
                            ];
                            $world = Town::where('id', $request->town_id)->first();
                            $distanceController = new DistanceController();
                            $distanceController->cache($data, [
                                'latitude'  => $world->latitude,
                                'longitude' => $world->longitude
                            ], [
                                'latitude'  => $request->latitude,
                                'longitude' => $request->longitude
                            ]);
                        }
                    }

                }
            }
        }

    }

    public function product(Request $request, Post $post) {

        if ($request->has('products')) {

            extract($post->products());

            $productAndAttributes = [];
            foreach ($products as $product) {
                $productAndAttributes[] = [
                    'product' => $product,
                    'attributes' => $productAttributes[$product->product_id] ?? []
                ];
            }

            $sort = 0;
            foreach ($request->products as $product) {

                $title = $product['title'] ?? $post->title;
                $startDate = $endDate = null;

                if (!empty($product['price']))
                    $price = toEnglish(str_replace(',', '', $product['price']));
                else
                    $price = 0;

                if (!empty($product['purchase_price']))
                    $purchasePrice = toEnglish(str_replace(',', '', $product['purchase_price']));
                else
                    $purchasePrice = 0;

                if (!empty($product['special_price']))
                    $specialPrice = toEnglish(str_replace(',', '', $product['special_price']));
                else
                    $specialPrice = 0;

                if (!empty($product['stock']))
                    $stock = toEnglish($product['stock']);
                else
                    $stock = 0;

                if (!empty($product['start_date'])) {
                    $dateTimeParts = explode(' ', $product['start_date']);
                    $dateParts = explode('/', $dateTimeParts[0]);
                    $startDate = toEnglish(rePairDate(jalali_to_gregorian($dateParts[0], $dateParts[1], $dateParts[2], '-')) . ' ' . $dateTimeParts[1]);
                }

                if (!empty($product['end_date'])) {
                    $dateTimeParts = explode(' ', $product['end_date']);
                    $dateParts = explode('/', $dateTimeParts[0]);
                    $endDate = toEnglish(rePairDate(jalali_to_gregorian($dateParts[0], $dateParts[1], $dateParts[2], '-')) . ' ' . $dateTimeParts[1]);
                }

                $data = [
                    'post_id'        => $post->id,
                    'title'          => $title,
                    'shipping_id'    => $request->product_shipping_id,
                    'tax_id'         => $request->product_tax_id,
                    'purchase_price' => $purchasePrice,
                    'price'          => $price,
                    'special_price'  => $specialPrice,
                    'start_date'     => $startDate,
                    'end_date'       => $endDate,
                    'stock'          => $stock,
                    'sort'           => $sort
                ];

                if ($request->has('product_type')) {
                    $data['type'] = $request->product_type;
                }

                $attributesCount = 0;
                $thisProductAttributes = [];
                if (isset($product['attributes'])) {
                    $attributesCount = count($product['attributes']);
                    foreach ($product['attributes'] as $pa) {
                        $thisProductAttributes[$pa['key']] = $pa['value'];
                    }
                }

                $old = null;

                if ($productAndAttributes != null) {

                    foreach ($productAndAttributes as $productAttribute) {

                        if (count($productAttribute['attributes']) == $attributesCount) {

                            if ($attributesCount > 0) {

                                $update = true;
                                foreach ($productAttribute['attributes'] as $attributeId => $keyId) {
                                    if (isset($thisProductAttributes[$attributeId])) {
                                        if ($thisProductAttributes[$attributeId] != $keyId) {
                                            $update = false;
                                            break;
                                        }
                                    } else {
                                        $update = false;
                                        break;
                                    }
                                }

                                if ($update)
                                    $old = $productAttribute;

                            } else if ($attributesCount == 0) {
                                $old = $productAttribute;
                                break;
                            }

                        }

                    }

                }

                if ($old == null) { // create

                    $createdProduct = Product::create($data);

                    if (isset($product['attributes'])) {
                        foreach ($product['attributes'] as $attribute) {
                            ProductAttribute::create([
                                'product_id'   => $createdProduct->id,
                                'attribute_id' => $attribute['key'],
                                'key_id'       => $attribute['value'],
                            ]);
                        }
                    }

                } else { // update
                    Product::where('product_id', $old['product']->product_id)->update($data);
                }

                $sort++;

            }

        }

        if ($request->has('productFiles')) {

            $sort = 0;
            foreach ($request->productFiles as $file) {

                $getFile = ProductFile::where([
                    'post_id' => $post->id,
                    'attachment_id' => $file['attachment_id'],
                ])->first();

                if ($file['active'] == '2') {
                    if ($getFile != null) {
                        $getFile->delete();
                    }
                } else {
                    $file['sort'] = $sort;
                    $file['post_id'] = $post->id;
                    if ($getFile == null) {
                        ProductFile::create($file);
                    } else {
                        $getFile->update($file);
                    }
                }

                $sort++;

            }

        }

    }

    public function mobiles(Request $request, Post $post) {

        PostMeta::where(['post_id' => $post->id, 'key' => 'number', 'more' => 'mobile'])->delete();

        if ($request->has('mobiles')) {
            foreach ($request->mobiles as $record) {
                $post->addMeta('number', $record, 'mobile');
            }
        }

    }

    public function phones(Request $request, Post $post) {

        PostMeta::where(['post_id' => $post->id, 'key' => 'number', 'more' => 'phone'])->delete();

        if ($request->has('phones')) {
            foreach ($request->mobiles as $record) {
                $post->addMeta('number', $record, 'phone');
            }
        }

    }

    public function aparat(Request $request, Post $post) {

        if ($request->has('aparat')) {
            $post->updateMeta('aparat', $request->aparat);
        }

    }

    public function course(Request $request, Post $post) {

        if ($request->has('course_type')) {
            $post->updateMeta('courseType', $request->course_type);
        }

        if ($request->has('course_status')) {
            $post->updateMeta('courseStatus', $request->course_status);
        }

        if ($request->has('teacher')) {
            $post->updateMeta('teacher', $request->teacher);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        can('deletePost');

        $record = Post::find($id);
        $user = auth()->user();
        $user->checkPostTypePermission($record->post_type, 'post_delete');

        $record->delete();

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.posts.index'));

    }

    public function like(Request $request) {

        $output = [
            'status' => 'error',
            'message' => 'پارامترهای ارسالی اشتباه می‌باشند'
        ];

        if ($request->has('post_id')) {
            if (!empty($request->post_id)) {
                if (Post::where('id', $request->post_id)->exists()) {

                    $ip = ip();
                    $postId = $request->post_id;

                    $type = 1;
                    if ($request->has('type')) {
                        if (in_array($request->type, [0, 1])) {
                            $type = $request->type;
                        }
                    }

                    $key = 'ip';
                    $value = $ip;

                    $data = [
                        'ip' => $ip,
                        'post_id' => $postId,
                        'type' => $type
                    ];

                    if (auth()->check()) {
                        $userId = auth()->id();
                        $data['user_id'] = $userId;
                        $key = 'user_id';
                        $value = $userId;
                    }

                    $where = [
                        $key => $value,
                        'post_id' => $postId,
                        'type' => $type
                    ];

                    if (Like::where($where)->exists()) {
                        Like::where($where)->delete();
                        $output['active'] = false;
                    } else {
                        Like::create($data);
                        $output['active'] = true;
                    }

                    $output['status'] = 'success';
                    $output['message'] = 'با موفقیت انجام شد';
                    $output['count'] = Like::where(['post_id' => $postId, 'type' => $type])->count();

                }

            }

        }

        return $output;

    }

    public function rate(Request $request) {

        $output = [
            'status' => 'error',
            'message' => 'پارامترهای ارسالی اشتباه می‌باشند'
        ];

        if ($request->has('post_id')) {
            if (!empty($request->post_id)) {
                if (Post::where('id', $request->post_id)->exists()) {

                    $ip = ip();
                    $postId = $request->post_id;

                    $rate = 3;
                    if ($request->has('rate')) {
                        $rate = $request->rate;
                    }

                    $key = 'ip';
                    $value = $ip;

                    $data = [
                        'ip' => $ip,
                        'post_id' => $postId,
                        'rate' => $rate
                    ];

                    if (auth()->check()) {
                        $userId = auth()->id();
                        $data['user_id'] = $userId;
                        $key = 'user_id';
                        $value = $userId;
                    }

                    $where = [
                        $key => $value,
                        'post_id' => $postId,
                    ];

                    if (Rate::where($where)->exists()) {
                        Rate::where($where)->update(['rate' => $rate]);
                    } else {
                        Rate::create($data);
                    }

                    $output['status'] = 'success';
                    $output['message'] = 'امتیاز شما با موفقیت ثبت شد';
                    $post = Post::find($postId);
                    $output['postRate'] = $post->rate(true);
                    $output['yourRate'] = $rate;

                }
            }

        }

        return $output;

    }

    public function favorite(Request $request) {

        $output = [
            'status' => 'error',
            'message' => 'پارامترهای ارسالی اشتباه می‌باشند'
        ];

        if ($request->has('post_id')) {
            if (!empty($request->post_id)) {
                if (Post::where('id', $request->post_id)->exists()) {

                    $postId = $request->post_id;
                    $userId = auth()->id();

                    $where = [
                        'post_id' => $postId,
                        'user_id' => $userId
                    ];

                    if (Favorite::where($where)->exists()) {
                        Favorite::where($where)->delete();
                        $output['active'] = false;
                    } else {
                        Favorite::create($where);
                        $output['active'] = true;
                    }

                    $output['status'] = 'success';
                    $output['message'] = 'با موفقیت انجام شد';

                }

            }

        }

        return $output;

    }

    public function clearCache($request, $postType) {
        if ($request->has('status')) {
            if ($request->status == 'publish') {
                $getPostType = getPostType($postType);
                $keys[] = "postsPostTypes_{$getPostType->id}";
                if (!empty($this->menusCacheTags)) {
                    foreach (array_unique($this->menusCacheTags) as $tagId) {
                        $keys[] = "postsTags_{$tagId}";
                    }
                }
                if (!empty($this->menusCacheCategories)) {
                    foreach (array_unique($this->menusCacheCategories) as $categoryId) {
                        $keys[] = "categoriesCategories_{$categoryId}";
                        $keys[] = "postsCategories_{$categoryId}";
                    }
                }
                $cacheKeys = MenuMeta::where('key', 'cache')->whereIn('value', $keys)->pluck('more')->toArray();
                foreach (array_unique($cacheKeys) as $cacheKey) {
                    if (Cache::has($cacheKey)) {
                        Cache::delete($cacheKey);
                    }
                }
            }
        }
    }

    public function search($params = null) {

        $output = [
            'status' => 'success',
            'view1' => false,
            'html1' => null
        ];

        if (!empty($params))
            $_GET = $params;

        $q = (isset($_GET['search']) ? $_GET['search'] : $_GET['q'] ?? '');

        $count = $_GET['count'] ?? 20;
        $to = $_GET['output'] ?? 'view';
        $view = $_GET['view'] ?? 'pages.search';
        $title = ($q == '') ? "جستجو" : "جستجو برای {$q}";

        if (isset($_GET['postType'])) {
            $postType = getPostType($_GET['postType']);
            if ($postType != null) {
                $title .= ' در ' . $postType->total_label;
            }
        }

        $posts = Post::where('lang', app()->getLocale())
            ->published()
            ->search()
            ->categories()
            ->tags()
            ->postType()
            ->postTypes()
            ->world()
            ->ordering()
            ->keyValue()
            ->attributes()
            ->searchPostTypes()
            ->paginate($count)
            ->appends(request()->query());

        $postCount = $posts->count();
        addSearch($q, $postCount);

        if ($postCount == 1) {
            return redirect($posts[0]->href(), 301);
        }

        $postTypes = [];
        $getPostTypes = $posts->pluck('post_type')->toArray();
        $categories = Category::whereIn('post_type', $getPostTypes)->get();
        foreach ($getPostTypes as $type) {
            $postType = getPostType($type);
            $postTypes[$type] = [
                'label' => $postType->label,
                'totalLabel' => $postType->total_label,
                'type' => $type,
                'icon' => $postType->icon,
                'posts' => $posts->where('post_type', $type)->filter(),
                'categories' => $categories->where('post_type', $type)->filter()
            ];
        }

        if (isset($_GET['postType'])) {
            $akv_s = AttributeRelation::where(['key' => 'search_postType_akv', 'value' => $_GET['postType']])->pluck('more')->toArray();
            $aIds = $kIds = $vIds = [];
            foreach ($akv_s as $akv) {
                $akvParts = explode('-', $akv);
                $a = $akvParts[0];
                $k = $akvParts[1];
                $v = $akvParts[2];
                $aIds[$a] = $a;
                $kIds[$k] = $k;
                $vIds[$v] = $v;
            }
            $attributes = Attribute::whereIn('id', $aIds)->get();
            $attributeKeys = AttributeKey::whereIn('id', $kIds)->get();
            $attributeValues = AttributeValue::whereIn('id', $vIds)->get();
            $akvFilter = [];
            foreach ($akv_s as $akv) {
                $akvParts = explode('-', $akv);
                $a = $akvParts[0];
                $k = $akvParts[1];
                $v = $akvParts[2];
                $aIds[$a] = $a;
                $kIds[$k] = $k;
                $vIds[$v] = $v;
                if (!isset($akvFilter[$a])) {
                    $akvFilter[$a] = [
                        'title' => $attributes->where('id', $a)->first()->title
                    ];
                }
                if (!isset($akvFilter[$a]['keys'][$k])) {
                    $akvFilter[$a]['keys'][$k] = [
                        'title' => $attributeKeys->where('id', $k)->first()->title
                    ];
                }
                if (!isset($akvFilter[$a]['keys'][$k]['values'][$v])) {
                    $akvFilter[$a]['keys'][$k]['values'][$v] = [
                        'title' => $attributeValues->where('id', $v)->first()->title,
                        'value' => "{$a}-{$k}-{$v}"
                    ];
                }
            }
        }

        $output['title'] = $title;
        $output['posts'] = $posts;
        $output['users'] = User::whereIn('id', $posts->pluck('user_id')->toArray())->get();
        $output['postTypes'] = $postTypes;
        $output['categories'] = $categories;
        $output['akvFilter'] = $akvFilter ?? [];

        if (isset($_GET['view1'])) {
            $output['view1'] = true;
            $output['html1'] = view($_GET['view1'], ['data' => $output])->render();
        }

        $canonicalParams = ['q', 'postType'];
        $canonicalData = [];

        foreach ($canonicalParams as $canonicalParam) {
            if (isset($_GET[$canonicalParam])) {
                if ($canonicalParam == 'q') {
                    $canonicalData[$canonicalParam] = str_replace([
                        '/',
                        '+',
                        ' '
                    ], [
                        '',
                        '-',
                        '-'
                    ], $_GET[$canonicalParam]);
                } else {
                    $canonicalData[$canonicalParam] = $_GET[$canonicalParam];
                }
            }
        }

        $output['canonical'] = url("search?" . http_build_query($canonicalData));

        if ($to == 'view') {
            return templateView($view, $output);
        } else if ($to == 'json') {
            return $output;
        }

    }

    public function translate()
    {
        can('translatePosts');
        $lang = Language::where('lang', $_GET['lang'])->first();
        $search = $_GET['search'] ?? '';
        $where = [];
        if (isset($_GET['postType']))
            $where['posts.post_type'] = $_GET['postType'];
        $records = Post::leftJoin('posts as a', function ($join) use ($lang) {
            $join->on('posts.id', '=', 'a.parent')->where('a.lang', $lang->lang);
        })->where('posts.title', 'like', "%{$search}%")->where($where)->whereNull('posts.parent')->whereNull('a.id')->selectRaw('posts.*')->paginate(10);
        return adminView('posts.language', compact('records', 'lang'));
    }

    public function storeTranslate(Request $request)
    {

        can('translatePosts');
        $request->validate(['title' => 'required', 'id' => 'required', 'lang' => 'required']);

        $id = $request->id;
        $lang = $request->lang;
        $title = $request->title;

        $post = Post::find($id);

        if ($post != null) {
            if ($post->parent == null) {
                if (!Post::where(['parent' => $id, 'lang' => $lang])->exists()) {

                    $translatePost = Post::create([
                        'user_id' => auth()->id(),
                        'title' => $title,
                        'thumbnail' => $post->thumbnail,
                        'lang' => $lang,
                        'parent' => $id,
                        'post_type' => $post->post_type,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);

                    return redirect(url("admin/posts/{$translatePost->id}/edit"));

                }
            }
        }

        session()->flash('danger', 'لطفا پارامترهای استاندارد را تغییر ندهید');
        return redirect()->back();

    }

}
