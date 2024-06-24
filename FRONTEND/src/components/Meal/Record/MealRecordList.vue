<template>
    <div class="p-6 bg-white rounded-lg shadow-lg">
      <h2 class="text-2xl font-bold mb-4 text-gray-800">Meal Records</h2>
      <table class="min-w-full bg-white">
        <thead>
          <tr class="w-full bg-gray-200 text-left text-gray-600 uppercase text-sm leading-normal">
            <th class="py-3 px-6">Meal Name</th>
            <th class="py-3 px-6">Schedule Date</th>
            <th class="py-3 px-6">Badge RFID</th>
            <th class="py-3 px-6">User</th>
            <th class="py-3 px-6">User Category</th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr v-for="record in mealRecords" :key="record.id" class="border-b border-gray-200 hover:bg-gray-100">
            <td class="py-3 px-6">{{ record.meal_schedule.meal_name.name }}</td>
            <td class="py-3 px-6">{{ formatDate(record.meal_schedule.date) }}</td>
            <td class="py-3 px-6">{{ record.badge.rfid }}</td>
            <td class="py-3 px-6">{{ record.badge.user.name }}</td>
            <td class="py-3 px-6">{{ record.badge.user.category.name }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { mapGetters, mapActions } from 'vuex';
  
  export default {
    computed: {
      ...mapGetters('record', ['mealRecords']),
    },
    created() {
      this.fetchMealRecords();
    },
    watch: {
      mealRecords(newValue) {
        if (newValue.length > 0) {
          console.log('record:', newValue);
        }
      },
    },
    methods: {
      ...mapActions('record', ['fetchMealRecords']),
      formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString();
      },
    },
  };
  </script>
  