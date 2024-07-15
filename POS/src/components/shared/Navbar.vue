<template>
    <nav class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-gray-900 dark:to-indigo-900 shadow-lg fixed left-0 right-0 top-0 z-50 transition-all duration-300">
      <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between">
          <router-link to="/" class="text-white font-bold text-2xl tracking-wider hover:text-indigo-200 transition-colors duration-300">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-indigo-200">POS @IP: 127.0.0.1</span>
          </router-link>
          
          <div class="flex items-center space-x-6">
           
            
            <button
              @click="handleLogout"
              class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-full shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300"
            >
              <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                </svg>
                Logout
              </span>
            </button>
          </div>
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
  
  /*
  navbar design
  */
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
