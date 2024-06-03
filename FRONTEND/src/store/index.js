import { createStore } from 'vuex';
import auth from './modules/auth';
import admin from './modules/admin';
import user from './modules/user';
import role from './modules/role';
import permission from './modules/permission';
import userCategory from './modules/userCategory';
import badge from './modules/badge';
import meal from './modules/meal';

const store = createStore({
  modules: {
    auth,
    admin,
    user,
    role,
    permission,
    userCategory,
    badge,
    meal,
  },
});

export default store;