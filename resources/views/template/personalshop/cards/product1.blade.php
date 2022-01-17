<?php
    if ($product != null) {
        $price = $product->price();
        $discount = $product->discount();
    }
    $href = $post->href();
    $attrs = LaraBase\Posts\Models\PostAttribute::where(['type' => 'post', 'post_id' => $post->id, 'active' => '1'])->whereIn('key_id', [6,7])->get();
    $at = [];
    foreach($attrs as $attr) {
        $v = LaraBase\Attributes\Models\AttributeValue::where('id', $attr->value_id)->first();
        $at[$attr->key_id] = $v == null ? '' : $v->title;
    }
?>
<div class="product-card-1 position-relative px-3">
    <figure class="text-center">
        <a href="{{ $href }}">
            <img src="{{ $post->thumbnail(250, 250) }}" alt="{{ $post->title }}">
        </a>
        <a href="{{ $href }}">
            <figcaption class="py-3 iransansMedium">{{ $post->title }}</figcaption>
        </a>
        <div class="d-flex justify-content-between pt-3">
            <span class="text-muted">{{ $at[6] ?? '' }}</span>
            <span class="text-muted">{{ $at[7] ?? '' }}</span>
        </div>
        @if ($product != null)
        <div class="hover">
            <div class="icons">
                <a href="{{ $href }}" class="fal fa-eye"></a>
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
        @endif
    </figure>
    @if ($product != null)
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
                <?php
                    $percent = intval(100 - ($price * 100) / ($price + $discount));
                ?>
                @if($percent > 0)
                    <div class="percent">
                        <span>{{ intval(100 - ($price * 100) / ($price + $discount)) }} % تخفیف</span>
                    </div>
                @endif
            @endif
        </div>
        @if($discount > 0)
            <span class="special">فروش ویژه</span>
        @endif
    @endif
</div>
