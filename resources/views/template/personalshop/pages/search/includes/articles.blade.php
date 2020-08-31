<?php
if (isset($data)) {
    extract($data);
}
?>
<h5 class="mb-3">شما در حال جستجو در <b class="text-main-color">مقالات</b> می باشید</h5>
@if($posts->count() > 0)
    <div class="row items">
        @foreach($posts as $post)
            <div class="col-md-4 mb-3">
                @include(includeTemplate('cards.blog1'))
            </div>
        @endforeach
    </div>
    <div class="row py-3">
        <div class="rtl-pagination">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    </div>
@else
    <div class="alert alert-primary">
        <p>موردی یافت نشد</p>
    </div>
@endif
