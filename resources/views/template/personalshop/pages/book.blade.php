@extends(includeTemplate('master'))
@section('title', $post->title)
@section('keywords', $tags->implode(', ', 'tag'))
@section('description', $post->excerpt)
@section('ogType', 'product')
@section('ogTitle', $post->title)
@section('ogDescription', $post->excerpt)
@section('ogImage', $post->thumbnail(150, 150))
@section('head-content')
    <meta property="article:published_time" content="{{ str_replace(' ', 'T', $post->published_at) }}+04:30" />
    <meta property="article:author" content="{{ $post->user()->name() }}" />
    <meta property="article:section" content="مقالات" />
    @foreach($post->tags as $tag)
        <meta property="og:tag" content="{{ $tag->tag }}" />
    @endforeach
    @if(view()->exists('admin.seo.singles.product'))
        @include('admin.seo.singles.product')
    @endif
@endsection
@section('content')
    <?php
        $price = $product->price();
        $discount = $product->discount();
        $percent = intval(100 - ($price * 100 / ($price+$discount)));
    ?>
    <div class="d-none">
        @foreach($gallery as $meta)
            <a href="{{ url($meta['value']) }}" data-fancybox="images">
                <img src="url($meta['value'])" />
            </a>
        @endforeach
    </div>
    <div class="product py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">صفحه اصلی</a></li>
                    @foreach($categories as $category)
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ url("categories/{$category->id}/{$category->slug}") }}">{{ $category->title }}</a></li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                </ol>
            </nav>
            <div class="d-flex flex-wrap bg-white rounded p-3 mb-3 border">
                <div class="d-flex flex-column justify-content-between thumbs">
                    @foreach($gallery as $i => $image)
                        @if($i <= 3)
                            @if($i <= 2)
                                <figure onclick="$('[data-fancybox=images]:first').click()" class="border overflow-hidden rounded">
                                    <img class="rounded" src="{{ resizeImage($image->value, 150, 150) }}" alt="{{ $post->title }}">
                                </figure>
                            @else
                                <figure onclick="$('[data-fancybox=images]:first').click()" class="border overflow-hidden rounded d-block d-md-none">
                                    <img class="rounded" src="{{ resizeImage($image->value, 150, 150) }}" alt="{{ $post->title }}">
                                </figure>
                            @endif
                        @endif
                    @endforeach
                </div>
                <div class="pr-3 pl-0 pl-md-3 image">
                    <div class="d-flex justify-content-between mb-2">
                        <i onclick="$('[data-fancybox=images]:first').click()" class="show-gallery fal fa-expand"></i>
                        <span id="product-percent" class="percent {{ $percent == 100 ? 'd-none' : '' }}">{{ $percent }}% تخفیف</span>
                    </div>
                    <figure onclick="$('[data-fancybox=images]:first').click()">
                        <img class="rounded" src="{{ $post->thumbnail(450, 450) }}" alt="{{ $post->title }}">
                    </figure>
                </div>
                <div class="flex-fill pr-0 pr-md-3 mt-3 mt-md-0">
                    <div class="d-flex justify-content-between pb-3 border-bottom">
                        <div class="d-flex">
                            <i class="diamond fa fa-diamond"></i>
                            <div>
                                <h1 class="title">{{ $post->title }}</h1>
{{--                                <h2 class="brand">نام برند : {{ $brand }}</h2>--}}
                            </div>
                        </div>
                        <div class="d-flex icons align-items-center position-relative">
                            @if(auth()->check())
                            <form onSuccess="favorite" method="post" class="ajaxForm" action="{{ route('posts.favorite') }}">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <button id="favorite" class="text-muted {{ $post->isFavorite() ? 'fa' : 'far' }} fa-heart"></button>
                                <script>
                                    function favorite(response) {
                                        if(response.active) {
                                            $('#favorite').addClass('fa').removeClass('far');
                                        } else {
                                            $('#favorite').addClass('far').removeClass('fa');
                                        }
                                    }
                                </script>
                            </form>
                            @endif
                            <i id="share" data-toggle="dropdown" class="text-muted far fa-share-alt mr-2 position-relative"></i>
                            <div class="dropdown-menu single-share-dropdown iransans" aria-labelledby="share">
                                <?php
                                $title = $post->title;
                                $href = $post->href();
                                ?>
                                <a href="{{ shareToTelegram($title, $href) }}" target="_blank" dropdown-item" type="button"><i class="fab fa-telegram ml-2 align-middle"></i>تلگرام</a>
                                <a href="{{ shareToWhatsApp($title, $href) }}" target="_blank" dropdown-item" type="button"><i class="fab fa-whatsapp ml-2 align-middle"></i>واتس‌اپ</a>
                                <a href="{{ shareToEmail($title, $href) }}" target="_blank" dropdown-item" type="button"><i class="fa fa-envelope ml-2 align-middle"></i>ایمیل</a>
                                <a href="{{ shareToTwitter($title, $href) }}" target="_blank" dropdown-item" type="button"><i class="fab fa-twitter ml-2 align-middle"></i>توئیتر</a>
                                <a href="{{ shareToFacebook($title, $href) }}" target="_blank" dropdown-item" type="button"><i class="fab fa-facebook ml-2 align-middle"></i>فیسبوک</a>
                                <a href="{{ shareToPinterest($title, $href) }}" target="_blank" dropdown-item" type="button"><i class="fab fa-pinterest ml-2 align-middle"></i>پینترست</a>
                            </div>
                        </div>
                    </div>
                    <div id="add-to-cart">
                        @include(includeTemplate('pages.product.add'))
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <ul class="digishop-tabs">
                    <li id="t1" class="{{ ($_GET['section'] ?? '' == 'comment') ? '' : 'active' }}">توضیحات</li>
                    <li id="t2">مشخصات</li>
                    <li id="t3" class="{{ ($_GET['section'] ?? '' == 'comment') ? 'active' : '' }}">نظرات</li>
                    <li id="t4">برچسب ها</li>
                </ul>
                <div class="content digishop-contents py-2">
                    <div class="contents text-content t1 {{ ($_GET['section'] ?? '' == 'comment') ? 'd-none' : '' }}">
                        {!! $post->content !!}
                    </div>
                    <div class="contents t2 d-none">
                        @include(includeTemplate('pages.public.attributes'))
                    </div>
                    <div class="contents t3 {{ ($_GET['section'] ?? '' == 'comment') ? '' : 'd-none' }}">
                        @include(includeTemplate('pages.public.comments'))
                    </div>
                    <div class="contents t4 d-none">
                        @foreach($tags as $tag)
                            <a href="{{ url('search?q='.$tag->tag) }}" class="d-inline-block text-muted bg-light border rounded p-2 mb-1">
                                <i class="fa fa-tag align-middle"></i>
                                <span>{{ $tag->tag }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    @include(includeTemplate('divider.1'), ['title' => 'محصولات مشابه'])
    @include(includeTemplate('sections.products'), [
        'posts' => \LaraBase\Posts\Models\Post::published()->postType('products')->categories($post->categories->pluck('id')->toArray())->limit(8)->get()
    ])
@endsection
