<?php
$price = $product->price();
$discount = $product->discount();
$totalPrice = $price + $discount;
$productCount = 0;
if ($cart['carts'] != null) {
    $productCart = $cart['carts']->where('product_id', $product->product_id)->first();
    if ($productCart != null) {
        $productCount = $productCart->count;
    }
}
?>
@csrf
<input type="hidden" name="productId" value="{{ $product->product_id }}">
<input type="hidden" name="view1" value="order.cart-header">
<input type="hidden" name="view2" value="pages.product.add-to-cart">
<div class="d-flex {{ $productCount > 0 ? '' : 'invisible' }}  count">
    <i class="plus-count d-block fal fa-plus"></i>
    <span class="counter" val="{{ $productCount }}">{{ $productCount }} عدد</span>
    <input type="hidden" name="count" value="{{ $productCount }}">
    @if($productCount == 1)
        <i id="{{ $product->product_id }}" class="mines-count d-block fal fa-trash-alt text-danger"></i>
    @else
        <i id="{{ $product->product_id }}" class="mines-count d-block fal fa-minus"></i>
    @endif
</div>
<div>
    <div class="text-center iransansFa">
        @if($discount > 0)
            <span class="discount-price ml-1">{{ number_format($totalPrice) }}</span>
            <span class="price">{{ number_format($price) }} تومان</span>
        @else
            <span class="price">{{ number_format($price) }} تومان</span>
        @endif
    </div>
    <button class="add-to-cart-button">
        <i class="fa fa-cart-plus"></i>
        <span class="d-inline-block">افزودن به سبد خرید</span>
    </button>
</div>
