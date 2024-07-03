<template>
  <div :class="[
    'p-6 rounded-lg shadow-lg',
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

    <div class="flex justify-end">
      <button
        type="button"
        :class="[
          'font-bold py-2 px-4 rounded',
          darkMode
            ? 'bg-blue-600 hover:bg-blue-700 text-white'
            : 'bg-blue-500 hover:bg-blue-700 text-white'
        ]"
        @click="submitForm"
      >
        Create
      </button>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  data() {
    return {
      modeName: '',
      description: '',
      status: 'active',
    }
  },
  computed: {
    ...mapGetters(['darkMode'])
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
