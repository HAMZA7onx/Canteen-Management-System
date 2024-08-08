import { createApp } from 'vue';
import App from './App.vue';
import './style.css';
import router from './router';
import store from './store';

import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faTrash, faEdit, faClock, faInfoCircle, faKey, faPlusCircle, faPlus, faUnlink, faUser, faUsers, faCalendar, faUtensils, faIdCard, faCogs } from '@fortawesome/free-solid-svg-icons';

library.add(faTrash, faEdit, faClock, faInfoCircle, faKey, faPlusCircle, faPlus, faUnlink, faUser, faUsers, faCalendar, faUtensils, faIdCard, faCogs);

if (localStorage.getItem('darkMode') === 'true' ||
    (!('darkMode' in localStorage) && 
     window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark');
}

const app = createApp(App);

app.component('font-awesome-icon', FontAwesomeIcon);

app.use(router);
app.use(store);
app.mount('#app');
