import { createRouter, createWebHistory } from 'vue-router';

import Login from '@/views/Auth/Login.vue';
import store from '@/store';
import UserList from '@/components/User/UserList.vue';
import AdminListView from '@/views/Admin/AdminListView.vue';
import RoleList from '@/components/Role/RoleList.vue';
import PermissionList from '@/components/Permission/PermissionList.vue';
import UserCategoryList from '@/components/UserCategory/UserCategoryList.vue';
import BadgeList from '@/components/Badge/BadgeList.vue';
import FoodComposantList from '@/components/WeekSchedule/FoodComposant/FoodComposantList.vue'
import MenuList from '@/components/WeekSchedule/Menu/MenuList.vue'
import DailyMealList from '@/components/WeekSchedule/DailyMeal/DailyMealList.vue'
import WeekScheduleList from '@/components/WeekSchedule/WeekSchedule/WeekScheduleTable.vue'
import RecordsDashboard from '@/components/Record/RecordsDashboard.vue'
import BadgingInterface from '@/components/Badging/BadgingInterface.vue'
import AdminBadgeList from '@/components/AdminBadge/BadgeList.vue'
 
const routes = [
  { path: '/', component: AdminListView },
  {path: '/login', component: Login},
  { path: '/users', component: UserList },
  { path: '/roles', component: RoleList },
  { path: '/permissions', component: PermissionList },
  { path: '/user-categories', component: UserCategoryList },
  { path: '/badges', component: BadgeList },
  { path: '/food-composants', component: FoodComposantList },
  { path: '/menus', component: MenuList },
  { path: '/daily', component: DailyMealList },
  { path: '/week-schedules', component: WeekScheduleList },
  { path: '/records', component: RecordsDashboard },
  { path: '/badging', component: BadgingInterface },
  { path: '/admins-badges', component: AdminBadgeList },
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