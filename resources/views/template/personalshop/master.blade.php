<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('template.head')
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
    <div class="d-block d-lg-none">
        <figure class="p-5 pb-1">
            <img width="100%" src="https://tarahesite.net/wp-content/uploads/2019/07/xp05ju6yjb4wkcsw00.png" alt="res">
        </figure>
        <h5 class="text-center">ساختار رسپانسیو در دست طراحی می باشد</h5>
    </div>
    <div class="d-none d-lg-block">
        @include("template.{$templateTheme}.header")
        <main>
            @yield('content')
        </main>
        @include("template.{$templateTheme}.footer")
    </div>
</body>
</html>
