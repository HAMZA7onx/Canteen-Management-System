<template>
    <div>
      <div class="mb-4">
        <label for="modeName" class="block text-sm font-medium text-gray-700">Mode Name</label>
        <input
          id="modeName"
          v-model="modeName"
          type="text"
          required
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
      </div>
  
      <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea
          id="description"
          v-model="description"
          rows="3"
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        ></textarea>
      </div>
  
      <div class="mb-4">
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select
          id="status"
          v-model="status"
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
  
      <div class="flex justify-end">
        <button
          type="button"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          @click="updateWeekSchedule"
        >
          Update
        </button>
      </div>
    </div>
  </template>
  
  <script>
  import { mapActions } from 'vuex'
  import store from '@/store' // Import the Vuex store instance

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
      }
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

        store.dispatch('weekSchedule/updateWeekSchedule', { id: this.weekSchedule.id, data: updatedWeekScheduleData })
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
  