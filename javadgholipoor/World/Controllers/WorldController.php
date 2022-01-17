<?php

namespace LaraBase\World\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaraBase\CoreController;

class WorldController extends CoreController {
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function sql() {
        
        if (isActiveWorld()) {
    
            $path = __DIR__ . '/../sql/';
    
            $tables = [
                'countries',
                'provinces',
                'cities',
                'towns',
                'world_metas',
                'post_locations',
                'distances',
            ];
    
            foreach ($tables as $table) {
                $file = $path . $table . '.sql';
                if (file_exists($file)) {
                    if (!\Schema::hasTable($table)) {
                        DB::unprepared( file_get_contents($file) );
                        echo ' IMPORT SUCCESSFULY ' . $table . '<br>';
                    } else {
                        echo 'OLD IMPORTED ' . $table . '<br>';
                    }
                }
            }
            
        }
        
    }
    
}
