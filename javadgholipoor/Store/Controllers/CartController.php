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
        if(!auth()->check()) {
            return redirect(url('cart'));
        }
        return templateView('order.address');
    }

    public function more()
    {
        if(!auth()->check()) {
            return redirect(url('cart'));
        }
        return templateView('order.more');
    }

    public function payment()
    {
        $orderController = new OrderController();
        $order = $orderController->order();
        $carts = $order->carts();
        if ($carts->count() == 0) {
            return  redirect(url('cart'));
        }
        if (empty($order->address_id) && needs_address($order->type)) {
            return redirect(url('cart/address'));
        }
        $user = auth()->user();
        $ncodeMeta = $user->getMeta('nationalCode');
        if (needs_name_code($order->type) && (auth()->user()->name == '' ||!$ncodeMeta|| auth()->user()->name == null || $ncodeMeta == null)) {
            return redirect(url('cart/more'));
        }
        return templateView('order.payment');
    }

}
