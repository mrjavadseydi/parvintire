<?php
    $logo = getOptionImage('digishopLogo');
?>
<header>
    <div class="container-fluid px-3 px-sm-5 d-flex">
        <a target="{{ $logo['target'] }}" href="{{ $logo['href'] }}">
            <figure>
                <img class="logo" src="{{ $logo['src'] }}" alt="{{ $logo['alt'] }}">
            </figure>
        </a>
        <nav class="flex-fill mr-3">
            <ul class="d-inline-block d-md-none">
                <li class="menu-toggle">
                    <a>
                        <i class="fal fa-bars icon-menu"></i>
                    </a>
                </li>
                <li>
                    <a href="{{ url('profile') }}">
                        <span>حساب کاربری من</span>
                    </a>
                </li>
            </ul>
            <ul class="main-menu">
                <a target="{{ $logo['target'] }}" href="{{ $logo['href'] }}">
                    <figure class="d-block d-md-none text-center py-3 border-bottom">
                        <img height="60px" src="{{ $logo['src'] }}" alt="{{ $logo['alt'] }}">
                    </figure>
                </a>
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
                                                            <i class="fa fa-angle-left"></i>
                                                            <span>{{ $menu3['title'] }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <figure>
                                            <img width="100%" class="rounded" src="{{ url($menu2['image']) }}" alt="{{ $menu2['title'] }}">
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
                        <li class="main-li my-account">
                            <a href="#">
                                <i class=""></i>
                                <span>حساب کاربری من</span>
                            </a>
                            <i class="dropdown-icon fal fa-angle-down align-middle"></i>
                            <div class="dropdown">
                                <a href="{{ route('profile') }}">
                                    <figure class="d-flex">
                                        <img class="rounded-circle" width="50" height="50" src="{{ auth()->user()->avatar() }}" alt="{{ auth()->user()->name() }}">
                                        <div class="d-flex flex-column justify-content-around">
                                            <figcaption class="text-muted">{{ auth()->user()->name() }}</figcaption>
                                            <figcaption>مشاهده حساب کاربری</figcaption>
                                        </div>
                                    </figure>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{ route('profile.orders') }}">
                                            <i class="fa fa-shopping-basket"></i>
                                            <span>سفارشات من</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('profile.favorites') }}">
                                            <i class="fa fa-heart"></i>
                                            <span>علاقه مندی ها</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('profile.password') }}">
                                            <i class="fa fa-lock"></i>
                                            <span>تغییر رمز عبور</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}">
                                            <i class="fa fa-sign-out"></i>
                                            <span>خروج از حساب کاربری</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}">
                                <span>ورود و ثبت نام</span>
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="mobile-icon icons d-inline-block d-md-none">

                    @if(auth()->check())
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
                        </span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>
