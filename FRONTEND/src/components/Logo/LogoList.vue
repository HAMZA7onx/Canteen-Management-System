<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-100 to-blue-200 dark:from-gray-900 dark:to-blue-900 py-6 sm:py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 sm:p-8 mb-8 transform hover:scale-105 transition-all duration-300">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-blue-600 mb-4">Gestion du Logo</h1>
        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mb-6">
          Gérez le logo de votre application ici. Vous pouvez télécharger un nouveau logo ou voir le logo actuel.
        </p>
        
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
          <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Logo Actuel</h2>
          <div class="flex justify-center items-center h-64 bg-gray-200 dark:bg-gray-600 rounded-lg overflow-hidden">
            <img v-if="currentLogo" :src="fullLogoUrl" alt="Current Logo" class="max-h-full max-w-full object-contain">
            <p v-else class="text-gray-500 dark:text-gray-400 text-lg">Aucun logo n'a été téléchargé.</p>
          </div>
        </div>
        
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
          <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Télécharger un Nouveau Logo</h2>
          <LogoForm @logo-uploaded="handleLogoUploaded" />
        </div>
      </div>
    </div>
    <Toast :show="showToast" :message="toastMessage" />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import LogoForm from './LogoForm.vue';
import Toast from '@/components/shared/Toast.vue';
import { API_URL } from '@/config/config';

export default {
  name: 'LogoList',
  components: {
    LogoForm,
    Toast,
  },
  data() {
    return {
      showToast: false,
      toastMessage: '',
    };
  },
  computed: {
    ...mapGetters('logo', ['currentLogo']),
    fullLogoUrl() {
      if (this.currentLogo) {
        const baseUrl = API_URL.replace('/api', '');
        return `${baseUrl}${this.currentLogo}`;
      }
      return '';
    }
  },
  created() {
    this.fetchLogo();
  },
  methods: {
    ...mapActions('logo', ['fetchLogo']),
    handleLogoUploaded() {
      this.fetchLogo();
      this.showSuccessToast('Logo téléchargé avec succès!');
    },
    showSuccessToast(message) {
      this.toastMessage = message;
      this.showToast = true;
      setTimeout(() => {
        this.showToast = false;
      }, 3000);
    },
  },
};
</script>
