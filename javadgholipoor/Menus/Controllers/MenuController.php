<?php

namespace LaraBase\Menus\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use LaraBase\CoreController;
use LaraBase\Menus\Models\Menu;
use LaraBase\Menus\Models\MenuMeta;
use LaraBase\Posts\Models\Language;

class MenuController extends CoreController
{

    public function __construct() {
        can('menus');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::paginate(100);
        return adminView('menus.all', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        can('createMenu');
        $places = getMenuPlaces();
        $languages = Language::all();
        return adminView('menus.create', compact('places', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        can('createMenu');
        $request->validate(['title' => 'required']);
        $menu = Menu::create($request->all());
        if ($request->has('places')) {
            foreach ($request->places as $place) {
                MenuMeta::create([
                    'menu_id' => $menu->id,
                    'key' => 'place',
                    'value' => $place,
                    'lang' => $request->has('lang') ? $request->lang : 'fa'
                ]);
            }
        }
        return redirect(route('admin.menus.edit', ['id' => $menu->id]));
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
        can('updateMenu');
        $places = getMenuPlaces();
        $menu = Menu::find($id);
        $menuItems = $menu->items(false, true);
        $activePlaces = MenuMeta::where(['menu_id' => $menu->id, 'key' => 'place'])->pluck('value')->toArray();
        return adminView('menus.edit', compact('menu', 'places', 'menuItems', 'activePlaces'));
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

        can('updateMenu');
        $request->validate(['title' => 'required']);
        $menu = Menu::find($id);
        $menu->update([
            'title' => $request->title
        ]);
        $places = MenuMeta::where(['key' => 'place', 'menu_id' => $menu->id])->pluck('value')->toArray();
        foreach ($places as $place) {
            $cacheKey = "{$place}MenuItems";
            if (Cache::has($cacheKey))
                Cache::delete($cacheKey);
        }
        MenuMeta::where(['menu_id' => $id, 'key' => 'place'])->delete();
        if ($request->has('places')) {
            foreach ($request->places as $place) {
                MenuMeta::create([
                    'menu_id' => $id,
                    'key' => 'place',
                    'value' => $place
                ]);
            }
        }
        $menu->cache();
        return redirect(route('admin.menus.edit', ['id' => $id]));

    }

    public function destroyConfirm($id) {
        can('deleteMenu');
        $record = Menu::find($id);
        return adminView('menus.destroy', compact('record'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request) {
        can('deleteMenu');
        $menu = Menu::find($id);
        $menu->delete();
        $menu->cache();

        if ($request->has('url')) {
            return redirect($request->url);
        }

        return redirect(route('admin.menus.index'));
    }

}
