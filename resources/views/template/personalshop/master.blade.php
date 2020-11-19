<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>
    <meta name="keywords" content="@yield('keywords')">
    <meta name="description" content="@yield('description')">
    <meta name="robots" content="@yield('robots', 'index, follow')"/>
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}"/>
    <meta property="og:locale" content="{{ app()->getLocale() }}" />
    <meta property="og:type" content="@yield('ogType')" />
    <meta property="og:title" content="@yield('title')" />
    <meta property="og:description" content="@yield('description')" />
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}" />
    <meta property="og:brand" content="{{ getOption('site-brand') }}" />
    <meta property="og:image" content="@yield('ogImage')" />
    <meta property="og:image:width" content="@yield('ogImageWidth', 150)" />
    <meta property="og:image:height" content="@yield('ogImageHeight', 150)" />
    <meta name="theme-color" content="@yield('color', getOption('template-theme-color') ?? '#039BE5')">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="@yield('description')" />
    <meta name="twitter:title" content="@yield('title')" />
    <meta name="twitter:image" content="@yield('ogImage')" />
    <link rel="icon" href="{{ favicon() }}">

    {{ renderTheme('template') }}

    @yield('head-content')

    {!! googleAnalytics() !!}

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
<body class="@yield('header', 'header')">
    <?php
        $personalShop = 1;
        $getPersonalShop = getOption('personalShop');
        if (!empty($getPersonalShop))
            $personalShop = $getPersonalShop;
    ?>
    @include(includeTemplate('header'.$personalShop))
    <main>
        @yield('content')
    </main>
    @include(includeTemplate('footer'.$personalShop))
</body>
</html>
