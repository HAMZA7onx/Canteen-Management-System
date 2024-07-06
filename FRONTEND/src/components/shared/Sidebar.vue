<template>
  <aside class="sidebar bg-white dark:bg-gray-800 text-gray-800 dark:text-white h-screen w-64 fixed mt-16 transition-all duration-300 shadow-lg">
    <div class="sidebar-content p-4">
      <nav>
        <ul class="space-y-2">
          <li
            v-for="item in menuItems"
            :key="item.label"
            :class="[
              'flex items-center rounded-md transition-all duration-200 cursor-pointer',
              { 'bg-indigo-100 dark:bg-indigo-900': activeItem === item }
            ]"
            @click="setActiveItem(item)"
            @keydown.enter="setActiveItem(item)"
            tabindex="0"
            role="menuitem"
            aria-current="page"
          >
            <router-link :to="item.route" class="flex items-center w-full p-2 text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400">
              <font-awesome-icon :icon="item.icon" class="w-5 h-5 mr-3" />
              {{ item.label }}
            </router-link>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>

<script>
import { library } from '@fortawesome/fontawesome-svg-core'
import { 
  faUserShield, faUserTag, faUsers, faUserFriends, faIdBadge, 
  faUtensils, faClipboardList, faCalendarAlt, faChartBar
} from '@fortawesome/free-solid-svg-icons'

library.add(
  faUserShield, faUserTag, faUsers, faUserFriends, faIdBadge, 
  faUtensils, faClipboardList, faCalendarAlt, faChartBar
)

export default {
  name: 'Sidebar',
  data() {
    return {
      activeItem: null,
      menuItems: [
        { label: 'Les admins', icon: 'user-shield', route: '/' },
        { label: 'Les roles', icon: 'user-tag', route: '/roles' },
        { label: 'Les collaborateurs', icon: 'users', route: '/users' },
        { label: 'Les user-Categories', icon: 'user-friends', route: '/user-categories' },
        { label: 'Les badges', icon: 'id-badge', route: '/badges' },
        { label: 'Les composants du menus', icon: 'utensils', route: '/food-composants' },
        { label: 'Les menus', icon: 'clipboard-list', route: '/menus' },
        { label: 'Gestion des repats', icon: 'utensils', route: '/daily' },
        { label: 'Le profiles repats', icon: 'calendar-alt', route: '/week-schedules' },
        { label: 'Records', icon: 'chart-bar', route: '/records' },
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
  transition: background-color 0.3s ease;
}

.sidebar-content {
  height: calc(100vh - 4rem);
  overflow-y: auto;
}

/* Custom scrollbar styles */
.sidebar-content::-webkit-scrollbar {
  width: 6px;
}

.sidebar-content::-webkit-scrollbar-track {
  background: transparent;
}

.sidebar-content::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 3px;
}

.sidebar-content::-webkit-scrollbar-thumb:hover {
  background-color: rgba(156, 163, 175, 0.7);
}
</style>
