<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 p-4">
      <div class="bg-gray-900 p-8 rounded-3xl shadow-2xl w-full max-w-md transition-all duration-500 transform hover:scale-105 border border-purple-500 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-tr from-purple-500/20 to-pink-500/20 animate-gradient"></div>
        
        <h2 class="text-4xl font-extrabold mb-8 text-center text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">POS Login</h2>
        
        <form @submit.prevent="login" class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
            <input type="email" id="email" v-model="email" required class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
            <input type="password" id="password" v-model="password" required class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
          </div>
          <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
              Login
            </button>
          </div>
        </form>
        
        <div v-if="error" class="mt-4 text-red-500 text-center">{{ error }}</div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import { useStore } from 'vuex';
  import { useRouter } from 'vue-router';
  
  export default {
    name: 'PosLogin',
    setup() {
      const store = useStore();
      const router = useRouter();
      const email = ref('');
      const password = ref('');
      const error = ref('');
  
      const login = async () => {
        try {
          await store.dispatch('auth/login', { email: email.value, password: password.value });
          router.push('/badging');
        } catch (err) {
          error.value = 'Invalid email or password';
        }
      };
  
      return { email, password, error, login };
    }
  };
  </script>
  