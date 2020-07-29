@extends(includeTemplate('profile.master'))
@section('title', 'علاقه مندی ها')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">علاقه مندی ها</li>
@endsection
@section('main')
    @foreach(['products' => 'محصولات'] as $k => $v)
{{--        @include(includeTemplate('divider.1'), ['title' => $v])--}}
        <div class="row">
        @foreach($favorites->where('post_type', $k)->filter() as $post)
            <div class="col-md-4">
                @if($k == 'products')
                    <?php $product = \LaraBase\Store\Models\Product::where('post_id', $post->id)->first(); ?>
                    @include(includeTemplate('cards.product1'))
                @else
                    @include(includeTemplate('cards.blog1'))
                @endif
            </div>
        @endforeach
        </div>
    @endforeach
@endsection
