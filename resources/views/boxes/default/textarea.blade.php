<div class="input-group">
    <label>{{ $box['title'] }}</label>
    <textarea
        {{ boxAttributes($box) }}
        {{ boxClasses($box) }}
        {{ boxIds($box) }}
        name="{{ $key }}">{{ old($key) ?? $post->$key }}</textarea>
</div>
