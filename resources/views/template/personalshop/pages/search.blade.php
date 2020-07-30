@extends(includeTemplate('master'))
@section('title', $title)
@section('keywords', $title)
@section('description', $title)
@section('ogType', 'product')
@section('ogDescription', $title)
@section('head-content')
    @if(view()->exists('admin.seo.pages.search'))
        @include('admin.seo.pages.search')
    @endif
@endsection
@section('content')
    <div class="container-fluid px-6 py-3">
        <?php $postType = $_GET['postType'] ?? 'products';?>
        @include(includeTemplate('pages.search.' . $postType))
    </div>
    <script>
        $(document).on('change', '.dropdown-filter input', function () {
            $('.search-view .items').addClass('d-none');
            $('.search-loading').removeClass('d-none');
            $(this).closest('form').submit();
        });
        function onSuccessSearch(response) {
            if(response.status = 'success') {
                $('.search-view').html(response.html1);
            }
            $('.search-view .items').removeClass('d-none');
            $('.search-loading').addClass('d-none');
        }
    </script>
@endsection
