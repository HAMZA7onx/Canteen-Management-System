<!-- src/components/CollaboratorStatistics/DateRangePicker.vue -->

<template>
    <div class="space-y-4">
      <div>
        <label for="start-date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
        <input
          type="date"
          id="start-date"
          v-model="localStartDate"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
      </div>
      <div>
        <label for="end-date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
        <input
          type="date"
          id="end-date"
          v-model="localEndDate"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        >
      </div>
      <button
        @click="emitDateRange"
        class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600"
      >
        Fetch Statistics
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
  