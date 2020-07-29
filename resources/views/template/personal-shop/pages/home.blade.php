@extends(includeTemplate('master'))
@section('title', getOption('site-title'))
@section('keywords', siteKeywords())
@section('description', siteDescription())
@section('ogType', 'website')
@section('ogTitle', getOption('site-title'))
@section('ogDescription', siteDescription())
@section('ogImage', siteLogo())
@section('head-content')
    @if(view()->exists('admin.seo.pages.home'))
        @include('admin.seo.pages.home')
    @endif
@endsection
@section('content')
    <div class="container-fluid position-relative px-6">
        <div class="circle-triangle">
            <figure>
                <img class="main-color" src="{{ image('circle-triangle.png', 'template') }}" alt="circle triangle">
            </figure>
            <figure class="person">
                <img src="{{ image('person.png', 'template') }}" alt="circle triangle">
            </figure>
        </div>
        <div class="circle-triangle-right">
            <figure>
                <img src="{{ image('broken-triangle.png', 'template') }}" alt="broken triangle">
            </figure>
            <figure class="broken-triangle-sm">
                <img src="{{ image('broken-triangle.png', 'template') }}" alt="broken triangle">
            </figure>
        </div>
        <div class="site-description">
            <h1>{{ siteName() }}</h1>
            <p>امیرحافظ کیهانی یکی از ستاره های‌ آینده دار ایرانیست که از سن بسیار کم توانست بـه کمک پدرشاستعدادهایش را در زمینه های گوناگون مانند زبان انگلیسی، موسیقی، ورزش، نویسندگی، پادکست و.. کشف کند .</p>
            <p>امیرحافظ کیهانی،متولد 18 مهر ماه 1381 در شهر تهران می باشد. این کودک اعجوبه و نابغه، با مربیگریپدر از سن یک سالگی فعالیت خود شروع کرده اسـت.</p>
            <p>امیرحافظ کیهانی،متولد 18 مهر ماه 1381 در شهر تهران می باشد. این کودک اعجوبه و نابغه، با مربیگریپدر از سن یک سالگی فعالیت خود شروع کرده اسـت.</p>
            <form action="{{ route('search') }}">
                <input type="hidden" name="postType" value="products">
                <input type="text" name="q" placeholder="دنبال چی میگردی؟">
                <button>جستجو</button>
            </form>
        </div>
        <?php
            $m = \LaraBase\Menus\Models\Menu::find(getOption('personal-skill-menu'));
        ?>
        <br><br><br><br>
        @if($m != null)
            @include(includeTemplate('divider.4'), ['title' => $m->title])
            @include(includeTemplate('sections.menu-skill'), ['menuId' => $m->id])
        @endif
        <br><br>
        @include(includeTemplate('divider.4'), ['title' => 'پادکست ها'])
        @include(includeTemplate('sections.swiper-podcast'))
    </div>
    <div class="d-none">
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
        <div class="position-relative overflow-hidden container-fluid px-6">
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
        <div class="position-relative overflow-hidden container-fluid px-6 py-3">
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
    </div>
@endsection
