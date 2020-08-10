<?php

namespace LaraBase\Posts\Actions;

use Illuminate\Support\Facades\Cache;
use LaraBase\Attachments\Models\Attachment;
use LaraBase\Attributes\Models\Attribute;
use LaraBase\Attributes\Models\AttributeKey;
use LaraBase\Attributes\Models\AttributeKeyPostType;
use LaraBase\Attributes\Models\AttributeRelation;
use LaraBase\Attributes\Models\AttributeValue;
use LaraBase\Auth\Models\User;
use LaraBase\Comments\Models\Comment;
use LaraBase\Menus\Models\Menu;
use LaraBase\Menus\Models\MenuMeta;
use LaraBase\Posts\Models\Favorite;
use LaraBase\Posts\Models\Like;
use LaraBase\Posts\Models\PostCommentAttributes;
use LaraBase\Posts\Models\PostLocation;
use LaraBase\Posts\Models\PostMeta;
use LaraBase\Posts\Models\PostAttribute;
use LaraBase\Posts\Models\PostType;
use LaraBase\Posts\Models\PostVideo;
use LaraBase\Posts\Models\Rate;
use LaraBase\Store\Models\Product;
use LaraBase\World\models\City;
use LaraBase\World\Models\Distance;
use LaraBase\World\models\Province;
use LaraBase\World\models\Town;

trait Post
{

    public function isLike($class = 'active')
    {

        $where = [
            'post_id' => $this->id,
            'type' => '1'
        ];

        if (auth()->check()) {
            $where['user_id'] = auth()->id();
        } else {
            $where['ip'] = ip();
        }

        if (Like::where($where)->exists()) {
            return $class;
        }

    }

    public function isDislike($class = 'active')
    {

        $where = [
            'post_id' => $this->id,
            'type' => '0'
        ];

        if (auth()->check()) {
            $where['user_id'] = auth()->id();
        } else {
            $where['ip'] = auth()->id();
        }

        if (Like::where($where)->exists())
            return $class;

    }

    public function rateByLikes($computeBy = 5)
    {

        $likes = Like::where('post_id', $this->id)->get();
        $positive = $likes->where('type', '1')->count();
        $negative = $likes->where('type', '0')->count();

        if ($negative == 0)
            return $computeBy;

        return round(($positive / ($positive + $negative)) * $computeBy, 1);

    }

    public function isFavorite($class = 'active')
    {

        if (auth()->check()) {
            if (Favorite::where(['post_id' => $this->id, 'user_id' => auth()->id()])->exists()) {
                return $class;
            }
        }

    }

    public function likes()
    {
        return Like::where(['post_id' => $this->id, 'type' => '1'])->count();
    }

    public function dislikes()
    {
        return Like::where(['post_id' => $this->id, 'type' => '0'])->count();
    }

    public function rate($resetCache = false)
    {
        $rating = Rate::where('post_id', $this->id)->get();
        $sum = $rating->sum('rate');
        $count = $rating->count();
        if ($count == 0)
            return 5;
        return $sum / $count;
    }

    public function menu($i)
    {
        return getMenu("post{$this->id}menu{$i}");
    }

    public function menus($metas)
    {
        $menuPlaces = [];
        if (isset($metas['menus']['count'])) {
            for ($i = 1; $i <= $metas['menus']['count']; $i++) {
                $menuPlace = "post{$this->id}menu{$i}";
                $menuPlaces[] = $menuPlace;
                if (!MenuMeta::where(['key' => 'place', 'value' => $menuPlace])->exists()) {
                    $menu = Menu::create(['title' => "فهرست ({$i}) پست ({$this->id})"]);
                    MenuMeta::create([
                        'menu_id' => $menu->id,
                        'key' => 'place',
                        'value' => $menuPlace
                    ]);
                }
            }
        }
        return MenuMeta::whereIn('value', $menuPlaces)->where('key', 'place')->pluck('menu_id')->toArray();
    }

    //////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////

