<?php

namespace LaraBase\World\Controllers;

use Illuminate\Http\Request;
use LaraBase\CoreController;
use LaraBase\World\models\Region;
use LaraBase\World\models\Town;

class RegionController extends CoreController
{

    public function index(Request $request)
    {

//        $this->apiSecurity('regions');

        $request->validate([
            'townId' => 'required|int'
        ]);

        return Region::where('town_id', $request->townId)->get();

    }

}
