<?php

namespace LaraBase\Categories\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use function foo\func;
use LaraBase\CoreModel;
use LaraBase\Posts\Models\Post;

class Category extends CoreModel {

    use Sluggable;
    use \LaraBase\Categories\Actions\Category;

    protected $table = 'categories';

    protected $fillable = [
        'post_type',
        'slug',
        'title',
        'excerpt',
        'content',
        'image',
        'parent',
        'lang'
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
                    'source' => 'slug'
                ]
            ];
        } else if(!empty($this->title)) {
            return [
                'slug' => [
                    'source' => 'title'
                ]
            ];
        } else {
            return [
                'slug' => [
                    'source' => date('Y-m-d-H-i-s')
                ]
            ];
        }

    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category');
    }

    public function scopeCanView($query, $canSetPostTypes = [], $canSetCategories = []) {

        if (empty($canSetPostTypes) || empty($canSetCategories))
            $user = auth()->user();

        if (!$user->can('canViewAllCategories')) {

            if (empty($canSetPostTypes)) {
                $canSetPostTypes = $user->canSetPostTypes();
            }

            $postTypes = [];
            if (isset($canSetPostTypes['permissions']['category_list'])) {
                $postTypes = $canSetPostTypes['permissions']['category_list'];
            }

            $categoriesPostTypes = [];
            if (empty($canSetCategories)) {
                $canSetCategories = $user->canSetCategories();
            }

            foreach (array_unique($canSetCategories->pluck('post_type')->toArray()) as $postType) {
                $categoriesPostTypes[] = $postType;
            }

            $query->whereIn('post_type', array_diff($postTypes, $categoriesPostTypes))->orWhereIn('id', $canSetCategories->pluck('id')->toArray());

        }

    }

    public function scopePostTypes($query, $postTypes = []) {

        if (!empty($postTypes)) {
            if (isset($_GET['post_types'])) {
                $postTypes = $_GET['post_types'];
            }
        }

        if (!empty($postTypes))
            $query->whereIn('post_type', $postTypes);

    }

    public function scopePostType($query, $postType = null) {

        if ($postType == null)
            if (isset($_GET['postType']))
                $postType = $_GET['postType'];

        if (!empty($postType))
            $query->where('post_type', $postType);

    }

    public function parent() {
        return Category::whereId($this->parent)->first();
    }

    public function parents()
    {
        $category = $this;
        $categories[] = $category;
        while ($category->parent != null) {
            $category = Category::where('id', $category->parent)->first();
            $categories[] = $category;
        }
        return array_reverse($categories);
    }

    public function parentField($field = 'title') {
        $parent = $this->parent();
        return $parent->$field ?? '-';
    }

}
