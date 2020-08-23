import './bootstrap';

import Header from './components/admin/Header';
import Sidebar from './components/admin/Sidebar';
import router from './router';

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
