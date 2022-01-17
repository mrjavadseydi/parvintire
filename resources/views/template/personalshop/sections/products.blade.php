<div class="position-relative overflow-hidden tabs-products pt-2">

    <div class="container">
        <div class="products py-3">
            <?php
            $key = md5(microtime());
            $products = \LaraBase\Store\Models\Product::whereIn('post_id', $posts->pluck('id')->toArray())->get();
            ?>
            <div class="swiper-container swiper-{{ $key }}">
                <div class="swiper-wrapper">
                    @foreach($posts as $post)
                        <?php
                            $product = $products->where('post_id', $post->id)->first();
                        ?>
                        <div class="swiper-slide">
                            @include(includeTemplate('cards.product1'))
                        </div>
                    @endforeach
                </div>
            </div>

            <script>
                var swiper = new Swiper('.swiper-{{ $key }}', {
                    slidesPerView: 4,
                    spaceBetween: 1,
                    loop: false,
                    breakpoints: {
                        300: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 40,
                        },
                        1024: {
                            slidesPerView: 4,
                            spaceBetween: 50,
                        },
                    }
                });
            </script>
        </div>
    </div>

    @include(includeTemplate('graphics.right-dots'))
    @include(includeTemplate('graphics.left-dots'))

</div>
