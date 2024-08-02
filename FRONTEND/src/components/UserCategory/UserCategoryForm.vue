<template>
  <form @submit.prevent="submitForm" class="space-y-8">
    <div class="relative group">
      <input
        type="text"
        id="name"
        v-model="localCategory.name"
        required
        class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 transition-all duration-300 text-gray-800 dark:text-gray-200 peer"
        placeholder=" "
      />
      <label
        for="name"
        class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 peer-focus:text-purple-600 dark:peer-focus:text-purple-400"
      >
        Nom
      </label>
      <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name[0] }}</p>

    </div>

    <div class="relative group">
      <textarea
        id="description"
        v-model="localCategory.description"
        class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border-2 border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 transition-all duration-300 text-gray-800 dark:text-gray-200 peer resize-none h-32"
        placeholder=" "
      ></textarea>
      <label
        for="description"
        class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 transition-all duration-300 transform -translate-y-6 scale-75 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 peer-focus:text-purple-600 dark:peer-focus:text-purple-400"
      >
        Description (optionnelle)
      </label>
      <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description[0] }}</p>
    </div>

    <button
      type="submit"
      class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-800"
    >
      {{ isEditMode ? 'Modifier' : 'Créer' }}
    </button>
  </form>
</template>

<script>
export default {
  props: {
    category: {
      type: Object,
      required: true,
    },
    errors: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      isEditMode: false,
      localCategory: { ...this.category },
    };
  },
  created() {
    this.isEditMode = !!this.category.id;
  },
  methods: {
    submitForm() {
      if (this.validateForm()) {
        this.$emit('submit', this.localCategory);
      }
    },
    validateForm() {
      if (!this.localCategory.name.trim()) {
        this.$emit('update:errors', { name: ['Le nom est requis.'] });
        return false;
      }
      return true;
    }
  },

  watch: {
  'localCategory.name': function() {
    if (this.errors.name) {
      this.$emit('update:errors', { ...this.errors, name: null });
    }
  }
},

};
</script>
