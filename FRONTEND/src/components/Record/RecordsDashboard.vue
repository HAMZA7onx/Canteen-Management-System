<template>
  <div class="records-dashboard p-2 sm:p-6 pt-16 sm:pt-24 min-h-screen bg-gradient-to-br from-purple-50 to-indigo-100 dark:from-gray-900 dark:to-indigo-900 transition-all duration-300">
    <section class="bg-gradient-to-r from-purple-100 to-indigo-200 dark:from-gray-800 dark:to-indigo-900 py-6 px-4 rounded-xl shadow-lg mb-6">
  <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
    Records Dashboard Features
  </h2>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="flex items-center">
      <svg class="h-6 w-6 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      <span class="text-sm text-gray-700 dark:text-gray-300">Hierarchical Date Navigation</span>
    </div>
    <div class="flex items-center">
      <svg class="h-6 w-6 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <span class="text-sm text-gray-700 dark:text-gray-300">Monthly Totals Summary</span>
    </div>
    <div class="flex items-center">
      <svg class="h-6 w-6 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
      </svg>
      <span class="text-sm text-gray-700 dark:text-gray-300">Detailed Day View</span>
    </div>
    <div class="flex items-center">
      <svg class="h-6 w-6 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
      <span class="text-sm text-gray-700 dark:text-gray-300">User Search Functionality</span>
    </div>
  </div>
