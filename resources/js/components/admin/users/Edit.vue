<template>
    <div>
        <head-content :title="pageTitle" :buttons="buttons"></head-content>
        <div class="row">
            <div class="col-md-3 pl-3 pl-md-0">
                <div class="card">
                    <div class="card-body text-center">
                        <div v-if="data.block" class="alert alert-danger text-center">
                            <span>حساب کاربری مسدود شده است</span>
                            <span @click="block" class="btn btn-success mt-3">آزاد سازی حساب کاربری</span>
                        </div>
                        <div class="d-flex">
                            <figure class="text-center position-relative d-inline-block">
                                <img class="rounded-circle shadow" width="100" height="100" :src="data.avatar">
                                <upload @before="profileBefore" @complete="completeProfile" uploadKey="profile" multiple="true">
                                    <i class="profile-image fad fa-camera-retro"></i>
                                </upload>
                            </figure>
                            <div class="flex-fill d-flex flex-column justify-content-center pr-3">
                                <div class="d-flex justify-content-between">
                                    <small>{{ data.name }} {{ data.family }} <small :class="data.online ? 'text-success' : 'text-danger'">({{ data.online ? 'آنلاین' : 'آفلاین' }})</small></small>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small>بازدید</small>
                                    <small v-text="data.lastSeen"></small>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small>ایجاد</small>
                                    <small>{{  data.created }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center py-3">
                            <button @click="verify('mobile')" v-if="!data.mobile_verified_at" class="btn btn-sm btn-warning mx-1">تایید موبایل</button>
                            <button @click="verify('mobile')" v-if="data.mobile_verified_at" class="btn btn-sm btn-success mx-1 fal fa-mobile" title="موبایل تایید شده است"></button>
                            <button @click="verify('email')" v-if="!data.email_verified_at" class="btn btn-sm btn-warning mx-1">تایید ایمیل</button>
                            <button @click="verify('email')" v-if="data.email_verified_at" class="btn btn-sm btn-success mx-1 fal fa-envelope" title="ایمیل تایید شده است"></button>
                            <a :href="`/switch/user/${data.id}`" class="btn btn-sm btn-primary mx-1 fal fa-sign-in" title="ورود با من"></a>
                            <button @click="block" v-if="!data.block" class="btn btn-sm btn-danger mx-1 fal fa-ban" title="مسدودکردن"></button>
                        </div>
                        <div class="px-3">
                            <ul class="list">
                                <li>
                                    <a href="#">
                                        <i class="fal fa-shopping-bag"></i>
                                        <span>سفارش ها</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fal fa-shopping-bag"></i>
                                        <span>تراکنش ها</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fal fa-shopping-bag"></i>
                                        <span>تراکنش های کیف پول</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fal fa-shopping-bag"></i>
                                        <span>بازدید ها</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fal fa-shopping-bag"></i>
                                        <span>نظرات</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fal fa-shopping-bag"></i>
                                        <span>لایک ها</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fal fa-shopping-bag"></i>
                                        <span>تیکت ها</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fal fa-shopping-bag"></i>
                                        <span>آدرس ها</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pl-3 pl-md-0 my-3 my-md-0">
                <form id="user-form" class="card" @submit.prevent="onSubmitUser" @change="keydown" @keydown="keydown">
                    <div class="card-header">
                        <span>ویرایش اطلاعات</span>
                    </div>
                    <div class="card-body scroll">
                        <div class="row">
                            <div class="col-md-4 col-6">
                                <text-input-group v-model="data.name" :val="data.name" :error="errors.get('name')" title="نام"></text-input-group>
                            </div>
                            <div class="col-md-4 col-6">
                                <text-input-group v-model="data.family" :val="data.family" title="نام خانوادگی" :error="errors.get('family')"></text-input-group>
                            </div>
                            <div class="col-md-4 col-6">
                                <text-input-group v-model="data.username" :val="data.username" :error="errors.get('username')" title="نام کاربری" classes="ltr text-left"></text-input-group>
                            </div>
                            <div class="col-md-4 col-6">
                                <text-input-group name="mobile" v-model="data.mobile" :val="data.mobile" :error="errors.get('mobile')"  classes="ltr text-left" title="موبایل"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group name="email" v-model="data.email" :val="data.email" :error="errors.get('email')" title="ایمیل" classes="ltr text-left"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group v-model="data.password" classes="ltr text-left" title="رمز عبور" :error="errors.get('password')"></text-input-group>
                            </div>
                            <div class="col-md-4 col-6">
                                <text-input-group v-model="data.metas.phone" :val="data.metas.phone" name="metas.phone" classes="ltr text-left" title="تلفن ثابت" :error="errors.get('metas.phone')"></text-input-group>
                            </div>
                            <div class="col-md-4 col-6">
                                <text-input-group v-model="data.metas.nationalCode" name="metas.nationalCode" :val="data.metas.nationalCode" classes="ltr text-left" title="کدملی" :error="errors.get('metas.nationalCode')"></text-input-group>
                            </div>
                            <div class="col-md-4">
                                <text-input-group v-model="data.metas.postalCode" :val="data.metas.postalCode" classes="ltr text-left" title="کدپستی" name="metas.postalCode" :error="errors.get('metas.postalCode')"></text-input-group>
                            </div>
                            <div class="col-md-7">
                                <multi-select-group v-model="data.roles" :val="data.roles" title="نقش ها" name="roles" :options="roles" :error="errors.get('roles')"></multi-select-group>
                            </div>
                            <div class="col-md-2 col-6 px-3 px-md-0">
                                <select-gender-group v-model="data.gender" :val="data.gender" :error="errors.get('gender')"></select-gender-group>
                            </div>
                            <div class="col-md-3 col-6">
                                <date-group title="تاریخ تولد" classes="ltr text-left" v-model="data.birthday" :val="data.birthday" :error="errors.get('gender')" id="birthday"></date-group>
                            </div>
                            <div class="col-md-12">
                                <location-group
                                    col="col-md-4"
                                    :province-val="data.metas.provinceId" province-name="metas.provinceId" :province-error="errors.get('metas.provinceId')"
                                    :city-val="data.metas.cityId" city-name="metas.cityId" :city-error="errors.get('metas.cityId')"
                                    :town-val="data.metas.townId" town-name="metas.townId" :town-error="errors.get('metas.townId')"
                                    with-town="true"
                                    @result="onLocationGroupResult"
                                ></location-group>
                            </div>
                            <div class="col-md-12">
                                <text-area-input-group v-model="data.metas.address" :val="data.metas.address" title="آدرس" name="metas.address" :error="errors.get('metas.address')"></text-area-input-group>
                            </div>
                            <div class="col-md-12">
                                <text-area-input-group v-model="data.metas.biography" :val="data.metas.biography" title="بیوگرافی" name="metas.biography" :error="errors.get('metas.biography')"></text-area-input-group>
                            </div>
                        </div>
                    </div>
                    <div class="py-2 px-3">
                        <button :disabled="disabled" class="btn btn-success py-2 px-3">{{ buttonTitle }}</button>
                    </div>
                </form>
            </div>
            <div class="col-md-3">
                <form class="card border-warning" id="wallet-form" @submit.prevent="onSubmitWallet" @keydown="keydown">
                    <div class="card-header bg-warning">
                        <span>شارژ کیف پول</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col text-left">
                                <span>موجودی : <b class="text-success">{{ wallet.credit }} {{ currency }}</b></span>
                                <span v-html="walletNewCredit"></span>
                            </div>
                            <div class="col-md-12">
                                <price-input-group v-model="wallet.price" classes="ltr text-left border-warning" :title="`مبلغ به ${currency}`" :error="walletErrors.get('price')"></price-input-group>
                            </div>
                            <div class="col-md-12">
                                <text-input-group v-model="wallet.description" classes="border-warning" :val="wallet.description" title="توضیحات" :error="walletErrors.get('description')"></text-input-group>
                            </div>
                        </div>
                        <div>
                            <button :disabled="walletButtonDisable" class="btn btn-warning py-2 px-3" v-text="walletButtonTitle">شارژ کیف پول</button>
                        </div>
                    </div>
                </form>
                <user-components :user="data"></user-components>
            </div>
        </div>
    </div>
</template>

<script>

    import HeadContent from '../HeadContent.vue';
    import DateGroup from "../../../../js/components/forms/group/DateGroup";
    import TextInputGroup from "../../../../js/components/forms/group/TextInputGroup";
    import SelectGenderGroup from "../../../../js/components/forms/group/SelectGenderGroup";
    import MultiSelectGroup from "../../../../js/components/forms/group/MultiSelectGroup";
    import TextAreaInputGroup from "../../../../js/components/forms/group/TextAreaInputGroup";
    import PriceInputGroup from "../../../../js/components/forms/group/PriceInputGroup";
    import LocationGroup from "../../../../js/components/forms/group/LocationGroup";
    import Upload from "../../vendor/Upload";
    import {Errors} from "../../../../js/errors";

    import VueIziToast from 'vue-izitoast';
    import 'izitoast/dist/css/iziToast.min.css';
    Vue.use(VueIziToast);

    Vue.component("user-components", () => import("../../../../views/template/"+window.templateTheme+"/admin/User"));

    export default {
        components: {
            HeadContent,
            TextInputGroup,
            SelectGenderGroup,
            MultiSelectGroup,
            DateGroup,
            TextAreaInputGroup,
            PriceInputGroup,
            LocationGroup,
            Upload
        },
        data() {
            return {
                pageTitle: 'ویرایش کاربر',
                buttonTitle: 'ذخیره',
                currency: 'ریال',
                disabled: false,
                buttons: [
                    {
                        title: 'افزودن کاربر',
                        to: '/admin/users/create',
                        icon: 'far fa-plus',
                        type: 'secondary'
                    },
                    {
                        title: 'کاربران',
                        to: '/admin/users',
                        icon: 'fad fa-users',
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
                ],
                errors: new Errors(),
                data: {
                    roles: [],
                    metas: {
                        phone: '',
                        nationalCode: '',
                        postalCode: '',
                        provinceId: '',
                        cityId: '',
                        townId: '',
                        regionId: '',
                    }
                },
                roles: {},
                wallet: {
                    price: 0,
                    credit: 0,
                    newCredit: 0,
                    description: 'شارژ توسط مدیریت',
                },
                walletButtonTitle: 'افزودن تراکنش کیف پول',
                walletButtonDisable: false,
                walletErrors: new Errors(),
                world: {},
                loadingGif: null
            }
        },
        mounted() {
            axios.get(`/api/v1/users/${this.$route.params.id}`)
                .then(response => {
                    this.data = response.data.user;
                    this.wallet.credit = response.data.user.walletCredit;
                    this.loadingGif = response.data.loading;
                    this.$parent.headContent({
                        title: 'ویرایش ' + this.data.fullname
                    });
                })
                .catch(error => {

                });
            axios.get('/api/v1/roles')
                .then(response => {
                    this.roles = response.data
                })
                .catch(error => {

                });
        },
        methods: {
            onSubmitUser(e) {
                this.disabled = true;
                this.buttonTitle = 'لطفا صبر کنید...';
                const formData = new FormData(document.getElementById('user-form'));
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
            onSubmitWallet(e) {
                this.disabled = true;
                this.buttonTitle = 'لطفا صبر کنید...';
                const formData = new FormData(document.getElementById('wallet-form'));
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
            },
            numberFormat(value) {
                value = value.split(',').join('');
                value = this.toEnglish(value);
                if (isNaN(value)) {
                    return value.substring(0, value.length - 1);
                } else {
                    value += '';
                    var x = value.split('.');
                    var x1 = x[0];
                    var x2 = x.length > 1 ? '.' + x[1] : '';
                    var rgx = /(\d+)(\d{3})/;
                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                    }
                    return x1 + x2;
                }
            },
            toEnglish(value) {
                var numbers = {
                    '۰': 0,
                    '۱': 1,
                    '۲': 2,
                    '۳': 3,
                    '۴': 4,
                    '۵': 5,
                    '۶': 6,
                    '۷': 7,
                    '۸': 8,
                    '۹': 9
                };
                for (const prop in numbers) {
                    if (numbers.hasOwnProperty(prop)) {
                        value = value.split(`${prop}`).join(`${numbers[prop]}`);
                    }
                }
                return value;
            },
            onLocationGroupResult(world) {
                this.data.metas.provinceId = world.provinceId;
                this.data.metas.cityId = world.cityId;
                this.data.metas.townId = world.townId;
                this.data.metas.regionId = world.regionId;
            },
            profileBefore(files) {
                this.data.avatar = this.loadingGif;
            },
            completeProfile(status, response) {
                this.data.avatar = response.thumbnail;
            },
            block() {
                axios.get(`/api/v1/users/${this.$route.params.id}/block`)
                    .then(response => {
                        this.data.block = response.data.block;
                    });
            },
            verify(type) {
                axios.get(`/api/v1/users/verify/${type}/${this.$route.params.id}`)
                    .then(response => {
                        this.data.email_verified_at = response.data.user.email_verified_at;
                        this.data.mobile_verified_at = response.data.user.mobile_verified_at;
                    });
            }
        },
        computed: {
            walletNewCredit() {
                var credit = this.wallet.credit;
                var price = this.wallet.price;
                if (credit == price)
                    return '';

                return '<br>موجودی جدید : ' + this.numberFormat(price + credit) + ' ' + this.currency;
            }
        },
    }
</script>
