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
                <?php $person1 = getOptionImage('digishopPeronLeft'); ?>
                <img src="{{ $person1['src'] }}" alt="{{ $person1['alt'] }}">
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
            <h1>{{ getOption('personal-title-1') }}</h1>
            <p>{!! getOption('personal-description-1') !!}</p>
            <form action="{{ route('search') }}">
                <input type="hidden" name="postType" value="products">
                <input type="text" name="q" placeholder="دنبال چی میگردی؟">
                <button>جستجو</button>
            </form>
        </div>
        <br><br><br><br>
        <?php $m = \LaraBase\Menus\Models\Menu::find(getOption('personal-skill-menu')); ?>
        @if($m != null)
            @include(includeTemplate('divider.4'), ['title' => $m->title])
            @include(includeTemplate('sections.menu-skill'), ['menuId' => $m->id])
        @endif
        <br><br>
        @include(includeTemplate('divider.4'), ['title' => 'پادکست ها'])
        @include(includeTemplate('sections.swiper-podcast'))
        <br><br>
        @include(includeTemplate('divider.4'), ['title' => 'کتاب ها'])
        @include(includeTemplate('sections.swiper-books'))
        @include(includeTemplate('sections.gallery'))
    </div>
    <br><br>
    <div class="position-relative">
        <div class="circle-triangle circle-right">
            <figure>
                <img class="main-color" src="{{ image('circle-triangle.png', 'template') }}" alt="circle triangle">
            </figure>
            <figure class="person">
                <?php $person2 = getOptionImage('digishopPeronRight'); ?>
                <img src="{{ $person2['src'] }}" alt="{{ $person2['alt'] }}">
            </figure>
        </div>
    </div>
    <div class="site-description site-description-2 position-relative">
        <h1>{{ getOption('personal-title-2') }}</h1>
        <p>{!! getOption('personal-description-2') !!}</p>
        <div class="circle-triangle-right left">
            <figure>
                <img src="{{ image('broken-triangle.png', 'template') }}" alt="broken triangle">
            </figure>
            <figure class="broken-triangle-sm">
                <img src="{{ image('broken-triangle.png', 'template') }}" alt="broken triangle">
            </figure>
        </div>
    </div>
    <br><br><br><br><br>
    <div class="container-fluid px-6 position-relative">
        @include(includeTemplate('divider.1'), ['title' => 'سایر توانایی های ' . siteName()])
        @include(includeTemplate('sections.tabs-articles-latest'), ['cats' => getOption('digishopHomeOtherSkill')])
    </div>
@endsection
