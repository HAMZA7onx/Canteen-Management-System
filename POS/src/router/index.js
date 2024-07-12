import { createRouter, createWebHistory } from 'vue-router';

import Login from '@/views/Auth/Login.vue';
import store from '@/store';
import BadgingInterface from '@/components/Badging/BadgingInterface.vue'
// import test from '@/components/Test/test.vue'
 
const routes = [
  {path: '/login', component: Login},
  { path: '/', component: BadgingInterface },
];


const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isLoggedIn = store.getters['auth/isLoggedIn'];
  if (to.meta.requiresAuth && !isLoggedIn) {
      next({ path: '/login' });
  } else {
      next();
  }
});

export default router; 