<template>
  <div :class="[
    'p-6 rounded-lg',
    darkMode ? 'bg-gray-800 text-white' : 'bg-white text-gray-800'
  ]">
    <div class="mb-4">
      <label for="modeName" :class="[
        'block text-sm font-medium',
        darkMode ? 'text-gray-300' : 'text-gray-700'
      ]">Mode Name</label>
      <input
        id="modeName"
        v-model="modeName"
        type="text"
        required
        :class="[
          'mt-1 block w-full py-2 px-3 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
          darkMode 
            ? 'bg-gray-700 border-gray-600 text-white' 
            : 'bg-white border-gray-300 text-gray-900'
        ]"
      />
    </div>

    <div class="mb-4">
      <label for="description" :class="[
        'block text-sm font-medium',
        darkMode ? 'text-gray-300' : 'text-gray-700'
      ]">Description</label>
      <textarea
        id="description"
        v-model="description"
        rows="3"
        :class="[
          'mt-1 block w-full py-2 px-3 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
          darkMode 
            ? 'bg-gray-700 border-gray-600 text-white' 
            : 'bg-white border-gray-300 text-gray-900'
        ]"
      ></textarea>
    </div>

    <div class="mb-4">
      <label for="status" :class="[
        'block text-sm font-medium',
        darkMode ? 'text-gray-300' : 'text-gray-700'
      ]">Status</label>
      <select
        id="status"
        v-model="status"
        :class="[
          'mt-1 block w-full py-2 px-3 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
          darkMode 
            ? 'bg-gray-700 border-gray-600 text-white' 
            : 'bg-white border-gray-300 text-gray-900'
        ]"
      >
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
    </div>


    <div class="mb-4">
      <h3 :class="[
        'text-md font-medium leading-6',
        darkMode ? 'text-gray-200' : 'text-gray-900'
      ]">L'email d'utilisateur qui creer le calendrier:</h3>
      <div :class="[
        'mt-2 text-sm',
        darkMode ? 'text-gray-400' : 'text-gray-500'
      ]">{{ creatorEmail }}</div>
    </div>
    
    <div class="mb-4">
      <h3 :class="[
        'text-md font-medium leading-6',
        darkMode ? 'text-gray-200' : 'text-gray-900'
      ]">L'email des utilisateurs qui modifier le calendrier:</h3>
      <div v-if="editors.length === 0" :class="[
        'mt-2 text-sm',
        darkMode ? 'text-gray-400' : 'text-gray-500'
      ]">
        No editors assigned.
      </div>
      <div v-else class="mt-2">
        <ul role="list" :class="[
          'divide-y',
          darkMode ? 'divide-gray-700' : 'divide-gray-200'
        ]">
          <li v-for="(email, index) in editors" :key="index" class="py-2">
            <div :class="[
              'text-sm',
              darkMode ? 'text-gray-400' : 'text-gray-500'
            ]">{{ email }}</div>
          </li>
        </ul>
      </div>
    </div>

    <div class="flex justify-end">
      <button
        type="button"
        :class="[
          'font-bold py-2 px-4 rounded',
          darkMode
            ? 'bg-blue-600 hover:bg-blue-700 text-white'
            : 'bg-blue-500 hover:bg-blue-700 text-white'
        ]"
        @click="updateWeekSchedule"
      >
        Update
      </button>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  props: {
    weekSchedule: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      modeName: this.weekSchedule.mode_name,
      description: this.weekSchedule.description,
      status: this.weekSchedule.status,
      creatorEmail: this.weekSchedule.creator,
      editors: this.weekSchedule.editors || [],
    }
  },
  computed: {
    ...mapGetters('weekSchedule', ['activeWeekSchedule']),
    hasActiveSchedule() {
      return this.activeWeekSchedule && this.activeWeekSchedule.id !== this.weekSchedule?.id
    }
  },
  created() {
    console.log('creatorEmail:', this.creatorEmail)
    console.log('editors:', this.editors)
  },
  methods: {
    ...mapActions('weekSchedule', ['updateWeekSchedule']),
    updateWeekSchedule() {
      try {
        const updatedWeekScheduleData = {
          mode_name: this.modeName,
          description: this.description,
          status: this.status,
        }

        this.$store.dispatch('weekSchedule/updateWeekSchedule', { id: this.weekSchedule.id, data: updatedWeekScheduleData })
          .then(() => {
            this.$emit('updated')
            this.$emit('close')
          })
          .catch((error) => {
            console.error('Error updating week schedule:', error)
            // Handle error if needed
          })
      } catch (error) {
        console.error('Error updating week schedule:', error)
        // Handle error if needed
      }
    }
  },
}
</script>
