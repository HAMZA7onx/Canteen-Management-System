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
                <div v-if="dailyMealData.discounts">
                  {{ console.log('Rendering discounts for meal:', dailyMealData.daily_meal_id, dailyMealData.discounts) }}
                  <p class="text-sm text-gray-500 dark:text-gray-400">Discounts:</p>
                  <ul class="list-disc list-inside">
                    <li v-for="(discount, categoryId) in dailyMealData.discounts" :key="categoryId" class="text-sm text-gray-500 dark:text-gray-400">
                      {{ getCategoryName(categoryId) }}: {{ discount.discount }}%
                    </li>
                  </ul>
                </div>
                <button v-else @click="fetchDiscounts(dailyMealData.daily_meal_id)" class="text-sm text-blue-500 hover:text-blue-700">
                  {{ console.log('Rendering Load discounts button for meal:', dailyMealData.daily_meal_id) }}
                  Load discounts
                </button>
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

      <div v-if="userCategories.length > 0">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Discounts</h3>
        <div v-for="category in userCategories" :key="category.id" class="mb-2">
          <label :for="`discount-${category.id}`" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ category.name }} Discount (%)
          </label>
          <input
            :id="`discount-${category.id}`"
            v-model="discounts[category.id]"
            type="number"
            min="0"
            max="100"
            step="0.01"
            class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-white"
          />
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
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'

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
  setup(props) {
    const store = useStore()

    const selectedDailyMealId = ref(null)
    const startTime = ref('')
    const endTime = ref('')
    const price = ref('')
    const errorMessage = ref('')
    const discounts = ref({})

    const dailyMeals = computed(() => store.getters['dailyMeal/dailyMeals'])
    const userCategories = computed(() => store.getters['userCategory/userCategories'])
    const assignedDailyMeals = computed(() => 
      store.getters['weekSchedule/getAssignedDailyMealsForDay'](props.weekScheduleId, props.day) || []
    )

    const availableDailyMeals = computed(() => {
      const assignedDailyMealIds = assignedDailyMeals.value.map((dailyMealData) => dailyMealData.daily_meal_id)
      return dailyMeals.value.filter(
        (dailyMeal) => !assignedDailyMealIds.includes(dailyMeal.id) && dailyMeal.id !== selectedDailyMealId.value
      )
    })

    onMounted(() => {
      store.dispatch('dailyMeal/fetchDailyMeals')
      store.dispatch('userCategory/fetchUserCategories')
    })

    const getDailyMealName = (dailyMealId) => {
      const dailyMeal = dailyMeals.value.find((meal) => meal.id === dailyMealId)
      return dailyMeal ? dailyMeal.name : ''
    }

    const getDailyMealDescription = (dailyMealId) => {
      const dailyMeal = dailyMeals.value.find((meal) => meal.id === dailyMealId)
      return dailyMeal ? dailyMeal.description : ''
    }

    const getCategoryName = (categoryId) => {
      const category = userCategories.value.find((cat) => cat.id === parseInt(categoryId))
      return category ? category.name : ''
    }

    const assignDailyMeals = () => {
      const dailyMealData = {
        daily_meal_id: selectedDailyMealId.value,
        start_time: startTime.value,
        end_time: endTime.value,
        price: price.value,
        discounts: discounts.value,
      }

      store.dispatch('weekSchedule/assignDailyMeals', {
        weekScheduleId: props.weekScheduleId,
        day: props.day,
        dailyMealData,
      })
      .then(() => {
        selectedDailyMealId.value = null
        startTime.value = ''
        endTime.value = ''
        price.value = ''
        discounts.value = {}
        errorMessage.value = ''
        store.dispatch('weekSchedule/fetchWeekSchedules')
      })
      .catch((error) => {
        console.error('Full error object:', error);
        console.error('Error response:', error.response);
        console.error('Error response data:', error.response?.data);

        if (error.response && error.response.data) {
          if (typeof error.response.data === 'string') {
            errorMessage.value = error.response.data;
          } else if (error.response.data.error) {
            if (error.response.data.error.includes('overlaps')) {
              errorMessage.value = `The specified duration overlaps with an existing daily meal for ${props.day}`;
            } else if (error.response.data.error.includes('after:start_time') || error.response.data.error.includes('end_time')) {
              errorMessage.value = 'Start time of the meal must be before end time';
            } else {
              errorMessage.value = error.response.data.error;
            }
          } else if (error.response.data.message) {
            errorMessage.value = error.response.data.message;
          } else {
            errorMessage.value = 'An unexpected error occurred. Please check the console for more details.';
          }
        } else {
          errorMessage.value = 'An error occurred while assigning the daily meal. Please check the console for more details.';
        }
      })
    }

    const detachDailyMeal = (dailyMealId) => {
      store.dispatch('weekSchedule/detachDailyMeal', {
        weekScheduleId: props.weekScheduleId,
        day: props.day,
        dailyMealId,
      })
      .then(() => {
        store.dispatch('weekSchedule/fetchWeekSchedules')
      })
      .catch((error) => {
        console.error('Error detaching daily meal:', error)
        errorMessage.value = 'An error occurred while detaching the daily meal.'
      })
    }

    const handleDailyMealSelect = (event) => {
      selectedDailyMealId.value = event.target.value
    }

    const fetchDiscounts = (dailyMealId) => {
      console.log('Fetching discounts for daily meal:', dailyMealId)
      store.dispatch('weekSchedule/fetchDiscountsForDailyMeal', {
        weekScheduleId: props.weekScheduleId,
        day: props.day,
        dailyMealId
      }).then((discounts) => {
        console.log('Discounts fetched successfully. Discounts data:', discounts)
        // Don't do anything else here for now
      }).catch(error => {
        console.error('Error fetching discounts:', error)
        errorMessage.value = 'An error occurred while fetching discounts.'
      })
    }

    return {
      selectedDailyMealId,
      startTime,
      endTime,
      price,
      errorMessage,
      discounts,
      dailyMeals,
      userCategories,
      assignedDailyMeals,
      availableDailyMeals,
      getDailyMealName,
      getDailyMealDescription,
      getCategoryName,
      assignDailyMeals,
      detachDailyMeal,
      handleDailyMealSelect,
      fetchDiscounts
    }
  }
}
</script>