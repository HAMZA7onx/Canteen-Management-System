<template>
  <div class="min-h-screen bg-food-ingredients dark:bg-food-ingredients-dark bg-cover bg-fixed py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <!-- Section d'en-tête -->
      <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-90 rounded-lg shadow-xl p-6 mb-8 transform hover:scale-105 transition-all duration-300">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-blue-500 mb-4">Gestion des Composants Alimentaires</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-4">
          Gérez efficacement vos composants alimentaires. Créez, modifiez et organisez les éléments de base de vos délicieux menus !
        </p>
        <button
          v-if="$can('creer_composants_menus')"
          class="relative inline-flex items-center justify-center p-0.5 mb-4 sm:mb-0 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-green-400 to-blue-600 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 w-full sm:w-auto"
          @click="openCreateModal"
        >
          <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
            Créer un Composant Alimentaire
          </span>
        </button>
      </div>

      <!-- Search bar -->
      <div class="mb-4">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Rechercher un composant alimentaire..."
          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        />
      </div>

      <!-- Liste des Composants Alimentaires -->
      <div class="bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-90 rounded-lg shadow-xl overflow-hidden transition-colors duration-300">
        <loading-wheel v-if="isLoading" />
        <div v-else-if="filteredFoodComposants.length === 0" class="p-8 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucun composant alimentaire</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par créer un nouveau composant alimentaire.</p>
          <div class="mt-6">
            <button
              v-if="$can('creer_composants_menus')"
              type="button"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              @click="openCreateModal"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              Nouveau composant
            </button>
          </div>
        </div>
        <div v-else>
          <table class="w-full table-auto hidden md:table">
            <thead class="bg-gray-100 dark:bg-gray-700">
              <tr class="text-gray-600 dark:text-gray-200 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Nom</th>
                <th class="py-3 px-6 text-left">Description</th>
                <th class="py-3 px-6 text-left">Dernière mise à jour</th>
                <th class="py-3 px-6 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-200 text-sm font-light">
              <tr
                v-for="foodComposant in paginatedFoodComposants"
                :key="foodComposant.id"
                class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150"
              >
                <td class="py-3 px-6 text-left whitespace-nowrap">
                  {{ foodComposant.name }}
                </td>
                <td class="py-3 px-6 text-left">
                  {{ foodComposant.description !== null ? foodComposant.description : '-' }}
                </td>
                <td class="py-3 px-6 text-left">
                  {{ formattedDates(foodComposant.updated_at) }}
                </td>

                <td class="py-3 px-6 text-center">
                  <button
                    v-if="$can('modifier_composants_menus')"
                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-full mr-2 transition-colors duration-300"
                    @click="openEditModal(foodComposant)"
                  >
                    <font-awesome-icon icon="edit" />
                  </button>
                  <button
                    v-if="$can('supprimer_composants_menus')"
                    class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full transition-colors duration-300"
                    @click="openDeleteConfirmation(foodComposant)"
                  >
                    <font-awesome-icon icon="trash" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
         
          <!-- Mobile view -->
          <ul class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
            <li v-for="foodComposant in paginatedFoodComposants" :key="foodComposant.id" class="py-4 px-4">
              <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ foodComposant.name }}</span>
                <button
                  v-if="$can('creer_composants_menus')"
                  class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200"
                  @click="toggleFoodComposantActions(foodComposant)"
                >
                  <font-awesome-icon icon="ellipsis-v" />
                </button>
              </div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ foodComposant.description !== null ? foodComposant.description : '-' }}</p>
              <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Dernière mise à jour: {{ formatDate(foodComposant.updated_at) }}</p>
              <div v-if="foodComposant.showActions" class="mt-2 space-y-2">
                <button
                  v-if="$can('modifier_composants_menus')"
                  class="w-full text-left text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 transition-colors duration-300 flex items-center"
                  @click="openEditModal(foodComposant)"
                >
                  <font-awesome-icon icon="edit" class="mr-2" />
                  Modifier
                </button>
                <button
                  v-if="$can('supprimer_composants_menus')"
                  class="w-full text-left text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-300 flex items-center"
                  @click="openDeleteConfirmation(foodComposant)"
                >
                  <font-awesome-icon icon="trash" class="mr-2" />
                  Supprimer
                </button>
              </div>
            </li>
          </ul>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4 px-6 py-3 bg-gray-50 dark:bg-gray-700">
          <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="px-3 py-1 bg-blue-500 text-white rounded-md disabled:opacity-50"
          >
            Previous
          </button>
          <span class="text-gray-600 dark:text-gray-300">
            Page {{ currentPage }} of {{ totalPages }}
          </span>
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-3 py-1 bg-blue-500 text-white rounded-md disabled:opacity-50"
          >
            Next
          </button>
        </div>
      </div>

      <!-- Modals -->
      <Overlay v-if="showCreateModal">
        <div class="modal-container" @click.stop>
          <Modal :show="showCreateModal" title="Créer un Composant Alimentaire" @close="closeCreateModal">
            <food-composant-form
              :foodComposant="{ name: '', description: '' }"
              @create="handleCreateFoodComposant"
              @close="closeCreateModal"
            />
          </Modal>
        </div>
      </Overlay>

      <Overlay v-if="showEditModal">
        <div class="modal-container" @click.stop>
          <Modal :show="showEditModal" title="Modifier le Composant Alimentaire" @close="closeEditModal">
            <food-composant-form
              :foodComposant="selectedFoodComposant"
              @update="handleUpdateFoodComposant"
              @close="closeEditModal"
            />
          </Modal>
        </div>
      </Overlay>

      <!-- Modal de Confirmation de Suppression -->
      <Overlay v-if="showDeleteConfirmation">
        <div class="rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-auto my-auto fixed inset-0 flex items-center justify-center" @click.stop>
          <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                    Supprimer le Composant Alimentaire
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      Êtes-vous sûr de vouloir supprimer {{ selectedFoodComposant.name }} ? Cette action est irréversible.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                @click="handleDeleteFoodComposant"
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
    <Toast :show="showToast" :message="toastMessage" />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import FoodComposantForm from './FoodComposantForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'
