<?php

namespace LaraBase\Menus\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use LaraBase\CoreController;
use LaraBase\Menus\Models\Menu;
use LaraBase\Menus\Models\MenuItem;
use LaraBase\Menus\Models\MenuMeta;

class MenuItemController extends CoreController {
    
    public function __construct() {
        can('menus');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return 'yes';
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        can('createMenu');
        
        if (!isset($_GET['menu_id'])) {
            return redirect(route('admin.menus.index'));
        }
        
        $menu = Menu::find($_GET['menu_id']);
        if ($menu == null) {
            return redirect(route('admin.menus.index'));
        }
        $menuItems = MenuItem::whereNull('type')->where(['menu_id' => $menu->id, 'active' => '1'])->get();
        return adminView('menus.create-item', compact('menu', 'menuItems'));
        
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        can('createMenu');
    
        $rules = [
            'title' => 'required',
            'menu_id' => 'required',
            'link' => 'required'
        ];
        
        if (empty($request->link)) {
            $request->request->add(['link' => '#']);
        }
    
        $request->validate($rules);
    
        $menu = Menu::find($request->menu_id);
        if ($menu == null) {
            return redirect(route('admin.menus.index'));
        }
        
        $data = [
            'menu_id' => $request->menu_id,
            'title' => $request->title,
            'link'  => $request->link,
            'parent' => $request->parent,
            'sort' => $request->sort ?? (MenuItem::where(['menu_id' => $menu->id, 'parent' => $request->parent])->max('sort') + 1),
            'attributes' => json_encode($request->input('attributes'))
        ];
    
        if ($request->has('image')) {
            if (!empty($request->image)) {
                $data['image'] = $request->image;
            }
        }
    
        if ($request->has('icon')) {
            if (!empty($request->icon)) {
                $data['icon'] = $request->icon;
            }
        }
    
        if ($request->has('class')) {
            if (!empty($request->class)) {
                $data['class'] = $request->class;
            }
        }
    
        if ($request->has('active')) {
            $data['active'] = $request->active;
        }
    
        if ($request->has('content')) {
            if (!empty($request->input('content'))) {
                $data['content'] = $request->input('content');
            }
        }
        
        if ($request->has('type')) {
            if (!empty($request->type)) {
                $type = $request->type;
                $data['type'] = $type;
                $getTypeInputs = getMenuTypes($type)['inputs'];
                $json = [];
                if (isset($getTypeInputs['ids'])) {
                    if ($request->has('ids')) {
                        $ids = [];
                        foreach (explode(',', toEnglish($request->ids)) as $id) {
                            $getId = intval($id);
                            if ($getId) {
                                $ids[] = $getId;
                            }
                        }
                        $json['ids'] = $ids;
                    }
                }
                if (isset($getTypeInputs['level'])) {
                    if ($request->has('level')) {
                        $json['level'] = toEnglish($request->level);
                    }
                }
                if (!empty($json)) {
                    $data['data'] = json_encode($json);
                }
            }
        }
    
        MenuItem::create($data);
        $menu->cache();
        return redirect(route('admin.menus.edit', $menu));
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        can('updateMenu');
        $menuItem = MenuItem::find($id);
        $menu = Menu::find($menuItem->menu_id);
        if ($menu == null) {
            return redirect(route('admin.menus.index'));
        }
        $menuItems = MenuItem::whereNull('type')->where(['menu_id' => $menu->id, 'active' => '1'])->get();
        return adminView('menus.edit-item', compact('menuItem', 'menu', 'menuItems'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
        can('updateMenu');
    
        $rules = [
            'title' => 'required',
            'link' => 'required'
        ];
    
        if (empty($request->link)) {
            $request->request->add(['link' => '#']);
        }
    
        $request->validate($rules);
    
        $menuItem = MenuItem::find($id);
        $menu = Menu::find($menuItem->menu_id);
        if ($menu == null) {
            return redirect(route('admin.menus.index'));
        }
    
        $data = [
            'title' => $request->title,
            'link'  => $request->link,
            'parent' => $request->parent,
            'attributes' => json_encode($request->input('attributes'))
        ];
    
        if ($request->has('sort')) {
            if (!empty($request->sort))
                $data['sort'] = $request->sort;
        }
    
        if ($request->has('image')) {
            if (!empty($request->image)) {
                $data['image'] = $request->image;
            }
        }
    
        if ($request->has('icon')) {
            if (!empty($request->icon)) {
                $data['icon'] = $request->icon;
            }
        }
    
        if ($request->has('class')) {
            if (!empty($request->class)) {
                $data['class'] = $request->class;
            }
        }
    
        if ($request->has('active')) {
            $data['active'] = $request->active;
        }
    
        if ($request->has('content')) {
            if (!empty($request->input('content'))) {
                $data['content'] = $request->input('content');
            }
        }
    
        $data['type'] = null;
        $data['data'] = null;
        if ($request->has('type')) {
            if (!empty($request->type)) {
                $type = $request->type;
                $data['type'] = $type;
                $getTypeInputs = getMenuTypes($type)['inputs'];
                $json = [];
                if (isset($getTypeInputs['ids'])) {
                    if ($request->has('ids')) {
                        $ids = [];
                        foreach (explode(',', toEnglish($request->ids)) as $id) {
                            $getId = intval($id);
                            if ($getId) {
                                $ids[] = $getId;
                            }
                        }
                        $json['ids'] = $ids;
                    }
                }
                if (isset($getTypeInputs['level'])) {
                    if ($request->has('level')) {
                        $json['level'] = toEnglish($request->level);
                    }
                }
                if (!empty($json)) {
                    $data['data'] = json_encode($json);
                }
            }
        }
    
        $menuItem->update($data);
        $menu->cache();
        return redirect(route('admin.menus.edit', $menu));
        
    }
    
    
    public function destroyConfirm($id) {
        can('deleteMenu');
        $record = MenuItem::find($id);
        return adminView('menus.destroy-item', compact('record'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request) {
        can('deleteMenu');
        $record = MenuItem::find($id);
        $record->delete();
        $menu = Menu::find($record->menu_id);
        $menu->cache();
        
        if ($request->has('url')) {
            return redirect($request->url);
        }
        
        return redirect(route('admin.menus.edit', ['id' => $record->menu_id]));
    }
    
}
