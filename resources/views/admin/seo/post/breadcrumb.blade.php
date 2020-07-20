@php
$href = $post->href();
@endphp
{
    "@type": "BreadcrumbList",
    "@id": "{{ $href }}/#breadcrumb",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "item": {
                "@type": "WebPage",
                "@id": "{{ url('/') }}",
                "url": "{{ url('/') }}",
                "name": "صفحه اصلی"
            }
        },
        @foreach($categories as $i => $category)
        {
            "@type": "ListItem",
            "position": {{ ($i+2) }},
            "item": {
                "@type": "WebPage",
                "@id": "{{ url("categories/{$category->id}/{$category->slug}") }}",
                "url": "{{ url("categories/{$category->id}/{$category->slug}") }}",
                "name": "{{ $category->title }}"
            }
        },
        @endforeach
        {
            "@type": "ListItem",
            "position": {{ ($categories->count() + 2) }},
            "item": {
                "@type": "WebPage",
                "@id": "{{ $href }}",
                "url": "{{ $href }}",
                "name": "{{ $post->title }}"
            }
        }
    ]
}
