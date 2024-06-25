<template>
  <aside class="sidebar">
    <div class="sidebar-content">
      <nav>
        <ul>
          <li
            v-for="item in menuItems"
            :key="item.label"
            :class="['sidebar-item', { 'active': activeItem === item }]"
            @click="setActiveItem(item)"
            @keydown.enter="setActiveItem(item)"
            tabindex="0"
            role="menuitem"
            aria-current="page"
          >
            <svg
              class="icon"
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
            <router-link :to="item.route" class="link">
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
        { label: 'Les menus', icon: 'meal-menus', route: '/menus' },
        { label: 'Les composants du menus', icon: 'meal-menus', route: '/food-composants' },
        { label: 'Le profiles repats', icon: 'meal-schedules', route: '/lll' },
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

<style scoped>
.sidebar {
  background-color: #2d3748; /* bg-gray-800 */
  color: #fff;
  height: 100vh;
  width: 16rem; /* w-64 */
  position: fixed;
  margin-top: 4rem; /* mt-16 */
}

.sidebar-content {
  padding: 1rem; /* p-4 */
}

.sidebar-item {
  display: flex;
  align-items: center;
  border-radius: 0.375rem; /* rounded-md */
  transition: background-color 0.2s;
  margin-bottom: 0.5rem; /* mb-2 */
  padding: 0.5rem;
  cursor: pointer;
}

.sidebar-item:focus {
  outline: none;
  background-color: #4a5568; /* bg-gray-700 */
}

.sidebar-item.active {
  background-color: #4a5568; /* bg-gray-700 */
  color: #fff;
}

.icon {
  margin-right: 0.5rem; /* mr-2 */
  color: #a0aec0; /* text-gray-400 */
  height: 1.25rem; /* h-5 */
  width: 1.25rem; /* w-5 */
}

.link {
  flex-grow: 1;
  text-decoration: none;
  color: inherit;
  display: flex;
  align-items: center;
}
</style>
