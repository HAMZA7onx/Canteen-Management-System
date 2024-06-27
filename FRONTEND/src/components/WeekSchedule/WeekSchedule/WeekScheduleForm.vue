<template>
    <div>
      <p class="text-sm text-gray-500 mb-4">
        Assigned Daily Meals for {{ day }}:
      </p>
      <div class="mt-2">
        <div v-if="assignedDailyMeals.length === 0">
          <p class="text-sm text-gray-500">No daily meals assigned.</p>
        </div>
        <div v-else>
          <ul role="list" class="divide-y divide-gray-200">
            <li
              v-for="dailyMeal in assignedDailyMeals"
              :key="dailyMeal.id"
              class="py-4"
            >
              <div class="flex items-center space-x-4">
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ dailyMeal.name }}
                  </p>
                  <p class="text-sm text-gray-500 truncate">
                    {{ dailyMeal.description }}
                  </p>
                  <p class="text-sm text-gray-500">
                    {{ dailyMeal.pivot.start_time }} -
                    {{ dailyMeal.pivot.end_time }} ({{ dailyMeal.pivot.price }}$)
                  </p>
                </div>
                <div>
                  <button
                    class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    @click="detachDailyMeal(dailyMeal.id)"
                  >
                    Detach
                  </button>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="mt-4">
        <label
          for="dailyMealSelect"
          class="block text-sm font-medium text-gray-700"
          >Select Daily Meals</label
        >
        <select
          id="dailyMealSelect"
          v-model="selectedDailyMealIds"
          multiple
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
          <option
            v-for="dailyMeal in availableDailyMeals"
            :key="dailyMeal.id"
            :value="dailyMeal.id"
          >
            {{ dailyMeal.name }}
          </option>
        </select>
      </div>
      <div class="mt-4">
        <label for="startTime" class="block text-sm font-medium text-gray-700"
          >Start Time</label
        >
        <input
          id="startTime"
          v-model="startTime"
          type="time"
          required
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
      </div>
      <div class="mt-4">
        <label for="endTime" class="block text-sm font-medium text-gray-700"
          >End Time</label
        >
        <input
          id="endTime"
          v-model="endTime"
          type="time"
          required
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
      </div>
      <div class="mt-4">
        <label for="price" class="block text-sm font-medium text-gray-700"
          >Price</label
        >
        <input
          id="price"
          v-model="price"
          type="number"
          step="0.01"
          required
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
      </div>
      <div class="mt-4 flex justify-end">
        <button
          type="button"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          @click="assignDailyMeals"
        >
          Assign
        </button>
      </div>
    </div>
  </template>
  
  <script>
  import { mapGetters, mapActions } from 'vuex'
  
  export default {
    props: {
      weekSchedule: {
        type: Object,
        required: true,
      },
      day: {
        type: String,
        required: true,
      },
    },
    data() {
      return {
        selectedDailyMealIds: [],
        startTime: '',
        endTime: '',
        price: '',
      }
    },
    computed: {
      ...mapGetters('dailyMeal', ['dailyMeals']),
      assignedDailyMeals() {
        return this.weekSchedule[`${this.day}DailyMeals`] || []
      },
      availableDailyMeals() {
        const assignedDailyMealIds = this.assignedDailyMeals.map(
          (dailyMeal) => dailyMeal.id
        )
        return this.dailyMeals.filter(
          (dailyMeal) => !assignedDailyMealIds.includes(dailyMeal.id)
        )
      },
    },
    created() {
      this.fetchDailyMeals()
    },
    methods: {
      ...mapActions('dailyMeal', ['fetchDailyMeals']),
      ...mapActions('weekSchedule', ['assignDailyMeals', 'detachDailyMeal']),
      assignDailyMeals() {
      const dailyMealData = {
        daily_meal_id: this.selectedDailyMealIds[0], // Assuming you're assigning only one daily meal at a time
        start_time: this.startTime,
        end_time: this.endTime,
        price: this.price,
      }
      this.$emit('assign', dailyMealData)
    },
      detachDailyMeal(dailyMealId) {
        this.$emit('detach', dailyMealId)
      },
    },
  }
  </script>
  