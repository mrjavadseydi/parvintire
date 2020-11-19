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
    @if(view()->exists('admin.seo.singles.article'))
        @include('admin.seo.singles.article')
    @endif
@endsection
@section('content')
    <div class="container article mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">صفحه اصلی</a></li>
                @foreach($categories as $category)
                    <li class="breadcrumb-item" aria-current="page"><a href="{{ url("categories/{$category->id}/{$category->slug}") }}">{{ $category->title }}</a></li>
                @endforeach
                <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
            </ol>
        </nav>
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {!! session()->get('error') !!}
            </div>
        @endif
        @if($transaction != null)
            <div class="alert alert-success">
                این محصول با موفقیت برای شما فعال شده است
            </div>
        @endif
        <div class="bg-white border rounded p-3 d-flex flex-wrap flex-md-nowrap">
            <img width="25%" height="100%" class="w-100-sm mb-3 mb-md-0" src="{{ $post->thumbnail(350, 350) }}" alt="{{ $post->title }}">
            <div class="pr-0 pr-md-3 flex-fill">
                <div class="d-flex flex-wrap flex-md-nowrap justify-content-between align-items-center">
                    <div>
                        <i class="text-second-color fa fa-diamond d-none d-md-inline-block"></i>
                        <h1 class="d-inline-block h5">{{ $post->title }}</h1>
                        <br>
                        <span class="text-muted">{{ jDateTime('d F Y', strtotime($post->created_at)) }}</span>
                    </div>
                    <div class="flex-fill">
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="position-relative d-inline-block">
                                <i id="share" data-toggle="dropdown" class="share text-muted far fa-share-alt mr-2 position-relative"></i>
                                <div class="article-share dropdown-menu single-share-dropdown iransans" aria-labelledby="share">
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
                            <?php $rate = $post->rateByLikes() ;?>
                            <div class="rate mr-3">
                                <i class="fa fa-star {{ $rate == 5 ? 'active' : '' }}"></i>
                                <i class="fa fa-star {{ $rate >= 4 ? 'active' : '' }}"></i>
                                <i class="fa fa-star {{ $rate >= 3 ? 'active' : '' }}"></i>
                                <i class="fa fa-star {{ $rate >= 2 ? 'active' : '' }}"></i>
                                <i class="fa fa-star {{ $rate >= 1 ? 'active' : '' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="my-3 text-content">
                    {!! $post->content !!}
                </p>
            </div>
        </div>

        @if($product->price > 0)
            @if($transaction == null)
                <div class="bg-white border rounded p-3 mt-3 text-center">
                    <h5 class="text-center">این محصول نقدی می باشد. برای فعال سازی تمام فایل ها لطفا مبلغ آن را پرداخت کنید.</h5>
                    <h4 class="text-center iransansFa py-3">مبلغ قابل پرداخت:
                        @if($product->discount() > 0)
                            <del class="text-danger">{{ number_format($product->discount() + $product->price()) }}</del>
                        @endif
                        <b class="text-success">{{ number_format($product->price()) }} تومان</b>
                    </h4>
                    <span class="btn btn-success pointer payment-course-button">پرداخت آنلاین</span>
                </div>
            @endif
        @endif
        <div class="row">
            <div class="col-md-12">
                @include(includeTemplate('sections.downloadBox'), ['title' => 'فایل ها'])
            </div>
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h6>امتیاز شما به این محصول</h6>
                    <div class="likes d-flex align-items-center">
                        <form onSuccess="onLike" method="post" action="{{ route('posts.like') }}" class="ajaxForm" action="{{ route('posts.like') }}">
                            @csrf
                            <input type="hidden" name="type" value="1">
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <button class="like-icon fa fa-thumbs-up like {{ $post->isLike('active') }}"></button>
                            <small class="like-count iransansFa">{{ $post->likes() }}</small>
                        </form>
                        <form onSuccess="onDisLike" method="post" action="{{ route('posts.like') }}" class="ajaxForm mr-1">
                            @csrf
                            <input type="hidden" name="type" value="0">
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <button class="dislike-icon fa fa-thumbs-down dislike {{ $post->isDislike('active') }}"></button>
                            <small class="dislike-count iransansFa">{{ $post->disLikes() }}</small>
                        </form>
                        <script>
                            function onLike(response) {
                                if(response.status == 'success') {
                                    if(response.active) {
                                        $('.like-icon').addClass('active');
                                    } else {
                                        $('.like-icon').removeClass('active');
                                    }
                                    $('.like-count').text(response.count);
                                }
                            }
                            function onDisLike(response) {
                                if(response.active) {
                                    $('.dislike-icon').addClass('active');
                                } else {
                                    $('.dislike-icon').removeClass('active');
                                }
                                $('.dislike-count').text(response.count);
                            }
                        </script>
                    </div>
                </div>
                <div class="tags mt-1">
                    <h6 class="d-inline-block">برچسب ها:</h6>
                    @foreach($tags as $tag)
                        <a rel="nofollow" href="{{ url("search?q={$tag->tag}") }}" class="mr-2">#{{ $tag->tag }}</a>
                    @endforeach
                </div>
                <div class="mt-3 border p-3 rounded">
                    @include(includeTemplate('pages.public.comments'))
                </div>
            </div>
        </div>
    </div>
@endsection
