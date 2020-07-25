<header>

    <i class="menu fa fa-bars"></i>

    <a target="_blank" class="jgh-tooltip" title="بریم به سایت" href="{{ url('') }}">
        <i class="fa fa-home"></i>
        <h1>لارابیس</h1>
    </a>

    <div class="account">

        <a>
            <i class="fad fa-angle-down"></i>
            <img height="50" width="50" src="{{ auth()->user()->avatar() }}" alt="">
        </a>

        <div class="dropdown">
            <ul>
                <li>
                    <a href="">
                        <i class="fad fa-envelope"></i>
                        <span>ایمیل فعال نیست</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <i class="fad fa-mobile"></i>
                        <span>موبایل فعال نیست</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <i class="fad fa-user-circle"></i>
                        <span>حساب کاربری</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}">
                        <i class="fad fa-sign-out-alt"></i>
                        <span>خروج</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="name">
            <span>جواد قلی پور</span>
            <small>0 تومان<a title="شارژ کیف پول" class="jgh-tooltip fa fa-plus-circle"></a></small>
        </div>

    </div>

    <div class="left">

        <ul class="badges">
            <li>
                <a title="نسخه جدید در دسترس می باشد" class="jgh-tooltip">
                    <i class="fad fa-sync-alt"></i>
                    <small class="orange">&nbsp;</small>
                </a>
            </li>
            <li>
                <a title="نظرات" class="jgh-tooltip">
                    <i class="fad fa-comments"></i>
                    <small class="red animated shake">&nbsp;</small>
                </a>
            </li>
            <li title="درخواست های پشتیبانی" class="jgh-tooltip">
                <a>
                    <i class="fad fa-user-headset"></i>
                    <small class="green">&nbsp;</small>
                </a>
            </li>
        </ul>

        <i onclick="$('.badges').slideToggle();" class="more fal fa-ellipsis-v"></i>
        <i class="more-badge"></i>

    </div>

</header>

<script>

    $(document).on('click', '.more', function () {
        $(this).toggleClass('active');
    });
    
</script>
