<div class="row">
    <form onSuccess="onSuccessSearch" action="{{ url("search") }}" class="col-md-3 ajaxForm">
        <input type="hidden" name="output" value="json">
        <input type="hidden" name="view1" value="{{ includeTemplate('pages.search.include-articles') }}">
        @include(includeTemplate('pages.search.search'), ['title' => 'دسته بندی محصولات'])
        <input type="hidden" name="postType" value="{{ $_GET['postType'] ?? 'products' }}">
        @include(includeTemplate('pages.search.categories'), ['title' => 'دسته بندی مقالات'])
        @include(includeTemplate('pages.search.akv'))
        @include(includeTemplate('pages.search.product-categories'))
    </form>
    <div class="col-md-9">
        <div class="search-view">
            @include(includeTemplate('pages.search.include-articles'))
        </div>
        <div class="search-loading d-none">
            <figure class="text-center">
                <img src="{{ image('loading.png', 'admin') }}" alt="loading...">
            </figure>
        </div>
    </div>
</div>
