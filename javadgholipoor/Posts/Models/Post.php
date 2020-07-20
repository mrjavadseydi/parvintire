<?php

namespace LaraBase\Posts\Models;

use LaraBase\CoreModel;
use Cviebrock\EloquentSluggable\Sluggable;
use LaraBase\Tags\Models\Tag;
use LaraBase\Categories\Models\Category;

class Post extends CoreModel {

    use Sluggable;
    use \LaraBase\Posts\Actions\Post;
    use \LaraBase\Posts\Actions\Image;
    use \LaraBase\Posts\Actions\Authorizable;
    use \LaraBase\Store\Actions\Store;

    protected $table = 'posts';

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'excerpt',
        'content',
        'thumbnail',
        'lang',
        'parent',
        'comments',
        'views',
        'post_type',
        'status',
        'final_status',
        'published_at',
        'deleted_at',
        'created_at',
        'updated_at',
//        'image',
//        'price',
//        'stock',
//        'unit_id',
//        'unit_id',
//        'parent',
//        'course_type',
//        'course_status',
//        'teacher',
//        'post_type',
//        'code',
//        'display',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        if(!empty($this->slug) ) {
            return [
                'slug' => [
                    'source' => 'slug',
                    'maxLength' => 100
                ]
            ];
        } else if (!empty($this->title)) {
            return [
                'slug' => [
                    'source' => 'title',
                    'maxLength' => 100
                ]
            ];
        } else {
            return [
                'slug' => [
                    'source' => date('Y-m-d-H-i-s'),
                    'maxLength' => 100
                ]
            ];
        }
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_category', 'post_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function usersFavorite() {
        return $this->belongsToMany(User::class);
    }

    public function survey() {

    }

    public function scopeCanView($query, $canSetPostTypes = [], $canSetCategories = []) {

        if (empty($canSetPostTypes) || empty($canSetCategories))
            $user = auth()->user();

        if (!$user->can('canViewAllPost')) {

            if (empty($canSetPostTypes)) {
                $canSetPostTypes = $user->canSetPostTypes();
            }

            $postTypes = [];
            if (isset($canSetPostTypes['permissions']['post_list'])) {
                $postTypes = $canSetPostTypes['permissions']['post_list'];
            }

            $categoriesPostTypes = [];
            if (empty($canSetCategories)) {
                $canSetCategories = $user->canSetCategories();
            }

            foreach (array_unique($canSetCategories->pluck('post_type')->toArray()) as $postType) {
                $categoriesPostTypes[] = $postType;
            }

            # get postIds from categories
            $postIds = [];
            foreach ($canSetCategories as $category)
                $postIds = array_merge($postIds, $category->posts->pluck('id')->toArray());

            $query->whereIn('post_type', array_diff($postTypes, $categoriesPostTypes))->orWhereIn('id', array_unique($postIds));

        }
    }

    function scopeSearch($query, $string = null) {

        if (empty($string)) {
            if (isset($_GET['search']))
                $string = $_GET['search'];
            if (isset($_GET['q']))
                $string = $_GET['q'];
        }

        if (!empty($string)) {
            $query->whereRaw("(posts.id LIKE '%{$string}%' OR posts.title LIKE '%{$string}%' OR posts.excerpt LIKE '%{$string}%' OR posts.post_type LIKE '%{$string}%')");
        }

        // TODO بهینه سازی جستجو بر اساس تگ بررسی شود
        if ($string) {
            $tags = Tag::where('tag', 'like', '%' . $string . '%')->pluck('id')->toArray();
            $postIds = PostTag::whereIn('tag_id', $tags)->pluck('post_id')->toArray();
            $query->orWhereIn('id', array_unique($postIds));
        }

    }

    public function scopeAttributes($query, $attributes = [])
    {
        if (empty($attributes))
            $attributes = $_GET['attributes'] ?? [];

        if (!empty($attributes)) {
            $implode = "'" . implode("', '", $attributes) . "'";
            $postIds = PostAttribute::whereRaw("CONCAT(attribute_id, '-', key_id, '-', value_id) IN ({$implode})")->pluck('post_id')->toArray();
            $query->whereIn('id', array_unique($postIds));
        }

    }

    public function scopeCategories($query, $categories = []) {

        if (empty($categories))
            $categories = $_GET['categories'] ?? [];

        if (!is_array($categories)) {
            if (!empty($categories)) {
                $categories = explode(',', $categories);
            }
        }

        if (!empty($categories)) {
            $postIds = PostCategory::whereIn('category_id', $categories)->pluck('post_id')->toArray();
            $query->whereIn('id', array_unique($postIds));
        }

    }

    public function scopeTags($query, $tags = []) {
        if (empty($tags))
            $tags = $_GET['tags'] ?? [];

        if (!is_array($tags)) {
            $tags = explode(',', $tags);
        }

        if (!empty($tags)) {
            $postIds = PostTag::whereIn('tag_id', $tags)->pluck('post_id')->toArray();
            $query->whereIn('id', array_unique($postIds));
        }

    }

    public function scopePostType($query, $postType = null) {

        if ($postType == null) {
            $postType = $_GET['postType'] ?? null;
        }

        if (!empty($postType)) {
            $query->where('post_type', $postType);
        }

    }

    public function scopePostTypes($query, $postTypes = []) {

        if (empty($postTypes)) {
            $postTypes = $_GET['postTypes'] ?? [];
        }

        if (!is_array($postTypes)) {
            $postTypes = explode(',', $postTypes);
        }

        if (!empty($postTypes))
            $query->whereIn('post_type', $postTypes);

    }

    public function scopeWorld($query, $provinces = [], $cities = [], $towns = []) {

        $ids = [];

        if (empty($provinces))
            $provinces = $_GET['provinces'] ?? [];

        if (!is_array($provinces)) {
            $provinces = explode(',', $provinces);
        }

        if (!empty($provinces)) {
            $get = PostLocation::whereIn('province_id', $provinces);
            $ids = array_merge($ids, $get->pluck('post_id')->toArray());
        }

        if (empty($cities))
            $cities = $_GET['cities'] ?? [];

        if (!is_array($cities)) {
            $cities = explode(',', $cities);
        }

        if (!empty($cities)) {
            $get = PostLocation::whereIn('city_id', $cities);
            $ids = array_merge($ids, $get->pluck('post_id')->toArray());
        }

        if (empty($towns))
            $towns = $_GET['towns'] ?? [];

        if (!is_array($towns)) {
            $towns = explode(',', $towns);
        }

        if (!empty($towns)) {
            $get = PostLocation::whereIn('town_id', $towns);
            $ids = array_merge($ids, $get->pluck('post_id')->toArray());
        }

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

    }

    function scopeUsers($query, $users = []) {
        if (empty($users))
            $users = $_GET['users'] ?? [];

        if (!empty($users)) {
            $query->whereIn('user_id', $users);
        }
    }

    public function scopeStatuses($query, $statuses = []) {

        if (empty($statuses))
            $statuses = $_GET['statuses'] ?? [];

        if (!empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

    }

    public function scopeFinalStatuses($query, $statuses = []) {

        if (empty($statuses))
            $statuses = $_GET['finalStatuses'] ?? [];

        if (!empty($statuses)) {
            $query->whereIn('final_status', $statuses);
        }

    }

    public function scopeStatus($query, $status = null) {

        if ($status == null)
            $status = $_GET['status'] ?? null;

        if (!empty($status)) {
            $query->where('status', $status);
        }

    }

    public function scopeFinalStatus($query, $finalStatus = null) {

        if ($finalStatus == null)
            $finalStatus = $_GET['finalStatus'] ?? null;

        if (!empty($finalStatus)) {
            $query->where('final_status', $finalStatus);
        }

    }

    public function scopePublished($query) {
        $query->where('status', 'publish');
    }

    public function scopeKeyValue($query) {
        if (isset($_GET['keyValue'])) {
            $postIds = [];
            foreach ($_GET['keyValue'] as $keyId => $values) {
                if (!empty($values)) {
                    if (!empty($values[0])) {
                        $postIds[$keyId] = PostAttribute::where('key_id', $keyId)->whereIn('value_id', $values)->pluck('post_id')->toArray();
                    }
                }
            }
            if (!empty($postIds)) {
                foreach ($postIds as $ids) {
                    $query->whereIn('id', $ids);
                }
            }
        }
    }

    public function scopeSearchPostTypes($query) {
        $postTypes = getPostTypes();
        $query->whereIn('post_type', $postTypes->where('search', '1')->pluck('type')->toArray());
    }

}
