<?php

namespace LaraBase\Store\Models;

use LaraBase\Auth\Models\User;
use LaraBase\CoreModel;
use LaraBase\Payment\Models\Transaction;
use LaraBase\Posts\Models\Post;
use ShippingWorld;

class Order extends CoreModel
{

    protected $table = 'orders';

    protected $fillable = [
        'id',
        'user_id',
        'hash',
        'price',
        'payed_price',
        'gift',
        'address_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function scopeSuccess($query)
    {
        $query->where('status', 1);
    }

    public function scopeUser($query, $userId)
    {
        $query->where('user_id', $userId);
    }

    public function scopeStatus($query, $status = -1)
    {
        $run = false;
        if ($status >= 0) {
            $run = true;
        } else {
            if (isset($_GET['status'])) {
                $status = $_GET['status'];
                $run = true;
            }
        }
        if ($run)
            $query->where('status', $status);
    }

    public function productsCounts()
    {
        return Cart::where('order_id', $this->id)->sum('count');
    }

    public function shippings()
    {
        return OrderShipping::where('order_id', $this->id)->get();
    }

    public function shippingStatuses()
    {
        return $this->hasMany(OrderShippingStatus::class);
    }

    public function shippingStatus()
    {
        return $this->hasOne(OrderShippingStatus::class);
    }

    public function transactions()
    {
        //return Transaction::where(['relation' => 'order','relation_id' => $this->id, 'status' => $status])->first();
        return $this->morphMany(Transaction::class, 'relation', 'relation');
    }

    public function transaction($status = 1)
    {
        return Transaction::where(['relation' => 'order','relation_id' => $this->id, 'status' => $status])->first();
        return Transaction::where('status', $status)->first();
    }

    public function carts()
    {
        return Cart::where('order_id', $this->id)->get();
    }

    public function address()
    {
        if ($this->address_id != null) {
            return Address::find($this->address_id);
        }
        return null;
    }

    public function addCart($productId)
    {

        $post = null;
        $product = Product::where('product_id', $productId)->first();
        if ($product != null) {
            $post = Post::find($product->post_id);
        }

        if ($post == null)
            return ['status' => 'error', 'message' => '?????? ???????? ???? ?????????? ?????? ?????? ?????? ??????????'];

        if ($post->status != 'publish')
            return ['status' => 'error', 'message' => '?????? ???????? ???? ?????????? ?????? ?????? ?????? ??????????'];

        if ($product->stock == 0)
            return ['status' => 'error', 'message' => '?????????? ?????????????? ???? ????????'];

        $orderId = $this->id;
        $shippingId = $product->shipping_id;

        if ($shippingId == null)
            return ['status' => 'error', 'message' => '?????? ?????? ?? ?????? ?????????? ?????? ???????? ??????'];

        $shipping = OrderShipping::where([
            'order_id' => $this->id,
            'shipping_id' => $shippingId
        ])->first();
        if ($shipping == null) {
            $getShipping = Shipping::find($shippingId);
            $shingle = null;
            if (!empty($this->address_id)) {
                $address = Address::find($this->address_id);
                $shingle = $address->shingle($shippingId);
            }
            $shipping = OrderShipping::create([
                'order_id' => $orderId,
                'shipping_id' => $shippingId,
                'free_postage' => $getShipping->free_postage,
                'postage' => ($shingle == null ? 0 : $shingle->postage)
            ]);
        }

        return array_merge($shipping->addCart($this->id, $product));

    }

    public function deleteCart($productId)
    {

        $cart = Cart::where(['order_id' => $this->id, 'product_id' => $productId])->first();
        $product = Product::where(['product_id' => $productId])->first();
        if ($cart == null) {
            return ['status' => 'error', 'message' => '?????? ???????? ???? ?????????? ?????? ?????? ?????? ??????????'];
        }

        if ($cart->count == 1) {
            $cart->delete();
            return [
                'status' => 'success',
                'message' => '???? ???????????? ?????? ????'
            ];
        } else {
            $tax = 0;
            if($product->tax){
                $tax = (int)((($cart->price * ($cart->count - 1)) * ($product->tax->percent))/100);
            }
            $cart->update([
                'count' => ($cart->count - 1),
                'total_price' => ($cart->price * ($cart->count - 1)),
                'tax' => $tax,
            ]);
            return [
                'status' => 'success',
                'message' => '?????????? ?????????? ?????????????????? ????'
            ];
        }

    }

}
