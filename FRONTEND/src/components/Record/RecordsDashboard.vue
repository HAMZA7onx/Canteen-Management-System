<template>
    <div class="records-dashboard p-6">
      <h1 class="text-3xl font-bold mb-6">Records Dashboard</h1>
      
      <div class="flex space-x-4 mb-6">
        <select v-model="selectedYear" @change="onYearChange" class="form-select">
          <option value="">Select Year</option>
          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
        </select>
        
        <select v-if="selectedYear" v-model="selectedMonth" @change="onMonthChange" class="form-select">
          <option value="">Select Month</option>
          <option v-for="month in months" :key="month" :value="month">{{ monthName(month) }}</option>
        </select>
        
        <select v-if="selectedMonth" v-model="selectedDay" @change="onDayChange" class="form-select">
          <option value="">Select Day</option>
          <option v-for="day in days" :key="day" :value="day">{{ day }}</option>
        </select>
      </div>
  
      <div v-if="records.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="(record, index) in records" :key="index" class="bg-white shadow rounded-lg p-6">
          <h2 class="text-xl font-semibold mb-4">{{ record.mode_name }}</h2>
          <p class="mb-2"><strong>Meal:</strong> {{ record.meal_name }}</p>
          <p class="mb-2"><strong>Time:</strong> {{ record.start_time }} - {{ record.end_time }}</p>
          <p class="mb-2"><strong>Price:</strong> ${{ record.price }}</p>
          <p class="mb-2"><strong>Badge Count:</strong> {{ record.badge_count }}</p>
          <div class="mt-4">
            <h3 class="font-semibold mb-2">Users:</h3>
            <ul class="list-disc pl-5">
              <li v-for="user in record.users.split(', ')" :key="user">{{ user }}</li>
            </ul>
          </div>
        </div>
      </div>
  
      <p v-else-if="selectedDay" class="text-center text-gray-500 mt-6">No records found for the selected date.</p>
    </div>
  </template>
  
  <script>
  import { mapState, mapActions } from 'vuex';
  import { ref, computed, watch } from 'vue';
  
  export default {
    name: 'RecordsDashboard',
    setup() {
      const selectedYear = ref(null);
      const selectedMonth = ref(null);
      const selectedDay = ref(null);
  
      const monthName = (month) => {
        return new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
      };
  
      return {
        selectedYear,
        selectedMonth,
        selectedDay,
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
      async onYearChange() {
        this.setSelectedYear(this.selectedYear);
        this.selectedMonth = null;
        this.selectedDay = null;
        await this.fetchMonths();
      },
      async onMonthChange() {
        this.setSelectedMonth(this.selectedMonth);
        this.selectedDay = null;
        await this.fetchDays();
      },
      async onDayChange() {
        this.setSelectedDay(this.selectedDay);
        await this.fetchDayRecords();
      },
    },
    mounted() {
      this.fetchYears();
    },
    watch: {
      selectedYear(newVal) {
        if (newVal) {
          this.fetchMonths();
        }
      },
      selectedMonth(newVal) {
        if (newVal) {
          this.fetchDays();
        }
      },
      selectedDay(newVal) {
        if (newVal) {
          this.fetchDayRecords();
        }
      },
    },
  };
  </script>
   