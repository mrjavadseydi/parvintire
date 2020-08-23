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
                $this->loadRoute($directory, $path, 'LaraBase');
            }
        }

        $appName = env('APP_NAME');
        $path = str_replace('javadgholipoor', 'projects', __DIR__ . "/{$appName}/routes");
        if ( file_exists( $path ) && is_dir( $path ) ) {
            $this->loadRoute($appName, $path, 'Project');
        }

    }

    public function loadRoute($directory, $path, $ns)
    {
        $parts = explode('/', $directory);
        $namespace = $ns . '\\'.end($parts).'\Controllers';
        $admin = $path . '/admin.php';
        $web = $path . '/web.php';
        $api = $path . '/api.php';
        if (file_exists($admin)) {
            Route::middleware(['web', 'auth:web'])
                ->as('admin.')
                ->prefix('admin')
                ->namespace($namespace)
                ->group($admin);
        }
        if (file_exists($web)) {
            Route::middleware('web')
                ->namespace($namespace)
                ->group($web);
        }
        if (file_exists($api)) {
            Route::prefix('api')
                ->middleware(['api'])
                ->namespace($namespace)
                ->group($api);
        }
    }

}
