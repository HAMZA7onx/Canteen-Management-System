<template>
  <aside class="sidebar bg-gray-800 dark:bg-gray-900 text-white h-screen w-64 fixed mt-16 transition-colors duration-300">
    <div class="sidebar-content p-4">
      <nav>
        <ul>
          <li
            v-for="item in menuItems"
            :key="item.label"
            :class="[
              'flex items-center rounded-md transition-colors duration-200 mb-2 p-2 cursor-pointer',
              { 'bg-gray-700 dark:bg-gray-800': activeItem === item }
            ]"
            @click="setActiveItem(item)"
            @keydown.enter="setActiveItem(item)"
            tabindex="0"
            role="menuitem"
            aria-current="page"
          >
            <svg
              class="w-5 h-5 mr-2 text-gray-400"
              aria-hidden="true"
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
            <router-link :to="item.route" class="flex-grow text-sm font-medium hover:text-gray-200">
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
        { label: 'Les admins', icon: 'admins', route: '/' },
        { label: 'Les roles', icon: 'roles', route: '/roles' },
        { label: 'Les collaborateurs', icon: 'users', route: '/users' },
        { label: 'Les user-Categories', icon: 'user-categories', route: '/user-categories' },
        { label: 'Les badges', icon: 'badges', route: '/badges' },
        { label: 'Les composants du menus', icon: 'meal-menus', route: '/food-composants' },
        { label: 'Les menus', icon: 'meal-menus', route: '/menus' },
        { label: 'Gestion des repats', icon: 'meal-menus', route: '/daily' },
        { label: 'Le profiles repats', icon: 'meal-schedules', route: '/week-schedules' },
        { label: 'Records', icon: 'meal-records', route: '/meal-records' },
      ],
    };
  },
  methods: {
    setActiveItem(item) {
      this.activeItem = item;
    }
  }
};
</script>
