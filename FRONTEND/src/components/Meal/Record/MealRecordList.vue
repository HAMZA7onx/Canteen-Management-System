<template>
    <div>
      <h2>Meal Records</h2>
      <table>
        <thead>
          <tr>
            <th>Meal Name</th>
            <th>Schedule Date</th>
            <th>Badge RFID</th>
            <th>User</th>
            <th>User Category</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in mealRecords" :key="record.id" class="hover:bg-gray-100">
            <td class="border px-4 py-2">{{ record.meal_schedule.meal_name.name }}</td>
            <td class="border px-4 py-2">{{ formatDate(record.meal_schedule.date) }}</td>
            <td class="border px-4 py-2">{{ record.badge.rfid }}</td>
            <td class="border px-4 py-2">{{ record.badge.user.name }}</td>
            <td class="border px-4 py-2">{{ record.badge.user.category.name }}</td>
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
  