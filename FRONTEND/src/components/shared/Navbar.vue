<template>
  <nav class="bg-white shadow-md fixed left-0 right-0 top-0 z-10">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <router-link to="/" class="text-gray-800 font-bold text-xl">DASHBOARD</router-link>
      <div class="flex items-center">
        <div class="font-semibold text-gray-800 cursor-pointer mr-4">PROFILE</div>
        <button
          @click="handleLogout"
          class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded"
        >
          Logout
        </button>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  name: 'Navbar',
  methods: {
    ...mapActions('auth', ['logout']),
    async handleLogout() {
      try {
        await this.logout();
      
      } catch (error) {
        console.error('Error during logout:', error);
        // Handle the error as needed
      }
    },
  },
};
</script>
