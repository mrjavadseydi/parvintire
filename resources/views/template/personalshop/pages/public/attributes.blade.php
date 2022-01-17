@foreach($post->attributes(true) as $attr)
    <div class="attribute-key-values mt-1">
        <h3 class="mb-2"><i class="fa fa-caret-left align-middle ml-1"></i>{{ $attr['title'] }}</h3>
        @foreach($attr['keys'] as $key)
            <div class="d-flex align-items-start">
                <h5 class="px-3">{{ $key['title'] }}</h5>
                <div class="d-flex flex-column flex-fill mr-2">
                    @foreach($key['values'] as $value)
                        <h6 class="mb-2 px-3">{{ $value['title'] }}</h6>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endforeach
