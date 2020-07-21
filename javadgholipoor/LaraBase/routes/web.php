<?php

Route::group([
    'prefix'     => 'larabase',
    'namespace'  => 'LaraBase\LaraBase\Controllers',
    'as'         => 'larabase.'
], function () {

    Route::get('installer', 'InstallController@installer')->name('installer');
    Route::post('storeConnection', 'InstallController@storeConnection')->name('storeConnection');
    Route::post('storeAdministrator', 'InstallController@storeAdministrator')->name('storeAdministrator');

    Route::get('migrate', 'MigrateController@migrate')->name('migrate');

    Route::get('update', 'UpdateController@update')->name('update');
    Route::get('update/install', 'UpdateController@install')->name('update.install');
    Route::get('update/themes/{force}', 'ThemeController@updateThemes')->name('update.themes');
    Route::get('update/themes', 'ThemeController@updateThemes')->name('update.themes');

    Route::get('init', 'InitController@init')->name('init');

    Route::get('info', function () {

        if (isDev()) {

            Artisan::call('clear:cache');

            $result = checkForUpdates();

            $old = getVersion();
            $new = $result['version'];

            $data[] = [
                'title' => 'larabase',
                'old' => $old,
                'new' => $new
            ];

            if (isset($result['project'])) {
                $data[] = [
                    'title' => 'project',
                    'old' => getProjectVersion($result['project']['name']),
                    'new' => $result['project']['version']
                ];
            }

            foreach ($result['themes'] as $theme) {
                $data[] = [
                    'title' => 'Theme ' . $theme['type'] . "/" . $theme['name'],
                    'old' => getThemeVersion($theme['type'], $theme['name']),
                    'new' => $theme['version']
                ];
            }

            foreach ($data as $item) {
                $color = '#222';
                if ($item['old'] != $item['new']) {
                    $color = 'deeppink';
                }
                echo '<span style="color: '.$color.';">'.$item['title'].' '. $item['old'] . ' to ' . $item['new'] .'</span><br>';
            }

            echo '<br><br><a target="_blank" href="'.url('larabase/update').'">بروزرسانی</a>';
            echo '<br><br><a target="_blank" href="'.url('larabase/update/install').'">بروزرسانی و نصب</a>';
            echo '<br><br><a target="_blank" href="'.url('larabase/update/themes').'">بروزرسانی قالب ها</a>';

            return;

        }

        return redirect(route('admin.dashboard'));

    });

    Route::get('sync', 'SyncController@sync');
    Route::get('sync/run', 'SyncController@run');

});
