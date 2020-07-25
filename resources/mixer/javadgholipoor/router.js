import VueRouter from 'vue-router';

export default new VueRouter({
    base: '/',
    mode: 'history',
    routes: [
        {
            path : '/admin',
            component : require('./components/Panel.vue').default,
        },
        {
            path : '/admin/users',
            component : require('./components/users/Users.vue').default,
        },
        {
            path : '/admin/users/create',
            component : require('./components/users/Create.vue').default,
        },
        {
            path : '/admin/users/:id/edit',
            component : require('./components/users/Edit.vue').default,
        },
    ]
});
