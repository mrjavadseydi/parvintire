@foreach($loadBoxes as $key)
    @if(isset($boxes[$key]))
        @if(in_array($key, $postBoxes))
            @include("boxes.default.{$boxes[$key]['box']}", [
                'key' => $key,
                'box' => $boxes[$key]
            ])
        @endif
    @endif
@endforeach