    public function boxes()
    {
        $postType = PostType::where('type', $this->post_type)->first();
        return json_decode($postType->boxes, true);
    }

    public function type()
    {
        return PostType::where('type', $this->post_type)->first();
    }

    public function postTypeLabel()
    {
        return $this->post_type;
    }

    public function postTypeTotalLabel()
    {
        return $this->post_type;
    }

    public function href()
    {
        return url($this->post_type . '/' . $this->id . '/' . $this->slug);
    }

    /*
     * ******************** Metas
     */

    public function meta($key, $return = 'value')
    {

        $where['post_id'] = $this->id;

        if (is_array($key)) {
            $where = array_merge($key, $where);
        } else {
            $where['key'] = $key;
        }

        $get = PostMeta::where($where)->first();

        if ($get != null)
            return $get->$return;

    }

    public function getMetas($where = [])
    {
        $where['post_id'] = $this->id;
        $get = PostMeta::where($where)->get();
        return $get;
    }

    /*
     * ******************** Metas
     */

    public function productPrice($numberFormat = false)
    {
        $product = Product::where('post_id', $this->id)->first();
        if ($product != null) {
            if ($numberFormat)
                return number_format($product->price);

            return $product->price;
        }
        return '-';
    }

    public function productStock()
    {
        $product = Product::where('post_id', $this->id)->first();
        if ($product != null) {
            return $product->stock;
        }
        return 0;
    }

    public function discountPrice($numberFormat = false)
    {
        $price = $this->price;

        if ($numberFormat)
            return number_format($price);

        return $price;
    }

    public function createdAt($type = null)
    {
        $format = 'Y/m/d H:i:s';
        if ($type == 'date')
            $format = 'Y/m/d';

        return jDateTime($format, strtotime($this->created_at));
    }

    public function duration()
    {
//        $videos = $this->videos();
//        return secondToTime($videos->sum('duration'));
    }

    public function unit()
    {
        return Unit::where('id', $this->unit_id)->first();
    }

    public function postKeywords()
    {
        $tags = [];
        foreach ($this->tags()->get() as $tag) {
            $tags[] = $tag->tag;
        }

        return implode(',', $tags);
    }

    public function description()
    {
        return $this->description;
    }

    public function status()
    {
        $postStatus = config('statusConfig');
        return $postStatus[$this->status]['title2'];
    }

    public function statusLightColor()
    {
        $postStatus = config('statusConfig');
        return $postStatus[$this->status]['lightColor'];
    }

    public function statusColor()
    {
        $postStatus = config('statusConfig');
        return $postStatus[$this->status]['color'];
    }

    public function finalStatus()
    {
        $postStatus = config('statusConfig');
        return $postStatus[$this->final_status]['title2'];
    }

    public function finalStatusLightColor()
    {
        $postStatus = config('statusConfig');
        return $postStatus[$this->final_status]['lightColor'];
    }

    public function finalStatusColor()
    {
        $postStatus = config('statusConfig');
        return $postStatus[$this->final_status]['color'];
    }

    public function postTypeIcon()
    {
        $get = PostType::where('type', $this->post_type)->first();
        if ($get != null)
            return $get->icon;
    }

    public function publication($key = 'city')
    {
        $id = $this->meta('publication');
        if (!empty($id)) {
            return config("publication.i{$id}.{$key}");
        }
    }

    public function user()
    {
        return User::where('id', $this->user_id)->first();
    }

    public function firstGallery()
    {
        $firstGallery = \App\Models\PostMeta::where([
            'post_id' => $this->id,
            'key' => 'gallery',
            'more' => '1'
        ])->first();

        if ($firstGallery == null)
            return $this->image();

        return uploadUrl() . $firstGallery->value;
    }

