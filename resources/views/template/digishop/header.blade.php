<?php
    $logo = getOptionImage('digishopLogo');
?>
<header>
    <div class="container-fluid px-3 px-sm-5">
        <div class="top d-flex align-items-center justify-content-between py-2">
            <a target="{{ $logo['target'] }}" href="{{ $logo['href'] }}">
                <figure>
                    <img class="logo" src="{{ $logo['src'] }}" alt="{{ $logo['alt'] }}">
                </figure>
            </a>
            <div class="search">
                <form action="{{ route('search') }}">
                    <input placeholder="دنبال چی میگردی؟" type="text" name="q">
                    <input type="hidden" name="postType" value="products">
                    <button class="far fa-search"></button>
                </form>
            </div>
            <div class="icons d-none d-md-inline-block">
                <a href="{{ url('profile/favorites') }}" class="align-middle">
                    <i class="fal fa-heart"></i>
                </a>
                <span class="cart-box align-middle mr-2 cart-view-1">
                    @include(includeTemplate('order.cart-header'))
                </span>
            </div>
        </div>
        <nav>
            <ul class="d-inline-block d-md-none">
                <li class="menu-toggle">
                    <a>
                        <i class="fal fa-bars icon-menu"></i>
                    </a>
                </li>
            </ul>
            <ul class="main-menu">
                <figure class="d-block d-md-none text-center py-3 border-bottom">
                    <img height="60px" src="{{ picsum(180, 60) }}" alt="">
                </figure>
                @foreach(getMenu(false, getOption('digishopMainMenu')) as $menu)
                    <li class="main-li">
                        <a href="{{ $menu['link'] }}">
                            <i class="{{ $menu['icon'] }}"></i>
                            <span>{{ $menu['title'] }}</span>
                        </a>
                        @if(isset($menu['list']))
                            <i class="dropdown-icon fal fa-angle-down align-middle"></i>
                            <div class="dropdown d-flex justify-content-between">
                                @foreach($menu['list'] as $menu2)
                                    <div class="list px-2">
                                        <h2 class="h6 py-1"><a href="{{ $menu2['link'] }}">{{ $menu2['title'] }}</a></h2>
                                        @if(isset($menu2['list']))
                                            <ul class="py-1">
                                                @foreach($menu2['list'] as $menu3)
                                                    <li>
                                                        <a href="{{ $menu3['link'] }}">
                                                            <i class="fa fa-home"></i>
                                                            <span>{{ $menu3['title'] }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <figure>
                                            <img width="100%" class="rounded" src="{{ $menu2['image'] }}" alt="{{ $menu2['title'] }}">
                                        </figure>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="left">
                <ul class="d-none d-md-inline-block">
                    @if(auth()->check())
                        @can('controlPanel')
                            <li>
                                <a href="{{ url('admin') }}">
                                    <span>پنل مدیریت</span>
                                </a>
                            </li>
                        @endcan
                        <li>
                            <a class="" href="{{ url('profile') }}">
                                <span class="ml-1">{{ auth()->user()->name() }}</span>
                                <img class="rounded-circle border" width="30" src="{{ auth()->user()->avatar() }}" alt="{{ auth()->user()->name() }}">
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}">
                                <span>ورود و ثبت نام</span>
                            </a>
                        </li>
                    @endif

                </ul>
                <div class="mobile-icon  icons d-inline-block d-md-none">

                    @if(auth()->check())
                        <a class="" href="{{ url('profile') }}">
                            <span>{{ auth()->user()->name() }}</span>
                        </a>
                        @can('controlPanel')
                            <a href="{{ url('admin') }}">
                                <span class="align-middle">
                                    <i class="fal fa-tachometer-alt"></i>
                                </span>
                            </a>
                        @endcan
                    @else
                        <a href="{{ route('login') }}">
                            <span class="align-middle">
                                <i class="fal fa-user"></i>
                            </span>
                        </a>
                    @endif
                    <a href="{{ url('cart') }}">
                        <span class="cart-box align-middle mr-2">
                            <i class="fal fa-shopping-bag"></i>
                            <span class="badge"></span>
                        </span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>
