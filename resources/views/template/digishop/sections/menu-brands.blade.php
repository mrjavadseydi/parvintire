<?php
    $menu = getMenu(false, $menuId);
?>
@if($menu != null)
    <div class="menu-brands position-relative py-3">
        @include(includeTemplate('divider.1'), ['title' => $title])
        <div class="container pt">
            <ul class="d-flex justify-content-around justify-content-md-between flex-wrap flex-md-nowrap">
                @foreach($menu as $item)
                    <li class="mb-3 mb-md-0">
                        <a {{ echoAttributes($item['attributes']) }} href="{{ $item['link'] }}">
                            <figure>
                                <img class="bg-white rounded" src="{{ renderImage($item['image'], 150, 150) }}" alt="{{ $item['title'] }}">
                            </figure>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
