<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <!-- Header Section with Background Image -->
    <div class="relative bg-indigo-600 dark:bg-indigo-800 text-white overflow-hidden">
      <div class="absolute inset-0">
        <img src="@/assets/menu-image.jpg" alt="Menu Background" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-indigo-600 dark:bg-indigo-800 mix-blend-multiply"></div>
      </div>
      <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">Menu Management</h1>
        <p class="mt-6 max-w-3xl text-xl">
          Create and manage your restaurant's menus with ease. Organize your dishes, set prices, and keep your offerings up to date.
        </p>
        <div class="mt-10 flex items-center space-x-6">
          <button
            class="bg-white text-indigo-600 hover:bg-indigo-50 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 font-semibold px-6 py-3 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105"
            @click="openCreateModal"
          >
            Create Menu
          </button>
          <a href="#menu-list" class="text-white font-semibold hover:text-indigo-200 transition duration-300">
            View Menus <span aria-hidden="true">→</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Menu List Section -->
    <div id="menu-list" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden transition-colors duration-300">
        <div class="px-4 py-5 sm:p-6">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu List</h2>
          <table class="w-full table-auto">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Food Components</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="menu in menus" :key="menu.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ menu.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ menu.description !== null ? menu.description : '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                  <button
                    class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out"
                    @click="openFoodComposantModal(menu)"
                  >
                    Manage
                  </button>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                  <button
                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2 transition duration-300 ease-in-out"
                    @click="openEditModal(menu)"
                  >
                    <font-awesome-icon icon="edit" />
                  </button>
                  <button
                    class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out"
                    @click="openDeleteConfirmation(menu)"
                  >
                    <font-awesome-icon icon="trash" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <Overlay v-if="showCreateModal">
      <div class="modal-container" @click.stop>
        <Modal :show="showCreateModal" title="Create Menu" @close="closeCreateModal">
          <menu-form
            :menu="{ name: '', description: '' }"
            @create="handleCreateMenu"
          />
        </Modal>
      </div>
    </Overlay>

    <Overlay v-if="showEditModal">
      <div class="modal-container" @click.stop>
        <Modal :show="showEditModal" title="Edit Menu" @close="closeEditModal">
          <menu-form
            :menu="selectedMenu"
            @update="handleUpdateMenu"
          />
        </Modal>
      </div>
    </Overlay>

    <Overlay v-if="showFoodComposantModal">
      <div class="modal-container" @click.stop>
        <Modal :show="showFoodComposantModal" title="Manage Food Components" @close="closeFoodComposantModal">
          <menu-food-composant-form
            :menuId="selectedMenu ? selectedMenu.id : null"
          />
        </Modal>
      </div>
    </Overlay>

    <!-- Delete Confirmation Modal -->
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
                  Delete Menu
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to delete {{ selectedMenu.name }}? This action cannot be undone.
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
              Delete
            </button>
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-100 dark:hover:bg-gray-500 dark:border-gray-500"
              @click="closeDeleteConfirmation"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </Overlay>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import MenuForm from './MenuForm.vue'
import MenuFoodComposantForm from './MenuFoodComposantForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'

export default {
  components: {
    MenuForm,
    MenuFoodComposantForm,
    Modal,
    Overlay
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      showDeleteConfirmation: false,
      showFoodComposantModal: false,
      selectedMenu: null
    }
  },
  computed: {
    ...mapGetters('menu', ['menus'])
  },
  created() {
    this.fetchMenus()
  },
  methods: {
    ...mapActions('menu', [
      'fetchMenus',
      'createMenu',
      'updateMenu',
      'deleteMenu'
    ]),
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
        })
        .catch(error => {
          console.error('Error creating menu:', error)
          // Handle error if needed
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
      this.updateMenu(menuData)
        .then(() => {
          this.closeEditModal()
        })
        .catch(error => {
          console.error('Error updating menu:', error)
          // Handle error if needed
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
        })
        .catch(error => {
          console.error('Error deleting menu:', error)
          // Handle error if needed
        })
    },
    openFoodComposantModal(menu) {
      this.selectedMenu = { ...menu }
      this.showFoodComposantModal = true
    },
    closeFoodComposantModal() {
      this.showFoodComposantModal = false
      this.selectedMenu = null
    }
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
