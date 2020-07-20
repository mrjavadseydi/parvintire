<?php

namespace LaraBase\Store;

use LaraBase\Store\Controllers\OrderController;

class Store
{

    protected $cart = null;

    public function getCart()
    {
        if ( !is_null($this->cart) )
            return $this->cart;

        $orderController = new OrderController();
        $this->cart = $orderController->cart(null);

        return $this->cart;
    }

}
