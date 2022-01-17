<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="keywords" content="@yield('keywords')">
<meta name="description" content="@yield('description')">
<meta name="robots" content="@yield('robots', 'index, follow')"/>
<link rel="canonical" href="{{ $canonical ?? url()->current() }}"/>
<link rel="icon" href="{{ favicon() }}">
<meta property="og:type" content="@yield('ogType')" />
<meta property="og:url" content="{{ url()->full() }}" />
<meta property="og:site_name" content="{{ getOption('site-title') }}" />
<meta property="og:brand" content="{{ getOption('site-brand') }}" />
<meta property="og:locale" content="{{ app()->getLocale() }}" />
<meta property="og:title" content="@yield('ogTitle')" />
<meta property="og:description" content="@yield('ogDescription')" />
<meta property="og:image" content="@yield('ogImage')" />
<meta property="og:image:width" content="@yield('ogImageWidth', 150)" />
<meta property="og:image:height" content="@yield('ogImageHeight', 150)" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="theme-color" content="@yield('color', getOption('template-theme-color') ?? '#039BE5')">

<title>@yield('title')</title>

{{ renderTheme('template') }}

@yield('head-content')

{!! googleAnalytics() !!}
