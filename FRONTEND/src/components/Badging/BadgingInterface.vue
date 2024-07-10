<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 p-4">
    <div class="bg-gray-900 p-8 rounded-3xl shadow-2xl w-full max-w-3xl transition-all duration-500 transform hover:scale-105 border border-purple-500 relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-tr from-purple-500/20 to-pink-500/20 animate-gradient"></div>
      
      <h2 class="text-5xl font-extrabold mb-8 text-center text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Badge Scanning Interface</h2>
      
      <div class="mb-10 text-center relative z-10">
        <p class="text-3xl text-gray-300 font-light">
          Scan your badge to begin
        </p>
      </div>
      
      <div class="mb-10 p-8 bg-gray-800 rounded-2xl shadow-inner relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/10 to-pink-600/10 animate-pulse"></div>
        <div v-if="lastScannedBadge" class="text-center relative z-10">
          <p class="text-3xl font-semibold text-gray-300 mb-4">
            Last scanned badge:
          </p>
          <p class="text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 animate-glow">
            {{ lastScannedBadge }}
          </p>
        </div>
        <div v-else class="text-center relative z-10">
          <p class="text-4xl font-semibold text-gray-400 animate-pulse">
            Waiting for badge...
          </p>
        </div>
      </div>
      
      <div v-if="message"
           :class="['mt-6 p-4 rounded-lg text-xl font-medium text-center relative z-10', messageClass]"
           style="transition: all 0.5s ease;">
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
    let messageTimer = null;

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
            } else if (data.error === 'There is no meal available at this time in the active schedule.') {
              setMessage('There is no meal available at this time in the active schedule.', 'error');
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
      
      // Clear any existing timer
      if (messageTimer) {
        clearTimeout(messageTimer);
      }
      
      // Set a new timer to clear the message after 10 seconds
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
      // Clear the timer if the component is unmounted
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
@keyframes gradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

@keyframes glow {
  0%, 100% { text-shadow: 0 0 10px rgba(168, 85, 247, 0.5), 0 0 20px rgba(168, 85, 247, 0.3); }
  50% { text-shadow: 0 0 20px rgba(168, 85, 247, 0.8), 0 0 30px rgba(168, 85, 247, 0.5); }
}

.animate-gradient {
  background-size: 200% 200%;
  animation: gradient 15s ease infinite;
}

.animate-glow {
  animation: glow 2s ease-in-out infinite;
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: .5; }
}
</style>