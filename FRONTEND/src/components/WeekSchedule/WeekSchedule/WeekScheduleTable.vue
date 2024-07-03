<template>
  <div class="container mx-auto px-4 py-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-indigo-900 min-h-screen">
    <div class="mb-8 text-center">
      <h1 class="text-4xl font-extrabold text-indigo-700 dark:text-indigo-300 mb-2">Week Schedules</h1>
      <p class="text-lg text-gray-600 dark:text-gray-400">Efficiently manage and organize your weekly meal plans</p>
    </div>

    <div class="mb-6 flex justify-between items-center">
      <button
        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-300 ease-in-out flex items-center"
        @click="openCreateModal"
      >
        <font-awesome-icon icon="plus-circle" class="mr-2" />
        Create Week Schedule
      </button>
      <div class="text-gray-600 dark:text-gray-400 italic">
        Total Schedules: {{ weekSchedules.length }}
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <loading-wheel v-if="isLoading" />
        <table v-else class="w-full table-auto">
          <thead>
            <tr class="bg-indigo-600 text-white text-sm leading-normal">
              <th class="py-3 px-6 text-left">Week Schedule</th>
              <th class="py-3 px-6 text-center">Status</th>
              <th class="py-3 px-6 text-center">Actions</th>
              <th v-for="day in ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']" :key="day" class="py-3 px-6 text-center">
                {{ day }}
              </th>
            </tr>
          </thead>
          <tbody class="text-gray-600 dark:text-gray-200 text-sm font-light">
            <tr
              v-for="weekSchedule in weekSchedules"
              :key="weekSchedule.id"
              class="border-b border-gray-200 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900 transition duration-300"
            >
              <td class="py-3 px-6 text-left whitespace-nowrap font-medium">
                {{ weekSchedule.mode_name }}
              </td>
              <td class="py-3 px-6 text-center">
                <span
                  :class="{
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': weekSchedule.status === 'active',
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': weekSchedule.status === 'inactive',
                  }"
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                >
                  {{ weekSchedule.status }}
                </span>
              </td>
              <td class="py-3 px-6 text-center">
                <div class="flex justify-center space-x-2">
                  <button
                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white font-bold py-2 px-3 rounded-lg transition duration-300"
                    @click="openEditModal(weekSchedule)"
                    title="Edit"
                  >
                    <font-awesome-icon icon="edit" />
                  </button>
                  <button
                    class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white font-bold py-2 px-3 rounded-lg transition duration-300"
                    @click="openDeleteConfirmation(weekSchedule)"
                    title="Delete"
                  >
                    <font-awesome-icon icon="trash" />
                  </button>
                </div>
              </td>
              <td v-for="day in ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']" :key="day" class="py-3 px-6 text-center">
                <button
                  class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-300"
                  @click="openAssignModal(weekSchedule, day)"
                >
                  Assign
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Week Schedule Modal -->
    <overlay v-if="showCreateModal" @close="closeCreateModal">
      <modal
        :show="showCreateModal"
        title="Create Week Schedule"
        @close="closeCreateModal"
      >
        <create-week-schedule-form
          @created="fetchWeekSchedules"
          @close="closeCreateModal"
        />
      </modal>
    </overlay>

    <!-- Edit Week Schedule Modal -->
    <overlay v-if="showEditModal" @close="closeEditModal">
      <modal
        :show="showEditModal"
        title="Edit Week Schedule"
        @close="closeEditModal"
      >
        <edit-week-schedule-form
          :weekSchedule="selectedWeekSchedule"
          @updated="fetchWeekSchedules"
          @close="closeEditModal"
        />
      </modal>
    </overlay>

    <!-- Assign Daily Meals Modal -->
    <overlay v-if="showAssignModal" @close="closeAssignModal">
      <modal
        :show="showAssignModal"
        :title="`Assign Daily Meals for ${selectedDay}`"
        @close="closeAssignModal"
      >
        <week-schedule-form
          :weekScheduleId="selectedWeekSchedule.id"
          :day="selectedDay"
          @assign="handleAssignDailyMeals"
          @detach="handleDetachDailyMeal"
        />
      </modal>
    </overlay>

    <!-- Delete Confirmation Modal -->
    <overlay v-if="showDeleteConfirmation" @close="closeDeleteConfirmation">
      <modal
        :show="showDeleteConfirmation"
        title="Delete Week Schedule"
        @close="closeDeleteConfirmation"
      >
        <div class="text-center">
          <font-awesome-icon icon="exclamation-triangle" class="text-5xl text-yellow-500 mb-4" />
          <h3 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-200">Confirm Deletion</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6">
            Are you sure you want to delete <span class="font-semibold">{{ selectedWeekSchedule?.mode_name }}</span>? This action cannot be undone.
          </p>
          <div class="flex justify-center space-x-4">
            <button 
              @click="handleDeleteWeekSchedule"
              class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300"
            >
              Delete
            </button>
            <button 
              @click="closeDeleteConfirmation"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition duration-300"
            >
              Cancel
            </button>
          </div>
        </div>
      </modal>
    </overlay>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import WeekScheduleForm from './WeekScheduleForm.vue'
