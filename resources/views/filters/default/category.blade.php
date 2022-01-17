<div class="input-group mt10">
    <label>دسته‌بندی</label>
    <select class="select2" name="categories[]" multiple="multiple">

        <?php
            $user = auth()->user();
        ?>
        @foreach(getUserPostTypes($user) as $postType)
            @php
                $categories = $user->canSetCategories(false, ['post_type' => $postType->type]);
            @endphp
            @if($categories->count() > 0)
                <optgroup label="دسته‌بندی‌های {{ $postType->total_label }}">
                    <?php echo selectOptions(($_GET['categories'] ?? []), $categories);?>
                </optgroup>
            @endif
        @endforeach

    </select>
</div>
