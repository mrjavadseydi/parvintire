<?php
$ids = [];

if (old('survey')) {
    $ids = old('survey');
} else {
    $ids = \LaraBase\Posts\Models\PostAttribute::where(['type' => 'comment', 'post_id' => $post->id, 'active' => '1'])->pluck('attribute_id')->toArray();
}

if (empty($ids)) {
    $ids = \LaraBase\Attributes\Models\Attribute::where('type', 'comment')->whereIn('id', \LaraBase\Attributes\Models\AttributeRelation::where(['key' => 'attribute_postType', 'more' => $post->post_type])->pluck('value')->toArray())->pluck('id')->toArray();
}

$surveys = \LaraBase\Attributes\Models\Attribute::whereIn('id', $ids)->get();

?>

<div class="input-group">
    <label>{{ $box['title'] }}</label>
    <select id="select2-box-{{ $key }}" name="{{ $key }}[]" multiple="multiple">
        @foreach($surveys as $survey)
            <option selected value="{{ $survey->title }}">{{ $survey->title }}</option>
        @endforeach
    </select>
</div>

<script>
    $(document).ready(function () {
        $('#select2-box-{{ $key }}').select2({
            dir: "{{ $box['dir'] }}",
            tags: "{{ $box['tags'] }}",
            maximumSelectionLength: "{{ $box['maximumSelectionLength'] }}",
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
