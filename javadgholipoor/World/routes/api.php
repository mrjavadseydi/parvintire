<?php
Route::group(['prefix' => 'world'], function () {

    Route::get('city/{id}', function ($id) {

        $filters = [
            'id', 'name', 'postage', 'latitude', 'longitude'
        ];

        if (empty($_GET)) {
            $_GET = [];
        }

        $cities = \LaraBase\World\models\City::where('province_id', $id)->where($_GET)->get($filters);

        return [
            'status' => 'success',
            'count'  => $cities->count(),
            'result' => $cities
        ];

    });

    Route::get('town/{id}', function ($id) {

        $filters = [
            'id', 'name', 'latitude', 'longitude'
        ];

        if (empty($_GET)) {
            $_GET = [];
        }

        $towns = \LaraBase\World\models\Town::where('city_id', $id)->where($_GET)->get($filters);

        return [
            'status' => 'success',
            'count'  => $towns->count(),
            'result' => $towns
        ];

    });

    Route::get('region/{id}', function ($id) {

        $filters = [
            'id', 'name', 'latitude', 'longitude'
        ];

        if (empty($_GET)) {
            $_GET = [];
        }

        $regions = \LaraBase\World\models\Region::where('town_id', $id)->where($_GET)->get($filters);

        return [
            'status' => 'success',
            'count'  => $regions->count(),
            'result' => $regions
        ];

    });

});

