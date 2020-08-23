import VueRouter from 'vue-router';

export default new VueRouter({
    base: '/',
    mode: 'history',
    routes: [
        {
            path : '/admin',
            component : require('./components/admin/Panel.vue').default,
        },
        {
            path : '/admin/users',
            component : require('./components/admin/users/All.vue').default
        },
        {
            path : '/admin/users/create',
            component : require('./components/admin/users/Create.vue').default,
        },
        {
            path : '/admin/users/:id/edit',
            component : require('./components/admin/users/Edit.vue').default,
        },
        {
            path : '/admin/roles',
            component : require('./components/admin/roles/All.vue').default
        },
        {
            path : '/admin/roles/create',
            component : require('./components/admin/roles/Create.vue').default
        }
    ]
});
