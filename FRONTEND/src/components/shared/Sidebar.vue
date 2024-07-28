<template>
  <div class="relative">
    <aside :class="['sidebar bg-white dark:bg-gray-800 text-gray-800 dark:text-white fixed top-16 bottom-0 transition-all duration-300 shadow-lg', sidebarOpen ? 'w-64' : 'w-0 sm:w-20', 'z-40']">
      <div class="sidebar-content p-4 h-full overflow-y-auto" v-show="sidebarOpen || !isMobile">
        <nav>
          <ul class="space-y-4 pb-16">
            <!-- Home item -->
            <li class="mb-4">
              <router-link :to="homeItem.route" 
                class="flex items-center w-full p-2 rounded-md text-sm font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900 transition-colors duration-200"
                :class="{ 'bg-indigo-100 dark:bg-indigo-900': activeItem === homeItem }"
                @click="setActiveItem(homeItem)">
                <font-awesome-icon :icon="homeItem.icon" class="w-5 h-5 icon" :class="sidebarOpen ? 'mr-3' : 'mx-auto'" />
                <span :class="['transition-opacity duration-300', sidebarOpen ? 'opacity-100' : 'opacity-0 sm:opacity-100 w-0 overflow-hidden']">
                  {{ homeItem.label }}
                </span>
              </router-link>
            </li>
            <!-- Existing menu groups -->
            <li v-for="(group, index) in menuGroups" :key="index">
              <div @click="toggleGroup(index)" class="flex items-center justify-between cursor-pointer p-2 rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900">
                <span :class="['font-bold text-lg transition-opacity duration-300', sidebarOpen ? 'opacity-100' : 'opacity-0 sm:opacity-100']">{{ group.title }}</span>
                <font-awesome-icon :icon="group.expanded ? 'chevron-up' : 'chevron-down'" :class="[sidebarOpen ? '' : 'hidden sm:inline-block']" />
              </div>
              <transition name="fade">
                <ul v-if="group.expanded || !sidebarOpen" class="mt-2 space-y-2">
                  <li v-for="item in group.items" :key="item.label"
                    :class="['flex items-center rounded-md transition-all duration-200 cursor-pointer', { 'bg-indigo-100 dark:bg-indigo-900': activeItem === item }]"
                    @click="setActiveItem(item)">
                    <div v-if="!item.permission">
                      <router-link :to="item.route" class="flex items-center w-full p-2 text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400">
                        <font-awesome-icon :icon="item.icon" class="w-5 h-5 icon" :class="sidebarOpen ? 'mr-3' : 'mx-auto'" />
                        <span :class="['transition-opacity duration-300', sidebarOpen ? 'opacity-100' : 'opacity-0 sm:opacity-100 w-0 overflow-hidden']">{{ item.label }}</span>
                      </router-link>
                    </div>
                    <div v-else-if="$can(item.permission)">
                      <router-link :to="item.route" class="flex items-center w-full p-2 text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400">
                        <font-awesome-icon :icon="item.icon" class="w-5 h-5 icon" :class="sidebarOpen ? 'mr-3' : 'mx-auto'" />
                        <span :class="['transition-opacity duration-300', sidebarOpen ? 'opacity-100' : 'opacity-0 sm:opacity-100 w-0 overflow-hidden']">{{ item.label }}</span>
                      </router-link>
                    </div>
                  </li>
                </ul>
              </transition>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
    <button @click="toggleSidebar" :class="[
      'fixed z-50 bg-indigo-600 text-white p-2 rounded-full shadow-lg hover:bg-indigo-700 transition-all duration-300 focus:outline-none',
      sidebarOpen ? 'left-64' : 'left-0 sm:left-20',
      'top-1/2 -translate-y-1/2'
    ]">
      <font-awesome-icon :icon="sidebarOpen ? 'chevron-left' : 'chevron-right'" />
    </button>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useStore } from 'vuex';
