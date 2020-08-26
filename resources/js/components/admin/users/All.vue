<template>
    <div>
        <head-content :title="pageTitle" :buttons="buttons"></head-content>
        <input @keyup="search" type="text" class="form-control rounded-1 mb-2" placeholder="جستجو بر اساس شناسه،‌ نام، نام خانوادگی،‌ موبایل، ایمیل، نام کاربری">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table>
                        <thead>
                        <tr>
                            <th></th>
                            <th>نام</th>
                            <th>موبایل / ایمیل</th>
                            <th>نقش‌ها</th>
                            <th>آخرین‌بازدید</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in data.data">
                                <td>
                                    <img class="rounded-circle" width="30" height="30" :src="user.avatar">
                                    <small v-text="`#${user.id}`"></small>
                                </td>
                                <td v-text="user.name ? user.name + ' ' + user.family : '-'"></td>
                                <td>
                                    <span class="d-block text-center" v-text="user.mobile ? user.mobile : ''"></span>
                                    <span class="d-block text-center" v-text="user.email ? user.email : ''"></span>
                                </td>
                                <td></td>
                                <td></td>
                                <td style="min-width: 170px;">
                                    <router-link :to="`/admin/users/${user.id}/edit`">
                                        <a class="jgh-tooltip fa fa-edit btn user-card-btn btn-success text-white h5 m-0" title="ویرایش"></a>
                                    </router-link>
                                    <a :href="`/switch/user/${user.id}`" class="jgh-tooltip fa fa-sign-in btn user-card-btn btn-primary text-white h5 m-0" title="ورود"></a>
                                    <a v-show="!user.email_verified_at" class="jgh-tooltip fa fa-envelope btn user-card-btn btn-warning text-white h5 m-0" title="ایمیل تایید نشده"></a>
                                    <a v-show="!user.mobile_verified_at" class="jgh-tooltip fa fa-mobile btn user-card-btn btn-warning text-white h5 m-0" title="موبایل تایید نشده"></a>
                                    <span @click="remove($event, user)" class="jgh-tooltip fa fa-trash btn user-card-btn btn-danger h5 m-0" title="حذف"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <pagination :data="data" :result="paginate"></pagination>
    </div>
</template>

<script>
    import HeadContent from "../HeadContent";
    import Pagination from "../../../../js/components/vendor/Pagination";

    var cancel;
    var CancelToken = axios.CancelToken;

    export default {
        data() {
            return {
                data: {},
                pageTitle: 'کاربران',
                buttons: [
                    {
                        title: 'افزودن کاربر',
                        to: '/admin/users/create',
                        icon: 'far fa-plus',
                        type: 'secondary'
                    },
                    {
                        title: 'مجوز ها',
                        to: '/admin/permissions',
                        icon: 'far fa-badge-check',
                        type: 'secondary'
                    },
                    {
                        title: 'افزودن نقش',
                        to: '/admin/roles/create',
                        icon: 'far fa-plus',
                        type: 'secondary'
                    },
                    {
                        title: 'نقش ها',
                        to: '/admin/roles',
                        icon: 'fad fa-user-tie',
                        type: 'secondary'
                    }
                ]
            }
        },
        created() {
            this.$parent.headContent({
                title: this.pageTitle
            });
            axios.get('/api/v1/users?count=16').then(response => {
                this.data = response.data;
            }).catch(error => console.log(error));
        },
        methods: {
            paginate(page) {
                window.scrollTo({top: 0, behavior: 'smooth'});
                if (typeof page === 'undefined') page = 1;
                axios.get('/api/v1/users?count=16&page=' + page)
                    .then(response => this.data = response.data)
                    .catch(error => console.log(error));
            },
            search(event) {

                if (cancel != undefined) {
                    cancel();
                }

                var q = event.target.value;
                const request = axios.CancelToken.source();
                axios.get('/api/v1/users?count=16&q=' + q, {
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c;
                    })
                })
                .then(response => this.data = response.data)
                .catch(error => console.log(error));
            },
            remove(event, item) {
                var name = item.fullname;
                var message = "آیا از حذف " + name + " اطمینان دارید؟";
                this.$toast.show(message, "", {
                    backgroundColor: 'rgb(255 162 162)',
                    progressBarColor: 'rgb(255 104 104)',
                    displayMode: 'once',
                    buttons: [
                        ['<button>حذف</button>', function (instance, toast) {
                            axios.delete('/api/v1/users/' + item.id)
                            .then(function (output) {
                                var response = output.data;
                                if (response.status == 'success') {
                                    event.target.parentElement.parentElement.remove();
                                } else {
                                    alert('شما اجازه انجام این کار را ندارید');
                                }
                            }).catch(error => console.log(error));
                            instance.hide({
                                transitionOut: 'fadeOutUp'
                            }, toast, 'buttonName');
                        }, true], // true to focus
                        ['<button>لغو</button>', function (instance, toast) {
                            instance.hide({
                                transitionOut: 'fadeOutUp'
                            }, toast, 'buttonName');
                        }]
                    ]
                });
            }
        },
        components: {
            HeadContent,
            Pagination
        }
    }
</script>

<style>
    .w-24 {
        width: 24%;
    }
    .user-card-btn {
        width: 35px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        padding: 0;
     }
</style>
