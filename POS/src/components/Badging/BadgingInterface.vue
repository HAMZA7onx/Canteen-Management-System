<template>
  <div class="min-h-screen flex flex-col bg-cover bg-center bg-fixed" :style="{ backgroundImage: `url(${foodBackground})` }">
    <div class="flex-grow flex items-center justify-center p-4 backdrop-blur-sm bg-black bg-opacity-50">
      <div class="w-full max-w-6xl flex gap-8">
        <!-- Carte d'information du repas -->
        <div v-if="currentMeal" class="bg-white bg-opacity-90 p-6 rounded-3xl shadow-2xl w-1/3 transform hover:scale-105 transition-all duration-300">
          <h3 class="text-3xl font-bold mb-4 text-gray-800">Repas Actuel</h3>
          <p class="text-2xl font-semibold text-indigo-600">{{ currentMeal.name }}</p>
          <p class="text-xl text-gray-600">{{ currentMeal.start_time }} - {{ currentMeal.end_time }}</p>
          <p class="text-xl font-bold text-green-600 mt-2">{{ currentMeal.price }} DH</p>
          <button @click="showDiscounts" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Afficher les Réductions
          </button>
        </div>
        <div v-else class="bg-white bg-opacity-90 p-6 rounded-3xl shadow-2xl w-1/3 transform hover:scale-105 transition-all duration-300">
          <p class="text-2xl font-semibold text-gray-600">Aucun repas assigné pour le moment</p>
        </div>

        <!-- Carte principale de badgeage -->
        <div class="bg-white bg-opacity-90 p-8 rounded-3xl shadow-2xl w-2/3 transform hover:scale-105 transition-all duration-300">
          <h2 class="text-5xl font-extrabold mb-8 text-center text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">Interface de Scan de Badge</h2>
          
          <div class="mb-10 text-center">
            <p class="text-3xl text-gray-700 font-light">
              Scannez votre badge pour commencer
            </p>
          </div>
          
          <div class="mb-10 p-8 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-2xl shadow-inner relative overflow-hidden">
            <div v-if="lastScannedBadge" class="text-center">
              <p class="text-3xl font-semibold text-gray-700 mb-4">
                Dernier badge scanné :
              </p>
              <p class="text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600 animate-pulse">
                {{ lastScannedBadge }}
              </p>
            </div>
            <div v-else class="text-center">
              <p class="text-4xl font-semibold text-gray-500 animate-pulse">
                En attente d'un badge...
              </p>
            </div>
          </div>
          
          <div v-if="lastScannedPerson && showWelcomeMessage" class="mb-10 p-8 bg-gradient-to-br from-green-100 to-blue-100 rounded-2xl shadow-inner relative overflow-hidden">
            <div class="text-center">
              <p class="text-3xl font-semibold text-gray-700 mb-4">Bienvenue :</p>
              <p class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600">
                {{ lastScannedPerson }}
              </p>
            </div>
          </div>
          
          <div v-if="message"
               :class="['mt-6 p-4 rounded-lg text-xl font-medium text-center', messageClass]">
            {{ message }}
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de réductions -->
    <div v-if="isDiscountModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
      <div class="bg-white p-8 rounded-lg shadow-xl max-w-4xl w-full">
        <h2 class="text-3xl font-bold mb-4">Réductions pour {{ currentMeal.name }}</h2>
        <input v-model="searchTerm" placeholder="Rechercher des réductions..." class="w-full p-2 mb-4 border rounded">
        <div class="max-h-96 overflow-y-auto">
          <div v-if="filteredDiscounts.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="discount in filteredDiscounts" :key="discount" class="p-4 bg-gray-100 rounded-lg shadow">
              <div class="font-semibold text-lg">{{ discount.split(':')[0] }}</div>
              <div class="text-green-600 font-bold">{{ discount.split(':')[1] }} DH</div>
            </div>
          </div>
          <p v-else class="text-center text-gray-500">Aucune réduction disponible pour ce repas.</p>
        </div>
        <button @click="closeDiscountModal" class="mt-6 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
          Fermer
        </button>
      </div>
    </div>
  </div>
</template>


<script>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useStore } from 'vuex';
import foodBackground from '@/assets/food.jpg';

export default {
  name: 'BadgingInterface',
  setup() {
    const store = useStore();
    const message = ref('');
    const messageClass = ref('');
    const lastScannedBadge = computed(() => store.getters['badging/getLastScannedBadge']);
    const currentMeal = computed(() => store.getters['badging/getCurrentMeal']);
    const lastScannedPerson = computed(() => store.getters['badging/getLastScannedPerson']);
    const showWelcomeMessage = ref(false);
    const isDiscountModalOpen = ref(false);
    const discounts = ref([]);
    const searchTerm = ref('');
    let badgeId = '';
    let lastKeyTime = Date.now();
    let messageTimer = null;

    const filteredDiscounts = computed(() => {
      return discounts.value.filter(discount => 
        discount.toLowerCase().includes(searchTerm.value.toLowerCase())
      );
    });

    const processBadge = async () => {
      if (badgeId) {
        try {
          const currentDay = new Date().toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
          const result = await store.dispatch('badging/verifyAndScanBadge', { rfid: badgeId, day: currentDay });
          setMessage(result.message, 'success');
        } catch (error) {
          handleError(error);
        }
        badgeId = '';
      }
    };

    const handleError = (error) => {
      if (error.response) {
        const { data } = error.response;
        setMessage(data.error, 'error');
      } else {
        setMessage('An error occurred while processing your badge.', 'error');
      }
    };

    const handleKeyPress = (event) => {
      const currentTime = Date.now();
      if (currentTime - lastKeyTime > 100) {
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
      showWelcomeMessage.value = true;
      
      if (messageTimer) {
        clearTimeout(messageTimer);
      }
      
      messageTimer = setTimeout(() => {
        message.value = '';
        messageClass.value = '';
        showWelcomeMessage.value = false;
      }, 5000);
    };

    const fetchCurrentMeal = () => {
      store.dispatch('badging/fetchCurrentMeal');
    };

    const showDiscounts = async () => {
      if (currentMeal.value) {
        const currentDay = new Date().toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
        try {
          const result = await store.dispatch('badging/fetchDiscounts', { day: currentDay, mealId: currentMeal.value.id });
          discounts.value = result;
          isDiscountModalOpen.value = true;
        } catch (error) {
          console.error('Error fetching discounts:', error);
        }
      }
    };

    const closeDiscountModal = () => {
      isDiscountModalOpen.value = false;
      searchTerm.value = '';
    };

    onMounted(() => {
      document.addEventListener('keypress', handleKeyPress);
      fetchCurrentMeal();
      const intervalId = setInterval(fetchCurrentMeal, 60000);
      
      onUnmounted(() => {
        document.removeEventListener('keypress', handleKeyPress);
        clearInterval(intervalId);
        if (messageTimer) {
          clearTimeout(messageTimer);
        }
      });
    });

    return {
      message,
      messageClass,
      lastScannedBadge,
      currentMeal,
      lastScannedPerson,
      foodBackground,
      showWelcomeMessage,
      isDiscountModalOpen,
      discounts,
      showDiscounts,
      closeDiscountModal,
      searchTerm,
      filteredDiscounts,
    };
  }
};
</script>

<style scoped>
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: .5; }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
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
