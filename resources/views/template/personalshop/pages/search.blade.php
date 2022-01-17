@extends(includeTemplate('master'))
@section('title', $title)
@section('keywords', $title)
@section('description', $title)
@section('ogType', 'product')
@section('ogDescription', $title)
@section('canonical', $canonical)
@section('robots', $robots)
@section('head-content')
    @if(view()->exists('admin.seo.pages.search'))
        @include('admin.seo.pages.search')
    @endif
@endsection
@section('content')
    <div class="container py-3">
        <?php $postType = $_GET['postType'] ?? 'products';?>
        <div class="row">
            <form id="form-search" onSuccess="onSuccessSearch" action="{{ url("search") }}" class="col-md-3 ajaxForm">
                <input id="paginator" type="hidden" name="page" value="1">
                <input type="hidden" name="count" value="21">
                <input type="hidden" name="output" value="json">
                <input type="hidden" name="postType" value="{{ $postType }}">
                <input type="hidden" name="view1" value="{{ includeTemplate('pages.search.includes.' . $postType) }}">
                @include(includeTemplate('pages.search.search'))
                @include(includeTemplate('pages.search.categories'), ['title' => 'دسته بندی ها'])
                @include(includeTemplate('pages.search.akv'))
                @include(includeTemplate('pages.search.tools.podcast-categories'))
                @include(includeTemplate('pages.search.tools.books-categories'))
                @include(includeTemplate('pages.search.tools.products-categories'))
                @include(includeTemplate('pages.search.tools.blog-categories'))
                @include(includeTemplate('pages.search.tools.files-categories'))
            </form>
            <div class="col-md-9">
                <div class="search-view">
                    @include(includeTemplate('pages.search.includes.'.$postType))
                </div>
                <div class="search-loading d-none">
                    <figure class="text-center">
                        <img src="{{ image('loading.png', 'admin') }}" alt="loading...">
                    </figure>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('change', '.dropdown-filter input', function () {
            loadingSearchForm();
            $('#paginator').val(1);
            $(this).closest('form').submit();
        });
        $(document).on('keyup', '#search-form-input', function (e) {
            if (e.keyCode == 13) {
                loadingSearchForm();
            }
        });
        $(document).on('click', '.pagination .page-link', function (e) {
            e.preventDefault();
            loadingSearchForm();
            $('#paginator').val($(this).text());
            $('#form-search').submit();
            $("html, body").animate({ scrollTop: 0 }, "slow");
        });
        function loadingSearchForm() {
            $('.search-view').addClass('d-none');
            $('.search-loading').removeClass('d-none');
        }
        function onSuccessSearch(response) {
            if(response.status = 'success') {
                $('.search-view').html(response.html1);
            }
            $('.search-view').removeClass('d-none');
            $('.search-loading').addClass('d-none');
        }
    </script>
@endsection
