<?php
$posts = \LaraBase\Posts\Models\Post::postType('books')->published()->latest()->limit(8)->get();
?>
@if(isset($posts))
    @if(count($posts) > 0)

        <?php
        $swiperKey = generateInt(8);
        ?>

        <style>

            .swiper-{{ $swiperKey }} {
                width: 100%;
                height: 100%;
                margin-bottom: 30px;
                position: relative;
            }

            .swiper-{{ $swiperKey }} .swiper-pagination {
                bottom: 0;
            }

            .swiper-slide {
                text-align: center;
                font-size: 18px;
                /* Center slide text vertically */
                display: -webkit-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                -webkit-justify-content: center;
                justify-content: center;
                -webkit-box-align: center;
                -ms-flex-align: center;
                -webkit-align-items: center;
                align-items: center;
            }

            .swiper-container-horizontal>.swiper-pagination-bullets, .swiper-pagination-custom, .swiper-pagination-fraction-pagination {
                bottom: auto;
            }

        </style>

        <section class="swiper-{{ $swiperKey }} overflow-hidden">
            <div class="swiper-wrapper py-2 px-1">
                <?php
                $postIds = [];
                foreach ($posts as $post) {
                    $postIds[] = $post->id;
                }
                $products = \LaraBase\Store\Models\Product::whereIn('post_id', $postIds)->get();
                ?>
                @foreach($posts as $post)
                    <?php
                    $product = $products->where('post_id', $post->id)->first();
                    ?>
                    <div class="swiper-slide">
                        @include(includeTemplate('cards.book'))
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination" style="bottom: 0"></div>
            <div class="swiper-button-next swiper-button-white"></div>
            <div class="swiper-button-prev swiper-button-white"></div>
        </section>

        <script>
            var swiper = new Swiper('.swiper-{{ $swiperKey }}', {
                breakpoints: {
                    1200: {
                        slidesPerView: 5,
                        slidesPerGroup: 5,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 3,
                        slidesPerGroup: 3,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 2,
                        slidesPerGroup: 2,
                        spaceBetween: 10,
                    },
                    576: {
                        slidesPerView: 2,
                        slidesPerGroup: 2,
                        spaceBetween: 10,
                    },
                    0: {
                        slidesPerView: 2,
                        slidesPerGroup: 2,
                        spaceBetween: 5,
                    }
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                }
            });
        </script>

    @endif
@endif
