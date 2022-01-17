<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
</head>
<body>
<div style="direction: rtl; max-width: 300px; background: #f7f7f7; margin: auto; border-radius: 10px; border: 1px solid #e0e0e0;">
    <div style="border-bottom: 1px solid #e0e0e0;">
        <h1 style="color: #353535; font-size: 18px; text-align: center; padding: 5px 0;">{{ getOption('site-title') }}</h1>
    </div>
    <div style="padding: 10px;">
        @yield('content')
    </div>
    <div style="border-top: 1px solid #e0e0e0; padding: 5px 0; text-align: center;">
        <a target="_blank" style="font-weight: bold; font-size: 11px; text-decoration: none; margin-left: 3px; color: #0079ee;" href="{{ url('/') }}">{{ siteName() }}</a>
        <a target="_blank" style="font-weight: bold; font-size: 11px; text-decoration: none; margin-left: 3px; color: #0079ee;" href="{{ url('/about') }}">درباره ما</a>
        <a target="_blank" style="font-weight: bold; font-size: 11px; text-decoration: none; color: #0079ee;" href={{ url('/contact-us') }}"">تماس با ما</a>
        <br>
        <span style="display: block; color: #777; font-size: 10px;">کلیه حقوق برای {{ siteName() }} محفوظ است</span>
        <span style="display: block; color: #777; font-size: 10px;">{{ siteAddress() }}</span>
    </div>
</div>
</body>
</html>
