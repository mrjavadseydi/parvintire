<div class="input-group">
    <label>{{ $box['title'] }}</label>
    <select id="select2-box-{{ $key }}" name="{{ $key }}[]" multiple="multiple">
        @if(old('tags'))
            @foreach(old('phones') as $phone)
                <option selected value="{{ $phone }}">{{ $phone }}</option>
            @endforeach
        @else
            @foreach($post->getMetas(['key' => 'number', 'more' => 'phone']) as $record)
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
