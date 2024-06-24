<template>
  <div class="p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-4">Meal Schedules</h2>

    <div class="mb-4">
      <button
        class="bg-blue-500 text-white px-4 py-2 rounded-md"
        @click="showCreateModal = true"
      >
        Create New Schedule
      </button>
    </div>

    <div v-if="showCreateModal || showEditModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <Overlay />
      <Modal
        :show="showCreateModal || showEditModal"
        :title="modalTitle"
        @close="closeModal"
      >
        <MealScheduleForm
          :mealSchedule="modalMealSchedule"
          @update:mealSchedule="handleMealScheduleUpdate"
          @close="closeModal"
        />
      </Modal>
    </div>

    <div>
      <table class="w-full table-auto">
        <thead>
          <tr>
            <th class="px-4 py-2">Meal Name</th>
            <th class="px-4 py-2">Meal Menus</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Start Time</th>
            <th class="px-4 py-2">End Time</th>
            <th class="px-4 py-2">Users Discount</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="mealSchedule in mealSchedules" :key="mealSchedule.id">
            <td class="border px-4 py-2">{{ getMealNameName(mealSchedule.meal_name_id) }}</td>
            <td class="border px-4 py-2">
              <button
                class="bg-blue-500 text-white px-2 py-1 rounded-md"
                @click="showMealMenus(mealSchedule)"
              >
                View Menus
              </button>
            </td>
            <td class="border px-4 py-2">{{ mealSchedule.date }}</td>
            <td class="border px-4 py-2">{{ mealSchedule.start_time }}</td>
            <td class="border px-4 py-2">{{ mealSchedule.end_time }}</td>
            <td class="border px-4 py-2">
              <button
                class="bg-blue-500 text-white px-2 py-1 rounded-md"
                @click="showUserCategoryDiscounts(mealSchedule)"
              >
                View Discounts
              </button>
            </td>
            <td class="border px-4 py-2">
              <button
                class="bg-blue-500 text-white px-2 py-1 rounded-md mr-2"
                @click="openEditModal(mealSchedule)"
              >
                Edit
              </button>
              <button
                class="bg-red-500 text-white px-2 py-1 rounded-md"
                @click="showDeleteConfirmation(mealSchedule)"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Meal Menus Modal -->
    <div v-if="showMealMenusModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <Overlay />
      <Modal
        :show="showMealMenusModal"
        title="Meal Menus"
        @close="closeMealMenusModal"
      >
        <div v-if="selectedMealSchedule">
          <h3 class="text-lg font-medium mb-2">Meal Name: {{ getMealNameName(selectedMealSchedule.meal_name_id) }}</h3>
          <ul>
            <li v-for="mealMenu in selectedMealSchedule.meal_menus" :key="mealMenu.id">
              {{ mealMenu.menu_name }}
            </li>
          </ul>
        </div>
      </Modal>
    </div>

    <!-- User Category Discounts Modal -->
    <div v-if="showUserCategoryDiscountsModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <Overlay />
      <Modal
        :show="showUserCategoryDiscountsModal"
        title="User Category Discounts"
        @close="closeUserCategoryDiscountsModal"
      >
        <UserCategoryDiscountsModal
          :show="showUserCategoryDiscountsModal"
          :mealSchedule="selectedMealSchedule"
          @close="closeUserCategoryDiscountsModal"
        />
      </Modal>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteConfirmationModal" class="fixed z-10 inset-0 overflow-y-auto">
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
                  Delete Meal Schedule
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this meal schedule? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="confirmDeleteMealSchedule"
            >
              Delete
            </button>
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="closeDeleteConfirmationModal"
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
import MealScheduleForm from './MealScheduleForm.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';
import UserCategoryDiscountsModal from '@/components/Meal/Schedule/UserCategoryDiscountsModal.vue';

export default {
  name: 'MealScheduleList',
  components: {
    MealScheduleForm,
    Modal,
    Overlay,
    UserCategoryDiscountsModal,
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      showMealMenusModal: false,
      showUserCategoryDiscountsModal: false,
      showDeleteConfirmationModal: false,
      modalMealSchedule: {
        meal_name_id: null,
        meal_menu_ids: [],
        date: '',
        start_time: '',
        end_time: '',
      },
      mealScheduleToDelete: null,
      selectedMealSchedule: null,
    };
  },
  computed: {
    ...mapGetters('mealSchedule', ['mealSchedules']),
    ...mapGetters('mealName', ['mealNames']),
    ...mapGetters('mealMenu', ['mealMenus']),
    modalTitle() {
      return this.showCreateModal ? 'Create New Meal Schedule' : 'Edit Meal Schedule';
    },
  },
  created() {
    this.fetchMealSchedules();
    this.fetchMealNames();
    this.fetchMealMenus();
  },
  mounted() {
    console.log('mealSchedules :', this.mealSchedules);
  },
  methods: {
    ...mapActions('mealSchedule', ['fetchMealSchedules', 'createMealSchedule', 'updateMealSchedule', 'deleteMealSchedule']),
    ...mapActions('mealName', ['fetchMealNames']),
    ...mapActions('mealMenu', ['fetchMealMenus']),
    getMealNameName(mealNameId) {
      const mealName = this.mealNames.find((name) => name.id === mealNameId);
      return mealName ? mealName.name : '';
    },
    getMealMenuName(mealMenuId) {
      const mealMenu = this.mealMenus.find((menu) => menu.id === mealMenuId);
      return mealMenu ? mealMenu.menu_name : '';
    },
    openEditModal(mealSchedule) {
      console.log('Meal Schedule Data:', mealSchedule);
      this.modalMealSchedule = {
        ...mealSchedule,
        meal_menu_ids: mealSchedule.meal_menu_ids || [],
      };
      this.showEditModal = true;
    },
    closeModal() {
      this.showCreateModal = false;
      this.showEditModal = false;
      this.modalMealSchedule = {
        meal_name_id: null,
        meal_menu_ids: [],
        date: '',
        start_time: '',
        end_time: '',
      };
    },
    handleMealScheduleUpdate(mealSchedule) {
      if (this.showCreateModal) {
        this.createMealSchedule(mealSchedule);
      } else {
        this.updateMealSchedule(mealSchedule);
      }
      this.closeModal();
    },
    showDeleteConfirmation(mealSchedule) {
      this.mealScheduleToDelete = mealSchedule;
      this.showDeleteConfirmationModal = true;
    },
    closeDeleteConfirmationModal() {
      this.showDeleteConfirmationModal = false;
      this.mealScheduleToDelete = null;
    },
    confirmDeleteMealSchedule() {
      if (this.mealScheduleToDelete) {
        this.deleteMealSchedule(this.mealScheduleToDelete.id)
          .then(() => {
            this.closeDeleteConfirmationModal();
          })
          .catch((error) => {
            console.error('Error deleting meal schedule:', error);
            this.closeDeleteConfirmationModal();
          });
      }
    },
    showMealMenus(mealSchedule) {
      this.selectedMealSchedule = mealSchedule;
      this.showMealMenusModal = true;
    },
    closeMealMenusModal() {
      this.showMealMenusModal = false;
      this.selectedMealSchedule = null;
    },
    showUserCategoryDiscounts(mealSchedule) {
      this.selectedMealSchedule = mealSchedule;
      this.showUserCategoryDiscountsModal = true;
    },
    closeUserCategoryDiscountsModal() {
      this.showUserCategoryDiscountsModal = false;
      this.selectedMealSchedule = null;
    },
  },
};
</script>
