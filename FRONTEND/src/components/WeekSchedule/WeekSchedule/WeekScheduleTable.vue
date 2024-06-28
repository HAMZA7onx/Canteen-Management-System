<template>
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">Week Schedules</h2>

    <div class="mb-4">
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="openCreateModal"
      >
        Create Week Schedule
      </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="w-full table-auto">
        <thead>
          <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
            <th class="py-3 px-6 text-left">Week Schedule</th>
            <th class="py-3 px-6 text-center">Status</th>
            <th class="py-3 px-6 text-center">Actions</th>
            <th class="py-3 px-6 text-center">Monday</th>
            <th class="py-3 px-6 text-center">Tuesday</th>
            <th class="py-3 px-6 text-center">Wednesday</th>
            <th class="py-3 px-6 text-center">Thursday</th>
            <th class="py-3 px-6 text-center">Friday</th>
            <th class="py-3 px-6 text-center">Saturday</th>
            <th class="py-3 px-6 text-center">Sunday</th>
          </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
          <tr
            v-for="weekSchedule in weekSchedules"
            :key="weekSchedule.id"
            class="border-b border-gray-200 hover:bg-gray-100"
          >
            <td class="py-3 px-6 text-left whitespace-nowrap">
              {{ weekSchedule.mode_name }}
            </td>
            <td class="py-3 px-6 text-center">
              <span
                :class="{
                  'bg-green-100 text-green-800 px-2 py-1 rounded-full': weekSchedule.status === 'active',
                  'bg-red-100 text-red-800 px-2 py-1 rounded-full': weekSchedule.status === 'inactive',
                }"
              >
                {{ weekSchedule.status }}
              </span>
            </td>
            <td class="py-3 px-6 text-center">
              <div class="flex justify-center space-x-2">
                <button
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                  @click="openEditModal(weekSchedule)"
                >
                  Edit
                </button>
                <button
                  class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                  @click="deleteWeekSchedule(weekSchedule)"
                >
                  Delete
                </button>
              </div>
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                @click="openAssignModal(weekSchedule, 'monday')"
              >
                Assign
              </button>
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                @click="openAssignModal(weekSchedule, 'tuesday')"
              >
                Assign
              </button>
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                @click="openAssignModal(weekSchedule, 'wednesday')"
              >
                Assign
              </button>
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                @click="openAssignModal(weekSchedule, 'thursday')"
              >
                Assign
              </button>
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                @click="openAssignModal(weekSchedule, 'friday')"
              >
                Assign
              </button>
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                @click="openAssignModal(weekSchedule, 'saturday')"
              >
                Assign
              </button>
            </td>
            <td class="py-3 px-6 text-center">
              <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded"
                @click="openAssignModal(weekSchedule, 'sunday')"
              >
                Assign
              </button>
            </td>
          </tr>
        </tbody>
      </table>
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
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import WeekScheduleForm from './WeekScheduleForm.vue'
import CreateWeekScheduleForm from './CreateWeekScheduleForm.vue'
import EditWeekScheduleForm from './EditWeekScheduleForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'

export default {
  components: {
    WeekScheduleForm,
    CreateWeekScheduleForm,
    EditWeekScheduleForm,
    Modal,
    Overlay,
  },
  data() {
    return {
      showAssignModal: false,
      showCreateModal: false,
      showEditModal: false,
      selectedWeekSchedule: null,
      selectedDay: null,
    }
  },
  computed: {
    ...mapGetters('weekSchedule', ['weekSchedules']),
  },
  created() {
    this.fetchWeekSchedules()
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
    handleAssignDailyMeals(dailyMealData) {
      this.assignDailyMeals({
        weekScheduleId: this.selectedWeekSchedule.id,
        day: this.selectedDay,
        dailyMealData,
      })
        .then(() => {
          this.fetchWeekSchedules()
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
        })
        .catch((error) => {
          console.error('Error detaching daily meal:', error)
          // Handle error if needed
        })
    },
    deleteWeekSchedule(weekSchedule) {
      this.deleteWeekSchedule(weekSchedule.id)
        .then(() => {
          this.fetchWeekSchedules()
        })
        .catch((error) => {
          console.error('Error deleting week schedule:', error)
          // Handle error if needed
        })
    },
  },
}
</script>
