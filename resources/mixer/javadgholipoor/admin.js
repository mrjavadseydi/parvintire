import './bootstrap';

import Panel from './components/Panel.vue';
import Header from './components/Header.vue';
import Sidebar from './components/Sidebar.vue';
import router from './router';

Vue.component('myHeader', Header);
Vue.component('mySidebar' , Sidebar);

// import Users from './components/users/Users.vue';
// Vue.component('Users', Users);

// import Users from './components/users/Users.vue';
// Vue.component('Users', Users);

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

const app = new Vue({
    el: '#app',
    router
});
