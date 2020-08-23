<template>
    <div>
        <head-content :title="title" :buttons="buttons"></head-content>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <figure class="text-center">
                            <img class="rounded-circle shadow-sm" width="150" height="150px" :src="data.avatar" alt="">
                        </figure>
                        <div class="d-flex justify-content-center py-3">
                            <button class="btn btn-sm btn-outline-warning mx-1">شارژ کیف پول</button>
                            <button v-if="!data.mobile_verified_at" class="btn btn-sm btn-outline-info mx-1">تایید موبایل</button>
                            <button v-if="!data.email_verified_at" class="btn btn-sm btn-outline-info mx-1">تایید ایمیل</button>
                            <button class="btn btn-sm btn-outline-success mx-1">ورود</button>
                            <button class="btn btn-sm btn-outline-danger mx-1">مسدود کردن</button>
                        </div>
                        <div class="px-3">
                            <div class="d-flex  justify-content-between">
                                <h6>آخرین بازدید</h6>
                                <h6>یک هفته پیش</h6>
                            </div>
                            <div class="d-flex  justify-content-between">
                                <h6>موجودی کیف پول</h6>
                                <h6 class="text-success">350,000 تومان</h6>
                            </div>
                            <div class="d-flex  justify-content-between">
                                <h6>تعداد مطالب</h6>
                                <h6>365</h6>
                            </div>
                            <div class="d-flex  justify-content-between">
                                <h6>تعداد نظرات</h6>
                                <h6>526</h6>
                            </div>
                            <div class="d-flex  justify-content-between">
                                <h6>تاریخ ایجاد</h6>
                                <h6>25 آذر 1398 ساعت 16:25</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 pr-3 pr-md-0">
                <form id="form" @submit.prevent="onSubmit" @keydown="keydown" class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <text-input-group name="mobile" v-model="data.mobile" :val="data.mobile" :error="errors.get('mobile')"  classes="ltr text-left" title="موبایل"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group name="email" v-model="data.email" :val="data.email" :error="errors.get('email')" title="ایمیل" classes="ltr text-left"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group v-model="data.username" :val="data.username" :error="errors.get('username')" title="نام کاربری" classes="ltr text-left"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group v-model="data.name" :val="data.name" :error="errors.get('name')" title="نام"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group v-model="data.family" :val="data.family" title="نام خانوادگی" :error="errors.get('family')"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group v-model="data.password" classes="ltr text-left" title="رمز عبور" :error="errors.get('password')"></text-input-group>
                            </div>
                            <div class="col-md-12">
                                <multi-select-group title="نقش ها" name="roles" :val="data.roles" :options="roles" :error="errors.get('password')"></multi-select-group>
                            </div>
                            <div class="col-md-4">
                                <select-gender-group :val="data.gender" :error="errors.get('gender')"></select-gender-group>
                            </div>
                            <div class="col-md-8">
                                <select-birthday-group :val="data.gender" :error="errors.get('gender')"></select-birthday-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group v-model="data.metas.phone" name="metas.phone" :val="data.metas.phone" classes="ltr text-left" title="تلفن ثابت" :error="errors.get('metas.phone')"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group v-model="data.metas.nationalCode" name="metas.nationalCode" :val="data.metas.nationalCode" classes="ltr text-left" title="کدملی" :error="errors.get('metas.nationalCode')"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group v-model="data.metas.postalCode" :val="data.metas.postalCode" classes="ltr text-left" title="کدپستی" name="metas.postalCode" :error="errors.get('metas.postalCode')"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group :val="data.province" classes="ltr text-left" title="استان" name="metas[province]" :error="errors.get('password')"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group :val="data.city" classes="ltr text-left" title="شهرستان" name="metas[city]" :error="errors.get('password')"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group :val="data.town" classes="ltr text-left" title="شهر" name="metas[town]" :error="errors.get('password')"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group :val="data.address" classes="ltr text-left" title="آدرس" name="metas[address]" :error="errors.get('password')"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group :val="data.bio" classes="ltr text-left" title="بیوگرافی" name="metas[bio]" :error="errors.get('password')"></text-input-group>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button :disabled="disabled" class="btn btn-success">{{ buttonTitle }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import TextInputGroup from "../forms/group/TextInputGroup";
    import SelectGenderGroup from "../forms/group/SelectGenderGroup";
    import HeadContent from '../HeadContent.vue';
    import MultiSelectGroup from "../forms/group/MultiSelectGroup";
    import SelectBirthdayGroup from "../forms/group/SelectBirthdayGroup";
    import {Errors} from "../../errors";

    import VueIziToast from 'vue-izitoast';
    import 'izitoast/dist/css/iziToast.min.css';
    Vue.use(VueIziToast);

    export default {
        components: {
            HeadContent,
            TextInputGroup,
            SelectGenderGroup,
            MultiSelectGroup,
            SelectBirthdayGroup
        },
        data() {
            return {
                title: 'ویرایش کاربر',
                buttonTitle: 'ذخیره',
                disabled: false,
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
                errors: new Errors(),
                data: {},
                roles: []
            }
        },
        mounted() {
            axios.get(`/api/v1/users/${this.$route.params.id}`)
                .then(response => {
                    this.data = response.data;
                })
                .catch(error => {

                });
            axios.get('/api/roles')
                .then(response => {
                    this.roles = response.data;
                })
                .catch(error => {

                });
        },
        methods: {
            onSubmit(e) {
                this.disabled = true;
                this.buttonTitle = 'لطفا صبر کنید...';
                const formData = new FormData(document.getElementById('form'));
                axios.put(`/admin/users/${this.$route.params.id}` , this.data )
                    .then(result => {
                        var response = result.data;
                        if (response.status == 'success') {
                            this.$toast.success(response.message);
                        } else {
                            this.$toast.error(response.message, 'خطا');
                        }
                        this.buttonTitle = 'ذخیره';
                        this.disabled = false;
                    })
                    .catch(error => {
                        this.errors.set(error.response.data.errors);
                        this.buttonTitle = 'ذخیره';
                        this.disabled = true;
                    });
            },
            keydown(event) {
                var field = event.target.name;
                this.errors.clear(field);
                if (!this.errors.any()) {
                    this.disabled = false;
                }
            }
        }
    }
</script>
