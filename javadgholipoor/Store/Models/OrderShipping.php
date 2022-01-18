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

        $tax = 0;

        if ($cart == null) {
            if($product->tax){
                $tax = (int)((($productPrice) * ($product->tax->percent))/100);
            }
            $cart = Cart::create([
                'order_id' => $orderId,
                'order_shipping_id' => $this->id,
                'product_id' => $product->product_id,
                'price' => $productPrice,
                'total_price' => $productPrice,
                'discount' => $productDiscount,
                'tax' => $tax,
            ]);
            $message = "با موفقیت به سبد خرید افزوده شد";
        } else {

            $count = $cart->count + 1;
            if ($count > $product->stock)
                return ['status' => 'error', 'message' => 'موجودی محصول کافی نمی باشد.', 'productCount' => $cart->count];
            if($product->tax){
                $tax = (int)((($productPrice * $count) * ($product->tax->percent))/100);
            }
            $cart->update([
                'count' => $count,
                'price' => $productPrice,
                'total_price' => $productPrice * $count,
                'discount' => $productDiscount,
                'tax' => $tax,
            ]);

            $message = "تعداد محصول بروزرسانی شد";

        }

        return ['status' => 'success', 'product' => $product, 'productCount' => $count, 'message' => $message];

    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

}
