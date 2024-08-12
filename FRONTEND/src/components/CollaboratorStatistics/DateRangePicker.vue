<!-- src/components/CollaboratorStatistics/DateRangePicker.vue -->

<template>
    <div class="space-y-6 p-8 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-gray-800 dark:to-blue-900 rounded-lg shadow-2xl">
      <div class="relative">
        <label for="date-debut" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Date de début</label>
        <input
          type="date"
          id="date-debut"
          v-model="dateDebutLocale"
          class="w-full px-4 py-3 rounded-md border-2 border-blue-300 focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300 ease-in-out dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-purple-500"
        >
        <span class="absolute right-3 top-10 text-blue-500 dark:text-purple-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </span>
      </div>
      <div class="relative">
        <label for="date-fin" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Date de fin</label>
        <input
          type="date"
          id="date-fin"
          v-model="dateFinLocale"
          class="w-full px-4 py-3 rounded-md border-2 border-blue-300 focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-300 ease-in-out dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-purple-500"
        >
        <span class="absolute right-3 top-10 text-blue-500 dark:text-purple-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </span>
      </div>
      <button
        @click="emettreIntervalleDates"
        class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-lg font-bold rounded-md hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 ease-in-out transform hover:scale-105 dark:from-blue-600 dark:to-purple-700 dark:hover:from-blue-700 dark:hover:to-purple-800"
      >
        Récupérer les statistiques
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
  