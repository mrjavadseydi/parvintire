<?php
    $teacherId = $post->meta('teacher');
?>
<div class="input-group">
    <label>{{ $box['title'] }}</label>
    <select class="select2" name="teacher">
        <option value="">انتخاب مدرس</option>
        @foreach(teachers() as $teacher)
            <option {{ ($teacher->id == $teacherId ? 'selected' : '') }}
                value="{{ $teacher->id }}">{{ $teacher->name() }}
            </option>
        @endforeach
    </select>
</div>
