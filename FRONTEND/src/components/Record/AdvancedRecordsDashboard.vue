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
      <h2 class="text-xl md:text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Filters</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
          <input v-model="filters.startDate" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
          <input v-model="filters.endDate" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User Category</label>
          <select v-model="filters.userCategory" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">All Categories</option>
            <option v-for="category in userCategories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
          <input v-model="filters.search" type="text" placeholder="Name or Matriculation Number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
      </div>
      <div class="mt-4 flex justify-end">
        <button @click="fetchRecords" class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
          Apply Filters
        </button>
      </div>
    </div>

    <!-- Records Section -->
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="spinner"></div>
    </div>
    <div v-else-if="groupedRecords.length" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
      <div class="p-4 md:p-6">
        <h2 class="text-xl md:text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Records</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <template v-for="(group, groupIndex) in groupedRecords" :key="groupIndex">
                <!-- Week Schedule Header -->
                <tr class="bg-gray-50 dark:bg-gray-700">
                  <th colspan="100%" class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ group.weekSchedule }}
                  </th>
                </tr>
                <!-- Records -->
                <tr v-for="record in group.records" :key="record.date" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ formatDate(record.date) }}
                  </td>
                  <td class="px-3 md:px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                      <div v-for="meal in record.meals" :key="meal.meal_name" class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
                        <div class="flex justify-start">
                          <div class="font-bold text-lg bg-gradient-to-r from-green-400 to-emerald-500 dark:bg-gray-800 text-transparent bg-clip-text p-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            {{ meal.meal_name }}
                          </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ meal.persons_count }} persons</div>
                        <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ formatPrice(meal.total_with_discount) }}</div>
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
      No records found for the selected criteria.
    </div>

    <!-- Meal Totals Summary -->
    <div v-if="mealTotals.length" class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 md:p-6">
      <h2 class="text-xl md:text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Meal Totals Summary</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="total in mealTotals" :key="total.meal_name" class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
          <h3 class="font-medium text-lg text-indigo-600 dark:text-indigo-400">{{ total.meal_name }}</h3>
          <p class="text-sm text-gray-600 dark:text-gray-300">Total Persons: {{ total.total_persons }}</p>
          <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Total with Discount: {{ formatPrice(total.total_with_discount) }}</p>
          <p class="text-sm text-gray-600 dark:text-gray-300">Total without Discount: {{ formatPrice(total.total_without_discount) }}</p>
        </div>
      </div>
    </div> 

    <!-- Download PDF Button -->
    <div class="mt-8 flex justify-end">
      <button @click="downloadPDF" :disabled="!groupedRecords.length" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">
        Download PDF
      </button>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

export default {
  name: 'AdvancedRecordsDashboard',
  setup() {
    const store = useStore();
    const records = ref([]);
    const loading = ref(false);
    const userCategories = ref([]);
    const filters = ref({
      startDate: '',
      endDate: '',
      userCategory: '',
      search: '',
    });

    const groupedRecords = computed(() => {
      const groups = [];
      let currentGroup = null;

      records.value.forEach(record => {
        if (!currentGroup || currentGroup.weekSchedule !== record.week_schedule) {
          currentGroup = {
            weekSchedule: record.week_schedule,
            records: []
          };
          groups.push(currentGroup);
        }
        currentGroup.records.push(record);
      });

      return groups;
    });

    const mealTotals = computed(() => {
      const totals = {};
      records.value.forEach(record => {
        record.meals.forEach(meal => {
          if (!totals[meal.meal_name]) {
            totals[meal.meal_name] = {
              meal_name: meal.meal_name,
              total_persons: 0,
              total_with_discount: 0,
              total_without_discount: 0,
            };
          }
          totals[meal.meal_name].total_persons += meal.persons_count;
          totals[meal.meal_name].total_with_discount += parseFloat(meal.total_with_discount);
          totals[meal.meal_name].total_without_discount += parseFloat(meal.total_no_discount);
        });
      });
      return Object.values(totals);
    });

    const fetchRecords = async () => {
      loading.value = true;
      try {
        const response = await store.dispatch('advancedRecord/fetchAdvancedRecords', {
          start_date: filters.value.startDate,
          end_date: filters.value.endDate,
          user_category: filters.value.userCategory || null,
          search: filters.value.search || null
        });
        records.value = response.data;
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
        doc.text('Advanced Records Report', 14, 22);
        doc.setFontSize(11);
        doc.text(`From: ${filters.value.startDate} To: ${filters.value.endDate}`, 14, 30);
  
        records.value.forEach((dayRecord, index) => {
          doc.setFontSize(14);
          doc.text(formatDate(dayRecord.date), 14, index === 0 ? 40 : doc.lastAutoTable.finalY + 20);
  
          doc.autoTable({
            startY: index === 0 ? 50 : doc.lastAutoTable.finalY + 30,
            head: [['Meal', 'Time', 'Persons', 'Total (No Discount)', 'Total (With Discount)']],
            body: dayRecord.meals.map(meal => [
              meal.meal_name,
              `${meal.start_time} - ${meal.end_time}`,
              meal.persons_count,
              formatPrice(meal.total_no_discount),
              formatPrice(meal.total_with_discount)
            ]),
          });
        });
  
        doc.save('advanced_records_report.pdf');
      };

    onMounted(() => {
      fetchUserCategories();
    });

    return {
      groupedRecords,
      mealTotals,
      loading,
      userCategories,
      filters,
      fetchRecords,
      formatDate,
      formatPrice,
      downloadPDF,
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
