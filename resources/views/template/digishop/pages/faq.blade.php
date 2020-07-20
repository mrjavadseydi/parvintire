@extends(includeTemplate('master'))
@section('title', 'سوالات متداول')
@section('keywords', siteKeywords())
@section('description', siteDescription())
@section('ogType', 'website')
@section('ogTitle', 'سوالات متداول')
@section('ogDescription', siteDescription())
@section('ogImage', siteLogo())
@section('content')
    <div class="container-fluid px-6 py-3">
        <div class="row">
            <div class="col-md-3">
                <div class="card mt-2">
                    <div class="card-body position-relative">
                        @include(includeTemplate('divider.2'), ['title' => "<small>دسته بندی ها</small>" ?? ''])
                        <br>
                        @foreach($categories as $item)
                            <div class="dropdown-filter">
                                <a class="{{ $item->id == ($_GET['categoryId'] ?? '') ? 'faq-active' : '' }}" href="{{ url('faq?categoryId=' . $item->id) }}">{{ $item->title }}</a>
                            </div>
                        @endforeach
                        @include(includeTemplate('graphics.right-circle'))
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="faq w-100">
                    <div id="accordion">
                        @foreach($posts as $i => $post)
                            <div class="card {{ $i == 0 ? '' : 'collapsed' }} mb-2" data-toggle="collapse" data-target="#i-{{ $i }}" aria-expanded="true" aria-controls="i-{{ $i }}">
                                <div class="card-header">
                                    <h6>{{ $post->title }} <i class="fal fa-angle-down float-left"></i></h6>
                                </div>
                                <div id="i-{{ $i }}" class="collapse {{ $i == 0 ? 'show' : '' }}" aria-labelledby="i-{{ $i }}" data-parent="#accordion">
                                    <div class="card-body">
                                        {!! $post->content !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
