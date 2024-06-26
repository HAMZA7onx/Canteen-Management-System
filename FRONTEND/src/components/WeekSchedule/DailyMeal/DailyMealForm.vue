<template>
  <form @submit.prevent="submitForm" class="space-y-6">
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">
        Name
      </label>
      <div class="mt-1">
        <input
          id="name"
          v-model="dailyMeal.name"
          type="text"
          required
          class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        />
      </div>
    </div>

    <div>
      <label for="description" class="block text-sm font-medium text-gray-700">
        Description
      </label>
      <div class="mt-1">
        <textarea
          id="description"
          v-model="dailyMeal.description"
          rows="3"
          class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        />
      </div>
    </div>

    <div class="pt-5">
      <div class="flex justify-end">
        <button
          type="button"
          class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          @click="$emit('close')"
        >
          Cancel
        </button>
        <button
          type="submit"
          class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          {{ isEditMode ? 'Update' : 'Create' }}
        </button>
      </div>
    </div>
  </form>
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

      if (this.isEditMode) {
        this.$emit('update', formData)
      } else {
        this.$emit('create', formData)
      }
    }
  }
}
</script>
