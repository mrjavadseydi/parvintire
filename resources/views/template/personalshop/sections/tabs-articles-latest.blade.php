<div class="container">
    <?php
    $key = md5(microtime());
    $categories = \LaraBase\Categories\Models\Category::whereIn('id', explode(',', $cats ?? ''))->with([
        'posts' => function($query) {
            return $query->latest()->published()->limit(8);
        }
    ])->get();
    $data = [];
    foreach ($categories as $category) {
        foreach ($category->posts as $post) {
            if (isset($data[$post->id])) {
                $data[$post->id]['categories'][] = $category->id;
            } else {
                $data[$post->id] = [
                    'post' => $post,
                    'categories' => [$category->id]
                ];
            }
        }
    }
    ?>
    <div class="key-{{ $key }} position-relative overflow-hidden tabs-products pt-2">

        <div class="text-center py-3">
            <ul class="tab-products-click d-inline-block">
                <li id="all" class="active">همه</li>
                @foreach($categories as $cat)
                    @if($cat->posts->count() > 0)
                        <li id="tab-products-{{ $cat->id }}">{{ $cat->title }}</li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="container-fluid px-6">
            <div class="products py-3">
                <div class="swiper-container p-1">
                    <div class="swiper-wrapper">
                        @foreach($data as $item)
                            <div class="swiper-slide tab-products-{{ implode(' tab-products-', $item['categories']) }}">
                                @include(includeTemplate('cards.blog1'), ['post' => $item['post']])
                            </div>
                        @endforeach
                    </div>
                </div>
                <script>
                    var swiper = new Swiper('.key-{{ $key }} .swiper-container', {
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
                                slidesPerView: 3,
                                spaceBetween: 50,
                            },
                        }
                    });
                    $(document).on('click', '.key-{{ $key }} ul li', function () {
                        var id = $(this).attr('id');
                        var parent = $(this).closest('.key-{{ $key }}');
                        parent.find('.swiper-slide').addClass('d-none');
                        parent.find('ul li').removeClass('active');
                        $('.'+id).removeClass('d-none');
                        $(this).addClass('active');
                        if(id == 'all') {
                            parent.find('.swiper-slide').removeClass('d-none');
                        }
                    });
                </script>
            </div>
        </div>
        @include(includeTemplate('graphics.right-dots'))
        @include(includeTemplate('graphics.left-dots'))
    </div>
</div>
