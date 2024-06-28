<template>
    <div>
      <div class="mb-4">
        <label for="modeName" class="block text-sm font-medium text-gray-700"
          >Mode Name</label
        >
        <input
          id="modeName"
          v-model="modeName"
          type="text"
          required
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
      </div>
  
      <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700"
          >Description</label
        >
        <textarea
          id="description"
          v-model="description"
          rows="3"
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        ></textarea>
      </div>
  
      <div class="mb-4">
        <label for="status" class="block text-sm font-medium text-gray-700"
          >Status</label
        >
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
  @click="submitForm"
>
  Create
</button>

      </div>
    </div>
  </template>
  
  <script>
  import { mapActions } from 'vuex'
  
  export default {
    data() {
      return {
        modeName: '',
        description: '',
        status: 'active',
      }
    },
    methods: {
      ...mapActions('weekSchedule', ['createWeekSchedule']),
      submitForm() {
        const weekScheduleData = {
          mode_name: this.modeName,
          description: this.description,
          status: this.status,
        }
  
        this.createWeekSchedule(weekScheduleData)
          .then(() => {
            this.$emit('created')
            this.resetForm()
            this.$emit('close') // Emit the 'close' event
          })
          .catch((error) => {
            console.error('Error creating week schedule:', error)
            // Handle error if needed
          })
      },
      resetForm() {
        this.modeName = ''
        this.description = ''
        this.status = 'active'
      },
    },
  }
  </script>