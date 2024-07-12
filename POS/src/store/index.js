import { createStore } from 'vuex';
import VuexPersistence from 'vuex-persist';

import auth from './modules/auth';
import badging from './modules/badging';


const vuexLocal = new VuexPersistence({
  storage: window.localStorage,
  modules: ['auth'] // Specify the modules you want to persist
});

const store = createStore({
  modules: {
    auth,
    badging,
  },
  plugins: [vuexLocal.plugin]
});

export default store;