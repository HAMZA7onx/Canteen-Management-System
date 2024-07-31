<template>
  <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 space-y-4 sm:space-y-6 max-h-[80vh] overflow-y-auto">
    <h3 class="text-2xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600 dark:from-teal-300 dark:to-indigo-400">
      Attribuer des composants alimentaires au menu: {{ menuName }}
    </h3>

    <div v-if="isLoading" class="flex justify-center items-center h-32">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-teal-500"></div>
    </div>

    <template v-else>
      <div v-if="assignedFoodComposants.length === 0" class="text-sm text-gray-500 dark:text-gray-400 text-center italic">
        Aucun composant alimentaire attribué pour l'instant.
      </div>

      <ul v-else role="list" class="space-y-3 max-h-[40vh] overflow-y-auto">
        <li
          v-for="foodComposant in assignedFoodComposants"
            :key="foodComposant.id"
            class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2 flex items-center justify-between transition duration-300 hover:shadow-md"
          >
            <div class="flex-grow mr-2">
              <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ foodComposant.name }}</p>
              <p class="text-xs text-gray-600 dark:text-gray-300 truncate">{{ foodComposant.description }}</p>
            </div>
            <button
              @click="handleDetachFoodComposant(foodComposant.id)"
              :disabled="isDetaching"
              class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 text-xs rounded-full transition duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="!isDetaching">Detach</span>
              <span v-else class="flex items-center">
                <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Detaching...
              </span>
            </button>
        </li>

      </ul>

      <div class="space-y-2">
        <label for="foodComposantSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sélectionnez les composants alimentaires</label>
        <multiselect
          v-model="selectedFoodComposants"
          :options="availableFoodComposants"
          :multiple="true"
          track-by="id"
          label="name"
          placeholder="Sélectionnez les composants"
          :searchable="true"
          :close-on-select="false"
          :clear-on-select="false"
          :preserve-search="true"
          :preselect-first="false"
          :max-height="200"
          class="multiselect-custom"
        >
        </multiselect>
      </div>

      <div class="flex justify-end">
        <button
          @click="assignFoodComposants"
          :disabled="selectedFoodComposants.length === 0 || isAssigning"
          class="px-6 py-2 rounded-lg bg-gradient-to-r from-teal-500 to-indigo-600 dark:from-teal-400 dark:to-indigo-500 text-white hover:from-teal-600 hover:to-indigo-700 dark:hover:from-teal-500 dark:hover:to-indigo-600 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
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
import { mapActions, mapGetters } from 'vuex'
import Multiselect from 'vue-multiselect'

export default {
  name: 'MenuFoodComposantForm',
  components: { Multiselect },
  props: {
    menuId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      selectedFoodComposants: [],
      isLoading: true,
      isAssigning: false,
      isDetaching: false
    }
  },
  computed: {
    ...mapGetters('menu', ['getMenuById']),
    ...mapGetters('foodComposant', ['foodComposants']),
    menu() {
      return this.getMenuById(this.menuId)
    },
    menuName() {
      return this.menu ? this.menu.name : ''
    },
    assignedFoodComposants() {
      return this.menu && this.menu.food_composants ? this.menu.food_composants : []
    },
    availableFoodComposants() {
      const assignedIds = this.assignedFoodComposants.map(fc => fc.id)
      return this.foodComposants.filter(fc => !assignedIds.includes(fc.id))
    }
  },
  methods: {
    ...mapActions('menu', ['attachFoodComposants', 'detachFoodComposant', 'fetchMenus']),
    ...mapActions('foodComposant', ['fetchFoodComposants']),
   
    async assignFoodComposants() {
      if (this.selectedFoodComposants.length > 0) {
        this.isAssigning = true
        const foodComposantIds = this.selectedFoodComposants.map(fc => fc.id)
        try {
          await this.attachFoodComposants({
            menuId: this.menuId,
            foodComposantIds: foodComposantIds
          })
          this.selectedFoodComposants = []
          await this.fetchMenus()
        } catch (error) {
          console.error('Error assigning food composants:', error)
        } finally {
          this.isAssigning = false
        }
      }
    },
    async handleDetachFoodComposant(foodComposantId) {
      this.isDetaching = true
      try {
        await this.detachFoodComposant({
          menuId: this.menuId,
          foodComposantId
        })
        await this.fetchMenus()
      } catch (error) {
        console.error('Error detaching food composant:', error)
      } finally {
        this.isDetaching = false
      }
    },
    async loadData() {
      this.isLoading = true
      try {
        await Promise.all([
          this.fetchFoodComposants(),
          this.fetchMenus()
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
  }
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

@media (max-width: 640px) {
  .multiselect-custom {
    max-width: 100%;
  }

  .multiselect-custom .multiselect__content-wrapper {
    max-height: 50vh;
  }

  .multiselect-custom .multiselect__input {
    width: 100%;
  }
}

/* Ensure the dropdown appears above other elements */
.multiselect__content-wrapper {
  z-index: 9999 !important;
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
