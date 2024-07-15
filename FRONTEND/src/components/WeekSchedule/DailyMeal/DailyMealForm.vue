<template>
  <div class="bg-gradient-to-br from-orange-400 to-pink-500 dark:from-orange-800 dark:to-pink-900 p-1 rounded-2xl shadow-xl">
    <form @submit.prevent="submitForm" class="bg-white dark:bg-gray-800 rounded-2xl p-6 space-y-6">
      <h2 class="text-3xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-pink-600 dark:from-orange-300 dark:to-pink-400">
        {{ isEditMode ? 'Edit Daily Meal' : 'Create New Daily Meal' }}
      </h2>

      <div class="relative">
        <input
          id="name"
          v-model="dailyMeal.name"
          type="text"
          required
          class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-2 border-transparent focus:border-orange-500 dark:focus:border-orange-400 focus:outline-none transition duration-300 placeholder-transparent"
          placeholder="Name"
        />
        <label
          for="name"
          class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-orange-500 dark:peer-focus:text-orange-400"
        >
          Name
        </label>
      </div>

      <div class="relative">
        <textarea
          id="description"
          v-model="dailyMeal.description"
          rows="4"
          class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 border-2 border-transparent focus:border-orange-500 dark:focus:border-orange-400 focus:outline-none transition duration-300 placeholder-transparent resize-none"
          placeholder="Description"
        ></textarea>
        <label
          for="description"
          class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-orange-500 dark:peer-focus:text-orange-400"
        >
          Description
        </label>
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
          type="submit"
          class="px-6 py-2 rounded-lg bg-gradient-to-r from-orange-500 to-pink-600 dark:from-orange-400 dark:to-pink-500 text-white hover:from-orange-600 hover:to-pink-700 dark:hover:from-orange-500 dark:hover:to-pink-600 transition duration-300"
        >
          {{ isEditMode ? 'Update' : 'Create' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    dailyMeal: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      isEditMode: false
    }
  },
  created() {
    this.isEditMode = !!this.dailyMeal.id
  },
  methods: {
    submitForm() {
      const formData = {
        id: this.dailyMeal.id,
        name: this.dailyMeal.name,
        description: this.dailyMeal.description
      }

      this.$emit(this.isEditMode ? 'update' : 'create', formData)
      this.$emit('close')
    }
  }
}
</script>
