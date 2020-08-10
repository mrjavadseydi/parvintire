<?php
Route::get('sitemap.xml', 'SitemapController@index')->name('sitemap');
Route::get('sitemap/{yearMonth}/{offset}.xml', 'SitemapController@urls')->name('sitemapUrls');
Route::get('robots.txt', 'SitemapController@robots')->name('robots.txt');
