<template>
  <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">
      Assigned Menus for {{ day }}
    </h2>

    <div v-if="errorMessage" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
      {{ errorMessage }}
    </div>

    <div class="mb-6">
      <div v-if="assignedMenus.length === 0">
        <p class="text-sm text-gray-500 dark:text-gray-400">No menus assigned.</p>
      </div>
      <div v-else>
        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
          <li
            v-for="menuData in assignedMenus"
            :key="menuData.menu_id"
            class="py-4"
          >
            <div class="flex items-center justify-between">
              <div class="flex-1 min-w-0 pr-4">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                  {{ getMenuName(menuData.menu_id) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                  {{ getMenuDescription(menuData.menu_id) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ menuData.start_time }} - {{ menuData.end_time }}
                  <span class="font-semibold text-green-600 dark:text-green-400">({{ menuData.price }} DH)</span>
                </p>
                <div v-if="Object.keys(menuData.discounts).length > 0">
                  {{ console.log('Rendering discounts for menu:', menuData.menu_id, menuData) }}
                  <p class="text-sm text-gray-500 dark:text-gray-400">Discounts:</p>
                  <ul class="list-disc list-inside">
                    <li v-for="(discount, categoryId) in menuData.discounts" :key="categoryId" class="text-sm text-gray-500 dark:text-gray-400">
                      {{ getCategoryName(categoryId) }}: {{ discount.discount }} DH
                    </li>
                  </ul>
                </div>
                <button v-else @click="fetchDiscounts(menuData.menu_id)" class="text-sm text-blue-500 hover:text-blue-700">
                  {{ console.log('Rendering Load discounts button for menu:', menuData.menu_id) }}
                  Load discounts
                </button>
              </div>
              <div>
                <button
                  class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-200"
                  @click="detachMenu(menuData.menu_id)"
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
        <label for="menuSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Menu</label>
        <select
          id="menuSelect"
          @change="handleMenuSelect"
          :value="selectedMenuId"
          class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-white"
        >
          <option value="" disabled>Select a menu</option>
          <option v-for="menu in availableMenus" :key="menu.id" :value="menu.id">
            {{ menu.name }}
          </option>
        </select>
      </div>

      <div>
        <label for="mealName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meal Name</label>
        <input
          id="mealName"
          v-model="mealName"
          type="text"
          required
          class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-white"
        />
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
        <div class="max-h-60 overflow-y-auto pr-2 border border-gray-600 p-2">
          <div v-for="category in userCategories" :key="category.id" class="mb-2">
            <label :for="`discount-${category.id}`" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              {{ category.name }} Discount (DH)
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
    </div>

    <div class="mt-6 flex justify-end">
      <button
        type="button"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-200"
        @click="assignMenu"
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

    const selectedMenuId = ref(null)
    const mealName = ref('')
    const startTime = ref('')
    const endTime = ref('')
    const price = ref('')
    const errorMessage = ref('')
    const discounts = ref({})

    const menus = computed(() => store.getters['menu/menus'])
    const userCategories = computed(() => store.getters['userCategory/userCategories'])
    const assignedMenus = computed(() => {
      const meals = store.getters['weekSchedule/getAssignedMenusForDay'](props.weekScheduleId, props.day) || []
      console.log('Assigned Menus:', meals)
      return meals
    })

    const availableMenus = computed(() => {
      const assignedMenuIds = assignedMenus.value.map((menuData) => menuData.menu_id)
      return menus.value.filter(
        (menu) => !assignedMenuIds.includes(menu.id) && menu.id !== selectedMenuId.value
      )
    })

    onMounted(() => {
      store.dispatch('menu/fetchMenus')
      store.dispatch('userCategory/fetchUserCategories')
      .then(() => {
        userCategories.value.forEach(category => {
          discounts.value[category.id] = 0
        })
      })
    })

    const resetDiscounts = () => {
      userCategories.value.forEach(category => {
        discounts.value[category.id] = 0
      })
    }

    const getMenuName = (menuId) => {
      const menu = menus.value.find((m) => m.id === menuId)
      return menu ? menu.name : ''
    }

    const getMenuDescription = (menuId) => {
      const menu = menus.value.find((m) => m.id === menuId)
      return menu ? menu.description : ''
    }

    const getCategoryName = (categoryId) => {
      const category = userCategories.value.find((cat) => cat.id === parseInt(categoryId))
      return category ? category.name : ''
    }

    const assignMenu = () => {
      const menuData = {
        menu_id: selectedMenuId.value,
        meal_name: mealName.value,
        start_time: startTime.value,
        end_time: endTime.value,
        price: price.value,
        discounts: discounts.value,
      }

      store.dispatch('weekSchedule/assignMenu', {
        weekScheduleId: props.weekScheduleId,
        day: props.day,
        menuData,
      })
      .then(() => {
        selectedMenuId.value = null
        mealName.value = ''
        startTime.value = ''
        endTime.value = ''
        price.value = ''
        resetDiscounts()

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
              errorMessage.value = `The specified duration overlaps with an existing menu for ${props.day}`;
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
          errorMessage.value = 'An error occurred while assigning the menu. Please check the console for more details.';
        }
      })
    }

    const detachMenu = (menuId) => {
      store.dispatch('weekSchedule/detachMenu', {
        weekScheduleId: props.weekScheduleId,
        day: props.day,
        menuId,
      })
      .then(() => {
        store.dispatch('weekSchedule/fetchWeekSchedules')
      })
      .catch((error) => {
        console.error('Error detaching menu:', error)
        errorMessage.value = 'An error occurred while detaching the menu.'
      })
    }

    const handleMenuSelect = (event) => {
      selectedMenuId.value = event.target.value
    }

    const fetchDiscounts = (menuId) => {
      console.log('Fetching discounts for menu:', menuId)
      store.dispatch('weekSchedule/fetchDiscountsForMenu', {
        weekScheduleId: props.weekScheduleId,
        day: props.day,
        menuId
      }).then(() => {
        console.log('Discounts fetched successfully.')
      }).catch(error => {
        console.error('Error fetching discounts:', error)
        errorMessage.value = 'An error occurred while fetching discounts.'
      })
    }

    return {
      selectedMenuId,
      mealName,
      startTime,
      endTime,
      price,
      errorMessage,
      discounts,
      menus,
      userCategories,
      assignedMenus,
      availableMenus,
      getMenuName,
      getMenuDescription,
      getCategoryName,
      assignMenu,
      detachMenu,
      handleMenuSelect,
      fetchDiscounts
    }
  }
}
</script>