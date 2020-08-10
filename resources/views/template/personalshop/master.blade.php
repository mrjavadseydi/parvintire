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
    @include(includeTemplate('header'))
    <main>
        @yield('content')
    </main>
    @include(includeTemplate('footer'))
</body>
</html>
