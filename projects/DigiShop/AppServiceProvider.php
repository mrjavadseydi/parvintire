<?php

namespace Project\DigiShop;

class AppServiceProvider
{

    public $cart = null;
    public $getCart = true;

    public function __construct()
    {
        view()->composer('*', function ($view) {
            $cart = \Store::getCart();
            $view->with('cart', $cart);
        });
    }

}
