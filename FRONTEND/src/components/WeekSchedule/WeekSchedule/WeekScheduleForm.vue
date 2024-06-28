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
            v-for="dailyMealData in assignedDailyMeals"
            :key="dailyMealData.daily_meal_id"
            class="py-4"
          >
            <div class="flex items-center space-x-4">
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                  {{ getDailyMealName(dailyMealData.daily_meal_id) }}
                </p>
                <p class="text-sm text-gray-500 truncate">
                  {{ getDailyMealDescription(dailyMealData.daily_meal_id) }}
                </p>
                <p class="text-sm text-gray-500">
                  {{ dailyMealData.start_time }} -
                  {{ dailyMealData.end_time }} ({{ dailyMealData.price }}$)
                </p>
              </div>
              <div>
                <button
                  class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  @click="detachDailyMeal(dailyMealData.daily_meal_id)"
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
      <label for="dailyMealSelect" class="block text-sm font-medium text-gray-700">Select Daily Meals</label>
      <select
        id="dailyMealSelect"
        @change="handleDailyMealSelect"
        :value="selectedDailyMealId"
        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
      >
        <option value="" disabled>Select a daily meal</option>
        <option v-for="dailyMeal in availableDailyMeals" :key="dailyMeal.id" :value="dailyMeal.id">
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
        >Price (DH)</label
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
    }
  },
  computed: {
    ...mapGetters('dailyMeal', ['dailyMeals']),
    ...mapGetters('weekSchedule', ['getAssignedDailyMealsForDay']),
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
  },
  methods: {
    ...mapActions('dailyMeal', ['fetchDailyMeals']),
    ...mapActions('weekSchedule', ['assignDailyMeals', 'detachDailyMeal']),
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
      }
      this.$emit('assign', dailyMealData)
      this.selectedDailyMealId = null // Reset selectedDailyMealId after assignment
    },
    detachDailyMeal(dailyMealId) {
      this.$emit('detach', dailyMealId)
    },
    handleDailyMealSelect(event) {
      this.selectedDailyMealId = event.target.value
    },
  },
}
</script>