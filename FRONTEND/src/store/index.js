import { createStore } from 'vuex';
import VuexPersistence from 'vuex-persist';

import auth from './modules/auth';
import admin from './modules/admin';
import user from './modules/user';
import role from './modules/role';
import permission from './modules/permission';
import userCategory from './modules/userCategory';
import badge from './modules/badge';
import mealMenu from './modules/mealMenu';
import mealName from './modules/mealName';
import mealSchedule from './modules/mealSchedule';

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
    mealMenu,
    mealName,
    mealSchedule,
  },
  plugins: [vuexLocal.plugin]
});

export default store;
