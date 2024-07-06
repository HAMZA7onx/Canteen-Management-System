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
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Day Records Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4 z-50" id="my-modal">
        <div class="relative bg-white dark:bg-gray-800 w-full max-w-4xl mx-auto rounded-lg shadow-xl">
          <div class="p-6">
            <h3 class="text-2xl leading-6 font-bold text-gray-900 dark:text-gray-100 mb-4">Records for {{ expandedYear }}-{{ monthName(expandedMonth) }}-{{ expandedDay }}</h3>
            <div class="mt-4">
              <div v-if="loading" class="flex justify-center items-center h-64">
                <div class="spinner"></div>
              </div>
              <div v-else-if="records.length" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="(record, index) in records" :key="index" class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md p-6 transition-all duration-300 ease-in-out hover:shadow-lg">
                  <h2 class="text-xl font-semibold mb-4 text-indigo-600 dark:text-indigo-400">{{ record.week_schedule_name }}</h2>
                  <p class="mb-2"><strong class="text-gray-700 dark:text-gray-300">Meal:</strong> <span class="text-gray-800 dark:text-gray-200">{{ record.meal_name }}</span></p>
                  <p class="mb-2"><strong class="text-gray-700 dark:text-gray-300">Time:</strong> <span class="text-gray-800 dark:text-gray-200">{{ record.start_time }} - {{ record.end_time }}</span></p>
                  <p class="mb-2"><strong class="text-gray-700 dark:text-gray-300">Price:</strong> <span class="text-gray-800 dark:text-gray-200">${{ record.price }}</span></p>
                  <p class="mb-2"><strong class="text-gray-700 dark:text-gray-300">Badge Count:</strong> <span class="text-gray-800 dark:text-gray-200">{{ record.badge_count }}</span></p>
                  <div class="mt-4">
                    <h3 class="font-semibold mb-2 text-gray-700 dark:text-gray-300">Users:</h3>
                    <ul class="list-disc pl-5 text-gray-800 dark:text-gray-200">
                      <li v-for="user in record.users.split(', ')" :key="user">{{ user }}</li>
                    </ul>
                  </div>
                </div>
              </div>
              <p v-else class="text-center text-gray-500 dark:text-gray-400 mt-6">No records found for the selected date.</p>
            </div>
          </div>
          <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 rounded-b-lg">
            <button @click="closeModal" class="w-full px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
              Close
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import { ref, computed } from 'vue';

export default {
  name: 'RecordsDashboard',
  setup() {
    const expandedYear = ref(null);
    const expandedMonth = ref(null);
    const expandedDay = ref(null);
    const showModal = ref(false);
    const loading = ref(false);

    const monthName = (month) => {
      return new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
    };

    return {
      expandedYear,
      expandedMonth,
      expandedDay,
      showModal,
      loading,
      monthName,
    };
  },
  computed: {
    ...mapState('record', ['years', 'months', 'days', 'records']),
  },
  methods: {
    ...mapActions('record', [
      'fetchYears',
      'fetchMonths',
      'fetchDays',
      'fetchDayRecords',
      'setSelectedYear',
      'setSelectedMonth',
      'setSelectedDay',
    ]),
    async expandYear(year) {
      if (this.expandedYear === year) {
        this.expandedYear = null;
        this.expandedMonth = null;
        this.expandedDay = null;
      } else {
        this.expandedYear = year;
        this.setSelectedYear(year);
        await this.fetchMonths();
      }
    },
    async expandMonth(month) {
      if (this.expandedMonth === month) {
        this.expandedMonth = null;
        this.expandedDay = null;
      } else {
        this.expandedMonth = month;
        this.setSelectedMonth(month);
        await this.fetchDays();
      }
    },
    async showDayRecords(day) {
      this.expandedDay = day;
      this.setSelectedDay(day);
      this.showModal = true;
      this.loading = true;
      try {
        await this.fetchDayRecords();
      } finally {
        this.loading = false;
      }
    },
    closeModal() {
      this.showModal = false;
    },
  },
  mounted() {
    this.fetchYears();
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
</style>
