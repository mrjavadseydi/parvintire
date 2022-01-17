<?php
    $key = md5(microtime());
    $posts = \LaraBase\Posts\Models\Post::whereIn('id', explode(',', $ids))->get();
    $products = \LaraBase\Store\Models\Product::whereIn('post_id', explode(',', $ids))->get();
    //$products = \LaraBase\Store\Models\Product::whereIn('product_id', explode(',', $ids))->get();
    //$posts = \LaraBase\Posts\Models\Post::whereIn('id', $products->pluck('post_id')->toArray())->get();
?>
<div class="offer-{{ $key }} container-fluid p-5 px-6 special-offers position-relative">
    <figure class="bg text-center pt-5 position-absolute">
        <img src="{{ image('bg-special-offers.png', 'template') }}" alt="پیشنهادات ویژه">
    </figure>
    <div class="container d-flex flex-wrap flex-md-nowrap">
        <div class="right order-1 order-md-1 px-3 mb-3 mb-md-0">
            <h5 class="pb-3">پیشنهادات ویژه</h5>
            <p class="text-justify">{{ $description ?? '' }}</p>
            <hr>
            <div class="timer">
                <span><i class="fa fa-history ml-2 align-middle"></i>زمان باقی مانده</span>

                @foreach($products as $i => $product)
                    <?php
                        $timestamp = strtotime($product->end_date) - strtotime('now');
                    ?>
                    <div time="{{ $timestamp }}" class="count-down-{{ $i }} {{ $i == 0 ? 'd-flex' : 'd-none' }} offer-{{ $i }} special-offer justify-content-end pt-3">
                        <div class="time d-flex flex-column">
                            <div class="top sec">0</div>
                            <div class="bottom">ثانیه</div>
                        </div>
                        <div class="time d-flex flex-column">
                            <div class="top min">0</div>
                            <div class="bottom">دقیقه</div>
                        </div>
                        <div class="time d-flex flex-column">
                            <div class="top hour">0</div>
                            <div class="bottom">ساعت</div>
                        </div>
                        <div class="time d-flex flex-column">
                            <div class="top day">0</div>
                            <div class="bottom">روز</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @foreach($products as $i => $product)
            <?php
                //dd($product);
                $post = $posts->where('id', $product->post_id)->first();
                $price = $product->price();
                $discount = $product->discount();
                $rate = $post->rateByLikes();
            ?>
            <div class="flex-fill center order-3 order-md-2 {{ $i == 0 ? 'd-flex' : 'd-none' }} offer-{{ $i }} special-offer position-relative align-items-center bg-white py-4 pl-3 ml-0 ml-md-4">
                <div class="w-right w-50 px-3">
                    <figure class="text-center">
                        <a href="{{ $post->href() }}?product={{ $product->product_id }}">
                            <img src="{{ $post->thumbnail(380, 300) }}" alt="{{ $product->title }}">
                        </a>
                    </figure>
                </div>
                <div class="w-left w-50">
                    <div class="text-left">
                        <span class="percent">{{ intval(100 - ($price * 100) / ($price+$discount)) }}% تخفیف</span>
                    </div>
                    <a href="{{ $post->href() }}?product={{ $product->product_id }}">
                        <h2>{{ $product->title }}</h2>
                    </a>
                    <div class="rate py-3">
                        <i class="fa fa-star {{ $rate == 5 ? 'active' : '' }}"></i>
                        <i class="fa fa-star {{ $rate >= 4 ? 'active' : '' }}"></i>
                        <i class="fa fa-star {{ $rate >= 3 ? 'active' : '' }}"></i>
                        <i class="fa fa-star {{ $rate >= 2 ? 'active' : '' }}"></i>
                        <i class="fa fa-star {{ $rate >= 1 ? 'active' : '' }}"></i>
                    </div>
                    <p>{{ $post->excerpt }}</p>
                    <div class="d-flex justify-content-center py-3">
                        <span class="price ml-3">{{ number_format($price) }} تومان</span>
                        <span class="discount-price">{{ number_format($price + $discount) }} تومان</span>
                    </div>
                    <div class="d-flex justify-content-center">
                        <form class="ml-3 addToCart" method="post" action="{{ route('addToCart') }}">
                            @csrf
                            <input type="hidden" name="productId" value="{{ $product->product_id }}">
                            <input type="hidden" name="view1" value="order.cart-header">
                            <button><i class="fal fa-cart-plus ml-2 align-middle"></i>افزودن به سبد خرید</button>
                        </form>
                        <form onSuccess="specialFavorite" method="post" class="ajaxForm" action="{{ route('posts.favorite') }}">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <button class="specialAddToFavorite"><i class="{{ $post->isFavorite() ? 'fa' : 'fal' }} fa-heart ml-2 align-middle"></i>افزودن به علاقه ها</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="left order-2 order-md-3 d-flex flex-md-column justify-content-around justify-content-md-between">
            @foreach($products as $i => $product)
                <?php
                $post = $posts->where('id', $product->post_id)->first();
                ?>
                <figure id="offer-{{ $i }}" class="offer-select {{ $i == 0 ? 'active' : '' }} mb-3">
                    <img src="{{ $post->thumbnail(100, 100) }}" alt="{{ $post->title }}">
                </figure>
            @endforeach
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.offer-{{ $key }} .offer-select', function () {
        $('.offer-{{ $key }} .offer-select').removeClass('active');
        $(this).addClass('active');
        $('.offer-{{ $key }} .special-offer').addClass('d-none').removeClass('d-flex');
        $('.offer-{{ $key }} .' + $(this).attr('id')).removeClass('d-none').addClass('d-flex');
    });
    time0 = 0;
    time1 = 0;
    time2 = 0;
    setInterval(function () {
        if(time0 == 0) {
            time0 = $('.offer-{{ $key }} .count-down-0').attr('time');
            $('.offer-{{ $key }} .count-down-0').attr('time', 0);
        }
        if (time0 >= 0) {
            $('.offer-{{ $key }} .count-down-0').find('.sec').text(time0%60);
            $('.offer-{{ $key }} .count-down-0').find('.min').text(Math.floor(time0/60)%60);
            $('.offer-{{ $key }} .count-down-0').find('.hour').text(Math.floor(Math.floor(time0/60)/60)%60);
            $('.offer-{{ $key }} .count-down-0').find('.day').text(Math.floor(Math.floor(Math.floor(time0/60)/60)/24)%24);
            time0--;
        }

        if(time1 == 0) {
            time1 = $('.offer-{{ $key }} .count-down-1').attr('time');
            $('.offer-{{ $key }} .count-down-1').attr('time', 0);
        }
        if(time1 >= 0) {
            $('.offer-{{ $key }} .count-down-1').find('.sec').text(time1%60);
            $('.offer-{{ $key }} .count-down-1').find('.min').text(Math.floor(time1/60)%60);
            $('.offer-{{ $key }} .count-down-1').find('.hour').text(Math.floor(Math.floor(time1/60)/60)%60);
            $('.offer-{{ $key }} .count-down-1').find('.day').text(Math.floor(Math.floor(Math.floor(time1/60)/60)/24)%24);
            time1--;
        }

        if(time2 == 0) {
            time2 = $('.offer-{{ $key }} .count-down-2').attr('time');
            $('.offer-{{ $key }} .count-down-2').attr('time', 0);
        }
        if(time2 >= 0) {
            $('.offer-{{ $key }} .count-down-2').find('.sec').text(time2%60);
            $('.offer-{{ $key }} .count-down-2').find('.min').text(Math.floor(time2/60)%60);
            $('.offer-{{ $key }} .count-down-2').find('.hour').text(Math.floor(Math.floor(time2/60)/60)%60);
            $('.offer-{{ $key }} .count-down-2').find('.day').text(Math.floor(Math.floor(Math.floor(time2/60)/60)/24)%24);
            time2--;
        }

    }, 1000);
    addToFavoriteEl = null;
    $(document).on('click', '.specialAddToFavorite', function () {
        addToFavoriteEl = $(this).find('i');
        @if(!auth()->check())
            window.location = ('{{ route('login') }}')
        @endif
    });
    function specialFavorite(data) {
        if(data.status == 'success') {
            if(data.active) {
                iziToast.success({
                    message: 'با موفقیت به علاقه مندی اضافه شد'
                });
                addToFavoriteEl.addClass('fa').removeClass('fal');
            } else {
                iziToast.success({
                    message: 'با موفقیت از علاقه مندی حذف شد'
                });
                addToFavoriteEl.addClass('fal').removeClass('fa');
            }
        }
    }
</script>
