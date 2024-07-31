<template>
    <div class="flex flex-col min-h-screen transition-colors duration-300 bg-cover bg-center bg-fixed" :style="{ backgroundImage: `url(${foodBackground})` }">
      <div class=" bg-gray-900 bg-opacity-20">
        <Navbar />
        <router-view />
      </div>
    </div>
  </template>
  
  <script>
  import Navbar from './Navbar.vue';
  import { mapState } from 'vuex';
  import foodBackground from '@/assets/food.jpg';
  
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

    setup() {
      return {
        foodBackground,
      };
    }
  };
  </script>
