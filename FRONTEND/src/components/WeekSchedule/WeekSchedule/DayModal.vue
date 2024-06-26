<template>
    <div>
      <h2 class="text-lg font-bold mb-4">Daily Meals</h2>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <h3 class="text-sm font-medium text-gray-700">Assigned Daily Meals</h3>
          <ul class="mt-2">
            <li
              v-for="dailyMeal in assignedDailyMeals"
              :key="dailyMeal.id"
              class="py-2 px-4 bg-gray-100 rounded-md mb-2 flex justify-between items-center"
            >
              <span>{{ dailyMeal.name }}</span>
              <button
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                @click="detachDailyMeal(dailyMeal.id)"
              >
                Remove
              </button>
            </li>
          </ul>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-700">Available Daily Meals</h3>
          <ul class="mt-2">
            <li
              v-for="dailyMeal in availableDailyMeals"
              :key="dailyMeal.id"
              class="py-2 px-4 bg-gray-100 rounded-md mb-2 flex justify-between items-center"
            >
              <span>{{ dailyMeal.name }}</span>
              <button
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded"
                @click="attachDailyMeal(dailyMeal.id)"
              >
                Assign
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { mapGetters } from 'vuex'
  
  export default {
    props: {
      weekSchedule: {
        type: Object,
        required: true
      },
      day: {
        type: String,
        required: true
      }
    },
    computed: {
      ...mapGetters('dailyMeal', ['dailyMeals']),
      assignedDailyMeals() {
        return this.weekSchedule[`${this.day}Meals`] || []
      },
      availableDailyMeals() {
        const assignedDailyMealIds = this.assignedDailyMeals.map(meal => meal.id)
        return this.dailyMeals.filter(meal => !assignedDailyMealIds.includes(meal.id))
      }
    },
    methods: {
      attachDailyMeal(dailyMealId) {
        this.$emit('attachDailyMeal', dailyMealId)
      },
      detachDailyMeal(dailyMealId) {
        this.$emit('detachDailyMeal', dailyMealId)
      }
    }
  }
  </script>
  