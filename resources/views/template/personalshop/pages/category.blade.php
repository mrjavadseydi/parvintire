@extends(includeTemplate('master'))
@section('title', $category->title)
@section('keywords', siteKeywords())
@section('description', siteDescription())
@section('ogType', 'website')
@section('ogTitle', getOption('site-title'))
@section('ogDescription', siteDescription())
@section('ogImage', siteLogo())
@section('head-content')
    @if(view()->exists('admin.seo.pages.category'))
        @include('admin.seo.pages.category')
    @endif
@endsection
@section('content')
    <div class="container py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ url('categories') }}?postType={{ $category->post_type }}">دسته بندی ها</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->title }}</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap">
            @foreach($parents as $c)
                <div class="d-flex mb-1 flex-grow-1 grid5 bg-light rounded p-3 ml-1 category-flex">
                    <a class="flex-40" href="{{ route('category', ['id' => $c->id, 'slug' => $c->slug]) }}">
                        <figure class="p-3 d-flex align-items-center">
                            <img src="{{ renderImage($c->image, 170, 130) }}" alt="{{ $c->title }}">
                        </figure>
                    </a>
                    <div class="d-flex rounded flex-column justify-content-between w-100">
                        <div class="d-flex flex-column">
                            <h3 class="h6">{{ $c->title }}</h3>
                            <p class="text-justify text-muted small">{{ strip_tags($c->description) }}</p>
                        </div>
                        <div class="text-left">
                            <a href="{{ route('category', ['id' => $c->id, 'slug' => $c->slug]) }}" class="more fal fa-arrow-circle-left"></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="posts">
            @if($category->post_type == 'products')
                <?php
                    $products = \LaraBase\Store\Models\Product::whereIn('post_id', $posts->pluck('id')->toArray())->get();
                ?>
                <div class="row mt-3">
                @foreach($posts as $post)
                    <?php $product = $products->where('post_id', $post->id)->first();?>
                    <div class="col-md-3">
                        @include(includeTemplate('cards.product1'))
                    </div>
                @endforeach
                </div>
            @else
                <div class="row mt-3">
                @foreach($posts as $post)
                    <div class="col-md-3">
                        @include(includeTemplate('cards.blog1'))
                    </div>
                @endforeach
                </div>
            @endif
            <div class="pagination-rtl">
                {{ $posts->appends($_GET)->links() }}
            </div>
        </div>
    </div>
@endsection
