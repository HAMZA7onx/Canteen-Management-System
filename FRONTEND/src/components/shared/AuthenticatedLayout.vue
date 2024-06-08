<template>
    <div class="flex flex-col min-h-screen bg-gray-100">
      <Navbar />
      <div class="flex flex-grow">
        <Sidebar />
        <main class="ml-64 mt-14 p-6 flex-grow overflow-auto">
          <router-view />
        </main>
      </div>
    </div>
  </template>
  
  <script>
  import Navbar from './Navbar.vue';
  import Sidebar from './Sidebar.vue';
  import { mapState } from 'vuex';
  
  export default {
    name: 'AuthenticatedLayout',
    components: {
      Navbar,
      Sidebar,
    },
    computed: {
      ...mapState(['auth']),
    },
    mounted() {
      // console.log(this.auth);
    },
  };
  </script>
  