<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('log', function () {
    if (isDev()) {
        dd(DB::getQueryLog());
    }
});

Route::group(['prefix' => 'clear'], function () {

    Route::get('cache', function () {
        if (isDev()) {
            Cache::clear();
            return 'clear cache';
        }
    });

});

Route::group(['prefix' => 'dev'], function () {
    Route::view('uploader', 'uploader');
    Route::get('lang', function () {
        return siteName();
    });
});

Route::get('database', function () {
   if (isDev()) {
       $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'hoorbook_larabase'
        AND ENGINE = 'MyISAM'";
       $tables = \Illuminate\Support\Facades\DB::select($sql);
       foreach ($tables as $table) {
           $tableName = $table->TABLE_NAME;
           $sql = "ALTER TABLE `$tableName` ENGINE=INNODB";
           \Illuminate\Support\Facades\DB::statement($sql);
       }
   }
});

Route::get('test', function () {
    $now = date('Y-m-d H:i:s');
    $posts = \LaraBase\Posts\Models\Post::where('status', 'publishTime')->where('published_at', '<=', $now)->get();
    foreach ($posts as $post) {
        $post->update([
            'status' => 'publish',
            'final_status' => 'publish'
        ]);
    }
    $count = $posts->count();
    if ($count > 0) {
        telegram()->tags(['posts_published'])->message([
            "مطالب با موفقیت منتشر شدند",
            "تعداد مطالب منتشر شده :‌ " . $count
        ])->sendToGroup();
    }
});
