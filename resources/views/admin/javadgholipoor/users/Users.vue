<template>
    <div>
        <head-content :title="title" :buttons="buttons"></head-content>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table>
                        <thead>
                        <tr>
                            <th></th>
                            <th>نام</th>
                            <th>نام کاربری</th>
                            <th>موبایل</th>
                            <th>ایمیل</th>
                            <th>نقش‌ها</th>
                            <th>آخرین‌بازدید</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in data.data">
                                <td></td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.username }}</td>
                                <td>{{ user.mobile }}</td>
                                <td>{{ user.email }}</td>
                                <td>-</td>
                                <td>-</td>
                                <td>
                                    <router-link :to="`/admin/users/${user.id}/edit`">
                                        <a class="jgh-tooltip fa fa-edit text-success h5 ml-1" title="ویرایش"></a>
                                    </router-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <pagination :data="data" :result="paginate"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    import HeadContent from '../HeadContent.vue';
    import Pagination from "../vendor/Pagination";
    export default {
        data() {
            return {
                data: {},
                title: 'کاربران',
                buttons: [
                    {
                        title: 'افزودن کاربر',
                        href: '/',
                        icon: 'far fa-plus',
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
                ]
            }
        },
        created() {
            axios.get('/api/v1/users').then(response => {
                this.data = response.data;
                this.$parent.headContent({
                    title: 'کاربران'
                });
            }).catch(error => console.log(error));
        },
        methods: {
            paginate(page) {
                if (typeof page === 'undefined') page = 1;
                axios.get('/api/v1/users?page=' + page)
                    .then(response => this.data = response.data)
                    .catch(error => console.log(error));
            }
        },
        components: {
            HeadContent,
            Pagination
        }
    }
</script>
