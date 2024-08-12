<!-- src/components/CollaboratorStatistics/CollaboratorStatistics.vue -->

<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-100 to-indigo-200 dark:from-gray-900 dark:to-indigo-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-600 mb-8">Collaborator Statistics</h1>

      <!-- Date Range Picker -->
      <div class="mb-8">
        <label for="start-date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
        <input type="date" id="start-date" v-model="startDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

        <label for="end-date" class="block mt-4 text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
        <input type="date" id="end-date" v-model="endDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

        <button @click="fetchStatistics" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
          Fetch Statistics
        </button>
      </div>

      <!-- Statistics Tables -->
      <div v-if="isLoading" class="text-center">
        <loading-wheel />
      </div>
      <div v-else-if="error" class="text-red-600 dark:text-red-400">
        {{ error }}
      </div>
      <div v-else>
        <div v-for="(monthData, month) in statistics" :key="month" class="mb-12">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ formatMonth(month) }}</h2>
          <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matriculation Number</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total (Without Discount)</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total (With Discount)</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Details</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="user in monthData" :key="user.user_id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ user.name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ user.matriculation_number }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ user.category }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ formatCurrency(user.total_without_discount) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ formatCurrency(user.total_with_discount) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button @click="showUserDetails(user)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">View Details</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- User Details Modal -->
      <modal v-if="selectedUser" @close="closeUserDetails">
        <template #header>
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ selectedUser.name }} - Meal Details</h3>
        </template>
        <template #body>
          <div class="space-y-4">
            <div v-for="meal in selectedUser.meals" :key="meal.date" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
              <p class="font-semibold text-gray-900 dark:text-gray-100">{{ formatDate(meal.date) }} - {{ meal.name }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Price: {{ formatCurrency(meal.price) }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">Discount: {{ meal.discount }}%</p>
              <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Final Price: {{ formatCurrency(meal.price_with_discount) }}</p>
            </div>
          </div>
        </template>
      </modal>
    </div>
  </div>
</template>
<script>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import LoadingWheel from '@/components/shared/LoadingWheel.vue';
import Modal from '@/components/shared/Modal.vue';

export default {
  name: 'CollaboratorStatistics',
  components: {
    LoadingWheel,
    Modal,
  },
  setup() {
    const store = useStore();
    const startDate = ref('');
    const endDate = ref('');
    const selectedUser = ref(null);

    const statistics = computed(() => store.getters['collaboratorStatistics/statistics']);
    const isLoading = computed(() => store.getters['collaboratorStatistics/isLoading']);
    const error = computed(() => store.getters['collaboratorStatistics/error']);

    const fetchStatistics = () => {
      store.dispatch('collaboratorStatistics/fetchStatistics', {
        startDate: startDate.value,
        endDate: endDate.value,
      });
    };

    const formatMonth = (monthStr) => {
      const [year, month] = monthStr.split('-');
      return new Date(year, month - 1).toLocaleString('default', { month: 'long', year: 'numeric' });
    };

    const formatCurrency = (amount) => {
      return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
    };

    const formatDate = (dateStr) => {
      return new Date(dateStr).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    };

    const showUserDetails = (user) => {
      selectedUser.value = user;
    };

    const closeUserDetails = () => {
      selectedUser.value = null;
    };

    return {
      startDate,
      endDate,
      statistics,
      isLoading,
      error,
      selectedUser,
      fetchStatistics,
      formatMonth,
      formatCurrency,
      formatDate,
      showUserDetails,
      closeUserDetails,
    };
  },
};
</script>
