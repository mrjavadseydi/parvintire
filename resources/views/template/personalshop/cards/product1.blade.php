<?php
    $price = $product->price();
    $discount = $product->discount();
?>
<div class="product-card-1 position-relative px-3">
    <figure class="text-center">
        <a href="{{ $post->href() }}?productId={{ $product->product_id }}">
            <img src="{{ $post->thumbnail(250, 250) }}" alt="{{ $post->title }}">
        </a>
        <a href="{{ $post->href() }}?productId={{ $product->product_id }}">
            <figcaption class="py-3 iransansMedium">{{ $post->title }}</figcaption>
        </a>
        <div class="hover">
            <div class="icons">
                <a href="{{ $post->href() }}?productId={{ $product->product_id }}" class="fal fa-eye"></a>
{{--                <i class="fal fa-share-alt"></i>--}}
{{--                <i class="fal fa-heart"></i>--}}
            </div>
            <form action="{{ route('addToCart') }}" class="addToCart" method="post">
                @csrf
                <input type="hidden" name="productId" value="{{ $product->product_id }}">
                <input type="hidden" name="view1" value="order.cart-header">
                <button>
                    <i class="fal fa-cart-plus"></i>
                    <span>افزودن به سبد خرید</span>
                </button>
            </form>
        </div>
    </figure>
    <div class="d-flex justify-content-between align-items-center py-3 iransansFa">
        <div class="d-flex flex-column">
            @if($discount > 0)
                <span class="price"><b>{{ number_format($price) }}</b> تومان</span>
                <span class="discount-price small">{{ number_format($discount + $price) }}</span>
            @else
                <span class="price"><b>{{ number_format($price) }}</b> تومان</span>
            @endif
        </div>
        @if($discount > 0)
            <div class="percent">
                <span>{{ intval(($price * 100) / ($price + $discount)) }} % تخفیف</span>
            </div>
        @endif
    </div>
    @if($discount > 0)
        <span class="special">فروش ویژه</span>
    @endif
</div>
