<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 space-y-6">
      <div class="space-y-4">
        <div class="relative">
          <input
            id="modeName"
            v-model="modeName"
            type="text"
            required
            class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition duration-300 placeholder-transparent"
            placeholder="Mode Name"
          />
          <label for="modeName" class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-blue-500 dark:peer-focus:text-blue-400">
            Nom de profile repas
          </label>
        </div>

        <div class="relative">
          <textarea
            id="description"
            v-model="description"
            rows="4"
            class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition duration-300 placeholder-transparent resize-none"
            placeholder="Description"
          ></textarea>
          <label for="description" class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-blue-500 dark:peer-focus:text-blue-400">
            Description
          </label>
        </div>

        <div class="relative">
          <select
            id="status"
            v-model="status"
            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition duration-300"
          >
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
          <label for="status" class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400">
            Status
          </label>
        </div>
      </div>

      <div class="space-y-4">
        <div>
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">E-mail du créateur:</h3>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ creatorEmail }}</p>
        </div>

        <div>
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Éditeurs:</h3>
          <div v-if="editors.length === 0" class="mt-1 text-sm text-gray-600 dark:text-gray-400 italic">
            Aucun éditeur assigné.
          </div>
          <ul v-else class="mt-2 space-y-2">
            <li v-for="(email, index) in editors" :key="index" class="text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-md px-3 py-2">
              {{ email }}
            </li>
          </ul>
        </div>
      </div>

      <div class="flex justify-end">
        <button
          type="button"
          @click="updateWeekSchedule"
          class="px-6 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-400 dark:to-indigo-500 text-white hover:from-blue-600 hover:to-indigo-700 dark:hover:from-blue-500 dark:hover:to-indigo-600 transition duration-300"
        >
          Modifier
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
