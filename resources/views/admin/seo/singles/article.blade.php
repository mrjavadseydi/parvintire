@extends('admin.seo.master')
@php
    $url = url('/');
    $siteLogo = getOptionImage('siteLogo');
@endphp
@section('seoContent')
    ,
    {
        "@context": "https://www.schema.org",
        "@type": "Article",
        "headline": "{{ $post->title }}",
        "alternativeHeadline": "{{ $post->title }}",
        "name": "{{ $post->title }}",
        "description": "{{ $post->excerpt }}",
        "image": "{{ $post->thumbnail() }}",
        "url": "{{ $post->href() }}",
        "author": "{{ $user->name() }}",
        "keywords": "{{ $post->tags->implode(' ', 'tag') }}",
        "datePublished": "{{ explode(' ', $post->published_at)[0] }}",
        "dateCreated": "{{ explode(' ', $post->created_at)[0] }}",
        "dateModified": "{{ empty($post->updated_at) ? explode(' ', $post->created_at)[0] : explode(' ', $post->updated_at)[0] }}",
        "publisher": {
            "@type": "Organization",
            "name": "{{ getOption('site-title') }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ $siteLogo['src'] }}"
            }
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url('search/q=&postType=articles') }}"
        }
    },
    @include('admin.seo.post.person'),
    @include('admin.seo.post.breadcrumb'),
    {
        "@context": "https://schema.org/",
        "@type": "MediaObject",
        "name": "{{ $post->title }}",
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": {{ $post->rateByLikes() }},
            "ratingCount": {{ getPostVisit($post->id) }},
            "bestRating": 5,
            "worstRating": 0
        }
    }
@endsection
