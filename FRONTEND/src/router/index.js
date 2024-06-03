import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/Auth/Login.vue';

const routes = [
  { path: '/', component: Login },
  // Add other routes here
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;