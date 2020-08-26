import './bootstrap';

import Header from './components/admin/Header';
import Sidebar from './components/admin/Sidebar';
import router from './router';

router.beforeEach((to, from, next) => {
    if (to.name) {
        var permissions = JSON.parse(window.Laravel.permissions);
        if(permissions.includes(to.name)) {
            next();
        } else {
            if (to.name != 'error') {
                next({name: 'error'});
            } else {
                next();
            }
        }
    } else {
        if (to.name != 'error') {
            next({name: 'error'});
        } else {
            next();
        }
    }
});

Vue.component('myHeader', Header);
Vue.component('mySidebar' , Sidebar);

const app = new Vue({
    el: '#app',
    router,
    methods: {
        headContent(arg) {
            document.title = arg.title;
        },
        numberFormat(value) {
            if (isNaN(value)) {
                return  '';
            } else {
                return val;
            }
        }
    }
});
