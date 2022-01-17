<?php

namespace LaraBase;

use DB;
use LaraBase\Hook\Hook;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use LaraBase\Permissions\Models\Permission;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider {

    // TODO optimize

    private
        $defaultStringLength = 191,
        $configPath = 'config';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        Schema::defaultStringLength($this->defaultStringLength);
        $this->publishConfigs();
        $this->hook();
        $this->init();

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setLocale();
        $this->install();
        $this->validator();
        $this->permissions();
    }

    public function getLocale()
    {
        if (hasCookie('locale'))
            return getCookie('locale');
        else
            return 'fa';
    }

    public function setLocale()
    {
        $this->app->setLocale($this->getLocale());
    }

    public function install() {
        $appName = env('APP_NAME');

        if (!Cache::has($appName . 'db')) {

            $usersTable = false;
            $visitTable = false;

            try {
                DB::connection()->getPdo();

                if (\Schema::hasTable('users')) {
                    $usersTable = true;
                }

                if (\Schema::hasTable('visits')) {
                    $visitTable = true;
                }

                $dbConnection = true;

            } catch (\Exception $e) {
                $dbConnection = false;
            }

            if ($dbConnection && $usersTable && $visitTable) {
                Cache::forever($appName . 'dbConnection', $dbConnection);
                Cache::forever($appName . 'usersTable', $usersTable);
                Cache::forever($appName . 'visitTable', $visitTable);
                Cache::forever($appName . 'db', true);
            } else {
                Cache::put($appName . 'dbConnection', $dbConnection);
                Cache::put($appName . 'usersTable', $usersTable);
                Cache::put($appName . 'visitTable', $visitTable);
            }

        }

    }

    public function validator() {

        Validator::extend('false', function($attribute, $value, $parameters)
        {
            return false;
        });

        Validator::extend('mobile', function($attribute, $value, $parameters)
        {
            if ($value != null) {
                return preg_match('/^(?:09|\+?63)(?:\d(:?-)?){9,10}$/', $value) ? true : false;
            }
            return true;
        });

        Validator::extend('phone', function($attribute, $value, $parameters)
        {
            if ($value != null) {
                return preg_match('/^0[0-8][0-8]{5,11}/', $value) ? true : false;
            }
            return true;
        });

        Validator::extend('nationalCode', function($attribute, $value, $parameters)
        {
            if ($value != null) {
                if (!preg_match('/^[0-9]{10}$/', $value))
                    return false;

                for ($i = 0; $i < 10; $i++) {
                    if (preg_match('/^' . $i . '{10}$/', $value))
                        return false;
                }

                for ($i = 0, $sum = 0; $i < 9; $i++) {
                    $sum += ((10 - $i) * intval(substr($value, $i, 1)));
                }

                $ret = $sum % 11;
                $parity = intval(substr($value, 9, 1));
                if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity)) {
                    return true;
                }
                return false;
            }

            return true;
        });

        Validator::extend('postalCode', function($attribute, $value, $parameters)
        {
            if ($value != null) {
                return preg_match('/^[0-9]{10}$/', $value) ? true : false;
            }

            return true;
        });

        Validator::extend('latin', function($attribute, $value, $parameters)
        {
            if ($value != null) {
                return preg_match('/(^([a-zA-Z0-9]+)(\d+)?$)/', $value) ? true : false;
            }

            return true;
        });

    }

    public function hook() {
        $this->app->singleton('Hook', function(){
            return new Hook();
        });
    }

    public function init() {

        try {
            DB::connection()->getPdo();
            if (\Schema::hasTable('options')) {
                $options = [];
                $getOptions = DB::table('options')->where('more', 'autoload')->get();
                foreach ($getOptions as $option)
                    $options[$option->key] = $option->value;

                Hook::add('options', function ($hook) use ($options) {
                    return $options;
                });

                $themes = [
                    'auth'       => $options['authTheme'] ?? 'default',
                    'admin'      => $options['adminTheme'] ?? 'default',
                    'template'   => $options['templateTheme'] ?? 'default',
                    'email'      => $options['emailTheme'] ?? 'default',
                    'uploader'   => $options['uploaderTheme'] ?? 'default',
                    'errors'     => $options['errorsTheme'] ?? 'default',
                ];

                Hook::add('themes', function ($hook) use ($themes) {
                    return $themes;
                });

                $projectOptions = [];
                foreach ($themes as $type => $name) {
                    $file = base_path("resources/views/{$type}/{$name}/theme.json");
                    if (file_exists($file)) {
                        $data = json_decode(file_get_contents($file), true);
                        foreach (['images', 'values'] as $option) {
                            if (isset($data['options'][$option])) {
                                foreach ($data['options'][$option] as $value) {
                                    $projectOptions[] = $value['key'];
                                }
                            }
                        }
                    }
                }

                foreach (getDefaultOptionImages() as $getDefaultOptionImage) {
                    foreach ($getDefaultOptionImage as $item) {
                        $projectOptions[] = $item['key'];
                    }
                }

                $images = [];
                foreach (DB::table('options')->whereIn('key', $projectOptions)->get() as $option) {
                    $images[$option->key] = [
                        'value' => $option->value,
                        'more' => $option->more
                    ];
                }

                Hook::add('images', function ($hook) use ($images) {
                    return $images;
                });

                view()->composer('*', function ($view) use ($themes) {
                    $view->with('adminTheme', $themes['admin'] );
                    $view->with('templateTheme', $themes['template'] );
                    $view->with('authTheme', $themes['auth'] );
                    $view->with('emailTheme', $themes['email'] );
                    $view->with('uploaderTheme', $themes['uploader'] );
                    $view->with('errorsTheme', $themes['errors'] );
                });
            }
        } catch (\Exception $e) {

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

    protected function permissions()
    {
        try {

            DB::connection()->getPdo();

            if (hasCache('permissions'))
                $permissions = getCache('permissions');
            else {
                $permissions = Permission::all();
                setCache('permissions', $permissions);
            }

            foreach ($permissions as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasRole($permission->roles());
                });
            }

        } catch (\Exception $e) {
        }
    }

}
