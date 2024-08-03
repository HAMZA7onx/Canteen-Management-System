<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <!-- Section d'en-tête avec image de fond -->
    <div class="relative bg-indigo-600 dark:bg-indigo-800 text-white overflow-hidden">
      <div class="absolute inset-0">
        <img src="@/assets/menu-image.jpg" alt="Arrière-plan du menu" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-indigo-600 dark:bg-indigo-800 mix-blend-multiply"></div>
      </div>
      <div class="relative max-w-7xl mx-auto py-12 px-4 sm:py-24 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl">Gestion des Menus</h1>
        <p class="mt-4 max-w-3xl text-lg sm:text-xl">
          Créez et gérez facilement les menus de votre restaurant. Organisez vos plats, définissez les prix et tenez vos offres à jour.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
          <button
            v-if="$can('creer_categories_menus')"
            class="relative inline-flex items-center justify-center p-0.5 mb-4 sm:mb-0 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-500 to-pink-500 group-hover:from-purple-500 group-hover:to-pink-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-purple-200 dark:focus:ring-purple-800 w-full sm:w-auto"
            @click="openCreateModal"
          >
            <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
              Créer un Menu
            </span>
          </button>
          <a href="#menu-list" class="text-white font-semibold hover:text-indigo-200 transition duration-300">
            Voir les Menus <span aria-hidden="true">→</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Section Liste des Menus -->
    <div id="menu-list" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden transition-colors duration-300">
        <div class="px-4 py-5 sm:p-6">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Liste des Menus</h2>
          
          <!-- Search bar -->
          <div class="mb-4">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher un menu..."
              class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            />
          </div>

          <loading-wheel v-if="isLoading" />
          <div v-else-if="filteredMenus.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucun menu</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par créer un nouveau menu pour votre restaurant.</p>
            <div class="mt-6">
              <button
                v-if="$can('creer_categories_menus')"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600"
                @click="openCreateModal"
              >
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Créer un menu
              </button>
            </div>
          </div>
          <div v-else>
            <!-- Desktop view -->
            <table class="w-full table-auto hidden md:table">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dernière mise à jour</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Composants Alimentaires</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="menu in paginatedMenus" :key="menu.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ menu.name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                    {{ menu.description === 'null' || menu.description === null ? '-' : menu.description }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <img
                      v-if="menu.image"
                      :src="getImageUrl(menu.image)"
                      alt="Menu image"
                      class="h-16 w-16 object-cover rounded-full mr-4"
                    />
                    <span v-else class="text-gray-400">No image</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                    {{ formatDate(menu.updated_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                    <button
                      v-if="$can('assigner_composants_menus') || $can('desassigner_composants_menus')"
                      class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out"
                      @click="openFoodComposantModal(menu)"
                    >
                      Gérer
                    </button>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                    <button
                      v-if="$can('modifier_categories_menus')"
                      class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2 transition duration-300 ease-in-out"
                      @click="openEditModal(menu)"
                    >
                      <font-awesome-icon icon="edit" />
                    </button>
                    <button
                      v-if="$can('supprimer_categories_menus')"
                      class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out"
                      @click="openDeleteConfirmation(menu)"
                    >
                      <font-awesome-icon icon="trash" />
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Mobile view -->
            <ul class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
              <li v-for="menu in paginatedMenus" :key="menu.id" class="py-4">
                <img
                v-if="menu.image"
                  :src="getImageUrl(menu.image)"
                  alt="Menu image"
                  class="h-16 w-16 object-cover rounded-full mr-4"
                />
                <div v-else class="h-16 w-16 bg-gray-200 dark:bg-gray-700 rounded-full mr-4 flex items-center justify-center">
                  <span class="text-gray-400">No image</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm font-medium text-gray-900 dark:text-white">{{ menu.name }}</span>
                  <button
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200"
                    @click="toggleMenuActions(menu)"
                  >
                    <font-awesome-icon icon="ellipsis-v" />
                  </button>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                  {{ menu.description === 'null' || menu.description === null ? '-' : menu.description }}
                </p>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Dernière mise à jour: {{ formatDate(menu.updated_at) }}</p>
                <div v-if="menu.showActions" class="mt-4 space-y-2">
                  <button
                    v-if="$can('assigner_composants_menus') || $can('desassigner_composants_menus')"
                    class="w-full text-left text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200 transition-colors duration-300 flex items-center"
                    @click="openFoodComposantModal(menu)"
                  >
                    <font-awesome-icon icon="utensils" class="mr-2" />
                    Gérer les Composants
                  </button>
                  <button
                    v-if="$can('modifier_categories_menus')"
                    class="w-full text-left text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 transition-colors duration-300 flex items-center"
                    @click="openEditModal(menu)"
                  >
                    <font-awesome-icon icon="edit" class="mr-2" />
                    Modifier
                  </button>
                  <button
                    v-if="$can('supprimer_categories_menus')"
                    class="w-full text-left text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-300 flex items-center"
                    @click="openDeleteConfirmation(menu)"
                  >
                    <font-awesome-icon icon="trash" class="mr-2" />
                    Supprimer
                  </button>
                </div>
              </li>
            </ul>

            <!-- Pagination -->
            <div class="mt-4 flex justify-between items-center">
              <button
                @click="prevPage"
                :disabled="currentPage === 1"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50"
              >
                Previous
              </button>
              <span class="text-gray-600 dark:text-gray-300">
                Page {{ currentPage }} of {{ totalPages }}
              </span>
              <button
                @click="nextPage"
                :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <Overlay v-if="showCreateModal">
      <div class="modal-container" @click.stop>
        <Modal :show="showCreateModal" title="Créer un Menu" @close="closeCreateModal">
          <menu-form
            :menu="{ name: '', description: '' }"
            @create="handleCreateMenu"
          />
        </Modal>
      </div>
    </Overlay>

    <Overlay v-if="showEditModal">
      <div class="modal-container" @click.stop>
        <Modal :show="showEditModal" title="Modifier le Menu" @close="closeEditModal">
          <menu-form
            :menu="selectedMenu"
            @update="handleUpdateMenu"
          />
        </Modal>
      </div>
    </Overlay>

    <Overlay v-if="showFoodComposantModal">
      <div class="modal-container" @click.stop>
        <Modal :show="showFoodComposantModal" title="Gérer les Composants Alimentaires" @close="closeFoodComposantModal">
          <menu-food-composant-form
            :menuId="selectedMenu ? selectedMenu.id : null"
          />
        </Modal>
      </div>
    </Overlay>

    <!-- Modal de Confirmation de Suppression -->
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
                  Supprimer le Menu
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Êtes-vous sûr de vouloir supprimer {{ selectedMenu.name }} ? Cette action ne peut pas être annulée.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="handleDeleteMenu"
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
    <Toast :show="showToast" :message="toastMessage" />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { API_URL } from '@/config/config.js';
import MenuForm from './MenuForm.vue'
import MenuFoodComposantForm from './MenuFoodComposantForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'
import LoadingWheel from '@/components/shared/LoadingWheel.vue'
import Toast from '@/components/shared/Toast.vue';
import permissionMixin from '@/mixins/permissionMixin';

export default {
  mixins: [permissionMixin],
  components: {
    MenuForm,
    MenuFoodComposantForm,
    Modal,
    Overlay,
    LoadingWheel,
    Toast,
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      showDeleteConfirmation: false,
      showFoodComposantModal: false,
      selectedMenu: null,
      isLoading: true,
      showToast: false,
      toastMessage: '',
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10,
    }
  },
  computed: {
    ...mapGetters('menu', ['menus']),
    filteredMenus() {
      return this.menus.filter(menu =>
        menu.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        (menu.description && menu.description.toLowerCase().includes(this.searchQuery.toLowerCase()))
      )
    },
    paginatedMenus() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.filteredMenus.slice(start, end)
    },
    totalPages() {
      return Math.ceil(this.filteredMenus.length / this.itemsPerPage)
    }
  },
  watch: {
    searchQuery() {
      this.currentPage = 1
    }
  },
  created() {
    this.fetchMenus()
    this.loadMenus()
  },
  methods: {
    ...mapActions('menu', [
      'fetchMenus',
      'createMenu',
      'updateMenu',
      'deleteMenu'
    ]),
    getImageUrl(imagePath) {
      return `${API_URL.replace('/api', '')}/storage/${imagePath}`;
    },
    openCreateModal() {
      this.showCreateModal = true
    },
    closeCreateModal() {
      this.showCreateModal = false
    },
    handleCreateMenu(menuData) {
      this.createMenu(menuData)
        .then(() => {
          this.closeCreateModal()
          this.loadMenus()
          this.showSuccessToast('Menu created successfully')
        })
        .catch(error => {
          console.error('Error creating menu:', error)
        })
    },
    openEditModal(menu) {
      this.selectedMenu = { ...menu }
      this.showEditModal = true
    },
    closeEditModal() {
      this.showEditModal = false
      this.selectedMenu = null
    },
    handleUpdateMenu(menuData) {
      const updatedMenuData = {
        id: this.selectedMenu.id,
        menuData: menuData
      }
      this.updateMenu(updatedMenuData)
        .then(() => {
          this.closeEditModal()
          this.loadMenus()
          this.showSuccessToast('Menu updated successfully')
        })
        .catch(error => {
          console.error('Error updating menu:', error)
          if (error.response && error.response.data && error.response.data.errors) {
            // Display validation errors to the user
            const errorMessages = Object.values(error.response.data.errors).flat()
            this.showErrorToast(errorMessages.join('\n'))
          } else {
            this.showErrorToast('An error occurred while updating the menu')
          }
        })
    },
    openDeleteConfirmation(menu) {
      this.selectedMenu = { ...menu }
      this.showDeleteConfirmation = true
    },
    closeDeleteConfirmation() {
      this.showDeleteConfirmation = false
      this.selectedMenu = null
    },
    handleDeleteMenu() {
      this.deleteMenu(this.selectedMenu)
        .then(() => {
          this.closeDeleteConfirmation()
          this.loadMenus()
          this.showSuccessToast('Menu deleted successfully')
        })
        .catch(error => {
          console.error('Error deleting menu:', error)
        })
    },
    openFoodComposantModal(menu) {
      this.selectedMenu = { ...menu }
      this.showFoodComposantModal = true
    },
    closeFoodComposantModal() {
      this.showFoodComposantModal = false
      this.selectedMenu = null
    },
    async loadMenus() {
      this.isLoading = true
      try {
        await this.fetchMenus()
      } catch (error) {
        console.error('Error fetching menus:', error)
      } finally {
        this.isLoading = false
      }
    },
    toggleMenuActions(menu) {
      menu.showActions = !menu.showActions
      this.$forceUpdate()
    },
    showSuccessToast(message) {
      this.toastMessage = message;
      this.showToast = true;
      setTimeout(() => {
        this.showToast = false;
      }, 3000);
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

<style scoped>
.modal-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

html {
  scroll-behavior: smooth;
}
</style>
