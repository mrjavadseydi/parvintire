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
