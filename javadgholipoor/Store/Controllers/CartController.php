<?php


namespace LaraBase\Store\Controllers;


use LaraBase\CoreController;
use LaraBase\Store\Models\Order;

class CartController extends CoreController
{

    public function cart()
    {
        return templateView('order.cart');
    }

    public function address()
    {
        return templateView('order.address');
    }

    public function payment()
    {
        $orderController = new OrderController();
        $order = $orderController->order();
        $carts = $order->carts();
        if ($carts->count() == 0) {
            return  redirect(url('cart'));
        }
        if (empty($order->address_id)) {
            return  redirect(url('cart/address'));
        }
        return templateView('order.payment');
    }

}
