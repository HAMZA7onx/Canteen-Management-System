<template>
  <div>
    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
      Assign Menus for Daily Meal: {{ dailyMealName }}
    </h3>

    <div v-if="assignedMenus.length === 0" class="text-sm text-gray-500 mb-4">
      No menus assigned.
    </div>

    <ul v-else role="list" class="divide-y divide-gray-200">
      <li
        v-for="menu in assignedMenus"
        :key="menu.id"
        class="py-4 flex items-center justify-between"
      >
        <div class="flex items-center">
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-900">{{ menu.name }}</p>
            <p class="text-sm text-gray-500">{{ menu.description }}</p>
          </div>
        </div>
        <div>
          <button
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
            @click="handleDetachMenu(menu.id)"
          >
            Detach
          </button>
        </div>
      </li>
    </ul>

    <div class="mt-4">
      <label for="menuSelect" class="block text-sm font-medium text-gray-700">Select Menu</label>
      <select
        id="menuSelect"
        v-model="selectedMenuId"
        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
      >
        <option value="" disabled selected>Select a menu</option>
        <option
          v-for="menu in availableMenus"
          :key="menu.id"
          :value="menu.id"
        >{{ menu.name }}</option>
      </select>
    </div>
    <div class="mt-4 flex justify-end">
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="assignMenu"
        :disabled="!selectedMenuId"
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
    dailyMealId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      selectedMenuId: null,
    }
  },
  computed: {
    ...mapGetters('menu', ['menus']),
    ...mapGetters('dailyMeal', ['dailyMeals']),
    dailyMeal() {
      return this.dailyMeals.find(meal => meal.id === this.dailyMealId)
    },
    dailyMealName() {
      return this.dailyMeal ? this.dailyMeal.name : ''
    },
    assignedMenus() {
      return this.dailyMeal && this.dailyMeal.menus ? this.dailyMeal.menus : []
    },
    availableMenus() {
      const assignedMenuIds = this.assignedMenus.map(menu => menu.id)
      return this.menus.filter(menu => !assignedMenuIds.includes(menu.id))
    },
  },
  methods: {
    ...mapActions('menu', ['fetchMenus']),
    ...mapActions('dailyMeal', ['fetchDailyMeals', 'attachMenu', 'detachMenu']),
    assignMenu() {
      if (this.selectedMenuId) {
        this.attachMenu({ dailyMealId: this.dailyMealId, menuId: this.selectedMenuId })
          .then(() => {
            this.selectedMenuId = null
          })
          .catch(error => {
            console.error('Error assigning menu:', error)
          })
      }
    },
    handleDetachMenu(menuId) {
      this.detachMenu({ dailyMealId: this.dailyMealId, menuId })
        .catch(error => {
          console.error('Error detaching menu:', error)
        })
    },
  },
  created() {
    this.fetchMenus()
    this.fetchDailyMeals()
  },
}
</script>
