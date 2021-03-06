<?php
    $menu = getMenu(false, $menuId)
?>
@if(isset($menu[0]))
    <?php
        $m = $menu[0];
    ?>
    <div class="resume">
        <div class="container">
            <div class="row">
                <div class="col-md-6 d-flex align-items-center">
                    <div class="d-flex">
                        <div class="w-50 d-flex flex-column justify-content-center align-items-center ml-3">
                            <div link="{{ $m['link'] }}" class="resume-item">
                                <i class="{{ $m['icon'] }} blue"></i>
                                <h5 class="py-2">{{ $m['title'] }}</h5>
                                <div class="text-muted">{{ htmlentities(mb_substr($m['content'], 0, 100)) }}...</div>
                                <div class="this-content d-none">{!! $m['content'] !!}</div>
                            </div>
                        </div>
                        @if(isset($menu[1]))
                            <div class="w-50 d-flex flex-column justify-content-center align-items-center">
                                <div link="{{ $menu[1]['link'] }}" class="resume-item mb-3">
                                    <i class="{{ $menu[1]['icon'] }} pink"></i>
                                    <h5 class="py-2">{{ $menu[1]['title'] }}</h5>
                                    <div class="text-muted">{{ htmlentities(mb_substr($menu[1]['content'], 0, 100)) }}</div>
                                    <div class="this-content d-none">{!! $menu[1]['content'] !!}</div>
                                </div>
                                @if(isset($menu[2]))
                                    <div link="{{ $menu[2]['link'] }}" class="resume-item mb-3">
                                        <i class="{{ $menu[2]['icon'] }} green"></i>
                                        <h5 class="py-2">{{ $menu[2]['title'] }}</h5>
                                        <div class="text-muted text-justify">{{ htmlentities(mb_substr($menu[2]['content'], 0, 100)) }}</div>
                                        <div class="this-content d-none">{!! $menu[2]['content'] !!}</div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <h3 class="iransansDNBold resume-title">{{ $m['title'] }}</h3>
                    <div class="text-muted h5 text-justify py-2 resume-content">{!! $m['content'] !!}</div>
                    <div class="text-left">
                        <a href="{{ $m['link'] }}" class="btn btn-second resume-link">?????????? ????????</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        timeout = null;
        $(document).on('mouseenter', '.resume-item', function () {
            el = $(this);
            timeout = setTimeout(function () {
                $('.resume-link').attr('href', el.attr('link'));
                $('.resume-title').text(el.find('h5').text());
                $('.resume-content').html(el.find('.this-content').html());
            }, 200);
        });
        $(document).on('mouseleave', '.resume-item', function () {
            clearTimeout(timeout)
        });
    </script>
@endif
