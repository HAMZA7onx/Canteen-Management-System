<!-- src/components/CollaboratorStatistics/CollaboratorStatistics.vue -->

<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-100 to-indigo-200 dark:from-gray-900 dark:to-indigo-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-600 mb-8">Statistiques des Collaborateurs</h1>

      <!-- Date Range Picker -->
      <DateRangePicker
        v-model:startDate="startDate"
        v-model:endDate="endDate"
        @fetch="fetchStatistics"
      />

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
          <StatisticsTable
            :headers="tableHeaders"
            :users="monthData"
            @view-details="showUserDetails"
          />
        </div>
      </div>

      <!-- User Details Modal -->
      <div v-if="selectedUser" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="my-modal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
          <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ selectedUser.name }} - Détails des Repas</h3>
            <div class="mt-2 px-7 py-3">
              <div class="space-y-4">
                <div v-for="meal in selectedUser.meals" :key="meal.date" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md text-left">
                  <p class="font-semibold text-gray-900 dark:text-gray-100">{{ formatDate(meal.date) }} - {{ meal.name }}</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Prix: {{ formatCurrency(meal.price) }}</p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Réduction: {{ formatCurrency(meal.discount) }}</p>
                </div>
              </div>
            </div>
            <div class="items-center px-4 py-3">
              <button
                id="ok-btn"
                @click="closeUserDetails"
                class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
              >
                Fermer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import LoadingWheel from '@/components/shared/LoadingWheel.vue';
import DateRangePicker from './DateRangePicker.vue';
import StatisticsTable from './StatisticsTable.vue';

export default {
  name: 'CollaboratorStatistics',
  components: {
    LoadingWheel,
    DateRangePicker,
    StatisticsTable,
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
      return new Date(year, month - 1).toLocaleString('fr-FR', { month: 'long', year: 'numeric' });
    };

    const formatCurrency = (amount) => {
      return new Intl.NumberFormat('fr-MA', { style: 'currency', currency: 'MAD' }).format(amount);
    };

    const formatDate = (dateStr) => {
      return new Date(dateStr).toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    };

    const showUserDetails = (user) => {
      selectedUser.value = user;
    };

    const closeUserDetails = () => {
      selectedUser.value = null;
    };

    const tableHeaders = [
      { key: 'name', label: 'Nom' },
      { key: 'matriculation_number', label: 'Numéro de Matricule' },
      { key: 'category', label: 'Catégorie' },
      { key: 'total_without_discount', label: 'Total (Sans Réduction)', format: formatCurrency },
      { key: 'total_with_discount', label: 'Total (Avec Réduction)', format: formatCurrency },
      { key: 'actions', label: 'Détails' },
    ];

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
      tableHeaders,
    };
  },
};
</script>
