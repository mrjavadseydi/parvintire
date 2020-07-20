<?php

namespace LaraBase\Store\Models;

use LaraBase\CoreModel;
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

    public function productsCounts()
    {
        return Cart::where('order_id', $this->id)->sum('count');
    }

    public function shippings()
    {
        return OrderShipping::where('order_id', $this->id)->get();
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
            return ['status' => 'error', 'message' => 'شما مجاز به انجام این کار نمی باشید'];

        if ($post->status != 'publish')
            return ['status' => 'error', 'message' => 'شما مجاز به انجام این کار نمی باشید'];

        if ($product->stock == 0)
            return ['status' => 'error', 'message' => 'محصول ناموجود می باشد'];

        $orderId = $this->id;
        $shippingId = $product->shipping_id;

        if ($shippingId == null)
            return ['status' => 'error', 'message' => 'نوع حمل و نقل محصول ثبت نشده است'];

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
        if ($cart == null) {
            return ['status' => 'error', 'message' => 'شما مجاز به انجام این کار نمی باشید'];
        }

        if ($cart->count == 1) {
            $cart->delete();
            return [
                'status' => 'success',
                'message' => 'با موفقیت حذف شد'
            ];
        } else {
            $cart->update(['count' => $cart->count - 1]);
            return [
                'status' => 'success',
                'message' => 'تعداد محصول بروزرسانی شد'
            ];
        }

    }

}
