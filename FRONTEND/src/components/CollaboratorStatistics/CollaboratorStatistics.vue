<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-100 to-indigo-200 dark:from-gray-900 dark:to-indigo-900 py-4 sm:py-6 px-2 sm:px-4 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-600 mb-4 sm:mb-6 text-center">Statistiques des Collaborateurs</h1>

      <!-- Date Range Picker -->
      <DateRangePicker
        v-model:startDate="startDate"
        v-model:endDate="endDate"
        @fetch="fetchStatistics"
        class="mb-4 sm:mb-6"
      />

      <!-- Statistics Tables -->
      <div v-if="isLoading" class="text-center">
        <loading-wheel />
      </div>
      <div v-else-if="error" class="text-red-600 dark:text-red-400 text-center">
        {{ error }}
      </div>
      <div v-else-if="Object.keys(statistics).length === 0" class="text-center py-8">
        <p class="text-lg text-gray-600 dark:text-gray-400">Aucune donnée disponible. Veuillez sélectionner une plage de dates et cliquer sur "Fetch Statistics".</p>
      </div>
      <div v-else>
        <div v-for="(monthData, month) in statistics" :key="month" class="mb-8 sm:mb-12">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ formatMonth(month) }}</h2>
          
          <!-- Search Bar -->
          <div class="mb-4">
            <input 
              v-model="searchQueries[month]" 
              @input="filterTable(month)"
              placeholder="Rechercher par nom..." 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
          </div>

          <div v-if="paginatedData[month] && paginatedData[month].length > 0">
            <StatisticsTable
              :headers="tableHeaders"
              :users="paginatedData[month]"
              @view-details="showUserDetails"
            />

            <!-- Pagination -->
            <div class="mt-4 flex flex-col sm:flex-row justify-between items-center">
              <button 
                @click="prevPage(month)" 
                :disabled="currentPage[month] === 1"
                class="w-full sm:w-auto px-4 py-2 mb-2 sm:mb-0 bg-indigo-600 text-white rounded-md disabled:opacity-50"
              >
                Précédent
              </button>
              <span class="mb-2 sm:mb-0">Page {{ currentPage[month] }} sur {{ totalPages[month] }}</span>
              <button 
                @click="nextPage(month)" 
                :disabled="currentPage[month] === totalPages[month]"
                class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50"
              >
                Suivant
              </button>
            </div>
          </div>
          <div v-else class="text-center py-4">
            <p class="text-gray-600 dark:text-gray-400">Aucun résultat trouvé pour ce mois.</p>
          </div>
        </div>
      </div>

      <!-- User Details Modal -->
      <div v-if="selectedUser" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="my-modal">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
          <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">{{ selectedUser.name }} - Détails des Repas</h3>
            <div class="mt-2 px-2 py-3 max-h-60 sm:max-h-96 overflow-y-auto">
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
import { ref, computed, reactive } from 'vue';
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
    const itemsPerPage = 10;

    const statistics = computed(() => store.getters['collaboratorStatistics/statistics']);
    const isLoading = computed(() => store.getters['collaboratorStatistics/isLoading']);
    const error = computed(() => store.getters['collaboratorStatistics/error']);

    const currentPage = reactive({});
    const totalPages = reactive({});
    const searchQueries = reactive({});
    const filteredData = reactive({});
    const paginatedData = reactive({});

    const initializePagination = () => {
      Object.keys(statistics.value).forEach(month => {
        currentPage[month] = 1;
        totalPages[month] = Math.ceil(Object.keys(statistics.value[month]).length / itemsPerPage);
        searchQueries[month] = '';
        filteredData[month] = Object.values(statistics.value[month]);
        updatePaginatedData(month);
      });
    };

    const updatePaginatedData = (month) => {
      const startIndex = (currentPage[month] - 1) * itemsPerPage;
      paginatedData[month] = filteredData[month].slice(startIndex, startIndex + itemsPerPage);
    };

    const filterTable = (month) => {
      filteredData[month] = Object.values(statistics.value[month]).filter(user => 
        user.name.toLowerCase().includes(searchQueries[month].toLowerCase())
      );
      currentPage[month] = 1;
      totalPages[month] = Math.ceil(filteredData[month].length / itemsPerPage);
      updatePaginatedData(month);
    };

    const prevPage = (month) => {
      if (currentPage[month] > 1) {
        currentPage[month]--;
        updatePaginatedData(month);
      }
    };

    const nextPage = (month) => {
      if (currentPage[month] < totalPages[month]) {
        currentPage[month]++;
        updatePaginatedData(month);
      }
    };

    const fetchStatistics = () => {
      console.log('Fetching statistics:', startDate.value, endDate.value);
      store.dispatch('collaboratorStatistics/fetchStatistics', {
        startDate: startDate.value,
        endDate: endDate.value,
      }).then(() => {
        initializePagination();
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
      currentPage,
      totalPages,
      searchQueries,
      paginatedData,
      fetchStatistics,
      formatMonth,
      formatCurrency,
      formatDate,
      showUserDetails,
      closeUserDetails,
      tableHeaders,
      filterTable,
      prevPage,
      nextPage,
    };
  },
};
</script>
