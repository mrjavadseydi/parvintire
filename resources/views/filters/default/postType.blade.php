<div class="input-group mt10">
    <label>نوع پست</label>
    <select class="select2" name="postTypes[]" multiple="multiple">
        @foreach(auth()->user()->canSetPostTypes()['postTypes'] as $postType)
            <option
                {{ (in_array($postType->type, ($_GET['postTypes'] ?? [])) ? "selected" : "" ) }}
                value="{{ $postType->type }}">{{ $postType->label }}
            </option>
        @endforeach
    </select>
</div>
