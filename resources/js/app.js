require('./bootstrap');

import Vue from 'vue';

import QuizArea from "./components/QuizComponent.vue";
import FlashMessages from "./components/Flash.vue";
import AjaxButton from "./components/AjaxButton.vue";
import GetRoutes from "./components/GetRoutes.vue";


Vue.component("quiz-area", QuizArea);
Vue.component("v-flash", FlashMessages);
Vue.component("ajax-button", AjaxButton);
Vue.component("get-routes", GetRoutes);

const files = require.context('./pages/', true, /\.(vue|js)$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
window.Events = new Vue({});

window.flash = (message, level = 'success', important = false) => {
    window.Events.$emit('flash', {
        id: Math.floor(Date.now()).toString(),
        message, level, important
    });
};

Vue.mixin({
    methods: {
        route: route,
        flash: flash
    }
});


const app = new Vue({
    el: '#app'
});