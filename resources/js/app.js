import './bootstrap';

import { createApp } from 'vue/dist/vue.esm-bundler.js';
import LoginForm from './components/LoginForm.vue';
import Register from './components/Register.vue';

const app = createApp({});

app.component('login-form', LoginForm);
app.component('register', Register);
app.mount("#app");
