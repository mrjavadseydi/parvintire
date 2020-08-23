<?php

namespace LaraBase\World\Controllers;

use Illuminate\Http\Request;
use LaraBase\CoreController;
use LaraBase\World\models\Province;

class ProvinceController extends CoreController
{

    public function index(Request $request)
    {

//        $this->apiSecurity('provinces');
        $countryId = 244;
        if($request->has('countryId'))
            $countryId = $request->countryId;

        return Province::where('country_id', $countryId)->get();

    }

}
