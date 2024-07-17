<template>
  <div class="bg-gradient-to-br from-blue-400 to-indigo-500 dark:from-blue-800 dark:to-indigo-900 p-1 rounded-2xl shadow-xl">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 space-y-6">
      <h2 class="text-3xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-300 dark:to-indigo-400">
        Create Week Schedule
      </h2>

      <div class="space-y-4">
        <div class="relative">
          <input
            id="modeName"
            v-model="modeName"
            type="text"
            required
            class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition duration-300 placeholder-transparent"
            placeholder="Mode Name"
          />
          <label
            for="modeName"
            class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-blue-500 dark:peer-focus:text-blue-400"
          >
            Mode Name
          </label>
        </div>

        <div class="relative">
          <textarea
            id="description"
            v-model="description"
            rows="4"
            class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition duration-300 placeholder-transparent resize-none"
            placeholder="Description"
          ></textarea>
          <label
            for="description"
            class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-blue-500 dark:peer-focus:text-blue-400"
          >
            Description
          </label>
        </div>

        <div class="relative">
          <select
            id="status"
            v-model="status"
            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-100 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition duration-300"
          >
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
          <label
            for="status"
            class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400"
          >
            Status
          </label>
        </div>
      </div>

      <div class="flex justify-end space-x-4">
        <button
          type="button"
          @click="$emit('close')"
          class="px-6 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition duration-300"
        >
          Cancel
        </button>
        <button
          type="button"
          @click="submitForm"
          class="px-6 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-400 dark:to-indigo-500 text-white hover:from-blue-600 hover:to-indigo-700 dark:hover:from-blue-500 dark:hover:to-indigo-600 transition duration-300"
        >
          Create
        </button>
      </div>
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
    ...mapGetters('weekSchedule', ['activeWeekSchedule']),
    hasActiveSchedule() {
      return this.activeWeekSchedule && this.activeWeekSchedule.id !== this.weekSchedule?.id
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
