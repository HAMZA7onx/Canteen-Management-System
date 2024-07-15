<template>
  <div class="relative">
    <aside :class="['sidebar bg-white dark:bg-gray-800 text-gray-800 dark:text-white fixed top-16 bottom-0 transition-all duration-300 shadow-lg', sidebarOpen ? 'w-64' : 'w-20']">
      <div class="sidebar-content p-4 h-full overflow-y-auto">
        <nav>
          <ul class="space-y-4 pb-16">
            <li v-for="(group, index) in menuGroups" :key="index">
              <div @click="toggleGroup(index)" class="flex items-center justify-between cursor-pointer p-2 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900">
                <span :class="['font-bold text-lg transition-opacity duration-300', sidebarOpen ? 'opacity-100' : 'opacity-0']">{{ group.title }}</span>
                <font-awesome-icon :icon="group.expanded ? 'chevron-up' : 'chevron-down'" :class="[sidebarOpen ? '' : 'hidden']" />
              </div>
              <transition name="fade">
                <ul v-if="group.expanded || !sidebarOpen" class="mt-2 space-y-2">
                  <li v-for="item in group.items" :key="item.label"
                    :class="['flex items-center rounded-md transition-all duration-200 cursor-pointer', { 'bg-indigo-100 dark:bg-indigo-900': activeItem === item }]"
                    @click="setActiveItem(item)">
                    <router-link :to="item.route" class="flex items-center w-full p-2 text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400">
                      <font-awesome-icon :icon="item.icon" class="w-5 h-5 icon" :class="sidebarOpen ? 'mr-3' : 'mx-auto'" />
                      <span :class="['transition-opacity duration-300', sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden']">{{ item.label }}</span>
                    </router-link>
                  </li>
                </ul>
              </transition>
            </li>
          </ul>
        </nav>
      </div>
      <button @click="toggleSidebar" class="fixed bottom-4 -right-4 bg-indigo-600 text-white p-2 rounded-full shadow-lg hover:bg-indigo-700 transition-all duration-300 focus:outline-none">
        <font-awesome-icon :icon="sidebarOpen ? 'chevron-left' : 'chevron-right'" />
      </button>
    </aside>
  </div>
</template>

<script>
import { ref } from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import {
  faUserShield, faUserTag, faUsers, faUserFriends, faIdBadge,
  faUtensils, faClipboardList, faCalendarAlt, faChartBar,
  faChevronDown, faChevronUp, faChevronLeft, faChevronRight,
  faCog, faLock, faUserCog
} from '@fortawesome/free-solid-svg-icons';

library.add(
  faUserShield, faUserTag, faUsers, faUserFriends, faIdBadge,
  faUtensils, faClipboardList, faCalendarAlt, faChartBar,
  faChevronDown, faChevronUp, faChevronLeft, faChevronRight,
  faCog, faLock, faUserCog
);

export default {
  name: 'Sidebar',
  setup() {
    const sidebarOpen = ref(true);
    const activeItem = ref(null);

    const menuGroups = ref([
      {
        title: 'Administration',
        expanded: true,
        items: [
          { label: 'Les admins', icon: 'user-shield', route: '/' },
          { label: 'Les roles', icon: 'user-tag', route: '/roles' },
          { label: 'Les user-Categories', icon: 'user-friends', route: '/user-categories' },
        ]
      },
      {
        title: 'Utilisateurs',
        expanded: false,
        items: [
          { label: 'Les collaborateurs', icon: 'users', route: '/users' },
          { label: 'Les badges des collabs', icon: 'id-badge', route: '/badges' },
          { label: 'Les badges des admins', icon: 'id-badge', route: '/admins-badges' },
        ]
      },
      {
        title: 'Restauration',
        expanded: false,
        items: [
          { label: 'Les composants du menus', icon: 'utensils', route: '/food-composants' },
          { label: 'Les menus', icon: 'clipboard-list', route: '/menus' },
          { label: 'Gestion des repats', icon: 'utensils', route: '/daily' },
          { label: 'Le profiles repats', icon: 'calendar-alt', route: '/week-schedules' },
        ]
      },
      {
        title: 'Rapports et Gestion',
        expanded: false,
        items: [
          { label: 'Records', icon: 'chart-bar', route: '/records' },
          { label: 'Gestion des pOS', icon: 'cog', route: '/pos-devices' },
        ]
      },
    ]);

    const toggleSidebar = () => {
      sidebarOpen.value = !sidebarOpen.value;
    };

    const toggleGroup = (index) => {
      menuGroups.value[index].expanded = !menuGroups.value[index].expanded;
    };

    const setActiveItem = (item) => {
      activeItem.value = item;
    };

    return {
      sidebarOpen,
      activeItem,
      menuGroups,
      toggleSidebar,
      toggleGroup,
      setActiveItem,
    };
  },
};
</script>

<style scoped>
.sidebar {
  transition: width 0.3s ease, background-color 0.3s ease;
}

.sidebar-content {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

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

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.sidebar-content:hover {
  scrollbar-color: rgba(156, 163, 175, 0.7) transparent;
}

.sidebar-content {
  scrollbar-gutter: stable;
}

.sidebar-content::-webkit-scrollbar-corner {
  background: transparent;
}

.sidebar ul li {
  transition: background-color 0.2s ease;
}

.sidebar ul li:hover {
  background-color: rgba(156, 163, 175, 0.1);
}

.sidebar ul li.active {
  background-color: rgba(99, 102, 241, 0.1);
  border-left: 3px solid #6366f1;
}

.sidebar .icon {
  transition: transform 0.2s ease;
}

.sidebar ul li:hover .icon {
  transform: translateX(3px);
}
</style>
