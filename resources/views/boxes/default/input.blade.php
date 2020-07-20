<div class="input-group">
    <label>{{ $box['title'] }}</label>
    <input
        {{ boxAttributes($box) }}
        {{ boxClasses($box) }}
        {{ boxIds($box) }}
        {{ boxType($box) }}
        name="{{ $key }}"
        value="{{ old($key) ?? $post->$key }}">
</div>
