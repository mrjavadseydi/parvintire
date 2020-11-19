@extends('admin.seo.master')
@php
    $price = 0;
    if ($product != null)
        $price = $product->price();

    $url = url('/');
@endphp
@section('seoContent')
,
{
    "@context": "https://www.schema.org",
    "@type": "Product",
    "name": "{{ $post->title }}",
    "image": "{{ $post->thumbnail() }}",
    "description": "{{ $post->excerpt }}",
    "sku": {{ $post->id }},
    "mpn": {{ $post->id }},
    "brand": "{{ getOption('site-brand') }}",
    "url": "{{ $post->href() }}",
    @include('admin.seo.post.rateByLikes'),
    "review": {
        "@type": "http://schema.org/Review",
        "author": "{{ $user->name() }}"
    },
    "offers": {
        "@type": "Offer",
        "priceCurrency": "IRR",
        "price": {{ toIRR($price) }},
        "itemCondition": "https://schema.org/UsedCondition",
        "availability": "https://schema.org/InStock",
        "priceValidUntil": "{{ ( $product->discount() > 0 ? explode(' ', $product->end_date)[0] : (date('Y')+1) . date('-m-d') ) }}",
        "url": "{{ $post->href() }}"
    }
},
@include('admin.seo.post.person'),
@include('admin.seo.post.breadcrumb')
@endsection
