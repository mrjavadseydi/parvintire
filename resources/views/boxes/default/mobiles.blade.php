<div class="input-group">
    <label>{{ $box['title'] }}</label>
    <select id="select2-box-{{ $key }}" name="{{ $key }}[]" multiple="multiple">
        @if(old('tags'))
            @foreach(old('mobiles') as $mobile)
                <option selected value="{{ $mobile }}">{{ $mobile }}</option>
            @endforeach
        @else
            @foreach($post->getMetas(['key' => 'number', 'more' => 'mobile']) as $record)
                <option selected value="{{ $record->value }}">{{ $record->value }}</option>
            @endforeach
        @endif
    </select>
</div>

<script>
    $(document).ready(function () {
        $('#select2-box-{{ $key }}').select2({
            dir: "{{ $box['dir'] }}",
            tags: "{{ $box['tags'] }}",
            maximumSelectionLength: "{{ $box['maximumSelectionLength'] }}",
        });
    });
</script>
