<?php $articleCategories = \LaraBase\Categories\Models\Category::where('post_type', 'articles')->where('parent', null)->get();?>
@if($articleCategories != null)
    <div class="card mt-2">
        <div class="card-body">
            @include(includeTemplate('divider.2'), ['title' => "<small>دسته بندی وبلاگ</small>" ?? ''])
            <br>
            @foreach($articleCategories as $item)
                <div class="dropdown-filter">
                    <a href="{{ url('search?q=&postType=articles&categories=' . $item->id) }}">{{ $item->title }}</a>
                </div>
            @endforeach
        </div>
    </div>
@endif
