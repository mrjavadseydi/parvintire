<?php
    if (isset($data)) {
        extract($data);
    }
?>
<h5 class="mb-3">شما در حال جستجو در <b class="text-main-color">کتاب ها</b> می باشید</h5>
@if($posts->count() > 0)
    <?php
        $products = \LaraBase\Store\Models\Product::whereIn('post_id', $posts->pluck('id')->toArray())->get()
    ?>
    <div class="row items">
   @foreach($posts as $post)
       <?php
           $product = $products->where('post_id', $post->id)->first();
       ?>
       <div class="col-md-4 mb-3">
           @include(includeTemplate('cards.product1'))
       </div>
   @endforeach
    </div>
    <div class="row py-3">
        <div class="rtl-pagination">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    </div>
    <script src="{{ asset('assets/admin/default/store.js') }}"></script>
@else
    <div class="alert alert-primary">
        <p>موردی یافت نشد</p>
    </div>
@endif
