import { createStore } from 'vuex';
import VuexPersistence from 'vuex-persist';

import auth from './modules/auth';
import admin from './modules/admin';
import user from './modules/user';
import role from './modules/role';
import permission from './modules/permission';
import userCategory from './modules/userCategory';
import badge from './modules/badge';
import foodComposant from './modules/foodComposant';

const vuexLocal = new VuexPersistence({
  storage: window.localStorage,
  modules: ['auth', 'role', 'permission'] // Specify the modules you want to persist
});

const store = createStore({
  modules: {
    auth,
    admin,
    user,
    role,
    permission,
    userCategory,
    badge,
    foodComposant
  },
  plugins: [vuexLocal.plugin]
});

export default store;
