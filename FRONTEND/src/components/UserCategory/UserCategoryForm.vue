<template>
  <div>
    <form @submit.prevent="submitForm" class="mt-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">
          Name
        </label>
        <div class="mt-1">
          <input
            type="text"
            id="name"
            v-model="category.name"
            required
            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
          />
        </div>
      </div>
      <div class="mt-4">
        <label for="description" class="block text-sm font-medium text-gray-700">
          Description
        </label>
        <div class="mt-1">
          <textarea
            id="description"
            v-model="category.description"
            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
          ></textarea>
        </div>
      </div>
      <div class="mt-4">
        <button
          type="submit"
          class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          {{ isEditMode ? 'Update' : 'Create' }}
        </button>
        <button
          type="button"
          class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          @click="$emit('close')"
        >
          Cancel
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    category: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      isEditMode: false,
    };
  },
  created() {
    this.isEditMode = !!this.category.id;
  },
  methods: {
    submitForm() {
      const formData = {
        name: this.category.name,
        description: this.category.description,
      };

      if (this.isEditMode) {
        this.$emit('submit', { ...this.category, ...formData });
      } else {
        this.$emit('submit', formData);
      }
    },
  },
};
</script>
