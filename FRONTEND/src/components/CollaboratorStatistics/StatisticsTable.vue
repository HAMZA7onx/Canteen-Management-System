<!-- src/components/CollaboratorStatistics/StatisticsTable.vue -->

<template>
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th v-for="header in headers" :key="header.key" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              {{ header.label }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="user in users" :key="user.user_id">
            <td v-for="header in headers" :key="header.key" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
              <template v-if="header.key === 'actions'">
                <button @click="$emit('view-details', user)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                  Voir Détails
                </button>
              </template>
              <template v-else-if="header.format">
                {{ header.format(user[header.key]) }}
              </template>
              <template v-else>
                {{ user[header.key] }}
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    name: 'StatisticsTable',
    props: {
      headers: Array,
      users: Array,
    },
    emits: ['view-details'],
  };
  </script>
  