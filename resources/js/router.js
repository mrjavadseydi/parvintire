import VueRouter from 'vue-router';

export default new VueRouter({
    base: '/',
    mode: 'history',
    routes: [
        {
            path : '/admin',
            component : require('./components/admin/Panel.vue').default,
            name: 'controlPanel'
        },
        {
            path : '/admin/error',
            component : require('./components/vendor/Error.vue').default,
            name: 'error'
        },
        {
            path : '/admin/permissions',
            component : require('./components/admin/permissions/All.vue').default,
            name: 'permissions'
        },
        {
            path : '/admin/roles',
            component : require('./components/admin/roles/All.vue').default,
            name: 'roles'
        },
        {
            path : '/admin/roles/create',
            component : require('./components/admin/roles/Create.vue').default,
            name: 'createRole'
        },
        {
            path : '/admin/roles/:id/edit',
            component : require('./components/admin/roles/Edit.vue').default,
            name: 'updateRole'
        },
        {
            path : '/admin/users',
            component : require('./components/admin/users/All.vue').default,
            name: 'users'
        },
        {
            path : '/admin/users/create',
            component : require('./components/admin/users/Create.vue').default,
            name: 'createUser'
        },
        {
            path : '/admin/users/:id/edit',
            component : require('./components/admin/users/Edit.vue').default,
            name: 'updateUser'
        }
    ]
});
