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
    <div class="container">
        <div class="site-description pt-5">
            <h1 class="h3">{{ getOption('personal-title-1') }}</h1>
            <p>{!! getOption('personal-description-1') !!}</p>
            <form action="{{ route('search') }}">
                <input type="hidden" name="postType" value="podcasts">
                <input type="text" name="q" placeholder="دنبال چی میگردی؟">
                <button>جستجو</button>
            </form>
        </div>
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
<div class="position-relative">
    <div class="container">
        <div class="site-description site-description-2">
            <h1 class="h2 site-description-title">{{ getOption('personal-title-2') }}</h1>
            <p>{!! getOption('personal-description-2') !!}</p>
        </div>
    </div>
    <div class="circle-triangle-right left">
        <figure>
            <img src="{{ image('broken-triangle.png', 'template') }}" alt="broken triangle">
        </figure>
        <figure class="broken-triangle-sm">
            <img src="{{ image('broken-triangle.png', 'template') }}" alt="broken triangle">
        </figure>
    </div>
</div>
<br><br>
<div class="container position-relative">
    @include(includeTemplate('divider.1'), ['title' => getOption('digishopTabProductTitle') . siteName()])
    @include(includeTemplate('sections.tabs-articles-latest'), ['cats' => getOption('digishopHomeOtherSkill')])
</div>
