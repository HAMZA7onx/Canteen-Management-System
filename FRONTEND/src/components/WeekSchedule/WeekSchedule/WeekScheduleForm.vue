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
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate flex items-center space-x-2">
                  <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 px-2 py-1 rounded-full">
                    {{ menuData.meal_name }}
                  </span>
                  <span class="text-gray-600 dark:text-gray-300">
                    {{ getMenuName(menuData.menu_id) }}
                  </span>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                  {{ getMenuDescription(menuData.menu_id) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ menuData.start_time }} - {{ menuData.end_time }}
                  <span class="font-semibold text-green-600 dark:text-green-400">({{ menuData.price }} DH)</span>
                </p>
                <div v-if="visibleDiscounts[menuData.menu_id] && Object.keys(menuData.discounts).length > 0">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Discounts:</p>
                  <ul class="list-disc list-inside">
                    <li v-for="(discount, categoryId) in menuData.discounts" :key="categoryId" class="text-sm text-gray-500 dark:text-gray-400">
                      {{ getCategoryName(categoryId) }}: {{ discount.discount }} DH
                    </li>
                  </ul>
                  <button @click="toggleDiscounts(menuData.menu_id)" class="text-sm text-blue-500 hover:text-blue-700 flex items-center">
                    <font-awesome-icon v-if="loadingDiscounts[menuData.menu_id]" icon="spinner" spin class="mr-2" />
                    Hide discounts
                  </button>
                </div>
                <button v-else @click="toggleDiscounts(menuData.menu_id)" class="text-sm text-blue-500 hover:text-blue-700 flex items-center">
                  <font-awesome-icon v-if="loadingDiscounts[menuData.menu_id]" icon="spinner" spin class="mr-2" />
                  {{ Object.keys(menuData.discounts).length > 0 ? 'Show discounts' : 'Load discounts' }}
                </button>
              </div>
              <div>
                <button
                  class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-200"
                  @click="detachMenu(menuData.menu_id)"
                  :disabled="detachingMenu[menuData.menu_id]"
                >
                  <font-awesome-icon v-if="detachingMenu[menuData.menu_id]" icon="spinner" spin class="mr-2" />
                  <font-awesome-icon v-else icon="unlink" class="mr-2" />
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
        <div class="mt-2 flex space-x-2">
          <button
            v-for="mealType in ['Petit-déjeuner', 'Déjeuner', 'Dîner']"
            :key="mealType"
            @click="mealName = mealType"
            class="px-2 py-1 text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500"
          >
            {{ mealType }}
          </button>
        </div>
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
        :disabled="assigningMenu"
      >
        <font-awesome-icon v-if="assigningMenu" icon="spinner" spin class="mr-2" />
        <font-awesome-icon v-else icon="plus" class="mr-2" />
        Assign
      </button>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import { useStore } from 'vuex'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faSpinner, faPlus, faUnlink } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Add the icons to the library
library.add(faSpinner, faPlus, faUnlink)

export default {
  components: {
    FontAwesomeIcon
  },
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
    const visibleDiscounts = ref({})
    const loadingDiscounts = ref({})
    const detachingMenu = ref({})
    const assigningMenu = ref(false)
    const mealTypes = ref(['Petit-déjeuner', 'Déjeuner', 'Dîner'])

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
      // Check if meal name already exists
      if (assignedMenus.value.some(menu => menu.meal_name === mealName.value)) {
        errorMessage.value = 'Le nom de repas déjà existe'
        return
      }

      // Check if any discount is greater than the price
      const priceValue = parseFloat(price.value)
      for (const categoryId in discounts.value) {
        if (parseFloat(discounts.value[categoryId]) > priceValue) {
          errorMessage.value = 'La réduction doit être inférieure au prix'
          return
        }
      }

      assigningMenu.value = true
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
      .finally(() => {
        assigningMenu.value = false
      })
    }

    const detachMenu = (menuId) => {
      detachingMenu.value[menuId] = true
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
      .finally(() => {
        detachingMenu.value[menuId] = false
      })
    }

    const handleMenuSelect = (event) => {
      selectedMenuId.value = event.target.value
    }

    const toggleDiscounts = (menuId) => {
      console.log('Toggling discounts for menu:', menuId)
      if (visibleDiscounts.value[menuId]) {
        visibleDiscounts.value[menuId] = false
      } else {
        loadingDiscounts.value[menuId] = true
        store.dispatch('weekSchedule/fetchDiscountsForMenu', {
          weekScheduleId: props.weekScheduleId,
          day: props.day,
          menuId
        }).then(() => {
          console.log('Discounts fetched successfully.')
          visibleDiscounts.value[menuId] = true
        }).catch(error => {
          console.error('Error fetching discounts:', error)
          errorMessage.value = 'An error occurred while fetching discounts.'
        }).finally(() => {
          loadingDiscounts.value[menuId] = false
        })
      }
    }

    // Watch for changes in mealName
    watch(mealName, (newValue) => {
      if (newValue && mealTypes.value.includes(newValue)) {
        mealTypes.value = mealTypes.value.filter(type => type !== newValue)
      } else if (newValue === '') {
        mealTypes.value = ['Petit-déjeuner', 'Déjeuner', 'Dîner']
      }
    })

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
      visibleDiscounts,
      loadingDiscounts,
      detachingMenu,
      assigningMenu,
      mealTypes,
      getMenuName,
      getMenuDescription,
      getCategoryName,
      assignMenu,
      detachMenu,
      handleMenuSelect,
      toggleDiscounts,
      FontAwesomeIcon,
      faSpinner,
      faPlus,
      faUnlink
    }
  }
}
</script>
