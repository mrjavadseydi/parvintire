<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ favicon() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0085c1">
    <title></title>
    <link rel="stylesheet" href="{{ asset('fonts/iransansDN/1/font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome/5.11.12/font.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/admin/javadgholipoor/admin.css?v=2.0.0') }}">
    @yield('head-content')
    {{ googleAnalytics() }}
</head>
<body class="rtl sidebar-hide @yield('body-class')">
<?php
$theme = doAction('theme');
$user = auth()->user();
?>
<div id="app">
    <my-header></my-header>
    <my-sidebar></my-sidebar>
    <div id="content" class="content">
        <router-view></router-view>
    </div>
</div>
<script>
    window.Laravel = {};
    window.Laravel.Auth = '{{ auth()->check() }}' == '' ? false : true;
    window.Laravel.csrfToken = '{{ csrf_token() }}';
    window.Laravel.url = '{{ url('/') }}';
    window.Laravel.logout = '{{ route('logout') }}';
    window.Laravel.wallet = '{{ number_format(getWalletCredit()) }}'
    window.Laravel.user = {};
    window.Laravel.user.name = '{{ $user->name() }}';
    window.Laravel.user.avatar = '{{ $user->avatar() }}';
    window.siebar = [];
    window.templateTheme = '{{ $templateTheme }}';
</script>
<script src="{{ asset('assets/admin/javadgholipoor/admin.js') }}?v={{ strtotime('now') }}"></script>
</body>
</html>
