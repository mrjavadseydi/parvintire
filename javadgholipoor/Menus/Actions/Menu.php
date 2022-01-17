<?php

namespace LaraBase\Menus\Actions;

use Illuminate\Support\Facades\Cache;
use LaraBase\Categories\Models\Category;
use LaraBase\Menus\Models\MenuItem;
use LaraBase\Menus\Models\MenuMeta;
use LaraBase\Posts\Models\Language;
use LaraBase\Posts\Models\Post;
use LaraBase\Posts\Models\PostCategory;
use LaraBase\Posts\Models\PostTag;
use LaraBase\Posts\Models\PostType;

trait Menu {

    public function cache() {
        $langs = ['fa'];
        $getLangs = Language::all();
        if ($getLangs->count() > 0) {
            $langs = [];
            foreach ($getLangs as $l) {
                $langs[] = $l->lang;
            }
        }
        foreach ($langs as $lang) {
            $menuItems = MenuItem::where('menu_id', $this->id)->whereNotNull('type')->get();
            $places = MenuMeta::where(['key' => 'place', 'menu_id' => $this->id])->pluck('value')->toArray();
            foreach ($places as $place) {
                $cacheKey = "{$place}MenuItems" . $lang;
                if (Cache::has($cacheKey))
                    Cache::delete($cacheKey);
            }
            MenuMeta::where(['menu_id' => $this->id, 'key' => 'cache'])->delete();
            if ($menuItems->where('type', 'postTypes')->count() > 0) {
                foreach ($places as $place) {
                    $cacheKey = "{$place}MenuItems" . $lang;
                    MenuMeta::create([
                        'menu_id' => $this->id,
                        'key'     => 'cache',
                        'value'   => 'postTypes',
                        'more'    => $cacheKey
                    ]);
                }
            }
            foreach ($menuItems->whereIn('type', ['categoriesPostTypes', 'categoriesCategories', 'postsPostTypes', 'postsCategories', 'postsTags'])->filter() as $item) {
                $data = json_decode($item->data, true);
                if (isset($data['ids'])) {
                    foreach ($data['ids'] as $id) {
                        $value = "{$item->type}_{$id}";
                        foreach ($places as $place) {
                            $cacheKey = "{$place}MenuItems" . $lang;
                            MenuMeta::create([
                                'menu_id' => $this->id,
                                'key'     => 'cache',
                                'value'   => $value,
                                'more'    => $cacheKey
                            ]);
                        }
                    }
                }
            }

            $menuItemsKey = "menu" . $this->id . "Items" . $lang;
            $menuIdKey = "menu" . $this->id . "Id" . $lang;
            $menuKey = "menu" . $this->id . "ById" . $lang;

            if (hasCache($menuItemsKey))
                deleteCache($menuItemsKey);

            if (hasCache($menuIdKey))
                deleteCache($menuIdKey);

            if (hasCache($menuKey))
                deleteCache($menuKey);
        }

    }

    public function items($onlyActive = true, $onlyMenuItems = false) {

        $where = ['menu_id' => $this->id];
        if ($onlyActive) {
            $where['active'] = '1';
        }

        $menuItems = treeView(MenuItem::where($where)->orderBy('sort', 'asc')->get());

        $i = 0;
        foreach ($menuItems as $menuItem) {

            $record = $menuItem['record'];
            $type = $record->type;

            $menuItems[$i]['id'] = $record['id'];
            $menuItems[$i]['link'] = $record['link'];
            $menuItems[$i]['icon'] = $record['icon'];
            $menuItems[$i]['image'] = $record['image'];
            $menuItems[$i]['class'] = $record['class'];
            $menuItems[$i]['active'] = $record['active'];
            $menuItems[$i]['attributes'] = $record['attributes'];
            $menuItems[$i]['content'] = $record['content'];
            $menuItems[$i]['createdAt'] = $record['created_at'];

            if (isset($menuItem['list'])) {
                $menuItems[$i]['list'] = $this->subItems($menuItem['list'], $onlyMenuItems, 1);
            }

            if (!$onlyMenuItems) {
                if (!empty($type)) {
                    $menuItems[$i]['list'] = array_merge($menuItem['list'] ?? [], $this->$type(json_decode($record->data, true), 2));
                }
            }

            unset($menuItems[$i]['record']);

            $i++;

        }

        return $menuItems;

    }

    public function subItems($menuItems, $onlyMenuItems = false, $level) {

        $level = $level+1;

        $i = 0;
        foreach ($menuItems as $menuItem) {

            $record = $menuItem['record'];
            $type = $record->type;

            $menuItems[$i]['link'] = $record['link'];
            $menuItems[$i]['icon'] = $record['icon'];
            $menuItems[$i]['image'] = $record['image'];
            $menuItems[$i]['class'] = $record['class'];
            $menuItems[$i]['active'] = $record['active'];
            $menuItems[$i]['attributes'] = $record['attributes'];

            if (isset($menuItem['list'])) {
                $menuItems[$i]['list'] = $this->subItems($menuItem['list'], $onlyMenuItems, $level);
            }

            if (!$onlyMenuItems) {
                if (!empty($type)) {
                    $menuItems[$i]['list'] = array_merge($menuItem['list'] ?? [], $this->$type(json_decode($record->data, true), $level));
                }
            }

            unset($menuItems[$i]['record']);

            $i++;

        }

        return $menuItems;

    }

