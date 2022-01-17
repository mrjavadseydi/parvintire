<?php

namespace Project;

use Illuminate\Support\ServiceProvider;
use LaraBase\Store\Store;

class AppServiceProvider extends ServiceProvider {

    private
        $appName = null,
        $configPath = 'config';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->appName = env('APP_NAME');

        $this->publishConfigs();

        $this->app->singleton('Store', function () {
            return new Store();
        });

        $this->app->bind('store', function () {
            return new Store();
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadMigrations();

        $appServiceProvider = __DIR__ . '/' . $this->appName . '/AppServiceProvider.php';
        if (file_exists($appServiceProvider)) {
            $appClass = '\Project\\'.$this->appName.'\AppServiceProvider';
            new $appClass();
        }

    }

    public function publishConfigs() {

        foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $directory) {
            $path = "{$directory}/{$this->configPath}";
            if ( file_exists( $path ) && is_dir( $path ) ) {
                foreach (glob("{$path}/*.php") as $file) {
                    $parts = explode('/', $file);
                    $fileName = end($parts);
                    $this->publishes([
                        $file => config_path($fileName),
                    ]);
                    $this->mergeConfigFrom(
                        $file, str_replace('.php', '', $fileName)
                    );
                }
            }
        }

    }

    public function loadMigrations() {
        $appName = env('APP_NAME');
        $this->loadMigrationsFrom(__DIR__ . "/{$appName}/migrations");
    }

}
