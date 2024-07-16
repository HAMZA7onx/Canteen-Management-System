<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-100 to-purple-200 dark:from-gray-900 dark:to-indigo-900 transition-all duration-500 ease-in-out">
    <!-- Section d'en-tête -->
    <div class="bg-white dark:bg-gray-800 shadow-lg transform -skew-y-2">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transform skew-y-2">
        <div class="flex flex-col items-center justify-between">
          <div class="mb-6 text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-600">
              Repas Quotidiens
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 font-medium">
              Créez votre journée parfaite, un délicieux repas à la fois
            </p>
          </div>
          <div class="flex items-center space-x-4 sm:space-x-6 mb-6">
            <div class="meal-type-icon" title="Petit-déjeuner">
              <span class="text-2xl sm:text-3xl">🍳</span>
            </div>
            <div class="meal-type-icon" title="Déjeuner">
              <span class="text-2xl sm:text-3xl">🥗</span>
            </div>
            <div class="meal-type-icon" title="Dîner">
              <span class="text-2xl sm:text-3xl">🍽️</span>
            </div>
          </div>
        </div>
        <div class="mt-6 flex justify-center">
          <button
            @click="openCreateModal"
            class="group relative inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 overflow-hidden text-base sm:text-lg font-medium text-indigo-600 border-2 border-indigo-600 rounded-full hover:text-white w-full sm:w-auto"
          >
            <span class="absolute left-0 block w-full h-0 transition-all bg-indigo-600 opacity-100 group-hover:h-full top-1/2 group-hover:top-0 duration-400 ease"></span>
            <span class="absolute right-0 flex items-center justify-start w-10 h-10 duration-300 transform translate-x-full group-hover:translate-x-0 ease">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </span>
            <span class="relative">Créer un Repas Quotidien</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Liste des Repas Quotidiens -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
  <div v-for="dailyMeal in sortedDailyMeals" :key="dailyMeal.id"
       class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-200 dark:border-gray-700">
    <div class="p-5">
      <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ dailyMeal.name }}</h3>
      <p class="text-gray-600 dark:text-gray-300 mb-4 h-20 overflow-y-auto text-sm">{{ dailyMeal.description !== null ? dailyMeal.description : 'Pas de description' }}</p>
      <div class="flex flex-col space-y-3">
        <button
          @click="openAssignMenuModal(dailyMeal)"
          class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-2 px-4 rounded-md transition-all duration-300 text-sm flex items-center justify-center"
        >
          <font-awesome-icon icon="utensils" class="mr-2" />
          Assigner des Menus
        </button>
        <div class="flex justify-between">
          <button
            @click="openEditModal(dailyMeal)"
            class="flex-1 mr-2 bg-yellow-400 hover:bg-yellow-500 text-white py-2 px-4 rounded-md transition-colors duration-300 text-sm flex items-center justify-center"
          >
            <font-awesome-icon icon="edit" class="mr-2" />
            Modifier
          </button>
          <button
            @click="openDeleteConfirmation(dailyMeal)"
            class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md transition-colors duration-300 text-sm flex items-center justify-center"
          >
            <font-awesome-icon icon="trash" class="mr-2" />
            Supprimer
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

    </div>

    <!-- Modals -->
    <Overlay v-if="showCreateModal">
      <div class="modal-container" @click.stop>
        <Modal :show="showCreateModal" title="Créer un Repas Quotidien" @close="closeCreateModal">
          <daily-meal-form
            :dailyMeal="{ name: '', description: '' }"
            @create="handleCreateDailyMeal"
          />
        </Modal>
      </div>
    </Overlay>

    <Overlay v-if="showEditModal">
      <div class="modal-container" @click.stop>
        <Modal :show="showEditModal" title="Modifier le Repas Quotidien" @close="closeEditModal">
          <daily-meal-form
            :dailyMeal="selectedDailyMeal"
            @update="handleUpdateDailyMeal"
          />
        </Modal>
      </div>
    </Overlay>

    <Overlay v-if="showAssignMenuModal">
      <div class="modal-container" @click.stop>
        <Modal :show="showAssignMenuModal" :title="`Assigner des Menus pour ${selectedDailyMeal.name}`" @close="closeAssignMenuModal">
          <daily-meal-menu-form
            :dailyMealId="selectedDailyMeal.id"
            :assignedMenus="selectedDailyMeal.menus"
            @assign="handleAssignMenu"
            @detach="handleDetachMenu"
            @menuAssigned="updateAssignedMenus"
            @menuDetached="updateAssignedMenus"
          />
        </Modal>
      </div>
    </Overlay>

    <Overlay v-if="showDeleteConfirmation">
      <div class="modal-container" @click.stop>
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
          <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-headline">
                  Supprimer le Repas Quotidien
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Êtes-vous sûr de vouloir supprimer ce repas quotidien ? Cette action ne peut pas être annulée.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="handleDeleteDailyMeal"
            >
              Supprimer
            </button>
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-100 dark:hover:bg-gray-500 dark:border-gray-500"
              @click="closeDeleteConfirmation"
            >
              Annuler
            </button>
          </div>
        </div>
      </div>
    </Overlay>
  </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex'
