<template>
  <div class="flex flex-col min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <Navbar />
    <div class="flex flex-grow">
      <Sidebar />
      <main :class="[
        'mt-14 p-6 flex-grow overflow-auto transition-all duration-300',
        isSidebarOpen ? 'sm:ml-64' : 'ml-0 sm:ml-20'
      ]">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue';
import { useStore } from 'vuex';
import Navbar from './Navbar.vue';
import Sidebar from './Sidebar.vue';

export default {
  name: 'AuthenticatedLayout',
  components: {
    Navbar,
    Sidebar,
  },
  setup() {
    const store = useStore();
    const isSidebarOpen = computed(() => store.getters['sidebar/isOpen']);

    return {
      isSidebarOpen,
    };
  },
};
</script>
