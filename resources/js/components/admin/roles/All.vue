<template>
    <div>
        <loader v-if="loader"></loader>
        <div v-else>
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
                                <td v-text="item.title ? item.title + ' ('+item.count+')' : '-'"></td>
                                <td v-text="item.name ? item.name : '-'"></td>
                                <td>
                                    <router-link :to="`/admin/roles/${item.id}/edit`">
                                        <a class="jgh-tooltip fa fa-edit btn user-card-btn btn-success text-white h5 m-0" title="ویرایش"></a>
                                    </router-link>
                                    <span @click="remove($event, item)" class="jgh-tooltip fa fa-trash btn user-card-btn btn-danger h5 m-0" title="حذف"></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
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
                loader: true,
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
                        to: '/admin/permissions',
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
                this.loader = false;
            }).catch(error => console.log(error));
        },
        components: {
            HeadContent
        },
        methods: {
            remove(event, item) {
                var name = item.title;
                var message = "آیا از حذف " + name + " اطمینان دارید؟";
                this.$toast.show(message, "", {
                    backgroundColor: 'rgb(255 162 162)',
                    progressBarColor: 'rgb(255 104 104)',
                    displayMode: 'once',
                    buttons: [
                        ['<button>حذف</button>', function (instance, toast) {
                            axios.delete('/api/v1/roles/' + item.id)
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
