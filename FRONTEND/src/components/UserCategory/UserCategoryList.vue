<template>
  <div class="container mx-auto px-4">
    <div class="mb-4">
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="openCreateUserCategoryModal"
      >
        Create User Category
      </button>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Category
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Meal Discount (%)
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              edit
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              delete
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="userCategory in userCategories" :key="userCategory.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ userCategory.category }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ userCategory.meal_discount }}%</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2"
                @click="openEditUserCategoryModal(userCategory)"
              >
                Edit
              </button>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"
                @click="deleteUserCategory(userCategory)"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Create User Category Modal -->
    <Overlay v-if="showCreateUserCategoryModal">
      <Modal
        :show="showCreateUserCategoryModal"
        @close="closeCreateUserCategoryModal"
        title="Create User Category"
      >
        <UserCategoryForm
          :userCategory="{}"
          @update:userCategory="createUserCategory"
        />
      </Modal>
    </Overlay>

    <!-- Edit User Category Modal -->
    <Overlay v-if="showEditUserCategoryModal">
      <Modal
        :show="showEditUserCategoryModal"
        @close="closeEditUserCategoryModal"
        title="Edit User Category"
      >
        <UserCategoryForm
          :userCategory="selectedUserCategory"
          @update:userCategory="updateUserCategory"
        />
      </Modal>
    </Overlay>

    <!-- Delete Confirmation Modal -->
    <div
      class="fixed z-10 inset-0 overflow-y-auto"
      v-if="showDeleteConfirmation"
    >
      <div
        class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
      >
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span
          class="hidden sm:inline-block sm:align-middle sm:h-screen"
          aria-hidden="true"
          >&#8203;</span
        >
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
                <h3
                  class="text-lg leading-6 font-medium text-gray-900"
                  id="modal-headline"
                >
                  Delete User Category
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete {{ userCategoryToDelete.category }}? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="confirmDeleteUserCategory"
            >
              Delete
            </button>
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="showDeleteConfirmation = false"
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
import { mapGetters, mapActions } from 'vuex';
import UserCategoryForm from '@/components/UserCategory/UserCategoryForm.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';

export default {
  name: 'UserCategoryList',
  components: {
    UserCategoryForm,
    Modal,
    Overlay,
  },
  data() {
    return {
      showCreateUserCategoryModal: false,
      showEditUserCategoryModal: false,
      showDeleteConfirmation: false,
      selectedUserCategory: null,
      userCategoryToDelete: null,
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
    openCreateUserCategoryModal() {
      this.showCreateUserCategoryModal = true;
    },
    closeCreateUserCategoryModal() {
      this.showCreateUserCategoryModal = false;
    },
    openEditUserCategoryModal(userCategory) {
      this.selectedUserCategory = { ...userCategory };
      this.showEditUserCategoryModal = true;
    },
    closeEditUserCategoryModal() {
      this.showEditUserCategoryModal = false;
      this.selectedUserCategory = null;
    },
    deleteUserCategory(userCategory) {
      this.userCategoryToDelete = userCategory;
      this.showDeleteConfirmation = true;
    },
    confirmDeleteUserCategory() {
      this.$store
        .dispatch('userCategory/deleteUserCategory', this.userCategoryToDelete.id)
        .then(() => {
          this.showDeleteConfirmation = false;
          this.userCategoryToDelete = null;
        })
        .catch((error) => {
          console.error('Error deleting user category:', error);
          this.showDeleteConfirmation = false;
          this.userCategoryToDelete = null;
        });
    },
  },
};
</script>
