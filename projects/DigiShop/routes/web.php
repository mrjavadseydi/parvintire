<?php

Route::group(['namespace' => 'Project\DigiShop\Controllers'], function () {
    Route::get('/', 'PageController@home')->name('home');
    Route::get('products/{id}/{slug}', 'PageController@product')->name('product');
    Route::get('articles/{id}/{slug}', 'PageController@article')->name('article');
    Route::get('podcasts/{id}/{slug}', 'PageController@podcast')->name('podcast');
    Route::get('books/{id}/{slug}', 'PageController@book')->name('book');
    Route::get('contact-us', 'PageController@contactUs')->name('contact-us');
});

Route::get('bot', function () {
    $agent = strtolower('Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.92 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
    $bots = [
        'bot',
        'checkmarknetwork'
    ];
    $addSearch = true;
    foreach ($bots as $bot) {
        if (strpos($agent, $bot)) {
            $addSearch = false;
            break;
        }
    }
    if ($addSearch) {
        echo 'added';
    } else {
        echo 'error';
    }
    echo '<br><br>' . $agent;
});
