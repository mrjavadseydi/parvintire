<header>

    <a class="logo" href="">
        <span class="logo-lg none-768">پنل مدیریت</span>
        <span class="logo-min block-768 none-576">پنل</span>
    </a>

    <nav>

        <ul>

            <li class="sidebar-toggle dn inline-block-768"><a><i class="icon-menu5"></i></a></li>
            <li class="sidebar-toggle-close none-768"><a><i class="icon-menu5"></i></a></li>

            <li>
                @php
                $user = auth()->user();
                @endphp
                <a>
                    <img class="circle" src="{{ $user->avatar() }}" alt="{{ $user->name() }}">
                    <span class="none-992">{{ $user->name() }}</span>
                </a>
                <ul class="dropdown">
                    <div class="user">
                        @include('modals.default.authBox')
                    </div>
                </ul>
            </li>

            <li>
                <?php
                $img = getOptionImage('adminDefaultLogo');
                ?>
                <a target="{{ $img['target'] }}" href="{{ $img['href'] }}">
                    <img style="max-width: 100%;" src="{{ $img['src'] }}" alt="{{ $img['alt'] }}">
                    <span class="none-992">{{ siteName() }}</span>
                </a>
            </li>

            <?php
            try {
                // $sms = json_decode(sms()->getCredit());
                $sms = (object)[
                    'credit' => sms()->getCredit(),
                    'currency' => 'ریال',
                ];
            ?>
                <li>
                    <a href="#">
                        <i class="icon-message"></i>
                        <span>{{ number_format($sms->credit) }} {{ $sms->currency }}</span>
                    </a>
                </li>
            <?php
            } catch (Exception $err) {
            }
            ?>

            {{-- @foreach (getMenu('defaultAdminTopHeader') as $menu)
                <li>
                    <a {{ echoAttributes($menu['attributes']) }} href="{{ $menu['link'] }}">
                        <i class="{{ $menu['icon'] }}"></i>
                        <span>{{ $menu['title'] }}</span>
                    </a>
                </li>
            @endforeach --}}

            @if(session()->has('switcher'))
                <li>
                    <a href="{{ url('switch/user/' . session()->get('switchTo')) }}">
                        <i class="icon-settings_backup_restore"></i>
                        <span>بازگشت به مدیریت</span>
                    </a>
                </li>
            @endif

        </ul>

        <div class="notifications">

{{--            <div class="notify notify-red alert-notification-toggle">--}}
{{--                <i class="icon-notifications"></i>--}}
{{--                <span class="pulse red active"></span>--}}
{{--            </div>--}}

            <?php
            $checkForUpdates = checkForUpdates();
            if (isset($checkForUpdates['version'])) {
                $version = getVersion();
                if ($version != 'install') {
                    if ($version != $checkForUpdates['version']) {
                    ?>
                    <a href="{{ route('larabase.update') }}" class="notify notify-orange message-notification-toggle">
                        <i class="icon-system_update"></i>
                        <span class="pulse red active"></span>
                    </a>
                    <?php
                    }
                }
            }
            ?>

            @if(\LaraBase\Comments\Models\Comment::comments()->where('status', '1')->exists())
                <a href="{{ route('admin.comments.index') }}?status=1" class="notify notify-green message-notification-toggle">
                    <i class="icon-bubbles"></i>
                    <span class="pulse green active"></span>
                </a>
            @endif

            {{-- @if(\LaraBase\Comments\Models\Comment::tickets()->where('status', '1')->exists())
                <a href="{{ route('admin.tickets.index') }}?status=1" class="notify notify-green message-notification-toggle">
                    <i class="icon-question"></i>
                    <span class="pulse red active"></span>
                </a>
            @endif --}}

{{--            @include('admin.notifications.message')--}}
{{--            @include('admin.notifications.alert')--}}

        </div>

    </nav>


</header>

{{--<div class="flout-setting">--}}
    {{--<i class="icon-cog spin"></i>--}}
{{--</div>--}}
