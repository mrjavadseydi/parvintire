<?php
    $menu = getMenu(false, $menuId);
?>
@if($menu != null)
<div class="position-relative overflow-hidden">
    <div class="position-relative container py-3">
        <div class="d-flex flex-wrap flex-lg-nowrap">
            @if(isset($menu[0]))
                <?php $m = $menu[0]; ?>
                <div class="d-flex mb-1 mb-lg-0 flex-grow-1 grid5 bg-light rounded p-3 ml-1 first">
                    <figure class="p-3 d-flex align-items-center">
                        <img src="{{ renderImage($m['image'], 170, 130) }}" alt="{{ $m['title'] }}">
                    </figure>
                    <div class="d-flex rounded flex-column justify-content-between w-100">
                        <div class="d-flex flex-column">
                            <h3 class="h6">{{ $m['title'] }}</h3>
                            <p class="text-justify text-muted small">{{ strip_tags($m['content']) }}</p>
                        </div>
                        <div class="text-left">
                            <a href="{{ $m['link'] }}" class="more fal fa-arrow-circle-left"></a>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($menu[1]))
                <?php $m = $menu[1]; ?>
                <div class="d-flex mb-1 mb-lg-0 flex-grow-1 flex-column justify-content-center ml-0 ml-sm-1 sm">
                    <div class="d-flex grid5 bg-light rounded px-3 py-0 mb-1">
                        <figure class="p-3 d-flex align-items-center">
                            <img src="{{ renderImage($m['image'], 90, 90) }}" alt="{{ $m['title'] }}">
                        </figure>
                        <div class="d-flex rounded flex-column justify-content-between w-100">
                            <div class="d-flex flex-column pt-2">
                                <h3 class="h6">{{ $m['title'] }}</h3>
                                <p class="text-justify text-muted small">{{ strip_tags($m['content']) }}</p>
                            </div>
                            <div class="text-left pb-2">
                                <a href="{{ $m['link'] }}" class="more fal fa-arrow-circle-left"></a>
                            </div>
                        </div>
                    </div>
                    @if(isset($menu[2]))
                        <?php $m = $menu[2]; ?>
                        <div class="d-flex grid5 bg-light rounded px-3 py-0">
                            <figure class="p-3 d-flex align-items-center">
                                <img src="{{ renderImage($m['image'], 90, 90) }}" alt="{{ $m['title'] }}">
                            </figure>
                            <div class="d-flex rounded flex-column justify-content-between w-100">
                                <div class="d-flex flex-column pt-2">
                                    <h3 class="h6">{{ $m['title'] }}</h3>
                                    <p class="text-justify text-muted small">{{ strip_tags($m['content']) }}</p>
                                </div>
                                <div class="text-left pb-2">
                                    <a href="{{ $m['link'] }}" class="more fal fa-arrow-circle-left"></a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            @if(isset($menu[3]))
                <?php $m = $menu[3]; ?>
                <div class="d-flex flex-grow-1 flex-column justify-content-center">
                    <div class="d-flex grid5 bg-light rounded px-3 py-0 mb-1">
                        <figure class="p-3 d-flex align-items-center">
                            <img src="{{ renderImage($m['image'], 90, 90) }}" alt="{{ $m['title'] }}">
                        </figure>
                        <div class="d-flex rounded flex-column justify-content-between w-100">
                            <div class="d-flex flex-column pt-2">
                                <h3 class="h6">{{ $m['title'] }}</h3>
                                <p class="text-justify text-muted small">{{ strip_tags($m['content']) }}</p>
                            </div>
                            <div class="text-left pb-2">
                                <a href="{{ $m['link'] }}" class="more fal fa-arrow-circle-left"></a>
                            </div>
                        </div>
                    </div>
                    @if(isset($menu[4]))
                        <?php $m = $menu[4]; ?>
                        <div class="d-flex grid5 bg-light rounded px-3 py-0">
                            <figure class="p-3 d-flex align-items-center">
                                <img src="{{ renderImage($m['image'], 90, 90) }}" alt="{{ $m['title'] }}">
                            </figure>
                            <div class="d-flex rounded flex-column justify-content-between w-100">
                                <div class="d-flex flex-column pt-2">
                                    <h3 class="h6">{{ $m['title'] }}</h3>
                                    <p class="text-justify text-muted small">{{ strip_tags($m['content']) }}</p>
                                </div>
                                <div class="text-left pb-2">
                                    <a href="{{ $m['link'] }}" class="more fal fa-arrow-circle-left"></a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    @include(includeTemplate('graphics.left-dots'))
    @include(includeTemplate('graphics.right-dots'))
</div>
@endif