import { library } from '@fortawesome/fontawesome-svg-core';
import {
  faHome,
  faUserShield, faUserTag, faUsers, faUserFriends, faIdBadge,
  faUtensils, faClipboardList, faCalendarAlt, faChartBar,
  faChevronDown, faChevronUp, faChevronLeft, faChevronRight,
  faCog, faLock, faUserCog, faClock, faClipboardCheck
} from '@fortawesome/free-solid-svg-icons';

library.add(
  faHome,
  faUserShield, faUserTag, faUsers, faUserFriends, faIdBadge,
  faUtensils, faClipboardList, faCalendarAlt, faChartBar,
  faChevronDown, faChevronUp, faChevronLeft, faChevronRight,
  faCog, faLock, faUserCog, faClock, faClipboardCheck
);

import permissionMixin from '@/mixins/permissionMixin';

export default {
  name: 'Sidebar',
  mixins: [permissionMixin],
  setup() {
    const store = useStore();
    const sidebarOpen = computed(() => store.state.sidebar.isOpen);
    const activeItem = ref(null);
    const isMobile = ref(window.innerWidth < 640);

    const homeItem = computed(() => ({
      label: 'Home',
      icon: 'home',
      route: '/'
    }));

    const menuGroups = ref([
      {
        title: 'Administration',
        expanded: true,
        items: [
          { label: 'Les admins', icon: 'user-shield', route: '/admins', permission: 'SUPER_ADMIN_PERMISSION' },
          { label: 'Les roles', icon: 'user-tag', route: '/roles', permission: 'SUPER_ADMIN_PERMISSION'  },
          { label: 'Les subscriptions des admins', icon: 'user-shield', route: '/admins-report-subscriptions', permission: 'gerer_subscribtions_des_admin'},
          { label: 'Les Categories des collaborateurs', icon: 'user-friends', route: '/user-categories', permission: 'voir_categorie_de_collaborateur' },
        ]
      },
      {
        title: 'Utilisateurs',
        expanded: false,
        items: [
          { label: 'Les collaborateurs', icon: 'users', route: '/users', permission: 'voir_collaborateurs' },
          { label: 'Les badges des collaborateurs', icon: 'id-badge', route: '/badges', permission: 'voir_badges_collaborateurs' },
          { label: 'Les badges des admins', icon: 'id-badge', route: '/admins-badges', permission: 'voir_badges_administrateurs' },
        ]
      },
      {
        title: 'Restauration',
        expanded: false,
        items: [
          { label: 'Les composants du menus', icon: 'utensils', route: '/food-composants', permission: 'voir_composants_menus' },
          { label: 'Les menus', icon: 'clipboard-list', route: '/menus', permission: 'voir_categories_menus' },
          { label: 'Gestion des repats', icon: 'clock', route: '/daily', permission: 'voir_repas' },
          { label: 'Le profiles repats', icon: 'calendar-alt', route: '/week-schedules', permission: 'voir_profils_repas' },
        ]
      },
      {
        title: 'Rapports et Gestion',
        expanded: false,
        items: [
          { label: 'Records', icon: 'chart-bar', route: '/records', permission: 'voir_enregistrements_repas' },
          { label: 'Audit de Records', icon: 'clipboard-check', route: '/records-audit', permission: 'voir_enregistrements_repas' },
          { label: 'Gestion des pOS', icon: 'cog', route: '/pos-devices', permission: 'voir_POS' },
        ]
      },
    ]);

    const toggleSidebar = () => {
      store.dispatch('sidebar/toggleSidebar');
    };

    const toggleGroup = (index) => {
      menuGroups.value[index].expanded = !menuGroups.value[index].expanded;
    };

    const setActiveItem = (item) => {
      activeItem.value = item;
    };

    const handleResize = () => {
      isMobile.value = window.innerWidth < 640;
    };

    onMounted(() => {
      window.addEventListener('resize', handleResize);
    });

    onUnmounted(() => {
      window.removeEventListener('resize', handleResize);
    });

    return {
      sidebarOpen,
      activeItem,
      menuGroups,
      homeItem,
      toggleSidebar,
      toggleGroup,
      setActiveItem,
      isMobile,
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
