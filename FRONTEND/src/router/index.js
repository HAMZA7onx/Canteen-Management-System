import { createRouter, createWebHistory } from 'vue-router';
import store from '@/store';
import UserList from '@/components/User/UserList.vue';
import AdminList from '@/components/Admin/AdminList.vue';
import RoleList from '@/components/Role/RoleList.vue';
import PermissionList from '@/components/Permission/PermissionList.vue';
import UserCategoryList from '@/components/UserCategory/UserCategoryList.vue';
import BadgeList from '@/components/Badge/BadgeList.vue';
import MealCategoryList from '@/components/Meal/Category/MealCategoryList.vue';
import MealMenuList from '@/components/Meal/Menu/MealMenuList.vue';
import MealComponentList from '@/components/Meal/Component/MealComponentList.vue';
import MealScheduleList from '@/components/Meal/Schedule/MealScheduleList.vue';
import MealRecordList from '@/components/Meal/Record/MealRecordList.vue';
import MenuComponentList from '@/components/Meal/MenuComponent/MenuComponentList.vue';


const routes = [
  { path: '/', component: AdminList },
  { path: '/users', component: UserList },
  { path: '/roles', component: RoleList },
  { path: '/permissions', component: PermissionList },
  { path: '/user-categories', component: UserCategoryList },
  { path: '/badges', component: BadgeList },
  { path: '/meal-categories', component: MealCategoryList },
  { path: '/meal-menus', component: MealMenuList },
  { path: '/meal-components', component: MealComponentList },
  { path: '/meal-schedules', component: MealScheduleList },
  { path: '/meal-records', component: MealRecordList },
  { path: '/menu-components', component: MenuComponentList },
];


const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isLoggedIn = store.getters['auth/isLoggedIn'];

  if (!to.meta.requiresAuth && !isLoggedIn) {
    next({ path: '/login' });
  }
});

export default router;