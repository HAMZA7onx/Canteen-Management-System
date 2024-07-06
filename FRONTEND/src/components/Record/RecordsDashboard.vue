<template>
  <div class="records-dashboard p-6 pt-24 min-h-screen bg-gradient-to-br from-purple-50 to-indigo-100 dark:from-gray-900 dark:to-indigo-900 transition-all duration-300">
    <h1 class="text-4xl font-bold mb-8 text-center bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-400 dark:to-indigo-400">Records Dashboard</h1>

    <!-- Years Table -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-xl">
      <table class="min-w-full">
        <thead>
          <tr class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-800 dark:to-indigo-800 text-white">
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Year</th>
            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="year in years" :key="year" class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150 ease-in-out">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ year }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              <button @click="expandYear(year)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition-colors duration-150 ease-in-out">
                {{ expandedYear === year ? 'Collapse' : 'Expand' }}
              </button>
            </td>
          </tr>
          <!-- Months for expanded year -->
          <tr v-if="expandedYear && months.length">
            <td colspan="2" class="px-6 py-4">
              <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-inner p-4">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Months of {{ expandedYear }}</h3>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
                  <button
                    v-for="month in months"
                    :key="month"
                    @click="expandMonth(month)"
                    class="px-4 py-2 bg-white dark:bg-gray-600 rounded-lg shadow hover:shadow-md transition-shadow duration-150 ease-in-out text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-900"
                  >
                    {{ monthName(month) }}
                  </button>
                </div>
              </div>
            </td>
          </tr>
          <!-- Days for expanded month -->
          <tr v-if="expandedMonth && days.length">
            <td colspan="2" class="px-6 py-4">
              <div class="bg-gray-100 dark:bg-gray-600 rounded-lg shadow-inner p-4">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Days of {{ monthName(expandedMonth) }}</h3>
                <div class="grid grid-cols-5 sm:grid-cols-7 md:grid-cols-10 gap-2">
                  <button
                    v-for="day in days"
                    :key="day"
                    @click="showDayRecords(day)"
                    class="px-3 py-2 bg-white dark:bg-gray-500 rounded-full shadow hover:shadow-md transition-shadow duration-150 ease-in-out text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-indigo-800"
                  >
                    {{ day }}
                  </button>
                </div>
              </div>

                  <!-- Monthly Totals Section -->
    <div v-if="monthlyTotals.length" class="mt-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Monthly Totals</h3>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-800">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Without Discount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total With Discount</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                  <tr v-for="total in monthlyTotals" :key="total.id" class="hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ total.email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${{ total.total_without_discount.toFixed(2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">${{ total.total_with_discount.toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>


    <!-- Day Records Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4 z-50" id="my-modal">
        <div class="relative bg-white dark:bg-gray-800 w-full max-w-6xl mx-auto rounded-lg shadow-xl">
          <div class="p-6">
            <h3 class="text-2xl leading-6 font-bold text-gray-900 dark:text-gray-100 mb-4">
              Records for {{ expandedYear }}-{{ monthName(expandedMonth) }}-{{ expandedDay }}
            </h3>
            <div class="mt-4">
              <div v-if="loading" class="flex justify-center items-center h-64">
                <div class="spinner"></div>
              </div>
              <div v-else-if="records.length" class="space-y-6">
                <div v-for="(record, index) in records" :key="index" 
                     class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-all duration-300 ease-in-out hover:shadow-lg">
                  <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                      <h2 class="text-xl font-semibold text-indigo-600 dark:text-indigo-400">
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
                        <p class="font-medium text-gray-800 dark:text-gray-200">${{ record.price }}</p>
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
                      {{ record.showUsers ? 'Hide Users' : 'Show Users' }}
                    </button>
                  </div>
                  <div v-if="record.showUsers" class="px-6 py-4 bg-gray-100 dark:bg-gray-600">
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

                    <div class="max-h-60 overflow-y-auto">
                      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                          <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                              Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                              Badge ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                              Category Discount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                              User Category
                            </th>
                          </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                          <tr v-for="(user, userIndex) in filteredUsers" :key="userIndex" class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                              {{ user.email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                              {{ user.badge_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                              {{ user.category_discount }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
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
          
          <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 rounded-b-lg">
            <button @click="closeModal" 
                    class="w-full px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
              Close
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script>
import { ref, reactive, computed } from 'vue';
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

    const monthName = (month) => {
      return new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
    };

    const years = computed(() => store.state.record.years);
    const months = computed(() => store.state.record.months);
    const days = computed(() => store.state.record.days);
    const records = ref([]);

    const monthlyTotals = computed(() => store.state.record.monthlyTotals);

    const filteredUsers = computed(() => {
      if (!records.value.length) return [];
      const activeRecord = records.value.find(r => r.showUsers);
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
        store.dispatch('record/setSelectedYear', year);
        await store.dispatch('record/fetchMonths');
      }
    };

    const expandMonth = async (month) => {
      if (expandedMonth.value === month) {
        expandedMonth.value = null;
        expandedDay.value = null;
      } else {
        expandedMonth.value = month;
        store.dispatch('record/setSelectedMonth', month);
        await store.dispatch('record/fetchDays');
      }
    };

    const showDayRecords = async (day) => {
      expandedDay.value = day;
      store.dispatch('record/setSelectedDay', day);
      showModal.value = true;
      loading.value = true;
      try {
        await store.dispatch('record/fetchDayRecords');
        records.value = store.state.record.records.map(record => ({
          ...record,
          showUsers: false,
          users: JSON.parse(record.users) // Parse the JSON string to an array of objects
        }));
      } finally {
        loading.value = false;
      }
    };

    const toggleUserList = (index) => {
      records.value.forEach((record, i) => {
        record.showUsers = i === index ? !record.showUsers : false;
      });
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