<template>

    <header>

        <i @click="menu" class="menu fa fa-bars"></i>

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
                laravel: Laravel,
                openSidebar: false
            }
        },
        methods: {
            menu() {
                if(this.openSidebar) {
                    document.getElementById('content').classList.remove('sidebar-open-content');
                    document.getElementById('sidebar-parent').classList.add('d-none');
                    document.getElementsByClassName('sidebar')[0].classList.remove('open');
                    this.openSidebar = false;
                } else {
                    document.getElementById('content').classList.add('sidebar-open-content');
                    document.getElementsByClassName('sidebar')[0].classList.add('open');
                    document.getElementById('sidebar-parent').classList.remove('d-none');
                    this.openSidebar = true;
                }
            }
        }
    }
</script>
