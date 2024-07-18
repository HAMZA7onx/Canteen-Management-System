<template>
    <div class="advanced-records-dashboard p-6 bg-gradient-to-br from-indigo-50 to-purple-100 dark:from-gray-900 dark:to-indigo-900 min-h-screen">
      <h1 class="text-4xl font-bold mb-8 text-center bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">Advanced Records Dashboard</h1>
  
      <!-- Filters Section -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 mb-8">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Filters</h2>
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
      <div v-else-if="records.length" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
        <div class="p-6">
          <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Records</h2>
          <div v-for="(dayRecord, index) in records" :key="index" class="mb-8 last:mb-0">
            <h3 class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-400">{{ formatDate(dayRecord.date) }}</h3>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Meal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Persons</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total (No Discount)</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total (With Discount)</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                  <tr v-for="meal in dayRecord.meals" :key="meal.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ meal.meal_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ meal.start_time }} - {{ meal.end_time }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ meal.persons_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ formatPrice(meal.total_no_discount) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ formatPrice(meal.total_with_discount) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 text-center text-gray-500 dark:text-gray-400">
        No records found for the selected criteria.
      </div>
  
      <!-- Download PDF Button -->
      <div class="mt-8 flex justify-end">
        <button @click="downloadPDF" :disabled="!records.length" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">
          Download PDF
        </button>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
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
          if (error.response) {
            console.error('Response data:', error.response.data);
            console.error('Response status:', error.response.status);
            console.error('Response headers:', error.response.headers);
          }
          // Show error message to user
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
        return new Date(dateString).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
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
        records,
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