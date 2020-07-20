<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ getOptionImage('favicon', 16, 16)['src'] }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    {{ renderTheme('admin') }}

    @yield('head-content')

    {{ googleAnalytics() }}

</head>
<body class="rtl @yield('body-class')">

<div class="header-sidebar">
    @include('admin.default.header')
    @include("admin.{$adminTheme}.sidebar")
</div>

<div class="content">
    @if(env('APP_DEBUG'))
        @if(isDev())
            <div class="alert alert-danger">
                <p>دیباگ وب سایت روشن است.</p>
            </div>
        @endif
    @endif
    @include('errors.default.messages')
    @yield('content', 'default')
</div>

@include('admin.default.footer')
@yield('footer-content')

<style>
    .fancybox-content {
        height: 98% !important;
    }
</style>

<span id="goTop" class="icon-arrow-up2"></span>

</body>
</html>
