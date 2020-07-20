<div class="input-group">
    <label>{{ $box['title'] }}</label>
    <select style="width: 100% !important;" tags="{{ $box['tags'] }}" url="{{ url($box['url']) }}" dir="{{ $box['dir'] }}" maximumSelectionLength="{{ $box['maximumSelectionLength'] }}" id="select2-tags" name="{{ $key }}[]" multiple="multiple">
        @if(old($key) ?? ($_GET['users'] ?? []))
            @foreach(old($key) ?? ($_GET['users'] ?? []) as $user)
                <option selected value="{{ $user }}">{{ users()->find($user)->name() }}</option>
            @endforeach
        @endif
    </select>
</div>
