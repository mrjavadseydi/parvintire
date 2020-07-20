<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="keywords" content="@yield('keywords')">
<meta name="description" content="@yield('description')">
<link rel="icon" href="{{ favicon() }}">
<link rel="canonical" href="{{ url()->current() }}"/>
<meta name="theme-color" content="@yield('color', getOption('errors-theme-color'), '#039BE5')">

<title>@yield('title')</title>

{{ renderTheme('errors') }}

@yield('head-content')

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

{!! googleAnalytics() !!}
