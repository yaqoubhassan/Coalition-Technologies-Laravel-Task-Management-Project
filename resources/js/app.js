import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue'
import axios from 'axios';
import router from './router'
import moment from 'moment/moment';


const app = createApp(App);

app.use(router)

app.config.globalProperties.$filters = {
    timeAgo(date) {
        return moment(date).fromNow()
    },
}

axios.defaults.baseURL = import.meta.env.VITE_BASE_URL;

app.mount("#app");