<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-100 to-indigo-200 dark:from-gray-900 dark:to-indigo-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 mb-8 transform hover:scale-105 transition-all duration-300">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-600 mb-4">User Category Hub</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-4">
          Manage and organize your user categories efficiently. Create, edit, and delete categories to keep your user base well-structured.
        </p>
        <div class="flex items-center text-sm text-indigo-600 dark:text-indigo-400">
          <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
          </svg>
          Streamline your user management process
        </div>
      </div>

      <!-- Action Button -->
      <div class="mb-6">
        <button
          class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transform hover:scale-105 transition-all duration-300"
          @click="openCreateModal"
        >
          <span class="mr-2">+</span> Create User Category
        </button>
      </div>

      <!-- Categories Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transition-colors duration-300">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Details</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="category in userCategories" :key="category.id" class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ category.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ category.description }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition-colors duration-300"
                    @click="openDetailsPopup(category)"
                  >
                    View Details
                  </button>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 mr-3 transition-colors duration-300"
                    @click="openEditModal(category)"
                  >
                    <font-awesome-icon icon="edit" />
                  </button>
                  <button
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-300"
                    @click="deleteCategory(category)"
                  >
                    <font-awesome-icon icon="trash" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Create/Edit Modal -->
      <Overlay v-if="showModal">
        <div class="modal-container" @click.stop>
          <Modal :show="showModal" :title="isEditMode ? 'Edit User Category' : 'Create User Category'" @close="closeModal">
            <user-category-form
              :category="selectedCategory"
              @submit="handleSubmit"
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
                  <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                    Delete User Category
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      Are you sure you want to delete {{ categoryToDelete?.name }}? This action cannot be undone.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-700 dark:hover:bg-red-800 transition-colors duration-300"
                @click="confirmDelete"
              >
                Delete
              </button>
              <button
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors duration-300"
                @click="closeDeleteConfirmation"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </Overlay>

      <!-- Details Popup -->
      <Overlay v-if="showDetailsPopup">
        <div class="modal-container" @click.stop>
          <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                  <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Category Details
                  </h3>
                  <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                      <p class="text-gray-500 dark:text-gray-400"><strong>Name:</strong></p>
                      <p class="text-gray-900 dark:text-gray-100">{{ selectedCategory.name }}</p>
                    </div>
                    <div>
                      <p class="text-gray-500 dark:text-gray-400"><strong>Description:</strong></p>
                      <p class="text-gray-900 dark:text-gray-100">{{ selectedCategory.description }}</p>
                    </div>
                    <div>
                      <p class="text-gray-500 dark:text-gray-400"><strong>Creator:</strong></p>
                      <p class="text-gray-900 dark:text-gray-100">{{ selectedCategory.creator }}</p>
                    </div>
                  </div>
                  <div class="mt-4">
                    <p class="text-gray-500 dark:text-gray-400"><strong>Editors:</strong></p>
                    <ul v-if="selectedCategory.editors && selectedCategory.editors.length > 0" class="list-disc list-inside text-gray-900 dark:text-gray-100">
                      <li v-for="editor in selectedCategory.editors" :key="editor">{{ editor }}</li>
                    </ul>
                    <p v-else class="text-gray-900 dark:text-gray-100">No editors</p>
                  </div>
                  <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                      <p class="text-gray-500 dark:text-gray-400"><strong>Created At:</strong></p>
                      <p class="text-gray-900 dark:text-gray-100">{{ formatDate(selectedCategory.created_at) }}</p>
                    </div>
                    <div>
                      <p class="text-gray-500 dark:text-gray-400"><strong>Updated At:</strong></p>
                      <p class="text-gray-900 dark:text-gray-100">
                        <span v-if="isUpdatedAtSameAsCreatedAt(selectedCategory)">
                          {{ formatDate(selectedCategory.updated_at) }} (No updates)
                        </span>
                        <span v-else>
                          {{ formatDate(selectedCategory.updated_at) }}
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-colors duration-300"
                @click="closeDetailsPopup"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </Overlay>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import UserCategoryForm from '@/components/UserCategory/UserCategoryForm.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';

export default {
  components: {
    UserCategoryForm,
    Modal,
    Overlay,
  },
  data() {
    return {
      showModal: false,
      showDeleteConfirmation: false,
      showDetailsPopup: false,
      selectedCategory: null,
      categoryToDelete: null,
      isEditMode: false,
    };
  },
  computed: {
    ...mapGetters('userCategory', ['userCategories']),
  },
  created() {
    this.fetchUserCategories();
  },
  methods: {
    ...mapActions('userCategory', [
      'fetchUserCategories',
      'createUserCategory',
      'updateUserCategory',
      'deleteUserCategory',
    ]),
    openCreateModal() {
      this.selectedCategory = {};
      this.isEditMode = false;
      this.showModal = true;
    },
    openEditModal(category) {
      this.selectedCategory = { ...category };
      this.isEditMode = true;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.selectedCategory = null;
      this.isEditMode = false;
    },
    handleSubmit(category) {
      if (this.isEditMode) {
        this.updateUserCategory(category)
          .then(() => {
            this.closeModal();
          })
          .catch((error) => {
            console.error('Error updating user category:', error);
          });
      } else {
        this.createUserCategory(category)
          .then(() => {
            this.closeModal();
          })
          .catch((error) => {
            console.error('Error creating user category:', error);
          });
      }
    },
    deleteCategory(category) {
      this.categoryToDelete = category;
      this.showDeleteConfirmation = true;
    },
    closeDeleteConfirmation() {
      this.showDeleteConfirmation = false;
      this.categoryToDelete = null;
    },
    confirmDelete() {
      this.deleteUserCategory(this.categoryToDelete.id)
        .then(() => {
          this.closeDeleteConfirmation();
        })
        .catch((error) => {
          console.error('Error deleting user category:', error);
          this.closeDeleteConfirmation();
        });
    },
    openDetailsPopup(category) {
      this.selectedCategory = { ...category };
      this.showDetailsPopup = true;
    },
    closeDetailsPopup() {
      this.showDetailsPopup = false;
      this.selectedCategory = null;
    },
    formatDate(date) {
      return new Date(date).toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    isUpdatedAtSameAsCreatedAt(category) {
      return new Date(category.created_at).getTime() === new Date(category.updated_at).getTime();
    },
  },
};
</script>

<style scoped>
.modal-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
</style>
