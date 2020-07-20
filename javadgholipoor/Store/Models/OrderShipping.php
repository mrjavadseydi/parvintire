<?php

namespace LaraBase\Store\Models;

use LaraBase\CoreModel;

class OrderShipping extends CoreModel
{

    protected $table = 'order_shippings';

    protected $fillable = [
        'id',
        'order_id',
        'shipping_id',
        'free_postage',
        'postage',
        'created_at',
        'updated_at',
    ];

    public function addCart($orderId, $product)
    {

        $where = [
            'order_id' => $orderId,
            'order_shipping_id' => $this->id,
            'product_id' => $product->product_id,
        ];

        $count = 1;
        $productPrice = $product->price;
        if($product->discount() > 0)
            $productPrice = $product->special_price;

        $productDiscount = 0;
        if ($product->discount() > 0)
            $productDiscount = $product->price - $product->special_price;

        $cart = Cart::where($where)->first();

        if ($cart == null) {
            $cart = Cart::create([
                'order_id' => $orderId,
                'order_shipping_id' => $this->id,
                'product_id' => $product->product_id,
                'price' => $productPrice,
                'total_price' => $productPrice,
                'discount' => $productDiscount
            ]);
            $message = "با موفقیت به سبد خرید افزوده شد";
        } else {

            $count = $cart->count + 1;
            if ($count > $product->stock)
                return ['status' => 'error', 'message' => 'موجودی محصول کافی نمی باشد.', 'productCount' => $cart->count];

            $cart->update([
                'count' => $count,
                'price' => $productPrice,
                'total_price' => $productPrice * $count,
                'discount' => $productDiscount
            ]);

            $message = "تعداد محصول بروزرسانی شد";

        }

        return ['status' => 'success', 'product' => $product, 'productCount' => $count, 'message' => $message];

    }

}
