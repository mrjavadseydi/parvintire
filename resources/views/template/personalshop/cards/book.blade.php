<?php
    $price = $product->price();
    $discount = $product->discount();
?>
<div class="product-card-1 podcast-cart position-relative px-3">
    <figure class="text-center pt-3">
        <a href="{{ $post->href() }}?productId={{ $product->product_id }}">
            <img class="rounded" src="{{ $post->thumbnail(230, 280) }}" alt="{{ $post->title }}">
        </a>
        <a href="{{ $post->href() }}">
            <figcaption class="py-3 iransansMedium">{{ $post->title }}</figcaption>
        </a>
    </figure>
    <div class="d-flex align-items-center py-3 iransansFa justify-content-center">
        <div class="d-flex align-items-center">
            @if($discount > 0)
                <span class="price"><b>{{ number_format($price) }}</b> تومان</span>
                <span class="discount-price small mr-2">{{ number_format($discount + $price) }}</span>
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
