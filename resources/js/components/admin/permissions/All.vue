<template>
    <div>
        <loader v-if="loader"></loader>
        <div v-else>
            <head-content :title="`${pageTitle} (${count})`" :buttons="buttons">
                <button @click="sync" :class="`btn ${syncType} mr-2`">
                    <i :class="['far fa-sync align-middle ml-2', isSync ? 'fa-spin' : '' ]"></i>{{ syncTitle }}
                </button>
            </head-content>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table>
                            <thead>
                            <tr>
                                <th></th>
                                <th>شناسه</th>
                                <th>عنوان</th>
                                <th>مجوز</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in items">
                                <td></td>
                                <td v-text="`#${item.id}`"></td>
                                <td v-text="item.label ? item.label + ' ('+item.count+')' : '-'"></td>
                                <td v-text="item.name ? item.name : '-'"></td>
                                <td>
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
                pageTitle: 'مجوز ها',
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
                count: '...',
                isSync: false,
                syncTitle: 'همگام سازی',
                syncType: 'btn-outline-primary'
            }
        },
        created() {
            this.$parent.headContent({
                title: 'مجوز ها'
            });
            axios.get('/api/v1/permissions').then(response => {
                this.items = response.data;
                this.count = this.items.length;
                this.loader = false;
            }).catch(error => console.log(error));
        },
        components: {
            HeadContent
        },
        methods: {
            remove(event, item) {
                return true;
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
            },
            sync() {
                this.isSync = true;
                this.syncTitle = 'در حال همگام سازی';
                this.syncType = 'btn-outline-primary';
                axios.get('/api/v1/permissions/sync').then(response => {
                    this.items = response.data;
                    this.count = this.items.length;
                    this.isSync = false;
                    this.syncTitle = 'همگام سازی با موفقیت انجام شد';
                    this.syncType = 'btn-success';
                }).catch(error => console.log(error));
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
