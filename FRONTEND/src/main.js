import { createApp } from 'vue';
import App from './App.vue';
import './style.css';
import router from './router';
import store from './store';
import i18n from './i18n'

// Import Font Awesome core
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faTrash, faEdit, faClock, faInfoCircle, faKey } from '@fortawesome/free-solid-svg-icons';

// Add icons to the library
library.add(faTrash, faEdit, faClock, faInfoCircle, faKey);

// Initialize dark mode
if (localStorage.getItem('darkMode') === 'true' ||
    (!('darkMode' in localStorage) && 
     window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark');
}

// Create and mount the app
const app = createApp(App);

app.component('font-awesome-icon', FontAwesomeIcon); // Register the FontAwesomeIcon component globally

app.use(router);
app.use(store);
app.use(i18n)
app.mount('#app');
