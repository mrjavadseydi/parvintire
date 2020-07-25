import Vue from 'vue';
import Axsios from 'axios';
import VueRouter from 'vue-router'

window.Vue = Vue;
Vue.use(VueRouter)

window.axios = Axsios;

window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};
