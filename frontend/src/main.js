import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import './assets/main.css'
import { createPinia } from 'pinia'
import router from './router';
import axios from './axios';
import * as stores from './stores'
import { createApp } from 'vue'
import App from './App.vue'

const app = createApp(App)
if (process.env.NODE_ENV === 'development') {
    app.config.devtools = true; // Habilita Vue DevTools
}
app.use(createPinia())
app.use(router)
app.mount('#app')
