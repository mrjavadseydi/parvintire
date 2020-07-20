@extends('admin.seo.master')
@php
$url = url('/');
$fullUrl = url()->full();
@endphp
@section('seoContent')
,
{
    "@type": "SearchResultsPage",
    "@id": "{{ $fullUrl }}/#webpage",
    "url": "{{ $fullUrl }}",
    "inLanguage": "{{ app()->getLocale() }}",
    "name": "{{ $title }}",
    "isPartOf": {
        "@id": "{{ $url }}/#website"
    }
},
{
    "@type": "BreadcrumbList",
    "@id": "{{ $fullUrl }}/#breadcrumb",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "item": {
                "@type": "WebPage",
                "@id": "{{ $url }}",
                "url": "{{ $url }}",
                "name": "صفحه اصلی"
            }
        },
        {
            "@type": "ListItem",
            "position": 2,
            "item": {
                "@type": "WebPage",
                "@id": "{{ $fullUrl }}",
                "url": "{{ $fullUrl }}",
                "name": "{{ $title }}"
            }
        }
    ]
}
@endsection
