@include(includeTemplate('sections.slider'), ['records' => $homeSlider])
<section class="pt-3">
    @include(includeTemplate('sections.grid5'), ['menuId' => getOption('digishopBottomSliderMenu')])
</section>
@include(includeTemplate('divider.1'), ['title' => 'تازه ترین محصولات'])
@include(includeTemplate('sections.tabs-products-latest'), ['cats' => getOption('digishopHomeNewProductsCats')])
@include(includeTemplate('sections.special-offers'), ['description' => getOption('digishopHomeSpecialText'), 'ids' => getOption('digishopHomeSpecialIds')])
<br>
@include(includeTemplate('divider.1'), ['title' => 'پرفروش ترین محصولات'])
@include(includeTemplate('sections.tabs-products-mostsales'), ['cats' => getOption('digishopHomeMostSalesProductsCats')])
@include(includeTemplate('sections.box4'), ['menuId' => getOption('digishop4Menu')])
@include(includeTemplate('divider.1'), ['title' => 'سوالات متداول'])
<div class="position-relative overflow-hidden container">
    <div class="row py-3 py-md-5">
        <div class="order-2 order-md-1 col-md-7 d-flex justify-content-center">
            @include(includeTemplate('sections.menu-faq'), ['ids' => getOption('digishopHomeFaqIds')])
        </div>
        <div class="order-1 order-md-2 mb-3 mb-md-0 col-md-5 d-flex justify-content-center">
            @include(includeTemplate('sections.menu-circle'), ['menuId' => getOption('digishopHomeCircleMenu')])
        </div>
    </div>
    @include(includeTemplate('graphics.left-circle'), ['class' => 'orange sm'])
</div>
@include(includeTemplate('divider.1'), ['title' => 'وبلاگ'])
<div class="position-relative overflow-hidden container py-3">
    <div class="row">
        @foreach(\LaraBase\Posts\Models\Post::published()->postType('articles')->latest()->limit(4)->get() as $post)
            <div class="col-md-3 col-6 px-2 px-md-3 mb-3 mb-md-0">
                @include(includeTemplate('cards.blog1'))
            </div>
        @endforeach
    </div>
    @include(includeTemplate('graphics.left-circle'))
    @include(includeTemplate('graphics.right-circle'), ['class' => 'orange sm'])
</div>
<br>
@include(includeTemplate('sections.menu-brands'), ['title' => 'عرضه کننده برترین برندهای جهان', 'menuId' => getOption('digishopHomeBrandMenu')])
