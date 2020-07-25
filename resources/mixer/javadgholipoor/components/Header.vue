<template>

    <header>

        <i class="menu fa fa-bars"></i>

        <a target="_blank" class="jgh-tooltip" title="بریم به سایت" href="/">
            <i class="fa fa-home"></i>
            <h1>لارابیس</h1>
        </a>

        <div class="account">

            <a>
                <i class="fad fa-angle-down"></i>
                <img height="50" width="50" :src="laravel.user.avatar" :alt="laravel.user.name">
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
                        <a href="/admin/profile">
                            <i class="fad fa-user-circle"></i>
                            <span>حساب کاربری</span>
                        </a>
                    </li>
                    <li>
                        <a :href="`${laravel.logout}`">
                            <i class="fad fa-sign-out-alt"></i>
                            <span>خروج</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="name">
                <span>{{ laravel.user.name }}</span>
                <small>{{ laravel.wallet }} تومان<a title="شارژ کیف پول" class="jgh-tooltip fa fa-plus-circle"></a></small>
            </div>

        </div>

    </header>

</template>

<script>
    export default {
        data() {
            return {
                laravel: Laravel
            }
        }
    }
</script>
