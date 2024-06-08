<!-- components/shared/AuthenticatedLayout.vue -->
<template>
    <div class="flex flex-col">
      <Navbar />
      <div class="flex flex-grow ">
        <Sidebar />
        <main class="ml-64 p-4 h-full w-full overflow-auto mt-10">
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
      console.log(this.auth);
    },
  };
  </script>