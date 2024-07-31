<template>
  <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 space-y-6">
    <h3 class="text-2xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-300 dark:to-indigo-400">
      Attribuer des menus pour le repas quotidien: {{ dailyMealName }}
    </h3>

    <div v-if="isLoading" class="flex justify-center items-center h-32">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-purple-500"></div>
    </div>

    <template v-else>
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
            :disabled="isDetaching"
            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="!isDetaching">Detach</span>
            <span v-else class="flex items-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Detaching...
            </span>
          </button>
        </li>
      </ul>

      <div class="space-y-2">
        <label for="menuSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sélectionnez les menus</label>
        <multiselect
          v-model="selectedMenus"
          :options="availableMenus"
          :multiple="true"
          track-by="id"
          label="name"
          placeholder="Sélectionnez les menus"
          :searchable="true"
          :close-on-select="false"
          :clear-on-select="false"
          :preserve-search="true"
          :preselect-first="false"
          :max-height="300"
          class="multiselect-custom"
        >
        </multiselect>
      </div>

      <div class="flex justify-end">
        <button
          @click="assignMenus"
          :disabled="selectedMenus.length === 0 || isAssigning"
          class="px-6 py-2 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-400 dark:to-indigo-500 text-white hover:from-purple-600 hover:to-indigo-700 dark:hover:from-purple-500 dark:hover:to-indigo-600 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="!isAssigning">Assign</span>
          <span v-else class="flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Assigning...
          </span>
        </button>
      </div>
    </template>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import Multiselect from 'vue-multiselect'

export default {
  components: { Multiselect },
  props: {
    dailyMealId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      selectedMenus: [],
      isLoading: true,
      isAssigning: false,
      isDetaching: false,
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
    ...mapActions('dailyMeal', ['fetchDailyMeals', 'attachMenus', 'detachMenu']),
    async assignMenus() {
      if (this.selectedMenus.length > 0) {
        this.isAssigning = true
        try {
          const menuIds = this.selectedMenus.map(menu => menu.id)
          await this.attachMenus({ dailyMealId: this.dailyMealId, menuIds })
          this.selectedMenus = []
          await this.fetchDailyMeals()
        } catch (error) {
          console.error('Error assigning menus:', error)
        } finally {
          this.isAssigning = false
        }
      }
    },

    async handleDetachMenu(menuId) {
      this.isDetaching = true
      try {
        await this.detachMenu({ dailyMealId: this.dailyMealId, menuId })
        await this.fetchDailyMeals()
      } catch (error) {
        console.error('Error detaching menu:', error)
      } finally {
        this.isDetaching = false
      }
    },
    async loadData() {
      this.isLoading = true
      try {
        await Promise.all([
          this.fetchMenus(),
          this.fetchDailyMeals()
        ])
      } catch (error) {
        console.error('Error loading data:', error)
      } finally {
        this.isLoading = false
      }
    }
  },
  created() {
    this.loadData()
  },
}
</script>

<style>
.multiselect-custom {
  @apply bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm;
}
.multiselect-custom .multiselect__tags {
  @apply bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200;
}
.multiselect-custom .multiselect__single {
  @apply bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200;
}
.multiselect-custom .multiselect__input {
  @apply bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200;
}
.multiselect-custom .multiselect__content-wrapper {
  @apply bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600;
}
.multiselect-custom .multiselect__option {
  @apply text-gray-700 dark:text-gray-200;
}
.multiselect-custom .multiselect__option--highlight {
  @apply bg-indigo-500 text-white;
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
