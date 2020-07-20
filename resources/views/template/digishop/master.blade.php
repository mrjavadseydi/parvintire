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

    @include("template.{$templateTheme}.header")

    <main>
        @yield('content')
    </main>

    @include("template.{$templateTheme}.footer")


</body>
</html>
