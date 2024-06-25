<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold">Food Composants</h2>
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="openCreateModal"
      >
        Create Food Composant
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
            v-for="foodComposant in foodComposants"
            :key="foodComposant.id"
            class="border-b border-gray-200 hover:bg-gray-100"
          >
            <td class="py-3 px-6 text-left whitespace-nowrap">
              {{ foodComposant.name }}
            </td>
            <td class="py-3 px-6 text-left">
              {{ foodComposant.description }}
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2"
                @click="openEditModal(foodComposant)"
              >
                Edit
              </button>
              <button
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                @click="openDeleteConfirmation(foodComposant)"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Create Food Composant Modal -->
    <overlay v-if="showCreateModal" @close="showCreateModal = false">
      <modal :show="showCreateModal" title="Create Food Composant" @close="showCreateModal = false">
        <food-composant-form
          :foodComposant="{ name: '', description: '' }"
          @create="createFoodComposant"
          @close="showCreateModal = false"
        />
      </modal>
    </overlay>

    <!-- Edit Food Composant Modal -->
    <overlay v-if="showEditModal" @close="showEditModal = false">
      <modal :show="showEditModal" title="Edit Food Composant" @close="showEditModal = false">
        <food-composant-form
          :foodComposant="selectedFoodComposant"
          @update="updateFoodComposant"
          @close="showEditModal = false"
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
                  Delete Food Composant
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete {{ selectedFoodComposant.name }}? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="handleDeleteFoodComposant"
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
import FoodComposantForm from './FoodComposantForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'

export default {
  components: {
    FoodComposantForm,
    Modal,
    Overlay
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      showDeleteConfirmation: false,
      selectedFoodComposant: null
    }
  },
  computed: {
    ...mapGetters('foodComposant', ['foodComposants'])
  },
  created() {
    this.fetchFoodComposants()
  },
  methods: {
    ...mapActions('foodComposant', [
      'fetchFoodComposants',
      'createFoodComposant',
      'updateFoodComposant',
      'deleteFoodComposant'
    ]),
    openCreateModal() {
      this.showCreateModal = true
    },
    closeCreateModal() {
      this.showCreateModal = false
    },
    openEditModal(foodComposant) {
      this.selectedFoodComposant = { ...foodComposant }
      this.showEditModal = true
    },
    closeEditModal() {
      this.showEditModal = false
      this.selectedFoodComposant = null
    },
    openDeleteConfirmation(foodComposant) {
      this.selectedFoodComposant = { ...foodComposant }
      this.showDeleteConfirmation = true
    },
    closeDeleteConfirmation() {
      this.showDeleteConfirmation = false
      this.selectedFoodComposant = null
    },
    handleDeleteFoodComposant() {
      this.deleteFoodComposant(this.selectedFoodComposant)
        .then(() => {
          this.closeDeleteConfirmation()
        })
        .catch(error => {
          console.error('Error deleting food composant:', error)
          // Handle error if needed
        })
    }
  }
}
</script>