    public function sounds()
    {

        $getSounds = PostMeta::where(['post_id' => $this->id, 'key' => 'sounds'])->get();

        $attachments = [];
        foreach (Attachment::whereIn('id', $getSounds->pluck('more')->toArray())->get() as $item) {
            $attachments[$item->id] = $item->title;
        }

        $sounds = [];
        foreach ($getSounds as $sound) {
            $sounds[] = [
                'id' => $sound->id,
                'path' => $sound->value,
                'attachmentId' => $sound->more,
                'title' => $attachments[$sound->more],
                'url' => url($sound->value)
            ];
        }
        return $sounds;
    }

    public function videos()
    {
        $videos = PostVideo::where(['post_id' => $this->id, 'active' => 1])->orderBy('sort')->get();
        return $videos;
    }

    public function videosCount()
    {
        return PostVideo::where([
            'post_id' => $this->id,
            'active' => 1
        ])->orderBy('sort')->count();
    }

    public function teacher()
    {
//        $teacher = \App\User::where('id', $this->teacher)->first();
//        return $teacher->name();
    }

    public function pageBuilders()
    {
        $data = [
            'post_id' => $this->id,
            'key' => "pageBuilder"
        ];
        $get = \App\Models\PostMeta::where($data)->get();
        $pageBuilders = $get->sortBy('more');
        return $pageBuilders;
    }

    public function deActivePageBuilders()
    {
        $data = [
            'post_id' => $this->id,
            'key' => "deActivePageBuilder"
        ];
        $get = \App\Models\PostMeta::where($data)->get();
        return $get->sortBy('more');
    }

    public function getMeta($key)
    {
        $data = [
            'key' => $key,
            'post_id' => $this->id
        ];
        $get = PostMeta::where($data)->first();
        return $get;
    }


    public function metas()
    {
        $metas = PostMeta::where('post_id', $this->id)->get();
        return $metas;
    }

    public function hasMeta($key)
    {
        if (PostMeta::where([
                'post_id' => $this->id,
                'key' => $key,
            ])->count() > 0) {
            return true;
        }

        return false;
    }

    public function addMeta($key, $value, $more = null)
    {
        PostMeta::create([
            'post_id' => $this->id,
            'key' => $key,
            'value' => $value,
            'more' => $more
        ]);
    }

    public function updateMeta($key, $value, $more = null)
    {
        if ($this->hasMeta($key)) {
            PostMeta::where([
                'post_id' => $this->id,
                'key' => $key
            ])->update([
                'value' => $value,
                'more' => $more
            ]);
        } else {
            $this->addMeta($key, $value, $more);
        }
    }

    public function updateMetaById($id, $data)
    {
        PostMeta::where('id', $id)->update($data);
    }

    public function deleteMeta($id, $key = false)
    {
        $data['post_id'] = $this->id;
        if ($id != false)
            $data['id'] = $id;
        if ($key != false)
            $data['key'] = $key;
        PostMeta::where($data)->delete();
    }

    public function publishTime()
    {
        return jDateTime('Y/m/d H:i:s', strtotime($this->published_at ?? $this->created_at));
    }

    public function displayPassword()
    {
        if ($this->display == 'encrypted') {
            if ($this->hasMeta('postDisplayPassword')) {
                $meta = $this->getMeta('postDisplayPassword');
                return $meta->value;
            }
        }
    }

