<template>
  <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 space-y-6">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">Select Date Range</h2>
    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
      <div class="flex-1">
        <label for="start-date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
        <div class="relative">
          <input
            type="date"
            id="start-date"
            v-model="localStartDate"
            class="mt-1 block w-full pl-10 pr-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          >
          <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
            </svg>
          </span>
        </div>
      </div>
      <div class="flex-1">
        <label for="end-date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
        <div class="relative">
          <input
            type="date"
            id="end-date"
            v-model="localEndDate"
            class="mt-1 block w-full pl-10 pr-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
          >
          <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
            </svg>
          </span>
        </div>
      </div>
    </div>
    <button
      @click="emitDateRange"
      class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:scale-105 dark:bg-indigo-500 dark:hover:bg-indigo-600"
    >
      <span class="mr-2">Fetch Statistics</span>
      <svg class="inline-block w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </button>
  </div>
</template>

<script>
import { ref, watch } from 'vue';

export default {
  name: 'DateRangePicker',
  props: {
    startDate: String,
    endDate: String,
  },
  emits: ['update:startDate', 'update:endDate', 'fetch'],
  setup(props, { emit }) {
    const localStartDate = ref(props.startDate);
    const localEndDate = ref(props.endDate);

    watch(() => props.startDate, (newValue) => {
      localStartDate.value = newValue;
    });

    watch(() => props.endDate, (newValue) => {
      localEndDate.value = newValue;
    });

    const emitDateRange = () => {
      emit('update:startDate', localStartDate.value);
      emit('update:endDate', localEndDate.value);
      emit('fetch');
    };

    return {
      localStartDate,
      localEndDate,
      emitDateRange,
    };
  },
};
</script>
