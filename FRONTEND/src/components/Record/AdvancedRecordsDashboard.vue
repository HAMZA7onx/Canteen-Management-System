<template>
  <div id="advanced-records-dashboard" class="advanced-records-dashboard p-4 md:p-6 bg-gradient-to-br from-indigo-50 to-purple-100 dark:from-gray-900 dark:to-indigo-900 min-h-screen">
    <section class="bg-gradient-to-r from-green-50 to-teal-100 dark:from-gray-800 dark:to-gray-900 py-6 px-4 rounded-xl shadow-lg mb-6">
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
        Dashboard Features
      </h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="flex items-center">
          <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
          </svg>
          <span class="text-sm text-gray-700 dark:text-gray-300">Advanced Filtering</span>
        </div>
        <div class="flex items-center">
          <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <span class="text-sm text-gray-700 dark:text-gray-300">Meal Totals Summary</span>
        </div>
        <div class="flex items-center">
          <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
          </svg>
          <span class="text-sm text-gray-700 dark:text-gray-300">PDF Export</span>
        </div>
        <div class="flex items-center">
          <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <span class="text-sm text-gray-700 dark:text-gray-300">Weekly Meal Grouping</span>
        </div>
      </div>
    </section>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 md:p-6 mb-6 md:mb-8">
      <h2 class="text-xl md:text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Filtres</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de Début</label>
          <input v-model="filters.startDate" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de Fin</label>
          <input v-model="filters.endDate" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catégorie d'Utilisateur</label>
          <select v-model="filters.userCategory" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">All Categories</option>
            <option v-for="category in userCategories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recherche</label>
          <input v-model="filters.search" type="text" placeholder="Name or Matriculation Number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
      </div>
      <div class="mt-4 flex justify-end">
        <button @click="fetchRecords" class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
          Appliquer les Filtres
        </button>
      </div>
    </div>

    <!-- Records Section -->
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="spinner"></div>
    </div>
    <div v-else-if="groupedRecords.length" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
      <div class="p-4 md:p-6">
        <h2 class="text-xl md:text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Enregistrements</h2>
        
        <!-- Search and Pagination for Records -->
        <div class="mb-4 flex flex-wrap items-center justify-between">
          <input v-model="recordsSearch" @input="filterRecords" type="text" placeholder="Search by date" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white mb-2 sm:mb-0">
          <div class="flex items-center">
            <button @click="prevRecordsPage" :disabled="currentRecordsPage === 1" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-l-md">Prev</button>
            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-600">{{ currentRecordsPage }} / {{ totalRecordsPages }}</span>
            <button @click="nextRecordsPage" :disabled="currentRecordsPage === totalRecordsPages" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-r-md">Next</button>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <template v-for="(dateRecord, groupIndex) in paginatedRecords" :key="groupIndex">
                <!-- Date Header -->
                <tr class="bg-gray-50 dark:bg-gray-700">
                  <th colspan="100%" class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ formatDate(dateRecord.date) }}
                  </th>
                </tr>
                <!-- Meals -->
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-3 md:px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                      <div v-for="meal in dateRecord.meals" :key="meal.meal_name" class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
                        <div class="flex justify-between">
                          <div class="font-bold text-lg bg-gradient-to-r from-green-400 to-emerald-500 dark:bg-gray-800 text-transparent bg-clip-text p-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            {{ meal.meal_name }}
                          </div>
                          <div class="font-bold text-lg bg-gradient-to-r from-green-400 to-emerald-500 dark:bg-gray-800 text-transparent bg-clip-text p-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            DH {{ meal.price }}
                          </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ meal.badge_count }} personnes</div>
                        <div class="text-sm font-semibold text-gray-800 dark:text-gray-200"><span class="text-[10px]">Total avec Remise:</span> {{ formatPrice(meal.total_with_discount) }}</div>
                        <div class="text-sm font-semibold text-gray-800 dark:text-gray-200"><span class="text-[10px]">Total sans Remise:</span> {{ formatPrice(meal.total_without_discount) }}</div>
                      </div>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 text-center text-gray-500 dark:text-gray-400">
      Aucun enregistrement trouvé pour les critères sélectionnés.
    </div>

    <!-- Meal Totals Summary -->
    <div v-if="mealTotals.length" class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 md:p-6">
      <h2 class="text-xl md:text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Résumé des Totaux de Repas</h2>
      
      <!-- Search and Pagination for Meal Totals -->
      <div class="mb-4 flex flex-wrap items-center justify-between">
        <input v-model="mealTotalsSearch" @input="filterMealTotals" type="text" placeholder="Search by meal name" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white mb-2 sm:mb-0">
        <div class="flex items-center">
          <button @click="prevMealTotalsPage" :disabled="currentMealTotalsPage === 1" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-l-md">Prev</button>
          <span class="px-3 py-1 bg-gray-100 dark:bg-gray-600">{{ currentMealTotalsPage }} / {{ totalMealTotalsPages }}</span>
          <button @click="nextMealTotalsPage" :disabled="currentMealTotalsPage === totalMealTotalsPages" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-r-md">Next</button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="total in paginatedMealTotals" :key="total.meal_name" class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg transform hover:scale-105 transition-all duration-200">
          <h3 class="font-medium text-lg text-indigo-600 dark:text-indigo-400">{{ total.meal_name }}</h3>
          <p class="text-sm text-gray-600 dark:text-gray-300">Total Persons: {{ total.total_persons }}</p>
          <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Total avec Remise : {{ formatPrice(total.total_with_discount) }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-300">Total sans Remise: {{ formatPrice(total.total_without_discount) }}</p>
        </div>
      </div>
    </div>

    <!-- Monthly Totals -->
    <div v-if="monthlyTotals.length" class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 md:p-6">
      <h2 class="text-xl md:text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Monthly Totals</h2>
      
      <!-- Search and Pagination for Monthly Totals -->
      <div class="mb-4 flex flex-wrap items-center justify-between">
        <input v-model="monthlyTotalsSearch" @input="filterMonthlyTotals" type="text" placeholder="Search by email" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white mb-2 sm:mb-0">
        <div class="flex items-center">
          <button @click="prevPage" :disabled="currentPage === 1" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-l-md">Prev</button>
          <span class="px-3 py-1 bg-gray-100 dark:bg-gray-600">{{ currentPage }} / {{ totalPages }}</span>
          <button @click="nextPage" :disabled="currentPage === totalPages" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-r-md">Next</button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="total in paginatedMonthlyTotals" :key="total.email" class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg transform hover:scale-105 transition-all duration-200">
          <h3 class="font-medium text-lg text-indigo-600 dark:text-indigo-400 truncate" :title="total.email">{{ total.email }}</h3>
          <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Total avec Remise : {{ formatPrice(total.total_with_discount) }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-300">Total sans Remise: {{ formatPrice(total.total_without_discount) }}</p>
        </div>
      </div>
    </div>

    <!-- Download PDF Button -->
    <div class="mt-8 flex justify-end">
      <button @click="downloadPDF" :disabled="!groupedRecords.length" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">
        Télécharger PDF
      </button>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, watch } from 'vue';
import { useStore } from 'vuex';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

export default {
  name: 'AdvancedRecordsDashboard',
  setup() {
    const store = useStore();
    const records = ref([]);
    const monthlyTotals = ref([]);
    const loading = ref(false);
    const userCategories = ref([]);
    const filters = ref({
      startDate: '',
      endDate: '',
      userCategory: '',
      search: '',
    });

    // Records pagination and search
    const recordsSearch = ref('');
    const filteredRecords = ref([]);
    const currentRecordsPage = ref(1);
    const recordsPerPage = 5; // Adjust as needed

    // Meal Totals pagination and search
    const mealTotalsSearch = ref('');
    const filteredMealTotals = ref([]);
    const currentMealTotalsPage = ref(1);
    const mealTotalsPerPage = 6; // Adjust as needed

    // Monthly Totals pagination and search
    const monthlyTotalsSearch = ref('');
    const filteredMonthlyTotals = ref([]);
    const currentPage = ref(1);
    const itemsPerPage = 12; // Adjust as needed

    const groupedRecords = computed(() => {
      return records.value.map(dateRecord => ({
        date: dateRecord.date,
        meals: dateRecord.meals.map(meal => ({
          ...meal,
          badge_count: meal.badge_count,
          total_with_discount: meal.total_with_discount,
          total_without_discount: meal.total_without_discount
        }))
      }));
    });

    const mealTotals = computed(() => {
      const totals = {};
      records.value.forEach(dateRecord => {
        dateRecord.meals.forEach(meal => {
          if (!totals[meal.meal_name]) {
            totals[meal.meal_name] = {
              meal_name: meal.meal_name,
              total_persons: 0,
              total_with_discount: 0,
              total_without_discount: 0,
            };
          }
          totals[meal.meal_name].total_persons += meal.badge_count;
          totals[meal.meal_name].total_with_discount += parseFloat(meal.total_with_discount);
          totals[meal.meal_name].total_without_discount += parseFloat(meal.total_without_discount);
        });
      });
      return Object.values(totals);
    });

    // Records filtering and pagination
    const filterRecords = () => {
      filteredRecords.value = groupedRecords.value.filter(record => 
        formatDate(record.date).toLowerCase().includes(recordsSearch.value.toLowerCase())
      );
      currentRecordsPage.value = 1;
    };

    const totalRecordsPages = computed(() => 
      Math.ceil(filteredRecords.value.length / recordsPerPage)
    );

    const paginatedRecords = computed(() => {
      const start = (currentRecordsPage.value - 1) * recordsPerPage;
      const end = start + recordsPerPage;
      return filteredRecords.value.slice(start, end);
    });

    const prevRecordsPage = () => {
      if (currentRecordsPage.value > 1) currentRecordsPage.value--;
    };

    const nextRecordsPage = () => {
      if (currentRecordsPage.value < totalRecordsPages.value) currentRecordsPage.value++;
    };

    // Meal Totals filtering and pagination
    const filterMealTotals = () => {
      filteredMealTotals.value = mealTotals.value.filter(total => 
        total.meal_name.toLowerCase().includes(mealTotalsSearch.value.toLowerCase())
      );
      currentMealTotalsPage.value = 1;
    };

    const totalMealTotalsPages = computed(() => 
      Math.ceil(filteredMealTotals.value.length / mealTotalsPerPage)
    );

    const paginatedMealTotals = computed(() => {
      const start = (currentMealTotalsPage.value - 1) * mealTotalsPerPage;
      const end = start + mealTotalsPerPage;
      return filteredMealTotals.value.slice(start, end);
    });

    const prevMealTotalsPage = () => {
      if (currentMealTotalsPage.value > 1) currentMealTotalsPage.value--;
    };

    const nextMealTotalsPage = () => {
      if (currentMealTotalsPage.value < totalMealTotalsPages.value) currentMealTotalsPage.value++;
    };

    // Monthly Totals filtering and pagination
    const filterMonthlyTotals = () => {
      filteredMonthlyTotals.value = monthlyTotals.value.filter(total => 
        total.email.toLowerCase().includes(monthlyTotalsSearch.value.toLowerCase())
      );
      currentPage.value = 1;
    };

    const totalPages = computed(() => 
      Math.ceil(filteredMonthlyTotals.value.length / itemsPerPage)
    );

    const paginatedMonthlyTotals = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage;
      const end = start + itemsPerPage;
      return filteredMonthlyTotals.value.slice(start, end);
    });

    const prevPage = () => {
      if (currentPage.value > 1) currentPage.value--;
    };

    const nextPage = () => {
      if (currentPage.value < totalPages.value) currentPage.value++;
    };

    const fetchRecords = async () => {
      loading.value = true;
      try {
        const response = await store.dispatch('advancedRecord/fetchAdvancedRecords', {
          start_date: filters.value.startDate,
          end_date: filters.value.endDate,
          user_category: filters.value.userCategory || null,
          search: filters.value.search || null
        });
        records.value = response.data.records;
        monthlyTotals.value = response.data.monthlyTotals;
        filterRecords();
        filterMealTotals();
        filterMonthlyTotals();
      } catch (error) {
        console.error('Error fetching records:', error);
        alert('An error occurred while fetching records. Please try again.');
      } finally {
        loading.value = false;
      }
    };

    const fetchUserCategories = async () => {
      try {
        const response = await store.dispatch('userCategory/fetchUserCategories');
        userCategories.value = response.data;
      } catch (error) {
        console.error('Error fetching user categories:', error);
      }
    };

    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
    };

    const formatPrice = (price) => {
      const numPrice = parseFloat(price);
      return isNaN(numPrice) ? 'N/A' : `DH ${numPrice.toFixed(2)}`;
    };

    const downloadPDF = () => {
      const doc = new jsPDF();
      doc.setFontSize(18);
      doc.text('Rapport sur les enregistrements ', 14, 22);
      doc.setFontSize(11);
      doc.text(`Du: ${filters.value.startDate} à: ${filters.value.endDate}`, 14, 30);

      records.value.forEach((dateRecord, index) => {
        doc.setFontSize(14);
        doc.text(formatDate(dateRecord.date), 14, index === 0 ? 40 : doc.lastAutoTable.finalY + 20);

        doc.autoTable({
          startY: index === 0 ? 50 : doc.lastAutoTable.finalY + 30,
          head: [['Repat', 'Temps', 'Persons', 'Total (Sans remise)', 'Total (avec remise)']],
          body: dateRecord.meals.map(meal => [
            meal.meal_name,
            `${meal.start_time} - ${meal.end_time}`,
            meal.badge_count,
            formatPrice(meal.total_without_discount),
            formatPrice(meal.total_with_discount)
          ]),
        });
      });

      doc.save('advanced_records_report.pdf');
    };

    onMounted(() => {
      fetchUserCategories();
    });

    watch([records, mealTotals, monthlyTotals], () => {
      filterRecords();
      filterMealTotals();
      filterMonthlyTotals();
    });

    return {
      groupedRecords,
      mealTotals,
      monthlyTotals,
      loading,
      userCategories,
      filters,
      fetchRecords,
      formatDate,
      formatPrice,
      downloadPDF,
      // Records pagination and search
      recordsSearch,
      paginatedRecords,
      currentRecordsPage,
      totalRecordsPages,
      prevRecordsPage,
      nextRecordsPage,
      // Meal Totals pagination and search
      mealTotalsSearch,
      paginatedMealTotals,
      currentMealTotalsPage,
      totalMealTotalsPages,
      prevMealTotalsPage,
      nextMealTotalsPage,
      // Monthly Totals pagination and search
      monthlyTotalsSearch,
      paginatedMonthlyTotals,
      currentPage,
      totalPages,
      prevPage,
      nextPage,
    };
  },
};
</script>

<style scoped>
.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border-left-color: #09f;
  animation: spin 1s ease infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

