import { createStore } from 'vuex';
import VuexPersistence from 'vuex-persist';

import auth from './modules/auth';
import admin from './modules/admin';
import user from './modules/user';
import role from './modules/role';
import userCategory from './modules/userCategory';
import badge from './modules/badge';
import foodComposant from './modules/foodComposant';
import menu from './modules/menu';
import dailyMeal from './modules/dailyMeal';
import weekSchedule from './modules/weekSchedule';
import record from './modules/record';
import badging from './modules/badging';
import adminBadge from './modules/adminBadge';

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
    userCategory,
    badge,
    foodComposant, 
    menu,
    dailyMeal, 
    weekSchedule,
    record,
    badging,
    adminBadge,
  },
  plugins: [vuexLocal.plugin]
});

export default store;