    public function postTypes($data, $level) {
        $output = [];
        $postTypes = PostType::where('sitemap', '1')->get();
        foreach ($postTypes as $postType) {
            $output[] = [
                'id' => null,
                'title' => $postType->total_label,
                'link' => $postType->href(),
                'icon' => $postType->icon,
                'image' => null,
                'class' => null,
                'attributes' => [],
                'level' => $level,
                'active' => '1'
            ];
        }
        return $output;
    }

    public function postsTags($data, $level) {

        $ids = [];
        if (isset($data['ids'])) {
            $ids = $data['ids'];
        }

        $postIds = PostTag::whereIn('tag_id', $ids)->pluck('post_id')->toArray();
        $posts = Post::Published()->whereIn('id', $postIds)->get();
        $output = [];

        foreach ($posts as $post) {
            $output[] = [
                'id' => null,
                'title' => $post->title,
                'link' => $post->href(),
                'icon' => null,
                'image' => null,
                'class' => null,
                'attributes' => [],
                'level' => $level,
                'active' => '1'
            ];
        }

        return $output;

    }

    public function postsCategories($data, $level) {

        $ids = [];
        if (isset($data['ids'])) {
            $ids = $data['ids'];
        }

        $postIds = PostCategory::whereIn('category_id', $ids)->pluck('post_id')->toArray();
        $posts = Post::Published()->whereIn('id', $postIds)->get();
        $output = [];

        foreach ($posts as $post) {
            $output[] = [
                'id' => null,
                'title' => $post->title,
                'link' => $post->href(),
                'icon' => null,
                'image' => null,
                'class' => null,
                'attributes' => [],
                'level' => $level,
                'active' => '1'
            ];
        }

        return $output;

    }

    public function postsPostTypes($data, $level) {

        $ids = [];
        if (isset($data['ids'])) {
            $ids = $data['ids'];
        }

        $postTypes = PostType::whereIn('id', $ids)->pluck('type')->toArray();
        $posts = Post::Published()->whereIn('post_type', $postTypes)->get();
        $output = [];

        foreach ($posts as $post) {
            $output[] = [
                'id' => null,
                'title' => $post->title,
                'link' => $post->href(),
                'icon' => null,
                'image' => null,
                'class' => null,
                'attributes' => [],
                'level' => $level,
                'active' => '1'
            ];
        }

        return $output;

    }

    public function categoriesCategories($data, $level) {

        $ids = [];
        if (isset($data['ids'])) {
            $ids = $data['ids'];
        }

        $catLevel = 1;
        if (isset($data['level'])) {
            $catLevel = $data['level'];
        }

        $allIds = [];
        $levels = [];
        for($i = 1; $i <= $catLevel; $i++) {
            $ids = Category::whereIn('parent', $ids)->pluck('id')->toArray();
            $allIds = array_merge($allIds, $ids);
            $levels[$i] = $ids;
        }

        $output = [];
        $categories = Category::whereIn('id', $allIds)->get();

        $cats = $categories->whereIn('id', $levels[1])->filter();
        $i = 0;
        foreach ($cats as $cat) {
            $output[$i] = [
                'id' => null,
                'title' => $cat->title,
                'link' => $cat->href(),
                'icon' => null,
                'image' => null,
                'class' => null,
                'attributes' => [],
                'level' => $level,
                'active' => '1'
            ];

            if (isset($levels[2])) {
                $list = $this->subCategories($cat->id, $levels, $categories, 2);
                if (!empty($list)) {
                    $output[$i]['list'] = $list;
                }
            }

            $i++;
        }

        return $output;

    }

    public function categoriesPostTypes($data, $level) {

        $ids = [];
        if (isset($data['ids'])) {
            $ids = $data['ids'];
        }

        $postTypes = PostType::whereIn('id', $ids)->pluck('type')->toArray();
        $ids = Category::whereIn('post_type', $postTypes)->whereNull('parent')->pluck('id')->toArray();

        $catLevel = 1;
        if (isset($data['level'])) {
            $catLevel = $data['level'];
        }

        $allIds = [];
        $levels = [];
        for($i = 1; $i <= $catLevel; $i++) {
            $ids = Category::whereIn('parent', $ids)->pluck('id')->toArray();
            $allIds = array_merge($allIds, $ids);
            $levels[$i] = $ids;
        }

        $output = [];
        $categories = Category::whereIn('id', $allIds)->get();

        $cats = $categories->whereIn('id', $levels[1])->filter();
        $i = 0;
        foreach ($cats as $cat) {
            $output[$i] = [
                'id' => null,
                'title' => $cat->title,
                'link' => $cat->href(),
                'icon' => null,
                'image' => null,
                'class' => null,
                'attributes' => [],
                'level' => $level,
                'active' => '1'
            ];

            if (isset($levels[2])) {
                $list = $this->subCategories($cat->id, $levels, $categories, 2);
                if (!empty($list)) {
                    $output[$i]['list'] = $list;
                }
            }

            $i++;
        }

        return $output;

    }

    public function subCategories($catId, $levels, $categories, $level) {

        $output = [];
        $level = $level + 1;
        $cats = $categories->where('parent', $catId)->filter();

        if ($cats != null) {
            $i = 0;
            foreach ($cats as $cat) {

                $output[$i] = [
                    'id' => null,
                    'title' => $cat->title,
                    'link' => $cat->href(),
                    'icon' => null,
                    'image' => null,
                    'class' => null,
                    'attributes' => [],
                    'level' => $level,
                    'active' => '1'
                ];

                if (isset($levels[$level])) {
                    $list = $this->subCategories($cat->id, $levels, $categories, $level);
                    if (!empty($list)) {
                        $output[$i]['list'] = $list;
                    }
                }

                $i++;
            }
        }

        return $output;

    }

}
