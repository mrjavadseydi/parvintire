<div class="input-group">
    <label>{{ $box['title'] }}</label>
    <select id="select2-box-{{ $key }}" name="{{ $key }}[]" multiple="multiple">
        @if(old('tags'))
            @foreach(old('tags') as $tag)
                <option selected value="{{ $tag }}">{{ $tag }}</option>
            @endforeach
        @else
            @foreach($post->tags as $tag)
                <option selected value="{{ $tag->tag }}">{{ $tag->tag }}</option>
            @endforeach
        @endif
    </select>
</div>

<script>
    $(document).ready(function () {
        $('#select2-box-{{ $key }}').select2({
            dir: "{{ $box['dir'] }}",
            tags: "{{ $box['tags'] }}",
            ajax: {
                url: "{{ url($box['url']) }}",
                data: function (params) {
                    return params;
                },
                processResults: function (data) {
                    return {
                        results: data.items
                    };
                }
            }
        });
    });
</script>