import DailyMealForm from './DailyMealForm.vue'
import DailyMealMenuForm from './DailyMealMenuForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'

export default {
  components: {
    DailyMealForm,
    DailyMealMenuForm,
    Modal,
    Overlay,
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      showAssignMenuModal: false,
      showDeleteConfirmation: false,
      selectedDailyMeal: null,
    }
  },
  computed: {
    ...mapGetters('dailyMeal', ['dailyMeals']),
    sortedDailyMeals() {
      return [...this.dailyMeals].sort((a, b) => a.name.localeCompare(b.name))
    },
  },
  created() {
    this.fetchDailyMeals()
  },
  methods: {
    ...mapActions('dailyMeal', [
      'fetchDailyMeals',
      'createDailyMeal',
      'updateDailyMeal',
      'deleteDailyMeal',
      'attachMenu',
      'detachMenu',
    ]),
    openCreateModal() {
      this.showCreateModal = true
    },
    closeCreateModal() {
      this.showCreateModal = false
    },
    handleCreateDailyMeal(dailyMealData) {
      this.createDailyMeal(dailyMealData)
        .then(() => {
          this.closeCreateModal()
        })
        .catch((error) => {
          console.error('Error creating daily meal:', error)
          // Handle error if needed
        })
    },
    openEditModal(dailyMeal) {
      this.selectedDailyMeal = { ...dailyMeal }
      this.showEditModal = true
    },
    closeEditModal() {
      this.showEditModal = false
      this.selectedDailyMeal = null
    },
    handleUpdateDailyMeal(dailyMealData) {
      this.updateDailyMeal(dailyMealData)
        .then(() => {
          this.closeEditModal()
        })
        .catch((error) => {
          console.error('Error updating daily meal:', error)
          // Handle error if needed
        })
    },
    openAssignMenuModal(dailyMeal) {
      this.selectedDailyMeal = { ...dailyMeal }
      this.showAssignMenuModal = true
    },
    closeAssignMenuModal() {
      this.showAssignMenuModal = false
      this.selectedDailyMeal = null
    },
    openDeleteConfirmation(dailyMeal) {
      this.selectedDailyMeal = { ...dailyMeal }
      this.showDeleteConfirmation = true
    },
    closeDeleteConfirmation() {
      this.showDeleteConfirmation = false
      this.selectedDailyMeal = null
    },
    handleDeleteDailyMeal() {
      this.deleteDailyMeal(this.selectedDailyMeal)
        .then(() => {
          this.closeDeleteConfirmation()
        })
        .catch((error) => {
          console.error('Error deleting daily meal:', error)
          // Handle error if needed
        })
    },
    handleAssignMenu(menuId) {
      const dailyMeals = this.dailyMeals
      this.attachMenu({ dailyMealId: this.selectedDailyMeal.id, menuId, dailyMeals })
        .then((updatedDailyMeal) => {
          this.selectedDailyMeal.menus = updatedDailyMeal.menus
          this.fetchDailyMeals()
        })
        .catch((error) => {
          console.error('Error assigning menu:', error)
          // Handle error if needed
        })
    },
    handleDetachMenu(menuId) {
      this.detachMenu({ dailyMealId: this.selectedDailyMeal.id, menuId })
        .then(() => {
          const updatedAssignedMenus = this.selectedDailyMeal.menus.filter(
            (menu) => menu.id !== menuId
          )
          this.selectedDailyMeal.menus = updatedAssignedMenus
          this.fetchDailyMeals()
        })
        .catch((error) => {
          console.error('Error detaching menu:', error)
          // Handle error if needed
        })
    },
    updateAssignedMenus(menuId = null) {
      this.selectedDailyMeal.menus = this.selectedDailyMeal.menus.filter(
        (menu) => menu.id !== menuId
      )
    },
  },
}
</script>

<style scoped>
.meal-type-icon {
  @apply flex items-center justify-center w-16 h-16 bg-white dark:bg-gray-700 rounded-full shadow-lg transform transition-all duration-300 hover:scale-110 cursor-pointer;
}

.modal-container {
  @apply flex justify-center items-center h-full;
}

/* Add these styles for the floating animation */
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
  100% { transform: translateY(0px); }
}

.meal-type-icon {
  animation: float 3s ease-in-out infinite;
}

/* Add a staggered animation delay for each meal type icon */
.meal-type-icon:nth-child(1) {
  animation-delay: 0s;
}

.meal-type-icon:nth-child(2) {
  animation-delay: 0.5s;
}

.meal-type-icon:nth-child(3) {
  animation-delay: 1s;
}

/* Add a subtle hover effect for daily meal cards */
.daily-meal-card {
  @apply transition-all duration-300 ease-in-out;
}

.daily-meal-card:hover {
  @apply transform -translate-y-2 shadow-xl;
}

/* Add a pulsing effect to the create button */
@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7); }
  70% { box-shadow: 0 0 0 10px rgba(99, 102, 241, 0); }
  100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
}

.create-button {
  animation: pulse 2s infinite;
}

/* Add a transition for dark mode */
.dark-mode-transition {
  @apply transition-colors duration-300 ease-in-out;
}
</style>
