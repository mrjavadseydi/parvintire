import './bootstrap';

// import Panel from './components/Panel.vue';
import Header from './components/Header.vue';
import Sidebar from './components/Sidebar.vue';
import router from './router';

Vue.component('myHeader', Header);
Vue.component('mySidebar' , Sidebar);

const app = new Vue({
    el: '#app',
    router
});
