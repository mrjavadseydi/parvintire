@extends('admin.seo.master')
@php
    $url = url('/');
    $fullUrl = url("categories/{$category->id}/{$category->slug}");
@endphp
@section('seoContent')
,
        {
            "@type": "CollectionPage",
            "@id": "{{ $fullUrl }}/#webpage",
            "url": "{{ $fullUrl }}",
            "inLanguage": "fa-IR",
            "name": "{{ $category->title }}",
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
                }
                @foreach($category->parents() as $i => $cat)
                ,
                {
                    "@type": "ListItem",
                    "position": {{ ($i+2) }},
                    "item": {
                        "@type": "WebPage",
                        "@id": "{{ url("categories/{$cat->id}/{$cat->slug}") }}",
                        "url": "{{ url("categories/{$cat->id}/{$cat->slug}") }}",
                        "name": "{{ $cat->title }}"
                    }
                }
                @endforeach
            ]
        }
@endsection
