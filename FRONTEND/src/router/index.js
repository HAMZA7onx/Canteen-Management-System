import { createRouter, createWebHistory } from 'vue-router';
import store from '@/store';

// Import your components and views
import Login from '@/views/Auth/Login.vue';
import AuthenticatedLayout from '@/components/shared/AuthenticatedLayout.vue';
// Import other components and views

const routes = [
  {
    path: '/login',
    component: Login,
    meta: { requiresAuth: false },
  },
  {
    path: '/',
    component: AuthenticatedLayout,
    meta: { requiresAuth: true },
    children: [
      // Add your authenticated routes here
      // Example: { path: '/admin', component: AdminView, meta: { requiresAuth: true } },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isLoggedIn = store.getters['auth/isLoggedIn'];

  if (to.meta.requiresAuth && !isLoggedIn) {
    // If the route requires authentication and the user is not logged in, redirect to the login page
    next({ path: '/login' });
  } else if (!to.meta.requiresAuth && isLoggedIn) {
    // If the route doesn't require authentication and the user is logged in, redirect to the home page
    next({ path: '/' });
  } else {
    // Otherwise, proceed to the requested route
    next();
  }
});

export default router;