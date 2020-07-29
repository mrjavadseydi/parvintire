<?php
$menu = getMenu(false, $menuId);
?>
@if($menu != null)
    <div class="menu-skill position-relative py-3">
        <div class="container pt">
            <ul class="d-flex justify-content-around justify-content-md-between flex-wrap flex-md-nowrap">
                @foreach($menu as $item)
                    <li class="mb-3 mb-md-0">
                        <a class="d-flex h-100 justify-content-center align-items-center" {{ echoAttributes($item['attributes']) }} href="{{ $item['link'] }}">
                            <figure class="text-center">
                                <img class="bg-white rounded" src="{{ renderImage($item['image'], 100, 100) }}" alt="{{ $item['title'] }}">
                                <figcaption class="text-center mt-3">{{ $item['title'] }}</figcaption>
                            </figure>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
