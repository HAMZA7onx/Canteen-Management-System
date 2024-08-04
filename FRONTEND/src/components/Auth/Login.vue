<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-r from-sky-400 to-blue-500 dark:from-gray-800 dark:to-gray-900 transition-colors duration-300">
    <div class="absolute top-4 right-4 flex items-center space-x-4">
      <button @click="toggleDarkMode" class="p-2 rounded-full bg-white dark:bg-gray-800 text-gray-800 dark:text-white shadow-lg hover:shadow-xl transition-all duration-300">
        <svg v-if="isDarkMode" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
      </button>
    </div>

    <div class="w-full max-w-md px-4">
      <div class="absolute top-3 left-4">
        <div class="w-[100px] h-[40px] flex items-center justify-center overflow-hidden">
          <img :src="logoUrl" alt="Logo" class="object-contain w-full h-full invert brightness-0">
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-lg px-8 pt-6 pb-8 mb-4 transform hover:scale-105 transition-all duration-300">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-white">Login</h2>
        
        <div v-if="warningMessage" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
          {{ warningMessage }}
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">
          <div>
            <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2" for="email">
              Email
            </label>
            <input
              class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 dark:text-white dark:bg-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 transition-all duration-300"
              id="email"
              type="email"
              v-model="credentials.email"
              required
              placeholder="Saizez votre email"
            />
          </div>
          <div>
            <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2" for="password">
              Mot de pass
            </label>
            <input
              class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 dark:text-white dark:bg-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 transition-all duration-300"
              id="password"
              type="password"
              v-model="credentials.password"
              required
              placeholder="Saizez votre mot de pass"
            />
          </div>
          <div>
            <button
              class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transform hover:scale-105 transition-all duration-300"
              type="submit"
              :disabled="isLoading"
            >
              {{ isLoading ? 'Connexion en cours...' : 'Se connecter' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { mapActions, mapGetters } from 'vuex';
import { API_URL } from '@/config/config';
import defaultLogo from '@/assets/logo.png';

export default {
  name: 'Login',
  setup() {
    const cachedLogoUrl = ref(localStorage.getItem('cachedLogoUrl') || defaultLogo);
    return { cachedLogoUrl };
  },
  data() {
    return {
      credentials: {
        email: '',
        password: '',
      },
      isDarkMode: false,
      warningMessage: '',
      isLoading: false,
    };
  },
  computed: {
    ...mapGetters('logo', ['currentLogo']),
    logoUrl() {
      return this.cachedLogoUrl;
    }
  },
  watch: {
    currentLogo: {
      handler(newLogo) {
        if (newLogo) {
          const baseUrl = API_URL.replace('/api', '');
          const fullLogoUrl = `${baseUrl}${newLogo}`;
          if (fullLogoUrl !== this.cachedLogoUrl) {
            this.cachedLogoUrl = fullLogoUrl;
            localStorage.setItem('cachedLogoUrl', fullLogoUrl);
          }
        }
      },
      immediate: true
    }
  },
  methods: {
    ...mapActions('logo', ['fetchLogo']),
    async handleLogin() {
      this.warningMessage = '';
      this.isLoading = true;

      try {
        await this.$store.dispatch('auth/login', this.credentials);
        this.$router.push({ name: 'dashboard' });
      } catch (error) {
        console.error(error);
        if (error.response) {
          switch (error.response.status) {
            case 422:
              this.warningMessage = 'Veuillez vérifier vos informations de connexion.';
              break;
            case 401:
              this.warningMessage = 'Email ou mot de passe incorrect.';
              break;
            default:
              this.warningMessage = 'Une erreur s\'est produite. Veuillez réessayer plus tard.';
          }
        } else {
          this.warningMessage = 'Impossible de se connecter au serveur. Veuillez vérifier votre connexion internet.';
        }
      } finally {
        this.isLoading = false;
      }
    },
    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode;
      document.documentElement.classList.toggle('dark');
    },
  },
  mounted() {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      this.isDarkMode = true;
      document.documentElement.classList.add('dark');
    }
    this.fetchLogo();
  },
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
}
</style>
