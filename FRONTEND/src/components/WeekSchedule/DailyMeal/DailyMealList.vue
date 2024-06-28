<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold">Daily Meals</h2>
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="openCreateModal"
      >
        Create Daily Meal
      </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="w-full table-auto">
        <thead>
          <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
            <th class="py-3 px-6 text-left">Name</th>
            <th class="py-3 px-6 text-left">Description</th>
            <th class="py-3 px-6 text-center">Menus</th>
            <th class="py-3 px-6 text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr
            v-for="dailyMeal in sortedDailyMeals"
            :key="dailyMeal.id"
            class="border-b border-gray-200 hover:bg-gray-100"
          >
            <td class="py-3 px-6 text-left whitespace-nowrap">
              {{ dailyMeal.name }}
            </td>
            <td class="py-3 px-6 text-left">
              {{ dailyMeal.description !== null ? dailyMeal.description : '-' }}
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                @click="openAssignMenuModal(dailyMeal)"
              >
                Assign Menus
              </button>
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2"
                @click="openEditModal(dailyMeal)"
              >
                Show
              </button>
              <button
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                @click="openDeleteConfirmation(dailyMeal)"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Create Daily Meal Modal -->
    <overlay v-if="showCreateModal" @close="closeCreateModal">
      <modal :show="showCreateModal" title="Create Daily Meal" @close="closeCreateModal">
        <daily-meal-form
          :dailyMeal="{ name: '', description: '' }"
          @create="handleCreateDailyMeal"
        />
      </modal>
    </overlay>

    <!-- Edit Daily Meal Modal -->
    <overlay v-if="showEditModal" @close="closeEditModal">
      <modal :show="showEditModal" title="Edit Daily Meal" @close="closeEditModal">
        <daily-meal-form
          :dailyMeal="selectedDailyMeal"
          @update="handleUpdateDailyMeal"
        />
      </modal>
    </overlay>

    <!-- Assign Menu Modal -->
    <overlay v-if="showAssignMenuModal" @close="closeAssignMenuModal">
    <modal
      :show="showAssignMenuModal"
      :title="`Assign Menus for ${selectedDailyMeal.name}`"
      @close="closeAssignMenuModal"
    >
      <daily-meal-menu-form
        :dailyMealId="selectedDailyMeal.id"
        :assignedMenus="selectedDailyMeal.menus"
        @assign="handleAssignMenu"
        @detach="handleDetachMenu"
        @menuAssigned="updateAssignedMenus"
        @menuDetached="updateAssignedMenus($event)"
      />
    </modal>
  </overlay>

    <!-- Delete Confirmation Modal -->
    <overlay v-if="showDeleteConfirmation" @close="closeDeleteConfirmation">
      <modal
        :show="showDeleteConfirmation"
        title="Delete Daily Meal"
        @close="closeDeleteConfirmation"
      >
        <p>Are you sure you want to delete this daily meal?</p>
        <div class="flex justify-end mt-4">
          <button
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"
            @click="handleDeleteDailyMeal"
          >
            Delete
          </button>
          <button
            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            @click="closeDeleteConfirmation"
          >
            Cancel
          </button>
        </div>
      </modal>
    </overlay>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import DailyMealForm from './DailyMealForm.vue'
import DailyMealMenuForm from './DailyMealMenuForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'

export default {
  components: {
    DailyMealForm,
    DailyMealMenuForm,
    Modal,
    Overlay,
  },
  data() {
    return {
      showCreateModal: false,
      showEditModal: false,
      showAssignMenuModal: false,
      showDeleteConfirmation: false,
      selectedDailyMeal: null,
    }
  },
  computed: {
    ...mapGetters('dailyMeal', ['dailyMeals']),
    sortedDailyMeals() {
      return [...this.dailyMeals].sort((a, b) => a.name.localeCompare(b.name))
    },
  },
  created() {
    this.fetchDailyMeals()
  },
  methods: {
    ...mapActions('dailyMeal', [
      'fetchDailyMeals',
      'createDailyMeal',
      'updateDailyMeal',
      'deleteDailyMeal',
      'attachMenu',
      'detachMenu',
    ]),
    openCreateModal() {
      this.showCreateModal = true
    },
    closeCreateModal() {
      this.showCreateModal = false
    },
    handleCreateDailyMeal(dailyMealData) {
      this.createDailyMeal(dailyMealData)
        .then(() => {
          this.closeCreateModal()
        })
        .catch((error) => {
          console.error('Error creating daily meal:', error)
          // Handle error if needed
        })
    },
    openEditModal(dailyMeal) {
      this.selectedDailyMeal = { ...dailyMeal }
      this.showEditModal = true
    },
    closeEditModal() {
      this.showEditModal = false
      this.selectedDailyMeal = null
    },
    handleUpdateDailyMeal(dailyMealData) {
      this.updateDailyMeal(dailyMealData)
        .then(() => {
          this.closeEditModal()
        })
        .catch((error) => {
          console.error('Error updating daily meal:', error)
          // Handle error if needed
        })
    },
    openAssignMenuModal(dailyMeal) {
      this.selectedDailyMeal = { ...dailyMeal }
      this.showAssignMenuModal = true
    },
    closeAssignMenuModal() {
      this.showAssignMenuModal = false
      this.selectedDailyMeal = null
    },
    handleAssignMenu(menuId) {
      this.attachMenu({ dailyMealId: this.selectedDailyMeal.id, menuId })
        .then(() => {
          this.fetchDailyMeals()
        })
        .catch((error) => {
          console.error('Error assigning menu:', error)
          // Handle error if needed
        })
    },
    handleDetachMenu(menuId) {
      this.detachMenu({ dailyMealId: this.selectedDailyMeal.id, menuId })
        .then(() => {
          this.fetchDailyMeals()
        })
        .catch((error) => {
          console.error('Error detaching menu:', error)
          // Handle error if needed
        })
    },
    openDeleteConfirmation(dailyMeal) {
      this.selectedDailyMeal = { ...dailyMeal }
      this.showDeleteConfirmation = true
    },
    closeDeleteConfirmation() {
      this.showDeleteConfirmation = false
      this.selectedDailyMeal = null
    },
    handleDeleteDailyMeal() {
      this.deleteDailyMeal(this.selectedDailyMeal)
        .then(() => {
          this.closeDeleteConfirmation()
        })
        .catch((error) => {
          console.error('Error deleting daily meal:', error)
          // Handle error if needed
        })
    },
  },
}
</script>
