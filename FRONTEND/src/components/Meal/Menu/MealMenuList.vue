<template>
    <div class="container mx-auto px-4">
      <!-- Meal Menu List -->
      <div class="mb-4">
        <button
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          @click="openCreateMealMenuModal"
        >
          Create Meal Menu
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
                Menu Name
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Description
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Price
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
            <tr v-for="mealMenu in mealMenus" :key="mealMenu.id">
              <td class="px-6 py-4 whitespace-nowrap">{{ mealMenu.menu_name }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ mealMenu.description }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ mealMenu.price }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex">
                <button
                  class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2"
                  @click="openEditMealMenuModal(mealMenu)"
                >
                  Edit
                </button>
                <button
                  class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"
                  @click="deleteMealMenu(mealMenu)"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <!-- Create Meal Menu Modal -->
      <Overlay v-if="showCreateMealMenuModal">
        <Modal
          :show="showCreateMealMenuModal"
          @close="closeCreateMealMenuModal"
          title="Create Meal Menu"
        >
          <MealMenuForm :mealMenu="{}" @update:mealMenu="createMealMenu" />
        </Modal>
      </Overlay>
  
      <!-- Edit Meal Menu Modal -->
      <Overlay v-if="showEditMealMenuModal">
        <Modal
          :show="showEditMealMenuModal"
          @close="closeEditMealMenuModal"
          title="Edit Meal Menu"
        >
          <MealMenuForm :mealMenu="selectedMealMenu" @update:mealMenu="updateMealMenu" />
        </Modal>
      </Overlay>
  
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
                    Delete Meal Menu
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      Are you sure you want to delete {{ mealMenuToDelete.menu_name }}? This action cannot be undone.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                @click="confirmDeleteMealMenu"
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
  import MealMenuForm from '@/components/Meal/Menu/MealMenuForm.vue';
  import Modal from '@/components/shared/Modal.vue';
  import Overlay from '@/components/shared/Overlay.vue';


  export default {
    name: 'MealMenuList',
    components: {
      MealMenuForm,
      Modal,
      Overlay,
    },
    data() {
      return {
        showCreateMealMenuModal: false,
        showEditMealMenuModal: false,
        showDeleteConfirmation: false,
        selectedMealMenu: null,
        mealMenuToDelete: null,
      };
    },
    computed: {
      ...mapGetters('mealMenu', ['mealMenus']),
    },
    created() {
      this.fetchMealMenus();
    },
    methods: {
      ...mapActions('mealMenu', ['fetchMealMenus', 'createMealMenu', 'updateMealMenu', 'deleteMealMenu']),
      openCreateMealMenuModal() {
        this.showCreateMealMenuModal = true;
      },
      closeCreateMealMenuModal() {
        this.showCreateMealMenuModal = false;
      },
      openEditMealMenuModal(mealMenu) {
        this.selectedMealMenu = { ...mealMenu };
        this.showEditMealMenuModal = true;
      },
      closeEditMealMenuModal() {
        this.showEditMealMenuModal = false;
        this.selectedMealMenu = null;
      },
      updateMealMenu(updatedMealMenu) {
        this.$store
          .dispatch('mealMenu/updateMealMenu', updatedMealMenu)
          .then(() => {
            this.closeEditMealMenuModal();
          })
          .catch((error) => {
            console.error('Error updating meal menu:', error);
          });
      },
      deleteMealMenu(mealMenu) {
        this.mealMenuToDelete = mealMenu;
        this.showDeleteConfirmation = true;
      },
      confirmDeleteMealMenu() {
        this.$store
          .dispatch('mealMenu/deleteMealMenu', this.mealMenuToDelete.id)
          .then(() => {
            this.showDeleteConfirmation = false;
            this.mealMenuToDelete = null;
          })
          .catch((error) => {
            console.error('Error deleting meal menu:', error);
            this.showDeleteConfirmation = false;
            this.mealMenuToDelete = null;
          });
      },
    },
  };
  </script>
  