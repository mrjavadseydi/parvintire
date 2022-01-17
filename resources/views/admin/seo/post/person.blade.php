{
    "@type" : ["Person"],
    "@id" : "{{ url('/') }}#/schema/person/3ad5dba453726835cb0801b1d0419fab",
    "name": "{{ $user->name() }}",
    "image" : {
        "@type" : "ImageObject",
        "@id" : "{{ url('/') }}#authorlogo",
        "url" : "{{ $user->avatar() }}",
        "caption" : "{{ $user->name() }}"
    }
}
