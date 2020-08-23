<template>
    <div>
        <head-content :title="pageTitle" :buttons="buttons"></head-content>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table>
                        <thead>
                        <tr>
                            <th></th>
                            <th>شناسه</th>
                            <th>عنوان</th>
                            <th>نقش</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in items">
                            <td></td>
                            <td v-text="`#${item.id}`"></td>
                            <td v-text="item.title ? item.title : '-'"></td>
                            <td v-text="item.name ? item.name : '-'"></td>
                            <td>
                                <router-link :to="`/admin/users/${item.id}/edit`">
                                    <a class="jgh-tooltip fa fa-edit btn user-card-btn btn-success text-white h5 m-0 mb-1 mb-md-0" title="ویرایش"></a>
                                </router-link>
                                <span @click="remove" class="jgh-tooltip fa fa-trash btn user-card-btn btn-danger h5 m-0" title="حذف"></span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import HeadContent from "../HeadContent";

    import VueIziToast from 'vue-izitoast';
    import 'izitoast/dist/css/iziToast.min.css';
    Vue.use(VueIziToast);

    export default {
        data() {
            return {
                items: {},
                pageTitle: 'نقش ها',
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
                        to: '/',
                        icon: 'far fa-badge-check',
                        type: 'secondary'
                    },
                    {
                        title: 'افزودن نقش',
                        to: '/admin/roles/create',
                        icon: 'far fa-plus',
                        type: 'secondary'
                    }
                ]
            }
        },
        created() {
            this.$parent.headContent({
                title: 'نقش ها'
            });
            axios.get('/api/v1/roles').then(response => {
                this.items = response.data;
            }).catch(error => console.log(error));
        },
        components: {
            HeadContent
        },
        methods: {
            remove() {
                this.$toast.warning('آیا از حذف این مورد اطمینان دارید');
            }
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
