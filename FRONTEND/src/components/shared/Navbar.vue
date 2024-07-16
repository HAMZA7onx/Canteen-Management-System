<template>
  <nav class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-gray-900 dark:to-indigo-900 shadow-lg fixed left-0 right-0 top-0 z-50 transition-all duration-300">
    <div class="container mx-auto px-4 py-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <button @click="toggleSidebar" class="mr-2 text-white sm:hidden">
            <font-awesome-icon icon="bars" />
          </button>
          <router-link to="/" class="text-white font-bold text-xl sm:text-2xl tracking-wider hover:text-indigo-200 transition-colors duration-300">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-indigo-200">TABLEAU DE BORD</span>
          </router-link>
        </div>
       
        <div class="hidden sm:flex items-center space-x-6">
          <router-link to="/profile" class="group relative font-semibold text-white hover:text-indigo-200 transition-colors duration-300">
            {{ $t('profile') }}
            <span class="absolute inset-x-0 bottom-0 h-0.5 bg-indigo-200 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
          </router-link>
         
          <DarkModeToggle class="transform hover:scale-110 transition-transform duration-300" />
         
          <button
            @click="handleLogout"
            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-full shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300"
          >
            <span class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
              </svg>
              {{ $t('logout') }}
            </span>
          </button>
        </div>

        <div class="sm:hidden">
          <button @click="toggleMobileMenu" class="text-white">
            <font-awesome-icon icon="ellipsis-v" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div v-if="mobileMenuOpen" class="sm:hidden bg-indigo-700 dark:bg-gray-800">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <router-link to="/profile" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-indigo-600 dark:hover:bg-gray-700">
          {{ $t('profile') }}
        </router-link>
        <DarkModeToggle class="block px-3 py-2" />
        <button
          @click="handleLogout"
          class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-white hover:bg-red-600 dark:hover:bg-red-700"
        >
          {{ $t('logout') }}
        </button>
      </div>
    </div>
  </nav>
</template>

<script>
import { ref } from 'vue';
import { mapActions } from 'vuex';
import DarkModeToggle from './DarkModeToggle.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBars, faEllipsisV } from '@fortawesome/free-solid-svg-icons';

library.add(faBars, faEllipsisV);

export default {
  name: 'Navbar',
  components: {
    DarkModeToggle,
  },
  setup() {
    const mobileMenuOpen = ref(false);

    const toggleMobileMenu = () => {
      mobileMenuOpen.value = !mobileMenuOpen.value;
    };

    return {
      mobileMenuOpen,
      toggleMobileMenu,
    };
  },
  methods: {
    ...mapActions('auth', ['logout']),
    ...mapActions('sidebar', ['toggleSidebar']),
    async handleLogout() {
      try {
        await this.logout();
        this.mobileMenuOpen = false;
      } catch (error) {
        console.error('Error during logout:', error);
        // Handle the error as needed
      }
    },
  },
};
</script>

<style scoped>
@keyframes gradientAnimation {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

nav {
  background-size: 200% 200%;
  animation: gradientAnimation 15s ease infinite;
}
</style>
