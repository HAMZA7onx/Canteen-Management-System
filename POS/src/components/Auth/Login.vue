<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600 p-4">
      <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md">
        <h2 class="text-4xl font-bold mb-6 text-center text-gray-800">Admin Access</h2>
        
        <div class="mb-8 text-center">
          <p class="text-lg text-gray-600">
            Scan your RFID badge to login
          </p>
        </div>
        
        <div class="mb-8 p-6 bg-gray-100 rounded-lg relative">
          <div v-if="lastScannedBadge" class="text-center">
            <p class="text-xl font-semibold text-gray-700 mb-2">
              Last scanned badge:
            </p>
            <p class="text-3xl font-bold text-blue-600">
              {{ lastScannedBadge }}
            </p>
          </div>
          <div v-else class="text-center">
            <p class="text-2xl font-semibold text-gray-500 animate-pulse">
              Waiting for badge...
            </p>
          </div>
        </div>
        
        <div v-if="message"
             :class="['mt-4 p-3 rounded text-sm font-medium text-center', messageClass]">
          {{ message }}
        </div>
  
        <div class="mt-8 text-center text-sm text-gray-500">
          <p>Only authorized administrators with RFID badges can access this interface.</p>
          <p class="mt-2">If you're having trouble, please contact IT support.</p>
        </div>
      </div>
    </div>
  </template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default {
  name: 'Login',
  setup() {
      const store = useStore();
      const router = useRouter();
      const message = ref('');
      const messageClass = ref('');
      const lastScannedBadge = ref('');
      let badgeId = '';
      let lastKeyTime = Date.now();
      let messageTimer = null;

      const processBadge = async () => {
          if (badgeId) {
              try {
                  await store.dispatch('auth/loginWithBadge', { rfid: badgeId });
                  setMessage('Login successful', 'success');
                  lastScannedBadge.value = badgeId;
                  router.push('/');
              } catch (error) {
                  handleError(error);
              }
              badgeId = ''; // Clear the badgeId for the next scan
          }
      };

      const handleError = (error) => {
        console.log(error)
        if (error.response) {
          const { status, data } = error.response;
          switch (status) {
            case 401:
              setMessage(data.message || 'Invalid badge', 'error');
              break;
            case 403:
              setMessage(data.message || 'You do not have permission to access this device', 'error');
              break;
            default:
              setMessage(data.message || 'An error occurred during login', 'error');
          }
        } else {
          setMessage(error.message || 'An error occurred during login', 'error');
        }
      };

      const handleKeyPress = (event) => {
          const currentTime = Date.now();
          if (currentTime - lastKeyTime > 100) { // Reset if there's a pause longer than 100ms
              badgeId = '';
          }
          lastKeyTime = currentTime;

          if (event.key !== 'Enter') {
              badgeId += event.key;
          } else {
              processBadge();
          }
      };

      const setMessage = (text, type) => {
          message.value = text;
          messageClass.value = type === 'error' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' :
                               type === 'warning' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                               'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
          
          if (messageTimer) {
              clearTimeout(messageTimer);
          }
          
          messageTimer = setTimeout(() => {
              message.value = '';
              messageClass.value = '';
          }, 5000);
      };

      onMounted(() => {
          document.addEventListener('keypress', handleKeyPress);
      });

      onUnmounted(() => {
          document.removeEventListener('keypress', handleKeyPress);
          if (messageTimer) {
              clearTimeout(messageTimer);
          }
      });

      return {
          message,
          messageClass,
          lastScannedBadge
      };
  }
};
</script>

<style scoped>
/* Remove previous animations and keep it simple */
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: .5; }
}
</style>