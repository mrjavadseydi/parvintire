@php
    $url = url('/');
    $mainMenu = getMenu(getOption('site-menu'), true) ?? [];
    $mainMenuCount = count($mainMenu);
    $sameAtMenu = getMenu(getOption('site-sameAs-menu'), true) ?? [];
    $sameAtMenuCount = count($sameAtMenu);
    $siteLogo = getOptionImage('siteLogo');
    $priceRange = getOption('price-range');
@endphp
<script type='application/ld+json'>
{
    "@context": "http://www.schema.org",
    "@graph": [
        {
            "@type": "{{ getOption('site-type') }}",
            "name": "{{ getOption('site-title') }}",
            "url": "{{ $url }}",
            "logo": "{{ $siteLogo['src'] }}",
            "image": "{{ $siteLogo['src'] }}",
            "description": "{{ siteDescription() }}",
            @if(!empty($priceRange))
            "priceRange": "{{ $priceRange }}",
            @endif
            "address": {
              "@type": "PostalAddress",
              "streetAddress": "{{ siteAddress() }}",
              "addressLocality": "{{ getOption('site-city') }}",
              "addressRegion": "{{ getOption('site-region') }}",
              "addressCountry": "{{ getOption('site-country') }}"
            },
            "geo": {
              "@type": "GeoCoordinates",
              "latitude": "{{ getOption('site-latitude') }}",
              "longitude": "{{ getOption('site-longitude') }}"
            },
            "telephone": "{{ sitePhone() }}",
            "openingHours": "{{ getOption('site-openingHours') }}",
            "sameAs": [
                @for($i = 0; $i < $sameAtMenuCount; $i++)
                "{{ url($sameAtMenu[$i]['link']) }}"{{ $i == ($sameAtMenuCount-1) ? '' : ',' }}
                @endfor
            ]
        },
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "url": "{{ $url }}",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "{{ url('search/?q={search_term_string}') }}",
                "query-input": "required name=search_term_string"
            }
        }
        @if($mainMenuCount > 0)
            ,
            @for($i = 0; $i < $mainMenuCount; $i++)
                {
                    "@context":"http://schema.org",
                    "@type":"SiteNavigationElement",
                    "@id":"#table-of-contents",
                    "name": "{{ $mainMenu[$i]['title'] }}",
                    "url":  "{{ $mainMenu[$i]['link'] }}"
                }{{ $i == ($mainMenuCount-1) ? '' : ',' }}
            @endfor
        @endif
        @yield('seoContent')
    ]
}
</script>
