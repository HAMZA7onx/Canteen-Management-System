<template>
    <div class="">
      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="relative">
          <label for="name" class="block text-gray-700 font-medium mb-1">Name</label>
          <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
            <input
              type="text"
              id="name"
              v-model="mealName.name"
              required
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            />
            <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
              </svg>
            </div>
          </div>
        </div>
  
        <div class="relative">
          <label for="description" class="block text-gray-700 font-medium mb-1">Description</label>
          <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 transition ease-in-out duration-150">
            <textarea
              id="description"
              v-model="mealName.description"
              class="w-full px-4 py-2 rounded-lg focus:outline-none"
            ></textarea>
            <div class="px-3 py-2 bg-gray-100 rounded-r-lg">
              <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                />
              </svg>
            </div>
          </div>
        </div>
  
        <div class="flex justify-end">
          <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition ease-in-out duration-150"
          >
            {{ isEditMode ? 'Update' : 'Create' }}
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    name: 'MealNameForm',
    props: {
      mealName: {
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
      this.isEditMode = !!this.mealName.id;
    },
    methods: {
      submitForm() {
        const formData = {
          id: this.mealName.id,
          name: this.mealName.name,
          description: this.mealName.description,
        };
  
        if (this.isEditMode) {
          this.$emit('update:mealName', formData);
        } else {
          this.$emit('update:mealName', formData);
        }
      },
    },
  };
  </script>
