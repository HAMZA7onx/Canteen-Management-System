<template>
    <div>
      <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
        Assign Food Composants for Menu: {{ menuName }}
      </h3>
  
      <div v-if="assignedFoodComposants.length === 0" class="text-sm text-gray-500 mb-4">
        No food composants assigned.
      </div>
  
      <ul v-else role="list" class="divide-y divide-gray-200">
        <li
          v-for="foodComposant in assignedFoodComposants"
          :key="foodComposant.id"
          class="py-4 flex items-center justify-between"
        >
          <div class="flex items-center">
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-900">{{ foodComposant.name }}</p>
              <p class="text-sm text-gray-500">{{ foodComposant.description }}</p>
            </div>
          </div>
          <div>
            <button
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
              @click="handleDetachFoodComposant(foodComposant.id)"
            >
              Detach
            </button>
          </div>
        </li>
      </ul>
  
      <div class="mt-4">
        <label for="foodComposantSelect" class="block text-sm font-medium text-gray-700">Select Food Composant</label>
        <select
          id="foodComposantSelect"
          v-model="selectedFoodComposantId"
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
          <option value="" disabled selected>Select a food composant</option>
          <option
            v-for="foodComposant in availableFoodComposants"
            :key="foodComposant.id"
            :value="foodComposant.id"
          >{{ foodComposant.name }}</option>
        </select>
      </div>
      <div class="mt-4 flex justify-end">
        <button
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          @click="assignFoodComposant"
          :disabled="!selectedFoodComposantId"
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