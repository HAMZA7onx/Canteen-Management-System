<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 space-y-6">
      <h3 class="text-2xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600 dark:from-teal-300 dark:to-indigo-400">
        Attribuer des composants alimentaires au menu: {{ menuName }}
      </h3>

      <div v-if="assignedFoodComposants.length === 0" class="text-sm text-gray-500 dark:text-gray-400 text-center italic">
        Aucun composant alimentaire attribué pour l'instant.
      </div>

      <ul v-else role="list" class="space-y-3">
        <li
          v-for="foodComposant in assignedFoodComposants"
          :key="foodComposant.id"
          class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 flex items-center justify-between transition duration-300 hover:shadow-md"
        >
          <div>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ foodComposant.name }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ foodComposant.description }}</p>
          </div>
          <button
            @click="handleDetachFoodComposant(foodComposant.id)"
            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
          >
            Detach
          </button>
        </li>
      </ul>

      <div class="space-y-2">
        <label for="foodComposantSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sélectionnez le composant alimentaire</label>
        <select
          id="foodComposantSelect"
          v-model="selectedFoodComposantId"
          class="w-full px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-teal-500 dark:focus:border-teal-400 focus:outline-none transition duration-300"
        >
          <option value="" disabled selected>Select a food component</option>
          <option
            v-for="foodComposant in availableFoodComposants"
            :key="foodComposant.id"
            :value="foodComposant.id"
          >{{ foodComposant.name }}</option>
        </select>
      </div>

      <div class="flex justify-end">
        <button
          @click="assignFoodComposant"
          :disabled="!selectedFoodComposantId"
          class="px-6 py-2 rounded-lg bg-gradient-to-r from-teal-500 to-indigo-600 dark:from-teal-400 dark:to-indigo-500 text-white hover:from-teal-600 hover:to-indigo-700 dark:hover:from-teal-500 dark:hover:to-indigo-600 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Assign
        </button>
      </div>
    </div>
</template>

  
  <script>
  import { mapActions, mapGetters } from 'vuex'
  
  export default {
    name: 'MenuFoodComposantForm',
    props: {
      menuId: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        selectedFoodComposantId: null
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
      ...mapActions('menu', ['attachFoodComposant', 'detachFoodComposant', 'fetchMenus']),
      ...mapActions('foodComposant', ['fetchFoodComposants']),
      
      assignFoodComposant() {
        if (this.selectedFoodComposantId) {
          this.attachFoodComposant({ 
            menuId: this.menuId, 
            foodComposantId: this.selectedFoodComposantId 
          })
            .then(() => {
              this.selectedFoodComposantId = null
              this.fetchMenus() // Refresh the menus to update the assigned food composants
            })
            .catch(error => {
              console.error('Error assigning food composant:', error)
            })
        }
      },
      handleDetachFoodComposant(foodComposantId) {
        this.detachFoodComposant({ 
          menuId: this.menuId, 
          foodComposantId 
        })
          .then(() => {
            this.fetchMenus() // Refresh the menus to update the assigned food composants
          })
          .catch(error => {
            console.error('Error detaching food composant:', error)
          })
      }
    },
    created() {
      this.fetchFoodComposants()
    }
  }
  </script>