import CreateWeekScheduleForm from './CreateWeekScheduleForm.vue'
import EditWeekScheduleForm from './EditWeekScheduleForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'
import LoadingWheel from '@/components/shared/LoadingWheel.vue'


export default {
  components: {
    WeekScheduleForm,
    CreateWeekScheduleForm,
    EditWeekScheduleForm,
    Modal,
    Overlay,
    LoadingWheel
  },
  data() {
    return {
      showAssignModal: false,
      showCreateModal: false,
      showEditModal: false,
      showDeleteConfirmation: false,
      selectedWeekSchedule: null,
      selectedDay: null,
      isLoading: true
    }
  },
  computed: {
    ...mapGetters('weekSchedule', ['weekSchedules']),
  },
  created() {
    this.fetchWeekSchedules(),
    this.loadWeekSchedules()
  },
  methods: {
    ...mapActions('weekSchedule', [
      'fetchWeekSchedules',
      'assignDailyMeals',
      'detachDailyMeal',
      'createWeekSchedule',
      'updateWeekSchedule',
      'deleteWeekSchedule',
    ]),
    openAssignModal(weekSchedule, day) {
      this.selectedWeekSchedule = weekSchedule
      this.selectedDay = day
      this.showAssignModal = true
    },
    closeAssignModal() {
      this.showAssignModal = false
      this.selectedWeekSchedule = null
      this.selectedDay = null
    },
    openCreateModal() {
      this.showCreateModal = true
    },
    closeCreateModal() {
      this.showCreateModal = false
    },
    openEditModal(weekSchedule) {
      this.selectedWeekSchedule = weekSchedule
      this.showEditModal = true
    },
    closeEditModal() {
      this.showEditModal = false
      this.selectedWeekSchedule = null
    },
    openDeleteConfirmation(weekSchedule) {
      this.selectedWeekSchedule = { ...weekSchedule }
      this.showDeleteConfirmation = true
    },
    closeDeleteConfirmation() {
      this.showDeleteConfirmation = false
      this.selectedWeekSchedule = null
    },
    handleAssignDailyMeals(dailyMealData) {
      this.assignDailyMeals({
        weekScheduleId: this.selectedWeekSchedule.id,
        day: this.selectedDay,
        dailyMealData,
      })
        .then(() => {
          this.fetchWeekSchedules()
          // this.closeAssignModal()
        })
        .catch((error) => {
          console.error('Error assigning daily meals:', error)
          // Handle error if needed
        })
    },
    handleDetachDailyMeal(dailyMealId) {
      this.detachDailyMeal({
        weekScheduleId: this.selectedWeekSchedule.id,
        day: this.selectedDay,
        dailyMealId,
      })
        .then(() => {
          this.fetchWeekSchedules()
          // this.closeAssignModal()
        })
        .catch((error) => {
          console.error('Error detaching daily meal:', error)
          // Handle error if needed
        })
    },
    handleDeleteWeekSchedule() {
      this.deleteWeekSchedule(this.selectedWeekSchedule.id)
        .then(() => {
          this.fetchWeekSchedules()
          this.closeDeleteConfirmation()
          // this.loadWeekSchedules()
        })
        .catch((error) => {
          console.error('Error deleting week schedule:', error)
          // Handle error if needed
        })
    },
    async loadWeekSchedules() {
      this.isLoading = true
      try {
        await this.fetchWeekSchedules()
      } catch (error) {
        console.error('Error fetching week schedules:', error)
      } finally {
        this.isLoading = false
      }
    },
  },
}
</script>
