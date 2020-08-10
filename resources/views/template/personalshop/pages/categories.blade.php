@extends(includeTemplate('master'))
@section('title', 'دسته بندی ها')
@section('keywords', siteKeywords())
@section('description', siteDescription())
@section('ogType', 'website')
@section('ogTitle', getOption('site-title'))
@section('ogDescription', siteDescription())
@section('ogImage', siteLogo())
@section('content')
    <div class="container py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active" aria-current="page">دسته بندی ها</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap">
        @foreach($categories as $category)
            <div class="d-flex mb-1 flex-grow-1 grid5 bg-light rounded p-3 ml-1 category-flex">
                <a class="flex-40" href="{{ route('category', ['id' => $category->id, 'slug' => $category->slug]) }}">
                    <figure class="p-3 d-flex align-items-center">
                        <img src="{{ renderImage($category->image, 170, 130) }}" alt="{{ $category->title }}">
                    </figure>
                </a>
                <div class="d-flex rounded flex-column justify-content-between w-100">
                    <div class="d-flex flex-column">
                        <h3 class="h6">{{ $category->title }}</h3>
                        <p class="text-justify text-muted small">{{ strip_tags($category->description) }}</p>
                    </div>
                    <div class="text-left">
                        <a href="{{ route('category', ['id' => $category->id, 'slug' => $category->slug]) }}" class="more fal fa-arrow-circle-left"></a>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
@endsection
