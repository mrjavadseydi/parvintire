<template>
    <div>
        <head-content :title="title" :buttons="buttons"></head-content>
        <div class="alert alert-success my-3" v-if="success.status" v-text="success.message"></div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <figure class="text-center">
                            <img class="rounded-circle shadow-sm" width="150" height="150px" :src="user.avatar" alt="">
                        </figure>
                        <div class="d-flex justify-content-center py-3">
                            <button class="btn btn-sm btn-outline-warning mx-1">شارژ کیف پول</button>
                            <button v-if="!user.mobile_verified_at" class="btn btn-sm btn-outline-info mx-1">تایید موبایل</button>
                            <button v-if="!user.email_verified_at" class="btn btn-sm btn-outline-info mx-1">تایید ایمیل</button>
                            <button class="btn btn-sm btn-outline-success mx-1">ورود</button>
                            <button class="btn btn-sm btn-outline-danger mx-1">مسدود کردن</button>
                        </div>
                        <div class="px-3">
                            <div class="d-flex  justify-content-between">
                                <h6>آخرین بازدید</h6>
                                <h6>25/5/شمسیت</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 pr-3 pr-md-0">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <input-ltr type="file" title="نام کاربری" name="username" error=""></input-ltr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import HeadContent from '../HeadContent.vue';

    export default {
        data() {
            return {
                title: 'ویرایش کاربر',
                buttonTitle: 'ذخیره',
                buttons: [
                    {
                        title: 'کاربران',
                        href: '/',
                        icon: 'fad fa-users',
                        type: 'primary'
                    },
                    {
                        title: 'مجوز ها',
                        href: '/',
                        icon: 'far fa-badge-check',
                        type: 'primary'
                    },
                    {
                        title: 'نقش ها',
                        href: '/',
                        icon: 'fad fa-user-tie',
                        type: 'primary'
                    },
                    {
                        title: 'فیلتر ها',
                        href: '/',
                        icon: 'fa fa-filter',
                        type: 'secondary'
                    }
                ],
                data : {
                    userLogin : '',
                    password : ''
                },
                errors : new Errors(),
                success : {
                    status : false,
                    message : ''
                },
                user: {}
            }
        },
        mounted() {
            axios.get(`/api/users/${this.$route.params.id}`)
                .then(response => {
                    this.user = response.data
                })
                .catch(error => {

                });
        },
        methods: {
            onSubmit() {
                this.buttonTitle = 'لطفا صبر کنید...';
                axios.post('/admin/users?vue'  , this.data)
                    .then(response => {
                        this.success = {
                            status : true,
                            message : response.data.message
                        }
                        this.data = {
                            userLogin : '',
                            password : ''
                        }
                    })
                    .catch(error => {
                        this.errors.record(error.response.data.errors);
                        this.buttonTitle = 'ذخیره';
                    });
            }
        },
        components: {
            HeadContent
        }
    }
</script>
