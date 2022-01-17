@if($postType != 'files')
<?php $articleCategories = \LaraBase\Categories\Models\Category::where('post_type', 'files')->where('parent', null)->get();?>
@if($articleCategories->count() > 0)
    <div class="card mt-2">
        <div class="card-body">
            @include(includeTemplate('divider.2'), ['title' => "<small>دسته بندی محصولات دانلودی</small>" ?? ''])
            <br>
            @foreach($articleCategories as $item)
                <div class="dropdown-filter">
                    <a href="{{ url('search?q=&postType=files&categories=' . $item->id) }}">{{ $item->title }}</a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endif
