<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold">Menus</h2>
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="openCreateModal"
      >
        Create Menu
      </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="w-full table-auto">
        <thead>
          <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
            <th class="py-3 px-6 text-left">Name</th>
            <th class="py-3 px-6 text-left">Description</th>
            <th class="py-3 px-6 text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr
            v-for="menu in menus"
            :key="menu.id"
            class="border-b border-gray-200 hover:bg-gray-100"
          >
            <td class="py-3 px-6 text-left whitespace-nowrap">
              {{ menu.name }}
            </td>
            <td class="py-3 px-6 text-left">
              {{ menu.description !== null ? menu.description : '-' }}
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2"
                @click="openEditModal(menu)"
              >
              <font-awesome-icon icon="edit" />
              </button>
              <button
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                @click="openDeleteConfirmation(menu)"
              >
              <font-awesome-icon icon="trash" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Create Menu Modal -->
    <overlay v-if="showCreateModal" @close="closeCreateModal">
      <modal :show="showCreateModal" title="Create Menu" @close="closeCreateModal">
        <menu-form
          :menu="{ name: '', description: '' }"
          @create="handleCreateMenu"
        />
      </modal>
    </overlay>

    <!-- Edit Menu Modal -->
    <overlay v-if="showEditModal" @close="closeEditModal">
      <modal :show="showEditModal" title="Edit Menu" @close="closeEditModal">
        <menu-form
          :menu="selectedMenu"
          @update="handleUpdateMenu"
        />
      </modal>
    </overlay>

    <!-- Delete Confirmation Modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto" v-if="showDeleteConfirmation">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
          class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
          role="dialog"
          aria-modal="true"
          aria-labelledby="modal-headline"
        >
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
              >
                <svg
                  class="h-6 w-6 text-red-600"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                  />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                  Delete Menu
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete {{ selectedMenu.name }}? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="handleDeleteMenu"
            >
              Delete
            </button>
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="closeDeleteConfirmation"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import MenuForm from './MenuForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'

export default {
  components: {
    MenuForm,
    Modal,
    Overlay
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      showDeleteConfirmation: false,
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
    }
  }
}
</script>
