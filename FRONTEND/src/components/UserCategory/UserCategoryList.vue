<template>
  <div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-4">User Categories</h2>
    <div class="mb-4">
      <button
        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
        @click="openCreateModal"
      >
        Create User Category
      </button>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th
            scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
          >
            Name
          </th>
          <th
            scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
          >
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-for="category in userCategories" :key="category.id">
          <td class="px-6 py-4 whitespace-nowrap">{{ category.name }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex">
            <button
              class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2"
              @click="openEditModal(category)"
            >
              Edit
            </button>
            <button
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"
              @click="deleteCategory(category)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Create/Edit Modal -->
    <teleport to="body">
      <user-category-form
        v-if="showModal"
        :category="selectedCategory"
        @submit="handleSubmit"
        @close="closeModal"
      />
    </teleport>

    <!-- Delete Confirmation Modal -->
    <teleport to="body">
      <div v-if="showDeleteConfirmation" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
          <div
            class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full"
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
                  <h3
                    class="text-lg leading-6 font-medium text-gray-900"
                    id="modal-headline"
                  >
                    Delete User Category
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      Are you sure you want to delete {{ categoryToDelete.name }}?
                      This action cannot be undone.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                @click="confirmDelete"
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
    </teleport>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import UserCategoryForm from '@/components/UserCategory/UserCategoryForm.vue';

export default {
  components: {
    UserCategoryForm,
  },
  data() {
    return {
      showModal: false,
      showDeleteConfirmation: false,
      selectedCategory: null,
      categoryToDelete: null,
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
      this.showModal = true;
    },
    openEditModal(category) {
      this.selectedCategory = { ...category };
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.selectedCategory = null;
    },
    handleSubmit(category) {
      if (category.id) {
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
  },
};
</script>
