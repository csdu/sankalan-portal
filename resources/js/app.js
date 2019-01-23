require('./bootstrap');

import Vue from 'vue';

import QuizArea from "./components/QuizComponent.vue";
import FlashMessages from "./components/Flash.vue";

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component("quiz-area", QuizArea);
Vue.component("v-flash", FlashMessages);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
window.Events = new Vue({});
const app = new Vue({
    el: '#app'
});