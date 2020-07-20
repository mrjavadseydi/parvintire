@php
$href = $post->href();
@endphp
{
    "@type": "WebPage",
    "@id" : "{{ $href }}#webpage",
    "url" : "{{ $href }}",
    "name" : "{{ $post->title }}",
    "isPartOf" : {
        "@id" : "{{ url('/') }}#website"
    },
    "primaryImageOfPage" : {
        "@id" : "{{ $href }}#primaryimage"
    },
    "datePublished": "{{ str_replace(' ', 'T', $post->published_at) }}+04:30",
    "dateModified": "{{ str_replace(' ', 'T', (empty($post->updated_at) ? $post->created_at : $post->updated_at)) }}+04:30",
    "author" : {
        "@id" : "{{ url('/') }}#/schema/person/3ad5dba453726835cb0801b1d0419fab"
    },
    "description" : "{{ $post->excerpt }}",
    "breadcrumb" : {
        "@id" : "{{ $href }}#breadcrumb"
    }
}
