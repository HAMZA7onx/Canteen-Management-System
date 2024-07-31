import { createRouter, createWebHistory } from 'vue-router';

import Login from '@/views/Auth/Login.vue';
import store from '@/store';
import BadgingInterface from '@/components/Badging/BadgingInterface.vue'
import DailyMealStats from '@/components/DailyMealStats.vue';
 
const routes = [
  {path: '/login', component: Login},
  {path: '/', component: BadgingInterface },
  {path: '/daily-meal-stats', name: 'DailyMealStats', component: DailyMealStats},
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