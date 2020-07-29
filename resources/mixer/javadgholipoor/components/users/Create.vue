<template>
    <div>
        <head-content :title="title" :buttons="buttons"></head-content>
        <div class="alert alert-success my-3" v-if="success.status" v-text="success.message"></div>
        <div class="row">
            <div class="col-md-4">
                <form @submit.prevent="onSubmit" @keydown="errors.clear($event.target.name)" class="card">
                    <div class="card-body">
                        <form-group title="ایمیل یا موبایل">
                        </form-group>
                        <div class="form-group">
                            <label class="col-form-label">ایمیل / موبایل</label>
                            <input v-model="data.userLogin" type="text" id="userLogin" name="userLogin" value="" :class=" ['form-control ltr text-left' , { 'is-invalid' : errors.has('userLogin')}]">
                            <small class="text-danger" v-if="errors.has('userLogin')">{{ errors.get('userLogin') }}</small>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">رمزعبور</label>
                            <input v-model="data.password" type="text" id="password" name="password" value="" :class=" ['form-control ltr text-left' , { 'is-invalid' : errors.has('password')}]">
                            <small class="text-danger" v-if="errors.has('password')">{{ errors.get('password') }}</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button :disabled="errors.any()" class="btn btn-success">{{ buttonTitle }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import HeadContent from '../HeadContent.vue';

    class Errors {
        constructor() {
            this.errors = {};
        }

        has(field) {
            return this.errors.hasOwnProperty(field);
        }

        get(field) {
            if(this.errors[field]) {
                return this.errors[field][0];
            }
        }

        record(errors) {
            this.errors = errors;
        }

        any() {
            return Object.keys(this.errors).length > 0;
        }

        clear(field) {
            if(field) {
                delete this.errors[field];
                return;
            }

            this.errors = {}
        }
    }

    export default {
        data() {
            return {
                title: 'افزودن کاربر',
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
                }
            }
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
