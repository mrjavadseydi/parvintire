<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ favicon() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0085c1">
    <title>@yield('title')</title>

{{--    {{ renderTheme('admin') }}--}}

    <link rel="stylesheet" href="http://localhost:8000/fonts/iransansDN/1/font.min.css">
    <link rel="stylesheet" href="http://localhost:8000/fonts/sahel/3.3.0/font.min.css">
    <link rel="stylesheet" href="http://localhost:8000/fonts/fontawesome/5.11.12/font.min.css">
{{--    <script src="http://localhost:8000/plugins/jquery/3.4.1/jquery.js"></script>--}}
{{--    <script src="http://localhost:8000/plugins/jquery-ui/1.12.1/jquery-ui.min.js"></script>--}}
{{--    <script src="http://localhost:8000/plugins/jquery-form/3.51.0/jquery-form.min.js"></script>--}}
{{--    <script src="http://localhost:8000/plugins/select2/4.0.12/select2.min.js"></script>--}}
{{--    <script src="http://localhost:8000/plugins/template7/1.4.0/template7.min.js"></script>--}}
{{--    <script src="http://localhost:8000/plugins/inputmask/5.0.0/inputmask.min.js"></script>--}}
{{--    <script src="http://localhost:8000/plugins/ckeditor/4/ckeditor.js"></script>--}}
{{--    <script src="http://localhost:8000/plugins/bootstrap/4.4.1/bootstrap.min.js"></script>--}}
{{--    <link rel="stylesheet" href="http://localhost:8000/assets/admin/javadgholipoor/plugins.css">--}}
    <link rel="stylesheet   " href="http://localhost:8000/assets/admin/javadgholipoor/admin.css?v=2.0.0">
{{--    <script src="http://localhost:8000/assets/admin/javadgholipoor/admin.js?v=2.0.0"></script>--}}

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

    <div class="content">
        <router-view></router-view>
    </div>

</div>

{{--@include('admin.javadgholipoor.footer')--}}

<style>
    .fancybox-content {
        height: 98% !important;
    }
</style>

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
</script>
<script src="{{ asset('assets/admin/javadgholipoor/admin.js') }}?v={{ strtotime('now') }}"></script>
<script>

    // $(document).on('click', '.menu', function () {
    //     if ($('.sidebar').hasClass('active')) {
    //         $('.sidebar').removeClass('active');
    //         $('.treeview-ul').slideUp();
    //         $('.treeview').removeClass('active').removeClass('clicked');
    //         $('.content').removeClass('sidebar-open-content');
    //     } else {
    //         $('.sidebar').addClass('active');
    //         $('.content').addClass('sidebar-open-content');
    //     }
    //     $(this).toggleClass('active');
    // });
    //
    // $( ".sidebar" ).hover(
    //     function() {
    //         $(this).addClass('open');
    //         $('.content').addClass('sidebar-open-content');
    //     }, function() {
    //         $(this).removeClass('open');
    //         if (!$(this).hasClass('active')) {
    //             $('.treeview-ul').slideUp();
    //             $('.treeview').removeClass('active').removeClass('clicked');
    //             $('.content').removeClass('sidebar-open-content');
    //         }
    //     }
    // );
    //
    // $(document).on('click', '.treeview > a', function () {
    //
    //     $('.treeview').removeClass('active');
    //     $('.treeview-ul').slideUp();
    //
    //     if (!$(this).parent().hasClass('clicked')) {
    //         $(this).parent().toggleClass('active').addClass('clicked');
    //         $(this).parent().find('.treeview-ul').slideToggle();
    //     } else {
    //         $(this).parent().removeClass('clicked');
    //     }
    //
    // });

</script>
</body>
</html>
