<template>
    <form @submit.prevent="submitForm" class="bg-white dark:bg-gray-800 rounded-2xl p-6 space-y-6">
      <h2 class="text-xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600 dark:from-teal-300 dark:to-indigo-400">
        {{ isEditMode ? 'Modifier le composant alimentaire' : 'Nouveau composant alimentaire' }}
      </h2>

      <div class="relative">
        <input
          id="name"
          v-model="foodComposant.name"
          type="text"
          required
          class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-teal-500 dark:focus:border-teal-400 focus:outline-none transition duration-300 placeholder-transparent"
          placeholder="Name"
        />
        <label
          for="name"
          class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-teal-500 dark:peer-focus:text-teal-400"
        >
          Nom
        </label>
      </div>

      <div class="relative">
        <textarea
          id="description"
          v-model="foodComposant.description"
          rows="4"
          class="peer w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200 border-2 border-transparent focus:border-teal-500 dark:focus:border-teal-400 focus:outline-none transition duration-300 placeholder-transparent resize-none"
          placeholder="Description"
        ></textarea>
        <label
          for="description"
          class="absolute left-4 -top-2.5 text-sm text-gray-600 dark:text-gray-400 transition-all duration-300 peer-placeholder-shown:text-base peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-teal-500 dark:peer-focus:text-teal-400"
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
        Annuler
        </button>
        <button
          type="submit"
          class="px-6 py-2 rounded-lg bg-gradient-to-r from-teal-500 to-indigo-600 dark:from-teal-400 dark:to-indigo-500 text-white hover:from-teal-600 hover:to-indigo-700 dark:hover:from-teal-500 dark:hover:to-indigo-600 transition duration-300"
        >
          {{ isEditMode ? 'Modifier' : 'Créer' }}
        </button>
      </div>
    </form>
</template>

<script>
export default {
  props: {
    foodComposant: {
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
    this.isEditMode = !!this.foodComposant.id
  },
  methods: {
    submitForm() {
      const formData = {
        id: this.foodComposant.id,
        name: this.foodComposant.name,
        description: this.foodComposant.description
      }

      if (this.isEditMode) {
        this.$emit('update', formData)
      } else {
        this.$emit('create', formData)
      }
      this.$emit('close')
    }
  }
}
</script>