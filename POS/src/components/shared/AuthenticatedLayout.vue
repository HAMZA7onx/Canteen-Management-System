<template>
    <div class="flex flex-col min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
      <Navbar />
      <div class="flex flex-grow">
        <main class="ml-64 mt-14 p-6 flex-grow overflow-auto">
          <router-view />
        </main>
      </div>
    </div>
  </template>
  
  <script>
  import Navbar from './Navbar.vue';
  import { mapState } from 'vuex';
  
  export default {
    name: 'AuthenticatedLayout',
    components: {
      Navbar,
    },
    computed: {
      ...mapState(['auth']),
    },
    mounted() {
      // console.log(this.auth);
    },
  };
  </script>
