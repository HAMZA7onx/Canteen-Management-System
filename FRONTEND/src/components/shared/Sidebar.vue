<template>
  <aside class="bg-gray-800 text-white h-screen w-64 fixed">
    <div class="p-4">
      <h2 class="text-xl font-bold mb-4">Menu</h2>
      <nav>
        <ul>
          <li v-for="item in menuItems" :key="item.label" class="mb-2">
            <router-link :to="item.route" class="hover:text-gray-300">
              <i :class="item.icon"></i> {{ item.label }}
            </router-link>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'Sidebar',
  data() {
    return {
      menuItems: [
        { label: 'Dashboard', icon: 'dashboard', route: '/' },
        { label: 'Users', icon: 'users', route: '/users' },
        { label: 'Admins', icon: 'admins', route: '/admins' },
        { label: 'Roles', icon: 'roles', route: '/roles' },
        { label: 'Permissions', icon: 'permissions', route: '/permissions' },
        { label: 'User Categories', icon: 'user-categories', route: '/user-categories' },
        { label: 'Badges', icon: 'badges', route: '/badges' },
        { label: 'Meal Categories', icon: 'meal-categories', route: '/meal-categories' },
        { label: 'Meal Menus', icon: 'meal-menus', route: '/meal-menus' },
        { label: 'Meal Components', icon: 'meal-components', route: '/meal-components' },
        { label: 'Meal Schedules', icon: 'meal-schedules', route: '/meal-schedules' },
        { label: 'Meal Records', icon: 'meal-records', route: '/meal-records' },
        { label: 'Menu Components', icon: 'menu-components', route: '/menu-components' },
      ],
    };
  },
};
</script>