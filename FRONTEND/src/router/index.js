import { createRouter, createWebHistory } from 'vue-router';
import store from '@/store';
import Login from '../views/Auth/Login.vue';
import AdminList from '@/components/Admin/AdminList.vue';

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta : { requiresAuth: false },
  },
  {
    path: '/',
    component: AdminList,
    meta: { requiresAuth: true },
  },
  {
    path: '/admins',
    component: AdminList,
    meta: { requiresAuth: true },
    children: [
      
    ]
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isLoggedIn = store.getters['auth/isLoggedIn'];

  if (!to.meta.requiresAuth && !isLoggedIn) {
    next({ path: '/login' });
  }else if(to.meta.requiresAuth && isLoggedIn) {
    next({path: '/admins'});

  }
});

export default router;