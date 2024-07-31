<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 space-y-6">
      <h3 class="text-2xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-300 dark:to-indigo-400">
        Attribuer des menus pour le repas quotidien: {{ dailyMealName }}
      </h3>

      <div v-if="assignedMenus.length === 0" class="text-sm text-gray-500 dark:text-gray-400 text-center italic">
        Aucun menu attribué pour l'instant.
      </div>

      <ul v-else role="list" class="space-y-3">
        <li
          v-for="menu in assignedMenus"
          :key="menu.id"
          class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 flex items-center justify-between transition duration-300 hover:shadow-md"
        >
          <div>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ menu.name }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ menu.description }}</p>
          </div>
          <button
            @click="handleDetachMenu(menu.id)"
            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
          >
            Detach
          </button>
        </li>
      </ul>

      <div class="space-y-2">
        <label for="menuSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Selectionner un Menu</label>
        <select
          id="menuSelect"
          v-model="selectedMenuId"
          class="w-full px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-purple-500 dark:focus:border-purple-400 focus:outline-none transition duration-300"
        >
          <option value="" disabled selected>Select a menu</option>
          <option
            v-for="menu in availableMenus"
            :key="menu.id"
            :value="menu.id"
          >{{ menu.name }}</option>
        </select>
      </div>

      <div class="flex justify-end">
        <button
          @click="assignMenu"
          :disabled="!selectedMenuId"
          class="px-6 py-2 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-400 dark:to-indigo-500 text-white hover:from-purple-600 hover:to-indigo-700 dark:hover:from-purple-500 dark:hover:to-indigo-600 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
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