    public function plans($onlyActive = false)
    {

        $output = [];

        $actives = PostAttribute::where([
            'type' => 'plan',
            'post_id' => $this->id
        ])->get();

        $planAttributes = AttributeRelation::where(['key' => 'attribute_plan'])->get();
        $attributes = Attribute::where('type', 'product')->whereIn('id', $planAttributes->pluck('value')->toArray())->get();

        $attributeKeys = AttributeRelation::where('key', 'attribute_key')->whereIn('value', $planAttributes->pluck('value')->toArray())->get();
        $keys = AttributeKey::whereIn('id', $attributeKeys->pluck('more')->toArray())->get();

        $attributeValues = AttributeRelation::where('key', 'key_value')->whereIn('value', $attributeKeys->pluck('more')->toArray())->get();
        $values = AttributeValue::whereIn('id', $attributeValues->pluck('more')->toArray())->get();

        foreach ($attributes as $attribute) {

            $attributeId = $attribute->id;
            $isActiveAttribute = $actives->where('attribute_id', $attributeId)->count();
            $showAttribute = true;

            if ($onlyActive) {
                if ($isActiveAttribute == 0) {
                    $showAttribute = false;
                }
            }

            if ($showAttribute) {

                $keysArray = [];
                foreach ($keys->whereIn('id', $attributeKeys->where('value', $attributeId)->pluck('more')->toArray())->filter() as $key) {

                    $keyId = $key->id;
                    $isActiveKey = $actives->where('attribute_id', $attributeId)->where('key_id', $keyId)->count();
                    $showKey = true;


                    if ($onlyActive) {
                        if ($isActiveKey == 0) {
                            $showKey = false;
                        }
                    }

                    if ($showKey) {

                        $valuesArray = [];
                        foreach ($values->whereIn('id', $attributeValues->where('value', $keyId)->pluck('more')->toArray())->filter() as $value) {

                            $valueId = $value->id;
                            $isActiveValue = $actives->where('attribute_id', $attributeId)->where('key_id', $keyId)->where('value_id', $valueId)->count();
                            $showValue = true;

                            if ($onlyActive) {
                                if ($isActiveValue == 0) {
                                    $showValue = false;
                                }
                            }

                            if ($showValue) {

                                $valuesArray[$valueId] = [
                                    'id' => $valueId,
                                    'title' => $value->title,
                                    'active' => $isActiveValue
                                ];

                            }

                        }

                        $keysArray[$keyId] = [
                            'id' => $keyId,
                            'title' => $key->title,
                            'values' => $valuesArray,
                            'active' => $isActiveKey
                        ];

                    }

                }

                $output[$attributeId] = [
                    'id' => $attributeId,
                    'title' => $attribute->title,
                    'keys' => $keysArray,
                    'active' => $isActiveAttribute
                ];

            }

        }

        return $output;

    }

    public function commentSpecifications()
    {

        $getSpecifications = PostCommentAttributes::where([
            'post_id' => $this->id,
            'active' => 1
        ])->get();

        $postSpecifications = [];
        foreach ($getSpecifications as $item) {
            $postSpecifications[] = $item->attribute_id;
        }

        $output = [];

        $lastSpecificationId = null;

        foreach ($postSpecifications as $id) {

            $get = Attribute::where('id', $id)->first();
            $comments = Comment::where('post_id', $this->id)->get();
            $commentIds = [];
            foreach ($comments as $comment) {
                $commentIds[] = $comment->id;
            }

            $commentMetas = CommentMeta::where(
                [
                    'key' => 'specification',
                    'more' => $id
                ])->whereIn('comment_id', $commentIds)->get();

            $value = 3;
            foreach ($commentMetas as $commentMeta) {
                $value = ($value + $commentMeta->value) / 2;
            }

            $output[] = [
                'id' => $id,
                'title' => $get->title,
                'value' => ceil($value)
            ];

        }

        return $output;

    }

    public function comments()
    {
        $comments = Comment::where([
            'post_id' => $this->id,
            'status' => '2',
            'parent' => null
        ])->orderBy('created_at', 'desc')->paginate(10);
        return $comments;
    }

    public function commentCount()
    {
        $cacheKey = "postCommentCount{$this->id}";

        if (Cache::has($cacheKey)) {
            $count = Cache::get($cacheKey);
        } else {
            $count = Comment::where([
                'post_id' => $this->id,
                'status' => '2'
            ])->count();
            Cache::add($cacheKey, $count, 720);
        }

        return $count;
    }

    public function visit()
    {
        $postId = $this->id;
        $cacheKey = "postVisit{$postId}";
        if (!Cache::has($cacheKey)) {
            $visits = Visit::where([
                'relation' => 'post',
                'relation_id' => $postId
            ])->sum('count');
            Cache::put($cacheKey, $visits, 720);
        }
        return Cache::get($cacheKey);
    }

