<?php

use Illuminate\Support\Facades\Cache;
use LaraBase\Menus\Models\MenuMeta;
use LaraBase\Menus\Models\Menu;

function getMenuPlaces()
{

    $locations = [];
    $themes = doAction('themes');

    foreach (['admin', 'template'] as $type) {
        $theme = $themes[$type];
        $jsonFile = base_path("resources/views/{$type}/{$theme}/theme.json");
        if (file_exists($jsonFile)) {
            $jsonData = json_decode(file_get_contents($jsonFile), true);
            if (isset($jsonData['menus'])) {
                foreach ($jsonData['menus'] as $menu => $menuItem) {
                    $locations[$type][$menu] = [
                        'key' => $menu,
                        'type' => $type,
                        'title' => $menuItem['title']
                    ];
                }
            }
        }
    }

    return $locations;

}

function getMenu($place, $menuById = false, $lang = null)
{
    if ($lang == null)
        $lang = app()->getLocale();

    if (empty($place) && empty($menuById)) {
        return null;
    }
    if ($menuById) {
        $menuKey = "menu" . $menuById . "ById" . $lang;
        $menuIdKey = "menu" . $menuById . 'Id' . $lang;
        $menuItemsKey = "menu" . $menuById . "Items" . $lang;
    } else {
        $menuKey = $place . "MenuByPlace" . $lang;
        $menuIdKey = $place . 'MenuId' . $lang;
        $menuItemsKey = $place . "MenuItems" . $lang;
    }
    if (Cache::has($menuItemsKey)) {
        return Cache::get($menuItemsKey);
    }
    if (Cache::has($menuIdKey)) {
        $menuId = Cache::get($menuIdKey);
    } else {
        if ($menuById) {
            $menuId = $menuById;
            Cache::forever($menuIdKey, $menuId);
        } else {
            $menuPlace = MenuMeta::where(['key' => 'place', 'value' => $place, 'lang' => $lang])->first();
            if ($menuPlace != null) {
                $menuId = $menuPlace->menu_id;
                Cache::forever($menuIdKey, $menuId);
            }
        }
    }
    if (isset($menuId)) {
        if (Cache::has($menuKey)) {
            $menu = Cache::get($menuKey);
        } else {
            $getMenu = Menu::find(Cache::get($menuIdKey));
            if ($getMenu != null) {
                $menu = $getMenu;
                Cache::forever($menuKey, $menu);
            }
        }
        if (isset($menu)) {
            $menuItems = $menu->items();
            Cache::forever($menuItemsKey, $menuItems);
            return $menuItems;
        }
    }
    return [];
}

function getMenuById($menuId) {
    return getMenu(false, $menuId);
}

function getMenuName($menuId)
{
    $menu = Menu::find($menuId);
    return $menu->title;
}

function getMenuTypes($key = false)
{

    $types = [
        'categoriesPostTypes' => [
            'title' => 'نمایش دسته ها - پست‌تایپ',
            'inputs' => [
                'ids' => ['title' => 'آیدی پست تایپ ها'],
                'level' => ['title' => 'تعداد سطوح نمایش']
            ]
        ],
        'categoriesCategories' => [
            'title' => 'نمایش دسته ها - دسته‌',
            'inputs' => [
                'ids' => ['title' => 'آیدی دسته ها'],
                'level' => ['title' => 'تعداد سطوح نمایش']
            ]
        ],
        'postsPostTypes' => [
            'title' => 'نمایش پست ها - پست‌تایپ',
            'inputs' => [
                'ids' => ['title' => 'آیدی پست تایپ ها'],
            ]
        ],
        'postsCategories' => [
            'title' => 'نمایش پست ها - دسته',
            'inputs' => [
                'ids' => ['title' => 'آیدی دسته ها'],
            ]
        ],
        'postsTags' => [
            'title' => 'نمایش پست ها - تگ',
            'inputs' => [
                'ids' => ['title' => 'آیدی تگ ها'],
            ]
        ],
        'postTypes' => [
            'title' => 'نمایش پست تایپ ها',
            'inputs' => []
        ],
    ];

    if ($key) {
        return $types[$key];
    }

    return $types;

}


function isActiveMenu($menu, $class = null)
{

    $output = false;

    $currentUrl = parse_url(url()->full());
    $menuUrl = parse_url(url($menu['link']));

    $url = $currentUrl['host'] . (isset($currentUrl['path']) ? $currentUrl['path'] : '') . (isset($currentUrl['query']) ? $currentUrl['query'] : '');
    $menu = $menuUrl['host'] . (isset($menuUrl['path']) ? $menuUrl['path'] : '') . (isset($menuUrl['query']) ? $menuUrl['query'] : '');

    if ($url == $menu)
        $output = true;

    if (!empty($class)) {
        if ($output)
            return $class;

        return '';
    }

    return $output;

}
