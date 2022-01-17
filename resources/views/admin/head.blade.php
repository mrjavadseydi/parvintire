<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="keywords" content="@yield('keywords')">
<meta name="description" content="@yield('description')">
<link rel="icon" href="{{ favicon() }}">
<link rel="canonical" href="{{ $canonical ?? url()->current() }}"/>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="theme-color" content="@yield('color', getOption('admin-theme-color') ?? '#039BE5')">

<title>@yield('title')</title>

{{ renderTheme('template') }}

@yield('head-content')

{!! googleAnalytics() !!}
