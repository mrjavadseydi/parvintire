<?php
    $key = generateUniqueToken();
    $menu = getMenu(false, $menuId);
?>
@if($menu != null)
    <div class="position-relative circle-menu circle-{{ $key }}">
        <div class="square">
            <?php $i = 1;?>
            @foreach($menu as $item)
                @if($i <= 6)
                    <div index="{{ $i }}" class="item item-{{ $i }} {{ $i == 1 ? 'active' : '' }}">
                        <i class="{{ $item['icon'] }}"></i>
                    </div>
                @endif
                <?php $i++;?>
            @endforeach
        </div>
        <?php $i = 1;?>
        @foreach($menu as $item)
            @if($i <= 6)
                <div class="content content-{{ $i }} {{ $i == 1 ? 'active' : 'd-none' }}">
                    <div class="d-flex flex-wrap justify-content-center flex-fill">
                        <h5>{{ $item['title'] }}</h5>
                        <h6>{!! $item['content'] !!}</h6>
                    </div>
                </div>
            @endif
            <?php $i++;?>
        @endforeach
    </div>
    <script>
        $(document).on('click', '.circle-{{ $key }} .item', function () {
            $('.circle-{{ $key }} .item').removeClass('active');
            $('.circle-{{ $key }} .content').addClass('d-none');
            $(this).addClass('active');
            $('.circle-{{ $key }} .content-'+$(this).attr('index')).removeClass('d-none');
        });
    </script>
@endif
