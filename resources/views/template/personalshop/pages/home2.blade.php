<div class="container-fluid py-3">
    <div class="container">
        <div class="row py-5">
            <div class="col-md-5">
                <div class="container">
                    <div class="site-description home-2 pt-5">
                        <h1 class="h3">{{ getOption('personal-title-1') }}</h1>
                        <p>{!! getOption('personal-description-1') !!}</p>
                        <form action="{{ route('search') }}">
                            <input type="hidden" name="postType" value="files">
                            <input type="text" name="q" placeholder="دنبال چی میگردی؟">
                            <button class="fal fa-search"></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-7 mt-5 mt-md-0">
                <div class="slider">
                    @include(includeTemplate('sections.slider2'), ['records' => $homeSlider])
                </div>
            </div>
        </div>
    </div>
    <div class="position-relative">
        @include(includeTemplate('graphics.right-circle'))
    </div>
    <?php
    $menu = getMenu(false, getOption('digishopConsultingMenuId'));
    ?>
    @if(isset($menu[0]))
        <?php $m = (object)$menu[0]; ?>
        <div class="position-relative py-5">
            <img style="position: absolute; right: 0; top: -200px;" width="25%" src="{{ image('multi-circle.png', 'template') }}" alt="multi circle">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 order-2 order-md-1">
                        <div class="site-description home-2 pt-5">
                            <h2 class="h3 mb-3 consulting-title">{{ $m->title }}</h2>
                            <div class="consulting-content">{!! $m->content !!}</div>
                            <div class="text-left mt-3">
                                <a href="{{ $m->link }}" class="consulting-link btn btn-second py-3 px-4 h4">ادامه مطلب</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 order-1 order-md-2">
                        <div class="consulting d-flex justify-content-between">
                            <div class="d-flex flex-column ml-3 ml-md-0 mt-5">
                                <a class="active" link="{{ $m->link }}">
                                    <figure class="d-flex flex-column justify-content-center h-100 align-items-center text-center">
                                        <img src="{{ $m->image }}" alt="{{ $m->title }}">
                                        <figcaption class="text-center">{{ $m->title }}</figcaption>
                                    </figure>
                                    <div class="this-content d-none">{!! $m->content !!}</div>
                                </a>
                                @if(isset($menu[1]))
                                    <?php $m = (object)$menu[1]; ?>
                                    <a link="{{ $m->link }}">
                                        <figure class="d-flex flex-column justify-content-center h-100 align-items-center text-center">
                                            <img src="{{ $m->image }}" alt="{{ $m->title }}">
                                            <figcaption class="text-center">{{ $m->title }}</figcaption>
                                        </figure>
                                        <div class="this-content d-none">{!! $m->content !!}</div>
                                    </a>
                                @endif
                            </div>
                            <div class="d-flex flex-column">
                                @if(isset($menu[2]))
                                    <?php $m = (object)$menu[2]; ?>
                                    <a link="{{ $m->link }}">
                                        <figure class="d-flex flex-column justify-content-center h-100 align-items-center text-center">
                                            <img src="{{ $m->image }}" alt="{{ $m->title }}">
                                            <figcaption class="text-center">{{ $m->title }}</figcaption>
                                        </figure>
                                        <div class="this-content d-none">{!! $m->content !!}</div>
                                    </a>
                                @endif
                                @if(isset($menu[3]))
                                    <?php $m = (object)$menu[3]; ?>
                                    <a link="{{ $m->link }}">
                                        <figure class="d-flex flex-column justify-content-center h-100 align-items-center text-center">
                                            <img src="{{ $m->image }}" alt="{{ $m->title }}">
                                            <figcaption class="text-center">{{ $m->title }}</figcaption>
                                        </figure>
                                        <div class="this-content d-none">{!! $m->content !!}</div>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <script>
                            timeout = null;
                            $(document).on('mouseenter', '.consulting a', function () {
                                var el = $(this);
                                timeout = setTimeout(function () {
                                    $('.consulting a').removeClass('active')
                                    el.addClass('active');
                                    $('.consulting-link').attr('href', el.attr('link'));
                                    $('.consulting-title').text(el.find('figcaption').text());
                                    $('.consulting-content').html(el.find('.this-content').html());
                                }, 200);
                            });
                            $(document).on('mouseleave', '.consulting a', function () {
                                clearTimeout(timeout)
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="py-5">
        @include(includeTemplate('divider.1'), ['title' => 'جدیدترین محصولات'])
        <div class="row">
            @include(includeTemplate('sections.tabs-products-latest'), ['cats' => getOption('digishopHomeLatestProducts')])
        </div>
    </div>
</div>
@include(includeTemplate('sections.parallax'), ['key' => 'digishopHomeParallax'])
<div class="container py-5">
    @include(includeTemplate('divider.1'), ['title' => 'محصولات دانلودی'])
    <div class="row py-1">
        @include(includeTemplate('sections.tabs-files-latest'), ['cats' => getOption('digishopHomeLatestFiles')])
    </div>
</div>
@include(includeTemplate('sections.resume'), ['menuId' => getOption('digishopResumeMenuId')])
<div class="py-2">
    @include(includeTemplate('divider.1'), ['title' => 'پادکست ها'])
</div>
<div class="container">
    @include(includeTemplate('sections.swiper-podcast'))
</div>
<?php
$img = getOptionImage('digishopHomeImageLink');
?>
<div class="my-3">
    <a target="{{ $img['target'] }}" class="{{ $img['href'] }} my-3">
        <img width="100%" src="{{ $img['src'] }}" alt="{{ $img['alt'] }}">
    </a>
</div>
<div class="py-2">
    @include(includeTemplate('divider.1'), ['title' => 'مقالات سایت'])
</div>
<?php
$articles = \LaraBase\Posts\Models\Post::postType('articles')->published()->orderBy('updated_at', 'desc')->limit(4)->get();
?>
@if($articles->count() > 0)
    <div class="container">
        <div class="articles">
            <div class="row">
                <div class="col-md-5">
                    <a href="{{ $articles[0]->href() }}" class="rounded-lg overflow-hidden d-block">
                        <div class="d-flex flex-column">
                            <figure>
                                <img src="{{ $articles[0]->thumbnail(550, 350) }}" width="100%" alt="{{ $articles[0]->title }}">
                                <figcaption class="py-2"><h4 class="text-black">{{ $articles[0]->title }}</h4></figcaption>
                                <p class="text-justify text-muted">{{ $articles[0]->excerpt }}</p>
                            </figure>
                            <div class="border-top d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <h5 class="text-success px-2">{{ jDateTime('d', strtotime($articles[0]->created_at)) }}</h5>
                                    <div class="border-right px-2 d-flex flex-column justify-content-center">
                                        <span class="text-muted">{{ jDateTime('F', strtotime($articles[0]->created_at)) }}</span>
                                        <span class="text-muted">{{ jDateTime('Y', strtotime($articles[0]->created_at)) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <?php
                                        $rate = $articles[0]->rateByLikes();
                                    ?>
                                    <i class="fa fa-star text-muted {{ $rate >= 5 ? 'text-gold' : '' }}"></i>
                                    <i class="fa fa-star text-muted {{ $rate >= 4 ? 'text-gold' : '' }}"></i>
                                    <i class="fa fa-star text-muted {{ $rate >= 3 ? 'text-gold' : '' }}"></i>
                                    <i class="fa fa-star text-muted {{ $rate >= 2 ? 'text-gold' : '' }}"></i>
                                    <i class="fa fa-star text-muted {{ $rate >= 1 ? 'text-gold' : '' }}"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-7">
                    <div class=" d-flex flex-column">
                        @foreach($articles as $i => $article)
                            @if($i > 0)
                                <a href="{{ $article->href() }}" class="d-flex rounded-lg overflow-hidden mb-3 align-items-center">
                                    <img width="25%" src="{{ $article->thumbnail(200, 150) }}" alt="{{ $article->title }}">
                                    <div class="d-flex flex-column flex-fill pr-3 justify-content-center">
                                        <h4 class="text-black">{{ $article->title }}</h4>
                                        <p class="text-muted my-3">{{ $article->excerpt }}</p>
                                        <div class="border-top d-flex justify-content-between align-items-center py-3">
                                            <div class="d-flex align-items-center">
                                                <h5 class="text-success px-2">{{ jDateTime('d', strtotime($article->created_at)) }}</h5>
                                                <div class="border-right px-2 d-flex flex-column justify-content-center">
                                                    <span class="text-muted">{{ jDateTime('F', strtotime($article->created_at)) }}</span>
                                                    <span class="text-muted">{{ jDateTime('Y', strtotime($article->created_at)) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <?php
                                                $rate = $article->rateByLikes();
                                                ?>
                                                <i class="fa fa-star text-muted {{ $rate >= 5 ? 'text-gold' : '' }}"></i>
                                                <i class="fa fa-star text-muted {{ $rate >= 4 ? 'text-gold' : '' }}"></i>
                                                <i class="fa fa-star text-muted {{ $rate >= 3 ? 'text-gold' : '' }}"></i>
                                                <i class="fa fa-star text-muted {{ $rate >= 2 ? 'text-gold' : '' }}"></i>
                                                <i class="fa fa-star text-muted {{ $rate >= 1 ? 'text-gold' : '' }}"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
