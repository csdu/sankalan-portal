require('./bootstrap');

import { createApp } from 'vue';
import emitter from 'mitt'

import QuizArea from "./components/QuizComponent.vue";
import CountdownTimer from "./components/CountdownTimer.vue";
import FlashMessages from "./components/Flash.vue";
import GetRoutes from "./components/GetRoutes.vue";
import LoginRegister from "./components/LoginRegister";
import Modal from './components/Modal.vue';
import MarkdownEditor from "./components/MarkdownEditor.vue";
import QuestionType from "./components/QuestionType.vue";

const app = createApp({});
const eventBus = emitter();

app.component("quiz-area", QuizArea);
app.component("v-flash", FlashMessages);
app.component("get-routes", GetRoutes);
app.component("login-register", LoginRegister);
app.component("countdown-timer", CountdownTimer);
app.component("modal", Modal);
app.component("markdown-editor", MarkdownEditor);
app.component("question-type", QuestionType);

const files = require.context('./pages/', true, /\.(vue|js)$/i);
files.keys().map(key => app.component(key.split('/').pop().split('.')[0], files(key).default));

window.flash = (message, level = 'success', important = false) => {
    eventBus.emit('flash', {
        id: Math.floor(Date.now()).toString(),
        message, level, important
    });
};

app.config.globalProperties.$eventBus = eventBus;

app.mixin({
    methods: {
        route: route,
        flash: flash,
        reload: () => window.location.reload(),
        redirectHandler: ({ request }) => window.location.replace(request.responseURL),
    }
});


app.mount('#app');