import LoadingWheel from '@/components/shared/LoadingWheel.vue'
import Toast from '@/components/shared/Toast.vue';
import permissionMixin from '@/mixins/permissionMixin';

export default {
  mixins: [permissionMixin],
  components: {
    FoodComposantForm,
    Modal,
    Overlay,
    LoadingWheel,
    Toast
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      showDeleteConfirmation: false,
      selectedFoodComposant: null,
      isLoading: true,
      showToast: false,
      toastMessage: '',
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10,
    }
  },
  computed: {
    ...mapGetters('foodComposant', ['foodComposants']),
    filteredFoodComposants() {
      return this.foodComposants.filter(composant =>
        composant.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        (composant.description && composant.description.toLowerCase().includes(this.searchQuery.toLowerCase()))
      )
    },
    paginatedFoodComposants() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.filteredFoodComposants.slice(start, end)
    },
    totalPages() {
      return Math.ceil(this.filteredFoodComposants.length / this.itemsPerPage)
    },
    formattedDates() {
      return (date) => {
        if (!date) return '-';
        return new Date(date).toLocaleString();
      };
    }
  },
  created() {
    if (this.$can('voir_composants_menus')) {
      this.fetchFoodComposants()
      this.loadFoodComposants()
    }
  },
  watch: {
    foodComposants(newValue) {
      if (newValue.length > 0) {
        console.log('Food composants loaded:', newValue)
      }
    },
    searchQuery() {
      this.currentPage = 1
    }
  },
  methods: {
    ...mapActions('foodComposant', [
      'fetchFoodComposants',
      'createFoodComposant',
      'updateFoodComposant',
      'deleteFoodComposant'
    ]),
    openCreateModal() {
      if (this.$can('creer_composants_menus')) {
        this.showCreateModal = true
      }
    },
    closeCreateModal() {
      this.showCreateModal = false
    },
    openEditModal(foodComposant) {
      if (this.$can('modifier_composants_menus')) {
        this.selectedFoodComposant = { ...foodComposant }
        this.showEditModal = true
      }
    },
    closeEditModal() {
      this.showEditModal = false
      this.selectedFoodComposant = null
    },
    openDeleteConfirmation(foodComposant) {
      if (this.$can('supprimer_composants_menus')) {
        this.selectedFoodComposant = { ...foodComposant }
        this.showDeleteConfirmation = true
      }
    },
    closeDeleteConfirmation() {
      this.showDeleteConfirmation = false
      this.selectedFoodComposant = null
    },
    handleDeleteFoodComposant() {
      if (this.$can('supprimer_composants_menus')) {
        this.deleteFoodComposant(this.selectedFoodComposant)
          .then(() => {
            this.closeDeleteConfirmation()
            this.loadFoodComposants()
            this.showSuccessToast('Composant deleted successfully!');
          })
          .catch(error => {
            console.error('Error deleting food composant:', error)
          })
      }
    },
    async loadFoodComposants() {
      this.isLoading = true
      try {
        await this.fetchFoodComposants()
      } catch (error) {
        console.error('Error fetching food composants:', error)
      } finally {
        this.isLoading = false
      }
    },
    toggleFoodComposantActions(foodComposant) {
      foodComposant.showActions = !foodComposant.showActions
      this.$forceUpdate()
    },
    showSuccessToast(message) {
      this.toastMessage = message;
      this.showToast = true;
      setTimeout(() => {
        this.showToast = false;
      }, 3000);
    },
    handleCreateFoodComposant(formData, { resolve, reject }) {
      if (this.$can('creer_composants_menus')) {
        this.createFoodComposant(formData)
          .then(() => {
            this.loadFoodComposants()
            this.showSuccessToast('Food component created successfully')
            resolve()
          })
          .catch(error => {
            console.error('Error creating food composant:', error)
            reject(error)
          })
      }
    },

    handleUpdateFoodComposant(formData, { resolve, reject }) {
      if (this.$can('modifier_composants_menus')) {
        this.updateFoodComposant(formData)
          .then(() => {
            this.loadFoodComposants()
            this.showSuccessToast('Food component updated successfully')
            resolve()
          })
          .catch(error => {
            console.error('Error updating food composant:', error)
            reject(error)
          })
      }
    },

    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++
      }
    },
    formatDate(date) {
      if (!date) return '-';
      return new Date(date).toLocaleString();
    },
  }
}
</script>

