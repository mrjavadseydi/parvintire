<?php

namespace LaraBase;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {

        parent::boot();

    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $directory) {
            $path = "{$directory}/routes";
            if ( file_exists( $path ) && is_dir( $path ) ) {
                dd($path);
                Route::middleware('web')
                    ->middleware('App\Http\Controllers')
                    ->group($path . '/web.php');
//                Route::prefix('api')
//                    ->middleware('api')
//                    ->group($path . '/api.php');
            }
        }
    }

}