    public function questions()
    {
        $questions = Comment::where([
            'post_id' => $this->id,
            'status' => 'publish',
            'parent' => 0
        ])->orderBy('created_at', 'desc')->paginate(10, ['*'], 'questionPage');
        return $questions;
    }

    public function questionCount()
    {
        return Comment::where([
            'post_id' => $this->id,
            'status' => 'publish',
            'parent' => 0
        ])->count();
    }

    public function newQuestion($text, $notifyEmail = false)
    {
        $comment = Comment::create([
            'post_id' => $this->id,
            'user_id' => auth()->user()->id,
            'title' => $text,
            'parent' => 0
        ]);

        if ($notifyEmail)
            $comment->notifyEmail();
    }

    public function newAnswer($text, $commentId)
    {
        $comment = Comment::create([
            'post_id' => $this->id,
            'user_id' => auth()->user()->id,
            'title' => $text,
            'parent' => $commentId
        ]);

        return $comment;
    }

    public function views()
    {
        return View::where([
            'post_id' => $this->id,
            'type' => 1
        ])->count();
    }

    public function videoDownloads()
    {
        return View::where([
            'type' => 3,
            'post_id' => $this->id
        ])->count();
    }

    public function pageBuilderModal($click)
    {

        $output = "";
        foreach ($click as $k => $v) {
            $output .= "{$k}={$v} ";
        }
        return $output;

    }

    public function pageBuilderClick($click)
    {

        $type = $click->type;

        if ($type != 'none') {

            if ($type == 'link') {
                return "href={$click->val} target={$click->openIn}";
            }

            if ($type == 'post') {
                $post = \App\Post::where('id', $click->post)->first();
                return "href={$post->href()} target={$click->openIn}";
            }

        }

    }

    public function pageBuilderInputs($click)
    {

        $clickInputs = "";
        foreach ($click as $k => $v) {
            $clickInputs .= '<input type="hidden" name="click[' . $k . ']" value="' . $v . '">';
        }
        return $clickInputs;

    }

    public function location()
    {
        if ($this->hasLocation())
            return PostLocation::where('post_id', $this->id)->first();
    }

    public function latitude()
    {

    }

    public function longitude()
    {

    }

    public function hasLocation()
    {
        if (PostLocation::where(['post_id' => $this->id])->exists()) {
            return true;
        }

        return false;
    }

    public function addLocation($data)
    {
        PostLocation::create(array_merge([
            'post_id' => $this->id,
            'post_type' => $this->post_type,
        ], $data));
    }

    public function updateLocation($data)
    {
        if ($this->hasLocation()) {
            PostLocation::where(['post_id' => $this->id])->update($data);
        } else {
            $this->addLocation($data);
        }
    }

    public function mobiles()
    {
        $get = \App\Models\PostMeta::where([
            'post_id' => $this->id,
            'key' => 'number',
            'more' => 'mobile',
        ])->get();
        return $get->pluck('value')->toArray();
    }

    public function phones()
    {
        $get = \App\Models\PostMeta::where([
            'post_id' => $this->id,
            'key' => 'number',
            'more' => 'phone'
        ])->get();
        return $get->pluck('value')->toArray();
    }

    public function star()
    {
        $get = \App\Models\PostMeta::where([
            'post_id' => $this->id,
            'key' => 'star'
        ])->first();

        if ($get != null)
            return $get->value;

        return 0;
    }

    public function adminName()
    {
        $get = \App\Models\PostMeta::where([
            'post_id' => $this->id,
            'key' => 'adminName'
        ])->first();

        if ($get != null)
            return $get->value;
    }

    public function website()
    {
        $get = \App\Models\PostMeta::where([
            'post_id' => $this->id,
            'key' => 'website'
        ])->first();

        if ($get != null)
            return $get->value;
    }

