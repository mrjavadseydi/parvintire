<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="@yield('keywords', siteKeywords())">
    <meta name="description" content="@yield('description', siteDescription())">
    <link rel="icon" href="{{ favicon() }}">
    <link rel="canonical" href="@yield('canonical')"/>
    <meta property="og:locale" content="fa_IR" />
    <meta property="og:type" content="@yield('ogType')" />
    <meta property="og:title" content="@yield('ogTitle')" />
    <meta property="og:description" content="@yield('ogDescription')" />
    <meta property="og:site_name" content="@yield('ogSiteName')" />
    <meta property="og:image" content="@yield('ogImage')" />
    <meta property="og:image:width" content="@yield('ogImageWidth')" />
    <meta property="og:image:height" content="@yield('ogImageHeight')" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title') | {{ siteName() }}</title>

    {{ renderTheme('auth') }}

    <script type="application/ld+json">
        {
           "@context": "http://schema.org",
           "@type": "WebSite",
           "url": "{{ url('/') }}",
           "potentialAction": {
                "@type": "SearchAction",
                "target": "{{ url('search') }}?search={search_term_string}",
                "query-input": "required name=search_term_string"
           }
        }
    </script>

    {{ googleAnalytics() }}

</head>
<body>

    @yield('content')

</body>
</html>
