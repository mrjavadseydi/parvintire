<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('errors.head')
    <style>
        :root {
            @foreach([
                '--main-color',
                '--main-color-hover',
                '--main-color-light',
                '--second-color',
                '--second-color-hover',
                '--text-color',
                '--mute-color',
            ] as $item)
              {{ $item }}: {{ getOption('digishop'.$item) }};
            @endforeach
        }
    </style>
</head>

<body>

<?php
$personalShop = 1;
$getPersonalShop = getOption('personalShop');
if (!empty($getPersonalShop))
    $personalShop = $getPersonalShop;
?>
@if($templateTheme == 'personalshop')
    @include("template.{$templateTheme}.header".$personalShop)
@else
    @include("template.{$templateTheme}.header")
@endif
<main class="py-3">
    <div class="container-fluid px-6">
        <div class="row">
            <div class="col-md-4">
                <?php
                $menu = getMenu(false, getOption('digishopErrorPagesMenu'));
                ?>
                @if($menu != null)
                    @foreach($menu as $m)
                    <div class="d-flex grid5 bg-light rounded px-3 py-0 mb-1">
                        <figure class="error-flex-sm p-3 d-flex align-items-center">
                            <img src="{{ renderImage($m['image'], 90, 90) }}" alt="{{ $m['title'] }}">
                        </figure>
                        <div class="d-flex rounded flex-column justify-content-between w-100">
                            <div class="d-flex flex-column pt-2">
                                <h3 class="h6">{{ $m['title'] }}</h3>
                                <p class="text-justify text-muted small">{{ strip_tags($m['content']) }}</p>
                            </div>
                            <div class="text-left pb-2">
                                <a href="{{ $m['link'] }}" class="more fal fa-arrow-circle-left"></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            <div class="col-md-8">
                <figure class="text-center position-relative">
                    <img src="{{ image('error.png', 'errors') }}" alt="ارور">
                    <figcaption class="text-center h4 text-muted">@yield('message')</figcaption>
                    <span class="position-absolute error-code">@yield('code')</span>
                </figure>
                <form class="error-search-form" action="{{ route('search') }}">
                    <input placeholder="دنبال چی میگردی؟ اینجا جستجو کن" type="text" name="q">
                    <input type="hidden" name="postType" value="products">
                    <button class="fal fa-search"></button>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ url('/') }}" class="btn btn-outline-warning">بازگشت به صفحه اصلی</a>
                </div>
            </div>
        </div>
    </div>
</main>
@if($templateTheme == 'personalshop')
    @include("template.{$templateTheme}.footer".$personalShop)
@else
    @include("template.{$templateTheme}.footer")
@endif
</body>
</html>
