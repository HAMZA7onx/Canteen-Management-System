<template>
  <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">
      Assigned Daily Meals for {{ day }}
    </h2>

    <div v-if="errorMessage" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
      {{ errorMessage }}
    </div>

    <div class="mb-6">
      <div v-if="assignedDailyMeals.length === 0">
        <p class="text-sm text-gray-500 dark:text-gray-400">No daily meals assigned.</p>
      </div>
      <div v-else>
        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
          <li
            v-for="dailyMealData in assignedDailyMeals"
            :key="dailyMealData.daily_meal_id"
            class="py-4"
          >
            <div class="flex items-center justify-between">
              <div class="flex-1 min-w-0 pr-4">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                  {{ getDailyMealName(dailyMealData.daily_meal_id) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                  {{ getDailyMealDescription(dailyMealData.daily_meal_id) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ dailyMealData.start_time }} - {{ dailyMealData.end_time }}
                  <span class="font-semibold text-green-600 dark:text-green-400">({{ dailyMealData.price }}$)</span>
                </p>
              </div>
              <div>
                <button
                  class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-200"
                  @click="detachDailyMeal(dailyMealData.daily_meal_id)"
                >
                  <font-awesome-icon icon="unlink" class="mr-2" />
                  Detach
                </button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <div class="space-y-4">
      <div>
        <label for="dailyMealSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Daily Meals</label>
        <select
          id="dailyMealSelect"
          @change="handleDailyMealSelect"
          :value="selectedDailyMealId"
          class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-white"
        >
          <option value="" disabled>Select a daily meal</option>
          <option v-for="dailyMeal in availableDailyMeals" :key="dailyMeal.id" :value="dailyMeal.id">
            {{ dailyMeal.name }}
          </option>
        </select>
      </div>

      <div>
        <label for="startTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
        <input
          id="startTime"
          v-model="startTime"
          type="time"
          required
          class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-white"
        />
      </div>

      <div>
        <label for="endTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
        <input
          id="endTime"
          v-model="endTime"
          type="time"
          required
          class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-white"
        />
      </div>

      <div>
        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (DH)</label>
        <input
          id="price"
          v-model="price"
          type="number"
          step="0.01"
          required
          class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-white"
        />
      </div>

      <!-- New section for discounts -->
      <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Discounts for User Categories</h3>
        <div v-if="userCategories.length === 0" class="text-sm text-gray-500 dark:text-gray-400">
          No user categories available.
        </div>
        <div v-else class="space-y-2">
          <div v-for="category in userCategories" :key="category.id" class="flex items-center">
            <label :for="`discount-${category.id}`" class="block text-sm font-medium text-gray-700 dark:text-gray-300 w-1/3">
              {{ category.name }}
            </label>
            <input
              :id="`discount-${category.id}`"
              v-model="discounts[category.id]"
              type="number"
              min="0"
              max="100"
              step="0.01"
              class="mt-1 block w-1/3 py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-white"
            />
            <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">%</span>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-6 flex justify-end">
      <button
        type="button"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-200"
        @click="assignDailyMeals"
      >
        <font-awesome-icon icon="plus" class="mr-2" />
        Assign
      </button>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  props: {
    weekScheduleId: {
      type: Number,
      required: true,
    },
    day: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      selectedDailyMealId: null,
      startTime: '',
      endTime: '',
      price: '',
      errorMessage: '',
      discounts: {},
    }
  },
  computed: {
    ...mapGetters('dailyMeal', ['dailyMeals']),
    ...mapGetters('weekSchedule', ['getAssignedDailyMealsForDay']),
    ...mapGetters('userCategory', ['userCategories']),
    assignedDailyMeals() {
      return this.getAssignedDailyMealsForDay(this.weekScheduleId, this.day) || []
    },
    availableDailyMeals() {
      const assignedDailyMealIds = this.assignedDailyMeals.map((dailyMealData) => dailyMealData.daily_meal_id)
      return this.dailyMeals.filter(
        (dailyMeal) => !assignedDailyMealIds.includes(dailyMeal.id) && dailyMeal.id !== this.selectedDailyMealId
      )
    },
  },
  created() {
    this.fetchDailyMeals()
    this.fetchUserCategories()
  },
  methods: {
    ...mapActions('dailyMeal', ['fetchDailyMeals']),
    ...mapActions('weekSchedule', {
      assignDailyMealAction: 'assignDailyMeals',
      detachDailyMealAction: 'detachDailyMeal',
      fetchWeekSchedulesAction: 'fetchWeekSchedules'
    }),
    ...mapActions('userCategory', ['fetchUserCategories']),
    getDailyMealName(dailyMealId) {
      const dailyMeal = this.dailyMeals.find((meal) => meal.id === dailyMealId)
      return dailyMeal ? dailyMeal.name : ''
    },
    getDailyMealDescription(dailyMealId) {
      const dailyMeal = this.dailyMeals.find((meal) => meal.id === dailyMealId)
      return dailyMeal ? dailyMeal.description : ''
    },
    assignDailyMeals() {
      const dailyMealData = {
        daily_meal_id: this.selectedDailyMealId,
        start_time: this.startTime,
        end_time: this.endTime,
        price: this.price,
        discounts: this.discounts,
      }

      this.assignDailyMealAction({
        weekScheduleId: this.weekScheduleId,
        day: this.day,
        dailyMealData,
      })
      .then(() => {
        this.selectedDailyMealId = null
        this.startTime = ''
        this.endTime = ''
        this.price = ''
        this.discounts = {}
        this.errorMessage = ''
        this.fetchWeekSchedulesAction() // Refresh the data
      })
      .catch((error) => {
        console.error('Full error object:', error);
        console.error('Error response:', error.response);
        console.error('Error response data:', error.response?.data);

        if (error.response && error.response.data) {
          if (typeof error.response.data === 'string') {
            this.errorMessage = error.response.data;
          } else if (error.response.data.error) {
            if (error.response.data.error.includes('overlaps')) {
              this.errorMessage = `The specified duration overlaps with an existing daily meal for ${this.day}`;
            } else if (error.response.data.error.includes('after:start_time') || error.response.data.error.includes('end_time')) {
              this.errorMessage = 'Start time of the meal must be before end time';
            } else {
              this.errorMessage = error.response.data.error;
            }
          } else if (error.response.data.message) {
            this.errorMessage = error.response.data.message;
          } else {
            this.errorMessage = 'An unexpected error occurred. Please check the console for more details.';
          }
        } else {
          this.errorMessage = 'An error occurred while assigning the daily meal. Please check the console for more details.';
        }
      })
    },
    detachDailyMeal(dailyMealId) {
      this.detachDailyMealAction({
        weekScheduleId: this.weekScheduleId,
        day: this.day,
        dailyMealId,
      })
      .then(() => {
        this.fetchWeekSchedulesAction() // Refresh the data
      })
      .catch((error) => {
        console.error('Error detaching daily meal:', error)
        this.errorMessage = 'An error occurred while detaching the daily meal.'
      })
    },
    handleDailyMealSelect(event) {
      this.selectedDailyMealId = event.target.value
    },
  },
}
</script>
