<template>
    <div>
        <head-content :title="pageTitle" :buttons="buttons"></head-content>
        <div class="row">
            <div class="col-md-4">
                <form @submit.prevent="onSubmit" @keydown="keydown" class="card">
                    <div class="card-body">
                        <text-input-group v-model="data.label" :val="data.label" name="label" :error="errors.get('label')" title="عنوان"></text-input-group>
                        <text-input-group v-model="data.name" :val="data.name" classes="ltr text-left" name="name" :error="errors.get('name')" title="کلید (فرمت مجاز : کاراکترهای انگلیسی)"></text-input-group>
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
    import HeadContent from '../HeadContent.vue';
    import TextInputGroup from "../../forms/group/TextInputGroup";
    import {Errors} from '../../../errors';
    export default {
        components: {
            HeadContent,
            TextInputGroup
        },
        created() {
            this.$parent.headContent({
                title: this.pageTitle
            });
        },
        data() {
            return {
                pageTitle: 'افزودن نقش',
                buttonTitle: 'ذخیره',
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
                        title: 'نقش ها',
                        to: '/admin/roles',
                        icon: 'fad fa-user-tie',
                        type: 'secondary'
                    }
                ],
                data : {
                    label : '',
                    role : ''
                },
                errors : new Errors()
            }
        },
        methods: {
            onSubmit() {
                this.disabled = true;
                this.buttonTitle = 'لطفا صبر کنید...';
                axios.post('/api/v1/roles'  , this.data)
                    .then(result => {
                        var response = result.data;
                        if (response.status == 'success') {
                            this.$router.push('/admin/roles/'+response.role.id+'/edit');
                        }
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
