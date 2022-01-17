<?php

namespace LaraBase\World\Controllers;

use Illuminate\Http\Request;
use LaraBase\CoreController;
use LaraBase\World\models\City;

class CityController extends CoreController
{

    public function index(Request $request)
    {

//        $this->apiSecurity('cities');

        $request->validate([
            'provinceId' => 'required|int'
        ]);

        return City::where('province_id', $request->provinceId)->get();

    }

}
