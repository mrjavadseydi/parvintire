<footer>
    <div class="container">
        <div class="row py-3">
            <div class="col-md-3 px-5">
                <h4 class="h6 mb-3">مجوز ها</h4>
                <div class="swiper-container certificate-swiper">
                    <div class="swiper-wrapper">
                        <?php $menu = getMenuById(getOption('digishopFooterCertificate')); ?>
                        @if($menu != null)
                            @foreach($menu as $item)
                                {{-- <a {{ echoAttributes($item['attributes']) }} href="{{ $item['link'] }}" class="swiper-slide">
                                    <figure class="text-center">
                                        <img width="70%" src="{{ url($item['image']) }}" alt="{{ $item['title'] }}">
                                    </figure>
                                </a> --}}
                                {!! $item['content'] !!}
                            @endforeach
                        @endif
                    </div>
                    <div class="swiper-pagination certificate-swiper"></div>
                </div>
                <script>
                    var swiper = new Swiper('.certificate-swiper', {
                        effect: 'flip',
                        grabCursor: true,
                        loop: true,
                        autoplay: {
                            delay: 2000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: '.swiper-pagination',
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                    });
                </script>
            </div>
            <div class="col-md-3 col-6 pl-3 pl-md-5 mt-4 mt-md-0">
                <h4 class="h6">لینک های اصلی</h4>
                <ul>
                    <?php $menu = getMenuById(getOption('digishopFooter1')); ?>
                    @if($menu != null)
                        @foreach($menu as $item)
                            <li>
                                <a href="{{ $item['link'] }}" {{ echoAttributes($item['attributes']) }}>
                                    <i class="fa fa-caret-left"></i>
                                    <span>{{ $item['title'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="col-md-3 col-6 pl-3 pl-md-4 mt-4 mt-md-0">
                <h4 class="h6">بیشترین جستجو ها</h4>
                <?php
                $search = \LaraBase\Posts\Models\Search::where('keyword', '!=', '')->limit(7)->groupBy('keyword')->orderBy('c', 'desc')->selectRaw('*, count(*) as c')->get();
                ?>
                <ul>
                    @foreach($search as $s)
                        <li><a href=""></a></li>
                        <li>
                            <a href="{{ url("search?q={$s->keyword}") }}">
                                <i class="fa fa-caret-left"></i>
                                <span>{{ $s->keyword }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-3">
                <h4 class="h6">عضویت در خبرنامه</h4>
                <form clear="#nl1, #nl2, #nl3" method="post" class="newsletters ajaxForm ajaxForm-iziToast" action="{{ route('addNewsLetters') }}">
                    @csrf
                    <input id="nl1" name="name" placeholder="نام و نام خانوادگی " type="text">
                    <input id="nl2" name="mobile" placeholder="موبایل" type="text">
                    <input id="nl3" name="email" placeholder="ایمیل" type="text">
                    <button>عضویت</button>
                </form>
            </div>
        </div>
    </div>
    <div class="copyright py-2">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    {!! siteCopyright() !!}
                </div>
                <div class="col-md-4 d-flex justify-content-end align-items-center">
                    <ul class="social d-inline-block">
                        <?php $menu = getMenuById(getOption('digishopFooterSocial')); ?>
                        @if($menu != null)
                            @foreach($menu as $item)
                                <li><a  href="{{ $item['link'] }}" {{ echoAttributes($item['attributes']) }} class="{{ $item['icon'] }}"></a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
