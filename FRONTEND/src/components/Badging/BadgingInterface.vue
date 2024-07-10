<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md transition-colors duration-300">
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 dark:text-white">Badge Scanning Interface</h2>
      
      <div class="mb-6 text-center">
        <p class="text-lg text-gray-700 dark:text-gray-300">
          Scan your badge to begin
        </p>
      </div>
      
      <div v-if="lastScannedBadge" class="mb-6 text-center">
        <p class="text-md text-gray-600 dark:text-gray-400">
          Last scanned badge: {{ lastScannedBadge }}
        </p>
      </div>
      
      <div v-if="message" :class="['mt-4 p-3 rounded', messageClass]">
        {{ message }}
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import { useStore } from 'vuex';

export default {
  name: 'BadgingInterface',
  setup() {
    const store = useStore();
    const message = ref('');
    const messageClass = ref('');
    const lastScannedBadge = ref('');
    let badgeId = '';
    let lastKeyTime = Date.now();

    const processBadge = async () => {
      if (badgeId) {
        try {
          const currentDay = new Date().toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
          const result = await store.dispatch('badging/verifyAndScanBadge', { rfid: badgeId, day: currentDay });
          setMessage(result.message, 'success');
          lastScannedBadge.value = badgeId;
        } catch (error) {
          handleError(error);
        }
        badgeId = ''; // Clear the badgeId for the next scan
      }
    };

    const handleError = (error) => {
      if (error.response) {
        const { status, data } = error.response;
        switch (status) {
          case 400:
            if (data.error === 'A record for this badge and meal already exists.') {
              setMessage('You have already badged for this meal.', 'warning');
            } else if (data.error.includes('There is no meal available at this time')) {
              setMessage('No meal is currently available.', 'warning');
            } else if (data.error === 'No active week schedule found') {
              setMessage('No active meal schedule found.', 'error');
            } else {
              setMessage(data.error, 'error');
            }
            break;
          case 404:
            setMessage('Badge not found in the system.', 'error');
            break;
          default:
            setMessage('An error occurred while processing your badge.', 'error');
        }
      } else {
        setMessage('An error occurred while processing your badge.', 'error');
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
    };

    onMounted(() => {
      document.addEventListener('keypress', handleKeyPress);
    });

    onUnmounted(() => {
      document.removeEventListener('keypress', handleKeyPress);
    });

    return {
      message,
      messageClass,
      lastScannedBadge
    };
  }
};
</script>
