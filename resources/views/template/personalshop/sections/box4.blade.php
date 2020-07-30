<?php
    $menu = getMenu(false, $menuId);
?>
@if($menu != null)
    <div class="position-relative overflow-hidden container-fluid px-6 py-5">
        <div class="row">
            @foreach($menu as $item)
                <div class="col-md-3 col-6 px-2 px-md-3 mb-3 mb-md-0">
                    <a {{ echoAttributes($item['attributes']) }} href="{{ $item['link'] }}" class="box4">
                        <figure>
                            <img width="100%" src="{{ renderImage($item['image'], 300, 280) }}" alt="{{ $item['title'] }}">
                            <figcaption>{{ $item['title'] }}</figcaption>
                        </figure>
                    </a>
                </div>
            @endforeach
        </div>
        @include(includeTemplate('graphics.right-circle'))
    </div>
@endif
