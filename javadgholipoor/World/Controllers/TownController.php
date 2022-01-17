<?php

namespace LaraBase\World\Controllers;

use Illuminate\Http\Request;
use LaraBase\CoreController;
use LaraBase\World\models\Town;

class TownController extends CoreController
{

    public function index(Request $request)
    {

//        $this->apiSecurity('towns');

        $request->validate([
            'cityId' => 'required|int'
        ]);

        return Town::where('city_id', $request->cityId)->get();

    }

}
