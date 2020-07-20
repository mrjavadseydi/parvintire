"image": [
    {
        "type": null,
        "url": "{{ $post->thumbnail() }}",
        "alt": null,
        "title": null
    }
    @foreach($post->gallery() as $gallery)
    ,
    {
        "type": null,
        "url": "{{ url($gallery->value) }}",
        "alt": null,
        "title": null
    }
    @endforeach
]