    public function residenceRules()
    {
        if (\App\Models\PostMeta::where(['key' => 'rules', 'post_id' => $this->id])->exists()) {
            $get = \App\Models\PostMeta::where(['key' => 'rules', 'post_id' => $this->id])->get();
            $rules = [];
            foreach ($get as $item) {
                $rules[$item->more] = $item->value;
            }
            return $rules;
        }
        return [];
    }

    public function rules()
    {
        $rules = \App\Models\PostMeta::where(['post_id' => $this->id, 'key' => 'rules'])->get();
        if ($rules->count() > 0) {
            $r = [];
            foreach ($rules as $rule) {
                $r[$rule->more] = $rule->value;
            }
        }
        return $r;
    }

    public function provinceName($location = null)
    {
//        if ($location == null)
//            $location = PostLocation::where('post_id', $this->id)->first();

        if ($location != null) {
            if (!empty($location->province_id)) {
                $get = Province::where('id', $location->province_id)->first();
                return $get->name;
            }
        }

        return '-';
    }

    public function cityName($location = null)
    {
//        if ($location == null)
//            $location = PostLocation::where('post_id', $this->id)->first();

        if ($location != null) {
            if (!empty($location->city_id)) {
                $get = City::where('id', $location->city_id)->first();
                return $get->name;
            }
        }

        return '-';
    }

    public function townName($location = null)
    {
//        if ($location == null)
//            $location = PostLocation::where('post_id', $this->id)->first();

        if ($location != null) {
            if (!empty($location->town_id)) {
                $get = Town::where('id', $location->town_id)->first();
                return $get->name;
            }
        }

        return '-';
    }

    public function needChange()
    {
        if ($this->hasMeta('needChange')) {
            return $this->getMeta('needChange')->value;
        }
    }

    public function images()
    {
        $images = [];

        $getImages = $this->getMetas('gallery');
        $i = 0;
        foreach ($getImages as $record) {

            $images[$i] = [
                'id' => $record->id,
                'value' => $record->value,
                'alt' => $record->value,
            ];

            if ($record->more == '1') {
                $images[$i]['multiple'] = true;
            }

            if ($record->value == $this->image) {
                $images[$i]['one'] = true;
            }

            $i++;

        }

        return $images;
    }

    public function provinceDistance($provinceId)
    {
        $get = Distance::where([
            'from_type' => '1',
            'to_type' => '4',
            'from' => $provinceId,
            'to' => $this->id
        ])->first();

        if ($get != null)
            return convertDistance($get->distance);

        return '-';
    }

    public function cityDistance($cityId)
    {
        $get = Distance::where([
            'from_type' => '2',
            'to_type' => '4',
            'from' => $cityId,
            'to' => $this->id
        ])->first();

        if ($get != null)
            return convertDistance($get->distance);

        return '-';
    }

    public function townDistance($townId)
    {
        $get = Distance::where([
            'from_type' => '3',
            'to_type' => '4',
            'from' => $townId,
            'to' => $this->id
        ])->first();

        if ($get != null)
            return convertDistance($get->distance);

        return '-';
    }

    public function worldDistances($location)
    {
        $output = [];

        if ($location != null) {
            $provinceId = $location->province_id;
            $cityId = $location->city_id;
            $townId = $location->town_id;

            $from = [];

            if (!empty($provinceId))
                $from[] = $provinceId;

            if (!empty($cityId))
                $from[] = $cityId;

            if (!empty($townId))
                $from[] = $townId;

            $get = Distance::where('to', $this->id)->whereIn('from', $from)->get();
            foreach ($get as $item) {
                $distance = convertDistance($item->distance);
                if ($item->from_type == '1') {
                    $output['0'] = 'فاصله از استان ' . $this->provinceName() . ' ' . $distance;
                }
                if ($item->from_type == '2') {
                    $output['1'] = 'فاصله از شهرستان ' . $this->cityName() . ' ' . $distance;
                }
                if ($item->from_type == '3') {
                    $output['2'] = 'فاصله از شهر ' . $this->townName() . ' ' . $distance;
                }
            }

        }

        return $output;
    }

}