</section>

    <!-- Years List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
      <ul class="divide-y divide-gray-200 dark:divide-gray-700">
        <li v-for="year in years" :key="year" class="p-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150 ease-in-out">
          <div class="flex justify-between items-center">
            <span class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ year }}</span>
            <button @click="expandYear(year)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition-colors duration-150 ease-in-out">
              {{ expandedYear === year ? 'Collapse' : 'Expand' }}
            </button>
          </div>
          
          <!-- Months for expanded year -->
          <div v-if="expandedYear === year" class="mt-4">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Months of {{ year }}</h3>
            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
              <button
                v-for="month in months"
                :key="month"
                @click="expandMonth(month)"
                class="p-2 bg-white dark:bg-gray-600 rounded-lg shadow hover:shadow-md transition-shadow duration-150 ease-in-out text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-900"
              >
                {{ monthName(month) }}
              </button>
            </div>
          </div>

          <!-- Days for expanded month -->
          <div v-if="expandedMonth && expandedYear === year" class="mt-4">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Days of {{ monthName(expandedMonth) }}</h3>
            <div class="grid grid-cols-5 sm:grid-cols-7 gap-2">
              <button
                v-for="day in days"
                :key="day"
                @click="showDayRecords(day)"
                class="p-2 bg-white dark:bg-gray-500 rounded-full shadow hover:shadow-md transition-shadow duration-150 ease-in-out text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-800"
              >
                {{ day }}
              </button>
            </div>
          </div>

          <!-- Monthly Totals Section -->
          <div v-if="expandedMonth && expandedYear === year && monthlyTotals.length" class="mt-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Monthly Totals</h3>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-800">
                  <tr>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Without Discount</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total With Discount</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                  <tr v-for="total in monthlyTotals" :key="total.id" class="hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ total.email }}</td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">DH {{ total.total_without_discount.toFixed(2) }}</td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">DH {{ total.total_with_discount.toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <!-- Day Records Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-2 z-50" id="my-modal">
        <div class="relative bg-white dark:bg-gray-800 w-full max-w-lg sm:max-w-xl md:max-w-2xl lg:max-w-4xl xl:max-w-6xl mx-auto rounded-lg shadow-xl">
          <div class="p-4 max-h-[80vh] overflow-y-auto modal-content">
            <h3 class="text-xl sm:text-2xl leading-6 font-bold text-gray-900 dark:text-gray-100 mb-4">
              Records for {{ expandedYear }}-{{ monthName(expandedMonth) }}-{{ expandedDay }}
            </h3>
            <div class="mt-4">
              <div v-if="loading" class="flex justify-center items-center h-64">
                <div class="spinner"></div>
              </div>
              <div v-else-if="records.length" class="space-y-6">
                <div v-for="(record, index) in records" :key="index"
                     class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-all duration-300 ease-in-out hover:shadow-lg">
                  <div class="p-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                      <h2 class="text-lg font-semibold text-indigo-600 dark:text-indigo-400 mb-2 sm:mb-0">
                        {{ record.week_schedule_name }}
                      </h2>
                      <span class="px-3 py-1 text-sm font-semibold text-indigo-600 bg-indigo-100 rounded-full dark:bg-indigo-900 dark:text-indigo-200">
                        {{ record.meal_name }}
                      </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                      <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Time</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">
                          {{ record.start_time }} - {{ record.end_time }}
                        </p>
                      </div>
                      <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Price</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">DH {{ record.price }}</p>
                      </div>
                      <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Badge Count</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ record.badge_count }}</p>
                      </div>
                      <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Users</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">
                          {{ record.users.length }}
                        </p>
                      </div>
                    </div>
                    <button @click="toggleUserList(index)"
                            class="w-full px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
                      {{ showUsersMap[index] ? 'Hide Users' : 'Show Users' }}
                    </button>
                  </div>
                  <div v-if="showUsersMap[index]" class="px-4 py-4 bg-gray-100 dark:bg-gray-600">
                    <h3 class="font-semibold mb-2 text-gray-700 dark:text-gray-300">Users:</h3>
                   
                    <!-- Search bar -->
                    <div class="mb-4">
                      <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search by email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                      />
                    </div>

                    <div class="overflow-x-auto">
                      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                          <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                              Email
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                              Badge ID
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                              Category Discount
                            </th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                              User Category
                            </th>
                          </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                          <tr v-for="(user, userIndex) in filteredUsers" :key="userIndex" class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                              {{ user.email }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                              {{ user.badge_id }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                              {{ user.category_discount }} DH
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                              {{ user.user_category_name }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <p v-else class="text-center text-gray-500 dark:text-gray-400 mt-6">
                No records found for the selected date.
              </p>
            </div>
          </div>
         
          <div class="w-full flex justify-center bg-gray-100 dark:bg-gray-700 px-4 py-4 rounded-b-lg sticky bottom-0">
            <button @click="closeModal"
                    class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
              Close
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>


<script>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';

export default {
  name: 'RecordsDashboard',
  setup() {
    const store = useStore();

    const expandedYear = ref(null);
    const expandedMonth = ref(null);
    const expandedDay = ref(null);
    const showModal = ref(false);
    const loading = ref(false);
    const searchQuery = ref('');
    const showUsersMap = ref({});

    const monthName = (month) => {
      return new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
    };

    const years = computed(() => store.getters['record/years']);
    const months = computed(() => store.getters['record/months']);
    const days = computed(() => store.getters['record/days']);
    const monthlyTotals = computed(() => store.getters['record/monthlyTotals']);

    const records = computed(() => 
      store.getters['record/records'].map(record => ({
        ...record,
        users: JSON.parse(record.users)
      }))
    );

    const filteredUsers = computed(() => {
      if (!records.value.length) return [];
      const activeRecord = records.value.find((_, index) => showUsersMap.value[index]);
      if (!activeRecord) return [];
      
      return activeRecord.users.filter(user => 
        user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
      );
    });

    const expandYear = async (year) => {
      if (expandedYear.value === year) {
        expandedYear.value = null;
        expandedMonth.value = null;
        expandedDay.value = null;
      } else {
        expandedYear.value = year;
        await store.dispatch('record/setSelectedYear', year);
        await store.dispatch('record/fetchMonths');
      }
    };

    const expandMonth = async (month) => {
      if (expandedMonth.value === month) {
        expandedMonth.value = null;
        expandedDay.value = null;
      } else {
        expandedMonth.value = month;
        await store.dispatch('record/setSelectedMonth', month);
        await Promise.all([
          store.dispatch('record/fetchDays'),
          store.dispatch('record/fetchMonthlyTotals')
        ]);
      }
    };

    const showDayRecords = async (day) => {
      expandedDay.value = day;
      await store.dispatch('record/setSelectedDay', day);
      showModal.value = true;
      loading.value = true;
      try {
        await store.dispatch('record/fetchDayRecords');
        showUsersMap.value = {}; // Reset showUsers map
      } finally {
        loading.value = false;
      }
    };

    const toggleUserList = (index) => {
      showUsersMap.value = {
        ...showUsersMap.value,
        [index]: !showUsersMap.value[index]
      };
      searchQuery.value = ''; // Reset search query when toggling
    };

    const closeModal = () => {
      showModal.value = false;
    };

    return {
      expandedYear,
      expandedMonth,
      expandedDay,
      showModal,
      loading,
      searchQuery,
      monthName,
      years,
      months,
      days,
      records,
      filteredUsers,
      expandYear,
      expandMonth,
      showDayRecords,
      toggleUserList,
      closeModal,
      monthlyTotals,
      showUsersMap,
    };
  },
  mounted() {
    this.$store.dispatch('record/fetchYears');
  },
};
</script>

<style scoped>
@keyframes gradientAnimation {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.modal-content {
  scroll-behavior: smooth;
}

.records-dashboard {
  background-size: 200% 200%;
  animation: gradientAnimation 15s ease infinite;
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .relative,
.modal-leave-active .relative {
  transition: all 0.3s ease;
}

.modal-enter-from .relative,
.modal-leave-to .relative {
  opacity: 0;
  transform: scale(0.95);
}

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border-left-color: #09f;
  animation: spin 1s ease infinite;
}

@keyframes spin {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }

  .max-h-60 {
    max-height: 15rem;
  }

  .max-h-60::-webkit-scrollbar {
    width: 8px;
  }

  .max-h-60::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  .max-h-60::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
  }

  .max-h-60::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  /* New styles for the table */
  table {
    border-collapse: separate;
    border-spacing: 0;
  }

  th, td {
    border-bottom-width: 1px;
    border-bottom-style: solid;
  }

  th {
    position: sticky;
    top: 0;
    z-index: 10;
  }
</style>