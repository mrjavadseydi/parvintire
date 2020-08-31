<?php

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'v1'], function () {

        Route::post('login', function () {
//            $user = \LaraBase\Auth\Models\User::find(1);
//            $token = $user->createToken('api')->accessToken;
//            return [
//                'token' => $token,
//                'user' => $user
//            ];
        });

        Route::middleware('auth:api')->get('get', function (\Illuminate\Http\Request $request) {
//    if (auth()->user()->tokenCan('')) {
//        return [
//            'success'
//        ];
//    } else {
//        return [
//            'error'
//        ];
//    }
            return ['s'];
        });

    });
});

