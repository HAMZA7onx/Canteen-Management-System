<template>
  <aside class="bg-gray-800 text-white h-screen w-64 fixed mt-16">
    <div class="p-4">
      <nav>
        <ul>
          <li
            v-for="item in menuItems"
            :key="item.label"
            class="mb-2 flex items-center rounded-md transition-colors duration-200"
            :class="{ 'bg-gray-700 text-white': activeItem === item }"
            @click="activeItem = item"
          >
            <svg
              class="mr-2 text-gray-400 h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5l7 7-7 7"
              />
            </svg>
            <router-link :to="item.route" class="flex-grow">
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
      activeItem: null,
      menuItems: [
        { label: 'Admins', icon: 'admins', route: '/' },
        { label: 'Users', icon: 'users', route: '/users' },
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
