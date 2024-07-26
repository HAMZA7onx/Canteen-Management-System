<template>
  <div class="container mx-auto px-4 py-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-indigo-900 min-h-screen">
    <div class="mb-8 text-center">
      <h1 class="text-4xl font-extrabold text-indigo-700 dark:text-indigo-300 mb-2">Horaires de la semaine</h1>
      <p class="text-lg text-gray-600 dark:text-gray-400">Gérez et organisez efficacement vos plans de repas hebdomadaires</p>
    </div>

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
      <button
        v-if="$can('creer_profils_repas')"
        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-300 ease-in-out flex items-center mb-4 sm:mb-0"
        @click="openCreateModal"
      >
        <font-awesome-icon icon="plus-circle" class="mr-2" />
        Créer un programme
      </button>
      <div class="text-gray-600 dark:text-gray-400 italic">
        Total Schedules: {{ weekSchedules.length }}
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <loading-wheel v-if="isLoading" />
        <div v-else>
          <!-- Desktop view -->
          <table class="w-full table-auto hidden sm:table">
            <thead>
              <tr class="bg-indigo-600 text-white text-sm leading-normal">
                <th class="py-3 px-6 text-left">Week Schedule</th>
                <th class="py-3 px-6 text-center">Status</th>
                <th class="py-3 px-6 text-center">Actions</th>
                <th v-for="day in ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']" :key="day" class="py-3 px-6 text-center">
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
                <td class="py-3 px-6 text-left whitespace-nowrap font-medium ">
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
                      v-if="$can('modifier_profils_repas')"
                      class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white font-bold py-2 px-3 rounded-lg transition duration-300"
                      @click="openEditModal(weekSchedule)"
                      title="Edit"
                    >
                      <font-awesome-icon icon="edit" />
                    </button>
                    <button
                      v-if="$can('supprimer_profils_repas')"
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
                    v-if="$can('assigner_repas')"
                    class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-300"
                    @click="openAssignModal(weekSchedule, day)"
                  >
                    Attribuer
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Mobile view -->
          <div class="sm:hidden">
            <div v-for="weekSchedule in weekSchedules" :key="weekSchedule.id" class="mb-4 border-b border-gray-200 dark:border-gray-700">
              <div class="flex justify-between items-center p-4">
                <span class="font-medium dark:text-gray-100">{{ weekSchedule.mode_name }}</span>
                <span
                  :class="{
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': weekSchedule.status === 'active',
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': weekSchedule.status === 'inactive',
                  }"
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                >
                  {{ weekSchedule.status }}
                </span>
                <button
                  @click="toggleExpand(weekSchedule.id)"
                  class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200"
                >
                  <font-awesome-icon :icon="expandedRows.includes(weekSchedule.id) ? 'chevron-up' : 'chevron-down'" />
                </button>
              </div>
              <div v-if="expandedRows.includes(weekSchedule.id)" class="p-4 bg-gray-50 dark:bg-gray-800">
                <div class="flex justify-center space-x-2 mb-4">
                  <button
                    v-if="$can('modifier_profils_repas')"
                    class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white font-bold py-2 px-3 rounded-lg transition duration-300"
                    @click="openEditModal(weekSchedule)"
                  >
                    <font-awesome-icon icon="edit" /> Edit
                  </button>
                  <button
                    v-if="$can('supprimer_profils_repas')"
                    class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white font-bold py-2 px-3 rounded-lg transition duration-300"
                    @click="openDeleteConfirmation(weekSchedule)"
                  >
                    <font-awesome-icon icon="trash" /> Delete
                  </button>
                </div>
                <div class="grid grid-cols-7 gap-2">
                  <button
                    v-if="$can('assigner_repas')"
                    v-for="(day, index) in ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']"
                    :key="day"
                    class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold py-2 px-2 rounded-lg shadow-md hover:shadow-lg transition duration-300 text-xs"
                    @click="openAssignModal(weekSchedule, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'][index])"
                  >
                    {{ day }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
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

    <overlay v-if="showEditModal" @close="closeEditModal">
      <modal
        :show="showEditModal"
        title="Modifier le programme hebdomadaire"
        @close="closeEditModal"
      >
        <edit-week-schedule-form
          :weekSchedule="selectedWeekSchedule"
          @updated="fetchWeekSchedules"
          @close="closeEditModal"
        />
      </modal>
    </overlay>

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
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import WeekScheduleForm from './WeekScheduleForm.vue'
import CreateWeekScheduleForm from './CreateWeekScheduleForm.vue'
import EditWeekScheduleForm from './EditWeekScheduleForm.vue'
import Modal from '@/components/shared/Modal.vue'
import Overlay from '@/components/shared/Overlay.vue'
import LoadingWheel from '@/components/shared/LoadingWheel.vue'
import permissionMixin from '@/mixins/permissionMixin';

export default {
  mixins: [permissionMixin],
  components: {
    WeekScheduleForm,
    CreateWeekScheduleForm,
    EditWeekScheduleForm,
    Modal,
    Overlay,
    LoadingWheel
  },
  setup() {
    const store = useStore()
    const showAssignModal = ref(false)
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const showDeleteConfirmation = ref(false)
    const selectedWeekSchedule = ref(null)
    const selectedDay = ref(null)
    const isLoading = ref(true)
    const expandedRows = ref([])

    const weekSchedules = computed(() => store.getters['weekSchedule/weekSchedules'])

    const fetchWeekSchedules = () => store.dispatch('weekSchedule/fetchWeekSchedules')
    const assignDailyMeals = (payload) => store.dispatch('weekSchedule/assignDailyMeals', payload)
    const detachDailyMeal = (payload) => store.dispatch('weekSchedule/detachDailyMeal', payload)
    const deleteWeekSchedule = (id) => store.dispatch('weekSchedule/deleteWeekSchedule', id)

    const openAssignModal = (weekSchedule, day) => {
      selectedWeekSchedule.value = weekSchedule
      selectedDay.value = day
      showAssignModal.value = true
    }

    const closeAssignModal = () => {
      showAssignModal.value = false
      selectedWeekSchedule.value = null
      selectedDay.value = null
    }

    const openCreateModal = () => {
      showCreateModal.value = true
    }

    const closeCreateModal = () => {
      showCreateModal.value = false
    }

    const openEditModal = (weekSchedule) => {
      selectedWeekSchedule.value = weekSchedule
      showEditModal.value = true
    }

    const closeEditModal = () => {
      showEditModal.value = false
      selectedWeekSchedule.value = null
    }

    const openDeleteConfirmation = (weekSchedule) => {
      selectedWeekSchedule.value = { ...weekSchedule }
      showDeleteConfirmation.value = true
    }

    const closeDeleteConfirmation = () => {
      showDeleteConfirmation.value = false
      selectedWeekSchedule.value = null
    }

    const handleAssignDailyMeals = (dailyMealData) => {
      assignDailyMeals({
        weekScheduleId: selectedWeekSchedule.value.id,
        day: selectedDay.value,
        dailyMealData,
      })
        .then(() => {
          fetchWeekSchedules()
        })
        .catch((error) => {
          console.error('Error assigning daily meals:', error)
        })
    }

    const handleDetachDailyMeal = (dailyMealId) => {
      detachDailyMeal({
        weekScheduleId: selectedWeekSchedule.value.id,
        day: selectedDay.value,
        dailyMealId,
      })
        .then(() => {
          fetchWeekSchedules()
        })
        .catch((error) => {
          console.error('Error detaching daily meal:', error)
        })
    }

    const handleDeleteWeekSchedule = () => {
      deleteWeekSchedule(selectedWeekSchedule.value.id)
        .then(() => {
          fetchWeekSchedules()
          closeDeleteConfirmation()
        })
        .catch((error) => {
          console.error('Error deleting week schedule:', error)
        })
    }

    const loadWeekSchedules = async () => {
      isLoading.value = true
      try {
        await fetchWeekSchedules()
      } catch (error) {
        console.error('Error fetching week schedules:', error)
      } finally {
        isLoading.value = false
      }
    }

    const toggleExpand = (weekScheduleId) => {
      const index = expandedRows.value.indexOf(weekScheduleId)
      if (index === -1) {
        expandedRows.value.push(weekScheduleId)
      } else {
        expandedRows.value.splice(index, 1)
      }
    }

    onMounted(() => {
      fetchWeekSchedules()
      loadWeekSchedules()
    })
 
    return {
      showAssignModal,
      showCreateModal,
      showEditModal,
      showDeleteConfirmation,
      selectedWeekSchedule,
      selectedDay,
      isLoading,
      expandedRows,
      weekSchedules,
      openAssignModal,
      closeAssignModal,
      openCreateModal,
      closeCreateModal,
      openEditModal,
      closeEditModal,
      openDeleteConfirmation,
      closeDeleteConfirmation,
      handleAssignDailyMeals,
      handleDetachDailyMeal,
      handleDeleteWeekSchedule,
      toggleExpand,
    }
  }
}
</script>

<style scoped> 
@media (max-width: 640px) {
  .container {
    padding-left: 1rem;
    padding-right: 1rem;
  }
}

/* Add any additional custom styles here */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
