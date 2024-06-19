<template>
  <div>
    <h2 class="text-2xl font-bold mb-4">Meal Schedules</h2>
    <div class="mb-4">
      <button
        class="bg-blue-500 text-white px-4 py-2 rounded-md"
        @click="showCreateModal = true"
      >
        Create New Schedule
      </button>
    </div>
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
    <div>
      <table class="w-full table-auto">
        <thead>
          <tr>
            <th class="px-4 py-2">Meal Name</th>
            <th class="px-4 py-2">Meal Menus</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Start Time</th>
            <th class="px-4 py-2">End Time</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="mealSchedule in mealSchedules" :key="mealSchedule.id">
            <td class="border px-4 py-2">{{ getMealNameName(mealSchedule.meal_name_id) }}</td>
            <td class="border px-4 py-2">
              <span v-for="(mealMenuId, index) in mealSchedule.meal_menu_ids" :key="index">
                {{ getMealMenuName(mealMenuId) }}{{ index !== mealSchedule.meal_menu_ids.length - 1 ? ', ' : '' }}
              </span>
            </td>
            <td class="border px-4 py-2">{{ mealSchedule.date }}</td>
            <td class="border px-4 py-2">{{ mealSchedule.start_time }}</td>
            <td class="border px-4 py-2">{{ mealSchedule.end_time }}</td>
            <td class="border px-4 py-2">
              <button
                class="bg-blue-500 text-white px-2 py-1 rounded-md mr-2"
                @click="showEditModal(mealSchedule)"
              >
                Edit
              </button>
              <button
                class="bg-red-500 text-white px-2 py-1 rounded-md"
                @click="deleteMealSchedule(mealSchedule.id)"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import MealScheduleForm from './MealScheduleForm.vue';
import Modal from '@/components/shared/Modal.vue';
import Overlay from '@/components/shared/Overlay.vue';

export default {
  name: 'MealScheduleList',
  components: {
    MealScheduleForm,
    Modal,
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      modalMealSchedule: {
        meal_name_id: null,
        meal_menu_ids: [],
        date: '',
        start_time: '',
        end_time: '',
      },
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
    showEditModal(mealSchedule) {
      this.modalMealSchedule = { ...mealSchedule };
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
  },
};
</script>
